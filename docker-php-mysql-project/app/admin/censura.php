<?php
/* censura.php */

include_once "../include/bd.inc.php";

$modulo = MANTENIMIENTO."censura";

$idc = isset($_GET['idc']) ? $_GET['idc'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

if($idc > 0):
	$qry = "SELECT id_censura, nombre, nomenclatura, activo FROM tbl_censura ";
	$qry .= "WHERE id_censura = $idc ";
	$result = @mysql_query($qry, $cnn) or die("Error 2001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$nombre = $campo->nombre;
		$nomenclatura = $campo->nomenclatura;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$id_censura = $idc;
		$accion = MODIFICAR;
	endif;

else:
	$id_censura = isset($_POST['id_censura']) ? $_POST['id_censura'] : '0';
	$nombre = isset($_POST['nombre']) ? strtoupper($_POST['nombre']) : '';
	$nomenclatura = isset($_POST['nomenclatura']) ? strtoupper($_POST['nomenclatura']) : '';
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;

if($accion == AGREGAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "INSERT INTO tbl_censura (nombre, nomenclatura, activo) VALUES (";
	$qry .= "'$nombre', '$nomenclatura', $activo)";
	$result = @mysql_query($qry, $cnn) or die("Error 2007: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_censura, $nombre, $nomenclatura, $activo);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_censura SET nombre = '$nombre', nomenclatura = '$nomenclatura', activo = $activo ";
	$qry .= "WHERE id_censura = $id_censura";
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 2003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_censura, $nombre, $nomenclatura, $activo);
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
	var campo2 = formulario.nomenclatura.value;
		
	if(campo1.length ==0){
		alert('El nombre de la Censura no puede ser vacio.');
		return false;
	}
 	if(campo2.length ==0){
		alert('La Nomenclatura de la Censura no puede ser vacio.');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Censura</td>
</tr>
<form name="censura" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td><table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
      <tr bgcolor="#DDDDDD">
        <td width="19%" class="css-label-metadata">Código</td>
        <td width="81%"><input name="id_censura" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_censura) ? $id_censura : '';?>" readonly>
            <span class="css-info-metadata">&nbsp;&nbsp;Código de Censura (Campo Automatico)</span></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td width="19%" class="css-label-metadata">Nombre:</td>
        <td width="81%"><input name="nombre" type="text" class="css-campo-metadata" size="40" maxlength="20" value="<?php echo isset($nombre) ? $nombre : '';?>">
            <span class="css-info-metadata">&nbsp;&nbsp;Nombre de Censura (Ej.: Clase A)</span></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td width="19%" class="css-label-metadata">Nomenclatura:</td>
        <td width="81%"><input name="nomenclatura" type="text" class="css-campo-metadata" size="10" maxlength="4" value="<?php echo isset($nomenclatura) ? $nomenclatura : '';?>">
            <span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Nomenclatura (Ej.: AA)</span></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td width="19%" class="css-label-metadata">Activo:</td>
        <td width="81%"><input name="activo" type="checkbox" class="css-campo-metadata" <?php echo isset($activo) ? $activo : '';?>></td>
      </tr>
    </table></td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()"> -->	</td>
</tr>
</form>
</table>
</body>
</html>

