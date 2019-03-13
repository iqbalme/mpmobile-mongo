<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_log';

    protected $fillable = [
        'order_id',
        'source',
        'keterangan'
    ];

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

}
