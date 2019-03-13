<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukFavorit extends Model
{
    protected $table = 'produk_favorit';

    protected $fillable = [
        'user_id',
        'produk_id',
        'store_id',
        'identifier'
    ];

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

}
