<?php
include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$status = 0;
$mensaje = "";
//echo (isset($_GET['resul'])) ? $_GET['resul'] : "";
$idp = $_GET['idp'];
//$idp = 548;
$modulo = substr(strrchr($_SERVER['PHP_SELF'], "/"),1, strpos(strrchr($_SERVER['PHP_SELF'], "/"), ".")-1) . ".php";

if(isset($_GET['acc'])):
	if($_GET['acc'] == 1):
		$idp = $_GET['idp'];
		$fecha_inicio = $_GET['fecha_inicio'];
		$fecha_fin = ($_GET['fecha_fin']=="")? "00-00-0000" : $_GET['fecha_fin'];
		$porcentaje = $_GET['porc'];
		$qry = "INSERT INTO tbl_pelicula_distribuidor (id_pelicula, fecha_inicio, fecha_fin, porcentaje) VALUES (";
		$qry .= $idp . ", '" . formato_fecha($fecha_inicio) . "', '" . formato_fecha($fecha_fin) . "', " . $porcentaje . ")";
 		$resultI = @mysql_query($qry);
		$Error = mysql_errno();
		if($Error == 0):
			$qry = "UPDATE tbl_operacion o, tbl_programacion p  SET o.porcentaje_distribuidor = $porcentaje ";
			$qry .= "WHERE o.id_programacion = p.id_programacion ";
			$qry .= "AND p.id_pelicula = $idp ";
			$qry .= "AND p.fecha_programacion >= '" .formato_fecha($fecha_inicio). "' ";
			if($fecha_fin!="00-00-0000"):
				$qry .= "AND p.fecha_programacion <= '" .formato_fecha($fecha_fin). "' ";
			endif;
			$resultU = @mysql_query($qry);
			$Error = mysql_errno();
		endif;
	else:
		$id = $_GET['id'];
		/* Busco las fecha inicio y fin del id */
		$qry = "SELECT fecha_inicio, fecha_fin, id_pelicula FROM tbl_pelicula_distribuidor WHERE id_pelicula_distribuidor = $id ";
		$resultB = @mysql_query($qry);
		if($Buscar = mysql_fetch_object($resultB)):
			$fecha_inicio = $Buscar->fecha_inicio;
			$fecha_fin = $Buscar->fecha_fin;
		endif;
				
		$qry = "DELETE FROM tbl_pelicula_distribuidor WHERE id_pelicula_distribuidor = $id";
		$resultD = @mysql_query($qry) or die("Error 3003: ".mysql_error());
		$resultado = $resultD;
		$Error = mysql_errno();
		if($Error == 0):
			$qry = "UPDATE tbl_operacion o, tbl_programacion p  SET o.porcentaje_distribuidor = 0 ";
			$qry .= "WHERE o.id_programacion = p.id_programacion ";
			$qry .= "AND p.id_pelicula = $idp ";
			$qry .= "AND p.fecha_programacion >= '" .$fecha_inicio. "' ";
			if($fecha_fin!="00-00-0000"):
				$qry .= "AND p.fecha_programacion <= '" .$fecha_fin. "' ";
			endif;
			$resultU = mysql_query($qry);
			$Error = mysql_errno();
		endif;
	endif;
	header("Location: $modulo?idp=$idp&err=$Error");
endif; 

$qryD = "SELECT id_pelicula_distribuidor, id_pelicula, fecha_inicio, fecha_fin, porcentaje FROM tbl_pelicula_distribuidor ";
$qryD .= "WHERE id_pelicula = $idp ORDER BY fecha_inicio";
//echo $qryD;
$resultD = @mysql_query($qryD, $cnn) or die("Error 3001: ".mysql_error());


?>
<!DOCTYPE html>
<html>
<head>
<title>Pelicula - Distribuidor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript">
jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});    
 
$(document).ready(function() {
	$("#fecha_inicio").datepicker({
		//showOn: 'button', 
   		//buttonImageOnly: true, 
   		//buttonImage: 'images/datepicker.png',
		showAnim: 'slideDown'
	});
});

$(document).ready(function() {
	$("#fecha_fin").datepicker({
		//showOn: 'button', 
   		//buttonImageOnly: true, 
   		//buttonImage: 'images/datepicker.png',
		showAnim: 'slideDown'
	});
});

</script>
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function Buscar(){
	document.pelicula_distribuidor.action = 'pelicula_distribuidor-buscar.php';
	document.pelicula_distribuidor.submit();
}
function VerCalendario(fecha){
	if(fecha==1){
		var FECHA = document.getElementById('fecha_inicio');
		FECHA.focus();
	} else {
		var FECHA2 = document.getElementById('fecha_fin');
		FECHA2.focus();
	}
}
function Validar(formulario){
	var vFecha = document.getElementById('fecha_inicio').value;
	var vPorc = document.getElementById('porc').value;
	if(vFecha.length==0){
		alert("Seleccione una fecha de inicio.");
		return false;
	} 
	if(vPorc==0){
		alert("El Porcentaje debe ser mayor a 0.");
		return false;
	} 
}
</script>

</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php
if (strlen($mensaje)>0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>$mensaje</td></tr>";
	echo $tabla;
endif;
?>
<tr>
	<td>
		<table width="300" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        	<form name="pelicula_distribuidor" method="GET" onSubmit="javascript: return Validar(this)"  action="">
        	<tr bgcolor="#DDDDDD">
				<td colspan="2" height="25" class="css-label-metadata" align="center">Fecha Inicio</td>
                <td colspan="2" height="25" class="css-label-metadata" align="center">Fecha Fin</td>
				<td width="75" height="25" class="css-label-metadata" align="center">Porcentaje</td>
                <td height="25" class="css-label-metadata" align="center">&nbsp;</td>
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="75" height="30" align="center" valign="middle"><input name="fecha_inicio" class="css-campo-metadata-id" id="fecha_inicio" type="text" size="10" readonly></td>
                <td width="22" align="center" valign="middle"><a href="javascript:void(0);"><img src="images/datepicker.png" onClick="VerCalendario(1)" border="0"></a></td>
                <td width="75" height="30" align="center" valign="middle"><input name="fecha_fin" class="css-campo-metadata-id" id="fecha_fin" type="text" size="10" readonly></td>
                <td width="22" align="center" valign="middle"><a href="javascript:void(0);"><img src="images/datepicker.png" onClick="VerCalendario(2)" border="0"></a></td>
				<td width="75" class="css-label-metadata" align="center"><input name="porc" id="porc" type="text" class="css-campo-metadata" size="4" maxlength="3" value="0" align="right" onKeyPress="onNumero()">%</td>
                <td class="css-label-metadata" align="center" valign="middle"><input type="image" name="submit" src="images/add.png" alt="Agregar"></td>
                <input type="hidden" name="acc" value="1">
                <input type="hidden" name="idp" value="<?php echo $idp;?>">
			</tr>
            </form>
			<?php 
				$table = "";
				$c = 0;
				$linea = 0;
				$bgcolor_select = "'#FFCC99'";
				while($campoD = mysql_fetch_object($resultD)):
					$c++;
					if($linea == 0):
						$bgcolor = "'#CCCCCC'";
						$linea = 1;
					else:
						$bgcolor = "'#DDDDDD'";
						$linea = 0;
					endif;
					$table .= "<tr bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') ."> \n \r";
					$table .= "<td height='25' colspan='2' class='css-datos-consulta' align='center' valign='middle'><b>".formato_fecha($campoD->fecha_inicio,2)."</b></td> \n \r";
					$fecha_fin_d = ($campoD->fecha_fin=='0000-00-00') ? "" : formato_fecha($campoD->fecha_fin,2);
					$table .= "<td height='25' colspan='2' class='css-datos-consulta' align='center' valign='middle'><b>".$fecha_fin_d."</b></td> \n \r";
					$table .= "<td class='css-datos-consulta' align='center' valign='middle'>".$campoD->porcentaje."</td> \n \r";
					$table .= "<td class='css-datos-consulta' align='center' valign='middle'><a href='".$modulo."?id=$campoD->id_pelicula_distribuidor&acc=2&idp=$campoD->id_pelicula'><img src='images/del.png' border='0' alt='Eliminar'></a>"."</td> \n \r";
					$table .= "</tr> \n \r";
				endwhile;
				echo $table;
			?>
            <!--	
            <tr bgcolor="#DDDDDD">
				<td colspan="4">&nbsp;</td>
			</tr>-->
		</table>
	</td>
</tr>
</table>
</body>