<?php
/* genero.php */

include_once "../include/bd.inc.php";

$modulo = MANTENIMIENTO."genero";

$idg = isset($_GET['idg']) ? $_GET['idg'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

if($idg > 0):
	$qry = "SELECT id_genero, nombre, activo FROM tbl_genero ";
	$qry .= "WHERE id_genero = $idg ";
	$result = @mysql_query($qry, $cnn) or die("Error 2001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$nombre = $campo->nombre;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$id_genero = $idg;
		$accion = MODIFICAR;
	endif;

else:
	$id_genero = isset($_POST['id_genero']) ? $_POST['id_genero'] : '0';
	$nombre = isset($_POST['nombre']) ? strtoupper($_POST['nombre']) : '';
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;

if($accion == AGREGAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "INSERT INTO tbl_genero (nombre, activo) VALUES (";
	$qry .= "'$nombre', $activo)";
	$result = @mysql_query($qry, $cnn) or die("Error 2007: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_genero, $nombre, $activo);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_genero SET nombre = '$nombre', activo = $activo ";
	$qry .= "WHERE id_genero = $id_genero";
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 2003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_genero, $nombre, $activo);
		$accion = AGREGAR;
	endif;
endif;


?>
<html>
<head>
<title>Genero</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre.value;
		
	if(campo1.length ==0){
		alert('El nombre del genero no puede ser vacio.');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Genero</td>
</tr>
<form name="genero" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Código</td>
				<td width="81%"><input name="id_genero" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_genero) ? $id_genero : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;Código del Genero (Campo Automatico)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Nombre:</td>
				<td width="81%"><input name="nombre" type="text" class="css-campo-metadata" size="40" maxlength="20" value="<?php echo isset($nombre) ? $nombre : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre del Genero (Ej.: Drama)</span></td>
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
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()"> -->
	</td>
</tr>
</form>
</table>
</body>
</html>

