<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudang';
    // protected $primaryKey = '';

    protected $fillable = [
        'toko_id',
        'nama_gudang',
        'kode_gudang',
        'lokasi_gudang'
    ];
    // protected $guard = [];
    // public $timestamps = false;
    // protected $connection = 'connection-name';

    // Flight::chunk(200, function ($flights) {
    //     foreach ($flights as $flight) {
    //             //
    //         }
    //     });

    // foreach (Flight::where('foo', 'bar')->cursor() as $flight) {
    // //
    // }

    public function gudang_admin(){
        return $this->hasMany('App\Model\GudangAdmin', 'gudang_id');
    }

    public function gudang_history(){
        return $this->hasMany('App\Model\GudangHistory', 'gudang_id');
    }

    public function gudang_asal(){
        return $this->hasMany('App\Model\GudangMutasi', 'gudang_id_asal');
    }

    public function gudang_tujuan(){
        return $this->hasMany('App\Model\GudangMutasi', 'gudang_id_tujuan');
    }

    public function toko(){
        return $this->belongsTo('App\Model\Toko', 'toko_id');
    }

    public function stok(){
        return $this->hasMany('App\Model\Stok', 'gudang_id');
    }
}
