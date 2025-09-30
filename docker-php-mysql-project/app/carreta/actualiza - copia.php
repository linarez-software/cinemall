<?php

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

$modulo = "configuracion.php";

$idc = isset($_GET['idc']) ? $_GET['idc'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";
//if($idc > 0):

$status = isset($_POST['status']) ? $_POST['status'] : '';
if($status == 1):
	$id_configuracion = isset($_POST['id_configuracion']) ? $_POST['id_configuracion'] : '';
	$nombre_empresa = isset($_POST['nombre_empresa']) ? trim(strtoupper($_POST['nombre_empresa'])) : '';
	$server_ip = isset($_POST['server_ip']) ? trim(strtoupper($_POST['server_ip'])) : '';
	$logo_empresa = isset($_POST['logo_empresa']) ? trim(strtoupper($_POST['logo_empresa'])) : '';
	$limite_funcion = isset($_POST['limite_funcion']) ? trim(strtoupper($_POST['limite_funcion'])) : '';
	$rif = isset($_POST['rif']) ? trim(strtoupper($_POST['rif'])) : '';
	$nit = isset($_POST['nit']) ? trim(strtoupper($_POST['nit'])) : '';
	$direccion = isset($_POST['direccion']) ? trim(strtoupper($_POST['direccion'])) : '';
	$telefono = isset($_POST['telefono']) ? trim(strtoupper($_POST['telefono'])) : '';
	$municipal = isset($_POST['municipal']) ? trim(strtoupper($_POST['municipal'])) : 0;
	$fomprocine = isset($_POST['fomprocine']) ? trim(strtoupper($_POST['fomprocine'])) : 0;
	$alquiler = isset($_POST['alquiler']) ? trim(strtoupper($_POST['alquiler'])) : 0;
	$isv = isset($_POST['isv']) ? trim(strtoupper($_POST['isv'])) : 12;
	$tasa = isset($_POST['tasa']) ? trim(strtoupper($_POST['tasa'])) : 0;
	
	//$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
	
endif;

if($accion == AGREGAR && $status == 1):
	$qry = "INSERT INTO tbl_configuracion (nombre_empresa, server_ip, logo_empresa, limite_funcion, rif, nit, direccion, telefono, fomprocine, municipal, alquiler,tasa) VALUES (";
	$qry .= "'$nombre_empresa', '$server_ip', '$logo_empresa', '$limite_funcion', '$rif', '$nit', '$direccion', '$telefono', $fomprocine, $muncipal, $alquiler, '$tasa')";
	$result = @mysql_query($qry, $cnn) or die("Error 3002: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_configuracion, $nombre_empresa, $server_ip, $logo_empresa, $limite_funcion, $rif, $nit, $direccion, $telefono, $alquiler);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$qry = "UPDATE tbl_configuracion SET 
			
			tasa = '$tasa'
			 "; 
	
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	$qry = "UPDATE pv_tbc_productos SET 
			
			precio = (dolar*$tasa) where dolar>0
			 "; 
	
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	$qry = "UPDATE pv_tbc_productos SET 
			
			preciovip = (dolarvip*$tasa) where dolarvip>0
			 "; 
	
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		$accion = AGREGAR;
	endif;
endif;

$qry = "SELECT id_configuracion, nombre_empresa, server_ip, logo_empresa, limite_funcion, rif, nit, direccion, telefono, municipal, fomprocine, alquiler,isv,tasa FROM tbl_configuracion ";
//$qry .= "WHERE id_pelicula = $idc ";
$result = @mysql_query($qry, $cnn) or die("Error 3001: ".mysql_error());

if($campo = mysql_fetch_object($result)):
	$id_configuracion = $campo->id_configuracion;
	$nombre_empresa = $campo->nombre_empresa;
	$server_ip = $campo->server_ip;
	$logo_empresa = $campo->logo_empresa;
	$limite_funcion = $campo->limite_funcion;
	$rif = $campo->rif;
	$nit = $campo->nit;
	$direccion = $campo->direccion;
	$telefono = $campo->telefono;
	$configuracion = $idc;
	$municipal =  $campo->municipal;
	$fomprocine =  $campo->fomprocine;
	$alquiler = $campo->alquiler; 
	$isv = $campo->isv; 
	$tasa = $campo->tasa; 
	$accion = MODIFICAR;
endif;
?>
<html>
<head>
<title>Configuracion</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script type="text/javascript" src="js/func.global.js"></script>
<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre_empresa.value;
	var campo2 = formulario.rif.value;
	var campo3 = formulario.nit.value;
	var campo4 = formulario.limite_funcion.value;
	var campo5 = formulario.logo_empresa.value;
	var campo6 = formulario.direccion.value;
	var campo7 = formulario.telefono.value;

	if(campo1.length ==0){
		alert('El Nombre de la Empresa no puede ser vacio.');
		return false;
	}
	if(campo2.length ==0){
		alert('El Rif de la Empresa no puede ser vacio.');
		return false;
	}
	if(campo3.length ==0){
		alert('El Nit de la Empresa no puede ser vacio.');
		return false;
	}
	if(campo4.length ==0){
		alert('El Tiempo Limite de Venta de Boletos no puede ser vacio.');
		return false;
	}
	
	if(campo6.length ==0){
		alert('La Direccion de la Empresa no puede ser vacio.');
		return false;
	}
	if(campo7.length ==0){
		alert('El Telefono de la Empresa no puede ser vacio.');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Actualizar Precios</td>
</tr>
<form name="configuracion" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre de la Empresa:</td>
				<td width="81%"><input name="nombre_empresa" type="text" class="css-campo-metadata" size="50" maxlength="45" value="<?php echo isset($nombre_empresa) ? $nombre_empresa : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Empresa (Ej.: Sysmo Sistemas, C.A)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Rif:</td>
				<td width="81%"><input name="rif" type="text" class="css-campo-metadata" size="15" maxlength="12" value="<?php echo isset($rif) ? $rif : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Rif de la Empresa (Ej.: J000000000 )</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Tasa:</td>
				<td width="81%"><input name="tasa" type="text" class="css-campo-metadata" size="10"  onKeyPress="return(SoloNumeroDec(event))"  maxlength="10" value="<?php echo isset($tasa) ? $tasa : 1;?>">
				<span class="css-info-metadata">&nbsp;&nbsp;</span></td>
			</tr>
			
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="id_configuracion" value="<?php echo $id_configuracion;?>">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Actualizar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()">&nbsp;&nbsp;&nbsp; -->
	</td>
</tr>
</form>
</table>
</body>
</html>
