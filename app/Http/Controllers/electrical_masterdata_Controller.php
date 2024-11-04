<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class electrical_masterdata_Controller extends Controller
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

public function Electrical_mas(Request $req)
{
    // return $req;
    // Decrypt the incoming request
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    // Assuming decryptedResponse is an associative array, extract the ref_id
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
   $ele_unitcost = $dataArray['ele_unitcost'];
//   return $ele_unitcost;
    
       // Insert data into database
    $electricalmasterdata = DB::table('electrical_masterdata')->insert([
        'unit_cost' => $ele_unitcost,
    ]);

    if ($electricalmasterdata) {
        $returndata = array("StatusResult" => "success");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    } else {
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    }

}
function GetElectrical_mas(Request $req){
    
        
        $getele_data = DB::table('electrical_masterdata')->get();
        
          if($getele_data){
              $returndata= array("StatusResult"=>"success","ele_mas_data"=>$getele_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }

public function Electrical_mas_edit(Request $req)
{
    
      // return $req;
    // Decrypt the incoming request
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    // Assuming decryptedResponse is an associative array, extract the ref_id
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
    $ele_id = $dataArray['id'];
       
      $ele_edit= DB::table('electrical_masterdata')
        ->where('ele_mas_id',$ele_id)
        ->get();
   
         if ($ele_edit) {
      $returndata= array("StatusResult"=>"success","ele_data"=>$ele_edit);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    } else {
        $returndata= array("StatusResult"=>"failure");
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    }

}

public function Electrical_mas_update(Request $req)
{
   
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    // Assuming decryptedResponse is an associative array, extract the ref_id
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
    
    $ele_id = $dataArray['id'];
    $ele_cost = $dataArray['ele_unitcost'];
    
    $tbl = DB::Table("electrical_masterdata")->where('ele_mas_id',$ele_id)->update([
        'unit_cost' => $ele_cost,
        ]);
        
        if ($tbl) {
        $returndata = array("StatusResult" => "success");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    } else {
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    }
   
}

public function Electrical_mas_delete(Request $req)
{
   
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    // Assuming decryptedResponse is an associative array, extract the ref_id
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
    
    $ele_id = $dataArray['id'];
     $tbl = DB::Table("electrical_masterdata")->where('ele_mas_id',$ele_id)->delete();
      if ($tbl) {
        $returndata = array("StatusResult" => "success");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    } else {
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = $this->encryption($returndata);
        return response()->json(["return_response" => $encryptedResponse]);
    }
     
}


}