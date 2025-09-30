<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;
$funcion = isset($_REQUEST['funcion']) ? $_REQUEST['funcion'] : 0;
$distribuidor = isset($_REQUEST['distribuidor']) ? $_REQUEST['distribuidor'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=resumen-distribuidor&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&pelicula=$pelicula&funcion=$funcion&distribuidor=$distribuidor&salida=";
//phpinfo();

//Busco los porcentajes
$qry = "SELECT municipal, fomprocine, tipo_calculo FROM tbl_configuracion ";
$result = mysql_query($qry);
if($datos = mysql_fetch_object($result)):
	//$municipal = $datos->municipal;
	$fomprocine = $datos->fomprocine;
	$tipo_calculo = $datos->tipo_calculo;
endif;

//echo $fecha_actual ."<br>";
$sql = "SELECT 	o.id_programacion, pe.nombre_espanol as pelicula, d.nombre as distribuidor, p.id_cine, c.nombre as cine, p.id_sala, s.nombre as sala, ";
$sql .= "p.id_pelicula, o.porcentaje_distribuidor, p.fecha_programacion, p.hora_inicio, SUM(o.cantidad_transaccion*dosporuno) as boletos, SUM(o.total_transaccion) as monto_bruto, pr.foncine as municipal ";
$sql .= "FROM tbl_operacion o INNER JOIN tbl_programacion p ON o.id_programacion = p.id_programacion ";
$sql .= "INNER JOIN tbl_precio pr ON o.id_precio = pr.id_precio ";
$sql .= "INNER JOIN tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula ";
$sql .= "INNER JOIN tbl_cine c ON p.id_cine = c.id_cine ";
$sql .= "INNER JOIN tbl_sala s ON p.id_sala = s.id_sala ";
$sql .= "INNER JOIN tbl_distribuidor d ON pe.id_distribuidor = d.id_distribuidor ";
$sql .= "WHERE o.status = 1 AND ";
$sql .= "p.fecha_programacion >= '$fecha_desde' AND p.fecha_programacion <= '$fecha_hasta' ";
if($pelicula > 0):
	$sql .= "AND  pe.id_pelicula = $pelicula ";
endif;
if($distribuidor > 0):
	$sql .= "AND  pe.id_distribuidor = $distribuidor ";
endif;

if($funcion <> 0):
	switch($funcion):
	
		case MATINEE:
			$sql .= "AND (p.hora_inicio < '". VESPERTINA ."') ";
			break;	
		
		case VIN:
			$sql .= "AND (p.hora_inicio >= '".VESPERTINA."' AND p.hora_inicio < '". VIN ."') ";
			break;
			
	endswitch;
endif;
$sql .= "GROUP BY p.id_cine, d.nombre, o.id_programacion, pe.nombre_espanol, c.nombre, p.id_sala, s.nombre, p.id_pelicula, pe.porcentaje_distribuidor, p.fecha_programacion, p.hora_inicio ";
$sql .= "ORDER BY p.id_cine, d.nombre, c.nombre, p.id_sala, pe.nombre_espanol, p.fecha_programacion, p.hora_inicio ";

//echo $sql;
$result = mysql_query($sql) or die("Error en Consulta: " . mysql_error());
?>
<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
.encabezado-reporte {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	background-color:#666666;
	color:#FFFFFF;
}
.reporte {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color:#666666;
}
</style>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<?php
	if($salida == 'pan'):?>
		<tr>
			<td width="100%" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')" class="imprimir">Imprimir</a>&nbsp;&nbsp;</td>
            <td width="30" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')"><img src="images/excel.jpg" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')" class="imprimir">Excel</a></td>
		</tr>
	<?php endif;?>
	<tr>
		
    <td align="left" colspan="4" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen Distribuidor</td>
	</tr>
	<tr>
		<td colspan="4"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$pelicula_actual = "";
		$sala_actual = "";
		$distribuidor_actual = "";
		$porcentaje_actual = "";
		$fecha_actual = "";
		$funcion_nombre = '';
		$total_pelicula_funcion = 0;
		$total_distribuidor = 0;
		$total_sala = 0;
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='9' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		
		if($funcion <> 0):
			switch($funcion):
			
				case MATINEE:
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' colspan='9' class='pelicula-nombre'>FUNCIONES MATINEE</td> \n";
					$table .= "</tr> \n";
					$funcion_nombre = "Matinee";
					break;	
				
				case VIN:
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' colspan='9' class='pelicula-nombre'>FUNCIONES V-I-N</td> \n";
					$table .= "</tr> \n";
					$funcion_nombre = "V-I-N";
					break;
				
				default:
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' colspan='9' class='pelicula-nombre'>TODAS LAS FUNCIONES</td> \n";
					$table .= "</tr> \n";
					$funcion_nombre = "M-V-I-N";
					break;
					
			endswitch;
		endif;
		
		
		
		while($campo=mysql_fetch_object($result)):
			if($cine_actual <> $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='5' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				$table .= "</tr> \n";
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>PELICULA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>DIA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>FUNCION</td> \n";
				//$table .= "<td align='center' height='21' class='encabezado-reporte'>BOLETOS</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>ENT. BRUTA</td> \n";
				//$table .= "<td align='center' height='21' class='encabezado-reporte'>IMP.</td> \n";
				//$table .= "<td align='center' height='21' class='encabezado-reporte'>FONPROCINE</td> \n";
				//$table .= "<td align='center' height='21' class='encabezado-reporte'>ENT. NETA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>% DIST</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte'>PARTICIPACIÓN</td> \n";
				$table .= "</tr> \n";
			endif;

			//echo $distribuidor_actual . "-". $campo->distribuidor ."<br>";
			//IMPRIMO DISTRIBUIDOR
			if($distribuidor_actual <> $campo->distribuidor):
				if(strlen($fecha_actual) > 0):
					
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' class='reporte'>".$pelicula_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$fecha_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($monto_bruto, 2, ',', '.')."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$porcentaje_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($total_pelicula_funcion, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_pelicula_funcion = 0;
					$monto_bruto = 0;
					
					$fecha_actual = $campo->fecha_programacion;
				endif;
				
				if($total_sala > 0):
					$table .= "<tr> \n";
					$table .= "<td align='right' height='35' colspan='6' class='pelicula-nombre'>TOTAL Sala: <b>$cine_actual $sala_actual</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($total_sala, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_sala = 0;
				endif;
				
				if($total_distribuidor > 0):
					$table .= "<tr> \n";
					$table .= "<td align='right' height='35' colspan='6' class='pelicula-nombre'>TOTAL Distribuidor: <b>$distribuidor_actual</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($total_distribuidor, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_distribuidor = 0;
				endif;

				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'>Distribuidor: <b>$campo->distribuidor</b></td> \n";
				$table .= "</tr> \n";
				$distribuidor_actual = $campo->distribuidor;
				
				if($sala_actual <> $campo->sala && strlen($sala_actual)>0):
					
				
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>$cine_actual $campo->sala</b></td> \n";
					$table .= "</tr> \n";
					$sala_actual = $campo->sala;
				endif;

			endif;

			if($sala_actual <> $campo->sala):
				if(strlen($fecha_actual) > 0):
					//$funcion_nombre = "M-V-I-N";
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' class='reporte'>".$pelicula_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$fecha_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($monto_bruto, 2, ',', '.')."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$porcentaje_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($total_pelicula_funcion, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_pelicula_funcion = 0;
					$monto_bruto = 0;
					$fecha_actual = $campo->fecha_programacion;
				endif;
				if($total_sala > 0):
					$table .= "<tr> \n";
					$table .= "<td align='right' height='35' colspan='6' class='pelicula-nombre'>TOTAL Sala: <b>$cine_actual $sala_actual</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($total_sala, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_sala = 0;
				endif;
				
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>$cine_actual $campo->sala</b></td> \n";
				$table .= "</tr> \n";
				$sala_actual = $campo->sala;
			endif;
			
			//echo $pelicula_actual . "-". $campo->pelicula . "-" . strlen($pelicula_actual) ."<br>";
			if($fecha_actual <> $campo->fecha_programacion):
				if(strlen($fecha_actual) > 0):
					//$funcion_nombre = "M-V-I-N";
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' class='reporte'>".$pelicula_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$fecha_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($monto_bruto, 2, ',', '.')."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$porcentaje_actual."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($total_pelicula_funcion, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_pelicula_funcion = 0;
					$monto_bruto = 0;
				endif;
			endif;
			
			//$table .= "<tr> \n";
			//$table .= "<td colspan='5' class='reporte'>".number_format($campo->monto_bruto, 2, ',', '.')."</td> \n";
			//$table .= "</tr> \n";
			
			
			$monto_bruto = $monto_bruto + $campo->monto_bruto;
			$base_imponible = $campo->monto_bruto / (($campo->municipal / 100) + 1);
			$monto_municipal = $campo->monto_bruto - ($campo->monto_bruto * $campo->municipal / 100);
			$monto_neto =  $base_imponible - ($base_imponible * $fomprocine / 100);
			$total_pelicula_funcion = $total_pelicula_funcion + ($monto_neto * $campo->porcentaje_distribuidor / 100);
			$pelicula_actual = $campo->pelicula;
			$fecha_actual = $campo->fecha_programacion;
			if($funcion == MATINEE):
				$porcentaje_actual = 40;
			else:
				$porcentaje_actual = $campo->porcentaje_distribuidor;
			endif;
			$total_distribuidor = $total_distribuidor + ($monto_neto * $campo->porcentaje_distribuidor / 100);
			$total_sala = $total_sala + ($monto_neto * $campo->porcentaje_distribuidor / 100);
			//echo $monto_bruto . " - " . $monto_municipal . " - " . $monto_neto . " - " . $total_pelicula_funcion . "<br>";
		endwhile;
		if($fecha_actual <> $campo->fecha_programacion):
			if(strlen($fecha_actual) > 0):
				//$funcion_nombre = "M-V-I-N";
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' class='reporte'>".$pelicula_actual."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$fecha_actual."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".number_format($monto_bruto, 2, ',', '.')."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$porcentaje_actual."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".number_format($total_pelicula_funcion, 2, ',', '.')."</td> \n";
				$table .= "</tr> \n";
				$total_pelicula_funcion = 0;
			endif;
		endif;
		if($total_sala > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right' height='35' colspan='6' class='pelicula-nombre'>TOTAL Sala: <b>$cine_actual $sala_actual</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($total_sala, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;
		if($total_distribuidor > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right' height='35' colspan='6' class='pelicula-nombre'>TOTAL Distribuidor: <b>$distribuidor_actual</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($total_distribuidor, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;
	else:
		$table = "<tr> \n";
		$table .= "<td align='left' height='21' class='mensaje-consulta'>No hay datos para los rangos seleccionados</td> \n";
		$table .= "</tr> \n";
	endif;
	echo $table;
	?>	
	</table></td>
	</tr>
</table>