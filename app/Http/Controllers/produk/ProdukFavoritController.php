<?php

namespace App\Http\Controllers\produk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\model\ProdukFavorit;
use App\model\Toko;

class ProdukFavoritController extends Controller
{
    // public $isExist;
    // protected $toko_id;
    // protected $user_id;

    public function __construct(){
        // $this->toko_id = env('toko_id');
        // $this->user_id = env('user_id');
    }

    public function store(Request $request)
    {
        $produkfav = ProdukFavorit::where(['user_id' => $request->user()->id, 'produk_id' => $request->input('produk_id')]);
        $toko_id = Toko::where('token', $request->route()[2]['token_toko'])->first()->id;
        if($produkfav->count() > 0){
            $id = $produkfav->first()->id;
            if(!ProdukFavorit::find($id)->delete()){
                $response = response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Internal Error'
                ], 500);
            } else {
                $response = response([
                    'status' => 'success',
                    'type'  => 'success',
                    'message' => 'Produk telah dihapus dari daftar favorit'
                ], 200);
            }
        } else {
            if(ProdukFavorit::create([
                'user_id'   => $request->user()->id,
                'produk_id' => $request->produk_id,
                'store_id'  => $toko_id,
                'identifier' => $request->user()->id.$request->produk_id.$toko_id
            ])){
                $response = response([
                    'status' => 'success',
                    'type'  => 'success',
                    'message' => 'Produk telah ditambahkan ke daftar favorit'
                ], 201);
            } else {
                $response = response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Internal Error'
                ], 500);
            }
        }

        return $response;
    }

    public function show()
    {
        //
    }

}
