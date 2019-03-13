<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Kupon extends Model
{
    protected $table = 'kupon';

    protected $fillable = [
        'kode_kupon',
        'deskripsi',
        'diskon',
        'isLimitedUse',
        'dateStart',
        'dateEnd',
        'total_use',
        'status',
        'toko_id'
    ];

    public function toko(){
        return $this->belongsTo('App\Model\Toko', 'toko_id');
    }
}
