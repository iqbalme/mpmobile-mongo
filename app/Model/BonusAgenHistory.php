<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BonusAgenHistory extends Model
{
    protected $table = 'bonus_agen_history';
    
    public function agen(){
        return $this->belongsTo('App\Model\Agen');
    }
}
