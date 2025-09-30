<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-peliculas-semanal&fecha1=".$_REQUEST['fecha1']."&sala=$sala&cine=$cine&pelicula=$pelicula&salida=imp";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "select sum(cantidad_transaccion) as boletos, tbl_operacion.costo, sum(total_transaccion) as monto, 
tbl_cine.nombre as cine, tbl_operacion.id_cine, tbl_programacion.hora_inicio as funcion, tbl_precio.nombre as precio,
tbl_pelicula.nombre_espanol as pelicula, tbl_programacion.id_pelicula, tbl_operacion.id_precio 
from `tbl_operacion`
inner join tbl_precio on tbl_operacion.id_precio=tbl_precio.id_precio
inner join `tbl_programacion` on `tbl_operacion`.`id_programacion`=`tbl_programacion`.id_programacion
inner join tbl_pelicula on tbl_pelicula.id_pelicula=tbl_programacion.id_pelicula
inner join tbl_cine on tbl_cine.id_cine=tbl_operacion.id_cine
and `tbl_programacion`.id_pelicula='$pelicula'
and `tbl_operacion`.fecha_operacion BETWEEN '$fecha_desde' and '$fecha_hasta'
group by costo";

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
			<td width="100%" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get;?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get;?>')" class="imprimir">Imprimir</a></td>
		</tr>
	<?php endif;?>
	<tr>
		
    <td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Ventas 
      Pelicula Semanal</td>
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
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
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
					$table .= "<td align='left' colspan='5' ><img src='images/tr.gif'></td> \n";
					$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";	
					
					//Total Pelicula
					if($total_pelicula_monto > 0):
						$table .= "<tr> \n";
						$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Pelicula: </td> \n";
						$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
						$table .= "</tr> \n";
						
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
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Pelicula:</b> $pelicula_actual</td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			/*if($campo->sala != $sala_actual):
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' ><img src='images/tr.gif'></td> \n";
					$table .= "<td width='15%' align='right'>".number_format($subtotal_monto, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";	
				
					$total_boletos = $total_boletos + $subtotal_boletos;
					$total_monto = $total_monto + $subtotal_monto;
					$subtotal_boletos = 0;
					$subtotal_monto = 0;
				endif;
			
				$sala_actual = $campo->sala;
				$table .= "<tr> \n";
				$table .= "<td align='left' colspan='6'><b>Sala:</b> $campo->sala</td> \n";
				$table .= "</tr> \n";
			endif;*/
			
			/* ENCABEZADO FUNCION */
			if($funcion_actual != $campo->funcion):
				$funcion_actual = $campo->funcion;
				$funcion_nombre = date("h:i a", strtotime($campo->funcion));
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' ><img src='images/tr.gif'></td> \n";
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
			$table .= "<td width='10%' align='right' class='info'>".number_format($campo->boletos, 0, ',', '.')."</td> \n";
			$table .= "<td width='15%' align='right' class='info'>".number_format($campo->costo, 2, ',', '.')."</td> \n";
			$table .= "<td width='15%' align='right' class='info'>".number_format($campo->monto, 2, ',', '.')."</td> \n";
			$table .= "<td width='20%' align='right' class='info'><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
			
			$funcion_nombre = '';
			$subtotal_boletos = $subtotal_boletos + $campo->boletos;
			$subtotal_monto = $subtotal_monto + $campo->monto;
			
			$total_pelicula_monto = $total_pelicula_monto + $campo->monto;
			$total_pelicula_boletos = $total_pelicula_boletos + $campo->boletos;
		endwhile;
		
		if($subtotal_boletos > 0):
			$table .= "<tr> \n";
			$table .= "<td align='left' colspan='5' ><img src='images/tr.gif'></td> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
			$table .= "</tr> \n";	
		endif;
	
		//Total Película
		if($total_pelicula_monto > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Pelicula: </td> \n";
			$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
			
			$total_cine_monto = $total_cine_monto + $total_pelicula_monto;
			$total_cine_boletos = $total_cine_boletos + $total_pelicula_boletos;
		endif;
		
		$table .= "<tr> \n <td colspan='6' height='7'><img src='images/tr.gif'></td> \n </tr>\n";
		$table .= "<tr> \n";
		$table .= "<td align='right'colspan='5' class='total-pelicula'>Total: </td> \n";
		$table .= "<td width='20%' align='right' class='cine-total'>".number_format($total_cine_monto, 2, ',', '.')."</td> \n";
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