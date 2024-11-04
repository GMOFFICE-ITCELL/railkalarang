<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class payamount_Controller extends Controller
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

function getPayAmount(Request $req){
    
     $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $payamount = $dataArray['amount'];
     $id = $dataArray['id'];
    $data =DB::table('Booking_Form')->where('BF_id',$id)->get();
    
    $id=$data[0]->BF_id;
    $name=$data[0]->Name;
    $mbno=$data[0]->Mob_no;
    $city=$data[0]->city;
    $parameters=$name ."^". $city."^" .$mbno ;
    
    
    $transaction=DB::Table('Transaction_table')->insert([
        'Ref_id'=>$id,
        'Transaction_date'=>date('y-m-d'),
        'Transaction_time'=>date('H:i'),
        'Transaction_timestamp'=>date('y-m-d H:i'),
        'Amount'=>$payamount,
        'Customer_details' =>$parameters,
        
        ]);
        $transactioninsertdata =DB::table('Transaction_table')->where('Ref_id',$id)->get();
        
        $tid=$transactioninsertdata[0]->Transaction_id;
        $transactionupdatedata =DB::Table('Transaction_table')->where('Transaction_id',$tid)->update([
        
        'Order_number' => "SCRRKGBKID" . $id . "TRID" . $tid,
        
        ]);
    
    
        if($transactionupdatedata){
          
                $returndata=array("status"=>"Success","id"=>$tid);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }   
}

function getexcessPayAmount(Request $req){
    
     $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);
     $payamount = $dataArray['amount'];
     $id = $dataArray['id'];
    //  return $payamount;
    $data =DB::table('Booking_Form')->where('BF_id',$id)->get();
    
    $id=$data[0]->BF_id;
    $name=$data[0]->Name;
    $mbno=$data[0]->Mob_no;
    $city=$data[0]->city;
    $parameters=$name ."^". $city."^" .$mbno ;
    
    
    $transaction=DB::Table('excess_Transaction_table')->insert([
        'Ref_id'=>$id,
        'Transaction_date'=>date('y-m-d'),
        'Transaction_time'=>date('H:i'),
        'Transaction_timestamp'=>date('y-m-d H:i'),
        'Amount'=>$payamount,
        'Customer_details' =>$parameters,
        
        ]);
        $transactioninsertdata =DB::table('excess_Transaction_table')->where('Ref_id',$id)->get();
        
        $tid=$transactioninsertdata[0]->Transaction_id;
        $transactionupdatedata =DB::Table('excess_Transaction_table')->where('Transaction_id',$tid)->update([
        
        'Order_number' => "SCRRKGBKID" . $id . "TRID" . $tid,
        
        ]);
    
    
        if($transactionupdatedata){
          
                $returndata=array("status"=>"Success","id"=>$tid);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }   
}
function transaction_data(Request $req){
    $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);

     $id = $dataArray['id'];
    
    
    $transacationData = $data =DB::table('Transaction_table')->where('Ref_id',$id)->get();
    
    if($transacationData){
          
                $returndata=array("status"=>"Success","data"=>$transacationData);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        } 
    
    
}

function dv_verify_data(Request $req){
    $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);

     $id = $dataArray['id'];
 
    $transacationData = $data =DB::table('Transaction_table')->where('Ref_id',$id)->get();
    
    if($transacationData){
          
                $returndata=array("status"=>"Success","dv_verifydata"=>$transacationData);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        } 
    
    
}




function dv_id_data(Request $req){

    $transacationData = $data =DB::table('Booking_Form')->where('verification',"allotted")->where('level',4)->get();
    
    if($transacationData){
          
                $returndata=array("status"=>"Success","dv_id_data"=>$transacationData);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        } 
    
    
}


function excess_transaction_data(Request $req){
    $decryptedResponse = $this->decryption($req);
       //  return ($decryptedResponse);
       // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }
    // Assuming decryptedResponse is an associative array, you can extract values like this:

    $jsonString = $decryptedResponse ?? '';
    $dataArray = json_decode($jsonString, true);

     $id = $dataArray['id'];
    
    
    $transacationData = $data =DB::table('excess_Transaction_table')->where('Ref_id',$id)->get();
    
    if($transacationData){
          
                $returndata=array("status"=>"Success","data"=>$transacationData);
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        }else{
            $returndata=array("status"=>"failure");
          $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
            
        } 
    
    
}




}