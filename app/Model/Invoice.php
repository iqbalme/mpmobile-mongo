<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'invoice_no',
        'order_id'
    ];

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

    public function order_snapshot(){
        return $this->hasOne('App\Model\OrderSnapshot');
    }
}
