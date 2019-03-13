<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class GudangAdmin extends Model
{
    protected $table = 'gudang_admin';
    
    public function gudang(){
        return $this->belongsTo('App\Model\Gudang');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }

}
