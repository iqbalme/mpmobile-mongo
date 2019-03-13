<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class UserData extends Model
{
    // protected $table = 'user_data';
    protected $connection = 'mongodb';
    protected $collection = 'user_data';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'display_name',
        'alamat',
        'kode_pos',
        'phone',
        'image_path'
    ];
    public function user(){
        return $this->belongsTo('App\Model\User');
    }
    
}
