<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class registration_Controller extends Controller
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
    
function decryptdataalongwithfile($req){
       
            $req = (substr($req,20));
            $decoded_data = json_decode(base64_decode($req));
            $req = $decoded_data; 
            return $req;
        }
    
    
    function type_drp(Request $req){
       
        $decryptdt=$this->decryption($req);
       
         if (isset($decryptdt['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptdt['error']]);
         }
         
    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
    $token = $dataArray['token'];
    if($token=='rkj*2024#456'){
        $type_data = DB::table('dr__type_table')->select('type')->get();
        
            if($type_data){
            $returnsuccessdata =  $this->encryption(array("StatusResult"=>"success","type_drp"=>$type_data));   
          
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
   
   
   //aadhar encryption and decryption
   
     function encryptadh($str){
        $encrypt_method = "AES-256-CBC";
        $secret_key ='7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv ='7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $encryptToken = openssl_encrypt($str, $encrypt_method, $key, 0, $iv);
        return$encryptToken = base64_encode($encryptToken);
    }
    
    function decryptadh($str){
         $encrypt_method = "AES-256-CBC";
        $secret_key ='7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv ='7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
       return $decryptToken = openssl_decrypt(base64_decode($str), $encrypt_method, $key, 0, $iv);
    }
   
   
    
    function reg_ins_dt(Request $req){
        // return $req;
        
        $dataArray = $this->decryptdataalongwithfile($data);
        
         
    //       $decryptdt=$this->decryption($req);
       
    //      if (isset($decryptdt['error'])) {
    //     // Handle the error appropriately
    //     return response()->json(['error' => $decryptdt['error']]);
    //      }
         
    // $jsonString = $decryptdt ?? '';
    // $dataArray = json_decode($jsonString, true);
    
    
    $token = $dataArray->token;
    
    
    if($token=='rkj*2024#456'){
      
        
        $typedrp = $dataArray->typedrp;
        $Name = $dataArray->Name;
        $Bank_name = $dataArray->Bank_name;
        $Desig_ofice = $dataArray->Desig_ofice;
        $PF_Ticket = $dataArray->PF_Ticket;
        $Office_Number = $dataArray->Office_Number;
        $Mob_no = $dataArray->Mob_no;
        $Res = $dataArray->Res;
        $Branch = $dataArray->Branch;
        $Account_num = $dataArray->Account_num;
          $Date_Required = $dataArray->Date_Required;
        $Purpose= $dataArray->Purpose;
        $relation = $dataArray->relation;
        $dependent= $dataArray->dependent;
        

        $reg_data = [
            'type' => $typedrp,
            'f_name' => $First_name,
            'l_name' => $Last_name,
            '$Designation'=>$Designation,
            'email' => $Email_address,
            'password' => $Password,
            'phone' => $Contact_Number,
            'alt_phone' => $Alternate_Contact_Number,
            'aadhar_no' => $Aadhaar_Number,
            'address' => $Address,
          
        ];

        // return $reg_data;
        
    $ins_data=DB::table('user_reg')->insert($reg_data);
    // return $ins_data;
    if($ins_data){
        
 $returnsuccessdata =  $this->encryption(array("StatusResult"=>"success","ReturnData"=>$ins_data));   
          
            return json_encode($returnsuccessdata);
    }
    else{
        $returnfailuredata=$this->encryption(array("StatusResult"=>"failure"));
        return json_encode($returnfailuredata);
    }
    }
     else{
        $returntokenmismatch= $this->encryption(array("StatusResult"=>"TokenMismatch"));
        return json_encode($returntokenmismatch);
    }
    
        
    
    }//end of reg_int_dt

//     function reg_ins_dt(Request $req, $data) {
//     $dataArray = $this->decryptdataalongwithfile($data);

//     $token = $dataArray->token;

//     if ($token == 'rkj*2024#456') {
//         $loc = "uploads";
//         $afv = '';
//         $abv = '';
//         $ifv = '';
//         $ibv = '';
//         $pfv = '';
//         $pbv = '';
//         $ufv = '';
//         $ubv = '';

//         if ($dataArray->typedrp == 'outsider') {
//             if ($req->file('afv')) {
//                 $afv = date("His") . rand(11111, 99999) . '.' . $req->file('afv')->getClientOriginalExtension();
//                 $req->file('afv')->move($loc, $afv);
//             }
//             if ($req->file('abv')) {
//                 $abv = date("His") . rand(11111, 99999) . '.' . $req->file('abv')->getClientOriginalExtension();
//                 $req->file('abv')->move($loc, $abv);
//             }
//         } else if ($dataArray->typedrp == 'Retired Employees') {
//             if ($req->file('pfv')) {
//                 $pfv = date("His") . rand(11111, 99999) . '.' . $req->file('pfv')->getClientOriginalExtension();
//                 $req->file('pfv')->move($loc, $pfv);
//             }
//             if ($req->file('pbv')) {
//                 $pbv = date("His") . rand(11111, 99999) . '.' . $req->file('pbv')->getClientOriginalExtension();
//                 $req->file('pbv')->move($loc, $pbv);
//             }
//             if ($req->file('afv')) {
//                 $afv = date("His") . rand(11111, 99999) . '.' . $req->file('afv')->getClientOriginalExtension();
//                 $req->file('afv')->move($loc, $afv);
//             }
//             if ($req->file('abv')) {
//                 $abv = date("His") . rand(11111, 99999) . '.' . $req->file('abv')->getClientOriginalExtension();
//                 $req->file('abv')->move($loc, $abv);
//             }
//             if ($req->file('ufv')) {
//                 $ufv = date("His") . rand(11111, 99999) . '.' . $req->file('ufv')->getClientOriginalExtension();
//                 $req->file('ufv')->move($loc, $ufv);
//             }
//             if ($req->file('ubv')) {
//                 $ubv = date("His") . rand(11111, 99999) . '.' . $req->file('ubv')->getClientOriginalExtension();
//                 $req->file('ubv')->move($loc, $ubv);
//             }
//         } else {
//             if ($req->file('afv')) {
//                 $afv = date("His") . rand(11111, 99999) . '.' . $req->file('afv')->getClientOriginalExtension();
//                 $req->file('afv')->move($loc, $afv);
//             }
//             if ($req->file('abv')) {
//                 $abv = date("His") . rand(11111, 99999) . '.' . $req->file('abv')->getClientOriginalExtension();
//                 $req->file('abv')->move($loc, $abv);
//             }
//             if ($req->file('ufv')) {
//                 $ufv = date("His") . rand(11111, 99999) . '.' . $req->file('ufv')->getClientOriginalExtension();
//                 $req->file('ufv')->move($loc, $ufv);
//             }
//             if ($req->file('ubv')) {
//                 $ubv = date("His") . rand(11111, 99999) . '.' . $req->file('ubv')->getClientOriginalExtension();
//                 $req->file('ubv')->move($loc, $ubv);
//             }
//             if ($req->file('fiv')) {
//                 $ifv = date("His") . rand(11111, 99999) . '.' . $req->file('fiv')->getClientOriginalExtension();
//                 $req->file('fiv')->move($loc, $ifv);
//             }
//             if ($req->file('biv')) {
//                 $ibv = date("His") . rand(11111, 99999) . '.' . $req->file('biv')->getClientOriginalExtension();
//                 $req->file('biv')->move($loc, $ibv);
//             }
//         }

//         $typedrp = $dataArray->typedrp;
//         $First_name = $dataArray->First_name;
//         $Last_name = $dataArray->Last_name;
//         $Designation = $dataArray->Designation;
//         $Email_address = $dataArray->Email_address;
//         $Password = $dataArray->Password;
//         $Contact_Number = $dataArray->Contact_Number;
//         $Alternate_Contact_Number = $dataArray->Alternate_Contact_Number;
//         $Aadhaar_Number = $dataArray->Aadhaar_Number;
//         $UMID_Number = $dataArray->UMID_Number;
//         $Address = $dataArray->Address;

//         $reg_data = [
//             'type' => $typedrp,
//             'f_name' => $First_name,
//             'l_name' => $Last_name,
//             'email' => $Email_address,
//             'password' => $Password,
//             'phone' => $Contact_Number,
//             'alt_phone' => $Alternate_Contact_Number,
//             'aadhar_no' => $Aadhaar_Number,
//             'umid' => $UMID_Number,
//             'address' => $Address,
//             'a_fv' => $afv,
//             'a_bv' => $abv,
//             'i_fv' => $ifv,
//             'i_bv' => $ibv,
//             'p_fv' => $pfv,
//             'p_bv' => $pbv,
//             'u_fv' => $ufv,
//             'u_bv' => $ubv,
//             // 'designation' => $Designation
//         ];

//         $insert = DB::table('user_reg')->insert($reg_data);
//         $data = ['StatusResult' => $insert ? 'success' : 'failure'];

//         return $this->encryptdata($data);
//     } else {
//         $data = ['StatusResult' => 'Token Mismatch'];
//         return $this->encryptdata($data);
//     }
// }

    
}