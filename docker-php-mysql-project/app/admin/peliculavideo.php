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
			id_formato_proyeccion, id_procedencia, activo,foto,video FROM tbl_pelicula ";
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
		$foto = $campo->foto;
		$video = $campo->video;
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
	//$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;
$url=$_SERVER['REQUEST_URI'];


?>
<html>
<head>
<title>Pelicula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function validar(formulario){

	

	
}

function validar2(){
	alert('Paso');
}
</script>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" action="upload3.php" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="0">
<?php
$erro = isset($_GET['error']) ? $_GET['error'] : '2';
if (($erro)==1):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>Archivo subido carrectamente</td></tr>";
	echo $tabla;
endif;
if (($erro)==0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>Archivo Invalido</td></tr>";
	echo $tabla;
endif;
?>
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Mantenimiento de Pelicula - Video Promocional</td>
</tr>

<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">

			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Codigo:</td>
				<td width="81%"><input name="id_pelicula" type="text"  readonly='readonly' class="css-campo-metadata-id" size="10" value="<?php echo isset($id_pelicula) ? $id_pelicula : '';?>" readonly>
				<input name="id" type="hidden"   value="<?php echo isset($id_pelicula) ? $id_pelicula : '';?>" >
				<input name="url" type="hidden"   value="<?php echo isset($url) ? $url : '';?>" >
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  width="19%" class="css-label-metadata">Nombre en Español:</td>
				<td width="81%"><input name="nombre_espanol" readonly='readonly' type="text" class="css-campo-metadata" size="40" maxlength="45" value="<?php echo isset($nombre_espanol) ? $nombre_espanol : '';?>">
				</td>
			</tr>
			
			
            
		</table>
	</td>
</tr>
<tr>
	<td>
    
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">

			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Imagen:</td>
				<td width="81%">
                <input type="hidden" name="MAX_FILE_SIZE" value="990000000" /> 
                <input type="file" name="video" class="css-boton-metadata"/> <input type="submit" name="enviar" value="Guardar" class="css-boton-metadata"/>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td  colspan=2  align="center" class="css-label-metadata">Video </td>
				
			</tr>
			
            <tr bgcolor="#DDDDDD">
				<td colspan="2"  align="center" class="css-label-metadata">
                <table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
				    <tr bgcolor="#DDDDDD">
                        <td  align="center" class="css-label-metadata" align='center'>
						
								<embed src="<?php echo $video;?>" width="400" height="450">
						</td>
                    </tr>
                    
                    
                </table>
                </td>
				
			</tr>
			
            
		</table>
      
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()">&nbsp;&nbsp;&nbsp; -->
	</td>
</tr>

</table>
</form>	
</body>
</html>
