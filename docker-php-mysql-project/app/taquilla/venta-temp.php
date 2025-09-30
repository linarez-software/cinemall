<?php
session_start();
include_once "../include/bd.inc.php";

$session = $_GET['session'];
$username = $_GET['username'];
$funcion = $_GET['funcion'];
$precio = $_GET['precio'];
$boletos = $_GET['boletos'];
$costo = $_GET['costo'];
$comision = $_GET['comision'];

$fecha = date('Y-m-d H:i:s',time());
// Busco el % distribuidor película
$porcentaje = 0;
$hoy = date('Y-m-d',time());
$qry = "SELECT 	CASE WHEN porcentaje IS NULL THEN 0 ELSE porcentaje END as porcentaje ";
$qry .= "FROM	tbl_pelicula_distribuidor d INNER JOIN  tbl_programacion p ON d.id_pelicula = p.id_pelicula ";
$qry .= "WHERE	p.id_programacion = $funcion ";
$qry .= "AND 	d.fecha_inicio <= '$hoy' ";
$qry .= "AND 	(d.fecha_fin >= '$hoy' OR d.fecha_fin = '0000-00-00') ";
$resultP = @mysql_query($qry);
if($campoP = mysql_fetch_object($resultP)):
	$porcentaje = $campoP->porcentaje;
endif; 

/* Busco la sala si es numerada */
$numerada = 0;
$qry_butaca = "SELECT fila, columna, s.id_sala, numerada FROM tbl_sala s ";
$qry_butaca .= "INNER JOIN tbl_programacion p ON s.id_sala = p.id_sala ";
$qry_butaca .= "WHERE id_programacion = $funcion ";
$rst_butaca = mysql_query($qry_butaca);
if($campo_b = mysql_fetch_object($rst_butaca)):
	$fila = $campo_b->fila;
	$columna = $campo_b->columna;
	$numerada = $campo_b->numerada;
endif;


$qry = "INSERT INTO temp_operacion (";
$qry .= "id_session, id_programacion, fecha_operacion, id_tipo_transaccion, cantidad_transaccion, ";
$qry .= "id_precio, costo, comision, total_transaccion, usuario_registro, status, porcentaje_distribuidor, numerada) Values (";
$qry .= "'$session', $funcion, '$fecha', 1, $boletos, $precio, $costo, $comision, ";
$qry .= ($boletos * $costo);
$qry .= ", '$username', 1, $porcentaje, $numerada)";
$result = mysql_query($qry);
//echo " BIEN? " . $result ;
//header("Location: index.php");

?>