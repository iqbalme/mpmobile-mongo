<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class VProdukJumlah extends Model
{
    protected $table = 'v_produk_jumlah';
   
    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }
}
