<?php 

include("../../include/config.php");

global $db;

$sql = "UPDATE
	users
SET
	users.u_currency = '".$_POST['selected_var']."'
WHERE
	users.u_id = '".$_SESSION['c25_id']."'";

mysqli_query ($db,$sql);

?>