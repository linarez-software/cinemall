<?php

/* censura-buscar.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."censura";

$nombre = isset($_POST['nombre']) ? trim(strtoupper($_POST['nombre'])) : '';
$nomenclatura = isset($_POST['nomenclatura']) ? trim(strtoupper($_POST['nomenclatura'])) : '';
$activo = isset($_POST['activo']) ? $_POST['activo'] : '';

$where = 0;
$qry = "SELECT id_censura, nombre, nomenclatura, activo FROM tbl_censura ";

if(strlen($nombre) > 0):
	$qry .= "AND nombre like '%$nombre%' ";
endif;
if(strlen($nomenclatura) > 0):
	$qry .= "AND nomenclatura like '%$nomenclatura%' ";
endif;
if($activo > 0):
	$qry .= "AND activo = $activo ";
endif;
//echo $qry;
$result = mysql_query($qry, $cnn) or die("Error 2002: ".mysql_error());

?>
<html>
<head>
<title>Cine Plus - Genero Buscar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" cellpadding="1" cellspacing="1">
<tr>
	<td align="right" height="24" colspan="5"><a href="<?php echo $modulo;?>" class="link-nuevo">Nuevo Registro</a></td>
</tr>
<tr>
	<th width="10%" class="css-titulo-metadata">C&oacute;digo</th>
	<th width="30%" class="css-titulo-metadata">Nombre</th>
	<th width="10%" class="css-titulo-metadata">Nomenclatura</th>
	<th width="10%" class="css-titulo-metadata">Status</th>
	<th width="40%" class="css-titulo-metadata"><img src="images/tr.gif"></th>
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
	$table .= "<a href='".$modulo."&idc=$campo->id_censura'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idc=$campo->id_censura' class='css-datos-consulta'>$campo->id_censura</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idc=$campo->id_censura' class='css-datos-consulta'>$campo->nombre</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idc=$campo->id_censura' class='css-datos-consulta'>$campo->nomenclatura</a></td>"."\n";
	$x_activo = $campo->activo == 0 ? 'No Activo' : 'Activo';
	$table .= "<td align='center'><a href='".$modulo."&idc=$campo->id_censura' class='css-datos-consulta'>$x_activo</a></td>"."\n";
	$table .= "<td><img src='images/tr.gif'></td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
</table>
</body>
</html>
