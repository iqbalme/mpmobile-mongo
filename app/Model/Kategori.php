<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'id',
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
        'parent',
        'status',
        'urutan',
        'toko_id'
    ];

    public function produk(){
        return $this->hasMany('App\Model\Produk', 'kategori_id');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko', 'toko_id');
    }
}
