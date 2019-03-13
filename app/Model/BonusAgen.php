<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BonusAgen extends Model
{
    protected $table = 'bonus_agen';
    
    public function agen(){
        return $this->belongsTo('App\Model\Agen');
    }
}
