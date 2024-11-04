<?php
// Include the doubleVerification function
include('double_verification.php'); 

include('AES128_php.php');
$aes = new AESEncDec();

// Define your key
$key = "pWhMnIEMc4q6hKdi2Fx50Ii8CKAoSIqv9ScSpwuMHM4=";

if (isset($_REQUEST['encData']) && !empty($_REQUEST['encData'])) {
    "<br>Encrypted data received: " . $_REQUEST['encData'];

    // Decrypt the encrypted data
    $encData = $aes->decrypt($_REQUEST['encData'], $key);
    "<br>Decrypted data: " . $encData;

    // Separate the data fields from the decrypted string
    // print_r($dataFields = explode("|", $encData));
    $dataFields = explode("|", $encData);
    $merchantid = $dataFields[0];
    $merchant_order_no = $dataFields[9];
    $amount = $dataFields[3];
    $transaction_response = $encData;
    $transaction_status = $dataFields[2];

    // echo "<br>Merchant ID: $merchantid";
    // echo "<br>Merchant Order No: $merchant_order_no";
    // echo "<br>Amount: $amount";
    // Database connection details
    $serverName = "localhost";
    $username = "scrailw2_rksc";
    $password = "alMZlNV_Z?*p";
    $dbname = "scrailw2_railkalarang";
//     DB_DATABASE=scrailw2_railkalarang
// DB_USERNAME=scrailw2_rksc
// DB_PASSWORD=alMZlNV_Z?*p
    $conn = new mysqli($serverName,$username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $level = "3";
    // Prepare and bind the SQL statement
      $stmt = $conn->prepare("UPDATE Transaction_table SET merchant_id = ?, merchant_order_no = ?,transaction_success_response = ?,transaction_status = ?,level = ? WHERE Order_number = ?");
    
    // Correct the bind_param call to match the number of placeholders
    $stmt->bind_param("ssssss",$merchantid,$merchant_order_no,$transaction_response,$transaction_status,$level,$merchantid);
    if ($stmt->execute()) {
        echo "<br>Record updated successfully";
    } else {
        echo "<br>Error updating record: " . $stmt->error;
    }
    
    
    $idmt = $conn->prepare("SELECT Ref_id FROM Transaction_table WHERE merchant_id = ?");
$idmt->bind_param("s", $merchantid);
$idmt->execute();
$result = $idmt->get_result();


    $row = $result->fetch_assoc();
    $ref_id = $row['Ref_id'];
    echo "<br>";
 

$idmt->close();
$mlevel = "4";
$status = "paid";
 $lmt = $conn->prepare("update Booking_Form set level = ?, merchant_order_no = ?,verification = ? where BF_id = ?");
    
    // Correct the bind_param call to match the number of placeholders
    $lmt->bind_param("ssss",$mlevel,$merchant_order_no,$status,$ref_id);
    if ($lmt->execute()) {
        echo "<br>Record updated successfully";
    } else {
        echo "<br>Error updating record: " . $stmt->error;
    }
    
$lmt->close();
    // $sql = "update Booking_Form set level = ?, merchant_order_no = ? where Ref_id = ?";
    // $result = $conn->query($sql);

    
    // $response = doubleVerification($merchantid, $merchant_order_no, $amount);
    // echo "<br>Double Verification Response: " . $response;
    // $dvdataFields = explode("|", $response);
    // $dvsuccessid = $dvdataFields[3];

    // $stmt = $conn->prepare("UPDATE Transaction_table SET dv_response = ?,dv_success_id = ? WHERE Order_number = ?");
    // $stmt->bind_param("sss",$response,$dvsuccessid,$merchantid);

    // if ($stmt->execute()) {
    //     echo "<br>Double verification response updated successfully";
    // } else {
    //     echo "<br>Error updating double verification response: " . $stmt->error;
    // }

    // // Close the statement and connection
    // $stmt->close();
    $conn->close();
    
   
    //   header("Location: http://localhost:4200/#/reciept");
    header("Location: https://rlyfunhalls.indianrailways.gov.in/Railkalarang/#/reciept");
} else {
    die("Please try again... No encrypted data found.");
}
?>

 
 
 
 

 
 
 
