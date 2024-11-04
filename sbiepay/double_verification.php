<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

   $merchantid = $_POST['DetailsField'] ?? '';
  $merchant_order_no = $_POST['OrderField'] ?? '';
 $amount = $_POST['AmountField'] ?? '';
 
 if (!empty($merchantid) && !empty($merchant_order_no) && !empty($amount)) {

    // Call double verification function
    $response = doubleVerification($merchantid, $merchant_order_no, $amount);

    // Assuming you want to use the response for something afterward
     "<br>Double Verification Response: " . $response;

  
        // You need to establish a connection to the database before running queries
    $serverName = "localhost";
    $username = "scrailw2_rksc";
    $password = "alMZlNV_Z?*p";
    $dbname = "scrailw2_railkalarang";
//     DB_DATABASE=scrailw2_railkalarang
// DB_USERNAME=scrailw2_rksc
// DB_PASSWORD=alMZlNV_Z?*p
    $conn = new mysqli($serverName,$username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Parse the response (assuming it's delimited by "|")
        $dvdataFields = explode("|", $response);
        echo $dvsuccessid = $dvdataFields[3] ?? ''; // Make sure index 3 exists

        // Prepare and bind the SQL update statement
        $stmt = $conn->prepare("UPDATE Transaction_table SET dv_response = ?, dv_success_id = ? WHERE Order_number = ?");
        $stmt->bind_param("sss", $response, $dvsuccessid, $merchantid);
        
         $idmt = $conn->prepare("SELECT Ref_id FROM Transaction_table WHERE merchant_id = ?");
$idmt->bind_param("s", $merchantid);
$idmt->execute();
$result = $idmt->get_result();


    $row = $result->fetch_assoc();
    $ref_id = $row['Ref_id'];
    echo "<br>";
 

$idmt->close();
        
        $mlevel = "6";
        $status = "allotted";
        $lmt = $conn->prepare("update Booking_Form set level = ?, merchant_order_no = ?,verification = ? where BF_id = ?");
    
    // Correct the bind_param call to match the number of placeholders
    $lmt->bind_param("ssss",$mlevel,$merchant_order_no,$status,$ref_id);
    if ($lmt->execute()) {
        echo "<br>Record updated successfully";
    } else {
        echo "<br>Error updating record: " . $stmt->error;
    }
    
$lmt->close();

        // Execute the statement and check for success
        if ($stmt->execute()) {
             "<br>Double verification response updated successfully";
             header("Location: https://rlyfunhalls.indianrailways.gov.in/Railkalarang/#/adminmenu");
        } else {
             "<br>Error updating double verification response: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    

} else {
    echo "Error: Missing required POST data.";
}

function doubleVerification($merchantid, $merchant_order_no, $amount)
{
    // Double verification URL
    $url = "https://www.sbiepay.sbi/payagg/statusQuery/getStatusQuery"; // Update the URL if needed

    // Create the query request string
    $queryRequest = "|$merchantid|$merchant_order_no|$amount";
    $queryRequest33 = http_build_query(array(
        'queryRequest' => $queryRequest,
        "aggregatorId" => "SBIEPAY",
        "merchantId" => $merchantid
    ));

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryRequest33);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        echo "cURL Error: $error_msg";
    } else {
        // Return the response from the server
        return $response;
    }

    // Close the cURL session
    curl_close($ch);
   
}
?>
