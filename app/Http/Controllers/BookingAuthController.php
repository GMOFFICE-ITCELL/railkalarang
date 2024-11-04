<?php

namespace App\Http\Controllers;

use App\Models\BookingForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingAuthController extends Controller
{


    public function verifyamenityotp(Request $request)
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
        $id = $dataArray['BF_id'];
        $token = $dataArray['token'];
        $verigyOtp = $dataArray['verotp'];


//  $mobile= (string) $req->Mob_no;
//  $id=$req->BF_id;


        $tbl = DB::Table('otp_table')->where('token', $token)->where('otp', $verigyOtp)->get();

        if (count($tbl) > 0) {

            //   $data =DB::table('Booking_Form')->where('Mob_no',$mobile)->get();

            //   return array("status"=>"verified","getpayment"=>$mobile,"id"=>$id);


            $returndata = array("status" => "verified", "getpayment" => $mobile, "id" => $id);
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);

        } else {
//   return array("status"=>"failed");

            $returndata = array("status" => "failed");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);


        }

    }

    public function login(Request $req)
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
        $mobile = $dataArray['mobile'];


//   $bid = $req->bid;
//   return $bid;
        // $mobile = (string) $req->Mob_no;

        $tbl = BookingForm::where('Mob_no', $mobile)->get();

        $otpdate = date('Y-m-d');

        if (count($tbl) > 0) {

            $rand = rand(111111, 999999);
            // $rand = 1234;
            $key = bin2hex(random_bytes(32));
            $app = "RBMS";


            $tbl = DB::table('otp_table')->insert(['otp' => $rand, 'token' => $key, 'Mob_no' => $mobile, 'otpdate' => $otpdate]);

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
                $dcode = json_decode($resp);
                $valsts = $dcode->status;
                $valvalue = $dcode->value;
                $err = curl_error($curl);

                curl_close($curl);


                if ($valsts == "success" && $valvalue == "accepted") {
                    // return array("status" => "success","token"=>$key,"mobile"=>$mobile,"id"=>$bid);

                    $returndata = array("status" => "success", "token" => $key, "mobile" => $mobile);

                    $encryptedResponse = encryption($returndata);
                    return array("return_response" => $encryptedResponse);


                } else {
                    // return array("status" => "failure");//failed to send otp

                    $returndata = array("status" => "failure");
                    $encryptedResponse = encryption($returndata);
                    return array("return_response" => $encryptedResponse);

                }

            } else {
                // return array("status" => "ftso");

                $returndata = array("status" => "ftso");
                $encryptedResponse = encryption($returndata);
                return array("return_response" => $encryptedResponse);
            }
        }// end of otp store
        else {
            //  return array("status" => "invm");

            $returndata = array("status" => "invm");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);
        }
        //end of mobile validation

    }

    function verifyotp(Request $req)
    {


        $decryptedResponse = decryption($req);

        // Check if decryption was successful
        if (isset($decryptedResponse['error'])) {
            // Handle the error appropriately
            return response()->json(['error' => $decryptedResponse['error']]);
        }

        // Assuming decryptedResponse is an associative array, you can extract values like this:

        $jsonString = $decryptedResponse ?? '';
        $dataArray = json_decode($jsonString, true);
        $mobile = $dataArray['mobile'];
        $token = $dataArray['token'];
        $verigyOtp = $dataArray['verotp'];


        $tbl = DB::Table('otp_table')->where('token', $token)->where('otp', $verigyOtp);

        if ($tbl) {


            $booking = BookingForm::where('Mob_no', $mobile)->first();

            $token = $booking->createToken('API_Token')->accessToken;

            return response()->json([
                "status" => "Success",
                'token' => $token,
                'booking' => $booking
            ]);


//            $returndata = array("status" => "Success", "mobile" => $mobile);
//            $encryptedResponse = encryption($returndata);
//            return array("return_response" => $encryptedResponse);

        } else {
//   return array("status"=>"failed");

            $returndata = array("status" => "failed");
            $encryptedResponse = encryption($returndata);
            return array("return_response" => $encryptedResponse);


        }

    }




}
