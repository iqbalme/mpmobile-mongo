<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverIp extends Model
{
    protected $table = 'eserver_ip';

    protected $fillable = [
        'id_server',
        'ip_sender'
    ];

}
