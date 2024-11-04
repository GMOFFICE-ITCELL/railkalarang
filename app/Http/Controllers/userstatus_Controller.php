<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class userstatus_Controller extends Controller
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
   
  
    
    
  function usersend_mobile(Request $req){
   
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
     $mobile = $dataArray['mobile'];
 
   
//   $bid = $req->bid;
//   return $bid;
    // $mobile = (string) $req->Mob_no;
 
    $tbl=DB::table('Booking_Form')->where('Mob_no',$mobile)->get();
  
$otpdate=date('Y-m-d');
     
    if(count($tbl)>0){
     
     $rand=rand(111111,999999);
    // $rand = 1234;
    $key = bin2hex(random_bytes(32));
     $app = "RBMS";
     

   
    $tbl=DB::table('otp_table')->insert(['otp' => $rand,'token'=>$key,'Mob_no'=>$mobile,'otpdate'=>$otpdate]);
   
        if($tbl){
            
            // Send SMS with OTP
            $smsData = [
                "filetype" => 2,
                "msisdn" => [$mobile],
                "language" => 0,
                "credittype" => 7,
                "senderid" => "SCRSMS",
                "templateid" => 0,
                "message" => "Login OTP $rand for Railway App/Portal $app. Do not share pls.",
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
       
           
        if($valsts == "success" && $valvalue == "accepted"){
        // return array("status" => "success","token"=>$key,"mobile"=>$mobile,"id"=>$bid);
                      
                       $returndata = array("status" => "success","token"=>$key,"mobile"=>$mobile);
              
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
                         
                         
        }
        else {
            // return array("status" => "failure");//failed to send otp
            
              $returndata = array("status" => "failure");
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
                         
        }
                     
    }
    else {
        // return array("status" => "ftso");
                   
              $returndata = array("status" => "ftso");
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
         }
    }// end of otp store
    else{
        //  return array("status" => "invm");
                    
              $returndata = array("status" => "invm");
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    }
    //end of mobile validation
  
  }


   
  function verifyUserotp(Request $req){
      
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
    $mobile = $dataArray['mobile'];
    $token=$dataArray['token'];
    $verigyOtp=$dataArray['verotp'];

      
       
//  $mobile= (string) $req->Mob_no;
//  $id=$req->BF_id;
 
 
   
    $tbl=DB::Table('otp_table')->where('token',$token)->where('otp',$verigyOtp);
  
     if ($tbl) {
       
    //   $data =DB::table('Booking_Form')->where('Mob_no',$mobile)->get();
      
        //   return array("status"=>"verified","getpayment"=>$mobile,"id"=>$id);
           
           
           
           
           $returndata = array("status"=>"Success","mobile"=>$mobile);
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
        
    } else {
//   return array("status"=>"failed");
   
    $returndata = array("status"=>"failed");
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
   
   
  
    
    } 


 }
 
 
 
    
}