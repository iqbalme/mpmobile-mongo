<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    //membutuhkan beberapa table relasi
    // invoice
    // order data
    // order snapshot
    // keranjang
    // ongkir
    // harga produk

    protected $fillable = [
        'user_id',
        'tanggal_kirim',
        'status_order', //0: tunggu pembayaran, 1: belum diproses, 2: sedang diproses, 3: sedang dikirim, 4: sampai, 5: selesai, 6: batal
        'subtotal',
        'toko_id',
        'order_number'
    ];

    public function order_data(){
        return $this->hasMany('App\Model\OrderData', 'order_id');
    }

    public function ongkir(){
        return $this->hasOne('App\Model\Ongkir', 'order_id');
    }

    public function invoice(){
        return $this->hasOne('App\Model\Invoice', 'order_id');
    }

    public function rating_produk(){
        return $this->hasMany('App\Model\RatingProduk', 'order_id');
    }

    public function user(){
        return $this->belongsTo('App\Model\Order');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }

    public function ulasan_produk(){
        return $this->hasMany('App\Model\UlasanProduk', 'order_id');
    }

    public function order_log(){
        return $this->hasMany('App\Model\OrderLog', 'order_id');
    }

    public function order_payment(){
        return $this->hasOne('App\Model\OrderPayment', 'order_id');
    }

    public function order_resi(){
        return $this->hasOne('App\Model\OrderResi', 'order_id');
    }

    public function user_store(){
        return $this->hasMany('App\Model\UserStore', 'user_id', 'user_id');
    }
}
