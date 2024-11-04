<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class withdraw_Controller extends Controller
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


function withdrawfun(Request $req){
    // return $req;
 $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $id =$dataArray['uid'];
     
     $data=DB::table('Booking_Form')->where('BF_id',$id)->update([
         'verification'=>"withdraw",
         'level'=>"7",
         'verified_date' => date('Y-m-d')
         ]);
          $returndata = array("Status" => "Success");
        
         $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
     
}

function revert_fun(Request $req){
    // return $req;
 $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $id =$dataArray['id'];
     
     $data=DB::table('Booking_Form')->where('BF_id',$id)->update([
         'verification'=>"verified",
         'reverted_date' => date('Y-m-d')
         ]);
          $returndata = array("Status" => "Success");
        
         $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
     
}
}