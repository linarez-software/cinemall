<?php

/* sala-buscar.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."sala";

$nombre = isset($_POST['nombre']) ? trim(strtoupper($_POST['nombre'])) : '';
$id_cine = isset($_POST['id_cine']) ? trim(strtoupper($_POST['id_cine'])) : '';
$activo = isset($_POST['activo']) ? $_POST['activo'] : '';

$where = 0;
$qry = "SELECT s.id_sala, s.nombre, c.nombre as cine, s.capacidad, s.activo, numerada, fila, columna FROM tbl_sala s ";
$qry .= "INNER JOIN tbl_cine c ON s.id_cine = c.id_cine ";
if(strlen($nombre) > 0):
	$qry .= "AND s.nombre like '%$nombre%' ";
endif;
if(strlen($id_cine) > 0):
	$qry .= "AND s.id_cine like '%$id_cine%' ";
endif;
if($activo > 0):
	$qry .= "AND s.activo = $activo ";
endif;
//echo $qry;
$result = mysql_query($qry, $cnn) or die("Error 8001: ".mysql_error());

?>
<form name='form1' method='post'>
<table width="100%" cellpadding="1" cellspacing="1">
<tr>
	<td align="right" height="24" colspan="7"><a href="<?php echo $modulo;?>" class="link-nuevo">Nuevo Registro</a></td>
</tr>
<tr>
	<th width="8%" class="css-titulo-metadata">C&oacute;digo</th>
	<th width="14%" class="css-titulo-metadata">Nombre</th>
	<th width="30%" class="css-titulo-metadata">Cine</th>
	<th width="8%" class="css-titulo-metadata">Capacidad</th>
	<th width="10%" class="css-titulo-metadata">Status</th>
	<th width="10%" class="css-titulo-metadata"><img src="images/tr.gif"></th>
	<th width="20%" class="css-titulo-metadata">Mostrar</th>
</tr>

<?php
$table = '';
$linea = 0;
$bgcolor_select = "'#FFCC99'";
if(isset($_GET["si"])) {
	$sql="insert into tbl_mostrar_sala values(".$_GET["si"].")";
	$result2 = mysql_query($sql, $cnn) or die("Error 8001: ".mysql_error());
}
if(isset($_GET["no"])) {
	$sql="delete from  tbl_mostrar_sala where sala=".$_GET["no"]."";
	$result2 = mysql_query($sql, $cnn) or die("Error 8001: ".mysql_error());
}
while($campo = mysql_fetch_object($result)){
	if($linea == 0):
		$bgcolor = "'#DDDDDD'";
		$linea = 1;
	else:
		$bgcolor = "'#CCCCCC'";
		$linea = 0;
	endif;
	$table .= "<a href='".$modulo."&ids=$campo->id_sala'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	$table .= "<td align='center'><a href='".$modulo."&ids=$campo->id_sala' class='css-datos-consulta'>$campo->id_sala</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&ids=$campo->id_sala' class='css-datos-consulta'>$campo->nombre</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&ids=$campo->id_sala' class='css-datos-consulta'>$campo->cine</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&ids=$campo->id_sala' class='css-datos-consulta'>$campo->capacidad</a></td>"."\n";
	$x_activo = $campo->activo == 0 ? 'No Activo' : 'Activo';
	$table .= "<td align='center'><a href='".$modulo."&ids=$campo->id_sala' class='css-datos-consulta'>$x_activo</a></td>"."\n";
	$alto = !empty($campo->fila) ? (($campo->fila + 3) * 63) : 0;
	$ancho = !empty($campo->columna) ? (($campo->columna+5) * 51)  : 0;
	$numerada = empty($campo->numerada) ? "<img src='images/tr.gif'>" : "<a rel='shadowbox;height=$alto;width=$ancho' href='sala-mapa.php?ids=$campo->id_sala'  class='css-datos-consulta'>Mapa Sala</a>";
	$table .= "<td align='center'>$numerada</td>"."\n";
	$sql="select * from tbl_mostrar_sala where sala= ".$campo->id_sala;
	$result2 = mysql_query($sql, $cnn) or die("Error 8001: ".mysql_error());
	$x=0;
	while($campo2 = mysql_fetch_object($result2)){
		$x=1;
	}
	if($x==0)	$table .= "<td align='center' onclick='agrega(".$campo->id_sala.")'>NO</td>"."\n";
	if($x==1)	$table .= "<td align='center' onclick='elimina(".$campo->id_sala.")'>SI</td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
</table>
<script type="text/javascript">
	function agrega(id)
	{
		document.form1.action="mantenimiento.php?modulo=sala&sub=buscar&si="+id;
		document.form1.submit();
	}
	function elimina(id)
	{
		document.form1.action="mantenimiento.php?modulo=sala&sub=buscar&no="+id;
		document.form1.submit();
	}
</script>
</form>