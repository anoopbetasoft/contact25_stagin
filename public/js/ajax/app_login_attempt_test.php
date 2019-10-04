<?php 
include("../../include/config.php");
include_once ("../../include/class.encrypt.php");
$encrypt = new encryption_class();
/*
function decode_code($code)
{
    return preg_replace_callback('@\\\(x)?([0-9a-f]{2,3})@',
        function ($m) {
            if ($m[1]) {
                $hex = substr($m[2], 0, 2);
                $unhex = chr(hexdec($hex));
                if (strlen($m[2]) > 2) {
                    $unhex .= substr($m[2], 2);
                }
                return $unhex;
            } else {
                return chr(octdec($m[2]));
            }
        }, $code);
}*/

	$sql = 'SELECT * FROM users WHERE users.u_id="'.$_POST['c25_u_id'].'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			
			$iv				=$row['u_pass_iv'];
			$tag			=$row['u_pass_tag'];
			$iv_local		=$row['u_pass_iv_local'];
			$tag_local		=$row['u_pass_tag_local'];
			$enc_string		=$row['u_pass_enc'];
			
			// if it's stored online, store iv / tags as a session - if not (and it's a phonegap app), save without encryption on local storage //
			if (isset($_SESSION)){
				$ISIT = 'yes';
				$enc_string		= str_replace(' ', '+', $_POST['local']);
				$dec			= $encrypt->decrypt($enc_string, 'aes-256-gcm', $_SESSION['c25_iv'], $_SESSION['c25_tag']); 

			}else{
				$dec			= str_replace(' ', '', $_POST['local']);
				$ISIT = 'no';
			}
			
			$myObj->c25_local 	= "marker".$dec;
			$myObj->c25_local 	= $_SESSION['c25_id'];
			$myJSON 			= html_entity_decode(json_encode($myObj));
			echo $myJSON;
			
		}while($row = mysqli_fetch_assoc($query));
	}
die();
/* login*/


	$sql = 'SELECT * FROM users WHERE users.u_email = "'.$_POST['username'].'" AND users.u_pass = "'.$_POST['password'].'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			
			$iv				=$row['u_pass_iv'];
			$tag			=$row['u_pass_tag'];
			$iv_local		=$row['u_pass_iv_local'];
			$tag_local		=$row['u_pass_tag_local'];
			$u_pass_enc		=$row['u_pass_enc'];
			include("../../include/config.php");
include_once ("../../include/class.encrypt.php");
$encrypt = new encryption_class();
$dec= $encrypt->decrypt($u_pass_enc, 'aes-256-gcm', $iv,$tag);
$variables = explode("#++--#contact25#--++#",$dec);
$myObj->c25_local 	= $dec;			
$myJSON 			= json_encode($myObj);
echo $myJSON;
			
die();	
			
			if (strlen($iv_local)==''){
				// set up encryption login details, iv and tag to unencrypt the basic login details, and an extra iv and tag for future local storage encryption //
				$plaintext = $_POST['username'].'#++--#contact25#--++#'.$_POST['password'].'#++--#contact25#--++#'.$row['u_id'];
				include_once ("../../include/class.encrypt.php");
				$encrypt = new encryption_class();
				$enc= $encrypt->encrypt($plaintext);
				$tag_local	= $enc[5];
				$iv_local	= $enc[4];
				$enc= $encrypt->encrypt($plaintext);
				$tag		= $enc[5];
				$iv			= $enc[4];
				$u_pass_enc	= $enc[0];
				$sql = 'UPDATE users SET u_pass_enc = "'.$enc[0].'", u_pass_cipher = "aes-256-gcm", u_pass_iv = "'.$enc[4].'", u_pass_tag = "'.$enc[5].'", u_pass_iv_local = "'.$iv_local.'", u_pass_tag_local = "'.$tag_local.'" WHERE u_id = "'.$row['u_id'].'" ';
				mysqli_query($db, $sql);
					
			}
				$myObj->c25_local 	= $u_pass_enc;
				$myObj->c25_iv 		= utf8_encode($iv_local);
				$myObj->c25_tag 	= utf8_encode($tag_local);
				//echo var_dump(urldecode($iv_local));
			
				//$myObj->age 		= $tag_local; //Setting the page Content-type
				$myJSON 			= html_entity_decode(json_encode($myObj));

				echo $myJSON;
			//echo var_dump(json_encode($u_pass_enc, $iv_local, $tag_local));
			
			die();
			$dec= $encrypt->decrypt($row['u_pass_enc'], $row['u_pass_cipher'], $row['u_pass_iv'],$row['u_pass_tag']);
			
			$variables = explode("#++--#contact25#--++#",$dec);
			
			if ($_POST['password'] == $variables[1]){
				// success
				echo '#'.var_dump($variables);
			}else{
				echo "fail";
			}
			
		
			
			
			/*
			$cipher = "aes-128-gcm";
			if (in_array($cipher, openssl_get_cipher_methods()))
			{
				$ivlen = openssl_cipher_iv_length($cipher);
				$iv = openssl_random_pseudo_bytes($ivlen);
				$ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
				//store $cipher, $iv, and $tag for decryption later
				//echo $ciphertext;
				$original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
				
			}*/
		}while($row = mysqli_fetch_assoc($query));
	}else{
		$sql = 'SELECT u_id, u_name FROM users WHERE users.u_email = "'.$_POST['username'].'" LIMIT 1';
		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);

		if ($num_rows>0){
			do{
				echo  -1;
			}while($row = mysqli_fetch_assoc($query));
		}else{

			echo 0;
		}
	}




	
	
	
	

?>