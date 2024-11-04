<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class tablevisiblity_Controller extends Controller
{

//for decryption
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

//for encryption
function encryption($data) {
    $key = '452c55d16a18f2ac049b2ec24637571a';
    $iv = 'cetksum*rkj#4202';
    $json_data = json_encode($data);
    $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $encoded = base64_encode($encrypted);

    return $encoded;
}

//get data function
function tablevisible(Request $req){


        $book_data = DB::table('Booking_Form')
//            ->where('verification',NULL)
//            ->where('doc_status',"success")
            ->get();

          if($book_data){
              $returndata= array("StatusResult"=>"success","book_table"=>$book_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);


           }
    }

//delete function
    function bookingdelete(Request $req){
          $decryptdt=decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['id'];

        // $id=$req->id;
        $value=DB::table('Booking_Form')->where('BF_id', $id)->delete();
        if($value){
            $returndata= array ("Status"=>"Success");
             $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
        else{
             $returndata= array ("Status"=>"failure");
             $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
    }

    function dialogueData(Request $req){
          $decryptdt=decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['id'];

        // $id=$req->id;
        $value=DB::table('Booking_Form')->where('BF_id', $id)->get();
        if(count($value)>0){
            $returndata= array ("Status"=>"Success","iddata"=>$value);
             $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
        else{
             $returndata= array ("Status"=>"failure");
             $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
    }

}
