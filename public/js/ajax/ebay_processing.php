<?php 
$album = 1;

if ($_POST['album']){
	$album = $_POST['album'];
	echo "test2";
}


include ("../../config.php");

$_SESSION['ebay_processing'] = 1;


/* Show any out of stock stickers*/

$sql = 'select * from spares where spares.s_qty = 0 and spares.s_u_id = 1 AND spares.s_album = 1';

$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	echo '<br><h4 style="color:green;">0 in stock ('.$num_rows.'):</h4>';
	do{
		echo $row['s_num'].'<br>';
	}while($row = mysql_fetch_assoc($query));
	echo '<br>';
}

/* Show any out of stock stickers*/

$sql = 'select * from spares where spares.s_qty = 1 and spares.s_u_id = 1 AND spares.s_album = 1';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	echo '<br><h4 style="color:green;">1 in stock ('.$num_rows.'):</h4>';
	do{
		echo $row['s_num'].'<br>';
	}while($row = mysql_fetch_assoc($query));
	echo '<br>';
}

/* Show any out of stock stickers*/

$sql = 'select * from spares where spares.s_qty = 2 and spares.s_u_id = 1 AND spares.s_album = 1';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	echo '<br><h4 style="color:green;">2 in stock ('.$num_rows.'):</h4>';
	do{
		echo $row['s_num'].'<br>';
	}while($row = mysql_fetch_assoc($query));
	echo '<br>';
}



?>