<?php

include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

$fecha = date('Y-m-d');
$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$limite = isset($_SESSION['limite']) ? $_SESSION['limite'] : 0;
$tiempo_expiracion = expiracion_funcion();
//echo $fecha;

$qry = "SELECT DISTINCT id_pelicula ";
$qry .= "FROM tbl_programacion ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND id_cine = ".CINE_TAQUILLA;
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
$intervalo = intval($peliculas/2);

$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." ";
$qry .= "ORDER BY p.nombre_corto ";
if($limite == 0):
	$qry .= "LIMIT 0, $intervalo ";
	$_SESSION['limite'] = 1;
else:
	$qry .= "LIMIT $intervalo, $peliculas ";
	$_SESSION['limite'] = 0;
endif;
//echo $qry;
$programacion = mysql_query($qry);
?>

<table border="0" width="100%" height="100%" cellpadding="2" cellspacing="2" bordercolor="#000000">
<!--
<tr>
	<td height="22" width='27' class="css-titulo-lista" align="center"></td>
	<td height="22" width='35%' class="css-titulo-lista" align="center">Peliculas</td>
	<td height="22" width='65%' class="css-titulo-lista" align="center">Funciones</td>
</tr>-->

<?php 
$table = "";
while($campo = mysql_fetch_object($programacion)):
	$id_pelicula = $campo->id_pelicula;
	$table .= "<tr> \n"; 
	$table .= "<td colspan='3' height='50' width='100%'><table border='2' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
	$table .= "<td height='60' width='49%' align='left' class='pelicula'>$campo->nombre_espanol</td> \n";
	$table .= "<td height='60' width='27' align='center' class='censura'>$campo->nomenclatura</td> \n";
	
	/*Busco las funciones para esta película*/
	$qry = "SELECT id_programacion, hora_inicio ";
	$qry .= "FROM tbl_programacion ";
	$qry .= "WHERE fecha_programacion = '$fecha' ";
	$qry .= "AND activo = 1 ";
	$qry .= "AND id_cine = ".CINE_TAQUILLA ." ";
	$qry .= "AND id_pelicula = $id_pelicula ";
	//$qry .= "AND hora_inicio = $id_pelicula ";
	$qry .= "ORDER BY hora_inicio ASC";
	//echo $qry;
	$funcion_result = mysql_query($qry);
	while($funcion = mysql_fetch_object($funcion_result)):
		$hora_limite = strtotime($funcion->hora_inicio) + ($tiempo_expiracion * 60); 
		$color = ($hora_limite >= time()) ? "'funcion-disponible'" : "'funcion-agotada'";
		//$disponibilidad = disponibilidad_funcion($funcion->id_programacion);
		//if($disponibilidad < 7):
		//	$color = "'funcion-agotada'";
		//endif;
		$table .= "<td height='60' width='150' class=$color align='center' valign='middle'>".formato_hora($funcion->hora_inicio);
		$table .= "</td> \n";
	endwhile;
	$table .= "<td><img src='images/tr.gif'></td>";
	mysql_free_result($funcion_result);
	
	$table .= "</tr> \n";
	$table .= "</tr>\n </table></td> \n";	
	
endwhile;
echo $table;
?>
</table>