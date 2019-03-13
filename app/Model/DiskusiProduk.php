<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class DiskusiProduk extends Model
{
    protected $table = 'diskusi_produk';

    protected $fillable = [
        'produk_id',
        'user_id',
        'pesan',
        'read_status'
    ];
    
    public function produk(){
        return $this->belongsTo('App\Model\Produk', 'produk_id');
    }

    public function user(){
        return $this->belongsTo('App\Model\User', 'user_id');
    }
    
}