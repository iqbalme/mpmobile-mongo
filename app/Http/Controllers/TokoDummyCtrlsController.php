<?php 

namespace App\Http\Controllers;

use App\Model\TokoDummy;
use App\Model\ProdukDummy;

class TokoDummyCtrlsController extends Controller {

    //insert dummy data
    public function createnew(){
        $toko = new TokoDummy;
        $toko->nama_toko = 'Digital Blast';
        $toko->save();
        $produk = new ProdukDummy;
        $produk->nama_produk = 'STB ZTE B860H Eks Indihome';
        $produk->tokodummy_id = new \MongoDB\BSON\ObjectId($toko->_id);
        $produk->save();
        return $toko;
    }

    public function showdummy(){
        // return TokoDummy::with('produk')->get();
        $toko = TokoDummy::all();
        return $toko->produk();

    }

}
