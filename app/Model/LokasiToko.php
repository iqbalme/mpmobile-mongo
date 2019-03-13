<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class LokasiToko extends Model
{
    protected $table = 'lokasi_toko';

    protected $fillable = [
        'toko_id',
        'kode_pos',
        'detail_lokasi',
        'longitude',
        'latitude'
    ];
    
    public function toko(){
        return $this->belongsTo('App\Model\Toko', 'toko_id');
    }

}
