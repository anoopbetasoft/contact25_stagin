<?php 

session_start();
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");


#die("#".($service));
$apiURL = "https://api.royalmail.net/shipping/v2";
$dateTime = date('Y-m-d\TH:i:s');
$applicationId = "RMG-API-G-01";
$transactionId = rand(1000000,900000000);
$shippingDate = gmdate('Y-m-d');
$shipmentType = "Delivery";
$serviceOccurrence = 1;
$serviceType = $service;
$serviceCode = $service_class;
$serviceFormat = $format_RM;
$recipientContactName = $name;
$recipientAddressLine1 = $add_1;
$recipientAddressLine2 = $add_2;
$postTown = $add_4;
$postcode = $postcode;
$countryCode = $country;
$noOfItems = "1";
$unitOfMeasure = "g";
$weightValue = $total_weight;
$sendersReference = "Contact25 // ".$_POST['order_id'];




/* Change the values below to the ClientID and Secret values associated with the application you
 * registered on the API Developer Portal
 */
$clientId = "0456e1a2-db52-4e68-9c09-8c388861f542";
$clientSecret = "eH2nY6bV8fP1pT5vF1rH2uL2aQ7oL0pA1sM6uS2nI0yR6uP8qI";

/* The value below should be changed to your actual username and password.  If you store the password
 * as hashed in your database, you will need to change the code below to remove hashing
 */
$username = "avila@jukeboxmarketing.comAPI";
$password = "Password2014!";
$password = "82P6w444%";

/* CREATIONDATE - The timestamp. The computer must be on correct time or the server you are
* connecting may reject the password digest for security.
*/
$creationDate = gmdate('Y-m-d\TH:i:s\Z');

/* NONCE - A random word.
* The use of rand() may repeat the word if the server is very loaded.
*/
$nonce = mt_rand();

/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard
* digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
* Note that we use a Sha1(password) instead of the plain password above
*/
$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE));
$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

/* ENCODEDNONCE - Now encode the nonce for security header */
$encodedNonce = base64_encode($nonce);

$curl = curl_init();

// CREATE MANIFEST //

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"><SOAP-ENV:Header><oas:Security xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\">\n  <!--You may enter ANY elements at this point-->\n  <AnyElement/>\n</oas:Security></SOAP-ENV:Header><SOAP-ENV:Body><v2:createManifestRequest xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\">\n  <v2:integrationHeader>\n    <!--Optional:-->\n    <v1:dateTime>2008-09-28T20:49:45</v1:dateTime>\n    <!--Optional:-->\n    <v1:version>1000.00</v1:version>\n    <v1:identification>\n      <!--Optional:-->\n      <v1:endUserId>string</v1:endUserId>\n      <v1:applicationId>string</v1:applicationId>\n      <!--Optional:-->\n      <v1:intermediaryId>string</v1:intermediaryId>\n      <v1:transactionId>string</v1:transactionId>\n    </v1:identification>\n    <!--Optional:-->\n    <v1:testFlag>true</v1:testFlag>\n    <!--Optional:-->\n    <v1:debugFlag>true</v1:debugFlag>\n    <!--Optional:-->\n    <v1:performanceFlag>true</v1:performanceFlag>\n  </v2:integrationHeader>\n  <!--Optional:-->\n  <v2:serviceOccurrence>100</v2:serviceOccurrence>\n  <!--Optional:-->\n  <v2:serviceOffering>\n    <serviceOfferingCode>\n      <!--Optional:-->\n      <identifier>string</identifier>\n      <!--Optional:-->\n      <code>string</code>\n      <!--Optional:-->\n      <name>string</name>\n      <!--Optional:-->\n      <description>string</description>\n    </serviceOfferingCode>\n  </v2:serviceOffering>\n  <!--Optional:-->\n  <v2:yourDescription>string</v2:yourDescription>\n  <!--Optional:-->\n  <v2:yourReference>string</v2:yourReference>\n</v2:createManifestRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>",
  CURLOPT_HTTPHEADER => array(
    "accept: application/soap+xml",
    "accept-encoding: gzip,deflate",
    "connection: keep-alive",
    "content-type: text/xml",
    "host: api.royalmail.net",
    "soapaction: \"createShipment\"",
    "x-ibm-client-id: $clientId",
    "x-ibm-client-secret: $clientSecret"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  #echo $response;
}


// PRINT MANIFEST //

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"><SOAP-ENV:Header><oas:Security xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\">\n  <!--You may enter ANY elements at this point-->\n  <AnyElement/>\n</oas:Security></SOAP-ENV:Header><SOAP-ENV:Body><v2:printManifestRequest xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\">\n  <v2:integrationHeader>\n    <!--Optional:-->\n    <v1:dateTime>2008-09-28T20:49:45</v1:dateTime>\n    <!--Optional:-->\n    <v1:version>1000.00</v1:version>\n    <v1:identification>\n      <!--Optional:-->\n      <v1:endUserId>string</v1:endUserId>\n      <v1:applicationId>string</v1:applicationId>\n      <!--Optional:-->\n      <v1:intermediaryId>string</v1:intermediaryId>\n      <v1:transactionId>string</v1:transactionId>\n    </v1:identification>\n    <!--Optional:-->\n    <v1:testFlag>true</v1:testFlag>\n    <!--Optional:-->\n    <v1:debugFlag>true</v1:debugFlag>\n    <!--Optional:-->\n    <v1:performanceFlag>true</v1:performanceFlag>\n  </v2:integrationHeader>\n  <!--You have a CHOICE of the next 2 items at this level-->\n  <v2:manifestBatchNumber>string</v2:manifestBatchNumber>\n  <v2:salesOrderNumber>string</v2:salesOrderNumber>\n</v2:printManifestRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>",
  CURLOPT_HTTPHEADER => array(
    "accept: application/xml",
    "content-type: text/xml",
    "soapaction: printManifest",
    "x-ibm-client-id: $clientId",
    "x-ibm-client-secret: $clientSecret"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

$output = json_decode(json_encode(simplexml_load_string(strtr($response, array(' xmlns:'=>' ')))), 1);
$pdf_decoded = ($output[Body][printLabelResponse][label]);

//Decode pdf content
$pdf_decoded = base64_decode ($pdf_decoded);
#die($pdf_decoded);
//Write data back to pdf file

$filename = 1;
$i = 0;
do{
	if (file_exists('../../rm_manifest/'.$filename.'.pdf')) {
		//echo "The file $filename exists";
		$filename ++;
	} else {
		// finished - file doesn't exist
		$i = 1;
	}
}while($i < 1);



$pdf = fopen ('../../rm_manifest/'.$filename.'.pdf','w');#die("test marker");
fwrite ($pdf,$pdf_decoded);
//close output file
fclose ($pdf);


 


function clean($string) {
	$string = trim($string); 
   	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	$string = str_replace('-', ' ', $string);
   return $string;  // Removes special chars.
}

?>