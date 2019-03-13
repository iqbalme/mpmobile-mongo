<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class TokoBank extends Model
{
    
    protected $table = 'toko_bank';

    protected $fillable = [
        'bank_id',
        'toko_id',
        'norek',
        'cabang',
        'nama_akun'
    ];

    public function toko_bank(){
        return $this->belongsTo('App\Model\Toko');
    }
}
