<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

$dropdown_menu = '<li> <a href="index.html">Dashboard</a> </li>
            <li> <a href="product-orders.html">My Orders</a> </li>
            
            <li> <a href="products.html">My Stuff</a> </li>';

echo json_encode(array($dropdown_menu)); 
session_start();



?>