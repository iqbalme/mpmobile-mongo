<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class OrderSnapshot extends Model
{
    protected $table = 'order_snapshot';

    protected $fillable = [
        'invoice_id',
        'produk_id',
        'gambar',
        'info_produk',
        'deskripsi',
        'attribut',
        'harga',
        'tgl_snapshot',
        'link_produk'
    ];

    public function invoice(){
        return $this->belongsTo('App\Model\invoice', 'invoice_id');
    }
}
