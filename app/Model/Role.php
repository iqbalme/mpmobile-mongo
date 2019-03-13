<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $fillable = [
        'user_id',
        'level_id'
    ];

    public function level(){
        return $this->belongsTo('App\Model\Level', 'level_id');
    }
}
