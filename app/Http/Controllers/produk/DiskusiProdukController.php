<?php

namespace App\Http\Controllers\produk;

use App\model\DiskusiProduk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiskusiProdukController extends Controller
{
    protected $toko_id;
    protected $user_id;

    public function __construct(){
        $this->toko_id = env('toko_id', 1);
        $this->user_id = env('user_id', 1);
    //     $this->middleware(function ($request, $next) {
    //         return $next($request);
    //     });
    }

    public function index()
    {
        return 'di sini lihat diskusi';
    }

    public function store(Request $request)
    {
        $data = [
            'user_id' => $this->user_id, //$request->user_id,
            'produk_id' => $request->produk_id,
            'pesan' => $request->pesan
        ];

        if (DiskusiProduk::create($data)){
            return response()->json([
                'status'    => 'success',
                'message'   => "Komentar Anda telah ditambahkan"
            ], 201);
        } else {
            throw(500);
        }
    }

    public function create()
    {
        //
    }

    public function show($id)
    {
        //
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
