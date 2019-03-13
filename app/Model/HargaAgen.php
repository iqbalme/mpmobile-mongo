<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class HargaAgen extends Model
{
    protected $table = 'harga_agen';

    protected $fillable = [
        'agen_id',
        'produk_id',
        'harga_produk',
        'harga_agen',
        'path_agen'
    ];

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }

    public function agen(){
        return $this->belongsTo('App\Model\Agen');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }
}