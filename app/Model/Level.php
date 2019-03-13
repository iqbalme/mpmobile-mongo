<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Level extends Model
{
    protected $table = 'level';

    protected $fillable = [
        'nama_level'
    ];

    public function role(){
        return $this->hasMany('App\Model\Role', 'level_id');
    }
}