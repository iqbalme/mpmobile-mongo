<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class GudangMutasi extends Model
{
    protected $table = 'gudang_mutasi';

    public function produk(){
        return $this->belongsToMany('App\Model\Produk', 'produk_id');
    }

    public function user_asal(){
        return $this->belongsToMany('App\Model\User', 'user_id_asal');
    }

    public function user_tujuan(){
        return $this->belongsToMany('App\Model\User', 'user_id_tujuan');
    }

    public function gudang_asal(){
        return $this->belongsToMany('App\Model\Gudang', 'gudang_id_asal');
    }

    public function gudang_tujuan(){
        return $this->belongsToMany('App\Model\Gudang', 'gudang_id_tujuan');
    }

}
