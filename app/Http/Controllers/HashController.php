<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Base64Url\Base64Url;

class HashController extends Controller
{

   public function encodebase64($str){
        // $encoded = Base64Url::encode($message); //Result must be "SGVsbG8gV29ybGQh"
        // $decoded = Base64Url::decode($encoded); //Result must be "Hello World!"
        $encoded = Base64Url::encode($str);
        return $encoded;
   }

   public function decodebase64($str){
        $decoded = Base64Url::decode($str);
        return $decoded;
   }

}