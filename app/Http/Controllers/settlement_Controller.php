<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class settlement_Controller extends Controller
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


    

    function get_settlement_data(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $date = $dataArray['date'];
     
  // Query for all settlements
   $booking_settle_data = DB::table('Booking_Form') 
   ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
    ->where('Transaction_table.Transaction_date', $date)
    ->whereNotNull('Transaction_table.transaction_id')  // Use whereNotNull to check for non-null values
    ->get();
        
        
    // Query for successful settlements
    
    $booking_success_settle_data = DB::table('Booking_Form')
        ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
         ->where('Transaction_table.Transaction_date', $date)
    ->whereNotNull('Transaction_table.transaction_id')
        ->where('Transaction_table.transaction_status', 'SUCCESS')
        ->select('Booking_Form.*', 'Transaction_table.transaction_status')  // Adjust columns as needed
        ->get();
                
                
    // Query for failed settlements
    
    $booking_fail_settle_data = DB::table('Booking_Form')
        ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
         ->where('Transaction_table.Transaction_date', $date)
    ->whereNotNull('Transaction_table.transaction_id')
        ->where('Transaction_table.transaction_status', 'FAIL')
        ->select('Booking_Form.*', 'Transaction_table.transaction_status')  // Adjust columns as needed
        ->get();


    // Check if any data exists and return it
    if ($booking_settle_data->isNotEmpty()) {
        $returndata = array(
            "StatusResult" => "success",
            "settlement" => $booking_settle_data,
            "settlement_success" => $booking_success_settle_data,
            "settlement_fail" => $booking_fail_settle_data
        );
        $encryptedResponse = $this->encryption($returndata);
        return array("return_response" => $encryptedResponse);
    } else {
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = $this->encryption($returndata);
        return array("return_response" => $encryptedResponse);
    }
    }
}