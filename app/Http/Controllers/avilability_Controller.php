<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class avilability_Controller extends Controller
{
    
    function encryption($data) {
    $key = '452c55d16a18f2ac049b2ec24637571a';
    $iv = 'cetksum*rkj#4202';
     $json_data = json_encode($data);
    $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $encoded = base64_encode($encrypted);

    return $encoded;
}

function from_dates(Request $req){
    
                $book_data = DB::table('Booking_Form')->where('verification',"allotted")->get();

        // $book_data = DB::table('Booking_Form')->get('From_date');
        // $to_data = DB::table('Booking_Form')->get('to_date');
        // $slot_data = DB::table('Booking_Form')->get('slot');
        
          if($book_data){
            //   $returndata= array("StatusResult"=>"success","book_table"=>$book_data,"todata"=>$to_data,"slotdata"=>$slot_data);
                  $returndata= array("StatusResult"=>"success","book_table"=>$book_data);
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