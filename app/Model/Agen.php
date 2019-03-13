<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Agen extends Model
{
    protected $table = 'agen';
   
    public function bonus_agen(){
        return $this->hasMany('App\Model\BonusAgen', 'agen_id');
    }

    public function bonus_agen_history(){
        return $this->hasMany('App\Model\BonusAgenHistory', 'agen_id');
    }

    public function harga_agen(){
        return $this->hasMany('App\Model\HargaAgen', 'agen_id');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }

    public function user(){
        return $this->belongsTo('App\Model\User', 'user_id');
    }
}
