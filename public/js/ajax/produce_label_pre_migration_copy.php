<?php 
 
session_start();
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");


$sql = "SELECT 
			*
		FROM
			orders,
			order_details,
			spares,
			countries
		WHERE
			orders.o_id = '".$_POST['order_id']."'
		AND
			orders.o_id = order_details.od_o_id
		AND
			spares.s_id = order_details.od_s_id
		AND
			countries.c_id = orders.o_country";

$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
;
if ($num_rows>0){
	
	$formats 	= array();
	$weight 	= array();
	$price 		= array();
	
	do{
		// Address Details //
		$name 			= clean($row['o_name']);
		$add_1			= clean($row['o_address_del_1']);
		$add_2			= clean($row['o_address_del_2']);
		$add_3			= clean($row['o_address_del_3']);
		$add_4			= clean($row['o_address_del_4']);
		$postcode		= $row['o_postcode_del'];
		$country		= $row['c_code'];
		
		if (!strlen($add_1)>0)
		{
			$add_1 		= $add_2;
		}
		
		array_push ($weight, ($row['s_weight']*$row['od_qty']));
		array_push ($price, ($row['od_price']*$row['od_qty']));
		
		$delivery		= $row['o_delivery'];
		if ($row['o_delivery']>0){
			$service = 24;	
			$service_class = 24;
		}else{
			$service = 48;
			$service_class = 48;
		}
		
		if ($row['s_weight'] > 0){
		}else{
			die("add weight to SKU: ".$row['od_s_id']);
		}	
		if (strlen($row['s_format']) > 0){
		}else{
			die("add format to SKU: ".$row['od_s_id']);
		}
		
		
		$format = $row['s_format'];
		if (($row['od_qty']>0)&&($row['s_next_format']>0)){
				
				if ($row['od_qty']>=$row['s_next_format']){
					
					/*move format up a level (L to LL, LL to P)*/
					if ($format == 'L'){
						
						$format = 'LL';
						#die("format".$format."##".$row['s_next_format']."##".$row['od_qty']);
						if ($service == 1){
							$service = 24;
						}
						if ($service == 2){
							$service = 48;
						}
					}elseif ($format == 'LL'){
						$format = 'P';	
					}
					
				}
				
			}
		
			if ($country != 'GB')
			{
				$service = 'I';
				$service_class = 'OLA';
				if ($format == 'L'){
					$format = 'IL';
				}
				if ($format == 'LL'){
					$format = 'ILL';
				}
				if ($format == 'P'){
					$format = 'IP';
				}
				
			}
		
			array_push ($formats, $format);
		
			$sql = 'UPDATE 
						order_details 
					SET 
						od_rm_label = 1, 
						od_purchased = 1, 
						od_rm_label_format="'.$format.'",
						od_rm_label_service="'.$service.'", 
						od_rm_label_weight="'.$row['s_weight'].'"  
					WHERE 
						od_o_id = "'.$row['od_o_id'].'"';
			mysql_query($sql);
		
	}while($row = mysql_fetch_assoc($query));
}

if (in_array("P", $formats))
  {
  	$end_format = "P";
  }elseif(in_array("LL", $formats))
  {
  	$end_format = "LL";
  }elseif(in_array("IL", $formats))
  {
  	$end_format = "IL";
  }elseif(in_array("ILL", $formats))
  {
  	$end_format = "ILL";
  }elseif(in_array("IP", $formats))
  {
  	$end_format = "IP";
  }else{
   	$end_format = "L";
  }

// SPECIFIC FORMAT PUSHED IF THE USER KNOWS IT SHOULD GO UP A FORMAT
if (!empty($_POST['format'])){
	$format = $_POST['format'];
}else{
	$format = $end_format;

}


$total_weight = array_sum($weight);
$total_price = array_sum($price);

if (strlen($add_4)>0){
	$postcode_town = $add_4;	
}else{
	if(strlen($add_3)>0){
		$postcode_town = $add_3;	
	}else{
		if(strlen($add_2)>0){
			$postcode_town = $add_2;	
		}
	}
}
	
	
if ($format == 'L'){
	if ($delivery>0){
		$service = 24;		
	}else{
		$service = 48;	
	}	
}
			
/*
	CALCULATE THE DATE
	 - If after 16:00, it's after the collection point for that day - set it as the following day
	 - If it's the weekend (Sat/Sun), go to the next monday.
*/

$current_hour = date("H");
$days_added = 0;
if ($current_hour < 16){
	$posting_date = date("Ymd");
}else{
	$posting_date = date("Ymd", strtotime("+1 day"));
	$days_added ++;
}
			
$day_of_week = date('N');
$weekend_days_extra = 0;

if ($weekend_days_extra>0){
	$posting_date = date("Ymd", strtotime("+".$weekend_days_extra." day"));	
}

/*manually tweaking post day*/
if ($posting_date=='20161124'){
	$posting_date = date("Ymd", strtotime("+1 day"));	
}

			
if (($format == 'LL')|| ($format == 'P')){

		if ($service == 1){
			$service = 24;
		}
		if ($service == 2){
			$service = 48;
		}
}	
			
			
/*special rules for weight*/
if (($total_weight > 749)&&($format != 'P')){
	$format = 'P'; ## force parcel if too heavy for large letter

}

if ($total_weight > 2000){
	$format = 'P'; ## force parcel if too heavy for large letter
	$service = 24; ## it's too heavy for 2nd class
	$service_class = 24;
}
			
	
if ((strlen($_GET['signed'])>0)||($delivery==4.99)||($total_price>50)){
	$signed = 	1;
}else{
	$signed = 	"";
}
			
if (($delivery==-2.99)||($delivery==-4.99)){
	$service = 24;
	$service_class = 24;
}

/*user requred post upgrade*/
if ($_POST['upgrade'] == 1){
	$service = 24;
	$service_class = 24;
}


/* records parcel */


$service_class = 'CRL';
// take mapped values & turned them back into RM understood values //
if ($service == '24'){
	$service = '1';
}
if ($service == '48'){
	$service = '2';
}
if ($format == 'P'){
	$format_RM = 'P';
}
if ($format == 'IPP'){
	$format_RM = 'H';
	$service_class = 'OLA';
	$service = 'I';
}
if ($format == 'IP'){
	$format_RM = 'E';
	$service_class = 'OLA';
	$service = 'I';
}
if ($format == 'ILL'){
	$format_RM = 'G';
	$service_class = 'OLA';
	$service = 'I';
}

if ($format == 'LL'){
	$format_RM = 'F';
}



#die("test");
if ($format == 'IL'){
	$format_RM = 'P';
	$service_class = 'OLA';
	$service = 'I';
	
}
if ($format == 'L'){
	$format_RM = 'L';
	$service_class = 'STL';
	## THINK THIS IS DEFINED ABOVE - TAKEN OUT SO 1ST CLASS WORKS ## $service = 2;
}
$sql = 'update test set value = "'.$format_RM.'#'.$service_class.'#'.$service.'"';
mysql_query($sql);
#die($service_class);
#$service = "1";

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
$password = "Password2014*";

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

/* Print all WS-Security values for debugging
 * echo 'nonce: ' . $nonce . PHP_EOL;
 * echo 'password digest: ' . $passwordDigest . PHP_EOL;
 * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL;
 * echo 'creation date: ' . $creationDate . PHP_EOL;
 */

$curl = curl_init();

/* The commented code below is provided for customers to adapt to handle the client side security
 * implementation for the API
 *
 * PHP code to validate the certificate returned from APIm
 * CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
 * 0: Dont check the common name (CN) attribute
 * 1: Check that the common name attribute at least exists
 * 2: Check that the common name exists and that it matches the host name of the server
 */
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");#

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\">\r\n   <soapenv:Header>\r\n      <wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n         <wsse:UsernameToken>\r\n            <wsse:Username>$username</wsse:Username>\r\n            <wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n            <wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n            <wsu:Created>$creationDate</wsu:Created>\r\n         </wsse:UsernameToken>\r\n      </wsse:Security>\r\n   </soapenv:Header>\r\n   <soapenv:Body>\r\n      <v2:createShipmentRequest>\r\n         <v2:integrationHeader>\r\n            <v1:dateTime>$dateTime</v1:dateTime>\r\n            <v1:version>2</v1:version>\r\n            <v1:identification>\r\n               <v1:applicationId>$applicationId</v1:applicationId>\r\n               <v1:transactionId>$transactionId</v1:transactionId>\r\n            </v1:identification>\r\n         </v2:integrationHeader>\r\n         <v2:requestedShipment>\r\n            <v2:shipmentType>\r\n               <code>$shipmentType</code>\r\n            </v2:shipmentType>\r\n            <v2:serviceOccurrence>$serviceOccurrence</v2:serviceOccurrence>\r\n            <v2:serviceType>\r\n               <code>$serviceType</code>\r\n            </v2:serviceType>\r\n            <v2:serviceOffering>\r\n               <serviceOfferingCode>\r\n                  <code>$serviceCode</code>\r\n               </serviceOfferingCode>\r\n            </v2:serviceOffering>\r\n            <v2:serviceFormat>\r\n               <serviceFormatCode>\r\n                  <code>$serviceFormat</code>\r\n               </serviceFormatCode>\r\n            </v2:serviceFormat>\r\n            <v2:shippingDate>$shippingDate</v2:shippingDate>\r\n            <v2:recipientContact>\r\n               <v2:name>$recipientContactName</v2:name>\r\n            </v2:recipientContact>\r\n            <v2:recipientAddress>\r\n               <addressLine1>$recipientAddressLine1</addressLine1>\r\n <addressLine2>$recipientAddressLine2</addressLine2>\r\n               <postTown>$postTown</postTown>\r\n               <postcode>$postcode</postcode>\r\n               <country>\r\n                  <countryCode>\r\n                     <code>$countryCode</code>\r\n                  </countryCode>\r\n               </country>\r\n            </v2:recipientAddress>\r\n            <v2:items>\r\n               <v2:item>\r\n                  <v2:numberOfItems>$noOfItems</v2:numberOfItems>\r\n                  <v2:weight>\r\n                     <unitOfMeasure>\r\n                        <unitOfMeasureCode>\r\n                           <code>$unitOfMeasure</code>\r\n                        </unitOfMeasureCode>\r\n                     </unitOfMeasure>\r\n                     <value>$weightValue</value>\r\n                  </v2:weight>\r\n               </v2:item>\r\n            </v2:items>\r\n            <v2:senderReference>$sendersReference</v2:senderReference>\r\n         </v2:requestedShipment>\r\n      </v2:createShipmentRequest>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
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
$output = json_decode(json_encode(simplexml_load_string(strtr($response, array(' xmlns:'=>' ')))), 1);
$shipment_number = ($output[Body][createShipmentResponse][completedShipmentInfo][allCompletedShipments][completedShipments][shipments][shipmentNumber]);

#die(var_dump($item_id));


/* start of 2nd call to print*/
$service = "1";
$service_class = 'CRL';
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
$password = "Password2014*";

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

/* Print all WS-Security values for debugging
 * echo 'nonce: ' . $nonce . PHP_EOL;
 * echo 'password digest: ' . $passwordDigest . PHP_EOL;
 * echo 'encoded nonce: ' . $encodedNonce . PHP_EOL;
 * echo 'creation date: ' . $creationDate . PHP_EOL;
 */

$curl = curl_init();

/* The commented code below is provided for customers to adapt to handle the client side security
 * implementation for the API
 *
 * PHP code to validate the certificate returned from APIm
 * CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
 * 0: Dont check the common name (CN) attribute
 * 1: Check that the common name attribute at least exists
 * 2: Check that the common name exists and that it matches the host name of the server
 */
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\">\r\n   <soapenv:Header>\r\n      <wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\r\n         <wsse:UsernameToken>\r\n            <wsse:Username>$username</wsse:Username>\r\n            <wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password>\r\n            <wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">$encodedNonce</wsse:Nonce>\r\n            <wsu:Created>$creationDate</wsu:Created>\r\n         </wsse:UsernameToken>\r\n      </wsse:Security>\r\n   </soapenv:Header>  <soapenv:Body>      <v2:printLabelRequest>         <v2:integrationHeader>            <v1:dateTime>$creationDate</v1:dateTime>            <v1:version>2</v1:version>            <v1:identification>               <v1:applicationId>$applicationId</v1:applicationId>              <v1:transactionId>$transactionId</v1:transactionId>            </v1:identification>         </v2:integrationHeader>         <v2:shipmentNumber>$shipment_number</v2:shipmentNumber>      </v2:printLabelRequest>   </soapenv:Body></soapenv:Envelope>",
  CURLOPT_HTTPHEADER => array(
    "accept: application/soap+xml",
    "accept-encoding: gzip,deflate",
    "connection: keep-alive",
    "content-type: text/xml",
    "host: api.royalmail.net",
    "soapaction: \"printLabel\"",
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

#echo $response;

$output = json_decode(json_encode(simplexml_load_string(strtr($response, array(' xmlns:'=>' ')))), 1);
$pdf_decoded = ($output[Body][printLabelResponse][label]);
#die($pdf_decoded);

/*
$pdf_base64 = "../../rm_labels/base64pdf.txt";
//Get File content from txt file
$pdf_base64_handler = fopen($pdf_base64,'r');
$pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
fclose ($pdf_base64_handler);*/

//Decode pdf content
$pdf_decoded = base64_decode ($pdf_decoded);
#die($pdf_decoded);
//Write data back to pdf file
$pdf = fopen ('../../rm_labels/'.$_POST['order_id'].'.pdf','w');#die("test marker");
fwrite ($pdf,$pdf_decoded);
//close output file
fclose ($pdf);

/*
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, 'stackoverflow.com', 80);
socket_getsockname($socket, $host, $port);
var_dump($port);
socket_close($socket);

// Open a telnet connection to the printer, then push all the data into it.

try
{
    $fp=fsockopen("localhost",46301);
    fputs($fp,$pdf_decoded);
    fclose($fp);

    echo 'Successfully Printed';
}
catch (Exception $e) 
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
*/


echo $_POST['order_id'];







die();







$pdf = fopen ('../../rm_labels/123.pdf','w');
file_put_contents ($pdf,$pdf_decoded);
die($pdf_decoded);


$pdf_decoded = base64_decode ($pdf_decoded);





die("saved?");
#file_put_contents('~/pdf/'.$pdf.'.pdf', $wc->out->document);

// upgrade to php 5.4 https://stackoverflow.com/questions/23101998/php-print-to-printer-local-network-directly-stuck-in-print-spooler
$pdf_decoded = base64_decode ($pdf);
#$handle = printer_open() or die($pdf_decoded);
exec("lpr -P printer -r filename.txt");
die("test");

$printerList = printer_list(PRINTER_ENUM_LOCAL);
        var_dump($printerList);


die("test");

die($pdf_decoded);



$printerList = printer_list(PRINTER_ENUM_LOCAL);
        var_dump($printerList);

die();



$filecontents = file_get_contents($pdf);
print $filecontents;

die();
#die(var_dump($pdf));
error_reporting(E_ALL);

/* Get the port for the service. */
$port = "9100";

/* Get the IP address for the target host. */
$host = "localhost";

/* construct the label */
$mrn = "123456";
$registration_date = "03/13/2013";
$dob = "06/06/1976";
$gender = "M";
$nursing_station = "ED";
$room = "ED01";
$bed = "07";
$lastname = "Lastname";
$firstname = "Firstname";
$visit_id = "12345678";

$label = "q424\nN\n";
$label .= "A10,16,0,3,1,1,N,\"MR# " . $mrn . " ";
$label .= $registration_date . "\"\n";
$label .= "B10,43,0,3,2,4,50,N,\"" . $mrn . "\"\n";
$label .= "A235,63,0,3,1,1,N,\" ";
$label .= $dob . " ";
$label .= $gender . "\"\n";
$label .= "A265,85,0,3,1,1,N,\" ";
$label .= $nursing_station . " ";
$label .= $room . "-";
$label .= $bed . "\"\n";
$label .= "A10,108,0,3,1,1,N,\"";
$label .= $lastname . ",";
$label .= $firstname;
$label .= "\"\n";
$label .= "A10,135,0,3,1,1,N,\" #" . $visit_id . "\"\n";
$label .= "B10,162,0,3,2,4,50,N,\"" . $visit_id . "\"\n";
$label .= "P1\n";

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error    ()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$host' on port '$port'...";
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror    (socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

socket_write($socket, $label, strlen($label));
socket_close($socket);

/* end of 2nd call to print*/
die(var_dump($pdf));



$curl = curl_init();

/* The commented code below is provided for customers to adapt to handle the client side security
 * implementation for the API
 *
 * PHP code to validate the certificate returned from APIm
 * CURLOPT_SSL_VERIFYHOST can be set to the following integer values:
 * 0: Dont check the common name (CN) attribute
 * 1: Check that the common name attribute at least exists
 * 2: Check that the common name exists and that it matches the host name of the server
 */
// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
// curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "\(path)\api.royalmail.net.crt");

$dateTime = date('Y-m-d\TH:i:s');
$applicationId = "RMG-API-G-01";
$transactionId = rand(1000000,900000000);
$nonce = mt_rand();

/* PASSWORDDIGEST This is the way to create the password digest. As per OASIS standard
* digest = base64_encode(Sha1(nonce + creationdate + Sha1(password)))
* Note that we use a Sha1(password) instead of the plain password above
*/
$nonce_date_pwd = $nonce.$creationDate.base64_encode(sha1($password, TRUE));
$passwordDigest = base64_encode(sha1($nonce_date_pwd, TRUE));

/* ENCODEDNONCE - Now encode the nonce for security header */
$encodedNonce = base64_encode($nonce);

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiURL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v1=\"http://www.royalmailgroup.com/integration/core/V1\" xmlns:v2=\"http://www.royalmailgroup.com/api/ship/V2\"><soapenv:Header><wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\"><wsse:UsernameToken wsu:Id=\"UsernameToken-FD7DC88B2EFF9C3ABB14967577963855\"><wsse:Username>$username</wsse:Username><wsse:Password Type=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest\">$passwordDigest</wsse:Password><wsse:Nonce EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\">piZSYcEKcgJEVto3/zxKow==</wsse:Nonce><wsu:Created>$creationDate</wsu:Created></wsse:UsernameToken></wsse:Security>
      
   </soapenv:Header>
   <soapenv:Body>
      <v2:printLabelRequest>
         <v2:integrationHeader>
            <v1:dateTime>$creationDate</v1:dateTime>
            <v1:version>2</v1:version>
            <v1:identification>
               <v1:applicationId>$applicationId</v1:applicationId>
               <v1:transactionId>$transactionId</v1:transactionId>
            </v1:identification>
         </v2:integrationHeader>
         <v2:shipmentNumber>$shipment_number</v2:shipmentNumber>
      </v2:printLabelRequest>
   </soapenv:Body>
</soapenv:Envelope>",
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
	

echo $response;





die();
echo $movies->movie->{'great-lines'}->line;

$xml=simplexml_load_string($response) or die("Error: Cannot create object");
echo   $xml; 
 


function clean($string) {
	$string = trim($string); 
   	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	$string = str_replace('-', ' ', $string);
   return $string;  // Removes special chars.
}

?>