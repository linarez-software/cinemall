<?php
/* usuariobuscar.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$modulo = CONFIGURACION."usuario";

$activo = isset($_GET['activo']) ? $_GET['activo'] : 1;

$where = 0;
$qry = "SELECT id_usuario, nombre_completo, username, passwd, nivel, activo FROM tbl_usuario ";
if($activo >= 0):
	$qry .= "WHERE activo = $activo ";
endif;
$qry .= "ORDER BY username DESC";

$result = mysql_query($qry, $cnn) or die("Error 2005: ".mysql_error());

?>
<html>
<head>
<title>Usuario Buscar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
</head>

<body>
<table width="100%" cellpadding="1" cellspacing="1">
<form name="buscar_usuario" action="" method="get">
<tr>
	<td colspan="5" height="35"><table width="100%" cellpadding="0" cellspacing="0">
		<tr>
            <td width="240" class="css-label-metadata">
            <input type="radio" name="activo" value="1" <?php echo ($activo==1) ? "checked": "";?>> Solo Activos 
            <input type="radio" name="activo" value="0" <?php echo ($activo==0) ? "checked": "";?>> No Activos 
            <input type="radio" name="activo" value="-1" <?php echo ($activo==-1) ? "checked": "";?>> Todos 
            <td width="200" class="css-label-metadata" align="left"><input type="submit" class="css-boton-metadata" name="usuario_buscar" value="Filtrar"></td>
            <td>
            <input type="hidden" name="modulo" value="usuario" />
            <input type="hidden" name="sub" value="buscar" />
            <img src="images/tr.gif" /></td>
        </tr>
    </table></td>
</tr>
</form>
<tr>
	<!--<th width="10%" class="css-titulo-metadata">Código</th>-->
    <th width="10%" class="css-titulo-metadata">Usuario</th>
	<th width="30%" class="css-titulo-metadata">Nombre completo</th>
	<th width="30%" class="css-titulo-metadata">Nivel</th>
	<th width="10%" class="css-titulo-metadata">Activo</th>
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
	$table .= "<a href='$modulo&idu=$campo->id_usuario'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	/*$table .= "<td align='center'><a href='$modulo&idu=$campo->id_usuario' class='css-datos-consulta'>$campo->id_usuario</a></td>"."\n";*/
	$table .= "<td align='center'><a href='$modulo&idu=$campo->id_usuario' class='css-datos-consulta'>$campo->username</a></td>"."\n";
	$table .= "<td align='center'><a href='$modulo&idu=$campo->id_usuario' class='css-datos-consulta'>$campo->nombre_completo</a></td>"."\n";
	$table .= "<td align='center'><a href='$modulo&idu=$campo->id_usuario' class='css-datos-consulta'>$campo->nivel</a></td>"."\n";
	$x_activo = $campo->activo == 0 ? 'No Activo' : 'Activo';
	$table .= "<td align='center'><a href='$modulo&idu=$campo->id_usuario' class='css-datos-consulta'>$x_activo</a></td>"."\n";
	$table .= "<td><img src='images/tr.gif'></td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
</table>
</body>
</html>
