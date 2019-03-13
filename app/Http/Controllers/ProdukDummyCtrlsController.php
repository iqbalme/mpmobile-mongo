<?php 

namespace App\Http\Controllers;
use App\Model\ProdukDummy;

class ProdukDummyCtrlsController extends Controller {

    public function all(){
        $produk = ProdukDummy::all();
        return $produk->toko->all();
    }

}
