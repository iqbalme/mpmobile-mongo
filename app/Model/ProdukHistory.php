<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukHistory extends Model
{
    protected $table = 'produk_history';

    protected $fillable = [
        'user_id',
        'produk_id',
        'harga_modal',
        'harga_jual',
        'harga_agen'
    ];

    protected $hidden = [
        'user_id', 
        'produk_id', 
        'created_at', 
        'updated_at'
    ];

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }
}
