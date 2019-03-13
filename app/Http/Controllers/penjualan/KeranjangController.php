<?php

namespace App\Http\Controllers\penjualan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Base64Url\Base64Url;
use App\Model\Produk;
use App\Model\Keranjang;

class KeranjangController extends Controller
{
    protected $toko_id;
    protected $user_id;
    protected $keranjang;

    public function __construct(){
        $this->toko_id = env('toko_id', 1);
        $this->user_id = env('user_id', 1);
        $this->keranjang = new Keranjang;
    }

    public function index()
    {
        $cart = new Keranjang;
        // return $cart->with('produk.gambar_produk')->get(); //asli

        return $cart->with(['produk.gambar_produk' => function ($query) { $query->where('default', '1'); }])->get();
        // $datakeranjang = $this->keranjang->where('user_id', 7);
        // $dataresult = [];
        // $datakeranjang = $this->keranjang->where('user_id', $this->user_id);
        // if($datakeranjang->count() > 0) {
            // dd($datakeranjang);
            // foreach($datakeranjang as $dc){
            //     $dataresult = [
            //         'user_id'       => $dc->user_id,
            //         'nama_produk'   => $dc->nama_produk,
            //         'produk_id'     => $dc->produk_id,
            //         'kuantitas'     => $dc->kuantitas,
            //         'harga'         => $dc->harga,    
            //         'berat'         => $dc->berat,
            //         'subtotal'      => $dc->subtotal,
            //         'catatan'       => $dc->catatan
            //     ];
            // }
            
        // } else {
        //     // no data here
        // }
    }

    public function create()
    {
        //
    }

    //ambil harga berdasarkan produk id dalam request
    private function getHargaBerat(Request $request, $id){
        $produk         = Produk::find($id);
        $usrstore       = $produk->user_store()->where('user_id', $request->user()->id)->first();
        $refuser        = $usrstore->referrer;
        $getharga       = $produk->harga_produk()->where('user_id', $refuser)->first();
        $isAgenOrMember = $usrstore->user_level == 'agen' ? 'agen' : 'member';
        $result[]       = $isAgenOrMember == 'agen' ? $getharga->harga_agen : $getharga->harga_jual;
        $result[]       = $produk->first()->berat;
        return $result;
    }

    public function store(Request $request)
    {
        $harga = $this->getHargaBerat($request, $request->input('produk_id'))[0];
        $berat = $this->getHargaBerat($request, $request->input('produk_id'))[1];
        $data = [
            'user_id'       => $request->user()->id,
            'produk_id'     => $request->input('produk_id'),
            'kuantitas'     => $request->input('qty'),
            'harga'         => $harga,
            'berat'         => $berat,
            'subberat'      => ($request->input('qty')*$berat),
            'catatan'       => $request->input('catatan'),
            'subtotal'      => ($request->input('qty')*$harga)
        ];

        $existdata = Keranjang::where(['user_id' => $request->user()->id, 'produk_id' => $request->input('produk_id')]);

        //tambahkan quantity jika barang sdh ada sblmnya dlm keranjang
        if($existdata->count() > 0){
            $newqty = $existdata->first()->kuantitas + $request->input('qty');
            $itemupdate = [
                'kuantitas'     => $newqty,
                'harga'         => $harga,
                'berat'         => $berat,
                'subberat'      => ($newqty*$berat),
                'catatan'       => $existdata->first()->catatan.' & '.$request->input('catatan'),
                'subtotal'      => ($newqty*$harga)
            ];
            if($existdata->update($itemupdate)){
                return response()->json([
                    'status'    => 'success',
                    'type'      => 'success',
                    'message'   => 'Data belanja Anda telah diupdate'
                ], 200);
            } else {
                return response()->json([
                    'status'    => 'error',
                    'type'      => 'danger',
                    'message'   => 'Internal Error'
                ], 500);
            }
        } else {
            if ($datacreated = $this->keranjang->create($data)){
                return response()->json([
                    'status'    => 'success',
                    'type'      => 'success',
                    'message'   => 'Produk Anda telah ditambahkan dalam Keranjang'
                ], 201);
            } else {
                 return response()->json([
                    'status'    => 'error',
                    'type'      => 'danger',
                    'message'   => 'Internal Error'
                 ], 500);
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
