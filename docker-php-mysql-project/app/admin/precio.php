<?php
/* sala.php */

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$modulo = MANTENIMIENTO."precio";

$idp = isset($_GET['idp']) ? $_GET['idp'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";

	
if($idp > 0):
	$qry = "SELECT id_precio, nombre, costo, comision, dia, comentario, contenido, foncine, activo, hora_limite, id_tipo, id_tipo_sala, censura, marcar, marca, clave,costodolar,dosporuno FROM tbl_precio ";
	$qry .= "WHERE id_precio = $idp ";
	$result = @mysql_query($qry, $cnn) or die("Error 9001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$id_precio = $campo->id_precio;
		$nombre = $campo->nombre;
		$costo = $campo->costo;
		$comision = $campo->comision;
		$dia = $campo->dia;
		$comentario = $campo->comentario;
		$foncine = $campo->foncine;
		$hora_limite = $campo->hora_limite;
		$tipo = $campo->id_tipo;
		$tipo_sala = $campo->id_tipo_sala;
		$censura = $campo->censura;
		$contenido = $campo->contenido;
		$costodolar = $campo->costodolar;
		$dosporuno = $campo->dosporuno;
		
		if($hora_limite == '00:00:00'):
			$hora_i = "";
			$minu_i = "";
		else:
			$hora_i = formato_hora($hora_limite, 'h');
			$minu_i = formato_hora($hora_limite, 'i');
		endif;
		$marca = $campo->marca;
		$marcar = $campo->marcar == 1 ? 'checked' : '';
		$activo = $campo->activo == 1 ? 'checked' : '';
		$dosporuno = '';
		$clave = $campo->clave;
		$accion = MODIFICAR;
		
		//Busco las Censuras para el precio seleccionado
		$censuras = array();
		$qry = "SELECT id_censura FROM tbr_precio_censura WHERE id_precio = $id_precio";
		$result = @mysql_query($qry);
		while($campo = mysql_fetch_array($result)):
			$censuras[]=$campo['id_censura'];
		endwhile;
	endif;

else:
	$id_precio = isset($_POST['id_precio']) ? $_POST['id_precio'] : '0';
	$nombre = isset($_POST['nombre']) ? strtoupper($_POST['nombre']) : '';
	$costo = !empty($_POST['costo']) ? $_POST['costo'] : 0;
	$comision = !empty($_POST['comision']) ? $_POST['comision'] : 0;
	$dia = isset($_POST['dia']) ? $_POST['dia'] : '';
	$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
	$foncine = isset($_POST['foncine']) ? $_POST['foncine'] : '';
	$hora_i = isset($_POST['hora_i']) ? $_POST['hora_i'] : '';
	$minu_i = isset($_POST['minu_i']) ? $_POST['minu_i'] : '';
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$costodolar = isset($_POST['costodolar']) ? $_POST['costodolar'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
	$censura = isset($_POST['censura']) ? $_POST['censura'] : '';
	$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 0;
	$tipo_sala = isset($_POST['tipo_sala']) ? $_POST['tipo_sala'] : 0;
	$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';
	$censuras = !empty($_POST['cen']) ? $_POST['cen'] : array();
	$marcar = isset($_POST['marcar']) ? $_POST['marcar'] : '';
	$marca = isset($_POST['marca']) ? strtoupper($_POST['marca']) : '';
	$clave = isset($_POST['clave']) ? strtoupper($_POST['clave']) : '';
	$dosporuno = isset($_POST['dosporuno']) ? strtoupper($_POST['dosporuno']) : '';
	$dia = '';
	for($i=1; $i <= 7; $i++):
		if(isset($_POST["dia$i"])):
			$dia .= $_POST["dia$i"] == 'on' ? 1 : 0;
		else:
			$dia .= '0';
		endif;
	endfor;
endif;

if($accion == AGREGAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$dosporuno	 = 1;
	$marcar = ($marcar == 'on') ? 1 : 0;
	$hora_limite = formato_hora($hora_i.':'.$minu_i.':00 pm', 'H:i');
	$qry = "";
	$qry = "INSERT INTO tbl_precio (nombre, costo, comision, dia, comentario, contenido, foncine, hora_limite, censura, id_tipo, id_tipo_sala, marcar, marca, clave, activo,costodolar) VALUES (";
	$qry .= "'$nombre', $costo, $comision, '$dia', '$comentario', '$contenido', $foncine, '$hora_limite', '$censura', $tipo, $tipo_sala, $marcar, '$marca', '$clave', $activo,$costodolar,$dosporuno)";
	////echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 9002: ".mysql_error());
	$id_precio = mysql_insert_id();
	if($result):
		//Actualizo las Censuras Restringidas
		//Borro las relaciones Censura y Precio
		$delete_qry = "DELETE FROM tbr_precio_censura WHERE id_precio = $id_precio ";
		$borrar_censura = @mysql_query($delete_qry) or die("Error 9003: ".mysql_error());
		foreach($censuras as $id_censura):
			//inserto las censuras
			$insert_qry = "INSERT INTO tbr_precio_censura (id_precio, id_censura) VALUES ($id_precio, $id_censura)";
			$insert_censura = @mysql_query($insert_qry) or die("Error 9004: ".mysql_error());
		endforeach;
		$mensaje = "Registro Agregado con Exito";
		unset($id_precio, $nombre, $costo, $comision, $dia, $comentario, $foncine, $activo, $hora_i, $minu_i, $censura, $tipo, $tipo_sala, $marca, $marcar, $clave);
		$dia = '0000000';
		$censuras = array();
		$accion = AGREGAR;
		header("Location:".$modulo."&sub=buscar");
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$dosporuno	 = ($dosporuno	 == 'ON') ? 2 : 1;
	$marcar = ($marcar == 'on') ? 1 : 0;
	$hora_limite = formato_hora($hora_i.':'.$minu_i.':00 pm', 'H:i');
	$qry = "UPDATE tbl_precio SET nombre = '$nombre', costo = $costo, comision = $comision, dia = '$dia', comentario = '$comentario', foncine = $foncine, hora_limite = '$hora_limite', censura = '$censura', 
	id_tipo = $tipo, id_tipo_sala = $tipo_sala,costodolar='$costodolar', contenido = '$contenido', marcar = $marcar, marca = '$marca', dosporuno = '1', clave = '$clave', activo = $activo ";
	$qry .= "WHERE id_precio = $id_precio";
	//echo $qry;DIE();
	$result = @mysql_query($qry, $cnn) or die("Error 9005: ".mysql_error());
	if($result):
		//Actualizo las Censuras Restringidas
		//Borro las relaciones Censura y Precio
		$delete_qry = "DELETE FROM tbr_precio_censura WHERE id_precio = $id_precio ";
		$borrar_censura = @mysql_query($delete_qry) or die("Error 9006: ".mysql_error());
		foreach($censuras as $id_censura):
			//inserto las censuras
			$insert_qry = "INSERT INTO tbr_precio_censura (id_precio, id_censura) VALUES ($id_precio, $id_censura)";
			$insert_censura = @mysql_query($insert_qry) or die("Error 9007: ".mysql_error());
		endforeach;
		$mensaje = "Registro Actualizado con Exito";
		unset($id_precio, $nombre, $costo, $comision, $dia, $comentario, $foncine, $activo, $censura, $hora_i, $minu_i, $tipo, $tipo_sala, $contenido, $marca, $marcar, $clave);
		$censuras = array();
		$dia = '0000000';
		$accion = AGREGAR;
		//header("Location:".$modulo."&sub=buscar");
	endif;
endif;


?>
<html>
<head>
<title>Precio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre.value;
	var campo2 = formulario.costo.value;
	var campo3 = formulario.comentario.value;
	var campo4 = formulario.foncine.value;
	var campo5 = formulario.tipo.value;
		
	if(campo1.length ==0){
		alert('El nombre de la Plantilla no puede ser vacio.');
		return false;
	}
	if(campo2.length ==0){
		alert('Costo del Boleto no puede ser vacio.');
		return false;
	}
	if(campo3.length ==0){
		alert('Comentario del Boleto no puede ser vacio.');
		return false;
	}
	if(campo5==0){
		alert('Seleccione un tipo de boleto.');
		return false;
	}
 	if(campo4.length ==0){
		alert('Impuesto no puede ser vacio.');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Plantilla de Precios</td>
</tr>
<form name="precio" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">C&oacute;digo</td>
				<td width="81%"><input name="id_precio" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_precio) ? $id_precio : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;C&oacute;digo de la plantilla (Campo Automatico)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Nombre:</td>
				<td width="81%"><input name="nombre" type="text" class="css-campo-metadata" size="40" maxlength="30" value="<?php echo isset($nombre) ? $nombre : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Plantilla (Ej.: General)</span></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Contenido Combo:</td>
				<td width="81%"><input name="contenido" type="text" class="css-campo-metadata" size="40" value="<?php echo isset($contenido) ? $contenido : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Contenido del Precio tipo combo</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Costo:</td>
				<td width="81%"><input name="costo" type="text" class="css-campo-metadata" size="12" maxlength="8" value="<?php echo isset($costo) ? $costo : '';?>" onKeyPress="onNumero()">
				<span class="css-info-metadata">&nbsp;&nbsp;Costo del boleto sin decimales (Ej.: 40)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Costo Dolar:</td>
				<td width="81%"><input name="costodolar" type="text" class="css-campo-metadata" size="12" maxlength="8" value="<?php echo isset($costodolar) ? $costodolar : '';?>" onKeyPress="onNumero()">
				<span class="css-info-metadata">&nbsp;&nbsp;Costo $ del boleto sin decimales (Ej.: 40)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Comisi&oacute;n</td>
				<td width="81%"><input name="comision" type="text" class="css-campo-metadata" size="12" maxlength="3" value="<?php echo isset($comision) ? $comision : '';?>" onKeyPress="onNumero()">
				<span class="css-info-metadata">&nbsp;&nbsp;Comisi&oacute;n extra del boleto sin decimales (Ej.: 35)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Dias Disponible:</td>
				<td width="81%"><table width="100%" cellpadding="0" cellspacing="0" align="left">
				<tr>
					<td width='15'><img src="images/tr.gif"></td>
					<td width='15' class="css-campo-metadata" align="center">D</td>
					<td width='15' class="css-campo-metadata" align="center">L</td>
					<td width='15' class="css-campo-metadata" align="center">M</td>
					<td width='15' class="css-campo-metadata" align="center">M</td>
					<td width='15' class="css-campo-metadata" align="center">J</td>
					<td width='15' class="css-campo-metadata" align="center">V</td>
					<td width='15' class="css-campo-metadata" align="center">S</td>
					<td><img src="images/tr.gif"></td>
				</tr>
				<tr>
					<td width='15'><img src="images/tr.gif"></td>
					<?php
					for($i=1; $i <= 7; $i++):
						$checked = (substr($dia, $i-1, 1) == 1)  ? 'checked' : '';
						echo "<td width='15'><input type='checkbox' name='dia$i' $checked></td>"."\n";
					endfor;	
					?>
					<td ><img src="images/tr.gif"></td>
				</tr>
				</table></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Comentario:</td>
				<td width="81%"><input name="comentario" type="text" class="css-campo-metadata" size="40" maxlength="30" value="<?php echo isset($comentario) ? $comentario : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Comentario acerca del boleto (Ej.: Entrada General)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">% Impuesto:</td>
				<td width="81%" class="css-campo-metadata"><input name="foncine" type="text" class="css-campo-metadata" size="6" maxlength="2" value="<?php echo isset($foncine) ? $foncine : '';?>" onKeyPress="onNumero()">&nbsp;%
				<span class="css-info-metadata">&nbsp;&nbsp;Porcentaje de cobro municipal sin decimales (Ej.: 2)</span></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Limite:</td>
				<td width="81%" class="css-campo-metadata"><?php echo combo_hora((isset($hora_i) ? $hora_i : -1), 'hora_i');?>&nbsp;<?php echo combo_minuto((isset($minu_i) ? $minu_i : -1),'minu_i');?>
				<span class="css-info-metadata">&nbsp;&nbsp;Hora Limite para aparecer en taquilla</span></td>
			</tr>
            <!--
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Censura:</td>
				<td width="81%" class="css-campo-metadata"><?php //echo combo_censura2(-1, $censura);?>
				<span class="css-info-metadata">&nbsp;&nbsp;Censura Minima Permitida</span></td>
			</tr> -->
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Censura Restringida:</td>
				<td width="81%" class="css-campo-metadata">
				<?php 
					$qry_censura = "SELECT id_censura, nomenclatura FROM tbl_censura WHERE activo = 1 ORDER BY nomenclatura ASC ";
					$rst_censura = mysql_query($qry_censura);
					$c = 0;
					while($campo_censura = mysql_fetch_object($rst_censura)):
						$checked = in_array($campo_censura->id_censura, $censuras) ? " checked" : "";
						echo "<input type='checkbox' name='cen[$c]' value='".$campo_censura->id_censura."' $checked>".$campo_censura->nomenclatura."&nbsp;&nbsp; \n";
						$c++;
					endwhile;
				?>
				<span class="css-info-metadata">&nbsp;&nbsp;Seleccione la censura NO permitida para este precio</span></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Tipo Boleto:</td>
				<td width="81%"><?php echo combo_tipo ((isset($tipo) ? $tipo : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Tipo Sala:</td>
				<td width="81%"><?php echo combo_tipo_sala ((isset($tipo_sala) ? $tipo_sala : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Marcar Boleto:</td>
				<td width="81%"><input name="marcar" type="checkbox" class="css-campo-metadata" <?php echo !empty($marcar) ? $marcar : '';?>></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Letra de Marca:</td>
				<td width="81%"><input name="marca" type="text" class="css-campo-metadata" size="5" maxlength="1" value="<?php echo isset($marca) ? $marca : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Escriba UNA sola letra para marcar el boleto (Ej.: N)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Clave:</td>
				<td width="81%"><input name="clave" type="password" class="css-campo-metadata" size="10" maxlength="10" value="<?php echo isset($clave) ? $clave : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Introduzca una clave de autorizaci&oacute;n para la venta de este boleto.</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Activo:</td>
				<td width="81%"><input name="activo" type="checkbox" class="css-campo-metadata" <?php echo !empty($activo) ? $activo : '';?>></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">2X1:</td>
				<td width="81%"><input name="dosporuno" type="checkbox" class="css-campo-metadata" <?php echo !empty($dosporuno) ? $dosporuno : '';?>></td>
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

