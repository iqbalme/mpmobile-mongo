<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\TokoRequest;

use App\model\Gudang;
use App\model\GudangAdmin;
use App\model\Toko;

class TokoController extends Controller
{
    public $user_id;
    public $toko_id;

    public function __construct(){
        //harus menjalankan middleware dulu sebelum gunakan method di constructor
        $this->middleware(function ($request, $next) {
            $this->user_id = $request->user()->id;            
            $toko = Toko::where('user_id', $this->user_id);
            if ($toko->count()){
                $this->toko_id = $toko->first()->id;
            } else {
                $this->toko_id = null;
            }
            return $next($request);
        });        
    }

    public function index()
    {
        
    }

    public function create()
    {

    }

    // public function store(Request $request)
    public function store(TokoRequest $request)
    {
        if (! $this->toko_id){
            $data = [
            'user_id'   => $this->user_id,
            'nama_toko' => $request->nama_toko,
            'slogan'    => $request->slogan,
            'deskripsi' => $request->deskripsi,
            'url_toko'  => $request->url_toko,
            'token'     => bcrypt($this->user_id.$request->url_toko)
            ];
            // if ($request->hasFile('logo_toko')) {
            //     $logo = $request->file('logo_toko')->store('images');
            //     $data['logo_path'] = $logo;
            // }
            $toko = Toko::create($data);
            $gudang = new Gudang;
            $gudang->nama_gudang = 'Gudang Utama';
            $gudangadmin = new GudangAdmin;
            $gudangadmin->user_id = $this->user_id;
            $gudangadmin->toko_id = $toko->id;
            $toko->gudang()->save($gudang);
            $gudang->gudang_admin()->save($gudangadmin);

            return response()->json([
                'status'    => 'sukses',
                'type'      => 'success',
                'message'   => 'Toko berhasil dibuat, Silakan tambahkan produk',
                'button'    => [
                    [1 =>
                            [
                                'value' => 'Tambah Produk',
                                'link'  => route('tambah_produk'),
                                'method' => 'get'
                            ]
                        ]
                    ]
                ], 201);
        } else {
            return response()->json([
                'status'    => 'error',
                'type'      => 'warning',
                'message'   => 'Toko telah diinput sebelumnya'
            ], 406);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if ($this->toko_id){
            $data = [
                'user_id' => auth()->user()->id,
                'nama_toko' => $request->nama_toko,
                'slogan' => $request->slogan,
                'deskripsi' => $request->deskripsi,
                'url_toko' => $request->url_toko,
                'route' => $request->route,
                'token' => bcrypt($request->user_id.$request->url_toko)
            ];
            if ($request->hasFile('logo_toko')) {
                $pathlogo = Toko::where('user_id', auth()->user()->id)->first()->logo_path;    
                if (!empty($pathlogo)){
                    unlink(public_path('storage/'.$request->logo_path));
                }
                $logo = $request->file('logo_toko')->store('images');
                $data['logo_path'] = $logo;
                // return $logo;
            }
            Toko::where('user_id', auth()->user()->id)->update($data);    
            return redirect(route('toko.index'));
        }
    }

    public function destroy($id)
    {
        //
    }

    public function hapuslogo(){
        $toko = Toko::where('user_id', auth()->user()->id)->first();
        // return $toko;
        unlink(public_path('storage/'.$toko->logo_path));
        $toko->update([
            'logo_path' => ''
        ]);       
        return redirect(route('toko.create'));
    }
}
