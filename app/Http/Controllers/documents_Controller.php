<?php

namespace App\Http\Controllers;

use App\Actions\UploadFileAction;
use App\Models\BookingForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class documents_Controller extends Controller
{

    protected $uploadFileAction;

    public function __construct(UploadFileAction $uploadFileAction)
    {
        $this->uploadFileAction = $uploadFileAction;
    }


    private function encrypt_ang($data)
    {
        $str_enc = json_encode($data);
        $enc = base64_encode($str_enc);
        $key = $this->randomString(20);
        return $key . $enc;
    }

    private function randomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    private function decrypt_ang($data)
    {
        $data1 = substr($data, 20);
        $decr = base64_decode($data1);
        return json_decode($decr, true);
    }

    function decryptdataalongwithfile($req)
    {

        $req = (substr($req, 20));
        $decoded_data = json_decode(base64_decode($req));
        $req = $decoded_data;
        return $req;
    }

    function encryptadh($str)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $encryptToken = openssl_encrypt($str, $encrypt_method, $key, 0, $iv);
        return $encryptToken = base64_encode($encryptToken);
    }

    function decryptadh($str)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        return $decryptToken = openssl_decrypt(base64_decode($str), $encrypt_method, $key, 0, $iv);
    }


    public function get_booking_dt(Request $req)
    {
        $decryptdt = decryption($req);
        if (isset($decryptdt['error'])) {
            return response()->json(['error' => $decryptdt['error']]);
        }
        $jsonString = $decryptdt ?? '';
        $dataArray = json_decode($jsonString, true);
        $b_id = $dataArray['bid'];

        $get_data = DB::table('Booking_Form')->where('BF_id', $b_id)->get();

        if ($get_data->isEmpty()) {
            // return array("StatusResult" => "failure");
            $data = ['StatusResult' => "failure"];
            return response()->json(encryption($data));

        } else {
            // return array("StatusResult" => "success", "booking_table" => $get_data);
            $data = ["StatusResult" => "success", "booking_table" => $get_data];
            return response()->json(encryption($data));

        }
    }


//checking all the doccuments are upload or not

    public function checking_data(Request $req)
    {
        $decryptdt = decryption($req);
        if (isset($decryptdt['error'])) {
            return response()->json(['error' => $decryptdt['error']]);
        }
        $jsonString = $decryptdt ?? '';
        $dataArray = json_decode($jsonString, true);
        $b_id = $dataArray['id'];
        $get_data = DB::table('Booking_Form')->where('BF_id', $b_id)->get();

        //  $jsonData=$get_data[0]->type;
        //  $type = json_encode([$jsonData]);

        $type = $get_data[0]->type;
        $mobileno = $get_data[0]->Mob_no;
        $af = $get_data[0]->aadhar_front;
        $ab = $get_data[0]->aadhar_back;
        $ppof = $get_data[0]->ppo_front;
        $ppob = $get_data[0]->ppo_back;
        $paf = $get_data[0]->pan_front;
        $bpf = $get_data[0]->bank_passbook;
        $daf = $get_data[0]->dep_aadhar_front;
        $dab = $get_data[0]->dep_aadhar_back;
        $idf = $get_data[0]->id_front;
        $idb = $get_data[0]->id_back;
        $umidf = $get_data[0]->umid_front;
        $umidb = $get_data[0]->umid_back;
        $lastpay = $get_data[0]->last_pay_slip;
        //  $decodedType = json_decode($type,true);


        if ($type == "General Public") {
            $general = DB::Select("SELECT * FROM `Booking_Form` WHERE `BF_id`=$b_id  AND `aadhar_front` IS NOT NULL AND `aadhar_back` IS NOT NULL AND `bank_passbook` IS NOT NULL AND `pan_front` IS NOT NULL");
            if (count($general) > 0) {
                $status = DB::TABLE('Booking_Form')->where('BF_id', $b_id)->update([
                    'doc_status' => "success"
                ]);
                $data = ["StatusResult" => "success"];
                return response()->json(encryption($data));

            }

        } else if ($type == "Serving Employees") {
            $serving = DB::Select("SELECT * FROM `Booking_Form` WHERE `BF_id`=$b_id  AND `aadhar_front` IS NOT NULL AND `aadhar_back` IS NOT NULL AND `bank_passbook` IS NOT NULL  AND `umid_front` IS NOT NULL AND `umid_back` IS NOT NULL AND `dep_aadhar_front` IS NOT NULL AND `dep_aadhar_back` IS NOT NULL AND `last_pay_slip` IS NOT NULL");
            if (count($serving) > 0) {
                $status = DB::TABLE('Booking_Form')->where('BF_id', $b_id)->update([
                    'doc_status' => "success"

                ]);
                $data = ["StatusResult" => "success"];
                return response()->json(encryption($data));
            }

        } else if ($type == "Retired Employees") {
            $retired = DB::Select("SELECT * FROM `Booking_Form` WHERE `BF_id`=$b_id AND `aadhar_front` IS NOT NULL AND `aadhar_back` IS NOT NULL AND `bank_passbook` IS NOT NULL  AND `umid_front` IS NOT NULL AND `umid_back` IS NOT NULL AND `ppo_front` IS NOT NULL AND `ppo_back` IS NOT NULL AND `dep_aadhar_front` IS NOT NULL AND `dep_aadhar_back` IS NOT NULL AND `Service_cert` IS NOT NULL ");

            if (count($retired) > 0) {
                $status = DB::TABLE('Booking_Form')->where('BF_id', $b_id)->update([
                    'doc_status' => "success"

                ]);
                $data = ["StatusResult" => "success"];
                return response()->json(encryption($data));
            }


        } else if ($type == "Staff_of_PSUs") {

            $psu = DB::Select("SELECT * FROM `Booking_Form` WHERE `BF_id`=$b_id  AND `aadhar_front` IS NOT NULL AND `aadhar_back` IS NOT NULL AND `bank_passbook` IS NOT NULL  AND `id_front` IS NOT NULL AND `id_back` IS NOT NULL AND `dep_aadhar_front` IS NOT NULL AND `dep_aadhar_back` IS NOT NULL AND `last_pay_slip` IS NOT NULL");
            //  return $psu;

            if (count($psu) > 0) {

                $status = DB::TABLE('Booking_Form')->where('BF_id', $b_id)->update([
                    'doc_status' => "success"

                ]);
                $data = ["StatusResult" => "success"];
                return response()->json(encryption($data));


            }
        } else if ($type == "SCRE Sangh" || "SCRM Union" || "SCRO Association" || "SCRPO Association" || "SC/ST Association" || "OBC Association" || "SCRO Association" || "RPF Association" || "SCR Lalitha Kala Samiti" || "retired_officers_association" || "Retired_Employees_Association") {
            $SCRE = DB::Select("SELECT * FROM `Booking_Form` WHERE `BF_id`=$b_id AND `Letter_head` IS NOT NULL");

            if (count($SCRE) > 0) {
                $status = DB::TABLE('Booking_Form')->where('BF_id', $b_id)->update([
                    'doc_status' => "success"

                ]);
                $data = ["StatusResult" => "success"];
                return response()->json(encryption($data));
            }
        } else {
            $data = ["StatusResult" => "failure"];
            return response()->json(encryption($data));

        }

    }

    public function checking_status_data(Request $req)
    {
        $decryptdt = decryption($req);
        if (isset($decryptdt['error'])) {
            return response()->json(['error' => $decryptdt['error']]);
        }
        $jsonString = $decryptdt ?? '';
        $dataArray = json_decode($jsonString, true);
        $b_id = $dataArray['id'];
        $desclaimer_doc = $dataArray['desclaimer'];
        $insertdesclaimer = DB::table('Booking_Form')->where('BF_id', $b_id)->update([
            'desclaimer' => $desclaimer_doc
        ]);
        $get_data = DB::table('Booking_Form')->where('BF_id', $b_id)->get();

        if (count($get_data) > 0) {
            $status = $get_data[0]->doc_status;
            $mobileno = $get_data[0]->Mob_no;

            if ($status == "success") {


                $smsData = [
                    "filetype" => 2,
                    "msisdn" => [$mobileno],
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
                $dcode = json_decode($resp);
                $valsts = $dcode->status;
                $valvalue = $dcode->value;
                $err = curl_error($curl);

                curl_close($curl);


//chos
                $mobileno1 = '7569670885';
                $mobileno2 = '9177385289';
                $smsData = [
                    "filetype" => 2,
                    "msisdn" => [$mobileno1, $mobileno2],
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
            $returnsuccessdata = encryption(array("StatusResult" => "success", "id" => $b_id));
            return response()->json($returnsuccessdata);

        } else {
            $returntokenmismatch = encryption(array("StatusResult" => "TokenMismatch"));
            return response()->json($returntokenmismatch);
        }
    }


    public function document_upload_ser_re(Request $req, $encryptedData)
    {


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('serv')) {
            $loc = "uploads";
            $file = $req->file('serv');
            $serv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $serv);

            $reg_data = [
                'Service_cert' => $serv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

// ----------------------------------
    public function document_upload_lh_scre(Request $req, $encryptedData)
    {


        $validator = Validator::make($req->all(), [
            'lhscrev' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }


        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhscrev')) {

            $filePath = $this->uploadFileAction->execute($req->file('lhscrev'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }


    public function document_upload_lh_scrm(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'lhscrmv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);

        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhscrmv')) {
        $filePath = $this->uploadFileAction->execute($req->file('lhscrmv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }


    public function document_upload_lh_scrov(Request $req, $encryptedData)
    {

   $validator = Validator::make($req->all(), [
            'lhscrov' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhscrov')) {
            $filePath = $this->uploadFileAction->execute($req->file('lhscrov'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_lh_scrpov(Request $req, $encryptedData)
    {

 $validator = Validator::make($req->all(), [
            'lhscrpov' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhscrpov')) {
            $filePath = $this->uploadFileAction->execute($req->file('lhscrpov'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }



    public function document_upload_lh_sc_st(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'lhsc_stv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhsc_stv')) {
            $filePath = $this->uploadFileAction->execute($req->file('lhsc_stv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }



    public function document_upload_lh_obc(Request $req, $encryptedData)
    {

  $validator = Validator::make($req->all(), [
            'lhsc_obc' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload

        if ($req->hasFile('lhsc_obc')) {
           $filePath = $this->uploadFileAction->execute($req->file('lhsc_obc'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_lh_rpf(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'lhsc_rpf' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhsc_rpf')) {


 $filePath = $this->uploadFileAction->execute($req->file('lhsc_rpf'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }



    public function document_upload_lalitha(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'lhsc_lalitha' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhsc_lalitha')) {
            $filePath = $this->uploadFileAction->execute($req->file('lhsc_lalitha'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_lhrao(Request $req, $encryptedData)
    {

        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhsc_rao')) {
            $loc = "uploads";
            $file = $req->file('lhsc_rao');
            $lhsc_rao = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $lhsc_rao);

            $reg_data = [
                'Letter_head' => $lhsc_rao
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_lhrea(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'lhsc_rea' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('lhsc_rea')) {


 $filePath = $this->uploadFileAction->execute($req->file('lhsc_rea'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }


    public function document_upload_aaf(Request $req, $encryptedData)
    {


        $validator = Validator::make($req->all(), [
            'afv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('afv')) {

 $filePath = $this->uploadFileAction->execute($req->file('afv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }



    public function document_upload_ab(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'abv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('abv')) {

 $filePath = $this->uploadFileAction->execute($req->file('abv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }

    public function document_upload_pf(Request $req, $encryptedData)
    {

        $validator = Validator::make($req->all(), [
            'pfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('pfv')) {
            $filePath = $this->uploadFileAction->execute($req->file('pfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_bpfv(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'bpfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('bpfv')) {
            $filePath = $this->uploadFileAction->execute($req->file('bpfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_uf(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'ufv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ufv')) {


 $filePath = $this->uploadFileAction->execute($req->file('ufv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }

    public function document_upload_ub(Request $req, $encryptedData)
    {
        // Decrypt the data
        $validator = Validator::make($req->all(), [
            'ubv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ubv')) {


            $filePath = $this->uploadFileAction->execute($req->file('ubv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
            } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
            }
    }

    public function document_upload_idf(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'idfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('idfv')) {


            $filePath = $this->uploadFileAction->execute($req->file('idfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
            } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
            }
    }

    public function document_upload_idbv(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'idbv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('idbv')) {

        $filePath = $this->uploadFileAction->execute($req->file('idbv'), BookingForm::folder(), 'public');

        $booking->letter_head = $filePath;
        $booking->save();


        $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


        return response()->json($this->encrypt_ang($dataret));
        } else {
        $dataret = ['StatusResult' => 'Token Mismatch'];
        return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_af_se(Request $req, $encryptedData)
    {
        // Decrypt the data
        $validator = Validator::make($req->all(), [
            'afv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('afv')) {

                $filePath = $this->uploadFileAction->execute($req->file('afv'), BookingForm::folder(), 'public');

                $booking->letter_head = $filePath;
                $booking->save();


                $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


                return response()->json($this->encrypt_ang($dataret));
                } else {
                $dataret = ['StatusResult' => 'Token Mismatch'];
                return response()->json($this->encrypt_ang($dataret));
                }
    }

    public function document_upload_ab_se(Request $req, $encryptedData)
    {
      // Decrypt the data
      $validator = Validator::make($req->all(), [
        'abv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);
    }


    // Decrypt the data
    $data = $this->decrypt_ang($encryptedData);


    // $b_id = 16;
   $b_id = $data['bid'];

    $booking = BookingForm::query()
        ->where('BF_id', $b_id)->first();


//        dd($booking);

    if (empty($booking)) {
        return response()->json([
            'success' => false,
            'message' => 'invalid Booking Id',
            'errors' => $validator->errors()
        ], 422);
    }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('abv')) {
            $filePath = $this->uploadFileAction->execute($req->file('abv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
            } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
            }
    }

    public function document_upload_uf_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'ufv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ufv')) {
            $filePath = $this->uploadFileAction->execute($req->file('ufv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_ub_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'ubv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ubv')) {
            $filePath = $this->uploadFileAction->execute($req->file('ubv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_ppfv_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'ppfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }


        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ppfv')) {

 $filePath = $this->uploadFileAction->execute($req->file('ppfv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }

    public function document_upload_ppbv_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'ppbv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('ppbv')) {

 $filePath = $this->uploadFileAction->execute($req->file('ppbv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }

    public function document_upload_afv_re(Request $req, $encryptedData)
    {
        // Decrypt the data
        $validator = Validator::make($req->all(), [
            'afv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('afv')) {

 $filePath = $this->uploadFileAction->execute($req->file('afv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }

    public function document_upload_abv_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'abv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('abv')) {
            $filePath = $this->uploadFileAction->execute($req->file('abv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_idfv_psu(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'idfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('idfv')) {

 $filePath = $this->uploadFileAction->execute($req->file('idfv'), BookingForm::folder(), 'public');

 $booking->letter_head = $filePath;
 $booking->save();


 $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


 return response()->json($this->encrypt_ang($dataret));
} else {
 $dataret = ['StatusResult' => 'Token Mismatch'];
 return response()->json($this->encrypt_ang($dataret));
}
    }


    public function document_upload_idbv_psu(Request $req, $encryptedData)
    {
           $validator = Validator::make($req->all(), [
            'idbv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('idbv')) {
            $filePath = $this->uploadFileAction->execute($req->file('idbv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_afv_psu(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'afv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('afv')) {
            $filePath = $this->uploadFileAction->execute($req->file('afv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_abv_psu(Request $req, $encryptedData)
    {
        // Decrypt the data
        $validator = Validator::make($req->all(), [
            'abv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('abv')) {
            $filePath = $this->uploadFileAction->execute($req->file('abv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_bpfv_re(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'bpfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('bpfv')) {
            $filePath = $this->uploadFileAction->execute($req->file('bpfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }


    public function document_upload_bpfv_psu(Request $req, $encryptedData)
    {
        // Decrypt the data
       $validator = Validator::make($req->all(), [
            'bpfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('bpfv')) {
            $filePath = $this->uploadFileAction->execute($req->file('bpfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_bpfv_se(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'bpfv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }

        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('bpfv')) {
            $loc = "uploads";
            $filePath = $this->uploadFileAction->execute($req->file('bpfv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_dafv_psu(Request $req, $encryptedData)
    {
        $validator = Validator::make($req->all(), [
            'dafv' => 'required|file|mimes:jpg,jpeg,png', // Adjust file types and size as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }


        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);


        // $b_id = 16;
       $b_id = $data['bid'];

        $booking = BookingForm::query()
            ->where('BF_id', $b_id)->first();


//        dd($booking);

        if (empty($booking)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid Booking Id',
                'errors' => $validator->errors()
            ], 422);
        }
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dafv')) {
            $filePath = $this->uploadFileAction->execute($req->file('dafv'), BookingForm::folder(), 'public');

            $booking->letter_head = $filePath;
            $booking->save();


            $dataret = ['StatusResult' => $booking ? 'success' : 'failure', 'data' => $booking];


            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_dabv_psu(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dabv')) {
            $loc = "uploads";
            $file = $req->file('dabv');
            $dabv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $dabv);

            $reg_data = [
                'dep_aadhar_back' => $dabv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }
// ---------------------------------------
    public function document_upload_dabv_re(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dabv')) {
            $loc = "uploads";
            $file = $req->file('dabv');
            $dabv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $dabv);

            $reg_data = [
                'dep_aadhar_back' => $dabv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_dafv_re(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dafv')) {
            $loc = "uploads";
            $file = $req->file('dafv');
            $dafv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $dafv);

            $reg_data = [
                'dep_aadhar_front' => $dafv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_dafv_se(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dafv')) {
            $loc = "uploads";
            $file = $req->file('dafv');
            $dafv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $dafv);

            $reg_data = [
                'dep_aadhar_front' => $dafv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_dabv_se(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('dabv')) {
            $loc = "uploads";
            $file = $req->file('dabv');
            $dabv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $dabv);

            $reg_data = [
                'dep_aadhar_back' => $dabv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_psv_se(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('psv')) {
            $loc = "uploads";
            $file = $req->file('psv');
            $psv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $psv);

            $reg_data = [
                'last_pay_slip' => $psv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }

    public function document_upload_psv_psu(Request $req, $encryptedData)
    {
        // Decrypt the data
        $data = $this->decrypt_ang($encryptedData);
        $b_id = $data['bid'];
        //  return $b_id;
        // Handle file upload
        if ($req->hasFile('psv')) {
            $loc = "uploads";
            $file = $req->file('psv');
            $psv = date("His") . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->move($loc, $psv);

            $reg_data = [
                'last_pay_slip' => $psv
            ];

            $insert = DB::table('Booking_Form')->where('BF_id', $b_id)->update($reg_data);
            $dataret = ['StatusResult' => $insert ? 'success' : 'failure'];

            return response()->json($this->encrypt_ang($dataret));
        } else {
            $dataret = ['StatusResult' => 'Token Mismatch'];
            return response()->json($this->encrypt_ang($dataret));
        }
    }
}
