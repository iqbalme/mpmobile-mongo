<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Testing extends Eloquent {
	
	protected $collection = 'produk';
	public $timestamps = false;
	public $primaryKey = '_id';
	protected $fillable = ["_id", "due_date", "completed", "created_at", "updated_at"];
}
