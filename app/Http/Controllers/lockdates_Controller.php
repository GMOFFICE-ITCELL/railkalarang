<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class lockdates_Controller extends Controller
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



function get_lock(Request $req){
    
  

        $locks_data=DB::table('lock_table')->get();
        if($locks_data){
              $returndata= array("StatusResult"=>"success","lock_table"=>$locks_data);
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    }
    
function lock_check_dt(Request $req){
    
      $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
     $selectedDate = $dataArray['selectedDate'];
        $locks_data=DB::table('lock_table')->where('date',$selectedDate)->get();
        if(count($locks_data)>0){
              $returndata= array("StatusResult"=>"success");
              $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);
          }
           else{
               $returndata= array("StatusResult"=>"failure");
                $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
                
           }
    
}    


  
function unlock_dt(Request $req){
    
      $decryptdt=$this->decryption($req);
       if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }
      $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
   

        $fdate = $dataArray['formatteddate1'];
        $tdate = $dataArray['formatteddate2'];
        
     $formatteddate1 = Carbon::parse($fdate);
    $formatteddate2 = Carbon::parse($tdate);
        
        $datesToDelete = [];

    // Gather all dates from startDate to endDate
    while ($formatteddate1->lte($formatteddate2)) {
        $datesToDelete[] = $formatteddate1->format('Y-m-d');
        
        // Move to the next day
        $formatteddate1->addDay();
    }

    // Perform the deletion for each date
    $deletedRows = DB::table('lock_table')
        ->whereIn('date', $datesToDelete)
        ->delete();

    // Return success or failure status
    if ($deletedRows) {
        $returnsuccessdata =  $this->encryption(array("status"=>"success"));   
          
              return  array("return_response"=>$returnsuccessdata);
    } else {
         $returnsuccessdata =  $this->encryption(array("status"=>"failure")); 
          return  array("return_response"=>$returnsuccessdata);
    }
    
}  

public function lock_ins_dt(Request $req)
{
      $decryptdt = $this->decryption($req);
    if (isset($decryptdt['error'])) {
        return response()->json(['error' => $decryptdt['error']]);
    }

    $jsonString = $decryptdt ?? '';
    $dataArray = json_decode($jsonString, true);
   
   
         $formatteddate1 = $dataArray['formatteddate1'];
        $formatteddate2 = $dataArray['formatteddate2'];
        
        
         try {
        // Convert dates to Carbon instances for easier manipulation
        $startDate = Carbon::parse($formatteddate1);
        $endDate = Carbon::parse($formatteddate2);
        

        // Initialize an array to store the rows to be inserted
        $rowsToInsert = [];

        // Loop through each date from startDate to endDate
        while ($startDate->lte($endDate)) {
            $dateStr = $startDate->format('Y-m-d');

            // Check if the date already exists in the table for the same zone, division, and department
            $existingDate = DB::table('lock_table')
                
                ->where('date', $dateStr)
                ->exists();

            // If the date does not exist, add it to the rows to be inserted
            if (!$existingDate) {
                $rowsToInsert[] = [
            
                'date' => $startDate->format('Y-m-d')
                
            ];
            }
            // Move to the next day
            $startDate->addDay();
        }
        
        $tbl = DB::table('lock_table')->insert($rowsToInsert);

            
            $returnsuccessdata =  $this->encryption(array("status"=>"success"));   
          
              return  array("return_response"=>$returnsuccessdata);
           
    } catch (\Exception $e) {
  
         $returnsuccessdata =  $this->encryption(array("status" => "error", "message" => $e->getMessage()));   
 
             return  array("return_response"=>$returnsuccessdata);
    }

}
}