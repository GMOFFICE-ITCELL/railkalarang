<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class payment_extension extends Controller
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
function extension_get(Request $req){
    // Get the current time in the format matching your database (assuming it's stored in 'Y-m-d H:i:s' format)
    $currentDateTime = date("Y-m-d H:i:s");

    // Fetch records where the paytimeclose is less than the current time and level is "1"
    $extend_data = DB::table('Booking_Form')
        ->where('paytime_close', '<', $currentDateTime) // Filter based on paytimeclose
        ->get();

    // Check if there are any records and return success or failure response accordingly
    if(count($extend_data) > 0) {
        $returndata = array("StatusResult" => "success", "extension_table" => $extend_data);
        $encryptedResponse = encryption($returndata);
        return array("return_response" => $encryptedResponse);
    } else {
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = encryption($returndata);
        return array("return_response" => $encryptedResponse);
    }
}


//get extendtime

// function extendtime(Request $req){
//  $decryptedResponse = decryption($req);
//     if (isset($decryptedResponse['error'])) {
//         return response()->json(['error' => $decryptedResponse['error']]);
//     }

//     $jsonString = $decryptedResponse ?? '';
//     $dataArray = json_decode($jsonString, true);

//      $book_id = $dataArray['pe_id'];
//      $closetime = $dataArray['closeTimeDate'];


//         $extend_data=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->update([
//              'paytime_close'=>$new_closetime
//             ]);

//              if(count($extend_data)>0){
//               $returndata= array("StatusResult"=>"success");
//               $encryptedResponse = encryption($returndata);
//                 return  array("return_response"=>$encryptedResponse);
//           }
//           else{
//               $returndata= array("StatusResult"=>"failure");
//                 $encryptedResponse = encryption($returndata);
//                 return array("return_response"=>$encryptedResponse);

//           }


// }

function extendtime(Request $req) {
    $decryptedResponse = decryption($req);

    if (isset($decryptedResponse['error'])) {
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);

    $book_id = $dataArray['pe_id'];
    $closetime = $dataArray['new_closetime'];  // Ensure this matches your JSON key

    try {
        // Convert the closetime to a DateTime object
        // $closeTimeDate = new \DateTime($closetime); // Reference the global namespace

        // // Add 12 hours
        // $closeTimeDate->modify('+12 hours');

        // Format the new closetime
        // $new_closetime = $closeTimeDate->format('Y-m-d H:i:s'); // Adjust format if needed
        date_default_timezone_set('Asia/Kolkata');
        $new_closetime = date("Y-m-d H:i:s", strtotime('+12 hours', strtotime($closetime)));

        // If the current time is past the original closetime,
        // this is already handled by DateTime's behavior.

        // Update the database
        $extend_data = DB::table('Booking_Form')->where('BF_id', $book_id)->update([
            'paytime_close' => $new_closetime
        ]);

        if ($extend_data > 0) {
            $returndata = array("StatusResult" => "success");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);
        } else {
            $returndata = array("StatusResult" => "failure");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);
        }
    } catch (Exception $e) {
        return response()->json(['error' => 'Failed to process date: ' . $e->getMessage()]);
    }
}



}
