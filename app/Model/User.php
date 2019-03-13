<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // protected $table = 'user';
    protected $collection = 'user';
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'level_id',
        'last_visit',
        'isSeller',
        'session_active',
        'token_api',
        'image_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    
    public function user_data(){
        return $this->hasOne('App\Model\UserData', 'user_id');
    }

    public function toko(){
        return $this->hasOne('App\Model\Toko', 'user_id');
    }

    public function produk(){
        return $this->hasManyThrough('App\Model\Produk', 'App\Model\Toko');
    }

    public function rating_produk(){
        return $this->hasMany('App\Model\RatingProduk', 'user_id');
    }

    public function order(){
        return $this->hasMany('App\Model\Order', 'user_id');
    }

    public function ulasan_produk(){
        return $this->hasMany('App\Model\UlasanProduk', 'user_id');
    }

    public function diskusi_produk(){
        return $this->hasMany('App\Model\DiskusiProduk', 'user_id');
    }

    public function produk_favorit(){
        return $this->hasMany('App\Model\ProdukFavorit', 'user_id');
    }

    public function gudang_asal(){
        return $this->hasMany('App\Model\GudangMutasi', 'user_id_asal');
    }

    public function gudang_tujuan(){
        return $this->hasMany('App\Model\GudangMutasi', 'user_id_tujuan');
    }

    public function pesan_asal(){
        return $this->hasMany('App\Model\Pesan', 'user_id_pengirim');
    }

    public function pesan_tujuan(){
        return $this->hasMany('App\Model\Pesan', 'user_id_tujuan');
    }

    public function user_bank(){
        return $this->hasMany('App\Model\UserBank', 'user_id');
    }

    public function agen(){
        return $this->hasOne('App\Model\Agen', 'user_id');
    }

}
