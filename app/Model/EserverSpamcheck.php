<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverSpamcheck extends Model
{

    protected $table = 'eserver_spamcheck';

    protected $fillable = [
       'id_server',
       'last_command'
    ];

}