<?php

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";


$idpd = isset($_GET['idpd']) ? $_GET['idpd'] : '0';

$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";
if($idpd > 0):
	$qry = "SELECT id_pelicula_distribuidor, id_pelicula, fecha_inicio, fecha_fin, porcentaje FROM tbl_pelicula_distribuidor ";
	$qry .= "WHERE id_pelicula_distribuidor = $idpd ";
	$result = @mysql_query($qry, $cnn) or die("Error 3001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$id_pelicula_distribuidor = $campo->id_pelicula_distribuidor;
		$pelicula = $campo->id_pelicula;
		$fecha_inicio = $campo->fecha_inicio;
		$fecha_fin = $campo->fecha_fin;
		$porcentaje = $campo->porcentaje;
		$pelicula_distribuidor = $idpd;
		$accion = MODIFICAR;
	endif;

else:
	$id_pelicula_distribuidor = isset($_POST['id_pelicula_distribuidor']) ? $_POST['id_pelicula_distribuidor'] : '';
	$pelicula = isset($_POST['pelicula']) ? trim(strtoupper($_POST['pelicula'])) : '';
	$fecha_inicio = isset($_POST['fecha_inicio']) ? trim(strtoupper($_POST['fecha_inicio'])) : '';
	$fecha_fin = isset($_POST['fecha_fin']) ? trim(strtoupper($_POST['fecha_fin'])) : '';
	$porcentaje = isset($_POST['porcentaje']) ? trim(strtoupper($_POST['porcentaje'])) : '';
	//$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;

if($accion == AGREGAR && $status == 1):
	$qry = "INSERT INTO tbl_pelicula_distribuidor (id_pelicula, fecha_inicio, fecha_fin, porcentaje) VALUES (";
	$qry .= "'$pelicula', '$fecha_inicio', '$fecha_fin', '$porcentaje')";
	$result = @mysql_query($qry, $cnn) or die("Error 3002: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_pelicula_distribuidor, $fecha_inicio, $fecha_fin, $porcentaje);
		unset($pelicula);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$qry = "UPDATE tbl_pelicula_distribuidor SET 
			fecha_inicio = '$fecha_inicio', 
			fecha_fin = '$fecha_fin', 
			porcentaje = $porcentaje, 
			id_pelicula = $pelicula ";
	$qry .= "WHERE id_pelicula_distribuidor = $id_pelicula_distribuidor";
	$result = @mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_pelicula_distribuidor, $fecha_inicio, $fecha_fin, $porcentaje);
		unset($pelicula);
		$accion = AGREGAR;
	endif;
endif;

?>
<html>
<head>
<title>Pelicula - Distribuidor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" type="text/javascript">
function Buscar(){
	document.pelicula_distribuidor.action = 'pelicula_distribuidor-buscar.php';
	document.pelicula_distribuidor.submit();
}
</script>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0">
<?php
if (strlen($mensaje)>0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>$mensaje</td></tr>";
	echo $tabla;
endif;
?>
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Pelicula-Distribuidor</td>
</tr>
<form name="pelicula_distribuidor" method="post" action="../../Configuración local/Temp/pelicula_distribuidor.php">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">

			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Código:</td>
				<td width="81%"><input name="id_pelicula_distribuidor" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_pelicula_distribuidor) ? $id_pelicula_distribuidor : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;Código de la Pelicula - Distribuidor  (Campo Automatico)</span>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Pelicula:</td>
				<td width="81%"><?php echo combo_pelicula(1, (isset($pelicula) ? $pelicula : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Fecha Inicio:</td>
				<td width="81%"><input name="fecha_inicio" type="text" class="css-campo-metadata" size="40" value="<?php echo isset($fecha_inicio) ? $fecha_inicio : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Fecha Inicio (Ej.: ?)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Fecha Fin:</td>
				<td width="81%"><input name="fecha_fin" type="text" class="css-campo-metadata" size="40" value="<?php echo isset($fecha_fin) ? $fecha_fin : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Fecha Fin (Ej.: ?)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Porcentaje:</td>
				<td width="81%"><input name="porcentaje" type="text" class="css-campo-metadata" size="10" value="<?php echo isset($porcentaje) ? $porcentaje : '';?>"></td>
			</tr>
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="text" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()">&nbsp;&nbsp;&nbsp; -->
	</td>
</tr>
</form>
</table>
</body>
</html>
