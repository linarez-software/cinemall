<?php

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$id_programacion = isset($_GET['idp']) ? $_GET['idp'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

if($id_programacion > 0):
	$qry = "SELECT p.id_programacion, p.id_cine, p.id_pelicula, p.id_sala, p.hora_inicio, p.hora_fin, p.fecha_programacion ";
	$qry .= "FROM tbl_programacion p ";
	$qry .= "WHERE p.id_programacion = $id_programacion";
	$result_prog = mysql_query($qry);
	if($campo_prog = mysql_fetch_object($result_prog)):
		$id_sala = $campo_prog->id_sala;
		$id_cine = $campo_prog->id_cine;
		$id_pelicula = $campo_prog->id_pelicula;
		$hora_inicio = $campo_prog->hora_inicio;
		$hora_fin = $campo_prog->hora_fin;
		$fecha = formato_fecha($campo_prog->fecha_programacion, 2);
		$hora_i = formato_hora($hora_inicio, 'h');
		$minu_i = formato_hora($hora_inicio, 'i');
		//$hora_f = formato_hora($hora_fin, 'h');
		//$minu_f = formato_hora($hora_fin, 'i');
		$programacion = $id_programacion;
		$activo = 1;
		$accion = MODIFICAR;
	endif;
else:
	$fecha = isset($_GET['fecha']) ? $_GET['fecha'] :  '';
	$id_sala = isset($_GET['ids']) ? $_GET['ids'] :  '';
	$id_cine = isset($_GET['idc']) ? $_GET['idc'] :  '';
	$id_pelicula = isset($_GET['pelicula']) ? $_GET['pelicula'] : '';
	$hora_i = isset($_GET['hora_i']) ? $_GET['hora_i'] : '';
	//$hora_f = isset($_GET['hora_f']) ? $_GET['hora_f'] : '';
	$minu_i = isset($_GET['minu_i']) ? $_GET['minu_i'] : '';
	//$minu_f = isset($_GET['minu_f']) ? $_GET['minu_f'] : '';
	$status = isset($_GET['status']) ? $_GET['status'] : '';
	$programacion = isset($_GET['programacion']) ? $_GET['programacion'] : '';
	$activo = isset($_GET['activo']) ? $_GET['activo'] : '1';
endif;



if($accion == AGREGAR && $status == 1):
	$hora_inicial = formato_hora($hora_i.':'.$minu_i.':00 pm', 'H:i');
	//$hora_final = formato_hora($hora_f.':'.$minu_f.':00 pm', 'H:i');
	$qry = "INSERT INTO tbl_programacion (id_cine, id_sala, id_pelicula, fecha_programacion, hora_inicio,  activo) VALUES (";
	$qry .= "$id_cine, $id_sala, $id_pelicula,'".formato_fecha($fecha)."', '$hora_inicial', $activo)";
	echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 11001: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		$accion = AGREGAR;
		echo "<script>window.close()</script>";
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$hora_inicial = formato_hora($hora_i.':'.$minu_i.':00 pm', 'H:i');
	//$hora_final = formato_hora($hora_f.':'.$minu_f.':00 pm', 'H:i');
	$qry = "UPDATE tbl_programacion SET 
			id_cine = $id_cine, 
			id_sala = $id_sala, 
			id_pelicula = $id_pelicula,
			fecha_programacion = '".formato_fecha($fecha)."', 
			hora_inicio = '$hora_inicial', 
			activo = $activo ";
	$qry .= "WHERE id_programacion = $programacion";
	echo $qry; 
	$result = mysql_query($qry, $cnn) or die("Error 11002: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		$accion = AGREGAR;
		echo "<script>window.close()</script>";
	endif;
endif;

?>
<html>
<head>
<script language="JavaScript"> 
function actualizaPadre(){ 
    window.opener.document.programacion.submit()
} 
</script> 


<title>Funciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body rightmargin="0" leftmargin="0" onUnload="javascript:actualizaPadre()">
<table align="center" width="500" cellpadding="0" cellspacing="0">
<?php
if (strlen($mensaje)>0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>$mensaje</td></tr>";
	echo $tabla;
endif;
?>
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Funciones</td>
</tr>
<form name="funcion" method="get" action="funcion.php">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Fecha:</td>
				<td width="81%"><input name="fecha" type="text" class="css-campo-metadata-id" size="30" value="<?=$fecha;?>" readonly></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cine:</td>
				<td width="81%"><input name="cine" type="text" class="css-campo-metadata-id" size="30" value="<?=buscar_cine($id_cine);?>" readonly>
				<input name="idc" type="hidden" value="<?=$id_cine;?>"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Sala:</td>
				<td width="81%"><input name="sala" type="text" class="css-campo-metadata-id" size="10"  value="<?=buscar_sala($id_sala);?>" readonly>
				<input name="ids" type="hidden" value="<?=$id_sala;?>"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Película:</td>
				<td width="81%"><?php echo combo_pelicula(1, (isset($id_pelicula) ? $id_pelicula : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Inicio:</td>
				<td width="81%"><?php echo combo_hora((isset($hora_i) ? $hora_i : -1), 'hora_i');?>&nbsp;<?php echo combo_minuto((isset($minu_i) ? $minu_i : -1),'minu_i');?></td>
			</tr>
			<!--
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Final:</td>
				<td width="81%">?php echo combo_hora((isset($hora_f) ? $hora_f : -1), 'hora_f');?>&nbsp;?php echo combo_minuto((isset($minu_f) ? $minu_f : -1),'minu_f');?></td>
			</tr>
			-->
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="activo" value="<?=$activo;?>">
		<input type="hidden" name="programacion" value="<?=$programacion;?>">
		<input type="hidden" name="accion" value="<?=$accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		<input type="hidden" value="Cancelar función" class="css-boton-metadata" onClick="javascript:Limpiar()">
		<!-- Boton cancelar funcion fue ocultado por presentar problemas de ejecucion.  Solucionar este problema lo antes posible<!-- -->
	</td>
</tr>
</form>
</table>
</body>
</html>


