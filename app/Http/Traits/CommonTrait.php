<?php
namespace App\Http\Traits; 

use Base64Url\Base64Url;
use Ixudra\Curl\Facades\Curl;

trait CommonTrait{
    
    public function cekongkir($id_origin, $id_destination, $weight, $couriername, $couriercode){
        $cost = Curl::to(env('rajaongkir_api'))
        ->withData(['origin' => $id_origin, 'destination' => $id_destination, 'weight' => $weight, 'courier' => strtolower($couriername)])
        ->withHeader('key: '.env('rajaongkir_key'))
        // ->withContentType('application/json')
        ->asJson(true) //as associative array
        ->post();

        if($cost['rajaongkir']['status']['code'] == 200){
            foreach($cost['rajaongkir']['results'][0]['costs'] as $item){
                if(strtolower($item['service']) == strtolower($couriercode)){
                    $result = $item['cost'][0]['value'];
                }
            }
            $response = $result;
        } else {
            $response = response(['status' => 'error', 'message' => $cost['rajaongkir']['status']['description']], $cost['rajaongkir']['status']['code']);
        }
        return $response;
    }

    private function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function setInvoice($order_date){
        //format invoice = INVOICE-MP/20180924/XVIII/IX/RANDOM17STRING
        // return date('d', strtotime($request->tanggal)); //day
        // return date('m', strtotime($request->tanggal)); //month
        // return date('Y', strtotime($request->tanggal)); //year in 4 digits
        // return date('l', strtotime($request->tanggal)); //day of the week
        $tanggal = strtotime($order_date);
        $part1  = date('Ymd', $tanggal);
        $part2  = $this->numberToRomanRepresentation(date('y', $tanggal));
        $part3  = $this->numberToRomanRepresentation(date('m', $tanggal));
        $part4  = strtoupper(str_random(17));
        $invoiceno = 'INVOICE-MP/'.$part1.'/'.$part2.'/'.$part3.'/'.$part4;
        return $invoiceno;
    }

    public function encodebase64($str){
        $encoded = Base64Url::encode($str);
        return $encoded;
    }

    public function decodebase64($str){
        $decoded = Base64Url::decode($str);
        return $decoded;
    }
}