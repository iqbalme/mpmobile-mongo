<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class OrderData extends Model
{
    protected $table = 'order_data';

    protected $fillable = [
        'order_id',
        'produk_id',
        'kuantitas',
        'harga',
        'subtotal',
        'berat'
    ];

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

    public function produk(){
        return $this->hasMany('App\Model\Produk', 'produk_id');
    }
}
