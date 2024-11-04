<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class refundstatement_Controller extends Controller
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



// function refund_statement(Request $req){

//      $decryptdt=decryption($req);
//       if (isset($decryptdt['error'])) {
//         return response()->json(['error' => $decryptdt['error']]);
//     }
//       $jsonString = $decryptdt ?? '';
//     $dataArray = json_decode($jsonString, true);


//      $fromdate = $dataArray['fromdate'];
//      $todate = $dataArray['todate'];

//   $refundst = DB::table('Booking_Form')
//         ->join('amenity_charges', 'Booking_Form.BF_id', '=', 'amenity_charges.ref_id')
//         ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
//         ->join('dr__type_table', 'Booking_Form.type', '=', 'dr__type_table.type')
//         ->whereNotNull('Transaction_table.transaction_id')
//         ->where('Transaction_table.transaction_status', 'SUCCESS')
//         ->select(
//             'Booking_Form.*',
//             'amenity_charges.
//             *',
//             'Transaction_table.*',
//             'dr__type_table.*'
//         )
//         ->whereBetween('Booking_Form.Date', [$fromdate, $todate])  // Filter between the dates
//         ->get();
// return $refundst;

//       if($refundst){
//          $returndata= array("StatusResult"=>"success","refundst"=>$refundst);
//               $encryptedResponse = $this->encryption($returndata);
//                 return  array("return_response"=>$encryptedResponse);
//       }

//       else{
//             $returndata= array("StatusResult"=>"failure");
//             $encryptedResponse = $this->encryption($returndata);
//             return array("return_response"=>$encryptedResponse);

//           }
//     }
    function refund_statement(Request $req) {
        // return $req;
    // Decrypt the incoming data
    $decryptdt = decryption($req);

    // Check for decryption errors
    if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }

    // Decode the decrypted JSON data
    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);


    // Extract fromdate and todate
    $fromdate = $dataArray['fromdate'];
    $todate = $dataArray['todate'];

    // Perform the query to fetch data from multiple tables with JOINs and date filtering
    $refundst = DB::table('Booking_Form')
        ->join('amenity_charges', 'Booking_Form.BF_id', '=', 'amenity_charges.ref_id')
        ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
         ->join('dr__type_table', 'Booking_Form.type', '=', 'dr__type_table.type')

        ->whereNotNull('Transaction_table.transaction_id')
        ->where('Transaction_table.transaction_status','SUCCESS')
        ->select(
            'Booking_Form.*',  // Select all columns from Booking_Form
            'amenity_charges.*',  // Select all columns from amenity_charges
            'Transaction_table.*',
            'dr__type_table.*'// Select all columns from Transaction_table

        )
        ->whereBetween('Booking_Form.Date', [$fromdate, $todate])  // Filter between the dates
        ->get();
        // return $refundst;

/*     $deposit = [];
    $amounts = [];
    $types = [];*/

  foreach ($refundst as $record) {
    $deposit = $record->deposit;
    $ele_amount= $record->electrical_charging_amount;
    $water_Amount= $record->water_Amount;
    $eng_Amount= $record->eng_Amount;
    $s_t_Amount= $record->s_t_Amount;
    $paid_Amount = $record->Amount;
    $totalcharges = $ele_amount + $water_Amount + $eng_Amount + $s_t_Amount;

    $refundamount=$deposit-$totalcharges;
    $refund_insert = DB::table('Booking_Form')->where('BF_id',$record->BF_id)->update([
        'Refund_amount'=>$refundamount
        ]);

}
 $refundsdata = DB::table('Booking_Form')
        ->join('amenity_charges', 'Booking_Form.BF_id', '=', 'amenity_charges.ref_id')
        ->join('Transaction_table', 'Booking_Form.BF_id', '=', 'Transaction_table.Ref_id')
         ->join('dr__type_table', 'Booking_Form.type', '=', 'dr__type_table.type')

        ->whereNotNull('Transaction_table.transaction_id')
        ->where('Transaction_table.transaction_status','SUCCESS')
        ->select(
            'Booking_Form.*',  // Select all columns from Booking_Form
            'amenity_charges.*',  // Select all columns from amenity_charges
            'Transaction_table.*',
            'dr__type_table.*'// Select all columns from Transaction_table

        )
        ->whereBetween('Booking_Form.Date', [$fromdate, $todate])  // Filter between the dates
        ->get();


    // Check if records are found
    if($refundsdata->isNotEmpty()) {
        $returndata = array("StatusResult" => "success", "refundst" => $refundsdata);


        $encryptedResponse = $this->encryption($returndata);
        return array("return_response" => $encryptedResponse);
    } else {
        // If no records found, return failure response
        $returndata = array("StatusResult" => "failure");
        $encryptedResponse = $this->encryption($returndata);
        return array("return_response" => $encryptedResponse);
    }
}



}
