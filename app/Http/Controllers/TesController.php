<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\cache;
use Illuminate\Support\Facades\Redis;
use App\Model\Testing;

use Illuminate\Http\Request;
use App\Model\ServerPulsa;
use App\Model\SPSpamChecker;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Traits\CommonTrait;
use Parser;
use Spatie\ArrayToXml\ArrayToXml;

class TesController extends Controller
{
    use CommonTrait;

    public function arraytoxml(){
        $array = [
            'command' => 'TOPUP',
            'pin' => 1234,
            'product' => 'T5',
            'msisdn' => '085343749369',
            'refTrxid' => 1
        ];
        
        $result = ArrayToXml::convert($array, 'fm');
        return $result;
    }

    public function token(Request $request){
        return $request->route()[2]['token_toko'];
    }
    
    public function sendjabber(Request $request)
    {
        return md5('s.2018');
        die;
        
        $username = $request->input('source');
        $command = $request->input('command');
        $result = '';
        $sendcommand = Curl::to(env('JABBER_URL_API'))
        ->withData([
                'type' => 'headline',
                'from' => $username.env('JABBER_SERVER'),
                'to'   => env('JABBER_SERVER_ADMIN').env('JABBER_SERVER'),
                'body' => $command
            ])
        // ->withHeader('key: '.env('rajaongkir_key'))
        // ->withContentType('application/json')
        ->asJson(true) //as associative array
        ->post();

        if($sendcommand == 0){
            $connection = DB::connection('mysql2');
            $u = $connection->table('spool')->where('username', $username)->get();
            if($u->count() > 0){
                $parsed = Parser::xml($u[0]->xml);
                $result = $parsed['body'];
                $connection->table('spool')->where('username', $username)->delete();
            }
            $response = response([
                'status'    => 'success',
                'type'      => 'success',
                'message'   => $result
            ], 200);
        } else {
            $response = response([
                    'status'    => 'error',
                    'type'      => 'danger',
                    'message'   => 'Internal Error'
                ], 500); 
        };

        return $response;
    }

    private function getLastCommand(){
        // SPSpamChecker::
    }

    // masukin ke data dummy utk server sekarang
    // cek dulu apakah perintahnya itu transaksional atau tidak

    private function setLastCommand(){

    }

    public function tesMongoWithRedis(){
        // Use of Redis cache
        $produk = Cache::remember('testing', 10, function() {
            return Testing::all();
        });
        return $produk->toJson();
    }

    public function tesMongo(){
        return Testing::all();
    }
}
