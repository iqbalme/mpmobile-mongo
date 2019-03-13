<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class ProdukUnggulan extends Model
{
    protected $table = 'produk_unggulan';

    public function produk(){
        return $this->belongsTo('App\Model\Produk');
    }    

}
