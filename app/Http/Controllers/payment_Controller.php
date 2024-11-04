<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class payment_Controller extends Controller
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

function getverifydata(Request $req){
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
     $verify_mbno = $dataArray['mobile'];
     $id =$dataArray['uid'];
    //  return $verify_mbno;
      $data =DB::table('Booking_Form')->where('Mob_no',$verify_mbno)->where('BF_id',$id)->get();
    //   return $data;
      if($data){

          $returndata=array("status"=>"Success","paymentdt"=>$data);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
      }
      else{
          $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
      }
    //   return array("status"=>"verified","payment"=>$data);
}


// bill generation

function bill_generation(Request $req){
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
     $slot = $dataArray['slot'];
      $type = $dataArray['type'];
      $uid = $dataArray['uid'];
      if (stripos($slot, "for one day") !== false) {
        $data = DB::table('dr__type_table')->select('deposit', 'H24')->where('type', $type)->first();

        $deposit = (float) $data->deposit;
            $H24 = $data->H24;
            $sum = $deposit + $H24;


        $bookingdata = DB::table('Booking_Form')->where('BF_id',$uid)->update([
            'deposit' => $deposit,
            'total_amount' =>$sum

            ]);

            $returndata=array("sum"=>$sum,"deposit"=>$deposit,"duration"=>$H24);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);

        // return response()->json($deposit);
    }
    // Check for "for half day" condition
    elseif (stripos($slot, "for half day") !== false) {
        $data = DB::table('dr__type_table')->select('deposit', 'H12')->where('type', $type)->first();
       $deposit = (float) $data->deposit;
            $H12 = $data->H12;
            $sum = $deposit + $H12;
            $bookingdata = DB::table('Booking_Form')->where('BF_id',$uid)->update([
            'deposit' => $deposit,
            'total_amount' =>$sum

            ]);

          $returndata=array("sum"=>$sum,"deposit"=>$deposit,"duration"=>$H12);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
    }

     $returndata=array('error' => 'Invalid slot value');
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
}

}
