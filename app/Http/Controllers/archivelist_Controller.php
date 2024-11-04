<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class archivelist_Controller extends Controller
{
       
function decryption($req) {
    $encryptedData = $req->input('encryptedData');
    $key = '452c55d16a18f2ac049b2ec24637571a';
    $iv = 'cetksum*rkj#4202';
 
 if ($decodedData = base64_decode($encryptedData, true)) {
        $decryptedJson = openssl_decrypt($decodedData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($decryptedJson === false) {
            return response()->json(['error' => 'Decryption failed']);
        }

        $decryptedArray = json_decode($decryptedJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'JSON decoding failed']);
        }

        return $decryptedArray; // Directly return the decrypted array
    } else {
        return response()->json(['error' => 'Invalid encoded data']);
    }
}


function encryption($data) {
    $key = '452c55d16a18f2ac049b2ec24637571a';
    $iv = 'cetksum*rkj#4202';
    $json_data = json_encode($data);
    $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $encoded = base64_encode($encrypted);

    return $encoded;
}

function get_Archive(Request $req){
        
         $decryptdt=$this->decryption($req);
      if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $dateselected = $dataArray['dateselected'];
 
    
    $archieve_data=DB::table('Booking_Form')->where('verification','archive')->where('From_date',$dateselected)->get();
           
        if(!$archieve_data->isEmpty()){
              $returndata= array("StatusResult"=>"success","archivedata"=>$archieve_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
    }
}

}