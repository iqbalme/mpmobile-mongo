<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverSoftware extends Model
{

    protected $table = 'eserver_software';

    protected $fillable = [
        'nama_server',
        'kode_server',
        'deskripsi',
        'link'
    ];
    
}
