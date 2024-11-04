<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Create_Controller extends Controller
{
    
function decryption($req) {
    $encryptedData = $req->input('encryptedData');
    $key = '452c55d16a18f2ac049b2ec24637571e';
    $iv = 'stockms*kvb#7685';

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
    $key = '452c55d16a18f2ac049b2ec24637571e'; // Should ideally be stored securely and not hard-coded
    $iv = 'stockms*kvb#7685'; // Initialization Vector

    $json_data = json_encode($data);
    $encrypted = openssl_encrypt($json_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $encoded = base64_encode($encrypted);

    return $encoded;
}




function insertdata(Request $req) {
    // Decrypt the response
    $decryptedResponse = $this->decryption($req);

    // Check if decryption was successful
    if (isset($decryptedResponse['error'])) {
        // Handle the error appropriately
        return response()->json(['error' => $decryptedResponse['error']]);
    }

    // Assuming decryptedResponse is an associative array, you can extract values like this:
     $stock_name = $decryptedResponse['stock_name'] ?? null;
     $buying_price = $decryptedResponse['buying_price'] ?? null; 
 
     
            // Data to insert
            $data = [
                'stock_name' => $stock_name,
                'buying_price'=> $buying_price
                // ... more columns
            ];
            
            // Insert data into the table
            $insertResult=DB::table('stocks_data_table')->insert($data);
            
            if($insertResult){
                $returndata = array("status"=>"success");
                 $encryptedResponse = $this->encryption($returndata);
                return  array("return_response"=>$encryptedResponse);;
            }else
            {
                $returndata = array("status"=>"failed");
                 $encryptedResponse = $this->encryption($returndata);
                return array("return_response"=>$encryptedResponse);
               
            }
            
    // Finally, return a response or the processed data
    //  return array(['stock_name' => $stock_name, 'buying_price' => $buying_price]); // Or return processed data
}







}// end of class