<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use MongoDB\Client as Mongo;


$router->get('tesmongo', 'TesController@tesMongo');
$router->get('tesmongoredis', 'TesController@tesMongoWithRedis');
// Route::get('mongo', function() {
//  $collection = (new Mongo)->mydatabase->mycollection;
//  return $collection->find()->toArray();
// });

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/testoken', ['middleware' => ['jwt.auth', 'jwt.refresh'], function (){
    return 'Halo berhasil';
}]);

$router->get('arrayxml', 'TesController@arraytoxml');

// $router->post('login', 'AuthController@login');
// $router->post('logout', 'AuthController@logout');
// $router->post('refresh', 'AuthController@refresh');
$router->post('me', 'AuthController@me');
$router->get('enchash64/{str}', 'HashController@encodebase64');
$router->get('dechash64/{str}', 'HashController@decodebase64');
$router->post('hapusfile', 'UploadController@hapus');
$router->post('tesheader', 'user\LokasiTokoController@index');
$router->get('tesobject', ['uses' => 'produk\ProdukController@tes']);

// tambahkan jwt.refresh nnt utk refresh setiap token every request
// utk sementara hapus dulu, msh testing
// $router->group(['middleware' => ['jwt.auth', 'jwt.refresh'], 'prefix' => '{token_toko}'], function() use ($router) { //middleware auth dengan jwt.refresh
$router->group(['prefix' => '{token_toko}'], function() use ($router) { //middleware auth tanpa jwt.refresh
    $router->get('/', 'TesController@token');
    $router->group(['middleware' => 'toko.user:owner,agen,member'], function() use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
        $router->post('refresh', 'AuthController@refresh');
    });    
    $router->group(['middleware' => ['jwt.auth']], function() use ($router) {
        $router->get('tesuserstore', 'penjualan\OrderController@userstore');
        $router->group(['middleware' => 'toko'], function() use ($router) { //middleware toko
            // grup prefix '/toko'
            $router->group(['prefix' => 'toko', 'middleware' => 'toko.user:owner'], function() use ($router) {
                $router->post('setlokasitoko', ['uses' => 'user\LokasiTokoController@store', 'as' => 'setlokasitoko']);
                $router->post('updatelokasitoko', ['uses' => 'user\LokasiTokoController@update', 'as' => 'updatelokasitoko']);
            });

            // grup prefix '/produk'
            $router->group(['prefix' => 'produk'], function() use ($router) {
                $router->group(['middleware' => 'toko.user:owner'], function() use ($router) {
                    $router->post('produk', ['uses' => 'produk\ProdukController@store', 'as' => 'tambahproduk']); // tambah produk
                    // $router->post('produk', function() { return 'Halo berhasil'; });
                    $router->post('upload', ['uses' => 'UploadController@upload', 'as' => 'uploadgambarproduk']);
                });            
                $router->group(['middleware' => 'toko.user:owner,agen,member'], function() use ($router) {
                    $router->get('count', ['uses' => 'produk\ProdukController@produkCount', 'as' => 'totalproduk']); //lihat total produk di toko
                    $router->get('produk', ['uses' => 'produk\ProdukController@index', 'as' => 'lihatproduk']); //lihat produk di toko
                    $router->post('detail', ['uses' => 'produk\ProdukController@view', 'as' => 'lihatdetailproduk']); //lihat detail produk di toko
                    $router->post('tambahdiskusi', ['uses' => 'produk\DiskusiProdukController@store', 'as' => 'tambahdiskusi']); //tambah diskusi
                    $router->group(['middleware' => 'produk:owner'], function() use ($router) {
                        $router->delete('produk/{produk_id}', ['uses' => 'produk\ProdukController@destroy', 'as' => 'hapusproduk']); //hapus produk
                        $router->patch('produk/{produk_id}', ['uses' => 'produk\ProdukController@update', 'as' => 'editproduk']); // edit produk
                    });
                    
                    $router->patch('produk/harga/{produk_id}', ['middleware' => 'produk:owner,agen', 'uses' => 'produk\ProdukController@updateHarga', 'as' => 'editharga']); // edit harga produk : agen dan owner
                    $router->post('favoritkan', ['middleware' => 'produk:agen,member', 'uses' => 'produk\ProdukFavoritController@store', 'as' => 'favoritkan']);
                });
                $router->group(['middleware' => 'toko.user:agen,member'], function() use ($router) {
                    $router->group(['middleware' => 'produk:agen,member'], function() use ($router) {
                        $router->post('addtocart', ['uses' => 'penjualan\KeranjangController@store', 'as' => 'addtocart']); //tambah keranjang
                    });
                });
            });
            
            // grup prefix '/order'
            $router->group(['prefix' => 'order'], function() use ($router) {
                $router->post('show_kurir', ['uses' => 'setting\KurirController@show', 'as' => 'show_kurir']); //show kurir
                $router->group(['middleware' => 'toko.user:owner,agen,member'], function() use ($router) {
                    $router->post('eorder', ['uses' => 'penjualan\OrderController@eorder', 'as' => 'e_order']); //ambil order
                });
                $router->group(['middleware' => ['toko.user:owner,agen','order.seller']], function() use ($router) {
                    $router->post('proses', ['uses' => 'penjualan\OrderController@proses', 'as' => 'proses_order']); //ambil order
                    $router->post('tolak', ['uses' => 'penjualan\OrderController@reject', 'as' => 'tolak_order']); //tolak order
                    $router->post('resi', ['uses' => 'penjualan\OrderController@setResi', 'as' => 'set_resi']); //set resi
                    $router->patch('resi/{order_number}', ['uses' => 'penjualan\OrderController@updateResi', 'as' => 'update_resi']); //set resi
                });
                $router->group(['middleware' => 'toko.user:agen,member'], function() use ($router) {
                    // $router->group(['middleware' => 'produk:agen,member'], function() use ($router) {
                        $router->get('getcart', ['uses' => 'penjualan\KeranjangController@index', 'as' => 'getcart']); //lihat keranjang
                    // });
                    $router->post('checkout', ['uses' => 'penjualan\OrderController@store', 'as' => 'checkout']); //move cart item to order
                    $router->group(['middleware' => 'order.owner'], function() use ($router) {
                        $router->post('make_payment', ['uses' => 'penjualan\OrderController@pay', 'as' => 'make_payment']); //lakukan pembayaran
                        $router->post('finish_payment', ['uses' => 'penjualan\OrderController@finish_payment', 'as' => 'finish_payment']); //jika pembayaran telah selesai
                    });                
                });
            });
        });
    });
});

/** some testings for the dummy data in relationship with mongodb */
/**
 * Routes for resource produk-dummy-ctrl
 */
$router->get('produk-dummy-ctrl', 'ProdukDummyCtrlsController@all');
$router->get('produk-dummy-ctrl/{id}', 'ProdukDummyCtrlsController@get');
$router->post('produk-dummy-ctrl', 'ProdukDummyCtrlsController@add');
$router->put('produk-dummy-ctrl/{id}', 'ProdukDummyCtrlsController@put');
$router->delete('produk-dummy-ctrl/{id}', 'ProdukDummyCtrlsController@remove');

/**
 * Routes for resource toko-dummy-ctrl
 */
$router->get('createnewdummy', 'TokoDummyCtrlsController@createnew');
$router->get('showdummy', 'TokoDummyCtrlsController@showdummy');
$router->get('toko-dummy-ctrl/{id}', 'TokoDummyCtrlsController@get');
$router->post('toko-dummy-ctrl', 'TokoDummyCtrlsController@add');
$router->put('toko-dummy-ctrl/{id}', 'TokoDummyCtrlsController@put');
$router->delete('toko-dummy-ctrl/{id}', 'TokoDummyCtrlsController@remove');
