<?php
/*
Proyecto:		Cine Plus
Fecha:			12/02/2006
Archivo:		bd.inc.php (Centralized)
Descripcion:	Conexión Server - Centralized Database Configuration
*/

$SERVER = '127.0.0.1:3406';
$USER = 'manager';
$PASSWORD = '4dm1n';
$DB = 'cine_plus';

$cnn = @mysql_connect($SERVER, $USER, $PASSWORD) or die('Error 1001: ' . mysql_error());

@mysql_select_db($DB, $cnn) or die('Error 1002: ' . mysql_error());


//Constante Global de Accion
define("AGREGAR", 1);
define("MODIFICAR", 2);

//Constante de Secciones
define("MANTENIMIENTO", "mantenimiento.php?modulo=");
define("PROGRAMACION", "programacion.php");
define("CONFIGURACION", "configuracion.php?modulo=");
define("REPORTES", "reportes.php?modulo=");
define("DESCUENTO","especial.php?modulo=");

//ZONA HORARIA
date_default_timezone_set('America/Caracas');
?>
