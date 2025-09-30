<?php

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
$qry = "SELECT id_configuracion, nombre_empresa, server_ip, logo_empresa, limite_funcion, rif, nit, direccion, telefono, municipal, fomprocine, alquiler,isv,tasa FROM tbl_configuracion ";
//$qry .= "WHERE id_pelicula = $idc ";
$result = @mysql_query($qry, $cnn) or die("Error 3001: ".mysql_error());
$tasa=0;
if($campo = mysql_fetch_object($result)):
	 $tasa = $campo->tasa; 

endif;


	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	$qry = "UPDATE tbl_precio SET 
			
			costo = (costodolar*$tasa) where costodolar>0
			 "; 
	
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	 $qry = "UPDATE pv_tbc_productos SET 
							
							precio = (dolar*$tasa) where dolar>0
							 "; 
					
					//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
 	$qry = "UPDATE pv_tbc_productos SET 
			
			preciovip = (dolarvip*$tasa) where dolarvip>0
			 "; 
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
 	
	echo"<script>";
	echo"alert('Precios Actualizados');";
	echo"location.href='mantenimiento.php?activo=1&modulo=precio&sub=buscar';";
	echo"</script>";
	
?>