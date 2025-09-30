<?php
/* pelicula_distribuidorbuscar.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$fecha_inicio = isset($_POST['fecha_inicio']) ? trim(strtoupper($_POST['fecha_inicio'])) : '';
$fecha_fin = isset($_POST['fecha_fin']) ? trim(strtoupper($_POST['fecha_fin'])) : '';
$id_pelicula = isset($_POST['id_pelicula']) ? trim(strtoupper($_POST['id_pelicula'])) : '';
$porcentaje = isset($_POST['porcentaje']) ? trim(strtoupper($_POST['porcentaje'])) : '';


$where = 0;
$qry = "SELECT id_pelicula_distribuidor, fecha_inicio, fecha_fin, id_pelicula, porcentaje FROM tbl_pelicula_distribuidor ";

if(strlen($fecha_inicio) > 0):
	$qry .= "WHERE nombrel like '%$fecha_inicio%' ";
	$where = 1;
endif;
if(strlen($fecha_fin) > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "fecha_fin like '%$fecha_fin%' ";
	$where = 1;
endif;
if($id_pelicula > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "id_pelicula = id_pelicula ";
	$where = 1;
endif;
if($porcentaje > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "porcentaje = $porcentaje ";
	$where = 1;
endif;
//echo $qry;
$result = mysql_query($qry, $cnn) or die("Error 2005: ".mysql_error());

?>
<html>
<head>
<title>Pelicula Distribuidor Buscar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
</head>

<body>
<table width="100%" cellpadding="1" cellspacing="1">
<tr>
	<th width="10%" class="css-titulo-metadata">Código</th>
	<th width="35%" class="css-titulo-metadata">Pelicula</th>
	<th width="35%" class="css-titulo-metadata">Fecha Inicio</th>
	<th width="35%" class="css-titulo-metadata">Fecha Fin</th>
	<th width="10%" class="css-titulo-metadata"><img src="images/tr.gif"></th>
</tr>
<?php
$table = '';
$linea = 0;
$bgcolor_select = "'#FFCC99'";
while($campo = mysql_fetch_object($result)){
	if($linea == 0):
		$bgcolor = "'#DDDDDD'";
		$linea = 1;
	else:
		$bgcolor = "'#CCCCCC'";
		$linea = 0;
	endif;
	$table .= "<a href='pelicula_distribuidor-modificar.php?idpd=$campo->id_pelicula_distribuidor'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	$table .= "<td align='center'><a href='pelicula_distribuidor.php?idpd=$campo->id_pelicula_distribuidor' class='css-datos-consulta'>$campo->id_pelicula_distribuidor</a></td>"."\n";
	$table .= "<td align='center'><a href='pelicula_distribuidor.php?idpd=$campo->id_pelicula_distribuidor' class='css-datos-consulta'>$campo->id_pelicula</a></td>"."\n";
	$table .= "<td align='center'><a href='pelicula_distribuidor.php?idpd=$campo->id_pelicula_distribuidor' class='css-datos-consulta'>$campo->fecha_inicio</a></td>"."\n";
	$table .= "<td align='center'><a href='pelicula_distribuidor.php?idpd=$campo->id_pelicula_distribuidor' class='css-datos-consulta'>$campo->fecha_fin</a></td>"."\n";
	$table .= "<td><img src='images/tr.gif'></td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
</table>
</body>
</html>
