<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'user_history';

    public function user(){
        return $this->belongsTo('App\Model\User');
    }
}
