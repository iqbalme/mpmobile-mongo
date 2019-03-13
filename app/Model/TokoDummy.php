<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokoDummy extends Model
{
    protected $collection = 'tokodummy';

    public $primaryKey = '_id';

    protected $fillable = [
        '_id',
        'nama_toko'
    ];

    public function produk(){
        return $this->hasMany('App\Model\ProdukDummy', 'tokodummy_id');
    }
}
