<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App\model\Order;
use App\model\Toko;

class OrderOwnerMiddleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        if ($request->getMethod() == 'POST'){
            $ordernumber = $request->input('order_number');
        } else {
            $ordernumber = $request->route()[2]['order_number'];
        }
        $orderx = Order::where('order_number', strtoupper($ordernumber));
        $orderitem = $orderx->first();
        
        if ($orderx->count() > 0){
            if($orderitem->user_id == $request->user()->id){
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