<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;


$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=resumen-general&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";

$qry = "SELECT 	o.id_cine, 
		c.nombre as cine, 
		pr.fecha_programacion as fecha, 
		pr.id_sala,
		p.foncine,
		CASE WHEN pr.hora_inicio <= '16:30:00' AND pe.porcentaje_distribuidor > 40
			THEN 40
			ELSE pe.porcentaje_distribuidor END as porcentaje_distribuidor,
		SUM(o.cantidad_transaccion) as cantidad_transaccion, 
		SUM(o.total_transaccion) as total_transaccion  ";

$qry .= "FROM tbl_operacion o 
		INNER JOIN tbl_programacion pr ON o.id_programacion = pr.id_programacion 
		INNER JOIN tbl_pelicula pe ON pe.id_pelicula = pr.id_pelicula 
		INNER JOIN tbl_cine c ON c.id_cine = o.id_cine 
		INNER JOIN tbl_precio p ON o.id_precio = p.id_precio ";

$qry .= "WHERE 	o.status = 1 
AND 	pr.fecha_programacion >= '$fecha_desde' 
AND 	pr.fecha_programacion <= '$fecha_hasta' 
AND		o.total_transaccion <> 0 ";

$qry .= "GROUP BY o.id_cine, 
		pr.id_sala,
		pr.fecha_programacion, 
		p.foncine,
		pe.porcentaje_distribuidor ";

$qry .= "ORDER BY pr.id_sala,
		pr.fecha_programacion,
		p.foncine ";

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
		
    <td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen General</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		
		$total_bruto = 0;
		$total_porc_municipal = 0;
		$total_municipal = 0;
		$total_porc_fronpocine = 0;
		$total_fronpocine = 0;
		$total_neto = 0;
		$total_porc_distribuidor = 0;
		$total_distribuidor = 0;
		$total_registros = 0;
		
		
		$sala_bruto = 0;
		$sala_porc_municipal = 0;
		$sala_municipal = 0;
		$sala_porc_fronpocine = 0;
		$sala_fronpocine = 0;
		$sala_neto = 0;
		$sala_porc_distribuidor = 0;
		$sala_distribuidor = 0;
		$sala_registros = 0;
		
		$sala_actual = 0;
		
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
				$table .= "<td align='left' colspan='4'><table width='100%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
				$table .= "<td width='14%' align='center' class='info'><b>Fecha Exhibicion</b></td> \n";
				$table .= "<td width='15%' align='center' class='info'><b>Monto Bruto</b></td> \n";
				$table .= "<td width='15%' align='center' class='info'><b>Base Imponible</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Imp. Municipal</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Fonprocine</b></td> \n";
				$table .= "<td width='14%' align='center' class='info'><b>Neto</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Distribuidor</b></td> \n </tr>\n";
				
				$table .= "<tr> \n <td width='20%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='16%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='6%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='10%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='6%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='10%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='16%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='6%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='10%' align='center' class='info'><b>Monto</b></td> \n";;

				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			if($sala_actual != $campo->id_sala):
				
				if($sala_registros > 0):
					$table .= "<tr> \n";
					$table .= "<td height='30' width='20%' align='right' class='total2'>Total Sala $cine_actual - $sala_actual: </td> \n";
					$table .= "<td width='16%' align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
					//Impuesto Municipal
					$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_municipal/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
					//Fonprocine
					$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_fronpocine/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
					//Neto
					$table .= "<td width='16%' align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
					//Distribuidor
					$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_distribuidor/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					
					$sala_bruto = 0;
					$sala_porc_municipal = 0;
					$sala_municipal = 0;
					$sala_porc_fronpocine = 0;
					$sala_fronpocine = 0;
					$sala_neto = 0;
					$sala_porc_distribuidor = 0;
					$sala_distribuidor = 0;
					$sala_registros = 0;
				endif;
				$sala_actual = $campo->id_sala;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>SALA: <b>$cine_actual - $sala_actual</b></td> \n";
				$table .= "</tr> \n";
				
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td width='20%' align='center' class='info'>".formato_fecha(htmlentities($campo->fecha), 2)."</td> \n";
			$table .= "<td width='16%' align='right' class='info'>".number_format($campo->total_transaccion, 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td width='6%' align='right' class='info'>".number_format($campo->foncine, 0, ',', '.')."</td> \n";
			$monto_impuesto = ($campo->total_transaccion * $campo->foncine) / 100;
			$table .= "<td width='10%' align='right' class='info'>".number_format($monto_impuesto, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td width='6%' align='right' class='info'>".number_format('5',0, ',', '.')."</td> \n";
			$monto_fonprocine = (($campo->total_transaccion - $monto_impuesto) * 5) / 100;
			$table .= "<td width='10%' align='right' class='info'>".number_format($monto_fonprocine, 2, ',', '.')."</td> \n";
			//Neto
			$neto = $campo->total_transaccion - $monto_impuesto - $monto_fonprocine;
			$table .= "<td width='16%' align='right' class='info'>".number_format($neto, 2, ',', '.')."</td> \n";
			//Distribuidor
			$table .= "<td width='6%' align='right' class='info'>".number_format($campo->porcentaje_distribuidor, 0, ',', '.')."</td> \n";
			$monto_distribuidor = ($neto * $campo->porcentaje_distribuidor) / 100;
			$table .= "<td width='10%' align='right' class='info'>".number_format($monto_distribuidor, 2, ',', '.')."</td> \n";
			
			$table .= "</tr> \n";
			
			
			$sala_bruto = $sala_bruto + $campo->total_transaccion;
			$sala_porc_municipal = $sala_porc_municipal + $campo->foncine;
			$sala_municipal = $sala_municipal + $monto_impuesto;
			$sala_porc_fronpocine = $sala_porc_fronpocine + 5;
			$sala_fronpocine = $sala_fronpocine + $monto_fonprocine ;
			$sala_neto = $sala_neto + $neto;
			$sala_porc_distribuidor = $sala_porc_distribuidor + $campo->porcentaje_distribuidor;
			$sala_distribuidor = $sala_distribuidor + $monto_distribuidor;
			$sala_registros++;
			
			$total_bruto = $total_bruto + $campo->total_transaccion;
			$total_porc_municipal = $total_porc_municipal + $campo->foncine;
			$total_municipal = $total_municipal + $monto_impuesto;
			$total_porc_fronpocine = $total_porc_fronpocine + 5;
			$total_fronpocine = $total_fronpocine + $monto_fonprocine ;
			$total_neto = $total_neto + $neto;
			$total_porc_distribuidor = $total_porc_distribuidor + $campo->porcentaje_distribuidor;
			$total_distribuidor = $total_distribuidor + $monto_distribuidor;
			$total_registros++;

		endwhile;
		
		/* ENCABEZADO SALA */
		if($sala_registros > 0):
			$table .= "<tr> \n";
			$table .= "<td height='30' width='20%' align='right' class='total2'>Total Sala $cine_actual - $sala_actual: </td> \n";
			$table .= "<td width='16%' align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_municipal/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_fronpocine/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
			//Neto
			$table .= "<td width='16%' align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
			//Distribuidor
			$table .= "<td width='6%' align='right' class='total2'>".number_format($sala_porc_distribuidor/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='10%' align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;
		
		//TOTAL GENERAL
		$table .= "<tr> \n";
		$table .= "<td height='30' width='20%' align='right' class='total2'>Total Cine $cine_actual: </td> \n";
		$table .= "<td width='16%' align='right' class='info-total2 '>".number_format($total_bruto, 2, ',', '.')."</td> \n";
		//Impuesto Municipal
		$table .= "<td width='6%' align='right' class='info-total2 '>".number_format($total_porc_municipal/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='10%' align='right' class='info-total2 '>".number_format($total_municipal, 2, ',', '.')."</td> \n";
		//Fonprocine
		$table .= "<td width='6%' align='right' class='info-total2 '>".number_format($total_porc_fronpocine/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='10%' align='right' class='info-total2 '>".number_format($total_fronpocine, 2, ',', '.')."</td> \n";
		//Neto
		$table .= "<td width='16%' align='right' class='info-total2 '>".number_format($total_neto, 2, ',', '.')."</td> \n";
		//Distribuidor
		$table .= "<td width='6%' align='right' class='info-total2 '>".number_format($total_porc_distribuidor/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='10%' align='right' class='info-total2 '>".number_format($total_distribuidor, 2, ',', '.')."</td> \n";
		$table .= "</tr> \n";
		
		$table .= "</tr> \n </table> \n </td> \n";
		
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