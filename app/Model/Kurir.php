<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Kurir extends Model
{
    protected $table = 'kurir';

    protected $fillable = [
        'nama_kurir',
        'tipe_kurir',
        'kode_kurir',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
