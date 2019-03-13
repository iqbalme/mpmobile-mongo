<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toko extends Model
{
    use SoftDeletes;
    protected $collection = 'toko';
    public $primaryKey = '_id';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        '_id',
        'user_id',
        'nama_toko',
        'slogan',
        'deskripsi',
        'url_toko',
        'logo_path',
        'token'
    ];

    protected $hidden = [
        'user_id',
        'token',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    public function user_store(){
        return $this->hasMany('App\Model\UserStore', 'store_id');
    }

    public function status_toko(){
        return $this->hasOne('App\Model\StatusToko', 'toko_id');
    }

    public function produk(){
        return $this->hasMany('App\Model\Produk', 'toko_id');
    }

    public function lokasi_toko(){
        return $this->hasOne('App\Model\LokasiToko', 'toko_id');
    }

    public function toko_bank(){
        return $this->hasMany('App\Model\TokoBank', 'toko_id');
    }

    public function kategori(){
        return $this->hasMany('App\Model\Kategori', 'toko_id');
    }

    public function gambar_produk(){
        return $this->hasManyThrough('App\Model\GambarProduk', 'App\Model\Produk');
    }

    public function diskusi_produk(){
        return $this->hasManyThrough('App\Model\DiskusiProduk', 'App\Model\Produk');
    }

    public function rating_produk(){
        return $this->hasManyThrough('App\Model\RatingProduk', 'App\Model\Produk');
    }

    public function ulasan_produk(){
        return $this->hasManyThrough('App\Model\UlasanProduk', 'App\Model\Produk');
    }

    public function produk_klik(){
        return $this->hasManyThrough('App\Model\ProdukKlik', 'App\Model\Produk');
    }

    public function produk_favorit(){
        return $this->hasManyThrough('App\Model\ProdukFavorit', 'App\Model\Produk');
    }

    public function gudang(){
        return $this->hasMany('App\Model\Gudang', 'toko_id');
    }

    public function gudang_admin(){
        return $this->hasMany('App\Model\GudangAdmin', 'toko_id');
    }

    public function order(){
        return $this->hasMany('App\Model\Order', 'toko_id');
    }

    public function agen(){
        return $this->hasMany('App\Model\Agen', 'toko_id');
    }

    public function harga_agen(){
        return $this->hasMany('App\Model\HargaAgen', 'toko_id');
    }

    public function kupon(){
        return $this->hasMany('App\Model\Kupon', 'toko_id');
    }

    public function stok(){
        return $this->hasMany('App\Model\Stok', 'toko_id');
    }

}
