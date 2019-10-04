<?php 

session_start();
$_SESSION['nots'] = '';
$_SESSION['nots'] = $_POST['got_it'];
$_SESSION['needs'] = '';
$_SESSION['needs'] = $_POST['needs'];
?>