<?php

namespace App\user;

use Jenssegers\Mongodb\Eloquent\Model;

class UserBank extends Model
{
    protected $table = 'user_bank';

    protected $fillable = [
        'bank_id',
        'user_id',
        'norek',
        'cabang',
        'nama_akun'
    ];

    public function user(){
        return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function bank(){
        return $this->hasMany('App\Model\UserBank', 'bank_id');
    }
}
