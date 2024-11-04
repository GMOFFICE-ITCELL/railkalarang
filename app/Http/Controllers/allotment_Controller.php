<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class allotment_Controller extends Controller
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
function allotment_get(Request $req){
        $allotment_data = DB::table('Booking_Form')
->where('level',"1")->get();
        // return $allotment_data;
          if(count($allotment_data)>0){
              $returndata= array("StatusResult"=>"success","allotment_table"=>$allotment_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);


           }
    }

function totalget(Request $req){

     // return $req;
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
     $pay_id = $dataArray['p_id'];

        $total_data = DB::table('Booking_Form')->where("BF_id",$pay_id)
->whereNotNull('paynowtime')->get();
        // return $allotment_data;
          if(count($total_data)>0){
              $returndata= array("StatusResult"=>"success","total_data"=>$total_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);

           }
    }



function verify_admin(Request $req){
    // return $req;
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
     $book_id = $dataArray['bid'];
     $book_mbno = $dataArray['mobile'];

    //     $book_id=$req->bid;
    //     $book_mbno=$req->mobile;

        $verify_data=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->update([

            'level'=>'2',
             'verification'=>'verified_by_admin',
              'verified_date' => date('Y-m-d')
            ]);

            $get_level=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->where('level', '2')->get();

            if(count($get_level)>0){

                 date_default_timezone_set('Asia/Kolkata');

    // Get the current time
    $currentDateTime = date("Y-m-d H:i:s"); // Example format: 2024-10-07 14:30:45

    // Calculate closetime (24 hours from the current time)
    $closetime = date("Y-m-d H:i:s", strtotime('+24 hours', strtotime($currentDateTime)));

    // Update the database with both paynowtime and closetime
    $inserttime = DB::table('Booking_Form')->where('BF_id', $book_id)->update([
        'paynowtime' => $currentDateTime,
        'paytime_close' => $closetime
    ]);
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


     function reject_admin(Request $req){
           // return $req;
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
     $book_id = $dataArray['bid'];
     $book_mbno = $dataArray['mobile'];
           $remark_dysecy = $dataArray['remark_dysecy'];

        $verify_data=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->update([

            'level'=>'9',
            'remarks'=>$remark_dysecy,
            'verification'=>'admin_rejected',
             'verified_date' => date('Y-m-d')

            ]);

            $get_level=DB::TABLE('Booking_Form')->where('BF_id',$book_id)->where('Mob_no',$book_mbno)->where('level','9')->get();

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

    function reject_get2(Request $req){


        $book_data = DB::table('Booking_Form')->where('verification',['rejected_by_cos','admin_rejected'])->where('level','9')->get();
        // return $book_data;
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


}
