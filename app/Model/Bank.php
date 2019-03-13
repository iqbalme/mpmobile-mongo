<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'Bank';

    public function user_bank(){
        return $this->belongsToMany('App\Model\UserBank', 'bank_id');
    }
}
