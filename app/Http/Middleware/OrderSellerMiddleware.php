<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App\model\Order;
use App\model\Toko;

class OrderSellerMiddleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        $token_toko = $request->route()[2]['token_toko'];
        if ($request->getMethod() == 'POST'){
            $ordernumber = $request->input('order_number');
        } else {
            $ordernumber = $request->route()[2]['order_number'];
        }
        $orderx = Order::where('order_number', strtoupper($ordernumber));
        $orderitem = $orderx->first();
        $toko_id = Toko::where('token', $token_toko)->first()->id;
        $referrer = $orderitem->user_store()->where('store_id', $toko_id)->first()->referrer;
        
        if ($orderx->count() > 0){
            if($referrer == $request->user()->id){
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
                'type'  => 'warning',
                'message' => 'Order tidak ditemukan, Silakan periksa kembali'
            ], 404);
        }
        return $response;
    }
}