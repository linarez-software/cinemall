<?php 
session_start();
include_once "../include/bd.inc.php"; 
if ($_POST["tipo"]== 'ente')
{
	$qry_led = "SELECT sum( t.cantidad_transaccion*  t.costo)total ";
	$qry_led .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
	$qry_led .= "INNER JOIN tbl_programacion pr ON t.id_programacion = pr.id_programacion ";
	$qry_led .= "INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula ";
	$qry_led .= "WHERE id_session = '" . $_SESSION['id']  ."'";
	$led_result = mysql_query($qry_led);
	$monto="0";
	while($campo = mysql_fetch_object($led_result)):
	    $monto=($campo->total==NULL) ? 0 : $campo->total;
	endwhile;
	echo $monto;
}else{
	$qry_led = " SELECT *  ";
	$qry_led .= " FROM pv_tbl_pagos ";
	$qry_led .= " WHERE codigo_banco = " . $_POST['banco']  ." and NUMERO_CONTROL='$_POST[ref]' and CODIGO_INSTRUMENTO_PAGO=4";
	$led_result = mysql_query($qry_led);
	
	$monto=0;
	while($campo = mysql_fetch_object($led_result)):
	    $monto=1;
	endwhile;
	$qry_led = " SELECT *  ";
	$qry_led .= " FROM  tbl_operacion  ";
	$qry_led .= " WHERE banco = " . $_POST['banco']  ." and ref='$_POST[ref]' and pm>0";
	$led_result = mysql_query($qry_led);
	
	while($campo = mysql_fetch_object($led_result)):
	    $monto=1;
	endwhile;
	echo $monto;

}
?>