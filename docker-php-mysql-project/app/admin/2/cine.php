<?php

include_once "../../include/bd.inc.php";

$modulo = MANTENIMIENTO."cine";

$idc = isset($_GET['idc']) ? $_GET['idc'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

if($idc > 0):
	$qry = "SELECT id_cine, nombre, cine_ip, direccion, salas, activo FROM tbl_cine ";
	$qry .= "WHERE id_cine = $idc ";
	$result = mysql_query($qry, $cnn) or die("Error 4001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$nombre = $campo->nombre;
		$cine_ip = $campo->cine_ip;
		$direccion = $campo->direccion;
		$salas = $campo->salas;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$id_cine = $campo->id_cine;
		$accion = MODIFICAR;
	endif;
else:
	$id_cine = isset($_POST['id_cine']) ? $_POST['id_cine'] : '';
	$nombre = isset($_POST['nombre']) ? trim(strtoupper($_POST['nombre'])) : '';
	$cine_ip = isset($_POST['cine_ip']) ? trim(strtoupper($_POST['cine_ip'])) : '';
	$direccion = isset($_POST['direccion']) ? trim(strtoupper($_POST['direccion'])) : '';
	$salas = isset($_POST['salas']) ? $_POST['salas'] : '';
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;	

if($accion == AGREGAR && $status == 1):
	$qry = "SELECT id_cine, nombre, cine_ip, direccion, salas, activo FROM tbl_cine ";
	$qry .= "WHERE id_empresa = 0 ";
	if(strlen($nombre) > 0):
		$qry .= "AND nombre = '$nombre' ";
	endif;
	if(strlen($cine_ip) > 0):
		$qry .= "AND cine_ip = '$cine_ip' ";
	endif;
	if(strlen($direccion) > 0):
		$qry .= "AND direccion = '$direccion' ";
	endif;
	if($salas > 0):
		$qry .= "AND salas = $salas ";
	endif;
	//echo $qry ;
	$result = @mysql_query($qry, $cnn) or die("Error 4002: ".mysql_error());
	if($result && mysql_num_rows($result) > 0 && $accion == AGREGAR):
		echo "<script>alert('Atención: Es posible que ya haya un Cine cargado con lo mismos datos, por favor verifique.');</script>";
	else:
		$activo = ($activo == 'on') ? 1 : 0;
		$qry = "INSERT INTO tbl_cine (nombre, cine_ip, direccion, salas, activo) VALUES (";
		$qry .= "'$nombre', '$cine_ip', '$direccion', $salas, $activo)";
		$result = @mysql_query($qry, $cnn) or die("Error 4003: ".mysql_error());
		if($result):
			$mensaje = "Registro Agregado con Exito";
			unset($id_cine, $nombre, $cine_ip, $salas, $activo, $status, $direccion);
			$accion = AGREGAR;
		endif;
	endif;	
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_cine SET nombre = '$nombre', cine_ip = '$cine_ip', direccion = '$direccion', salas = $salas, activo = $activo ";
	$qry .= "WHERE id_cine = $id_cine";
	$result = @mysql_query($qry, $cnn) or die("Error 4004: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_cine, $nombre, $cine_ip, $salas, $activo, $status, $direccion);
		$accion = AGREGAR;
	endif;
endif;

?>
<html>
<head>
<title>Cine</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">

function validar(formulario){

	var campo1 = formulario.nombre.value;
	var campo2 = formulario.cine_ip.value;
	var campo3 = formulario.direccion.value;
	var campo4 = formulario.salas.value;
		
	if(campo1.length ==0){
		alert('El nombre del Cine no puede ser vacio.');
		return false;
	}
	if(campo2.length ==0){
		alert('El Alias del Cine no puede ser vacio.');
		return false;
	}
	if(campo3.length ==0){
		alert('La Direccion del Cine no puede ser vacio.');
		return false;
	}
 	if(campo4.length ==0){
		alert('Las Salas del Cine no puede ser vacio.');
		return false;
	} else {
		return true;	
	}
}

function validar2(){
	alert('Paso');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Cine</td>
</tr>
<form name="cine" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Código:</td>
				<td width="81%"><input name="id_cine" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_cine) ? $id_cine : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;Código del Cine (Campo Automatico)</span>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre:</td>
				<td width="81%"><input name="nombre" type="text" class="css-campo-metadata" size="40" maxlength="30" value="<?php echo isset($nombre) ? $nombre : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre del Cine (Ej.: Cines Acarigua)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Alias:</td>
				<td width="81%"><input name="cine_ip" type="text" class="css-campo-metadata" size="20" maxlength="20" value="<?php echo isset($cine_ip) ? $cine_ip : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Alias del Cine  (Ej.: Cineaca)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Direccion:</td>
				<td width="81%"><textarea name="direccion" cols="45" rows="4" class="css-campo-metadata"><?php echo isset($direccion) ? $direccion : '';?></textarea></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Salas:</td>
				<td width="81%"><input name="salas" type="text" class="css-campo-metadata" size="5" maxlength="2" value="<?php echo isset($salas) ? $salas : '';?>" onKeyPress="onNumero()">
				<span class="css-info-metadata">&nbsp;&nbsp;Cántidad de Salas</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Activo:</td>
				<td width="81%"><input name="activo" type="checkbox" class="css-campo-metadata" <?php echo isset($activo) ? $activo : '';?>></td>
			</tr>
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>
</tr>
</form>
</table>
