<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class receipt_Controller extends Controller
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


function getreceipt (Request $req){

     $decryptedResponse = decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $order_no = $dataArray['orderno'];

     $rdata =DB::table('Transaction_table')->where('Order_number',$order_no)->get();
     if($rdata){
         $returndata=array("status"=>"Success","recieptdata"=>$rdata);
         $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }
}
function getstatusreceipt(Request $req){

     $decryptedResponse = decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['id'];

     $rdata =DB::table('Transaction_table')->where('Ref_id',$id)->get();
     if($rdata){
         $returndata=array("status"=>"Success","recieptdata"=>$rdata);
         $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }
}

function getexcessreceipt (Request $req){

     $decryptedResponse = decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $order_no = $dataArray['orderno'];

     $rdata =DB::table('excess_Transaction_table')->where('Order_number',$order_no)->get();
     if($rdata){
         $returndata=array("status"=>"Success","recieptdata"=>$rdata);
         $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }
}



function setallot(Request $req){

     $decryptedResponse = decryption($req);

       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['id'];

     $allotlevel = DB::table('Transaction_table')->where('Ref_id',$id)->update([
         'level'=>"4"]);
         $allotlevel = DB::table('Booking_Form')->where('BF_id',$id)->update([
         'level'=>"4",
         'verification'=>"allotted"]);

         if($allotlevel){
         $returndata=array("status"=>"Success");
         $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        }

}

}
