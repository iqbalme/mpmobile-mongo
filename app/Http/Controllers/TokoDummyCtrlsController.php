<?php 

namespace App\Http\Controllers;

use App\Model\TokoDummy;
use App\Model\ProdukDummy;

class TokoDummyCtrlsController extends Controller {

    //insert dummy data
    public function all(){
        $toko = new TokoDummy;
        $toko->nama_toko = 'Digital Blast';
        $toko->save();
        $produk = new ProdukDummy;
        $produk->nama_produk = 'STB ZTE B860H Eks Indihome';
        $toko->produk()->save($produk);
        return $toko;
    }

    public function allproduk(){
        // return TokoDummy::with('produk')->get();
        $toko = TokoDummy::all();
        return $toko;

    }

}