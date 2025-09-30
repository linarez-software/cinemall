<?php
session_start();

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-salas-bruta&fecha1=".$_REQUEST['fecha1']."&sala=$sala&cine=$cine&pelicula=$pelicula&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT tper.nombre AS precio, t.costo, t.comision, SUM( t.cantidad_transaccion ) AS boletos, SUM( t.total_transaccion ) AS monto, ";
$qry .= "tpe.nombre_espanol AS pelicula, ts.nombre AS sala, tc.nombre AS cine, t.id_programacion, t.id_precio, tp.id_cine, ";
$qry .= "tp.id_sala, tp.id_pelicula, tp.hora_inicio as funcion, tp.fecha_programacion, dosporuno ";
$qry .= "FROM tbl_operacion t ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual' ";
$qry .= "AND cantidad_transaccion <> 0 ";
$qry .= "AND t.status IN (1,2) ";
if($sala > 0):
	$qry .= "AND ts.id_sala = $sala ";
endif;
if($cine > 0):
	$qry .= "AND tp.id_cine = $cine ";
endif;
if($pelicula > 0):
	$qry .= "AND tpe.id_pelicula = $pelicula ";
endif;
$qry .= "GROUP BY tp.id_sala, tper.nombre, t.costo, tpe.nombre_espanol, ts.nombre, tc.nombre, t.id_programacion, t.id_precio,  ";
$qry .= "tp.id_cine, tp.id_pelicula, tp.id_sala,  tp.hora_inicio, tp.fecha_programacion, dosporuno  ";
$qry .= "ORDER BY tp.id_sala,tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.costo ";
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
		<td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Ventas Diarias Bruta</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$pelicula_actual = "";
		$funcion_actual = "";
		$subtotal_boletos = 0;
		$subtotal_monto = 0;
		$total_boletos = 0;
		$total_monto = 0;
		$sala_actual = 0;
		$funcion_nombre = '';
		$total_pelicula_monto = 0;
		$total_pelicula_boletos = 0;
		$total_cine_monto = 0;
		$total_cine_boletos = 0;
		
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'><span style='font-size:14px;color:#000000;'>".ucfirst(strftime("%A", strtotime($fecha_actual))) .", ".date('d/m/Y', strtotime($fecha_actual))."</span></td> \n";
		$table .= "</tr> \n";
		while($campo=mysql_fetch_object($result)):
			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Cine:</b> $cine_actual</td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO PELICULA */
			if($pelicula_actual != $campo->pelicula):
				$pelicula_actual = $campo->pelicula;
			if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' >&nbsp;</td> \n";
					$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";	
					
					//Total Pelicula
					if($total_pelicula_monto > 0):
						$table .= "<tr> \n";
						$table .= "<td align='right' colspan='2' class='total-pelicula'>Total Pel&iacute;cula: </td> \n";
						$table .= "<td width='10%' align='right' class='pelicula-total'>".number_format($total_pelicula_boletos, 0, ',', '.')."</td> \n";
						$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
						$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
						$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
						$table .= "</tr> \n";
						
						//$table .= "<tr> \n";
						//$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Pelicula: </td> \n";
						//$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
						//$table .= "</tr> \n";
						
						$total_cine_monto = $total_cine_monto + $total_pelicula_monto;
						$total_cine_boletos = $total_cine_boletos + $total_pelicula_boletos;
						
						$total_pelicula_monto = 0;
						$total_pelicula_boletos = 0;
					endif;
					
					
					$total_boletos = $total_boletos + $subtotal_boletos;
					$total_monto = $total_monto + $subtotal_monto;
					$subtotal_boletos = 0;
					$subtotal_monto = 0;
				endif;
												
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'>Pelicula: <b>$pelicula_actual</b></td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			if($campo->sala != $sala_actual):
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' >&nbsp;</td> \n";
					$table .= "<td width='15%' align='right'>".number_format($subtotal_monto, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";	
				
					$total_boletos = $total_boletos + $subtotal_boletos;
					$total_monto = $total_monto + $subtotal_monto;
					$subtotal_boletos = 0;
					$subtotal_monto = 0;
				endif;
			
				$sala_actual = $campo->sala;
				$table .= "<tr> \n";
				$table .= "<td align='left' colspan='6' class='pelicula-nombre'><b>Sala:</b> $campo->sala</td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO FUNCION */
			if($funcion_actual != $campo->funcion):
				$funcion_actual = $campo->funcion;
				$funcion_nombre = date("h:i a", strtotime($campo->funcion));
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' >&nbsp;</td> \n";
					$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";	
				
					$total_boletos = $total_boletos + $subtotal_boletos;
					$total_monto = $total_monto + $subtotal_monto;
					$subtotal_boletos = 0;
					$subtotal_monto = 0;
				endif;
				/*$table .= "<tr> \n";
				$table .= "<td align='left'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
				$table .= "<td width='30%' align='left'>FUNCION: ". date('h:i a', strtotime($funcion_actual)) . "</td> \n";
				$table .= "<td width='30%' align='center'>PRECIO</td> \n";
				$table .= "<td width='30%' align='center'>CANTIDAD</td> \n";
				$table .= "<td width='30%' align='center'>TOTAL</td> \n";
				$table .= "</tr> \n </table> \n </td> \n";
				$table .= "</tr> \n";*/
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td align='left' width='15%' class='info'> $funcion_nombre </td> \n";
			$table .= "<td width='25%' align='left' class='info'>".htmlentities($campo->precio) ."</td> \n";
			$table .= "<td width='10%' align='right' class='info'>".number_format($campo->boletos*$campo->dosporuno, 0, ',', '.')."</td> \n";
			$table .= "<td width='15%' align='right' class='info'>".number_format($campo->costo, 2, ',', '.')."</td> \n";
			$monto = ($campo->boletos * ($campo->costo));
			$table .= "<td width='15%' align='right' class='info'>".number_format($monto, 2, ',', '.')."</td> \n";
			$table .= "<td width='20%' align='right' class='info'>&nbsp;</td> \n";
			$table .= "</tr> \n";
			
			$funcion_nombre = '';
			$subtotal_boletos = $subtotal_boletos + ($campo->boletos*$campo->dosporuno);
			$subtotal_monto = $subtotal_monto + $monto;
			
			$total_pelicula_monto = $total_pelicula_monto + $monto;
			$total_pelicula_boletos = $total_pelicula_boletos +($campo->boletos*$campo->dosporuno);
		endwhile;
		
		if($subtotal_boletos > 0):
			$table .= "<tr> \n";
			$table .= "<td align='left' colspan='5' >&nbsp;</td> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
			$table .= "</tr> \n";	
		endif;
	
		//Total Película
		if($total_pelicula_monto > 0):
			$table .= "<td align='right' colspan='2' class='total-pelicula'>Total Pel&iacute;cula: </td> \n";
			$table .= "<td width='10%' align='right' class='pelicula-total'>".number_format($total_pelicula_boletos, 0, ',', '.')."</td> \n";
			$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
			$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
			$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
			//$table .= "<tr> \n";
			//$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Pelicula: </td> \n";
			//$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
			//$table .= "</tr> \n";
			
			$total_cine_monto = $total_cine_monto + $total_pelicula_monto;
			$total_cine_boletos = $total_cine_boletos + $total_pelicula_boletos;
		endif;
		
		$table .= "<tr> \n <td colspan='6' height='7'>&nbsp;</td> \n </tr>\n";
		$table .= "<td align='right' colspan='2' class='total-pelicula'>Total Cine $cine_actual: </td> \n";
		$table .= "<td width='10%' align='right' class='pelicula-total'>".number_format($total_cine_boletos, 0, ',', '.')."</td> \n";
		$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
		$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
		$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_cine_monto, 2, ',', '.')."</td> \n";
		//$table .= "<tr> \n";
		//$table .= "<td align='right'colspan='5' class='total-pelicula'>Total Cine $cine_actual: </td> \n";
		//$table .= "<td width='20%' align='right' class='cine-total'>".number_format($total_cine_monto, 2, ',', '.')."</td> \n";
		//$table .= "</tr> \n";

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