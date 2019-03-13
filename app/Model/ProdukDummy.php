<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukDummy extends Model
{
    protected $collection = 'produkdummy';

    public $primaryKey = '_id';

    protected $fillable = [
        '_id',
        'nama_produk',
        'tokodummy_id'
    ];

    public function toko(){
        return $this->belongsTo('App\Model\TokoDummy');
    }
}
