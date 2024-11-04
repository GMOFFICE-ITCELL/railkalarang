<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function verifyOtp(Request $req)
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


            $user = Auth::user();
            $token = $user->createToken('API_Token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);


//            $returndata = array("status" => "verified");
//            $encryptedResponse = $this->encryption($returndata);
//            return array("return_response" => $encryptedResponse);

        } else {
//   return array("status"=>"failed");

            $returndata = array("status" => "failed");
            $encryptedResponse = $this->encryption($returndata);
            return array("return_response" => $encryptedResponse);


        }
    }



    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }




        $check_user = User::where('username', $request->username)->first();


        if (empty($check_user)) {
            return response()->json([
                'message' => 'Invalid username'
            ], 401);
        }


        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $loginRole = $check_user->role;
        $mobile = $check_user->mobile_number;

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
                        "admindata" => $check_user,
                        "string_key" => "RailkalarangAmenities"
                    ];

                    $encryptedResponse = $this->encryption($returndata);
                    return ["return_response" => $encryptedResponse];
                } else {
                    $returndata = ["status" => "ftso"];
                    $encryptedResponse = $this->encryption($returndata);
                    return ["return_response" => $encryptedResponse];
                }
            } else {
                $returndata = ["status" => "invm"];
                $encryptedResponse = $this->encryption($returndata);
                return ["return_response" => $encryptedResponse];
            }
        } else {

            $user = Auth::user();
            $token = $user->createToken('API_Token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);

        }


    }


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

}
