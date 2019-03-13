<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverAuth extends Model
{
    protected $table = 'eserver_auth';

    protected $fillable = [
        'id_server',
        'trx_pin'
    ];

}
