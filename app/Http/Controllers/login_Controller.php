<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class login_Controller extends Controller
{

    function decryption($req)
    {
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


    function encryption($data)
    {
        $key = '452c55d16a18f2ac049b2ec24637571a';
        $iv = 'cetksum*rkj#4202';
        $json_data = json_encode($data);
        $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        $encoded = base64_encode($encrypted);

        return $encoded;
    }


    function uploadAadhar(Request $req)
    {

        return $req->all();

    }




// function rk_login(Request $req) {
// // return $req;
//  $decryptedResponse = decryption($req);
// //  return ($decryptedResponse);

//       // Check if decryption was successful
//     if (isset($decryptedResponse['error'])) {
//         // Handle the error appropriately
//         return response()->json(['error' => $decryptedResponse['error']]);
//     }

//     // Assuming decryptedResponse is an associative array, you can extract values like this:

//     $jsonString = $decryptedResponse ?? '';
//     $dataArray = json_decode($jsonString, true);
//      $usrName = $dataArray['username'];
//      $password = $dataArray['password'];


//         // Verifying the input data with the table login
//         $login_verify_dt = DB::table('login_table')->where('L_Username', $usrName)->where('L_password', $password)->get();

//         $loginRole = $login_verify_dt[0]->role;
//         // return $loginRole;
//         $mobile = $login_verify_dt[0]->mobile_number;
//
//           if(count($login_verify_dt)>0){
//               $returndata = array("status_result" => "success","ReturnData"=>$login_verify_dt,"string_key"=>"RailkalarangAdmin");

//                  $encryptedResponse = encryption($returndata);
//                 return  array("return_response"=>$encryptedResponse);
//             }else{
//                 $returndata = array("status_result" => "notfound");
//                  $encryptedResponse = encryption($returndata);
//                 return array("return_response"=>$encryptedResponse);

//             }
//

// }

    function rk_login(Request $req)
    {
        // Decrypt the request data
        $decryptedResponse = decryption($req);

        // Check if decryption was successful
        if (isset($decryptedResponse['error'])) {
            return response()->json(['error' => $decryptedResponse['error']]);
        }

        // Decode the decrypted JSON string into an associative array
        $dataArray = json_decode($decryptedResponse ?? '', true);

        if (!$dataArray || !isset($dataArray['username'], $dataArray['password'])) {
            return response()->json(['error' => 'Invalid data']);
        }

        $usrName = $dataArray['username'];
        $password = $dataArray['password'];

        // Verify the input data with the login table
        $login_verify_dt = DB::table('login_table')
            ->where('L_Username', $usrName)
            ->where('L_password', $password)
            ->first();

        if ($login_verify_dt) {
            $loginRole = $login_verify_dt->role;
            $mobile = $login_verify_dt->mobile_number;

            if (in_array($loginRole, [3, 4])) {
                // Generate OTP and other relevant details
                $rand = rand(111111, 999999);
                $key = bin2hex(random_bytes(32));
                $app = "RBMS";
                $otpdate = date('y-m-d');

                $tbl = DB::table('otp_table')->insert([
                    'otp' => $rand,
                    'token' => $key,
                    'Mob_no' => $mobile,
                    'otpdate' => $otpdate
                ]);

                if ($tbl) {
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
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "125.16.147.178/VoicenSMS/webresources/CreateSMSCampaignPost",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 20,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($smsData),
                        CURLOPT_HTTPHEADER => [
                            "content-type: application/json"
                        ],
                    ]);

                    $resp = curl_exec($curl);
                    $dcode = json_decode($resp);
                    $valsts = $dcode->status ?? '';
                    $valvalue = $dcode->value ?? '';
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($valsts == "success" && $valvalue == "accepted") {
                        $returndata = [
                            "status_result" => "success",
                            "token" => $key,
                            "mobile" => $mobile,
                            "role" => $loginRole,
                            "admindata" => $login_verify_dt,
                            "string_key" => "RailkalarangAmenities"
                        ];

                        $encryptedResponse = encryption($returndata);
                        return ["return_response" => $encryptedResponse];
                    } else {
                        $returndata = ["status" => "ftso"];
                        $encryptedResponse = encryption($returndata);
                        return ["return_response" => $encryptedResponse];
                    }
                } else {
                    $returndata = ["status" => "invm"];
                    $encryptedResponse = encryption($returndata);
                    return ["return_response" => $encryptedResponse];
                }
            } else {
                $returndata = [
                    "status_result" => "success",
                    "ReturnData" => $login_verify_dt,
                    "string_key" => "RailkalarangAdmin"
                ];

                $encryptedResponse = encryption($returndata);
                return ["return_response" => $encryptedResponse];
            }


        } else {
            $returndata = ["status_result" => "notfound"];
            $encryptedResponse = encryption($returndata);
            return ["return_response" => $encryptedResponse];
        }
    }


    function verifyamenityotp(Request $req)
    {

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


        $mobile = $dataArray['Mob_no'];
        $token = $dataArray['token'];
        $verigyOtp = $dataArray['verotp'];

        $tbl = DB::Table('otp_table')->where('token', $token)->where('otp', $verigyOtp)->get();
        // return $tbl;
        if (count($tbl) > 0) {

            //   $data =DB::table('Booking_Form')->where('Mob_no',$mobile)->get();

            //   return array("status"=>"verified","getpayment"=>$mobile,"id"=>$id);


            $returndata = array("status" => "verified");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);

        } else {
//   return array("status"=>"failed");

            $returndata = array("status" => "failed");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);


        }


    }


}
