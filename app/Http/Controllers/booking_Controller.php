<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class booking_Controller extends Controller
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
    
function decryptdata($req){
       
            $req = (substr($req,20));
            $decoded_data = json_decode(base64_decode($req));
            $req = $decoded_data; 
            return $req;
        }
        
    //  function type_drp(Request $req){

    //     $decryptdt=$this->decryption($req);
    //   //  return $decryptdt;
         
    //   // return json_encode($decrypdt);
       
    //      if (isset($decryptdt['error'])) {
    //     // Handle the error appropriately
    //     return response()->json(['error' => $decryptdt['error']]);
    //      }
  
    // $jsonString = $decryptdt ?? '';
    // $dataArray = json_decode($jsonString, true);
    // $token = $dataArray['token'];
    // $desig = $dataArray['desig'] ?? null; 
    // $Place = $dataArray['place'] ?? null;
    // $Option = $dataArray['option'] ?? null;
    // // return $Place;
    // //  return response()->json(['Option' => $Option,'Place' =>$Place,'desig' =>$desig]);
    // if($token=='20244562429'){
        
    //     $type_data = DB::table('dr__type_table')->select('type')->get();
        
    //         if($type_data){
                
    //         $returnsuccessdata =  $this->encryption(array("StatusResult"=>"success","type_drp"=>$type_data));   
          
    //         return json_encode($returnsuccessdata);
    //         }
    //         else{
    //             $returnfailure =$this->encryption(array("StatusResult"=>"failure"));
    //             return json_encode($returnfailure);
    //         }
        
    // }
    // else{
    //     $returntokenmismatch= $this->encryption(array("StatusResult"=>"TokenMismatch"));
    //     return json_encode($returntokenmismatch);
    // }
    
    // }//end type_drp function
    
    
  function type_drp(Request $req) {
    // $decryptdt = $this->decryption($req);

    // if (isset($decryptdt['error'])) {
    //     return response()->json(['error' => $decryptdt['error']]);
    // }

    // $jsonString = $decryptdt ?? '';
    // $dataArray = json_decode($jsonString, true);
    // return $dataArray;
     $decryptedResponse = $this->decryption($req);
    if (isset($decryptedResponse['error'])) {
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
    
    $token = $dataArray['token'];
    // $token = $dataArray['token'];
    $desig = $dataArray['desig'] ?? null; 
    $Place = $dataArray['place'] ?? null;
    $Option = $dataArray['Option'] ?? null;
    //   return response()->json(['Option' => $Option,'Place' =>$Place,'desig' =>$desig]);
    if ($token == '20244562429') {
        // Fetch types based on the desig and place conditions
        $type_data = [];
        if ($Option === 'no' && $desig === 'Associations') {

            $type_data = DB::table('dr__type_table')->where('category', 'Associations')->select('type')->get();
            // return $Option;
            
        // return $type_data;
        } elseif ($Option === 'no' && $desig === 'General_public') {
            $type_data = DB::table('dr__type_table')->where('category', 'General_public')->select('type')->get();
            // return $Option;
           } elseif ($Option === 'no' && $desig === 'PSU_staff') {
            $type_data = DB::table('dr__type_table')->where('category', 'PSU for railway')->select('type')->get();
        } elseif (($Option != 'no') && 
                  (($Place === 'secunderabad') || ($Place === 'hyderabad') || ($Place === 'other'))) {
            $type_data = DB::table('dr__type_table')->where('category', 'Railway_Employees')->select('type')->get();
            
        } 
        
        else {
            $type_data = DB::table('dr__type_table')->select('type','category')->get();
        }
// return $type_data;
        if ($type_data) {
    $type_data = $type_data->toArray(); // Convert Laravel collection to array
    $returnsuccessdata = $this->encryption(array("StatusResult" => "success", "type_drp" => $type_data));   
      return json_encode($returnsuccessdata); // Use response()->json for proper encoding
} else {
    $returnfailure = $this->encryption(array("StatusResult" => "failure"));
     return json_encode($returnfailure);
    
       
               
}

    } else {
        $returntokenmismatch = $this->encryption(array("StatusResult" => "TokenMismatch"));
        return json_encode($returntokenmismatch);
    }
}



     function dependent_drp(Request $req){

        $decryptdt=$this->decryption($req);
       //  return $decryptdt;
         
      // return json_encode($decrypdt);
       
         if (isset($decryptdt['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptdt['error']]);
         }
         
         
         
    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
    $token = $dataArray['token'];
   
    if($token=='rkj*2024#456'){
        
        $dep_data = DB::table('dependent_dropdown')->select('dependent_relation')->get();
        
            if($dep_data){
                
            $returnsuccessdata =  $this->encryption(array("StatusResult"=>"success","dependent_drp"=>$dep_data));   
          
            return json_encode($returnsuccessdata);
            }
            else{
                $returnfailure =$this->encryption(array("StatusResult"=>"failure"));
                return json_encode($returnfailure);
            }
        
    }
    else{
        $returntokenmismatch= $this->encryption(array("StatusResult"=>"TokenMismatch"));
        return json_encode($returntokenmismatch);
    }
    
    }//end type_drp function
    
function payband_drp(Request $req){

       $decryptdt=$this->decryption($req);
       //  return $decryptdt;
         
      // return json_encode($decrypdt);
       
         if (isset($decryptdt['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptdt['error']]);
         }
      
    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
    $token = $dataArray['token'];
   
    if($token=='rkj*2024#456'){
        
        $pay_data = DB::table('payband_level_drp')->select('level')->get();
        
            if($pay_data){
                
            $returnsuccessdata =  $this->encryption(array("StatusResult"=>"success","payband_drp"=>$pay_data));   
          
            return json_encode($returnsuccessdata);
            }
            else{
                $returnfailure =$this->encryption(array("StatusResult"=>"failure"));
                return json_encode($returnfailure);
            }
        
    }
    else{
        $returntokenmismatch= $this->encryption(array("StatusResult"=>"TokenMismatch"));
        return json_encode($returntokenmismatch);
    }
    
    }//end type_drp function
    
 
// function reg_ins_dt(Request $req){
//         // return $req;
//     $decryptdt = $this->decryption($req);
//     if (isset($decryptdt['error'])) {
//         return response()->json(['error' => $decryptdt['error']]);
//     }

//     $jsonString = $decryptdt ?? '';
//     $dataArray = json_decode($jsonString, true);
//     $token = $dataArray['token'];

//     if ($token == 'rkj*2024#456') {
//         $typedrp = $dataArray['typedrp'] ?? null;
//         $Name = $dataArray['Name'] ?? null;
//         $Bank_name = $dataArray['Bank_name'] ?? null;
//         $Desig_ofice = $dataArray['Desig_ofice'] ?? null;
//         $PF_Ticket = $dataArray['PF_Ticket'] ?? null;
//         $Office_Number = $dataArray['Office_Number'] ?? null;
//         $Mob_no = $dataArray['Mob_no'] ?? null;
//         $Res = $dataArray['Res'] ?? null;
//         $Branch = $dataArray['Branch'] ?? null;
//         $Account_num = $dataArray['Account_num'] ?? null;
//         $ifsc_code = $dataArray['ifsc_code'] ?? null;
//         $Date_Required = $dataArray['Date_Required'] ?? null;
//         $Purpose = $dataArray['Purpose'] ?? null;
//         $relation = $dataArray['relation'] ?? null;
//         $dependent = $dataArray['dependent'] ?? null;

//         $reg_data = [
//             'type' => $typedrp,
//             'Name' => $Name,
//             'Bank_name' => $Bank_name,
//             'Desig_ofice' => $Desig_ofice,
//             'PF_Ticket' => $PF_Ticket,
//             'Office_Number' => $Office_Number,
//             'Mob_no' => $Mob_no,
//             'Res' => $Res,
//             'Branch' => $Branch,
//             'ifsc_code' => $ifsc_code,
//             'Account_num' => $Account_num,
//             'Date_Required' => $Date_Required,
//             'Purpose' => $Purpose,
//             'relation' => $relation,
//             'dependent' => $dependent
//         ];

//         $ins_data = DB::table('Booking_Form')->insert($reg_data);
//         $insertedId = DB::table('Booking_Form')->insertGetId($reg_data);

//         if ($ins_data) {
//             $returnsuccessdata = $this->encryption(array("StatusResult" => "success", "ReturnData" => $ins_data, "inserted_id" => $insertedId));   
//             return json_encode($returnsuccessdata);
//         } else {
//             $returnfailuredata = $this->encryption(array("StatusResult" => "failure"));
//             return json_encode($returnfailuredata);
//         }
//     } else {
//         $returntokenmismatch = $this->encryption(array("StatusResult" => "TokenMismatch"));
//         return json_encode($returntokenmismatch);
//     }
// }

public function reg_ins_dt(Request $req)
{
    $decryptdt = $this->decryption($req);
    if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }

    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
   
    $token = $dataArray['token'];

    if ($token == 'rkj*2024#456') {
        $typedrp = $dataArray['typedrp'];
        $Name = $dataArray['Name'];
        $slot = is_array($dataArray['slot']) ? implode(',', $dataArray['slot']) : $dataArray['slot']; // Ensure slot is a string
        $city = $dataArray['city'];
        $state = $dataArray['state'];
        $country = $dataArray['country'];
        $address = $dataArray['address'];
        $pincode = $dataArray['pincode'];
        $Bank_name = $dataArray['Bank_name'];
        $Desig_ofice = $dataArray['Desig_ofice'];
        $PF_Ticket = $dataArray['PF_Ticket'];
        
         $place_work = $dataArray['place_work'];
        $Grade_pay = $dataArray['Grade_pay'];
        $hrmsid = $dataArray['hrmsid'];
        $Office_Number = $dataArray['Office_Number'];
        $Mob_no = $dataArray['Mob_no'];
        $Res = $dataArray['Res'];
        $Branch = $dataArray['Branch'];
        $Account_num = $dataArray['Account_num'];
        $ifsc_code = $dataArray['ifsc_code'];
        $fromdate = $dataArray['Fromdate'];
        $Purpose = $dataArray['Purpose'];
        $relation = $dataArray['relation'];
        $dependent = $dataArray['dependent'];

        $reg_data = [
            'type' => $typedrp,
            'Date' => date('Y-m-d'),
            'Name' => $Name,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'Address' => $address,
            'Pincode' => $pincode,
            'slot' => $slot, // Slot is now a comma-separated string
            'Bank_name' => $Bank_name,
            'Desig_ofice' => $Desig_ofice,
            'PF_Ticket' => $PF_Ticket,
            'Office_Number' => $Office_Number,
            'Mob_no' => $Mob_no,
            'Res' => $Res,
            'Branch' => $Branch,
            'ifsc_code' => $ifsc_code,
            'Account_num' => $Account_num,
            'From_date' => $fromdate,
            'Purpose' => $Purpose,
            'relation' => $relation,
            'dependent' => $dependent,
            'place_work' => $place_work,
            'Grade_pay' => $Grade_pay,
            'hrmsid' => $hrmsid
        ];

        $insertedId = DB::table('Booking_Form')->insertGetId($reg_data);
        $ins_data = DB::table('Booking_Form')->where('BF_id', $insertedId)->get();
        $mobileno = $ins_data[0]->Mob_no;
        if(count($ins_data) > 0){
                
            $smsData = [
                "filetype" => 2,
                "msisdn" => [$mobileno],
                "language" => 0,
                "credittype" => 7,
                "senderid" => "SCRSMS",
                "templateid" => 0,
                "message" => "Login OTP for Railway App/Portal. Do not share pls.",
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
            $dcode = json_decode($resp);
            $valsts = $dcode->status;
            $valvalue = $dcode->value;
            $err = curl_error($curl);

            curl_close($curl);
            
            

            
            
        } else {
            $valsts = '';
            $valvalue = '';
        }

        $returnsuccessdata = $this->encryption(array("StatusResult" => "success", "Returndata" => $ins_data, "inserted_id" => $insertedId));
        return response()->json($returnsuccessdata);
    } else {
        $returntokenmismatch = $this->encryption(array("StatusResult" => "TokenMismatch"));
        return response()->json($returntokenmismatch);
    }
}



function get_slotdata(Request $req) {
    $decryptdt = $this->decryption($req);
      if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
    
    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
    $date_selected = $dataArray['Fromdate'];
    
    // Calculate the next date
    $next_date = date('Y-m-d', strtotime($date_selected . ' +1 day'));
    $previous_date = date('Y-m-d', strtotime($date_selected . ' -1 day'));
    // Query for slots on the selected date
    $get_slot_current = DB::table('Booking_Form')->where('From_date', $date_selected)->where('verification',"allotted")->pluck('slot');
    
    // Query for slots on the next date
    $get_slot_next = DB::table('Booking_Form')->where('From_date', $next_date)->where('verification',"allotted")->pluck('slot');
    
    $get_slot_previous = DB::table('Booking_Form')->where('From_date', $previous_date)->where('verification',"allotted")->pluck('slot');
    // Prepare return data based on the availability of slots
    if (!empty($get_slot_current) || !empty($get_slot_next) || !empty($get_slot_previous)) {
        $returndata = [
            "status_result" => "success",
            "Returndata" => !empty($get_slot_current) ? $get_slot_current : null,
            "returnnextdata" => !empty($get_slot_next) ? $get_slot_next : null,
                        "returnnextdata" => !empty($get_slot_next) ? $get_slot_next : null,
            "returnpreviousdata" => !empty($get_slot_previous) ? $get_slot_previous : null,

            // Optionally add this to identify that one set of data is empty
            "Returnfaildata" => count($get_slot_current)== 0 ? "empty" : null,
            "Returnfailnextdata" => count($get_slot_next)==0 ? "nextempty" : null,
            "Returnfailpreviousdata" => count($get_slot_previous)==0 ? "previousempty" : null,

        ];
    } else {
        // Both sets of data are empty
        $returndata = [
            "status_result" => "failure",
            "Returnfaildata" => "empty",
            "Returnfailnextdata" => "nextempty",
            "Returnfailpreviousdata" => "previousempty"
        ];
    }
    
    $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
}

///alloted code
// function get_slotdata(Request $req) {
//     $decryptdt = $this->decryption($req);
//     if (isset($decryptdt['error'])) {
//         return response()->json(['error' => $decryptdt['error']]);
//     }
    
//     $jsonString = $decryptdt ?? '';
//     $dataArray = json_decode($jsonString, true);
//     $date_selected = $dataArray['Fromdate'];
    
//     // Calculate the next and previous dates
//     $next_date = date('Y-m-d', strtotime($date_selected . ' +1 day'));
//     $previous_date = date('Y-m-d', strtotime($date_selected . ' -1 day'));
    
//     // Query for available slots on the selected date, excluding those with 'alloted' status
//     $get_slot_current = DB::table('Booking_Form')
//         ->where('From_date', $date_selected)
//         ->where('verification', '!=', 'alloted') // Exclude 'alloted' slots
//         ->pluck('slot');
    
//     // Query for available slots on the next date, excluding 'alloted' slots
//     $get_slot_next = DB::table('Booking_Form')
//         ->where('From_date', $next_date)
//         ->where('verification', '!=', 'alloted') // Exclude 'alloted' slots
//         ->pluck('slot');
    
//     // Query for available slots on the previous date, excluding 'alloted' slots
//     $get_slot_previous = DB::table('Booking_Form')
//         ->where('From_date', $previous_date)
//         ->where('verification', '!=', 'alloted') // Exclude 'alloted' slots
//         ->pluck('slot');
    
//     // Prepare return data based on the availability of slots
//     if (!empty($get_slot_current) || !empty($get_slot_next) || !empty($get_slot_previous)) {
//         $returndata = [
//             "status_result" => "success",
//             "Returndata" => !empty($get_slot_current) ? $get_slot_current : null,
//             "returnnextdata" => !empty($get_slot_next) ? $get_slot_next : null,
//             "returnpreviousdata" => !empty($get_slot_previous) ? $get_slot_previous : null,
//             "Returnfaildata" => count($get_slot_current) == 0 ? "empty" : null,
//             "Returnfailnextdata" => count($get_slot_next) == 0 ? "nextempty" : null,
//             "Returnfailpreviousdata" => count($get_slot_previous) == 0 ? "previousempty" : null,
//         ];
//     } else {
//         // No slots available on any date
//         $returndata = [
//             "status_result" => "failure",
//             "Returnfaildata" => "empty",
//             "Returnfailnextdata" => "nextempty",
//             "Returnfailpreviousdata" => "previousempty"
//         ];
//     }
    
//     // Encrypt the response before returning
//     $encryptedResponse = $this->encryption($returndata);
//     return ["return_response" => $encryptedResponse];
// }



}