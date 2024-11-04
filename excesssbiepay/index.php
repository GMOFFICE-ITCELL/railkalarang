<?php
 require_once('AES128_php.php'); 
 $AESobj=new AESEncDec();

/*
	Sample SBI EPay 
*/
 "<b><center>Billing, Shipping and Payment Model</center></b><br/><br/>";
//  echo    $amount = $_POST['EncString'] ?? '';
 echo $amount = $_POST['AmountField'] ?? '';
 echo $details = $_POST['DetailsField'] ?? '';
 echo $order = $_POST['OrderField'] ?? '';
 

//encryption key
$key = "pWhMnIEMc4q6hKdi2Fx50Ii8CKAoSIqv9ScSpwuMHM4=";
 '<br>';
 "iv =".$iv=substr($key, 0, 16);
//requestparameter 
//merchantid|
// $requestParameter  ="1000605|DOM|IN|INR|".$amount."|Shital^1234567895^Mumbai|https://SbiEpay/sbi_mcrypt_lib/sbi_epay/sucess.php|https://SbiEpay/sbi_mcrypt_lib/sbi_epay/fail.php|SBIEPAY|SCRRKGBKID123TRID1225|2|NB|ONLINE|ONLINE";

 $requestParameter  ="1000605|DOM|IN|INR|".$amount."|".$details."|https://rlyfunhalls.indianrailways.gov.in/railkalarang/excesssbiepay/success_pay.php|https://rlyfunhalls.indianrailways.gov.in/railkalarang/excesssbiepay/failure.php|SBIEPAY|".$order."|2|NB|ONLINE|ONLINE";

 '<b>Requestparameter:-</b> '.$requestParameter.'<br/><br/>';
//Billingdetails
// $billingDtls ="BillerName|Mumbai|Maharastra|403706|India|+91|222|1234567|9892456281|biller@gmail.com|N";
// echo '<b>Billingdetails:-</b> '.$billingDtls.'<br/><br/>';
//Shippingdetails
// $shippingDtls ="ShipperName|Mayuresh Enclave, Sector 20, Plat A-211, Nerul(w),Navi-Mumbai,403706|Mumbai|Maharastra|India|403706|+91|222|30988373|981234567";
// echo '<b>Shippingdetails:-</b> '.$shippingDtls.'<br/><br/>';
//Paymentdetails
// $PaymentDtls="aggGtwmapID| | | | | | |";
// echo '<b>Paymentdetails:-</b> '.$PaymentDtls.'<br/><br/>';


// $aes = new Crypt_AES();
// $secret=base64_decode($key);
// $aes->setKey($secret);

// $AESobj->encrypt($data,$key);

$EncryptTrans = $AESobj->encrypt($requestParameter,$key);
// $EncryptbillingDetails=$AESobj->encrypt($billingDtls,$key);
// $EncryptshippingDetais=$AESobj->encrypt($shippingDtls,$key);
// $EncryptpaymentDetails=$AESobj->encrypt($PaymentDtls,$key);
	

 '<b>Encrypted EncryptTrans:-</b>'.$EncryptTrans.'<br/><br/>';
// echo '<b>Encrypted EncryptbillingDetails:-</b> '.$EncryptbillingDetails.'<br/><br/>';
// echo '<b>Encrypted EncryptshippingDetais:-</b>'.$EncryptshippingDetais.'<br/><br/>';
// echo '<b>Encrypted EncryptpaymentDetails:-</b>'.$EncryptpaymentDetails.'<br/><br/>';
// echo "<br/>Action URL:https://test.sbiepay.com/secure/AggregatorHostedListener"; 

?>
<form name="ecom" method="post" action="https://test.sbiepay.sbi/secure/AggregatorHostedListener">
	<input type="hidden" name="EncryptTrans" value="<?php echo $EncryptTrans; ?>">
	<!--<input type="hidden" name="EncryptbillingDetails" value="<?php echo $EncryptbillingDetails; ?>">-->
	<!--<input type="hidden" name="EncryptshippingDetais" value="<?php echo $EncryptshippingDetais; ?>">-->
	<!--<input type="hidden" name="EncryptpaymentDetails" value="<?php echo $EncryptshippingDetais; ?>">-->
	<!--<input type="hidden" name="merchIdVal" value ="1000605"/>-->
	<!--<input type="submit" name="submit" value="Submit">-->
 <input type="hidden" name="merchIdVal" value ="1000605"/>
    </form>

    <script>
        // Auto-submit the form when the page loads
        document.ecom.submit();
    </script>