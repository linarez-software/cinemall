<?php
/* peliculabuscar.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."pelicula";

$nombre_espanol = isset($_POST['nombre_espanol']) ? trim(strtoupper($_POST['nombre_espanol'])) : '';
$nombre_corto = isset($_POST['nombre_corto']) ? trim(strtoupper($_POST['nombre_corto'])) : '';
$nombre_ingles = isset($_POST['nombre_ingles']) ? trim(strtoupper($_POST['nombre_ingles'])) : '';
$id_distribuidor = isset($_POST['id_distribuidor']) ? trim(strtoupper($_POST['id_distribuidor'])) : '';
$duracion = isset($_POST['duracion']) ? trim(strtoupper($_POST['duracion'])) : '';
$id_censura = isset($_POST['id_censura']) ? trim(strtoupper($_POST['id_censura'])) : '';
$pagina_web = isset($_POST['pagina_web']) ? trim(strtoupper($_POST['pagina_web'])) : '';
$id_genero = isset($_POST['id_genero']) ? trim(strtoupper($_POST['id_genero'])) : '';
$sinopsis = isset($_POST['sinopsis']) ? trim(strtoupper($_POST['sinopsis'])) : '';
$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
$nombre_buscar = isset($_GET['nombre_buscar']) ? $_GET['nombre_buscar'] : '';
$activo = ($activo == 'on') ? 1 : 0;

$pag = isset($_GET['pag']) ? $_GET['pag'] : 1;
$cant_registros = 25;

$where = 0;
$qry = "SELECT id_pelicula, nombre_espanol, nombre_corto, nombre_ingles, id_distribuidor, duracion, 
		id_censura, pagina_web, id_genero, sinopsis, activo FROM tbl_pelicula ";

if(strlen($nombre_buscar) > 0):
	$qry .= "WHERE nombre_espanol like '%$nombre_buscar%' ";
	$where = 1;
endif;
if(strlen($nombre_espanol) > 0):
	$qry .= "WHERE nombrel like '%$nombre_espanol%' ";
	$where = 1;
endif;
if(strlen($nombre_corto) > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "nombre_corto like '%$nombre_corto%' ";
	$where = 1;
endif;
if($nombre_ingles > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "nombre_ingles like '%$nombre_ingles%' ";
	$where = 1;
endif;
if($id_distribuidor > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "id_distribuidor = id_distribuidor ";
	$where = 1;
endif;
if($duracion > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "duracion = $duracion ";
	$where = 1;
endif;
if($id_censura > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "censura = $censura ";
	$where = 1;
endif;
if(strlen($pagina_web) > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "pagina_web like '%$pagina_web%' ";
	$where = 1;
endif;
if($id_genero > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "id_genero = $id_genero ";
	$where = 1;
endif;
if($activo > 0):
	$qry .= ($where == 0) ? 'WHERE ' : 'AND ';
	$qry .= "activo = $activo ";
endif;
$qry .= " ORDER BY id_pelicula DESC ";

$result = mysql_query($qry, $cnn) or die("Error 2005: ".mysql_error());
$registros = mysql_num_rows($result);

$inicio = ($pag==1) ? 0 : ($pag - 1) * $cant_registros; 
$qry .= "LIMIT $inicio, $cant_registros ";
$result = mysql_query($qry, $cnn) or die("Error 2005: ".mysql_error());
$total_paginas = ceil($registros / $cant_registros); 
?>

<table width="100%" cellpadding="1" cellspacing="1">
<form name="buscar_pelicula" action="" method="get">
<tr>
	<td colspan="5" height="35"><table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80" class="css-label-metadata">Película:</td>
            <td width="180" class="css-label-metadata"><input type="text" class="css-campo-metadata" name="nombre_buscar" value="<?php echo $nombre_buscar;?>" size="40"></td>
            <td width="200" class="css-label-metadata" align="left"><input type="submit" class="css-boton-metadata" name="pelicula_buscar" value="Buscar"></td>
            <td >
            <input type="hidden" name="modulo" value="pelicula" />
            <input type="hidden" name="sub" value="buscar" />
            <img src="images/tr.gif" /></td>
        </tr>
    </table></td>
</tr>
</form>
<tr>
	<th width="10%" class="css-titulo-metadata">Código</th>
	<th width="35%" class="css-titulo-metadata">Nombre en Español</th>
	<th width="35%" class="css-titulo-metadata">Nombre en Ingles</th>
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
	$table .= "<a href='".$modulo."&idp=$campo->id_pelicula'>"."\n";
	$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ." >"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_pelicula' class='css-datos-consulta'>$campo->id_pelicula</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_pelicula' class='css-datos-consulta'>$campo->nombre_espanol</a></td>"."\n";
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_pelicula' class='css-datos-consulta'>$campo->nombre_ingles</a></td>"."\n";
	$x_activo = $campo->activo == 0 ? 'No Activo' : 'Activo';
	$table .= "<td align='center'><a href='".$modulo."&idp=$campo->id_pelicula' class='css-datos-consulta'>$x_activo</a></td>"."\n";
	$table .= "<td><img src='images/tr.gif'></td>"."\n";
	$table .= "</tr>"."\n";
	$table .= "</a>"."\n";
}
echo $table;
?>
<tr>
	<td colspan="5" height="35" align="center" class="css-datos-consulta">
		<?php
		for ($i=1; $i<=$total_paginas; $i++){ 
			if ($pag == $i) { 
				echo "<b>".$pag."</b> | "; 
			} else { 
				echo "<a href='$modulo&sub=buscar&pag=$i' class='css-datos-consulta'>$i</a> | "; 
			} 
		}
		?>
    </td>
</tr>
</table>
