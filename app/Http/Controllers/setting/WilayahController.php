<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\model\Wilayah;

class WilayahController extends Controller
{

    public function getProvinsi(){
        $provinsi = Wilayah::distinct()->pluck('provinsi');
        return response()->json([
            'status' => 'sukses',
            'data'   => $provinsi
        ], 200);
    }

    public function getKabupaten(Request $request){
        $kabupaten = Wilayah::where('provinsi', $request->provinsi)->distinct()->pluck('kabupaten');
        // $kabupaten = 'ini adalah request dari controller :'.$request->provinsi;
        return response()->json([
            'status' => 'sukses',
            'data'   => $kabupaten
        ], 200);
    }

    public function getKecamatan(Request $request){
        $kecamatan = Wilayah::where('kabupaten', $request->kabupaten)->distinct()->pluck('kecamatan');
        return response()->json([
            'status' => 'sukses',
            'data'   => $kecamatan
        ], 200);
    }
    
    public function getKelurahan(Request $request){
        $kelurahan = Wilayah::where('kecamatan', $request->kecamatan)->distinct()->pluck('kelurahan');
        return response()->json([
            'status' => 'sukses',
            'data'   => $kelurahan
        ], 200);
    }

    public function getKodepos(Request $request){
        $kodepos = Wilayah::where('kelurahan', $request->kelurahan)
        ->where('kecamatan', $request->kecamatan)->first();
        return response()->json([
            'status' => 'sukses',
            'data'   => $kodepos
        ], 200);
    }
    
}