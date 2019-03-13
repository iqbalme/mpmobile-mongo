<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class EserverStore extends Model
{

    protected $table = 'eserver_store';

    protected $fillable = [
        'id_server',
        'store_id',
        'option'
    ];

}