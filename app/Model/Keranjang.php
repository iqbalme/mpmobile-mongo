<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'produk_id',
        'kuantitas',
        'harga',
        'berat',
        'subtotal',
        'subberat',
        'catatan'
    ];
    
    public function ongkir(){
        return $this->hasOne('App\Model\Ongkir', 'keranjang_id');
    }

    public function produk(){
        return $this->belongsTo('App\Model\Produk', 'produk_id');
    }
}
