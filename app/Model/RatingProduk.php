<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class RatingProduk extends Model
{
    protected $table = 'rating_produk';

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

    public function v_harga_dan_toko(){
        return $this->belongsTo('App\Model\v_harga_dan_toko', 'produk_id');
    }

    public function v_harga_produk(){
        return $this->belongsTo('App\Model\v_harga_produk', 'produk_id');
    }
}
