<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$distribuidor = isset($_REQUEST['distribuidor']) ? $_REQUEST['distribuidor'] : 0;


$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=resumen-general-u&fecha1=".$_REQUEST['fecha1']."&distribuidor=".$_REQUEST['distribuidor']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";

$qry = "SELECT	c.nombre as cine, 
				pr.fecha_programacion as fecha, 
				sa.nombre as nombre_sala, 
				pe.nombre_espanol, 
				CASE WHEN pr.hora_inicio <= '16:30:00'
				THEN	'MATINEE'
				ELSE		'VIN'
				END as tipo_funcion, 
				op.foncine,op.municipal,op.iva,
				CASE WHEN pr.hora_inicio <= '16:30:00' 
				THEN 40
				ELSE (case when (SELECT
				tbl_pelicula_distribuidor.porcentaje
				FROM
				tbl_pelicula_distribuidor
				WHERE
				tbl_pelicula_distribuidor.id_pelicula=pr.id_pelicula and
				tbl_pelicula_distribuidor.fecha_inicio<=pr.fecha_programacion AND
				tbl_pelicula_distribuidor.fecha_fin>=pr.fecha_programacion) is null then 40 ELSE
				(SELECT
				tbl_pelicula_distribuidor.porcentaje
				FROM
				tbl_pelicula_distribuidor
				WHERE
				tbl_pelicula_distribuidor.id_pelicula=pr.id_pelicula and
				tbl_pelicula_distribuidor.fecha_inicio<=pr.fecha_programacion AND
				tbl_pelicula_distribuidor.fecha_fin>=pr.fecha_programacion) end) END  as porcentaje_distribuidor, ";
$qry .= "SUM(op.cantidad_transaccion*dosporuno) as cantidad_transaccion, SUM(op.total_transaccion) as total_transaccion  ";
$qry .= "FROM tbl_operacion op INNER JOIN tbl_programacion pr ON op.id_programacion = pr.id_programacion ";
$qry .= "INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula ";
$qry .= "INNER JOIN tbl_sala sa ON sa.id_sala = pr.id_sala ";
$qry .= "INNER JOIN tbl_precio p ON op.id_precio = p.id_precio ";
$qry .= "INNER JOIN tbl_cine c ON c.id_cine = op.id_cine ";
$qry .= "WHERE 	op.status = 1 
		AND 	p.costo > 0
		AND 	pr.fecha_programacion >= '$fecha_desde' 
		AND 	pr.fecha_programacion <= '$fecha_hasta' 
		AND		op.total_transaccion <> 0 ";
if($distribuidor > 0):
	$qry .= "AND  pe.id_distribuidor = $distribuidor ";
endif;
		
$qry .= "GROUP BY op.id_cine, sa.nombre, pe.nombre_espanol, pr.fecha_programacion, tipo_funcion, op.foncine,op.municipal,op.iva ";
$qry .= "ORDER BY sa.nombre, pe.nombre_espanol, pr.fecha_programacion, op.foncine,op.municipal,op.iva ";
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
		
    <td align="left" colspan="4" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen General Distribuidor</td>
	</tr>
	<tr>
		<td colspan="4"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$pelicula_actual = "";
		
		$total_bruto = 0;
		$total_cantidad = 0;
		$total_porc_municipal = 0;
		$total_municipal = 0;
		$total_porc_fronpocine = 0;
		$total_fronpocine = 0;
		$total_neto = 0;
		$total_porc_distribuidor = 0;
		$total_distribuidor = 0;
		$total_registros = 0;
		$total_base = 0;
		
		
		$total_iva = 0;
		
		$sala_iva = 0;
		$sala_bruto = 0;
		$sala_cantidad = 0;
		$sala_porc_municipal = 0;
		$sala_municipal = 0;
		$sala_porc_fronpocine = 0;
		$sala_fronpocine = 0;
		$sala_neto = 0;
		$sala_porc_distribuidor = 0;
		$sala_distribuidor = 0;
		$sala_registros = 0;
		$sala_base = 0;
		
		$sala_actual = 0;
		
		$pelicula_registros = 0;
		$funcion_actual = "";
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		while($campo=mysql_fetch_object($result)):
		//$distribuidor = $campo->distribuidor;
			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				$table .= "</tr> \n";
				
				$table .= "<tr> \n";
				$table .= "<td align='left' colspan='4'><table width='100%' align='left' cellspading='1' cellspacing='1' border='0'> \n <tr> \n";
				$table .= "<td width='8%' align='center' class='info'><b>Fecha Exhibicion</b></td> \n";
				$table .= "<td width='15%' align='center' class='info'><b>Pelicula</b></td> \n";
				$table .= "<td width='6%' align='center' class='info'><b>Sala</b></td> \n";
				$table .= "<td width='10%' align='center' class='info'><b>Función</b></td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>Espectadores</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Monto Bruto</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Base Imponible</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>% Iva</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>Iva</b></td> \n";
				$table .= "<td width='12%' align='center' class='info' colspan='2'><b>Imp. Municipal</b></td> \n";
				$table .= "<td width='12%' align='center' class='info' colspan='2'><b>Fonprocine</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Neto</b></td> \n";
				$table .= "<td width='12%' align='center' class='info' colspan='2'><b>Distribuidor</b></td> \n </tr>\n";
				
				$table .= "<tr> \n ";
				$table .= "<td width='8%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='15%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='6%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='10%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='5%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='8%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='8%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='9%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='8%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='7%' align='center' class='info'><b>Monto</b></td> \n";;

				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			if($pelicula_actual != $campo->nombre_espanol):
				if($pelicula_registros > 0):
					$table .= "<tr> \n";
					$table .= "<td height='30' align='right' class='total2' colspan='4'> &nbsp;</td> \n";
					$table .= "<td align='center' class='total2'>".number_format($sala_cantidad, 0, ',', '.')."</td> \n";
					$table .= "<td align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
					
					//Base Imponible
					$table .= "<td align='right' class='total2'>".number_format($sala_base, 2, ',', '.')."</td> \n";
					$table .= "<td width='12%' align='right' class='total2'>".number_format(16, 2, ',', '.')."</td> \n";
					$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_iva, 2, ',', '.')."</td> \n";
					//Impuesto Municipal
					$table .= "<td align='right' class='total2'>".number_format($sala_porc_municipal/$pelicula_registros, 2, ',', '.')."</td> \n";
					$table .= "<td align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
					//Fonprocine
					$table .= "<td align='right' class='total2'>".number_format($sala_porc_fronpocine/$pelicula_registros, 2, ',', '.')."</td> \n";
					$table .= "<td align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
					//Neto
					$table .= "<td align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
					//Distribuidor
					$table .= "<td align='right' class='total2'>".number_format($sala_porc_distribuidor/$pelicula_registros, 2, ',', '.')."</td> \n";
					$table .= "<td align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					
					$sala_iva = 0;
					$sala_bruto = 0;
					$sala_base = 0;
					$sala_cantidad = 0;
					$sala_porc_municipal = 0;
					$sala_municipal = 0;
					$sala_porc_fronpocine = 0;
					$sala_fronpocine = 0;
					$sala_neto = 0;
					$sala_porc_distribuidor = 0;
					$sala_distribuidor = 0;
					$pelicula_registros = 0;
					$funcion_actual = "";
				endif;
				//$sala_actual = $campo->id_sala;
				$pelicula_actual = $campo->nombre_espanol;
				//$table .= "<tr> \n";
				//$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>SALA: <b>$cine_actual - $sala_actual</b></td> \n";
				//$table .= "</tr> \n";
				
			endif;
									
			$table .= "<tr> \n";
			$table .= "<td align='center' class='info'>".formato_fecha(htmlentities($campo->fecha), 2)."</td> \n";
			$table .= "<td align='center' class='info'>".$campo->nombre_espanol."</td> \n";
			$table .= "<td align='center' class='info'>".$campo->nombre_sala."</td> \n";
			$table .= "<td align='center' class='info'>".$campo->tipo_funcion."</td> \n";
			$table .= "<td align='center' class='info'>".number_format($campo->cantidad_transaccion, 0, ',', '.')."</td> \n";
			$table .= "<td align='right' class='info'>".number_format($campo->total_transaccion, 2, ',', '.')."</td> \n";
			//Base Imponible
			$base_imponible = ($campo->total_transaccion / (($campo->foncine / 100) + 1));
			$base_imponible=($campo->total_transaccion*100)/(100+$campo->municipal+$campo->iva);
			$table .= "<td align='right' class='info'>".number_format($base_imponible, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->iva, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format(($campo->iva*$base_imponible/100), 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td align='right' class='info'>".number_format($campo->municipal, 0, ',', '.')."</td> \n";
			$monto_impuesto = ($base_imponible * $campo->municipal) / 100;
			$table .= "<td align='right' class='info'>".number_format($monto_impuesto, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td align='right' class='info'>".number_format($campo->foncine,2, ',', '.')."</td> \n";
			$monto_fonprocine = (($base_imponible)  * $campo->foncine) / 100;
			$table .= "<td align='right' class='info'>".number_format($monto_fonprocine, 2, ',', '.')."</td> \n";
			//Neto
			$neto = $base_imponible - $monto_fonprocine;
			$table .= "<td align='right' class='info'>".number_format($neto, 2, ',', '.')."</td> \n";
			//Distribuidor
			$table .= "<td align='right' class='info'>".number_format($campo->porcentaje_distribuidor, 0, ',', '.')."</td> \n";
			$monto_distribuidor = ($neto * $campo->porcentaje_distribuidor) / 100;
			$table .= "<td align='right' class='info'>".number_format($monto_distribuidor, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
			
			$base=$base_imponible;
			$sala_iva = $sala_iva+ $campo->iva*$base_imponible/100;
			
			$sala_cantidad = $sala_cantidad + $campo->cantidad_transaccion;
			$sala_base = $sala_base + $base_imponible;
			$sala_bruto = $sala_bruto + $campo->total_transaccion;
			$sala_porc_municipal = $sala_porc_municipal + $campo->municipal;
			$sala_municipal = $sala_municipal + $monto_impuesto;
			$sala_porc_fronpocine = $sala_porc_fronpocine +  $campo->foncine;
			$sala_fronpocine = $sala_fronpocine + $monto_fonprocine ;
			$sala_neto = $sala_neto + $neto;
			$sala_porc_distribuidor = $sala_porc_distribuidor + $campo->porcentaje_distribuidor;
			$sala_distribuidor = $sala_distribuidor + $monto_distribuidor;
			$sala_registros++;
			$pelicula_registros++;
			
			$total_cantidad = $total_cantidad + $campo->cantidad_transaccion;
			$total_base = $total_base + $base_imponible;
			$total_bruto = $total_bruto + $campo->total_transaccion;
			$total_porc_municipal = $total_porc_municipal + $campo->municipal;
			$total_municipal = $total_municipal + $monto_impuesto;
			$total_porc_fronpocine = $total_porc_fronpocine +  $campo->foncine;
			$total_fronpocine = $total_fronpocine + $monto_fonprocine ;
			$total_neto = $total_neto + $neto;
			$total_porc_distribuidor = $total_porc_distribuidor + $campo->porcentaje_distribuidor;
			$total_distribuidor = $total_distribuidor + $monto_distribuidor;
			$total_registros++;
		endwhile;
		
		/* TOTAL PELICULA */
		if($pelicula_registros > 0):
			$table .= "<tr> \n";
			$table .= "<td height='30' width='20%' align='right' class='total2' colspan='4'>&nbsp;</td> \n";
			$table .= "<td align='center' class='info'>".number_format($sala_cantidad, 0, ',', '.')."</td> \n";
			$table .= "<td align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
			
			//Base Imponible
			$table .= "<td align='right' class='total2'>".number_format($sala_base, 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td width='12%' align='right' class='total2'>".number_format(16, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_iva, 2, ',', '.')."</td> \n";
			$table .= "<td align='right' class='total2'>".number_format($sala_porc_municipal/$pelicula_registros, 2, ',', '.')."</td> \n";
			$table .= "<td align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td align='right' class='total2'>".number_format($sala_porc_fronpocine/$pelicula_registros, 2, ',', '.')."</td> \n";
			$table .= "<td align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
			//Neto
			$table .= "<td align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
			//Distribuidor
			$table .= "<td align='right' class='total2'>".number_format($sala_porc_distribuidor/$pelicula_registros, 2, ',', '.')."</td> \n";
			$table .= "<td align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;
		
		//TOTAL GENERAL
		$table .= "<tr> \n";
		$table .= "<td height='30' align='right' class='total2' colspan='4'>Total Cine $cine_actual: </td> \n";
		$table .= "<td align='center' class='info-total2'>".number_format($total_cantidad, 0, ',', '.')."</td> \n";
		$table .= "<td align='right' class='info-total2 '>".number_format($total_bruto, 2, ',', '.')."</td> \n";
				
		//Base Imponible
		$table .= "<td width='12%' align='right' class='info-total2 '>".number_format($total_base, 2, ',', '.')."</td> \n";
		//Impuesto Municipal
		$table .= "<td width='12%' align='right' class='info-total2 '></td> \n";
		$table .= "<td width='12%' align='right' class='info-total2 '>".number_format($total_base*16/100, 2, ',', '.')."</td> \n";
		$table .= "<td width='5%' align='right' class='info-total2 '>".number_format($total_porc_municipal/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='9%' align='right' class='info-total2 '>".number_format($total_municipal, 2, ',', '.')."</td> \n";
		//Fonprocine
		$table .= "<td width='5%' align='right' class='info-total2 '>".number_format($total_porc_fronpocine/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='9%' align='right' class='info-total2 '>".number_format($total_fronpocine, 2, ',', '.')."</td> \n";
		//Neto
		$table .= "<td width='14%' align='right' class='info-total2 '>".number_format($total_neto, 2, ',', '.')."</td> \n";
		//Distribuidor
		$table .= "<td width='5%' align='right' class='info-total2 '>".number_format($total_porc_distribuidor/$total_registros, 2, ',', '.')."</td> \n";
		$table .= "<td width='9%' align='right' class='info-total2 '>".number_format($total_distribuidor, 2, ',', '.')."</td> \n";
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