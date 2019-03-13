<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class KategoriSugesti extends Model
{
    protected $table = 'kategori_sugesti';

    protected $fillable = [
        'nama_kategori',
        'default_icon'
    ];
}
