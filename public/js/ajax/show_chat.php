<?php 
include ("../../config.php");


$sql = "SELECT * FROM chat";
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);

	$sql = "
	INSERT INTO
		chat	
			(
				
				p_id_1,
				p_id_2,
				salutation,
				fname,
				lname,
				address,
				town,				
				postcode,
				country,
				tel,
				password,
				vat_num,
				removeREQ				
			)
			VALUES
			(
				
				'".$_REQUEST['email']."',
				'".$_REQUEST['company']."',
				'".$_REQUEST['salutation']."',
				'".$_REQUEST['fname']."',
				'".$_REQUEST['lname']."',
				'".$_REQUEST['address']."',
				'".$_REQUEST['town']."',
				'".$_REQUEST['postcode']."',
				'".$_REQUEST['country']."',
				'".$_REQUEST['tel']."',
				'".$_REQUEST['password']."',
				'".$_REQUEST['vat_num']."',
				'".$_REQUEST['removeREQ']."'
			)			
		";
mysql_query($sql); 
;

			$sql = "SELECT 
						* 
					FROM 
						chat";
					##echo $sql;
			
			$query 		= mysql_query($sql);
			$row		= mysql_fetch_assoc($query);
			$num_rows 	= mysql_num_rows($query);

$_SESSION['p_id'] = 9;

if ($num_rows>0){
	do{
		if ($row['speaker'] == $_SESSION['p_id']){
			echo '<em class="speach-right-title">You replied '.date('H:i', strtotime($row['timestamp'])).':</em>
			<p class="speach-right green-bubble">'.$row['text'].'</p>';
		}else{
			echo '<em class="speach-left-title">Other replied '.date('H:i', strtotime($row['timestamp'])).':</em>
			<p class="speach-left blue-bubble">'.$row['text'].'</p>';
		}
		echo '<div class="clear"></div>';
		
	}while($row = mysql_fetch_assoc($query));
}
?>