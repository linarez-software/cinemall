<?php
session_start();

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

$status = isset($_POST['status'])? $_POST['status'] : 0;

if($status > 0){ 
	$username = isset($_POST['username'])? $_POST['username'] : ' ';
	$passwd = isset($_POST['passwd'])? $_POST['passwd'] : ' ';
	
	$Query1 = "SELECT username, nivel,id_usuario FROM tbl_usuario WHERE username = '$username' AND passwd = '$passwd' AND (nivel >0 AND nivel <= 3) AND activo = 1"; 
	$check = mysql_query($Query1) or die("Error en Query1: $Query1. mySQL devolvio " . mysql_error() . '.'); 
	$Results = mysql_num_rows($check); 
	if ($campo = mysql_fetch_object($check)) 
	{ 
		$login = "1"; 
		$_SESSION['LOGIN'] = 1; 
		$_SESSION['NIVEL'] = $campo->nivel;
		$_SESSION['USER'] = $campo->username;
		$_SESSION[login_usu] =$campo->id_usuario;
		//Busco el Cine Actual
		$qry = "SELECT nombre FROM tbl_cine WHERE activo = 1";
		$result = mysql_query($qry);
		if($campo_cine = mysql_fetch_object($result)):
			$_SESSION['CINE'] = $campo_cine->nombre;
		endif;
		//session_start(); 
		setcookie('registrado', $username, time() +8400); 
		// Aqui debes poner la pagina donde vaz si el login es correcto. 
		header("location: programacion.php"); 
	} else 	{ 
		header("location: login.php?login=no");
		//echo("Login erroneo, por favor intente de nuevo.<a href=\"login.php\">Volver</a>"); 
	} 
} 

?>