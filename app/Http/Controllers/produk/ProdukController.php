<?php

namespace App\Http\Controllers\produk;

use Log;

use JWTAuth as auth;

use App\User;
use App\model\Toko;
use App\model\Produk;
use App\model\UserStore;
use App\model\GambarProduk;
use App\model\ProdukKlik;
use App\model\HargaProduk;
use App\model\ProdukAttribut;
use App\model\ProdukAttributDetails;
use Illuminate\Http\Request;
// use App\Http\Requests\ProdukRequest;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    protected $token;
    protected $user_id;
    protected $produkCount = 0;

    public function __construct(Request $request){
        $this->token = $request->route()[2]['token_toko'];
        $this->user_id = $request->user()->id;
    }
    
    public function getprodukrating($rating, $id){
        $data = $rating->where('produk_id', $id);
        return $data->avg('rating');
    }

    private function getProductData($request){
        $user_id = $this->user_id;
        $token = $this->token;
        $owner = Toko::where('token', $token)->first()->user_store->where('user_level', 'owner')->first();
        $toko_id = Toko::where('token', $token)->first()->id;
        $productToHide = [];
        $extra = '';
        if(($request->input('limit')) && ($request->input('offset'))){
            $produk = Produk::whereIn('id', $request->list_produkid)->limit($request->input('limit'))->offset($request->input('offset'))->get();
        } elseif ($request->input('limit')){
            $produk = Produk::whereIn('id', $request->list_produkid)->limit($request->input('limit'))->get();
        } else {
            $produk = Produk::whereIn('id', $request->list_produkid)->get();
        }        
        $produk_array = $produk->toArray();

        foreach($produk as $key => $product){
            $produk_array[$key]['isFavorit'] = $product->produk_favorit()->where('user_id', $user_id)->where('store_id', $toko_id)->first() == null ? false : true;
            $produk_array[$key]['gambar'] = $product->gambar_produk;
            foreach($product->gambar_produk as $gbrprdk){
                if($gbrprdk->default==1){
                    $produk_array[$key]['default_image'] = $gbrprdk->image;
                }
            }
            $cekuserstore = $product->user_store->where('user_id', $user_id);
            if($cekuserstore->count() > 0){
                $user_store = $product->user_store->where('user_id', $user_id)->first();
                $harga = $user_store->user_level == 'owner' ? HargaProduk::where(['produk_id' => $product->id, 'user_id' => $user_id])->get()->toArray() :
                HargaProduk::where(['produk_id' => $product->id, 'user_id' => $user_store->referrer])->get()->toArray();
                if ($user_store->user_level == 'agen'){
                    // tampilkan harga agen dan harga jual saja
                    // hide harga modal
                    $harga[0]['harga_modal'] = null;
                } elseif ($user_store->user_level == 'member') {
                    // tampilkan harga jual saja
                    // hide harga modal dan harga agen
                    $harga[0]['harga_modal'] = null;
                    $harga[0]['harga_agen'] = null;
                } 
            } else {
                $refrr = $product->user_store->where('user_id', $owner->user_id)->first()->referrer;
                $harga = HargaProduk::where(['produk_id' => $produk[$key]->id, 'user_id' => $refrr])->get()->toArray();
                if(count($harga) == 0){
                    $harga[0]['harga_jual'] = null;
                }
                $harga[0]['harga_modal'] = null;
                $harga[0]['harga_agen'] = null;
            }
            if(empty($harga[0]['harga_jual'])){
                $productToHide[] = $key; //inisiasi produk yang disembunyikan utk harga 0 atau belum disetting
            }                      
            // $produk_array[$key]['harga'] = $harga;
            $produk_array[$key]['harga']['harga_jual'] = $harga[0]['harga_jual'];
            $produk_array[$key]['harga']['harga_agen'] = $harga[0]['harga_agen'];
            $produk_array[$key]['harga']['harga_modal'] = $harga[0]['harga_modal'];
            $produk_array[$key]['dilihat'] = $product->produk_klik->jumlah;
            $jumlahterjual = $product->v_produk_jumlah->where('status_order', '5');
            $produk_array[$key]['terjual'] = $jumlahterjual->count() > 0 ? $product->v_produk_jumlah->where('status_order', '5')->first()->totalkuantitas : 0;
            $produk_array[$key]['nama_kategori'] = $product->kategori->nama_kategori;
        }

        // keluarkan/sembunyikan produk yang harganya 0
        for($i=(count($productToHide)); $i>0; $i--){
            unset($produk_array[$productToHide[$i-1]]);
        }

        $this->produkCount = count($produk_array);

        if(count($produk_array) > 0){
            // return $produk_array;
            return response()->json([
                'status'    => 'success',
                'type'      => 'success',
                'count'     => count($produk_array),
                'data'      => $produk_array
            ], 200);
        } else {
            return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'count'     => count($produk_array),
                'message'   => "Tidak ada produk untuk ditampilkan"
            ], 404);
        }
    }

    private function getProductDetails($request){
        $user_id = $this->user_id;
        $token = $this->token;
        $owner = Toko::where('token', $token)->first()->user_store->where('user_level', 'owner')->first();
        $productToHide = [];
        $produk = Produk::find($request->input('produk_id'));
        $produk_array = $produk->toArray();

        $produk_array['gambar'] = $produk->gambar_produk;
        foreach($produk->gambar_produk as $gbrprdk){
            if($gbrprdk->default==1){
                $produk_array['default_image'] = $gbrprdk->image;
            }
        }

        $cekuserstore = $produk->user_store->where('user_id', $user_id);
        if($cekuserstore->count() > 0){
            $user_store = $produk->user_store->where('user_id', $user_id)->first();
            $harga = $user_store->user_level == 'owner' ? HargaProduk::where(['produk_id' => $product->id, 'user_id' => $user_id])->get()->toArray() :
            HargaProduk::where(['produk_id' => $produk->id, 'user_id' => $user_store->referrer])->get()->toArray();
            if ($user_store->user_level == 'agen'){
                // tampilkan harga agen dan harga jual saja
                // hide harga modal
                $harga[0]['harga_modal'] = null;
            } elseif ($user_store->user_level == 'member') {
                // tampilkan harga jual saja
                // hide harga modal dan harga agen
                $harga[0]['harga_modal'] = null;
                $harga[0]['harga_agen'] = null;
            } 
        } else {
            $refrr = $product->user_store->where('user_id', $owner->user_id)->first()->referrer;
            $harga = HargaProduk::where(['produk_id' => $produk[$key]->id, 'user_id' => $refrr])->get()->toArray();
            if(count($harga) == 0){
                $harga[0]['harga_jual'] = null;
            }
            $harga[0]['harga_modal'] = null;
            $harga[0]['harga_agen'] = null;
        }
               
        // $produk_array[$key]['harga'] = $harga;
        $produk_array['harga']['harga_jual'] = $harga[0]['harga_jual'];
        $produk_array['harga']['harga_agen'] = $harga[0]['harga_agen'];
        $produk_array['harga']['harga_modal'] = $harga[0]['harga_modal'];
        $produk_array['dilihat'] = $produk->produk_klik->jumlah;
        $jumlahterjual = $produk->v_produk_jumlah->where('status_order', '5');
        $produk_array['terjual'] = $jumlahterjual->count() > 0 ? $produk->v_produk_jumlah->where('status_order', '5')->first()->totalkuantitas : 0;
        $produk_array['nama_kategori'] = $produk->kategori->nama_kategori;

        if(!empty($harga[0]['harga_jual'])){
            return response()->json([
                'status'    => 'success',
                'type'      => 'success',
                'data'      => $produk_array
            ], 200);
        } else {
            return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => "Tidak ada produk untuk ditampilkan"
            ], 404);
        }
    }

    public function produkCount(Request $request){
        $this->getProductData($request);
        return response()->json([
            'status'    => 'success',
            'type'      => 'success',
            'count'     => $this->produkCount
        ], 200);
    }


    public function index(Request $request)
    {   
        // return $request;
        return $this->getProductData($request);
    }

    public function view(Request $request)
    {   
        return $this->getProductDetails($request);
    }

    public function store(Request $request)
    {
        $gambar = [];
        $token = $request->route()[2]['token_toko'];
        $toko_id = Toko::where('token', $token)->first()->id;
        $data = [
            'isVirtual'         => $request->isVirtual,
            'kategori_id'       => $request->kategori_id,
            'nama_produk'       => $request->nama_produk,
            'deskripsi_produk'  => $request->deskripsi_produk,
            'status'            => $request->status,
            'inStock'           => $request->inStock,
            'toko_id'           => $toko_id,
        ];

        //cek apakah produk itu virtual atau bukan
        $virtual = $request->isVirtual == '1' ? true : false;

        if (!$virtual){
            //berat barang wajib pada non virtual barang
            $data['berat']          = $request->input('berat') ? $request->berat : 1000; //isi default value
            $data['satuan_berat']   = $request->input('satuan_berat') ? $request->satuan_berat : 'gram'; //isi default value
        }

        if ($datacreated = Produk::create($data)){
            $harga                      = new HargaProduk;
            $klik                       = new ProdukKlik;
            $harga->user_id             = $request->user()->id;
            $harga->harga_modal         = $request->harga_dasar;
            $harga->harga_jual          = $request->harga_jual;
            $harga->harga_agen          = $request->harga_agen;
            $datacreated->harga_produk()->save($harga);
            $datacreated->produk_klik()->save($klik);

            //jika produk bukan virtual, maka masukkan beberapa attribut
            if (!$virtual && $request->input('attribut')){
                foreach($request->attribut as $key => $value) {
                    $produk_attribut = new ProdukAttribut;
                    $produk_attribut->tipe = $value['tipe'];
                    $produk_attribut->nama_attribut = $value['nama_attribut'];
                    $produk_attribut->keterangan = $value['keterangan'] ? $value['keterangan'] : '';
                    $datacreated->produk_attribut()->save($produk_attribut);

                    foreach($value['data'] as $keydata => $valuedata) {
                        $produk_attribut_details    = new ProdukAttributDetails;
                        $produk_attribut_details->keterangan = $valuedata;
                        $produk_attribut->produk_attribut_details()->save($produk_attribut_details);    
                    }                    
                }
            }
            
            // simpan gambar jika ada
            if($request->has('gambar')){
                foreach($request->input('gambar') as $key => $gbrproduk){
                    $default = $key == 0 ? '1' : '0';

                    $gambarprodukdata = new GambarProduk;
                    $gambarprodukdata->image = $gbrproduk;
                    $gambarprodukdata->default = $default;
                    $datacreated->gambar_produk()->save($gambarprodukdata);
                }
            }
            
            return response()->json([
                'status'    => 'success',
                'type'      => 'success',
                'message'   => "Produk Anda telah ditambahkan"
            ], 201);
        } else {
             return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Internal Error'
             ], 500);
        }
    }

    public function updateHarga(Request $request, ...$params)
    {
        $id = $params[1];
        $hargaproduk    = HargaProduk::where(['user_id' => $request->user()->id, 'produk_id' => $id])->first();
        $agenlist       = UserStore::where('referrer', $request->user()->id);
        $harga_modal    = $request->has('harga_modal') ? true : false;
        $harga_agen    = $request->has('harga_agen') ? true : false;

        $tempreq = $request->all();
        if($harga_modal){
            if ($request->user_level == 'agen') {
                $tempreq = $request->except('harga_modal');
            }
        };

        if(empty($tempreq)){
            if($hargaproduk->update($tempreq)){
                if($agenlist->count() > 0){
                    foreach($agenlist->get() as $item){
                        $list[] = $item->user_id;
                    }        
                    $pricetoupdate = HargaProduk::where('produk_id', $id)->whereIn('user_id', $list);
                    if($harga_agen && ($pricetoupdate->count() > 0)){
                        foreach($pricetoupdate->get() as $modal_update){
                            $modal_update->update([
                                'harga_modal' => $tempreq['harga_agen']
                            ]);
                        }
                    }
                }
                return response()->json([
                    'status'    => 'sucess',
                    'type'      => 'info',
                    'message'   => 'Harga produk telah diupdate',
                ], 200);
            } else {
                abort(500);
            };
        } else {
            return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Tidak ada perubahan data',
            ], 500);
        }
    }

    public function update(Request $request, ...$params)
    {
        $id = $params[1];
        if(!empty($request)){
            $produk = Produk::find($id);
            if ($produk && $produk->update($request->all())){
                return response()->json([
                    'status'    => 'success',
                    'type'      => 'info',
                    'message'   => 'Data produk telah diupdate',
                ], 200);
            } else {
                return response('Internal Error', 500);
            }
        } else {
            return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Tidak ada perubahan data',
            ], 500);
        }        
    }

    public function destroy(Request $request, ...$params)
    {
        $id = $params[1];
        $produk = Produk::find($id);
        if ($produk && $produk->delete()){
            return response()->json([
                'status'    => 'success',
                'type'      => 'success',
                'message'   => 'Produk telah dihapus dari Toko',
            ], 200);
        } else {
            abort(500);
        }        
    }

    public function addtocart($id){

    }

    public function checkout(){

    }

}