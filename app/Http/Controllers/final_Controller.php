<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class final_Controller extends Controller
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
function final_get(Request $req){
    // return $req;
        $final_data = DB::table('Booking_Form')	
->where('doc_status',"success")->where("verification","allotted")->get();

$filtered_data = [];

foreach ($final_data as $booking) {
    $amenity_data = DB::table('amenity_charges')
        ->where('ref_id', $booking->BF_id)  // Assuming 'ref_id' in amenity_charges refers to the 'BF_id' in Booking_Form
        ->whereNotNull([
            'electrical_reading_from1', 
            'electrical_reading_to1', 
            'electrical_reading_from2',
            'electrical_reading_to2',
            'electrical_reading_from3',
            'electrical_reading_to3',
            'electrical_reading_from4',
            'electrical_reading_to4',
            'electrical_reading_from5',
            'electrical_reading_to5',
            'electrical_reading_from6',
            'electrical_reading_to6',
            'numberOfUnits1', 
            'numberOfUnits2',
            'numberOfUnits3',
            'numberOfUnits4',
            'numberOfUnits5',
            'numberOfUnits6',
            'ratePerUnit', 
            'electrical_charging_amount', 
            'no_of_litres_water', 
            'cost_perlitre',
            'water_Amount'
        ])
        ->get();
          if (!$amenity_data->isEmpty()) {
            $filtered_data[] = $booking;
        }
    }
        // return $allotment_data;
          if(!empty($filtered_data)){
              $returndata= array("StatusResult"=>"success","final_table"=>$filtered_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    
}
    
    function get_final_electrical(Request $req){
        
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['ref_id'];
    //  return $id;
    
        // $id=$req->id;
        $final_ele_data=DB::table('amenity_charges')->where('ref_id', $id)->get();
        
        
      
        
        
        
        if($final_ele_data){
              $returndata= array("StatusResult"=>"success","final_elec_table"=>$final_ele_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
    function get_final_engineering(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['ref_id'];
    
        // $id=$req->id;
        $final_engg_data=DB::table('amenity_charges')->where('ref_id', $id)->get();
        if($final_engg_data){
              $returndata= array("StatusResult"=>"success","final_engg_table"=>$final_engg_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
    function get_final_water(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['ref_id'];
    
        // $id=$req->id;
        $final_water_data=DB::table('amenity_charges')->where('ref_id', $id)->get();
        if($final_water_data){
              $returndata= array("StatusResult"=>"success","final_water_table"=>$final_water_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
    function get_final_ST(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['ref_id'];
    
        // $id=$req->id;
        $final_ST_data=DB::table('amenity_charges')->where('ref_id', $id)->get();
        if($final_ST_data){
              $returndata= array("StatusResult"=>"success","final_ST_table"=>$final_ST_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
    
       function get_final_transaction(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $id = $dataArray['ref_id'];
    
        // $id=$req->id;
        $final_trans_data=DB::table('Transaction_table')->where('Ref_id', $id)->get();
        if($final_trans_data){
              $returndata= array("StatusResult"=>"success","final_trans_table"=>$final_trans_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
    
    function insertExcessData(Request $req){
         $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
    $id = $dataArray['ref_id'];
    $link = $dataArray['link'];
    $Eamount = $dataArray['Eamount'];
    
    $insertdata = DB::table('excess_table')->insert([
        'Ref_id' => $id,
        'ex_link'=>$link,
        'Ex_amount'=>$Eamount,
        'ex_status'=>"unpaid",
        ]);
        
        
        $sendmessage = DB::table('Booking_Form')->where('BF_id',$id)->get();
        
        $mobile = $sendmessage[0]->Mob_no;
        
        if($sendmessage){
       $smsData = [
                "filetype" => 2,
                "msisdn" => [$mobile],
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
        
        if($insertdata){
              $returndata= array("StatusResult"=>"success");
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