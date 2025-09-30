<?php

include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

$fecha = date('Y-m-d');
$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$nume = isset($_GET['n']) ? $_GET['n'] : 0;
$tiempo_expiracion = expiracion_funcion();
//echo $fecha;

$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, f.id_sala, s.nombre as sala, s.numerada ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "INNER JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "INNER JOIN tbl_sala s ON s.id_sala = f.id_sala ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." ";
$qry .= "ORDER BY f.id_sala, p.nombre_corto ";
//echo $qry;
$programacion = mysql_query($qry);

?>
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
	<td height="22" width="180" class="css-titulo-lista" align="center">Peliculas</td>
	<td height="22" width="500" class="css-titulo-lista" align="center">Funciones</td>
    <td height="22" class="css-titulo-lista" align="center"><img src="images/tr.gif" /></td>
</tr>
<?php 
$table = "";
while($campo = mysql_fetch_object($programacion)):
	$id_pelicula = $campo->id_pelicula;
	$id_sala = $campo->id_sala;
	$nombre_sala = $campo->sala;
	$numerada = $campo->numerada;
	
	/*Busco la sala de esta película*/
	$qry = "SELECT p.id_programacion, s.nombre, numerada ";
	$qry .= "FROM tbl_programacion p INNER JOIN tbl_sala s ON p.id_sala = s.id_sala ";
	$qry .= "WHERE p.fecha_programacion = '$fecha' ";
	$qry .= "AND p.activo = 1 ";
	$qry .= "AND p.id_cine = ".CINE_TAQUILLA ." ";
	$qry .= "AND p.id_pelicula = $id_pelicula ";
	$qry .= "ORDER BY p.id_programacion ASC";
	//echo $qry;
	//$sala_result = mysql_query($qry);
	//if($campo_sala = mysql_fetch_object($sala_result)):
	//	$nombre_sala = $campo_sala->nombre;
	//	$numerada = $campo_sala->numerada;
	//endif;
	
	$table .= "<tr> \n"; 
	$table .= "<td colspan='3' height='50' width='100%'><table border='0' height='50' width='100%' cellpadding='1' cellspacing='1' align='left'>\n <tr>\n";
	$table .= "<td height='45' width='180' class='css-pelicula' align='center'>$campo->nombre_espanol-<span style='color:#0000FF'>$campo->nomenclatura</span><br><em><span style='color:#FF0000'>Sala $nombre_sala</span></em></td> \n";
	
	/*Busco las funciones para esta película*/
	$qry = "SELECT id_programacion, hora_inicio ";
	$qry .= "FROM tbl_programacion ";
	$qry .= "WHERE fecha_programacion = '$fecha' ";
	$qry .= "AND activo = 1 ";
	$qry .= "AND id_cine = ".CINE_TAQUILLA ." ";
	$qry .= "AND id_pelicula = $id_pelicula ";
	$qry .= "AND id_sala = $id_sala ";
	//$qry .= "AND hora_inicio = $id_pelicula ";
	$qry .= "ORDER BY hora_inicio ASC";
	//echo $qry;
	$funcion_result = mysql_query($qry);
	$bgcolor = "'#CCCCCC'";
	$bgcolorselect = "'#C8C4FF'";
	while($funcion = mysql_fetch_object($funcion_result)):
		$hora_limite = strtotime($funcion->hora_inicio) + ($tiempo_expiracion * 60);
		$disponible = disponibilidad_funcion($funcion->id_programacion);
		if($hora_limite >= time()):
			if($disponible >= 7):
				$table .= "<a href='index.php?idf=$funcion->id_programacion&n=$numerada'><td width='90' height='55' align='center' class='css-datos-funciones' valign='middle' ".rowSelect($bgcolor,'out')." ".rowSelect($bgcolorselect,'over')."><a href='index.php?idf=$funcion->id_programacion&n=$numerada' class='css-datos-funciones-2'>".formato_hora($funcion->hora_inicio).'<br>(';
				if($funcion->id_programacion == $idf):
					//$table .= disponibilidad_funcion($idf);
					$table .= $disponible;
				else:	
					$table .= "0";
				endif;
			$table .= ")</a></a></td> \n";
			endif;
		endif;
	endwhile;
	$table .= "<td bgcolor='#CCCCCC'><img src='images/tr.gif'></td>";
	mysql_free_result($funcion_result);
	

	$table .= "</tr> \n";
	$table .= "</tr>\n </table></td> \n";	
	
endwhile;
$table .= "<tr><td colspan='3' bgcolor='#CCCCCC'><img src='images/tr.gif'></td></tr>";
echo $table;
$fecha = date('Y-m-d');
$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$nume = isset($_GET['n']) ? $_GET['n'] : 0;
$tiempo_expiracion = expiracion_funcion();
//echo $fecha;

$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, f.id_sala, s.nombre as sala, s.numerada,fecha_programacion  ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "INNER JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "INNER JOIN tbl_sala s ON s.id_sala = f.id_sala INNER JOIN tbl_programacion_preventa ON f.id_programacion = tbl_programacion_preventa.id_programacion ";
$qry .= "WHERE   fecha_programacion>CURDATE() ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." ";
$qry .= "ORDER BY fecha_programacion,f.id_sala, p.nombre_corto ";
//echo $qry;
$programacion = mysql_query($qry);

?>


</tr>

<tr>
	<td height="22" width='180' class="css-titulo-lista" align="center">Preventa</td>
	<td height="22" width='520' class="css-titulo-lista" align="center"></td>
</tr>
<?php 
$table = "";
while($campo = mysql_fetch_object($programacion)):
	$id_pelicula = $campo->id_pelicula;
	$id_sala = $campo->id_sala;
	$nombre_sala = $campo->sala;
	$numerada = $campo->numerada;
	$fecha= $campo->fecha_programacion;
	/*Busco la sala de esta película*/
	$qry = "SELECT p.id_programacion, s.nombre, numerada ";
	$qry .= "FROM tbl_programacion p INNER JOIN tbl_sala s ON p.id_sala = s.id_sala ";
	$qry .= "WHERE p.fecha_programacion = '$fecha' ";
	$qry .= "AND p.activo = 1 ";
	$qry .= "AND p.id_cine = ".CINE_TAQUILLA ." ";
	$qry .= "AND p.id_pelicula = $id_pelicula  ";
	$qry .= "ORDER BY p.id_programacion ASC";
	//echo $qry;
	//$sala_result = mysql_query($qry);
	//if($campo_sala = mysql_fetch_object($sala_result)):
	//	$nombre_sala = $campo_sala->nombre;
	//	$numerada = $campo_sala->numerada;
	//endif;
	if ($dia=="Monday") $dia="Lunes";
	if ($dia=="Tuesday") $dia="Martes";
	if ($dia=="Wednesday") $dia="Miér.";
	if ($dia=="Thursday") $dia="Jueves";
	if ($dia=="Friday") $dia="Viernes";
	if ($dia=="Saturday") $dia="Sabado";
	if ($dia=="Sunday") $dia="Domingo";
	$dias = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
  
	$dia = $dias[(date('N', strtotime($campo->fecha_programacion))) - 1];
	$fec=explode("-", $campo->fecha_programacion);
	
	$table .= "<tr> \n"; 
	$table .= "<td colspan='3' height='50' width='100%'><table border='0' height='50' width='100%' cellpadding='1' cellspacing='1' align='left'>\n <tr>\n";
	$table .= "<td height='45' width='180' class='css-pelicula' align='center'>".$dia.", ".$fec[2]."/".$fec[1]."/".$fec[0]."<br>$campo->nombre_espanol-<span style='color:#0000FF'>$campo->nomenclatura</span><br><em><span style='color:#FF0000'>Sala $nombre_sala</span></em></td> \n";
	
	/*Busco las funciones para esta película*/
	$qry = "SELECT id_programacion, hora_inicio ";
	$qry .= "FROM tbl_programacion ";
	$qry .= "WHERE fecha_programacion = '$fecha' ";
	$qry .= "AND activo = 1 ";
	$qry .= "AND id_cine = ".CINE_TAQUILLA ." ";
	$qry .= "AND id_pelicula = $id_pelicula ";
	$qry .= "AND id_sala = $id_sala ";
	//$qry .= "AND hora_inicio = $id_pelicula ";
	$qry .= "ORDER BY hora_inicio ASC";
	//echo $qry;
	$funcion_result = mysql_query($qry);
	$bgcolor = "'#CCCCCC'";
	$bgcolorselect = "'#C8C4FF'";
	while($funcion = mysql_fetch_object($funcion_result)):
		$hora_limite = strtotime($funcion->hora_inicio) + ($tiempo_expiracion * 60);
		$disponible = disponibilidad_funcion($funcion->id_programacion);
		//if($hora_limite >= time()):
			if($disponible >= 7):
				$table .= "<a href='index.php?idf=$funcion->id_programacion&n=$numerada'><td width='90' height='55' align='center' class='css-datos-funciones' valign='middle' ".rowSelect($bgcolor,'out')." ".rowSelect($bgcolorselect,'over')."><a href='index.php?idf=$funcion->id_programacion&n=$numerada' class='css-datos-funciones-2'>".formato_hora($funcion->hora_inicio).'<br>(';
				if($funcion->id_programacion == $idf):
					//$table .= disponibilidad_funcion($idf);
					$table .= $disponible;
				else:	
					$table .= "0";
				endif;
			$table .= ")</a></a></td> \n";
			endif;
		//endif;
	endwhile;
	$table .= "<td bgcolor='#CCCCCC'><img src='images/tr.gif'></td>";
	mysql_free_result($funcion_result);
	

	$table .= "</tr> \n";
	$table .= "</tr>\n </table></td> \n";	
	
endwhile;
$table .= "<tr><td colspan='3' bgcolor='#CCCCCC'><img src='images/tr.gif'></td></tr>";
echo $table;

?>

</table>
