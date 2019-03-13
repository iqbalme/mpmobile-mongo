<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [

    ];

    public function user_pengirim(){
        return $this->belongsToMany('App\Model\User', 'user_id_pengirim');
    }

    public function user_tujuan(){
        return $this->belongsToMany('App\Model\User', 'user_id_tujuan');
    }
}
