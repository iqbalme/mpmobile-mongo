<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverJenistrx extends Model
{
    protected $table = 'eserver_jenistrx';

    protected $fillable = [
        'jenis_trx'
    ];

}
