<?php 
session_start();



$_SESSION['start_on'] = $_POST['start_on'];
echo $_SESSION['start_on'];
?>