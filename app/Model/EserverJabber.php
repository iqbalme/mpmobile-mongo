<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverJabber extends Model
{

    protected $table = 'eserver_jabber';

    protected $fillable = [
        'id_server',
        'jabber_center',
        'jabber_sender',
        'jabber_sender_pswd'
    ];
    
}
