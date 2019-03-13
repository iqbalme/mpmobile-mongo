<?php
namespace App\Http\Traits; 

use Parser;
use Base64Url\Base64Url;
use Ixudra\Curl\Facades\Curl;
use Spatie\ArrayToXml\ArrayToXml;

trait eServer{

    private function pulsa_fm(){
        $pin = '';
        $kodeproduk = '';
        $notujuan = '';
        $idtransaksi = '';
    }


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
    
    public function sendJabber($request){
        $cekserverpulsa = ServerPulsaStore::where('store_id', $this->toko);
        $server_id;

        if($cekserverpulsa->count() > 0){
            $server_id = $cekserverpulsa->first()->server_id;
        } else {
            $server_id = 1;
        }

        $jabberserv = env('JABBER_SERVER', '');
        $jabberapi  = env('JABBER_URL_API', '');
        $server     = ServerPulsa::find($server_id);        
        $username   = $server->agen_sender;
        $command    = $request->input('command');
        $origin     = $username.$jabberserv;
        $dest       = $server->server_sender.$jabberserv;

        $connection = DB::connection('mysql2');
        $u = $connection->table('spool')->where('username', $username)->get();
        //sebelum kirim command, cek dulu di database, jika ada offline msg, maka hapus agar tidak konflik response
        if($u->count() > 0){
            $connection->table('spool')->where('username', $username)->delete();
        }
        $sendcommand = Curl::to($jabberapi)
        ->withData([
                'type' => 'headline',
                'from' => $origin,
                'to'   => $dest,
                'body' => $command.'.'.$this->decodebase64($server->agen_pin)
            ])
        ->asJson(true) //as associative array
        ->post();

        if($sendcommand == 0){
            $u2 = DB::connection('mysql2')->select('select xml from spool where username = ?', array($username));
            if($u2->count() > 0){
                $parsed = Parser::xml($u2[0]->xml);
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
}