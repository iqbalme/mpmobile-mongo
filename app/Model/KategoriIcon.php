<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriIcon extends Model
{
    protected $table = 'kategori_icon';

    protected $fillable = [
        'kategori_id',
        'type',
        'icon_type',
        'path'
    ];

    public function kategori(){
        return $this->belongsTo('App\Model\Kategori');
    }
}
