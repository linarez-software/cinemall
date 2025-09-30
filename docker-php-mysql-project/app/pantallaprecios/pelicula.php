<?php
$qry = "SELECT DISTINCT f.id_pelicula ";
$qry .= "FROM tbl_programacion f INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "WHERE fecha_programacion = '$fecha' and foto<>'' ";
$qry .= "AND id_cine = ".CINE_TAQUILLA;
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
//$intervalo = intval($peliculas/2);
$intervalo = 1;

if(!isset($_SESSION["intermin2"])){
	 $_SESSION["intermin2"]=0;
	 }
else
	{
	$_SESSION["intermin2"]+=1;
		 if($_SESSION["intermin2"]>=$peliculas){
		 	$_SESSION["intermin2"]=0;
		 }
	 }
$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, p.sinopsis,foto ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." and foto<>'' ";
$qry .= "ORDER BY p.nombre_corto ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";
echo $qry;
$programacion = mysql_query($qry);
?>

<table id="tabla-pelicula" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
<?php 
$table = "";
$par = 0;
for($p=1; $p<=$intervalo; $p++):
	if($campo = mysql_fetch_object($programacion)):
		$id_pelicula = $campo->id_pelicula;
		if($par==0):
			$par++;
			$tr_fondo = "";
		else:
			$par=0;
			$tr_fondo = "par";
		endif;
		$table .= "<tr class='pelicula $par'> \n"; 
		//$table .= "<table border='0' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
		$table .= "<td height='20%' align='center' class='pelicula' colspan=7><img width='400' height='700' src='http://192.168.1.100:5080/admin/$campo->foto'></td> \n";
		$table .= "</tr> \n"; 
		$table .= "<tr class='pelicula $par'> \n"; 
		//$table .= "<table border='0' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
		$table .= "<td height='20%' align='center' class='pelicula borde-abajo borde-derecho' colspan=7>$campo->sinopsis</td> \n";
		$table .= "</tr> \n"; 
		
		$table .= "</tr> \n";
		$table .= "<tr class='pelicula $tr_fondo'> \n"; 
		//$table .= "<table border='0' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
		$table .= "<td height='10' align='center' class='censura2'>$campo->nomenclatura</td> \n";
		$table .= "<td align='left' class='pelicula borde-abajo borde-derecho' >".utf8_encode($campo->nombre_espanol)."</td> \n";
		
		/*Busco las funciones para esta película*/
		$qry = "SELECT id_programacion, hora_inicio ";
		$qry .= "FROM tbl_programacion ";
		$qry .= "WHERE fecha_programacion = '$fecha' ";
		$qry .= "AND activo = 1 ";
		$qry .= "AND id_cine = ".CINE_TAQUILLA ." ";
		$qry .= "AND id_pelicula = $id_pelicula ";
		$qry .= "AND TIME_TO_SEC(hora_inicio) + ".($tiempo_expiracion * 60). " >= TIME_TO_SEC(now()) ";
		$qry .= "ORDER BY hora_inicio ASC";
		//echo $qry . "<br>";
		$funcion_result = mysql_query($qry);
		for($i=1; $i<=5; $i++):
			if($funcion = mysql_fetch_object($funcion_result)):
				$hora_limite = strtotime($funcion->hora_inicio) + ($tiempo_expiracion * 60); 
				$color = ($hora_limite >= time()) ? "funcion-disponible" : "funcion-agotada";
				//$disponibilidad = disponibilidad_funcion($funcion->id_programacion);
				//if($disponibilidad < 7):
				//	$color = "'funcion-agotada'";
				//endif;
				$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>".formato_hora($funcion->hora_inicio)."</td> \n";
			else:
				$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>&nbsp;</td> \n";
			endif;
		endfor;
		
	
	endif;
endfor;
mysql_free_result($funcion_result);
echo $table;
?>
</table>