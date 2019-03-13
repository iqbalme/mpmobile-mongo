<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
// use JWTAuth as auth;
use App\model\Produk;

class ProdukMiddleware
{

    public function handle(Request $request, Closure $next, ...$params)
    {
        // $routename = $request->route()[1]['as'];
        $idproduk;
        if ($request->getMethod() == 'POST'){
            $idproduk = $request->input('produk_id');
        } else {
            if($request->route()[1]['as'] <> 'getcart'){
                $idproduk = $request->route()[2]['produk_id'];
            }            
        }

        // $user    = auth::user();
        $user       = $request->user();
        $produk     = Produk::find($idproduk);

        // return $request->getPathInfo();
        // die;

        if($user){
            if(!empty($produk)){
                $idprodukdb = $produk->toko()->first()->id;
                $userstore  = $user->user_store()->where('store_id', $idprodukdb);
                if($userstore->count() > 0){
                    if(in_array($userstore->first()->user_level, $params)){
                        // pass new value to $request to controller
                        $request->merge(['user_level' => $userstore->first()->user_level]);
                        $response = $next($request);
                    } else {
                        // $response = 'satu';
                        // die;
                        $response = response([
                            'status'    => 'error',
                            'type'      => 'danger',
                            'message'   => 'Unathorized User'
                        ], 401);       
                    }
                } else {
                    // $response = 'dua';
                    // die;
                    $response = response([
                        'status'    => 'error',
                        'type'      => 'danger',
                        'message'   => 'Unathorized User'
                    ], 401);
                }
            } else {
                // $response = 'tiga';
                // die;
                $response = response([
                    'status'    => 'error',
                    'type'      => 'danger',
                    'message'   => 'Unathorized User'
                ], 401);
            }            
        } else {
            $response = response([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Login terlebih dahulu'
            ], 500);
        }
        
        return $response;
    }
}