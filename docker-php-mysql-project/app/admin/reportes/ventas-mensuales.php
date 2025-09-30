<?php
//session_start();
//include_once "../../include/bd.inc.php";
//include '../include/func.glb.php';
//include '../include/config.inc.php';

$modulo = REPORTES."ventas-mensuales";
$fecha_actual = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d', time());

$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_GET['sala']) ? $_GET['sala'] : 0;

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$boleto = isset($_REQUEST['boleto']) ? $_REQUEST['boleto'] :  'off';
$get = "modulo=ventas-mensuales&fecha1=".$_REQUEST['fecha1']."&sala=$sala&cine=$cine&boleto=$boleto&salida=imp";

$porc_fomprocine = buscar_fomprocine();
$porc_municipal = buscar_impuesto_municipal();
$porc_alquiler = buscar_alquiler();

//echo $fecha_actual ."<br>";
/* $qry = "select sum(cantidad_transaccion) as espectadores, sum(total_transaccion) as total, date(fecha_operacion) as fecha_operacion
from tbl_operacion
where date(fecha_operacion) BETWEEN '$fecha_desde' and '$fecha_hasta'
group by date(fecha_operacion)"; */
$qry = "select  sum(o.cantidad_transaccion*dosporuno) as espectadores, sum(o.total_transaccion) as total, o.fecha_operacion
from tbl_operacion o inner join tbl_programacion p on o.id_programacion = p.id_programacion inner join tbl_precio j on o.id_precio = j.id_precio 
group by fecha_operacion having o.fecha_operacion between '$fecha_desde' and '$fecha_hasta'";
//echo $qry;
$result = mysql_query($qry);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mensual Por Dia</title>
<link href="../css/reportes.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="90%" align="center" cellpadding="0" cellspacing="0">
	<?php
	if($salida == 'pan'):?>
		<tr>
			<td width="100%" align="right" valign="middle"><a href="#" onClick="imprimir_reporte('<?php echo $get;?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onClick="imprimir_reporte('<?php echo $get;?>')" class="imprimir">Imprimir</a></td>
		</tr>
	<?php endif;?>
	<tr>
		<td colspan="4" align="center" class="titulo-reporte">Ventas Totales por Dia</td>
	</tr>
	<tr>
		<td colspan="4" align="right" width="50%" class="fecha"><b>Fecha:</b> <?php echo $fecha_impresion;?></td>
	</tr>
	<tr>
    	<td colspan="4" align="center" class="fecha">Lapso del <?php echo  formato_fecha($fecha_desde,2)?> al <?php echo  formato_fecha($fecha_hasta,2)?></td>
    </tr>
	<tr>
		<td colspan="4"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
	
	if($result):
		$table = "<center><table><tr>
						<td align=center class='total-pelicula'>Fecha de Operacion</td>
						<td align=left class='total-pelicula'>Monto</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		if($boleto=='on'):
			$table .= "<td align=left class='total-pelicula'>Boletos</td>";
		else:				
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		endif;				
		$table .= "</tr>";
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
		
		while($campo=mysql_fetch_object($result)):
		
			$fecha_operacion = $campo->fecha_operacion;
			$total=$campo->total;
			$fecha_operacion=formato_fecha($fecha_operacion,2);
			$boletos = $campo->espectadores;
			$table.="<tr>
						<td align=center class='info'>$fecha_operacion</td>
						<td align=right class='info'>".number_format($total, 2, ',', '.')."</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> ";
			if($boleto=='on'):
				$table .= "<td align=right class='info'>".number_format($boletos, 0, ',', '.')."</td>";
			else:				
				$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			endif;	
			$table .= "</tr>";
			
			$subtotal_monto = $subtotal_monto + $campo->total;
			$subtotal_boletos = $subtotal_boletos + $campo->espectadores;
		endwhile;
		
		
		if($subtotal_monto > 0):
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'></th> \n";
			$table .= "<td width='20%' align='right' class='cine-total'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			if($boleto=='on'):
				$table .= "<td width='20%' align='right' class='cine-total'><b>".number_format($subtotal_boletos, 0, ',', '.')."</b></td> \n";
			else:				
				$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			endif;	
			$table .= "</tr> \n";	
		endif;
		
			$baseImponible = ($subtotal_monto / 1.18);
			$impuestoMunicipal=($baseImponible*2)/100;
			$impuestoMunicipal2=($baseImponible*16)/100;
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'>Base Imponible </th> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($baseImponible, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr> \n";	
			
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'>Imp. Municipal (".number_format($porc_municipal, 2, ',', '.')."%)</th> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($impuestoMunicipal, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr> \n";	
			
			$subTotal=$subtotal_monto-$impuestoMunicipal;
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'>Iva 16%</th> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($impuestoMunicipal2, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr> \n";	
	
			/*$fomprocine=($subTotal*$porc_fomprocine)/100;
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'>Fonprocine (".number_format($porc_fomprocine, 2, ',', '.')."%)</th> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($fomprocine, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr> \n";
			
			$subTotal=$subTotal-$fomprocine;
			$table .= "<tr> \n";
			$table .= "<td align='right' class='total-pelicula'></td> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($subTotal, 2, ',', '.')."</b></td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr> \n";
			
			$total=$subTotal*$porc_alquiler/100;
			$table .= "<tr> \n";
			$table .= "<th align='right' class='total-pelicula'>Alquiler (".number_format($porc_alquiler, 2, ',', '.')."%)</th> \n";
			$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total, 2, ',', '.')."</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td> \n";
			$table .= "</tr></center> \n";*/
			
		//Total Pelcula
		/*if($total_pelicula_monto > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Pelcula: </td> \n";
			$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;*/
		
		echo $table;
		
	
	endif;
	?>	
	</table></td>
	</tr>
</table>
</body>
</html>
