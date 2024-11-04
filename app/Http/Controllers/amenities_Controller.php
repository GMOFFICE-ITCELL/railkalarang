<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class amenities_Controller extends Controller
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
   
public function checkAndInsert(Request $req)
{
    $records = DB::table('Booking_Form')->where('verification','allotted')->get();

    // Fetch all existing ref_ids from amenity_charges
    $existingAmenityIds = DB::table('amenity_charges')->pluck('ref_id')->toArray();

    // Check if any records were found
    if ($records->isEmpty()) {
        return response()->json(['status' => 'error', 'message' => 'No records found']);
    }

    $insertedRecords = 0;
    $insertedData = []; // Array to store inserted records data

    // Iterate through each record
    foreach ($records as $record) {
        // Check if the record's BF_id already exists in amenity_charges
        if (!in_array($record->BF_id, $existingAmenityIds)) {
            // Parse the From_date and check if it is in the past
            $fromDate = Carbon::parse($record->From_date);

            if ($fromDate->isPast()) {
                // Insert the row into amenity_charges
                DB::table('amenity_charges')->insert([
                    'ref_id' => $record->BF_id,
                ]);
                $insertedRecords++;
            }
        }

    }
    // Determine the response based on the number of records processed
    if ($insertedRecords > 0) {
        
        $returndata = [
            "status_result" => "success",
           
        ];
    } else {
        $returndata = [
            "status_result" => "failure",
           
        ];
       
        
    }

    
}

//Electrical data


public function Getelectrical_master(Request $req) {
    
    // Fetch the latest entry from the Waterworks_masterdata table based on the ID
    $getelec_data = DB::table('electrical_masterdata')
                        ->orderBy('ele_mas_id', 'desc')  // Sort by 'id' in descending order to get the latest
                        ->first();  // Retrieve only the latest entry
    
    // Check if data is found
    if ($getelec_data) {
        $returndata = array("StatusResult" => "success", "electrical_master_data" => $getelec_data);
    } else {
        $returndata = array("StatusResult" => "failure");
    }

    // Encrypt the response
    $encryptedResponse = $this->encryption($returndata);
    
    // Return the encrypted response
    return array("return_response" => $encryptedResponse);
}



public function get_Electrical_Data(Request $req)
{
    // Fetch all records from amenity_charges
    $amenityRecords = DB::table('amenity_charges')->get();

    // Array to hold records from Booking_Form if any field in amenity_charges is null
    $relatedBookingData = [];

    // Iterate through each record in amenity_charges
    foreach ($amenityRecords as $amenity) {
        // Check if any of the specified fields are null
        if (is_null($amenity->electrical_reading_from1) || 
            is_null($amenity->electrical_reading_to1) || 
            is_null($amenity->electrical_reading_from2) || 
            is_null($amenity->electrical_reading_to2) || 
            is_null($amenity->electrical_reading_from3) || 
            is_null($amenity->electrical_reading_to3) || 
            is_null($amenity->electrical_reading_from4) || 
            is_null($amenity->electrical_reading_to4) || 
            is_null($amenity->electrical_reading_from5) || 
            is_null($amenity->electrical_reading_to5) || 
            is_null($amenity->electrical_reading_from6) || 
            is_null($amenity->electrical_reading_to6) || 
            is_null($amenity->numberOfUnits1) || 
            is_null($amenity->numberOfUnits2) || 
            is_null($amenity->numberOfUnits3) || 
            is_null($amenity->numberOfUnits4) || 
            is_null($amenity->numberOfUnits5) || 
            is_null($amenity->numberOfUnits6) || 
            is_null($amenity->ratePerUnit) || 
            is_null($amenity->electrical_charging_amount)) {

            // Fetch the related Booking_Form data based on ref_id
            $relatedRecord = DB::table('Booking_Form')
                ->where('BF_id', $amenity->ref_id)
                ->where("verification","allotted")
                ->first();

            if ($relatedRecord) {
                // Add the related Booking_Form data to the result array
                $relatedBookingData[] = $relatedRecord;
            }
        }
    }

    // Determine the response based on whether related data was found
    if (count($relatedBookingData) > 0) {
        $returndata = [
            "status_result" => "success",
            "electrical_booking_data" => $relatedBookingData, // Send the related records data to the frontend
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    } else {
        $returndata = [
            "status_result" => "failure",
            "electrical_booking_data" => [], // No related records found
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    }

  
    
}

//water data

//waterworks masterdata

public function GetWater_master(Request $req) {
    
    // Fetch the latest entry from the Waterworks_masterdata table based on the ID
    $getwater_data = DB::table('Waterworks_masterdata')
                        ->orderBy('water_mas_id', 'desc')  // Sort by 'id' in descending order to get the latest
                        ->first();  // Retrieve only the latest entry
    
    // Check if data is found
    if ($getwater_data) {
        $returndata = array("StatusResult" => "success", "water_master_data" => $getwater_data);
    } else {
        $returndata = array("StatusResult" => "failure");
    }

    // Encrypt the response
    $encryptedResponse = $this->encryption($returndata);
    
    // Return the encrypted response
    return array("return_response" => $encryptedResponse);
}



public function get_Water_Data(Request $req)
{
    // Fetch all records from amenity_charges
    $amenityRecords = DB::table('amenity_charges')->get();

    // Array to hold records from Booking_Form if any field in amenity_charges is null
    $relatedBookingData = [];

    // Iterate through each record in amenity_charges
    foreach ($amenityRecords as $amenity) {
        // Check if any of the specified fields are null
        if (is_null($amenity->no_of_litres_water) || 
            is_null($amenity->water_Amount) ) {

            // Fetch the related Booking_Form data based on ref_id
            $relatedRecord = DB::table('Booking_Form')
                ->where('BF_id', $amenity->ref_id)
                ->where("verification","allotted")
                ->first();

            if ($relatedRecord) {
                // Add the related Booking_Form data to the result array
                $relatedBookingData[] = $relatedRecord;
            }
        }
    }

    // Determine the response based on whether related data was found
    if (count($relatedBookingData) > 0) {
        $returndata = [
            "status_result" => "success",
            "water_booking_data" => $relatedBookingData, // Send the related records data to the frontend
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    } else {
        $returndata = [
            "status_result" => "failure",
            "water_booking_data" => [], // No related records found
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    }

}

//engineering data

public function get_engineering_Data(Request $req)
{
    // Fetch all records from amenity_charges
    $amenityRecords = DB::table('amenity_charges')->get();

    // Array to hold records from Booking_Form if any field in amenity_charges is null
    $relatedBookingData = [];

    // Iterate through each record in amenity_charges
    foreach ($amenityRecords as $amenity) {
        // Check if any of the specified fields are null
        if (is_null($amenity->eng_remarks) || 
            is_null($amenity->eng_Amount) ) {

            // Fetch the related Booking_Form data based on ref_id
            $relatedRecord = DB::table('Booking_Form')
                ->where('BF_id', $amenity->ref_id)
                ->where("verification","allotted")
                ->first();

            if ($relatedRecord) {
                // Add the related Booking_Form data to the result array
                $relatedBookingData[] = $relatedRecord;
            }
        }
    }

    // Determine the response based on whether related data was found
    if (count($relatedBookingData) > 0) {
        $returndata = [
            "status_result" => "success",
            "engineering_booking_data" => $relatedBookingData, // Send the related records data to the frontend
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    } else {
        $returndata = [
            "status_result" => "failure",
            "engineering_booking_data" => [], // No related records found
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    }

}
//get s&t data


public function get_ST_Data(Request $req)
{
    
    // Fetch all records from amenity_charges
    $amenityRecords = DB::table('amenity_charges')->get();

    // Array to hold records from Booking_Form if any field in amenity_charges is null
    $relatedBookingData = [];

    // Iterate through each record in amenity_charges
    foreach ($amenityRecords as $amenity) {
        // Check if any of the specified fields are null
        if (is_null($amenity->s_t_remarks) || 
            is_null($amenity->s_t_Amount) ) {

            // Fetch the related Booking_Form data based on ref_id
            $relatedRecord = DB::table('Booking_Form')
                ->where('BF_id', $amenity->ref_id)
                ->where("verification","allotted")
                ->first();

            if ($relatedRecord) {
                // Add the related Booking_Form data to the result array
                $relatedBookingData[] = $relatedRecord;
            }
        }
    }

    // Determine the response based on whether related data was found
    if (count($relatedBookingData) > 0) {
        $returndata = [
            "status_result" => "success",
            "st_booking_data" => $relatedBookingData, // Send the related records data to the frontend
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    } else {
        $returndata = [
            "status_result" => "failure",
            "st_booking_data" => [], // No related records found
        ];
        $encryptedResponse = $this->encryption($returndata);
    return ["return_response" => $encryptedResponse];
    }

}


}
