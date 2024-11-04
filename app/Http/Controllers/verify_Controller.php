<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class verify_Controller extends Controller
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
    
// function verify(Request $req){
//     // return $req;
//  $decryptedResponse = $this->decryption($req);
// //  return ($decryptedResponse);

//       // Check if decryption was successful
//     if (isset($decryptedResponse['error'])) {
//         // Handle the error appropriately
//         return response()->json(['error' => $decryptedResponse['error']]);
//     }
        
//     // Assuming decryptedResponse is an associative array, you can extract values like this:

//     $jsonString = $decryptedResponse ?? '';
//     $dataArray = json_decode($jsonString, true);
//      $book_id = $dataArray['bid'];
//      $book_mbno = $dataArray['mobile'];  
          
//     //   $book_id=$req->bid;
//     //     $book_mbno=$req->mobile;
        
//     $verify_data=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->update([
            
//             'level'=>'1',
//              'verification'=>'verified_by_cos',
//               'verified_date' => date('Y-m-d') 
//             ]);
//         //  return $verify_data;   
// //    // Fetch the current booking's date and slot
//     $currentBooking = DB::table('Booking_Form')
//         ->select('From_date', 'slot')
//         ->where('BF_id', $book_id)
//         ->first();

//     if ($currentBooking) {
//         $bookingDate = $currentBooking->From_date;
//         $slotTime = $currentBooking->slot;

//         // Fetch other bookings with the same date and slot
//         $sameSlotBookings = DB::table('Booking_Form')
//             ->where('From_date', $bookingDate)
//             ->where('slot', $slotTime)
//             ->where('BF_id', '!=', $book_id) // Exclude the verified booking
//             ->where('verification', '!=', 'verified_by_cos') // Exclude verified bookings
//             ->get();
//             return $sameSlotBookings;
    
    
//         // Archive other bookings if they exist
//         if ($sameSlotBookings->isNotEmpty()) {
//             DB::table('Booking_Form')
//                 ->where('From_date', $bookingDate)
//                 ->where('slot', $slotTime)
//                 ->where('BF_id', '!=', $book_id)
//                 ->update([
//                     'verification' => 'archive',
//                     'level' => '5'
//                 ]);
//         }
         
        
//             $get_level=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->where('level', '1')->get();
            
//             if(count($get_level)>0){
                
//                 $smsData = [
//                 "filetype" => 2,
//                 "msisdn" => [$book_mbno],
//                 "language" => 0,
//                 "credittype" => 7,
//                 "senderid" => "SCRSMS",
//                 "templateid" => 0,
//                 "message" => "Login OTP  for Railway App/Portal . Do not share pls.",
//                 "ukey" => "SjC4tJEbLu83HQucsC0RUnUag"
//             ];

//             $curl = curl_init();
//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => "125.16.147.178/VoicenSMS/webresources/CreateSMSCampaignPost",
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => "",
//                 CURLOPT_MAXREDIRS => 20,
//                 CURLOPT_TIMEOUT => 30,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => "POST",
//                 CURLOPT_POSTFIELDS => json_encode($smsData),
//                 CURLOPT_HTTPHEADER => array(
//                     "content-type: application/json"
//                 ),
//             ));

//             $resp = curl_exec($curl);
//             // return $resp;
//             $dcode=json_decode($resp);
//             $valsts=$dcode->status;
//             $valvalue=$dcode->value;
//             $err = curl_error($curl);

//             curl_close($curl);
              
// //dy.sec
// $mobileno2='9177385289';
//             $smsData = [
//                 "filetype" => 2,
//                 "msisdn" => [$mobileno2],
//                 "language" => 0,
//                 "credittype" => 7,
//                 "senderid" => "SCRSMS",
//                 "templateid" => 0,
//                 "message" => "Login OTP for Railway App/Portal. Do not share pls.",
//                 "ukey" => "SjC4tJEbLu83HQucsC0RUnUag"
//             ];

//             $curl = curl_init();
//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => "125.16.147.178/VoicenSMS/webresources/CreateSMSCampaignPost",
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => "",
//                 CURLOPT_MAXREDIRS => 20,
//                 CURLOPT_TIMEOUT => 30,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => "POST",
//                 CURLOPT_POSTFIELDS => json_encode($smsData),
//                 CURLOPT_HTTPHEADER => array(
//                     "content-type: application/json"
//                 ),
//             ));

//             $resp = curl_exec($curl);
//             $dcode = json_decode($resp);
//             $valsts = $dcode->status;
//             $valvalue = $dcode->value;
//             $err = curl_error($curl);

//             curl_close($curl);            
                
//             }
            
//              else{
//               $valsts ='';
//               $valvalue='';
//           }
    

//         if($valsts == "success" && $valvalue == "accepted"){
//         $returndata = array("Status" => "Success","BF_id"=>$book_id);
        
//          $encryptedResponse = $this->encryption($returndata);
//                 return  array("return_response"=>$encryptedResponse);
    

//         }
//         else {
//             $returndata= array("Status" => "ftsendo");//failed to send otp
//              $encryptedResponse = $this->encryption($returndata);
//                 return  array("return_response"=>$encryptedResponse);
//         }
//     }


//  }

function verify(Request $req){
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        return response()->json(['error' => $decryptedResponse['error']]);
    }
        
    // Assuming decryptedResponse is an associative array
    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
    $book_id = $dataArray['bid'];
    $book_mbno = $dataArray['mobile'];  
        
    // Update booking form
    $verify_data = DB::table('Booking_Form')->where('BF_id', $book_id)->where('Mob_no', $book_mbno)->update([
        'level' => '1',
        'verification' => 'verified_by_cos',
        'verified_date' => date('Y-m-d') 
    ]);

    // Fetch the current booking's date and slot
    $currentBooking = DB::table('Booking_Form')
        ->select('From_date', 'slot')
        ->where('BF_id', $book_id)
        ->first();

    // Check if the current booking was found
    if ($currentBooking) {
        $bookingDate = $currentBooking->From_date;
        $slotTime = $currentBooking->slot;

        // Debugging - Check values of date and slot
        // Log the booking date and slot to see if they're valid
        error_log("Booking Date: $bookingDate, Slot Time: $slotTime");

        // Fetch other bookings with the same date and slot
        $sameSlotBookings = DB::table('Booking_Form')
    ->where('From_date', $bookingDate)
    ->where('slot', $slotTime)
    ->where('BF_id', '!=', $book_id)
    ->where(function ($query) {
        $query->where('verification', '!=', 'verified_by_cos')
              ->orWhereNull('verification');
    })
    ->get();

    // return $sameSlotBookings;
        // Debugging - Check if any records were found
        error_log("Same Slot Bookings: " . json_encode($sameSlotBookings));


// Fetch other bookings with the same date and slot

if ($sameSlotBookings->isNotEmpty()) {
    // Loop through each booking and update them individually
    foreach ($sameSlotBookings as $booking) {
        DB::table('Booking_Form')
            ->where('BF_id', $booking->BF_id)
            ->update([
                'verification' => 'archive',
                'level' => '5'
            ]);
    }
}


        // Check if level is 1 and send SMS if needed
        $get_level = DB::table('Booking_Form')->where('BF_id', $book_id)->where('Mob_no', $book_mbno)->where('level', '1')->get();

        if (count($get_level) > 0) {
            // Send SMS code
            $smsData = [
                "filetype" => 2,
                "msisdn" => [$book_mbno],
                "language" => 0,
                "credittype" => 7,
                "senderid" => "SCRSMS",
                "templateid" => 0,
                "message" => "Login OTP  for Railway App/Portal . Do not share pls.",
                "ukey" => "SjC4tJEbLu83HQucsC0RUnUag"
            ];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "125.16.147.178/VoicenSMS/webresources/CreateSMSCampaignPost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 20,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($smsData),
                CURLOPT_HTTPHEADER => ["content-type: application/json"],
            ]);

            $resp = curl_exec($curl);
            $dcode = json_decode($resp);
            $valsts = $dcode->status;
            $valvalue = $dcode->value;
            $err = curl_error($curl);
            curl_close($curl);

            // Send SMS to the second mobile number
            $mobileno2 = '9177385289';
            $smsData['msisdn'] = [$mobileno2];
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($smsData));
            curl_exec($curl);
            curl_close($curl);
        }

        // Final response
        if ($valsts == "success" && $valvalue == "accepted") {
            $returndata = ["Status" => "Success", "BF_id" => $book_id];
            $encryptedResponse = $this->encryption($returndata);
            return ["return_response" => $encryptedResponse];
        } else {
            $returndata = ["Status" => "ftsendo"];
            $encryptedResponse = $this->encryption($returndata);
            return ["return_response" => $encryptedResponse];
        }
    } else {
        return response()->json(['error' => 'Booking not found']);
    }
}



    
    
function reject(Request $req){
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
     $book_id = $dataArray['bid'];
     $book_mbno = $dataArray['mobile'];  
      $remark_cos = $dataArray['remark_cos'];  
        
        $verify_data=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->update([
            
            'level'=>'9',
            'remarks'=>$remark_cos,
            'verification'=>'rejected_by_cos',
             'verified_date' => date('Y-m-d')
            
            ]);
            
            $get_level=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->where('level', '9')->get();
            
            if(count($get_level)>0){
                
                $smsData = [
                "filetype" => 2,
                "msisdn" => [$book_mbno],
                "language" => 0,
                "credittype" => 7,
                "senderid" => "SCRSMS",
                "templateid" => 0,
                "message" => "Login OTP  for Railway App/Portal . Do not share pls.",
                "ukey" => "SjC4tJEbLu83HQucsC0RUnUag"
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "125.16.147.178/VoicenSMS/webresources/CreateSMSCampaignPost",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 20,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($smsData),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json"
                ),
            ));

            $resp = curl_exec($curl);
            // return $resp;
            $dcode=json_decode($resp);
            $valsts=$dcode->status;
            $valvalue=$dcode->value;
            $err = curl_error($curl);

            curl_close($curl);
                
            }
            
             else{
              $valsts ='';
              $valvalue='';
          }
        
        if($valsts == "success" && $valvalue == "accepted"){
        $returndata = array("Status" => "Success","BF_id"=>$book_id);
        
         $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
        else {
            $returndata= array("Status" => "ftsendo");//failed to send otp
             $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        }
                

    }
    
}