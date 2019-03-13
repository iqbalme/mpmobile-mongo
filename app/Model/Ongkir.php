<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Ongkir extends Model
{
    protected $table = 'ongkir';
    
    protected $fillable = [
        'order_id',
        'id_asal',
        'id_tujuan', 
        'total_berat',
        'biaya_kirim',
        'kurir_id'
    ];

    public function keranjang(){
        return $this->belongsTo('App\Model\Keranjang');
    }

    public function order(){
        return $this->belongsTo('App\Model\Order');
    }

    public function kurir(){
        return $this->belongsTo('App\Model\Kurir', 'kurir_id');
    }

}
