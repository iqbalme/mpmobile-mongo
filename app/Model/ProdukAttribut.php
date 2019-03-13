<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukAttribut extends Model
{
    protected $table = 'produk_attribut';

    protected $fillable = [
        'produk_id',
        'tipe',
        'nama_attribut',
        'keterangan'
    ];

    public function produk(){
        return $this->belongsTo('App\Model\Produk', 'produk_id');
    }

    public function produk_attribut_details(){
        return $this->hasMany('App\Model\ProdukAttributDetails', 'produk_attribut_id');
    }
}
