<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class transaction_Controller extends Controller
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


//get data function    
function transaction_get(Request $req){
    
        
        $transaction_data = DB::table('Transaction_table')->where('transaction_status',	
"SUCCESS")->where('level','1')->get();
        
          if($transaction_data){
              $returndata= array("StatusResult"=>"success","transaction_table"=>$transaction_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }

// function tansaction_success(Request $req){
//     $trans_succ=DB::table
// }

function tansaction_success(Request $req){
    
     $decryptedResponse = $this->decryption($req);
        
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['id'];
   
     $allotlevel = DB::table('Transaction_table')->where('Ref_id',$id)->update([
         'level'=>"2"]);
         if($allotlevel){
         $returndata=array("status"=>"Success");
         $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }   
       
}


}