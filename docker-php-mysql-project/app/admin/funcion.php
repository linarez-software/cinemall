<?php
session_start();
include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$id_programacion = isset($_GET['idp']) ? $_GET['idp'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : AGREGAR;

$status = 0;
$mensaje = "";
//variable que obtiene el valor del combo de peliculas
$eliminar="";

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
		$sql="select * from tbl_programacion_preventa where id_programacion = $id_programacion";
		$result_prog = mysql_query($sql);
		$preventa = "";
		$desde = "";
		if($campo_prog = mysql_fetch_object($result_prog)):
			$preventa = $campo_prog->hasta ;$desde = $campo_prog->desde ;
		endif;
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

//Mediante esta rutinita se logra agregar la pelicula a los 7 dias que componen una semana
if($accion == AGREGAR && $status == 1):
	$dias = isset($_GET['dias_repetir']) ? $_GET['dias_repetir'] : '';
	for($i=0;$i<$dias;$i++){
		$mer=($hora_i>=11)?"am":"pm";
		$mer=($hora_i==12)? "pm": $mer;
		$hora_inicial = formato_hora($hora_i.':'.$minu_i.':00 '.$mer, 'H:i');
		//$hora_final = formato_hora($hora_f.':'.$minu_f.':00 pm', 'H:i');
		$qry = "INSERT INTO tbl_programacion (id_cine, id_sala, id_pelicula, fecha_programacion, hora_inicio,  activo) VALUES (";
		$qry .= "$id_cine, $id_sala, $id_pelicula,'".formato_fecha($fecha)."', '$hora_inicial', $activo)";
		$result = mysql_query($qry, $cnn) or die("Error 11001: ".mysql_error());
		$fecha=formato_fecha($fecha);
		$fecha++;
		$fecha=formato_fecha($fecha,2);
	}
	if($result):
		$mensaje = "Registro Agregado con Exito";
		$accion = AGREGAR;
		echo "<script>window.close()</script>";
	endif;

elseif($accion == MODIFICAR && $status == 1 && $id_pelicula!=0):
	$mer=($hora_i>=11)?"am":"pm";
	$mer=($hora_i==12)? "pm": $mer;
	$hora_inicial = formato_hora($hora_i.':'.$minu_i.':00 '.$mer, 'H:i');
	//$hora_final = formato_hora($hora_f.':'.$minu_f.':00 pm', 'H:i');

	$qry = "UPDATE tbl_programacion SET 
			id_cine = $id_cine, 
			id_sala = $id_sala, 
			id_pelicula = $id_pelicula,
			fecha_programacion = '".formato_fecha($fecha)."', 
			hora_inicio = '$hora_inicial', 
			activo = $activo ";
	$qry .= "WHERE id_programacion = $programacion";
	//echo $qry; 
	$result = mysql_query($qry, $cnn) or die("Error 11002: ".mysql_error());
	if($result):
		$sql="delete from  tbl_programacion_preventa where id_programacion =$programacion";
		$result = mysql_query($sql, $cnn) or die("Error 11002: ".mysql_error());
		if($_GET["hasta"]!="")
		{
			$sql="insert into tbl_programacion_preventa values ($programacion,'".$_GET["desde"]."','".($_GET["hasta"])."')";
			$result = mysql_query($sql, $cnn) or die("Error 11002: ".mysql_error());

		}
		
		$mensaje = "Registro Actualizado con Exito";
		$accion = AGREGAR;
		echo "<script>window.close()</script>";
	endif;
elseif($accion == MODIFICAR && $status == 1 && $id_pelicula==0):
	$qry="Delete from tbl_programacion where id_programacion=$programacion";
	echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 11002: ".mysql_error());
	echo "<script>window.close()</script>";
endif;
$sql="select * from tbl_operacion where id_programacion like '$programacion'";
$result_prog = mysql_query($sql);
	$ventas_hechas=0;
	if($campo_prog = mysql_fetch_object($result_prog)):
		$ventas_hechas=1;
	endif;
	$ventas_hechas;
?>
<html>
<head>
<script language="javascript" type="text/javascript" src="js/func.ajax.js"></script>
<script language="JavaScript"> 
function actualizaPadre(){ 
    window.opener.document.programacion.submit()
} 
function clave()
{
	var sign = prompt("Clave?");

	if (sign.toLowerCase() == "281080") {
	   document.funcion.submit();
	}

// there are many ways to use the prompt feature

}
function Validar(){

	var libre = document.getElementById("libre").value;
	if(libre > 0){
		alert("La pel�cula seleccionada ya esta programada en este horario.");
		return false;	
	}

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
<form name="funcion" method="get" action="funcion.php" onSubmit="return Validar();">
<div id="mensaje"></div>
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Fecha:</td>
				<td width="81%"><input name="fecha" id="fecha" type="text" class="css-campo-metadata-id" size="30" value="<?php echo $fecha;?>" readonly></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cine:</td>
				<td width="81%"><input name="cine" type="text" class="css-campo-metadata-id" size="30" value="<?php echo buscar_cine($id_cine);?>" readonly>
				<input name="idc" id="idc" type="hidden" value="<?php echo $id_cine;?>"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Sala:</td>
				<td width="81%"><input name="sala" id="sala" type="text" class="css-campo-metadata-id" size="10"  value="<?php echo buscar_sala($id_sala);?>" readonly>
				<input name="ids" id="ids" type="hidden" value="<?php echo $id_sala;?>"></td>
			</tr>

			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Pel&iacute;cula:</td>
				<td width="81%"><?php echo combo_pelicula_funcion(1, (isset($id_pelicula) ? $id_pelicula : 0));?></td>
			</tr>
            <?php 
			if($_SESSION['NIVEL'] <= 1):
			?>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Inicio:</td>
				<td width="81%"><?php echo combo_hora((isset($hora_i) ? $hora_i : -1), 'hora_i');?>&nbsp;<?php echo combo_minuto((isset($minu_i) ? $minu_i : -1),'minu_i', " onchange='funcionRepetida();' ");?></td>
			</tr>
            <?php
			else:
			?>
            <tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Hora Inicio:</td>
				<td width="81%"><?php echo combo_hora((isset($hora_i) ? $hora_i : -1), 'hora_i2', ' disabled');?>&nbsp;<?php echo combo_minuto((isset($minu_i) ? $minu_i : -1),'minu_i2', ' disabled');?></td>
                <input type="hidden" name="hora_i" value="<?php echo $hora_i; ?>">
                <input type="hidden" name="minu_i" value="<?php echo $minu_i; ?>">
			</tr>
            <?php 
			endif;
			if($accion == AGREGAR): 
			?>
	            <tr bgcolor="#DDDDDD">
					<td width="19%" class="css-label-metadata">D&iacute;as Repetir:</td>
					<td width="81%"><input type="text" name="dias_repetir" id="dias_repetir" class="css-campo-metadata" maxlength="1" value="7" onKeyPress="onNumero()"></td>
				</tr>
             <?php 
             endif;
             ?>
             <?php 
			if($accion != AGREGAR): 
			?>
	         
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Preventa:</td>
				<td width="81%">
					<input type='date' class="form-control"  value="<?php echo $desde;?>" name ='desde' >
                    <input type='date' class="form-control"  value="<?php echo $preventa;?>" name ='hasta' ><input type="button" value="Limpiar Fecha" class="css-boton-metadata" onClick="document.funcion.hasta.value='';document.funcion.desde.value='';">

				</td>
			</tr>
				<?php 
			endif; 
			?>
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
		<input type="hidden" name="activo" value="<?php echo $activo;?>">
		<input type="hidden" name="programacion" value="<?php echo $programacion;?>">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
        <input type="hidden" name="libre" id="libre" value="">
		<input type="hidden" name="status" value="1">
        <?php if($accion == MODIFICAR): ?>
	       <!-- <input type="button" value="Eliminar" class="css-boton-metadata" onClick="javascript:EliminarProgramacion(<?php echo $programacion;?>)">&nbsp;&nbsp;&nbsp; -->
        <?php endif; ?>
        
        <?php if($ventas_hechas==0) {?><input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp; <?php }?>
		<?php if($ventas_hechas==1) {?><input type="button" value="Guardar2" class="css-boton-metadata" onclick='clave();'>&nbsp;&nbsp;&nbsp; <?php }?>
		<input type="hidden" value="Cancelar funci�n" class="css-boton-metadata" onClick="javascript:Limpiar()">
		<!-- Boton cancelar funcion fue ocultado por presentar problemas de ejecucion.  Solucionar este problema lo antes posible<!-- -->
	</td>
</tr>
</form>
</table>
</body>
</html>


