<?php
$qry = "SELECT DISTINCT id_pelicula ";
$qry .= "FROM tbl_programacion ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND id_cine = ".CINE_TAQUILLA;
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
//$intervalo = intval($peliculas/5);
$intervalo = 5;
$_SESSION['limite'] = empty($_SESSION['limite']) ? 0 : $_SESSION['limite'];
$inicio = ($_SESSION['limite'] * $intervalo);
if($_SESSION['limite']*$intervalo > $peliculas):
	$intervalo=($_SESSION['limite']*$intervalo)-$peliculas;
endif;

$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." ";
$qry .= "ORDER BY p.nombre_corto ";

$qry .= "LIMIT $inicio, $intervalo ";
if(($_SESSION['limite']+1)*$intervalo >= $peliculas):
	$_SESSION['limite']=0;
else:
	$_SESSION['limite']++;
endif;
$programacion = mysql_query($qry);
?>

<table id="tabla-pelicula" width="100%" height="440" cellpadding="0" cellspacing="0" border="0">
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
		$table .= "<tr class='pelicula $tr_fondo'> \n"; 
		//$table .= "<table border='0' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
		$table .= "<td height='20%' align='center' class='censura2'>$campo->nomenclatura</td> \n";
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
				$disponibilidad = disponibilidad_funcion($funcion->id_programacion);
				if($disponibilidad > 7):
					$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>".formato_hora($funcion->hora_inicio)."</td> \n";
				else:
					$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>&nbsp;</td> \n";
				endif;
			else:
				$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>&nbsp;</td> \n";
			endif;
		endfor;
		$table .= "</tr> \n";
	else:
		if($par==0):
			$par++;
			$tr_fondo = "";
		else:
			$par=0;
			$tr_fondo = "par";
		endif;
		$table .= "<tr class='pelicula $tr_fondo'> \n"; 		
		$table .= "<td height='20%' align='center' class='censura2'>&nbsp;</td> \n";
		$table .= "<td align='left' class='borde-abajo'>&nbsp;</td> \n";
		for($i=1; $i<=5; $i++):
			$table .= "<td width='12%' class='borde-abajo $color' align='center' valign='middle'>&nbsp;</td> \n";
		endfor;
		$table .= "</tr> \n";

	endif;
endfor;
mysql_free_result($funcion_result);
echo $table;
?>
</table>