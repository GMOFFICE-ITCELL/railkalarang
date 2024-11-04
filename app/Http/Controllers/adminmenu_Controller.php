<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class adminmenu_Controller extends Controller{


//for encryption
function encryption($data) {
    $key = '452c55d16a18f2ac049b2ec24637571a';
    $iv = 'cetksum*rkj#4202';
    $json_data = json_encode($data);
    $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $encoded = base64_encode($encrypted);

    return $encoded;
}

//get data function
function verify_get(Request $req){


        $book_data = DB::table('Booking_Form')->where('verification','verified_by_cos')->where('level','1')->get();

          if($book_data){
              $returndata= array("StatusResult"=>"success","book_table"=>$book_data);
              $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = encryption($returndata);
                return array("return_response"=>$encryptedResponse);


           }
    }

function reject_get(Request $req){


        $book_data = DB::table('Booking_Form')->where('verification',['rejected_by_cos','admin_rejected'])->where('level','9')->get();

          if($book_data){
              $returndata= array("StatusResult"=>"success","book_table"=>$book_data);
              $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = encryption($returndata);
                return array("return_response"=>$encryptedResponse);


           }
    }

function withdraw_get(Request $req){


        $book_data = DB::table('Booking_Form')->where('verification','withdraw')->where('level','7')->get();

          if($book_data){
              $returndata= array("StatusResult"=>"success","book_table"=>$book_data);
              $encryptedResponse = encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = encryption($returndata);
                return array("return_response"=>$encryptedResponse);


           }
    }
}
