<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payment';

    protected $fillable = [
        'order_id',
        'payment_method',
        'keterangan'
    ];

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

}
