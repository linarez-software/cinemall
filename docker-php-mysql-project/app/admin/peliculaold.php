<?php

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

$modulo = MANTENIMIENTO."pelicula";

$idp = isset($_GET['idp']) ? $_GET['idp'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$status = 0;
$mensaje = "";
if($idp > 0):
	$qry = "SELECT id_pelicula, nombre_espanol, nombre_corto, nombre_ingles, id_distribuidor, duracion, 
			id_censura, pagina_web, id_genero, sinopsis, porcentaje_distribuidor, id_tipo, registro_obra, 
			id_formato_proyeccion, id_procedencia, activo,listar FROM tbl_pelicula ";
	$qry .= "WHERE id_pelicula = $idp ";
	$result = @mysql_query($qry, $cnn) or die("Error 3001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$id_pelicula = $campo->id_pelicula;
		$nombre_espanol = $campo->nombre_espanol;
		$nombre_corto = $campo->nombre_corto;
		$nombre_ingles = $campo->nombre_ingles;
		$distribuidor = $campo->id_distribuidor;
		$duracion = $campo->duracion;
		$censura = $campo->id_censura;
		$pagina_web = $campo->pagina_web;
		$genero = $campo->id_genero;
		$sinopsis = $campo->sinopsis;
		$porcentaje = $campo->porcentaje_distribuidor;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$tipo = $campo->id_tipo;
		$pelicula = $idp;
		$registro_obra = $campo->registro_obra;
		$id_formato_proyeccion = $campo->id_formato_proyeccion;
		$id_procedencia = $campo->id_procedencia;
		$listar = $campo->listar;
		
		$accion = MODIFICAR;
	endif;

else:
	$id_pelicula = isset($_POST['id_pelicula']) ? $_POST['id_pelicula'] : '';
	$nombre_espanol = isset($_POST['nombre_espanol']) ? trim(strtoupper($_POST['nombre_espanol'])) : '';
	$nombre_corto = isset($_POST['nombre_corto']) ? trim(strtoupper($_POST['nombre_corto'])) : '';
	$nombre_ingles = isset($_POST['nombre_ingles']) ? trim(strtoupper($_POST['nombre_ingles'])) : '';
	$duracion = isset($_POST['duracion']) ? trim(strtoupper($_POST['duracion'])) : '';
	$pagina_web = isset($_POST['pagina_web']) ? trim(strtoupper($_POST['pagina_web'])) : '';
	$sinopsis = isset($_POST['sinopsis']) ? trim(strtoupper($_POST['sinopsis'])) : '';
	$censura = isset($_POST['censura']) ? trim(strtoupper($_POST['censura'])) : '';
	$genero = isset($_POST['genero']) ? trim(strtoupper($_POST['genero'])) : '';
	$distribuidor = isset($_POST['distribuidor']) ? trim(strtoupper($_POST['distribuidor'])) : '';
	$porcentaje = isset($_POST['porcentaje']) ? trim(strtoupper($_POST['porcentaje'])) : 0;
	$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '0';
	$registro_obra = isset($_POST['registro_obra']) ? $_POST['registro_obra'] : ''; 
	$id_formato_proyeccion = isset($_POST['formato_proyeccion']) ? $_POST['formato_proyeccion'] : 0; 
	$id_procedencia = isset($_POST['procedencia']) ? $_POST['procedencia'] : 0; 
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$listar = isset($_POST['listar']) ? $_POST['listar'] : 0;
	//$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;

if($accion == AGREGAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "INSERT INTO tbl_pelicula (nombre_espanol, nombre_corto, nombre_ingles, id_distribuidor, duracion, 
			id_censura, pagina_web, id_genero, sinopsis, porcentaje_distribuidor, id_tipo, registro_obra, 
			id_formato_proyeccion, id_procedencia, activo,listar) VALUES (";
	$qry .= "'$nombre_espanol', '$nombre_corto', '$nombre_ingles','$distribuidor', '$duracion', '$censura', 
			'$pagina_web', '$genero', '$sinopsis', $porcentaje, $tipo, '$registro_obra', $id_formato_proyeccion, $id_procedencia, $activo,$listar)";
	$result = @mysql_query($qry, $cnn) or die("Error 3002: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_pelicula, $nombre_espanol, $nombre_corto, $nombre_ingles, $duracion);
		unset($pagina_web, $sinopsis, $activo, $distribuidor, $genero, $censura, $tipo, $registro_obra, $id_formato_proyeccion, $id_procedencia);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_pelicula SET 
			nombre_espanol = '$nombre_espanol', 
			nombre_corto = '$nombre_corto', 
			nombre_ingles = '$nombre_ingles', 
			duracion = $duracion, 
			pagina_web = '$pagina_web', 
			sinopsis = '$sinopsis', 
			id_tipo = $tipo,
			activo = $activo, 
			id_distribuidor = $distribuidor,
			listar = $listar,
			porcentaje_distribuidor = $porcentaje, 
			id_genero = $genero, 
			id_censura = $censura,
			registro_obra = '$registro_obra',
			id_formato_proyeccion = $id_formato_proyeccion, 
			id_procedencia = $id_procedencia ";
	$qry .= "WHERE id_pelicula = $id_pelicula";
	$result = @mysql_query($qry, $cnn) or die("Error 3003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_pelicula, $nombre_espanol, $nombre_corto, $nombre_ingles, $duracion, $registro_obra, $id_formato_proyeccion, $id_procedencia);
		unset($pagina_web, $sinopsis, $activo, $distribuidor, $genero, $censura, $tipo, $porcentaje);
		$accion = AGREGAR;
	endif;
endif;

?>
<html>
<head>
<title>Pelicula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre_espanol.value;
	var campo2 = formulario.nombre_ingles.value;
	var campo3 = formulario.nombre_corto.value;
	var campo4 = formulario.duracion.value;
	var campo5 = formulario.tipo.value;

	if(campo1.length ==0){
		alert('El Nombre En Español de la Pelicula no puede ser vacio.');
		return false;
	}
	if(campo2.length ==0){
		alert('El Nombre En Ingles de la Pelicula  no puede ser vacio.');
		return false;
	}
	if(campo3.length ==0){
		alert('El Nombre Corto de la Pelicula  no puede ser vacio.');
		return false;
	}
	if(campo5==0){
		alert('Seleccione un tipo de boleto.');
		return false;
	}
	if(campo4.length ==0){
		alert('La Duracion de la Pelicula  no puede ser vacio.');
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
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Pelicula</td>
</tr>
<form name="pelicula" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">

			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Código:</td>
				<td width="81%"><input name="id_pelicula" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_pelicula) ? $id_pelicula : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;Código de la Pelicula (Campo Automatico)</span>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre en Español:</td>
				<td width="81%"><input name="nombre_espanol" type="text" class="css-campo-metadata" size="40" maxlength="45" value="<?php echo isset($nombre_espanol) ? $nombre_espanol : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Pelicula (Ej.: La Era de Hielo 2)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre en Ingles:</td>
				<td width="81%"><input name="nombre_ingles" type="text" class="css-campo-metadata" size="40" maxlength="45" value="<?php echo isset($nombre_ingles) ? $nombre_ingles : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre de la Pelicula en Ingles (Ej.: Ice Age 2)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre Corto:</td>
				<td width="81%"><input name="nombre_corto" type="text" class="css-campo-metadata" size="30" maxlength="20" value="<?php echo isset($nombre_corto) ? $nombre_corto : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre Corto de la Pelicula (Ej.: La Era 2)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Duración:</td>
				<td width="81%"><input name="duracion" type="text" class="css-campo-metadata" size="10" maxlength="3" value="<?php echo isset($duracion) ? $duracion : '';?>" onKeyPress="onNumero()">
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Censura:</td>
				<td width="81%"><?php echo combo_censura(1, (isset($censura) ? $censura : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Pagina Web:</td>
				<td width="81%"><input name="pagina_web" type="text" class="css-campo-metadata" size="40" maxlength="40" value="<?php echo isset($pagina_web) ? $pagina_web : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp; Pagina Web de la Pelicula(Ej.: http://www.iceagemovie.com/)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Genero:</td>
				<td width="81%"><?php echo combo_genero(1, (isset($genero) ? $genero : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Sinopsis:</td>
				<td width="81%"><textarea name="sinopsis" cols="40" rows="7" class="css-campo-metadata"><?php echo isset($sinopsis) ? $sinopsis : '';?></textarea></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Tipo Pel&iacute;cula:</td>
				<td width="81%"><?php echo combo_tipo ((isset($tipo) ? $tipo : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Distribuidor:</td>
				<td width="81%"><?php echo combo_distribuidor (1, (isset($distribuidor) ? $distribuidor : 0)); ?></td>
			</tr>
            <!--
            <tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">% Distribuidor:</td>
				<td width="81%"><input name="porcentaje" type="hidden" class="css-campo-metadata" size="5" maxlength="3" value="<?php echo isset($porcentaje) ? $porcentaje : 0;?>">
				<span class="css-info-metadata">%&nbsp;&nbsp; Porcentaje de pago al distribuidor</span></td>
			</tr>
            -->
            <tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata" height="25">% Distribuidor:</td>
				<td width="81%">
                <?php 
					if(!empty($id_pelicula)>0):
				?>
					<a href="pelicula_distribuidor.php?keepThis=true&idp=<?php echo $id_pelicula;?>&TB_iframe=true&height=210&width=296" title="Pelicula Distribuidor" class="thickbox"><input name="b_distribuidor" type="button" class="css-campo-metadata" value="Cargar Porcentaje"></a>
                    <span class="css-info-metadata">%&nbsp;&nbsp; Porcentaje de pago al distribuidor</span></td>
              	<?php
					else:
				?>
                	<span class="css-info-metadata">Debe guardar la película para cargar el % distribuidor.</span></td>	
				<?php
					endif;
				?>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nro. Registro Obra:</td>
				<td width="81%"><input name="registro_obra" type="text" class="css-campo-metadata" size="40" maxlength="40" value="<?php echo !empty($registro_obra) ? $registro_obra : '';?>">
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Formato Proyección:</td>
				<td width="81%"><?php echo combo_formato_proyeccion ((!empty($id_formato_proyeccion) ? $id_formato_proyeccion : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Procedencia:</td>
				<td width="81%"><?php echo combo_procedencia ((!empty($id_procedencia) ? $id_procedencia : 0)); ?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Activo:</td>
				<td width="81%"><input name="activo" type="checkbox" class="css-campo-metadata" <?php echo !empty($activo) ? $activo : '';?>></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Listar:</td>
				<td width="81%">
                <?php
				$selected1=($listar==0) ? "selected='selected'" : "";
				$selected2=($listar==1) ? "selected='selected'" : "";
				echo "<select  name='listar' class='css-campo-metadata' >;";
				echo"<option value='0' $selected1>NO</option>";
				echo"<option value='1' $selected2>SI</option>";
				echo "</select>";
				?> 
                </td>
			</tr>
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()">&nbsp;&nbsp;&nbsp; -->
	</td>
</tr>
</form>
</table>
</body>
</html>
