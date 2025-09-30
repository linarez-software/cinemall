<?php
/* precio-buscar.php */
include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."precio";

$nombre = isset($_POST['nombre']) ? trim(strtoupper($_POST['nombre'])) : '';
$comentario = isset($_POST['comentario']) ? trim(strtoupper($_POST['comentario'])) : '';
$activo = isset($_GET['activo']) ? $_GET['activo'] : 1;


$where = 0;
$qry = "SELECT p.id_precio, p.nombre, p.costo, p.dia, p.comentario, p.foncine, p.activo, t.nombre_tipo, p.marcar,dosporuno FROM tbl_precio p LEFT JOIN tbc_tipo t ON p.id_tipo = t.id_tipo ";
if($activo >= 0):
	$qry .= "WHERE p.activo = $activo ";
endif;
$qry .= " ORDER BY p.id_precio DESC";
//echo $qry;
$result = mysql_query($qry, $cnn) or die("Error 9001: ".mysql_error());

?>
<html>
<head>
<title>Cine Plus - Precio Buscar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript">
function precio(){
	var opcion = confirm("Esta seguro de Actulizar los precios?");
	if (opcion == true) {
        document.buscar_precio.action="actualiza.php?act=0";
		document.buscar_precio.submit();
	}

}
</script>
<body>
<table width="100%" cellpadding="1" cellspacing="1">
<form name="buscar_precio" action="" method="get">
<tr>
	<td colspan="7" height="35"><table width="100%" cellpadding="0" cellspacing="0">
		<tr>
            <td width="240" class="css-label-metadata">
            <input type="radio" name="activo" value="1" <?php echo ($activo==1) ? "checked": "";?>> Solo Activos 
            <input type="radio" name="activo" value="0" <?php echo ($activo==0) ? "checked": "";?>> No Activos 
            <input type="radio" name="activo" value="-1" <?php echo ($activo==-1) ? "checked": "";?>> Todos 
            <td width="200" class="css-label-metadata" align="left"><input type="submit" class="css-boton-metadata" name="precio_buscar" value="Filtrar"></td>
            <td width="200" class="css-label-metadata" align="left"><input type="button" class="css-boton-metadata" name="acualizar" onclick="precio();" value="Actualizar Precios"></td>
            <td align="right" height="24">
            <a href="<?php echo $modulo;?>" class="link-nuevo">Nuevo Registro</a>
            <input type="hidden" name="modulo" value="precio" />
            <input type="hidden" name="sub" value="buscar" />
            <img src="images/tr.gif" /></td>
        </tr>
    </table></td>
</tr>
</form>
<?php if($_SESSION['NIVEL']==3) die();?>
<tr>
	<th width="10%" class="css-titulo-metadata">C&oacute;digo</th>
	<th width="30%" class="css-titulo-metadata">Nombre</th>
	<th width="10%" class="css-titulo-metadata">Costo</th>
	<th width="10%" class="css-titulo-metadata">Status</th>
    <th width="10%" class="css-titulo-metadata">Tipo</th>
    <th width="10%" class="css-titulo-metadata">Marcado</th>
	<th width="10%" class="css-titulo-metadata">2x1</th>
	<th width="30%" class="css-titulo-metadata"><img src="images/tr.gif"></th>
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
	$table .= "<a href='".$modulo."&idp=$campo->id_precio'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$campo->id_precio</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$campo->nombre</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>".number_format($campo->costo,2,',','.')."</a></td>"."\n";
	$x_activo = $campo->activo == 0 ? 'No Activo' : 'Activo';
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$x_activo</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$campo->nombre_tipo</a></td>"."\n";
	$marcado = $campo->marcar == 0 ? '' : 'Si';
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$marcado</a></td>"."\n";
	$marcado = $campo->dosporuno == 1 ? 'NO' : 'Si';
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_precio' class='css-datos-consulta'>$marcado</a></td>"."\n";
	$table .= "<td><img src='images/tr.gif'></td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
</table>
</body>
</html>
