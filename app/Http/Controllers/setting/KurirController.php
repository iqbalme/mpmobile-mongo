<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Kurir;
use Ixudra\Curl\Facades\Curl;

class KurirController extends Controller
{

    public function index()
    {

    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        $kurir = Kurir::all();
        $nama_kurir = $request->input('nama_kurir');
        $tipe_kurir = $request->input('tipe_kurir');
        if(empty($nama_kurir)){
            return Kurir::select('nama_kurir')->distinct()->get();
        } else {
            if(empty($tipe_kurir)){
                return Kurir::select('tipe_kurir')->where('nama_kurir', $nama_kurir)->get();
            } else {
                return Kurir::where(['nama_kurir' => $nama_kurir, 'tipe_kurir' => $tipe_kurir])->first()->id;
            }
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
