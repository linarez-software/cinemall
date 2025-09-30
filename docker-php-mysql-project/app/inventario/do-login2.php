<?php
session_start();

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

$status = isset($_POST['status'])? $_POST['status'] : 0;

if($status > 0){ 
	$passwd = isset($_POST['passwd'])? $_POST['passwd'] : ' ';
	
	$Query1 = "SELECT * FROM tbl_configuracion WHERE especial = '".strtoupper($passwd)."' "; 
	$check = mysql_query($Query1) or die("Error en Query1: $Query1. mySQL devolvio " . mysql_error() . '.'); 
	$Results = mysql_num_rows($check); 
	if ($campo = mysql_fetch_object($check)) 
	{ 
		$login = "1"; 
		$_SESSION['LOGIN'] = 1; 
		$_SESSION['LOGIN2'] = 2; 

		header("location: especial.php");		
	
	} else 	{ 
		header("location: login2.php?login=no");
	} 
} 

?>