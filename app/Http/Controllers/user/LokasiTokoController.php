<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\model\LokasiToko;
use App\model\Toko;
// use App\Http\Requests\LokasitokoRequest;

class LokasiTokoController extends Controller
{
    protected $toko_id;

    public function __construct(Request $request){
        $token = $request->route()[2]['token_toko'];
        $this->toko_id = Toko::where('token', $token)->first()->id;
    }

    // public function store(LokasitokoRequest $request)
    public function store(Request $request)
    {
        $data = [
            'toko_id'        => $this->toko_id,
            'detail_lokasi'  => $request->alamat_toko,
            'kode_pos'       => $request->kode_pos,
            'longitude'      => $request->input('longitude') ? $request->longitude : '',
            'latitude'       => $request->input('latitude') ? $request->latitude : ''
        ];
        if(LokasiToko::where('toko_id', $this->toko_id)->count() > 0){
            $response = response([
                'status' => 'error',
                'type'  => 'warning',
                'message' => 'Lokasi sudah diset, ingin update lokasi?'
            ], 200);
        } else {
            if ($lokasi = LokasiToko::create($data)){
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Lokasi toko telah disimpan',
                    'data'      => $lokasi
                ], 201);
            } else {
                $response = response([
                        'status' => 'error',
                        'type'  => 'danger',
                        'message' => 'Internal Error'
                    ], 500);
            }
        }
        return $response;        
    }


    public function update(Request $request)
    {
        $data = [
            'toko_id'        => $this->toko_id,
            'detail_lokasi'  => $request->input('alamat_toko'),
            'kode_pos'       => $request->input('kode_pos'),
            'longitude'      => $request->input('longitude'),
            'latitude'       => $request->input('latitude')
        ];
        $data = array_filter($data, function($value) { return $value !== null; });
        if(LokasiToko::where('toko_id', $this->toko_id)->update($data)){
            $response = response([
                'status' => 'success',
                'type'  => 'info',
                'message' => 'Lokasi toko telah diupdate'
            ], 200);
        } else {
            $response = response([
                'status' => 'error',
                'type'  => 'warning',
                'message' => 'Gagal update lokasi, Internal Error'
            ], 500);
        }
        return $response;
    }

}
