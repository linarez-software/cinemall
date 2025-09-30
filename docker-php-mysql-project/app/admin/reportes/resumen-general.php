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

$qry = "select id_cine,cine,fecha,id_sala,nombre_sala,foncine,municipal,iva,porcentaje_distribuidor ,sum(cantidad_transaccion) as cantidad_transaccion,sum(total_transaccion) as total_transaccion

		from (
		SELECT 	o.id_cine, 
		c.nombre as cine, 
		pr.fecha_programacion as fecha, 
		pr.id_sala,
		sa.nombre as nombre_sala,
		o.foncine,o.municipal,o.iva,
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
			tbl_pelicula_distribuidor.fecha_fin>=pr.fecha_programacion) end) END as porcentaje_distribuidor,
		SUM(o.cantidad_transaccion*dosporuno) as cantidad_transaccion, 
		SUM(o.cantidad_transaccion*o.costo*dosporuno) as total_transaccion,pr.hora_inicio  ";

$qry .= "FROM tbl_operacion o 
		INNER JOIN tbl_programacion pr ON o.id_programacion = pr.id_programacion 
		INNER JOIN tbl_pelicula pe ON pe.id_pelicula = pr.id_pelicula 
		INNER JOIN tbl_cine c ON c.id_cine = o.id_cine 
		INNER JOIN tbl_precio p ON o.id_precio = p.id_precio 
		INNER JOIN tbl_sala sa ON sa.id_sala = pr.id_sala ";

$qry .= "WHERE 	o.status = 1 
AND 	fecha_operacion >= '$fecha_desde'  AND 	fecha_operacion <= '$fecha_hasta' 
AND 	fecha_programacion >= '$fecha_desde'  AND 	fecha_programacion <= '$fecha_hasta' 

AND		o.total_transaccion <> 0  ";

$qry .= " GROUP BY  id_cine,cine,fecha,id_sala,nombre_sala,foncine,municipal,iva,porcentaje_distribuidor ";

$qry .= ") a  group by 1,2,3,4,5,6,7,8,9 order by 4,3";



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
		
    <td align="left" colspan="4" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen General</td>
	</tr>
	<tr>
		<td colspan="4"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		
		$total_bruto = 0;
		$total_porc_municipal = 0;
		$total_municipal = 0;
		$total_porc_fronpocine = 0;
		$total_fronpocine = 0;
		$total_iva = 0;
		$total_neto = 0;
		$total_porc_distribuidor = 0;
		$total_distribuidor = 0;
		$total_registros = 0;
		
		$total_iva = 0;
		
		$sala_iva = 0;
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
		$total_iva =0;
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		$por=40;
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
				$table .= "<td width='20%' align='center' class='info'><b>Fecha Exhibicion</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>Monto Bruto</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>Base Imponible</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>% Iva</b></td> \n";
				$table .= "<td width='12%' align='center' class='info'><b>Iva</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Imp. Municipal</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Fonprocine</b></td> \n";
				$table .= "<td width='14%' align='center' class='info'><b>Neto</b></td> \n";
				$table .= "<td width='14%' align='center' class='info' colspan='2'><b>Distribuidor</b></td> \n </tr>\n";
				
				$table .= "<tr> \n <td width='20%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='12%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='12%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='9%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='9%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='9%' align='center' class='info'><b>Monto</b></td> \n";
				$table .= "<td width='14%' align='center' class='info'>&nbsp;</td> \n";
				$table .= "<td width='5%' align='center' class='info'><b>%</b></td> \n";
				$table .= "<td width='9%' align='center' class='info'><b>Monto</b></td> \n";;

				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			if($sala_actual != $campo->id_sala):
				
				if($sala_registros > 0):
					
					$table .= "<tr> \n";
					$table .= "<td height='30' width='20%' align='right' class='total2'>Total Sala $cine_actual - $nombre_sala: </td> \n";
					$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
					
					//Base Imponible
					$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_base, 2, ',', '.')."</td> \n";
					$table .= "<td width='12%' align='right' class='total2'>".number_format(16, 2, ',', '.')."</td> \n";
					$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_iva, 2, ',', '.')."</td> \n";
					//Impuesto Municipal
					$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_municipal/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
					//Fonprocine
					$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_fronpocine/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
					//Neto
					$table .= "<td width='14%' align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
					//Distribuidor
					$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_distribuidor/$sala_registros, 2, ',', '.')."</td> \n";
					$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					
					$sala_iva = 0;
					$sala_bruto = 0;
					$sala_base = 0;
					$sala_porc_municipal = 0;
					$sala_municipal = 0;
					$sala_porc_fronpocine = 0;
					$sala_fronpocine = 0;
					$sala_neto = 0;
					$sala_porc_distribuidor = 0;
					$sala_distribuidor = 0;
					$sala_registros = 0;
				endif;
				$por=$campo->porcentaje_distribuidor;
				$sala_actual = $campo->id_sala;
				$nombre_sala = $campo->nombre_sala;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'>SALA: <b>$cine_actual - $nombre_sala</b></td> \n";
				$table .= "</tr> \n";
				
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td width='20%' align='center' class='info'>".formato_fecha(htmlentities($campo->fecha), 2)."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->total_transaccion, 2, ',', '.')."</td> \n";
			//Base Imponible
			
			$base_imponible=($campo->total_transaccion*100)/(100+$campo->municipal+$campo->iva);
			$table .= "<td width='12%' align='right' class='info'>".number_format($base_imponible, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->iva, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format(($campo->iva*$base_imponible/100), 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td width='5%' align='right' class='info'>".number_format($campo->municipal, 0, ',', '.')."</td> \n";
			$monto_impuesto = ($base_imponible * $campo->municipal) / 100;
			
			//$campo2->iva*$base/100
			$table .= "<td width='9%' align='right' class='info'>".number_format($monto_impuesto, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td width='5%' align='right' class='info'>".number_format(2.5,2, ',', '.')."</td> \n";
			$monto_fonprocine = (($base_imponible) * 2.5) / 100;
			$table .= "<td width='9%' align='right' class='info'>".number_format($monto_fonprocine, 2, ',', '.')."</td> \n";
			//Neto
			$neto = $base_imponible - $monto_fonprocine;
			$table .= "<td width='14%' align='right' class='info'>".number_format($neto, 2, ',', '.')."</td> \n";
			//Distribuidor

			$table .= "<td width='5%' align='right' class='info'>".number_format($campo->porcentaje_distribuidor, 0, ',', '.')."</td> \n";
			$monto_distribuidor = ($neto * $campo->porcentaje_distribuidor) / 100;
			$table .= "<td width='9%' align='right' class='info'>".number_format($monto_distribuidor, 2, ',', '.')."</td> \n";
			
			$table .= "</tr> \n";
			$base=$base_imponible;
			$sala_iva = $sala_iva+ $campo->iva*$base_imponible/100;
			$sala_base = $sala_base + $base_imponible;
			$sala_bruto = $sala_bruto + $campo->total_transaccion;
			$sala_porc_municipal = $sala_porc_municipal + $campo->municipal;
			$sala_municipal = $sala_municipal + $monto_impuesto;
			$sala_porc_fronpocine = $sala_porc_fronpocine +   2.5;
			$sala_fronpocine = $sala_fronpocine + $monto_fonprocine ;
			$sala_neto = $sala_neto + $neto;
			$sala_porc_distribuidor = $sala_porc_distribuidor + $campo->porcentaje_distribuidor;
			$sala_distribuidor = $sala_distribuidor + $monto_distribuidor;
			$sala_registros++;
			
			$total_iva = $total_iva + $sala_iva;
			$total_base = $total_base + $base_imponible;
			$total_bruto = $total_bruto + $campo->total_transaccion;
			$total_porc_municipal = $total_porc_municipal +  2.5;
			$total_municipal = $total_municipal + $monto_impuesto;
			$total_porc_fronpocine = $total_porc_fronpocine +   2.5;
			$total_fronpocine = $total_fronpocine + $monto_fonprocine ;
			$total_neto = $total_neto + $neto;
			$total_porc_distribuidor = $total_porc_distribuidor + $campo->porcentaje_distribuidor;
			$total_distribuidor = $total_distribuidor + $monto_distribuidor;
			$total_registros++;

		endwhile;
		
		/* ENCABEZADO SALA */
		if($sala_registros > 0):
			$table .= "<tr> \n";
			$table .= "<td height='30' width='20%' align='right' class='total2'>Total Sala $cine_actual - $nombre_sala: </td> \n";
			$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_bruto, 2, ',', '.')."</td> \n";
			
			//Base Imponible
			$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_base, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='total2'>".number_format(16, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='total2'>".number_format($sala_iva, 2, ',', '.')."</td> \n";
			//Impuesto Municipal
			$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_municipal/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_municipal, 2, ',', '.')."</td> \n";
			//Fonprocine
			$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_fronpocine/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_fronpocine, 2, ',', '.')."</td> \n";
			//Neto
			$table .= "<td width='14%' align='right' class='total2'>".number_format($sala_neto, 2, ',', '.')."</td> \n";
			//Distribuidor
			$table .= "<td width='5%' align='right' class='total2'>".number_format($sala_porc_distribuidor/$sala_registros, 2, ',', '.')."</td> \n";
			$table .= "<td width='9%' align='right' class='total2'>".number_format($sala_distribuidor, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;
		
		//TOTAL GENERAL
		$table .= "<tr> \n";
		$table .= "<td height='30' width='20%' align='right' class='total2'>Total Cine $cine_actual: </td> \n";
		$table .= "<td width='12%' align='right' class='info-total2 '>".number_format($total_bruto, 2, ',', '.')."</td> \n";
		
		//Base Imponible
		$table .= "<td width='12%' align='right' class='info-total2 '>".number_format($total_base, 2, ',', '.')."</td> \n";
		$table .= "<td width='12%' align='right' class='info-total2 '></td> \n";
		$table .= "<td width='12%' align='right' class='info-total2 '>".number_format($total_base*16/100, 2, ',', '.')."</td> \n";
		//Impuesto Municipal
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