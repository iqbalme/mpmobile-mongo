<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class GudangHistory extends Model
{
    protected $table = 'gudang_history';
    
    public function gudang(){
        return $this->belongsTo('App\Model\Gudang');
    }
}
