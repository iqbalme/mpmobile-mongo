<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStore extends Model
{
    protected $table = 'user_store';

    protected $fillable = [
        'user_id',
        'store_id',
        'user_level',
        'referrer',
        'path_referrer'
    ];

    public function store(){
        return $this->belongsTo('App\Model\Toko', 'store_id');
    }
}
