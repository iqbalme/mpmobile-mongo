<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\model\Kategori;
use App\model\Toko;
// use App\Http\Resources\KategoriResource;

class KategoriController extends Controller
{
    public $toko_id;

    public function __construct(){
        //harus menjalankan middleware dulu sebelum gunakan method di constructor
        $this->middleware(function ($request, $next) {
            $this->toko_id = $request->get('toko_id');
            return $next($request);
        });        
    }
    
    public function index(Kategori $kategori)
    {
        return $this->toko_id;
        // return KategoriResource::collection($kategori::paginate(2));
    }

    public function create()
    {
        // $cat = Kategori::where('toko_id', $this->toko_id)->orderBy('urutan')->get();
        //belum masuk parent utamanya
        $kat = Kategori::where('toko_id', $this->toko_id)->pluck('nama_kategori', 'id');
        // $kat = [[0 => 'Utama'], $katdata];
        // $categorydata = [$kat, $katlist];
        $icon = [
            0 => 'Icon',
            1 => 'Upload',
            2 => 'URL',
            3 => 'SVG'
        ];
        return view('toko.kategori.tambahKategori', compact('kat', 'icon'));
        // dd($categorydata);
        return $kat;
    }

    public function store(Request $request)
    {
        $data = [
            'kode_kategori'     => str_random(20),
            'nama_kategori'     => $request->nama_kategori,
            'deskripsi'         => $request->deskripsi,
            'parent'            => $request->parent,
            'status'            => $request->status,
            'icon_type'         => $request->icon_type,
            'icon'              => $request->icon,
            'icon_type_mobile'  => $request->icon_type_mobile,
            'icon_mobile'       => $request->icon_mobile,
            'urutan'            => $request->urutan,
            'toko_id'           => $this->toko_id
        ];
        $kategori = Kategori::create($data);
        if ($kategori){
            return response()->json([
                'status'    => 'sukses',
                'type'      => 'success',
                'data'      => $data
            ], 200);
        }
        abort(400);
        // return $this->toko_id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat = Kategori::find($id);
        $kat = Kategori::where('toko_id', $this->toko_id)->pluck('nama_kategori', 'id');
        $icon = [
            0 => 'Icon',
            1 => 'Upload',
            2 => 'URL',
            3 => 'SVG'
        ];
        // return $cat;
        return view('toko.kategori.editKategori', compact('cat', 'kat', 'icon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
