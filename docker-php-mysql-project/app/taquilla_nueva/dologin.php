<?php

include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

$status = isset($_POST['status'])? $_POST['status'] : 0;

if($status > 0){ 
	$username = isset($_POST['username'])? $_POST['username'] : ' ';
	$passwd = isset($_POST['passwd'])? $_POST['passwd'] : ' ';
	
	$Query1 = "SELECT username, nombre_completo FROM tbl_usuario WHERE username = '$username' AND passwd = '$passwd'"; 
	$check = mysql_query($Query1) or die("Error en Query1: $Query1. mySQL devolvio " . mysql_error() . '.'); 
	if ($Results = mysql_fetch_object($check)) 
	{ 
		session_start();
		$_SESSION['login'] = 1;
		$_SESSION['username'] = $Results->username;
		$_SESSION['nombre'] = $Results->nombre_completo;
		$_SESSION['id'] = session_id();
		
		$qry = "DELETE temp_operacion_butaca FROM temp_operacion_butaca, temp_operacion ";
		$qry .= "WHERE temp_operacion_butaca.id_operacion = temp_operacion.id_operacion AND temp_operacion.usuario_registro = '".$_SESSION['username']."'";
		$result = mysql_query($qry);
		
		$qry = "DELETE FROM temp_operacion WHERE usuario_registro = '".$_SESSION['username']."'";
		$result = mysql_query($qry);
		
		//Borro la conexión vieja (si la hay) e inserto la nueva
		$qry = "DELETE FROM tbl_conexion WHERE username = '".$_SESSION['username']."' AND modulo = 2";
		$result = mysql_query($qry);
		
		$qry = "INSERT INTO tbl_conexion (id_session, username, modulo, fecha_hora_conexion) VALUES ('".$_SESSION['id']."','".$_SESSION['username']."', 2, now())";
		$result = mysql_query($qry);
		
		//session_register("login"); 
		//session_start(); 
		setcookie('registrado', $username, time() +8400); 
		// Aqui debes poner la pagina donde va si el login es correcto. 
		header("location: index.php"); 
	} else 	{ 
		header("location: login.php?login=no");
		//echo("Login erroneo, por favor intente de nuevo.<a href=\"login.php\">Volver</a>"); 
	} 
} 
?>