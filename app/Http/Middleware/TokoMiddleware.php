<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App\model\Toko;
use App\model\UserStore;
use App\model\Produk;

class TokoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $pathInfo = $request->getpathInfo();
        $idproduk;
        $token = $request->route()[2]['token_toko'];
        $list_store = [];
        $list_produkid = [];
        $toko = Toko::where('token', $token);
        $pathSplitted = explode('/', $pathInfo);
        if($toko->count() > 0){
            if(end($pathSplitted) != 'produk' && end($pathSplitted) != 'count'){
                $response = $next($request);
            } else {
                $owner_id = $toko->first()->user_store()->where('user_level', 'owner')->first()->id;
                $cektoko = UserStore::where('user_id', $owner_id)->whereIn('user_level', ['owner', 'agen']);
                if($cektoko->count() > 0){
                    foreach($cektoko->get() as $dftrtoko){
                        $list_store[] = $dftrtoko->store_id;
                    }
                }

                if(count($list_store) > 0){
                    $dataproduk = Produk::whereIn('toko_id', $list_store)->get();
                    foreach($dataproduk as $item){
                        $list_produkid[] = $item->id;
                    }
                }
                
                if ($request->getMethod() == 'GET'){
                    $idproduk   = null;
                } elseif ($request->getMethod() == 'POST'){
                    $idproduk   = $request->input('produk_id');
                } else {
                    $idproduk   = $request->route()[2]['produk_id'];                
                }

                if ($request->getMethod() == 'GET'){
                    $request->merge(['list_produkid' => $list_produkid]);
                    $response = $next($request);
                } else {
                    if(!empty($idproduk)){
                        if(in_array($idproduk, $list_produkid)){
                            $response = $next($request);
                        } else {
                            $response = response([
                                'status' => 'error',
                                'type'  => 'warning',
                                'message' => 'Produk tidak ditemukan'
                            ], 404);
                        }
                    } else {
                        $response = $next($request);
                    }
                }            
            }
        } else {
            $response = response([
                'status' => 'error',
                'type'  => 'warning',
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }

        return $response;
    }
}