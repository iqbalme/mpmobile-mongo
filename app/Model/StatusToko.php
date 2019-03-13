<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class StatusToko extends Model
{
    protected $table = 'status_toko';

    protected $fillable = [
        'toko_id',
        'status',
        'mulaitutup',
        'akhirtutup',
        'catatan'
    ];
    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }
}
