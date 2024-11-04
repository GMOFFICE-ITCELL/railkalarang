<?php
/*
Sample SBI EPay
*/
echo "<b><center>Payment Model Status</center></b><br/><br/>";

include('AES128_php.php');

//encryption key
$key = "A7C9F96EEE0602A61F184F4F1B92F0566B9E61D98059729EAD3229F882E81C3A";
//requestparameter
$requestParameter  = "|1000112|0000001534|75346|https://gpnagpur.ac.in/gperp/government_polytechnic_nagpur_online_payment_sbi/rv_submit_test_ret_url.php";
echo '<b>Requestparameter:-</b> '.$requestParameter.'<br/><br/>';

//$aes = new CryptAES();
//$aes->set_key(base64_decode($key));
//$aes->require_pkcs5();


//$EncryptTrans = $aes->encrypt($requestParameter, $key);



$AESobj = new AESEncDec();
//$aes->set_key(base64_decode($key));
//$aes->require_pkcs5();

$EncryptTrans = $AESobj->encrypt($requestParameter,$key);
// echo $orderid;

echo '<b>Encrypted EncryptTrans:-</b>'.$EncryptTrans.'<br/><br/>';
echo "<br/>Action URL:https://test.sbiepay.sbi/secure/AggMerchantStatusQueryAction";

?>

<form name="ecomStatus" method="post" action="https://test.sbiepay.sbi/secure/aggMerchantStatusQueryWithAmountAction">
    <input type="hidden" name="encryptQuery" value="<?php echo $EncryptTrans; ?>">
    <input type="hidden" name="merchIdVal" value ="1000112"/>
    <input type="hidden" name="aggIdVal" value ="SBIEPAY"/>
    <input type="submit" name="submit" value="Submit">
</form>
