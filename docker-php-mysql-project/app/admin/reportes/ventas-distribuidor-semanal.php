<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;
$distribuidor = isset($_REQUEST['distribuidor']) ? $_REQUEST['distribuidor'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-distribuidor-semanal&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&sala=$sala&cine=$cine&distribuidor=$distribuidor&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "select tbl_pelicula.nombre_espanol as pelicula, tbl_operacion.fecha_operacion as fecha_actual, sum(tbl_operacion.total_transaccion) as monto, tbl_programacion.id_sala as sala, tbl_programacion.hora_inicio as hora	
from tbl_operacion
inner join tbl_programacion
on tbl_operacion.id_programacion=tbl_programacion.id_programacion
inner join tbl_pelicula
on tbl_programacion.id_pelicula=tbl_pelicula.id_pelicula
inner join tbl_distribuidor
on tbl_pelicula.id_distribuidor=tbl_distribuidor.id_distribuidor
and tbl_pelicula.id_distribuidor='$distribuidor'
and tbl_operacion.fecha_operacion BETWEEN '$fecha_desde' and '$fecha_hasta'
group by pelicula";
//echo $qry;

//and tbl_programacion.id_sala='$sala'
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
		
    <td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen Pel&iacute;cula Distribuidor</td>
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
			/* ENCABEZADO PELICULA */
			if($pelicula_actual != $campo->pelicula):
				$pelicula_actual = $campo->pelicula;
				if($subtotal_boletos > 0):

					//Total Pelicula
					if($total_pelicula_monto > 0):
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
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td align='left' height='25' width='35%' class='info'><b> $pelicula_actual </b></td> \n";
			$table .= "<td width='15%' align='right' class='info'>".formato_fecha($campo->fecha_actual,2)."</td> \n";
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
	
		if($total_pelicula_monto > 0):
			$total_cine_monto = $total_cine_monto + $total_pelicula_monto;
			$total_cine_boletos = $total_cine_boletos + $total_pelicula_boletos;
		endif;
		
		$table .= "<tr> \n <td colspan='6' height='7'><img src='images/tr.gif'></td> \n </tr>\n";
		$table .= "<tr> \n";
		$table .= "<td align='right'colspan='5' class='total-pelicula'>Total Resumen: </td> \n";
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