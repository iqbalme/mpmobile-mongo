<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class OrderResi extends Model
{
    protected $table = 'order_resi';

    protected $fillable = [
        'order_id',
        'kurir_id',
        'nomor_resi'
    ];

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

}
