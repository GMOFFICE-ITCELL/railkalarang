<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
