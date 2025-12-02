<?php
session_start();


$_SESSION["FLAG"]="New";

if(!isset($_SESSION['logout_way']))$_SESSION['logout_way']='index.php';

$way=$_SESSION['logout_way'];
session_unset();  
session_destroy(); 
header("Location: ".$way);
?>
