<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }

    public function gudang(){
        return $this->belongsTo('App\Model\Gudang');
    }

}
