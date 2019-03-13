<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukKlik extends Model
{
    protected $table = 'produk_klik';

    protected $fillable = [
        'produk_id',
        'jumlah'
    ];

    public function produk(){
        return $this->belongsTo('App\Model\Produk', 'produk_id');
    }

}
