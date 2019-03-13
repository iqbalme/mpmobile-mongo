<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukAttributDetails extends Model
{
    protected $table = 'produk_attribut_details';

    protected $fillable = [
        'produk_attribut_id',
        'keterangan'
    ];

    public function produk_attribut(){
        return $this->belongsTo('App\Model\ProdukAttribut', 'produk_attribut_id');
    }
}
