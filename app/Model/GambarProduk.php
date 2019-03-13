<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class GambarProduk extends Model
{
    protected $table = 'gambar_produk';

    protected $hidden = array('produk_id', 'created_at', 'updated_at');
    
    protected $fillable = [
        'produk_id',
        'image',
        'default'
    ];
    
    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }

    public function v_harga_dan_toko(){
        return $this->belongsTo('App\Model\v_harga_dan_toko', 'produk_id');
    }

    public function v_harga_produk(){
        return $this->belongsTo('App\Model\v_harga_produk', 'produk_id');
    }
}