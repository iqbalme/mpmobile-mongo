<?php

namespace App\Http\Controllers\penjualan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\model\Order;
use App\model\Toko;
use App\model\Produk;
use App\model\Ongkir;
use App\model\Kurir;
use App\model\Invoice;
use App\model\Keranjang;
use App\model\OrderData;
use App\model\OrderResi;
use App\Model\ServerPulsa;
use App\Model\SPSpamChecker;
use App\Model\ServerPulsaStore;

use Ixudra\Curl\Facades\Curl;
use App\Http\Traits\CommonTrait;
use Illuminate\Support\Facades\DB;

// sisa isi aktifitas order semuanya ke dalam tabel order log

class OrderController extends Controller
{
    use CommonTrait;

    protected $keranjang;
    protected $toko;

    public function __construct(Request $request){
        $token              = $request->route()[2]['token_toko'];
        $toko               = Toko::where('token', $token)->first();
        $user               = $request->user();
        $keranjang          = Keranjang::where('user_id', $user->id);
        $this->keranjang    = $keranjang->count() > 0 ? $keranjang->get() : null;
        $this->toko         = $toko->id;
    }

    public function index()
    {
        
    }

    // terima order
    public function proses(Request $request){
        $order = Order::where('order_number', $request->input('order_number'));
        if($order->count()>0){
            if($order->update(['status_order' => '2'])){
                $response = response([
                    'status'    => 'success',
                    'type'      => 'success',
                    'message'   => 'Order sudah diterima, silakan diproses secepatnya'
                ], 200); 
            } else {
                $response = response([
                    'status'    => 'error',
                    'type'      => 'warning',
                    'message'   => 'Internal Error'
                ], 500); 
            }
        } else {
            $response = response([
                'status'    => 'error',
                'type'      => 'danger',
                'message'   => 'Invalid Order'
            ], 404); 
        }
        return $response;
    }

    // tolak order
    public function reject(Request $request){
        
    }

    public function store(Request $request)
    {
        $keranjang = $this->keranjang;
        if(empty($keranjang)){
            $response = response([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Tidak ada item dalam keranjang belanja'
            ], 404); 
        } else {
            $subtotal = 0;
            $berat = 0;
            foreach($keranjang as $item){
                $subtotal += $item->subtotal;
            }
            $orderx = [
                'user_id'       => $request->user()->id,
                'tanggal_kirim' => null,
                'status_order'  => '0',
                'subtotal'      => $subtotal,
                'toko_id'       => $this->toko,
                'order_number'  => strtoupper(str_random(20))
            ];
            $nama_kurir = Kurir::find($request->input('kurir_id'))->first();
            if($order = Order::create($orderx)){
                foreach($keranjang as $item){
                    $orderdata = new OrderData;
                    $orderdata->produk_id = $item->produk_id;
                    $orderdata->kuantitas = $item->kuantitas;
                    $orderdata->harga = $item->harga;
                    $orderdata->subtotal = $item->subtotal;
                    $orderdata->berat = $item->subberat;
                    $order->order_data()->save($orderdata);
                    $berat += $item->subberat;
                }
                $dataongkir = [
                    'id_asal' => $request->input('id_asal'),
                    'id_tujuan' => $request->input('id_tujuan'),
                    'total_berat' => $berat,
                    'biaya_kirim' => $this->cekongkir($request->input('id_asal'),$request->input('id_tujuan'),$berat,$nama_kurir->nama_kurir,$nama_kurir->tipe_kurir),
                    'kurir_id' => $request->input('kurir_id')
                ];
                $order->ongkir()->create($dataongkir);
                $order->invoice()->create([
                    'invoice_no' => $this->setInvoice($order->created_at)
                ]);
                Keranjang::where('user_id', $request->user()->id)->delete();
                $response = response([
                    'status'    => 'sukses',
                    'type'      => 'success',
                    'message'   => 'Berhasil menambahkan order'
                ], 200); 
            } else {
                $response = response([
                    'status'    => 'error',
                    'type'      => 'danger',
                    'message'   => 'Gagal menambahkan order'
                ], 500); 
            }
        }
        return $response;
    }

    private function cekResi($ordernumber){
        $orderx = Order::where('order_number', strtoupper($ordernumber));
        $resi = OrderResi::where('order_id', $orderx->first()->id);
        $status = ($resi->count() > 0) ? true : false;
        return $status;
    }

    public function setResi(Request $request){
        $ordernumber = strtoupper($request->input('order_number'));
        if($this->cekResi($ordernumber)){
            $response = response([
                'status' => 'error',
                'type'  => 'warning',
                'message' => 'Resi sudah ada, Anda ingin mengubah resi?'
            ], 500);
        } else {
            $order = Order::where('order_number', strtoupper($ordernumber))->first();
            if($resi = $order->order_resi()->create([
                'kurir_id' => $order->ongkir->kurir_id,
                'nomor_resi' => $request->input('nomor_resi')
            ])){
                $order->update(['status_order' => '3']);
                $response = response([
                    'status' => 'success',
                    'type'  => 'info',
                    'message' => 'Set resi berhasil'
                ], 200);
            } else {
                $response = response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Gagal update resi'
                ], 500);
            }
        }
        return $response;
    }

    public function updateResi(Request $request, $order_number){
        $order = Order::where('order_number', strtoupper($order_number))->first();
        $resi = OrderResi::where('order_id', $order->first()->id);
        if($order->count() > 0){
            $data = [
                'kurir_id' => $request->input('kurir_id'),
                'nomor_resi' => $request->input('nomor_resi')
            ];
            $data = array_filter($data, function($value) { return $value !== null; });
            if($resi->first()->update($data)){
                $response = response([
                    'status' => 'success',
                    'type'  => 'info',
                    'message' => 'Update kurir/resi berhasil'
                ], 200);
            } else {
                $response = response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Gagal update resi'
                ], 500);
            }            
        } else {
            $response = response([
                'status' => 'error',
                'type'  => 'warning',
                'message' => 'Order tidak ditemukan, Silakan periksa kembali'
            ], 404);
        }
    }

    public function userstore(Request $request){
        $order = Order::where('user_id', $request->user()->id);
        return $order->first()->user_store()->get();
    }

    public function pay(Request $request){
        // di sini menampilkan beberapa item pembayaran
    }

    public function finish_payment(Request $request){
        $order = Order::where('order_number', $request->input('order_number'));
        if($order->update(['status_order' => '1'])){
            $order->first()->order_payment()->create([
                'payment_method' => $request->input('payment_method'),
                'keterangan' => $request->input('keterangan')
            ]);
            $response = response([
                'status'    => 'success',
                'type'      => 'success',
                'message'   => 'Pembayaran telah sukses diterima, pesanan akan segera diproses oleh penjual'
            ], 200);
        } else {
            $response = response([
                'status'    => 'success',
                'type'      => 'success',
                'message'   => 'Internal Error'
            ], 500);
        }
        return $response;
    }

    public function eorder(Request $request)
    {

    }

    private function getLastCommand(){
        // SPSpamChecker::
    }

    private function setLastCommand(){

    }

}
