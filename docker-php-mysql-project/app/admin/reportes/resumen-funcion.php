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
$get = "modulo=resumen-funcion&distribuidor=".$_REQUEST['distribuidor']."&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&sala=$sala&cine=$cine&pelicula=$pelicula&funcion=$funcion&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT
	o.id_cine,
	c.nombre as cine,
	o.id_programacion,
	pr.fecha_programacion as fecha,
	pe.nombre_espanol as pelicula,
	pr.id_pelicula,
	pr.hora_inicio as funcion,
	SUM(o.cantidad_transaccion*dosporuno)  as cantidad_transaccion,
	SUM(o.total_transaccion) as total_transaccion,
	pe.id_distribuidor,
	d.nombre as distribuidor ";
	
$qry .= " FROM tbl_operacion o
	INNER JOIN tbl_programacion pr ON o.id_programacion = pr.id_programacion
	INNER JOIN tbl_pelicula pe ON pe.id_pelicula = pr.id_pelicula 
	INNER JOIN tbl_cine c ON c.id_cine = o.id_cine 
	INNER JOIN tbl_distribuidor d ON d.id_distribuidor = pe.id_distribuidor INNER JOIN tbl_precio tper ON o.id_precio = tper.id_precio ";

$qry .= "WHERE o.status = 1 AND ";
$qry .= "pe.id_distribuidor = $distribuidor AND ";
$qry .= "pr.fecha_programacion >= '$fecha_desde' AND pr.fecha_programacion <= '$fecha_hasta' ";
if($pelicula > 0):
	$qry .= "AND  pe.id_pelicula = $pelicula ";
endif;
if($funcion <> 0):
	switch($funcion):
	
		case DIURNO:
			$qry .= "AND (pr.hora_inicio >= '".DIURNO."' AND pr.hora_inicio < '". MATINEE ."') ";
			break;	
		
		case MATINEE:
			$qry .= "AND (pr.hora_inicio >= '".MATINEE."' AND pr.hora_inicio < '". VESPERTINA ."') ";
			break;	
		
		case VESPERTINA:
			$qry .= "AND (pr.hora_inicio >= '".VESPERTINA."' AND pr.hora_inicio < '". INTERMEDIA ."') ";
			break;
			
		case INTERMEDIA:
			$qry .= "AND (pr.hora_inicio >= '".INTERMEDIA."' AND pr.hora_inicio < '". NOCHE ."') ";
			break;
		
		case NOCHE:
			$qry .= "AND (pr.hora_inicio >= '".NOCHE."' AND pr.hora_inicio < '". MEDIANOCHE ."') ";
			break;
			
		case MEDIANOCHE:
			$qry .= "AND pr.hora_inicio >= '".INTERMEDIA."' ";
			break;
			
	endswitch;
endif;

$qry .= "GROUP BY o.id_cine,
o.id_programacion,
pr.fecha_programacion,
pr.id_pelicula,
pr.hora_inicio ";

$qry .= "ORDER BY pe.nombre_espanol,
pr.hora_inicio, pr.fecha_programacion ";

//echo $qry;
$result = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
?>
<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
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
		
    <td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen de Liquidacion de Pelicula</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$pelicula_actual = "";
		$funcion_actual = "";
		
		$boletos_funcion = 0;
		$monto_funcion = 0;
		
		$boletos_pelicula = 0;
		$monto_pelicula = 0;
			
		
		$sala_actual = 0;
		$funcion_nombre = '';
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		while($campo=mysql_fetch_object($result)):
		$distribuidor = $campo->distribuidor;
			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				$table .= "</tr> \n";
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>Distribuidor: <b>".$campo->distribuidor."</b></td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO PELICULA */
			if($pelicula_actual != $campo->pelicula):
								
				//Imprimo el total de la función anterior en caso de que cambie de pelicula
				if($boletos_funcion > 0):
					$table .= "<tr> \n";
					$table .= "<td width='40%' align='left' class='info'>Total Funcion <b>$funcion_actual</b></td> \n";
					$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($boletos_funcion, 0, ',', '.')."</i></td> \n";
					$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($monto_funcion, 2, ',', '.')."</i></td> \n";
					$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
					$table .= "</tr> \n";	
					$table .= "<tr> \n";
					$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
					$table .= "</tr> \n";
				
					$monto_funcion = 0;
					$boletos_funcion = 0;
				endif;
				
				//Imprimo el total de la pelicula
				if($boletos_pelicula > 0):
					$table .= "<tr> \n";
					$table .= "<td width='40%' align='left' class='info'>Total Pelicula <b>$pelicula_actual</b></td> \n";
					$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($boletos_pelicula, 0, ',', '.')."</i></td> \n";
					$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($monto_pelicula, 2, ',', '.')."</i></td> \n";
					$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
					$table .= "</tr> \n";	
					$table .= "<tr> \n";
					$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
					$table .= "</tr> \n";
				
					$monto_pelicula = 0;
					$boletos_pelicula = 0;
				endif;			
				$pelicula_actual = $campo->pelicula;
							
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>Pelicula: <b>$pelicula_actual</b></td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO FUNCION */
			if($campo->funcion >= DIURNO && $campo->funcion < MATINEE):
				$funcion_nombre = "DIURNO";
			endif;
			
			if($campo->funcion >= MATINEE && $campo->funcion < VESPERTINA):
				$funcion_nombre = "MATINEE";
			endif;
			
			if($campo->funcion >= VESPERTINA && $campo->funcion < INTERMEDIA):
				$funcion_nombre = "VESPERTINA";
			endif;
			
			if($campo->funcion >= INTERMEDIA && $campo->funcion < NOCHE):
				$funcion_nombre = "INTERMEDIA";
			endif;

			if($campo->funcion >= NOCHE && $campo->funcion < MEDIANOCHE):
				$funcion_nombre = "NOCHE";
			endif;
				
			if($campo->funcion >= MEDIANOCHE):
				$funcion_nombre = "MEDIANOCHE";
			endif;	
			
			if($funcion_actual != $funcion_nombre):
				//$funcion_nombre = date("h:i a", strtotime($campo->funcion));
				if($boletos_funcion > 0):
					$table .= "<tr> \n";
					$table .= "<td width='40%' align='left' class='info'>Total Funcion <b>$funcion_actual</b></td> \n";
					$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($boletos_funcion, 0, ',', '.')."</i></td> \n";
					$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($monto_funcion, 2, ',', '.')."</i></td> \n";
					$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
					$table .= "</tr> \n";	
					$table .= "<tr> \n";
					$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
					$table .= "</tr> \n";
				
					$monto_funcion = 0;
					$boletos_funcion = 0;
				endif;
				$funcion_actual = $funcion_nombre;
				
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>Funcion: <i>$funcion_nombre</i></td> \n";
				$table .= "</tr> \n";
				
				$table .= "<tr> \n";
				$table .= "<td align='left' colspan='4'><table width='100%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
				$table .= "<td width='40%' align='left' class='info'><b>Fecha Exhibicion</b></td> \n";
				$table .= "<td width='25%' align='center' class='info'><b>Total Expectadores</b></td> \n";
				$table .= "<td width='25%' align='center' class='info'><b>Monto Bruto</b></td> \n";
				$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
				$table .= "</tr> \n </table> \n </td> \n";
				$table .= "</tr> \n";
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td width='40%' align='left' class='info'>".htmlentities($campo->fecha) ."</td> \n";
			$table .= "<td width='25%' align='center' class='info'>".number_format($campo->cantidad_transaccion, 0, ',', '.')."</td> \n";
			$table .= "<td width='25%' align='center' class='info'>".number_format($campo->total_transaccion, 2, ',', '.')."</td> \n";
			$table .= "<td width='10%' align='right' class='info'>&nbsp;</td> \n";
			$table .= "</tr> \n";
			
			$boletos_funcion = $boletos_funcion + $campo->cantidad_transaccion;
			$monto_funcion = $monto_funcion + $campo->total_transaccion;
			
			$boletos_pelicula = $boletos_pelicula + $campo->cantidad_transaccion;
			$monto_pelicula = $monto_pelicula + $campo->total_transaccion;
			
			$boletos_total = $boletos_total + $campo->cantidad_transaccion;
			$monto_total = $monto_total + $campo->total_transaccion;
		endwhile;
		
		//Imprimo el total de la función anterior en caso de que cambie de pelicula
		if($boletos_funcion > 0):
			$table .= "<tr> \n";
			$table .= "<td width='40%' align='left' class='info'>Total Funcion <b>$funcion_actual</b></td> \n";
			$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($boletos_funcion, 0, ',', '.')."</i></td> \n";
			$table .= "<td width='25%' align='center' class='info-total'><i>".number_format($monto_funcion, 2, ',', '.')."</i></td> \n";
			$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
			$table .= "</tr> \n";	
			$table .= "<tr> \n";
			$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
			$table .= "</tr> \n";
		
			$monto_funcion = 0;
			$boletos_funcion = 0;
		endif;
		
		//Imprimo el total de la pelicula
		if($boletos_pelicula > 0):
			$table .= "<tr> \n";
			$table .= "<td width='40%' align='left' class='info'>Total Pelicula <b>$pelicula_actual</b></td> \n";
			$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($boletos_pelicula, 0, ',', '.')."</i></td> \n";
			$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($monto_pelicula, 2, ',', '.')."</i></td> \n";
			$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
			$table .= "</tr> \n";	
			$table .= "<tr> \n";
			$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
			$table .= "</tr> \n";
		
			$monto_pelicula = 0;
			$boletos_pelicula = 0;
		endif;			
		
		//Imprimo Total General Distribuidor
		$table .= "<tr> \n";
		$table .= "<td width='40%' align='left' class='info'>Total Distribuidor <b>".$distribuidor."</b></td> \n";
		$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($boletos_total, 0, ',', '.')."</i></td> \n";
		$table .= "<td width='25%' align='center' class='info-total2'><i>".number_format($monto_total, 2, ',', '.')."</i></td> \n";
		$table .= "<td width='10%' align='right' class='class='info'>&nbsp;</td> \n";
		$table .= "</tr> \n";	
		$table .= "<tr> \n";
		$table .= "<td height='15' colspan='4'>&nbsp;</td> \n";
		$table .= "</tr> \n";
		
		echo $table;
	else:
		$table = "<tr> \n";
		$table .= "<td align='left' height='21' class='mensaje-consulta'>No hay datos para los rangos seleccionados</td> \n";
		$table .= "</tr> \n";
		
		echo $table;
	endif;
	?>	
	</table></td>
	</tr>
</table>