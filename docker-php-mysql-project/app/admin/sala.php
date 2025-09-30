<?php
/* sala.php */

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."sala";

$ids = isset($_GET['ids']) ? $_GET['ids'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

if($ids > 0):
	$qry = "SELECT id_sala, nombre, id_cine, capacidad, numerada, fila, columna, id_tipo_sala, activo FROM tbl_sala ";
	$qry .= "WHERE id_sala = $ids ";
	$result = @mysql_query($qry, $cnn) or die("Error 8001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$nombre = $campo->nombre;
		$cine = $campo->id_cine;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$id_sala = $campo->id_sala;
		$capacidad = $campo->capacidad;
		$numerada = $campo->numerada == 1 ? 'checked' : '';
		$fila = $campo->fila;
		$columna = $campo->columna;
		$tipo_sala = $campo->id_tipo_sala;
		$accion = MODIFICAR;
	endif;

else:
	$id_sala = isset($_POST['id_sala']) ? $_POST['id_sala'] : '0';
	$nombre = isset($_POST['nombre']) ? strtoupper($_POST['nombre']) : '';
	$cine = isset($_POST['cine']) ? strtoupper($_POST['cine']) : '0';
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
	$numerada = isset($_POST['numerada']) ? $_POST['numerada'] : '';
	$fila = isset($_POST['fila']) ? $_POST['fila'] : 0;
	$columna = isset($_POST['columna']) ? $_POST['columna'] : 0;
	$tipo_sala = isset($_POST['tipo_sala']) ? $_POST['tipo_sala'] : 0;
endif;

if($accion == AGREGAR && $status == 1):
	$disponibles = salas_por_cine($cine);
	$activo = ($activo == 'on') ? 1 : 0;
	$numerada = ($numerada == 'on') ? 1 : 0;
	if($disponibles > 0):
		$qry = "";
		$qry = "INSERT INTO tbl_sala (nombre, id_cine, capacidad, activo, numerada, fila, columna, id_tipo_sala) VALUES (";
		$qry .= "'$nombre', $cine, $capacidad, $activo, $numerada, $fila, $columna, $tipo_sala)";
		$result = @mysql_query($qry, $cnn) or die("Error 8002: ".mysql_error());
		if($result):
			$mensaje = "Registro Agregado con Exito";
			unset($id_sala, $nombre, $cine, $capacidad, $activo, $numerada, $fila, $columna, $tipo_sala);
			$accion = AGREGAR;
		endif;
	else:
		echo "<script>alert('El cine seleccionado llego a su capacidad de Salas')</script>";
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$numerada = ($numerada == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_sala SET nombre = '$nombre', id_cine = $cine, activo = $activo, capacidad = $capacidad, ";
	$qry .= "numerada = $numerada, fila = $fila, columna = $columna, id_tipo_sala = $tipo_sala ";
	$qry .= "WHERE id_sala = $id_sala";
	$result = mysql_query($qry, $cnn) or die("Error 8003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_sala, $nombre, $cine, $capacidad, $activo, $numerada, $fila, $columna, $tipo_sala);
		$accion = AGREGAR;
	endif;
endif;

$disabled = empty($numerada) ? "disabled='true'" : "";

?>

<table width="100%" cellpadding="0" cellspacing="0">
<?php
if (strlen($mensaje)>0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>$mensaje</td></tr>";
	echo $tabla;
endif;
?>
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Salas</td>
</tr>
<form name="precio" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">C&oacute;digo</td>
				<td width="81%"><input name="id_sala" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_sala) ? $id_sala : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;C&oacute;digo de Sala (Campo Automatico)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Nombre:</td>
				<td width="81%"><input name="nombre" type="text" class="css-campo-metadata" size="40" maxlength="30" value="<?php echo isset($nombre) ? $nombre : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Sala (Ej.: Sala 1)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cine:</td>
				<td width="81%"><?php echo combo_cine(1, (isset($cine) ? $cine : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Capacidad:</td>
				<td width="81%"><input name="capacidad" type="text" class="css-campo-metadata" size="10" maxlength="3" value="<?php echo isset($capacidad) ? $capacidad : '';?>" onKeyPress="onNumero()"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Tipo Sala:</td>
				<td width="81%"><?php echo combo_tipo_sala ((isset($tipo_sala) ? $tipo_sala : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Numerada:</td>
				<td width="81%"><input name="numerada" type="checkbox" class="css-campo-metadata" <?php echo isset($numerada) ? $numerada : '';?> onClick="habilitarNumerada(this);"></td>
			</tr>
			<div id="div_numerada" style="display:none;">
				<tr bgcolor="#DDDDDD">
					<td width="19%" class="css-label-metadata" align="right">Nro. Filas:</td>
					<td width="81%"><input name="fila" id="fila" type="input" <?php echo $disabled;?> size="5" maxlength="3" class="css-campo-metadata" value="<?php echo isset($fila) ? $fila : '';?>" onkeypress="return onNumero(event)">
						<span class="css-info-metadata">&nbsp;&nbsp;Sera identificadas con Letra (Ej.: A, B, C...)</span>
					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td width="19%" class="css-label-metadata" align="right">Nro. Columnas:</td>
					<td width="81%"><input name="columna" id="columna" type="input" <?php echo $disabled;?> size="5" maxlength="3" class="css-campo-metadata" value="<?php echo isset($columna) ? $columna : '';?>" onkeypress="return onNumero(event)">
						<span class="css-info-metadata">&nbsp;&nbsp;Sera identificadas con N&uacute;mero (Ej.: 1, 2, 3...)</span>
					</td>
				</tr>
			</div>
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

<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre.value;
	var campo2 = formulario.capacidad.value;
		
	if(campo1.length ==0){
		alert('El Nombre de la Sala no puede ser vacio.');
		return false;
	}
 	if(campo2.length ==0){
		alert('La Capacidad de la Sala no puede ser vacio.');
		return false;
	} else {
		return true;	
	}
}
function habilitarNumerada(valor){
	var Chequeo = valor.value;
	if(Chequeo.length>0){
		document.getElementById("fila").disabled=false;
		document.getElementById("columna").disabled=false;
	} else {
		document.getElementById("fila").value="";
		document.getElementById("columna").value="";
		document.getElementById("fila").disabled=true;
		document.getElementById("columna").disabled=true;
	}
}
</script>
