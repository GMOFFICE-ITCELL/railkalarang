<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class amenity_update_Controller extends Controller
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

public function updateFinalStatus(Request $req)
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
    // $ref_id = $dataArray['ref_id'];

    // SQL query to check if all fields are not null
$result = DB::update("
    UPDATE `amenity_charges`
    SET `final_status` = 'completed'
    WHERE `electrical_reading_from1` IS NOT NULL 
      AND `electrical_reading_to1` IS NOT NULL 
       AND `electrical_reading_from2` IS NOT NULL 
      AND `electrical_reading_to2` IS NOT NULL 
      AND `electrical_reading_from3` IS NOT NULL 
      AND `electrical_reading_to3` IS NOT NULL 
      AND `electrical_reading_from4` IS NOT NULL 
      AND `electrical_reading_to4` IS NOT NULL 
      AND `electrical_reading_from5` IS NOT NULL 
      AND `electrical_reading_to5` IS NOT NULL 
      AND`electrical_reading_from6` IS NOT NULL 
      AND `electrical_reading_to6` IS NOT NULL 
      AND `numberOfUnits1` IS NOT NULL
      AND `numberOfUnits2` IS NOT NULL
      AND `numberOfUnits3` IS NOT NULL
      AND `numberOfUnits4` IS NOT NULL
      AND `numberOfUnits5` IS NOT NULL
      AND `numberOfUnits6` IS NOT NULL
      AND `ratePerUnit` IS NOT NULL 
      AND `electrical_charging_amount` IS NOT NULL 
      AND `electrical_remarks` IS NOT NULL 
      AND `electrical_date` IS NOT NULL 
      AND `no_of_litres_water` IS NOT NULL 
      AND `water_Amount` IS NOT NULL 
      AND `water_remarks` IS NOT NULL 
      AND `water_date` IS NOT NULL 
      AND `eng_remarks` IS NOT NULL 
      AND `eng_Amount` IS NOT NULL 
      AND `eng_date` IS NOT NULL 
      AND `s_t_remarks` IS NOT NULL 
      AND `s_t_Amount` IS NOT NULL 
      AND `s_t_date` IS NOT NULL
");

// if (count($result) > 0) {
//     $status = DB::table('amenity_charges')
//                 ->update(['final_status' => 'completed']);

    
        
        if ($result) {
            $returndata = [
                "status_result" => "success",
            ];
        } else {
            $returndata = [
                "status_result" => "failure",
            ];
        }
    

    // Encrypt the response before returning
    $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];

}

//electrical update data
public function update_Electrical_Data(Request $req)
{
    
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
    $id = $dataArray['up_id'];
    $reading_from1 = $dataArray['Reading_from1'];
    $reading_to1 = $dataArray['Reading_to1'];
     $reading_from2 = $dataArray['Reading_from2'];
    $reading_to2 = $dataArray['Reading_to2'];
     $reading_from3 = $dataArray['Reading_from3'];
    $reading_to3 = $dataArray['Reading_to3'];
     $reading_from4 = $dataArray['Reading_from4'];
    $reading_to4 = $dataArray['Reading_to4'];
     $reading_from5 = $dataArray['Reading_from5'];
    $reading_to5 = $dataArray['Reading_to5'];
     $reading_from6 = $dataArray['Reading_from6'];
    $reading_to6 = $dataArray['Reading_to6'];
    $unit1 = $dataArray['Unit1'];
    $unit2 = $dataArray['Unit2'];
    $unit3 = $dataArray['Unit3'];
    $unit4 = $dataArray['Unit4'];
    $unit5 = $dataArray['Unit5'];
    $unit6 = $dataArray['Unit6'];
    $unitrate = $dataArray['unitrate'];
    $amount = $dataArray['amount'];
    $remarks = $dataArray['ele_remarks'];
    $date=date('Y-m-d');

    
    $electricalUpdate = DB::table('amenity_charges')->where('ref_id',$id)->update([
        'electrical_reading_from1'=>$reading_from1,
        'electrical_reading_to1'=>$reading_to1,
         'electrical_reading_from2'=>$reading_from2,
        'electrical_reading_to2'=>$reading_to2,
         'electrical_reading_from3'=>$reading_from3,
        'electrical_reading_to3'=>$reading_to3,
          'electrical_reading_from4'=>$reading_from4,
        'electrical_reading_to4'=>$reading_to4,
          'electrical_reading_from5'=>$reading_from5,
        'electrical_reading_to5'=>$reading_to5,
          'electrical_reading_from6'=>$reading_from6,
        'electrical_reading_to6'=>$reading_to6,
        'numberOfUnits1'=>$unit1,
          'numberOfUnits2'=>$unit2,
            'numberOfUnits3'=>$unit3,
              'numberOfUnits4'=>$unit4,
                'numberOfUnits5'=>$unit5,
                  'numberOfUnits6'=>$unit6,
        'ratePerUnit'=>$unitrate,
        'electrical_charging_amount'=>$amount,
        'electrical_remarks'=>$remarks,
        'electrical_date'=>$date,
        ]);
         if ($electricalUpdate) {
        $returndata = [
            "status_result" => "success",
        ];
    } else {
        $returndata = [
            "status_result" => "failure",
        ];
    }

    $encryptedResponse = $this->encryption($returndata);
    $response = ["return_response" => $encryptedResponse];

    // Call the updateFinalStatus function after the main update
    $this->updateFinalStatus($req);

    return $response;
     
}

//water update data
public function update_Water_Data(Request $req)
{
    
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
    $id = $dataArray['up_id'];
    $litres = $dataArray['water_stored'];
    $perlitre = $dataArray['perlit'];
    $amount = $dataArray['amount'];
    $remarks = $dataArray['water_remarks'];
    $date=date('Y-m-d');

    
    $waterUpdate =  DB::table('amenity_charges')->where('ref_id',$id)->update([
        'no_of_litres_water'=>$litres,
        'cost_perlitre'=>$perlitre,
        'water_Amount'=>$amount,
        'water_remarks'=>$remarks,
        'water_date'=>$date,
        ]);
        
         if ($waterUpdate) {
        $returndata = [
            "status_result" => "success",
        ];
    } else {
        $returndata = [
            "status_result" => "failure",
        ];
    }

    $encryptedResponse = $this->encryption($returndata);
    $response = ["return_response" => $encryptedResponse];

    // Call the updateFinalStatus function after the main update
    $this->updateFinalStatus($req);

    return $response;
}


//engg update data
public function update_engineering_Data(Request $req)
{
    
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
    $id = $dataArray['up_id'];
    $amount = $dataArray['amount'];
    $remarks = $dataArray['engg_remarks'];
    $date=date('Y-m-d');

    
    $enggUpdate =  DB::table('amenity_charges')->where('ref_id',$id)->update([
        'eng_Amount'=>$amount,
        'eng_remarks'=>$remarks,
        'eng_date'=>$date,
        ]);
        
               if ($enggUpdate) {
        $returndata = [
            "status_result" => "success",
        ];
    } else {
        $returndata = [
            "status_result" => "failure",
        ];
    }

    $encryptedResponse = $this->encryption($returndata);
    $response = ["return_response" => $encryptedResponse];

    // Call the updateFinalStatus function after the main update
    $this->updateFinalStatus($req);

    return $response;
}

//S&T update data

public function update_ST_Data(Request $req)
{
    
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
    $id = $dataArray['up_id'];
    $amount = $dataArray['amount'];
    $remarks = $dataArray['st_remarks'];
    $date=date('Y-m-d');

    
    $stUpdate =  DB::table('amenity_charges')->where('ref_id',$id)->update([
        's_t_Amount'=>$amount,
        's_t_remarks'=>$remarks,
        's_t_date'=>$date,
        ]);
        
       if ($stUpdate) {
        $returndata = [
            "status_result" => "success",
        ];
    } else {
        $returndata = [
            "status_result" => "failure",
        ];
    }

    $encryptedResponse = $this->encryption($returndata);
    $response = ["return_response" => $encryptedResponse];

    // Call the updateFinalStatus function after the main update
    $this->updateFinalStatus($req);

    return $response;
    
}


    
    
}