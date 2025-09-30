<?php
session_start();
include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$accion = isset($_GET['accion']) ? $_GET['accion'] : AGREGAR;
$fecha = isset($_GET['fecha']) ? formato_fecha($_GET['fecha']) : date('Y-m-d', time());
$sala = isset($_GET['sala']) ? $_GET['sala'] : 0;
$cine = isset($_GET['cine']) ? $_GET['cine'] :  0;
$pelicula = isset($_GET['pelicula']) ? $_GET['pelicula'] : 0;
$hora_inicio = isset($_GET['hora_inicio']) ? $_GET['hora_inicio'] : '00:00:00';
$status = isset($_GET['status']) ? $_GET['status'] : 0;
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : 0;
$precio = isset($_GET['precio']) ? $_GET['precio'] : 0;

$mensaje = "";
//variable que obtiene el valor del combo de peliculas
$eliminar="";

if($status > 0):
	$qry = "SELECT id_programacion ";
	$qry .= "FROM tbl_programacion ";
	$qry .= "WHERE id_pelicula = $pelicula ";
	$qry .= "AND id_cine = $cine ";
	$qry .= "AND hora_inicio = '$hora_inicio' ";
	$qry .= "AND fecha_programacion = '" . $fecha . "' ";
	$result_prog = mysql_query($qry);
	//echo $qry . "<br>";
	$id_programacion = 0;
	if($campo_prog = mysql_fetch_object($result_prog)):
	 	$id_programacion = $campo_prog->id_programacion;
		if($id_programacion > 0):
			//Busco el monto del precio
			$qry = "SELECT id_precio, costo FROM tbl_precio WHERE id_precio = $precio ";
			$result_precio = mysql_query($qry, $cnn) or die("Error 11001: ".mysql_error());
			if($campo_precio = mysql_fetch_object($result_precio)):
				$costo = $campo_precio->costo;
			endif;
			
			$porcentaje = 0;
			$hoy = date('Y-m-d',time());
			$qry = "SELECT 	CASE WHEN porcentaje IS NULL THEN 0 ELSE porcentaje END as porcentaje ";
			$qry .= "FROM	tbl_pelicula_distribuidor d INNER JOIN  tbl_programacion p ON d.id_pelicula = p.id_pelicula ";
			$qry .= "WHERE	p.id_programacion = $id_programacion ";
			$qry .= "AND 	d.fecha_inicio <= '$hoy' ";
			$qry .= "AND 	(d.fecha_fin >= '$hoy' OR d.fecha_fin = '0000-00-00') ";
			$resultP = @mysql_query($qry);
			if($campoP = mysql_fetch_object($resultP)):
				$porcentaje = $campoP->porcentaje;
			endif; 
			$qry_id2 = "SELECT porcentaje,pe.id_procedencia FROM tbl_pelicula pe INNER JOIN tbl_programacion  pr  on  pe.id_pelicula = pr.id_pelicula  inner join tbl_distribuidor d ON d.id_distribuidor = pe.id_distribuidor WHERE pr.id_programacion = $id_programacion ";
			$result_id222 = mysql_query($qry_id2);
			$pd = 5;
			if($campo_id2 = mysql_fetch_object($result_id222)):
				$pd =  $campo_id2->porcentaje;
				if($campo_id2->id_procedencia==1)$pd = 0;
			endif;
			

			
			$total_transaccion = $cantidad * $costo;
			$qry = "INSERT INTO tbl_operacion (id_cine, id_programacion, fecha_operacion, id_tipo_transaccion, cantidad_transaccion, id_precio, ";
			$qry .= "costo, recargo, total_transaccion, usuario_registro, status, transmitido, hora_operacion, porcentaje_distribuidor,foncine) VALUES (";
			$qry .= "$cine, $id_programacion, '$fecha', 2, $cantidad, $precio, $costo, 0, $total_transaccion, ". $_SESSION['USER'] .", 1, 0, '". date("H:i:s")."', $porcentaje,'$pd')";
			echo $qry;
			$result = mysql_query($qry, $cnn) or die("Error 11001: ".mysql_error());
			
			echo "<script>window.close()</script>";
		endif;
	endif;
endif;

?>
<html>
<head>
<script language="javascript" type="text/javascript" src="js/func.ajax.js"></script>
<script language="JavaScript"> 
function actualizaPadre(){ 
    window.opener.document.filtro.submit()
} 
function Validar(formulario){

	var campo1 = formulario.pelicula.value;
	var campo2 = formulario.hora_inicio.value;
	var campo3 = formulario.precio.value;
	var campo4 = formulario.cantidad.value;
	
	if(campo1 ==0){
		alert('Seleccione una película.');
		return false;
	}
	if(campo2 ==0){
		alert('Seleccione una hora de inicio.');
		return false;
	}
	if(campo3 == 0){
		alert('Seleccione un precio.');
		return false;
	}
	if(campo4 ==0){
		alert('La cantidad debe ser mayor a 0.');
		return false;
	} else {
		//Validar si no excede la disponibilidad de aforo de la sala
		
		return true;	
	}
}
</script> 

<title>Función Extra</title>
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
<form name="funcion" method="get" action="" onSubmit="return Validar(this)">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Fecha:</td>
				<td width="81%"><input name="fecha" type="text" class="css-campo-metadata-id" size="30" value="<?php echo formato_fecha($fecha, 2);?>" readonly></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cine:</td>
				<td width="81%"><input name="cine_nombre" type="text" class="css-campo-metadata-id" size="30" value="<?php echo buscar_cine($cine);?>" readonly>
				<input name="cine" type="hidden" value="<?php echo $cine;?>"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Película:</td>
				<td width="81%">
				<?php 
				$qry = "SELECT DISTINCT p.id_pelicula, p.nombre_espanol FROM tbl_pelicula p INNER JOIN tbl_programacion pr ";
				$qry .= "ON p.id_pelicula = pr.id_pelicula ";
				$qry .= "WHERE fecha_programacion = '$fecha' ";
				if($pelicula > 0):
					$qry .= "AND p.id_pelicula = $pelicula ";
				endif;
				$qry .= "ORDER BY nombre_espanol ";
				//echo $qry ;
				$result = mysql_query($qry) or die("Error 006-combo - ".mysql_error());
				$tabla = "";
				//echo $fecha;
				$tabla .= "<select name='pelicula' class='css-campo-metadata' onchange='cargarFunciones(this.value, ". '"' . $fecha . '"' . ");'>";
				$tabla .= "<option value='0' selected >- Pel&iacute;culas -</option>";
				while($campo = mysql_fetch_object($result)):
					if($pelicula == $campo->id_pelicula):
						$select = 'selected';
					else:
						$select = '';
					endif;
					$tabla .= "<option value='$campo->id_pelicula' $select >$campo->nombre_espanol</option>";
				endwhile;
				$tabla .= "</select>";
				echo $tabla;
				?>				
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Inicio:</td>
				<td width="81%">
                <div id="div_hora">
				<?php
				if($pelicula>0):
					$qry = "SELECT DISTINCT hora_inicio FROM tbl_programacion ";
					$qry .= "WHERE fecha_programacion = '$fecha' ";
					$qry .= "AND id_pelicula = $pelicula ";
					$qry .= "ORDER BY hora_inicio ";
					//echo $qry ;
				
					$result = mysql_query($qry) or die("Error 006-combo - ".mysql_error());
					$tabla = "";
					$tabla .= "<select name='hora_inicio' class='css-campo-metadata'>";
					$tabla .= "<option value='0' selected >- Hora -</option>";
					while($campo = mysql_fetch_object($result)):
						$tabla .= "<option value='$campo->hora_inicio'>" . date('h:i a', strtotime($campo->hora_inicio)) . "</option>";
					endwhile;
					$tabla .= "</select>";
					echo $tabla;
				else:
					$tabla = "";
					$tabla .= "<select name='hora_inicio' class='css-campo-metadata'>";
					$tabla .= "<option value='0' disabled='disabled'>- Seleccione Película -</option>";
					$tabla .= "</select>";
					echo $tabla;
				endif;
				?>
                </div></td>	
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Precio:</td>
				<td width="81%">
                <?php
				$dia_seleccionado = date('w', strtotime($fecha));
                $qry = "SELECT DISTINCT id_precio, nombre, costo FROM tbl_precio ";
				$qry .= "WHERE activo = 1 AND SUBSTRING(dia, $dia_seleccionado+1, 1) = 1 ";
				$qry .= "ORDER BY nombre ";
				//echo $qry ;
				$result = mysql_query($qry) or die("Error 006-combo - ".mysql_error());
				$tabla = "";
				$tabla .= "<select name='precio' class='css-campo-metadata'>";
				$tabla .= "<option value='0' selected >- Precio -</option>";
				while($campo = mysql_fetch_object($result)):
					$tabla .= "<option value='$campo->id_precio' >$campo->nombre (Bsf. $campo->costo)</option>";
				endwhile;
				$tabla .= "</select>";
				echo $tabla;
				?></td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cantidad:</td>
				<td width="81%"><input name="cantidad" type="text" class="css-campo-metadata" size="10" value="0"></td>
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
</body>
</html>


