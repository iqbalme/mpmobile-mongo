<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    //contoh upload file
    public function upload(Request $request)
    {
        // return time().' & '.microtime();
        $file = $request->file('gambar');
        $filename = sha1(time()).'_'.$file->getClientOriginalName();
        return $file->move(storage_path('gambar'), $filename);
    }

    //contoh hapus file
    public function hapus(){
        unlink(storage_path('gambar/').'0d263deb31b41034965e23db5c7c4696cffb8a07_vlcsnap-2018-05-30-00h01m54s468.png');
    }
}
