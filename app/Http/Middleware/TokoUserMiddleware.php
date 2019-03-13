<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Model\Toko;
use Illuminate\Http\Request;
use JWTAuth;

class TokoUserMiddleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        $token = $request->route()[2]['token_toko'];
        $toko = Toko::where('token', $token)->first();
        $pathInfo = explode('/', $request->getPathInfo());
        if(end($pathInfo) == 'login'){
            $cekUserExist = User::where('username', $request->input('username'));
            if($cekUserExist->count() == 0){
                return response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Anda belum terdaftar di toko ini'
                ], 401);
            } else {
                $isUser = JWTAuth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')]);
                $user = $isUser ? JWTAuth::user() : null;
                if($user == null){
                    return response([
                        'status' => 'error',
                        'type'  => 'warning',
                        'message' => 'Password yang Anda masukkan salah.'
                    ], 401);
                }
            }            
        } else {
            $user = $request->user();
        }

        if($user != null){
            $usertoko = $user->user_store()->where('store_id', $toko->id);
            if($usertoko->count() > 0){
                $user_level = $usertoko->first()->user_level;
                if(in_array($user_level, $params)){
                    $response = $next($request);
                } else {
                    $response = response([
                        'status' => 'error',
                        'type'  => 'danger',
                        'message' => 'Unathorized User'
                    ], 401);
                }
            } else {
                $response = response([
                    'status' => 'error',
                    'type'  => 'danger',
                    'message' => 'Anda belum terdaftar di toko ini'
                ], 401);
            }        
        } else {
            $response = response([
                'status' => 'error',
                'type'  => 'danger',
                'message' => 'Anda belum terdaftar di toko ini'
            ], 401);
        }
        return $response;
    }
}
