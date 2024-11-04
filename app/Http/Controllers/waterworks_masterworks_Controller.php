<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class waterworks_masterworks_Controller extends Controller
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


public function waterworks_mas(Request $req)
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
    $water_unitcost = $dataArray['water_unitcost'];
    $stored_water = $dataArray['stored_water'];
    $sur_charges = $dataArray['sur_charges'];

//   return $ele_unitcost;
    
       // Insert data into database
    $electricalmasterdata = DB::table('Waterworks_masterdata')->insert([
        'unit_cost' => $water_unitcost,
        'water_stored' => $stored_water,
        'sur_charge' => $sur_charges,
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
function GetWater_mas(Request $req){
    
        
        $getele_data = DB::table('Waterworks_masterdata')->get();
        
          if($getele_data){
              $returndata= array("StatusResult"=>"success","water_mas_data"=>$getele_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }

public function waterworks_mas_edit(Request $req)
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
    $water_id = $dataArray['id'];
       
       
      $water_edit= DB::table('Waterworks_masterdata')
        ->where('water_mas_id',$water_id)
        ->get();
   
         if ($water_edit) {
      $returndata= array("StatusResult"=>"success","water_data"=>$water_edit);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    } else {
        $returndata= array("StatusResult"=>"failure");
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    }

}

public function waterworks_mas_update(Request $req)
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
    
    $water_id = $dataArray['id'];
   $water_unitcost = $dataArray['water_unitcost'];
    $stored_water = $dataArray['stored_water'];
    $sur_charges = $dataArray['sur_charges'];
  
    
    
    
    
    $tbl = DB::Table("Waterworks_masterdata")->where('water_mas_id',$water_id)->update([
        'unit_cost' => $water_unitcost,
        'water_stored' => $stored_water,
        'sur_charge' => $sur_charges,
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

public function waterworks_mas_delete(Request $req)
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
    
    $water_id = $dataArray['id'];
    
     $tbl = DB::Table("Waterworks_masterdata")->where('water_mas_id',$water_id)->delete();
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