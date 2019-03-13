<?php

namespace App\Model;

uuse Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;
    protected $table = 'produk';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'toko_id',
        'isVirtual',
        'kategori_id',
        'nama_produk',
        'deskripsi_produk',
        'status',
        'inStock',
        'berat',
        'satuan_berat'
    ];

    protected $hidden = [
        'toko_id',
        'deleted_at'
    ];

    public function user_store(){
        return $this->hasMany('App\Model\UserStore', 'store_id', 'toko_id');
    }

    public function ulasan_produk(){
        return $this->hasMany('App\Model\UlasanProduk', 'produk_id');
    }

    public function rating_produk(){
        return $this->hasMany('App\Model\RatingProduk', 'produk_id');
    }

    public function produk_favorit(){
        return $this->hasMany('App\Model\ProdukFavorit', 'produk_id');
    }

    public function produk_attribut(){
        return $this->hasMany('App\Model\ProdukAttribut', 'produk_id');
    }

    public function produk_klik(){
        return $this->hasOne('App\Model\ProdukKlik', 'produk_id');
    }

    public function stok(){
        return $this->hasMany('App\Model\Stok', 'produk_id');
    }

    public function gambar_produk(){
        return $this->hasMany('App\Model\GambarProduk', 'produk_id');
    }

    public function order_data(){
        return $this->hasMany('App\Model\OrderData', 'produk_id');
    }

    public function diskusi_produk(){
        return $this->hasMany('App\Model\DiskusiProduk', 'produk_id');
    }

    public function harga_produk(){
        return $this->hasMany('App\Model\HargaProduk', 'produk_id');
    }

    public function kategori(){
        return $this->belongsTo('App\Model\Kategori');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko');
    }

    public function gudang_mutasi(){
        return $this->hasMany('App\Model\GudangMutasi', 'produk_id');
    }

    public function keranjang(){
        return $this->hasMany('App\Model\Keranjang', 'produk_id');
    }

    public function v_produk_jumlah(){
        return $this->hasMany('App\Model\VProdukJumlah', 'produk_id');
    }

}