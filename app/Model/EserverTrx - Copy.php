<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverTrx extends Model
{
    protected $table = 'eserver_trx';

    protected $fillable = [
        'id_server',
        'id_jenistrx',
        'status'
    ];

}
