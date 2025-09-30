<?php

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$fecha_hasta=$fecha_desde;
for ($i=0;$i<6;$i++)
	$fecha_hasta++;
	
$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-salas-semanal2&fecha1=".$_REQUEST['fecha1']."&sala=$sala&cine=$cine&pelicula=$pelicula&salida=imp";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT distinct  tc.nombre AS cine ";
$qry .= "FROM tbl_operacion t ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
$qry .= "WHERE DATE(tp.fecha_programacion) between '$fecha_desde' and '$fecha_desde'";
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
$qry .= "ORDER BY tp.id_sala, tp.id_cine, tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.costo ";
//echo $qry;
$result = mysql_query($qry) or die("Error en Consulta: " . mysql_error());

?>
<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
.pelicula-total1 {
    border-top-color: #666666;
    background-color: #666666c7;
    color: white;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    border-top-width: 1px;
    border-top-style: solid;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    font-weight: bold;
    
}
.pelicula-total2 {
    border-top-color: #666666;
    background-color: #66666697;
    color: white;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    border-top-width: 1px;
    border-top-style: solid;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    font-weight: bold;
    
}
.pelicula-total3 {
    border-top-color: #666666;
    background-color: #66666673;
    color: white;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 14px;
    border-top-width: 1px;
    border-top-style: solid;
    border-right-style: none;
    border-bottom-style: none;
    border-left-style: none;
    font-weight: bold;
    
}
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
     </td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$tbs=0;
		$tbt=0;
		$tbtm=0;

		$tbp=0;
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
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'>Dia: ".formato_fecha($fecha_desde,2)."</td> \n";
		$table .= "</tr> \n";
		$cccc=0;
		$t=0;
		$t1=0;

		while($campo=mysql_fetch_object($result)):
			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Cine:</b> $cine_actual</td> \n";
				$table .= "</tr> \n";
			endif;
			
			$qry = "SELECT distinct ts.id_sala,ts.nombre ";
			$qry .= "FROM tbl_operacion t ";
			$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
			$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
			$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
			$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
			$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
			$qry .= "WHERE DATE(tp.fecha_programacion) between '$fecha_desde' and '$fecha_desde'";
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
			$qry .= "ORDER BY tp.id_sala, tp.id_cine, tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.costo ";
			//echo $qry;
			$result2 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
			

			while($campo2=mysql_fetch_object($result2)):
				$table .= "<tr> \n";
				$table .= "<td align='left' colspan='6' class='pelicula-nombre'><b>Sala:</b> $campo2->nombre</td> \n";
				$table .= "</tr> \n";
				$qry = "SELECT  ";
				$qry .= "tpe.nombre_espanol AS pelicula, tpe.id_pelicula ";
				$qry .= "FROM tbl_operacion t ";
				$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
				$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
				$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
				$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
				$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
				$qry .= "WHERE DATE(tp.fecha_programacion) between '$fecha_desde' and '$fecha_desde' and ts.id_sala = $campo2->id_sala ";
				$qry .= "AND t.status IN (1,2) ";
				$qry .= "GROUP BY 1,2  ";
				$qry .= "ORDER BY 1 ";
				$ts=0;
				$ts1=0;
				$result3 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
				while($campo3=mysql_fetch_object($result3)):
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Pelicula:</b> $campo3->pelicula</td> \n";
					$table .= "</tr> \n";
					$tp=0;
					$tp1=0;
				
					$qry = "SELECT ";
					$qry .= " tp.hora_inicio as funcion ";
					$qry .= "FROM tbl_operacion t ";
					$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
					$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
					$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
					$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
					$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
					$qry .= "WHERE DATE(tp.fecha_programacion) between '$fecha_desde' and '$fecha_desde' and ts.id_sala = $campo2->id_sala ";
					$qry .= "AND t.status IN (1,2) ";
					
					$qry .= "AND ts.id_sala = $campo2->id_sala ";
					$qry .= "AND tpe.id_pelicula = $campo3->id_pelicula ";
					$qry .= "GROUP BY 1 ";
					$qry .= "ORDER BY 1 ";
					$result33 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
					while($campo33=mysql_fetch_object($result33)):
							

								$qry = "SELECT tper.nombre AS precio, t.costo, SUM( t.cantidad_transaccion*dosporuno ) AS boletos, SUM( t.total_transaccion ) AS monto, ";
								$qry .= " tp.hora_inicio as funcion ";
								$qry .= "FROM tbl_operacion t ";
								$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
								$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
								$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
								$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
								$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
								$qry .= "WHERE DATE(tp.fecha_programacion) between '$fecha_desde' and '$fecha_desde' and ts.id_sala = $campo2->id_sala ";
								$qry .= "AND t.status IN (1,2) ";
								
								$qry .= "AND ts.id_sala = $campo2->id_sala ";
								$qry .= "AND tpe.id_pelicula = $campo3->id_pelicula and tp.hora_inicio='$campo33->funcion' ";
								$qry .= "GROUP BY 1,2,5 ";
								$qry .= "ORDER BY 1 ";
								$result333 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
								$x=0;
								$tf=0;
								$tf1=0;
					
								while($campo333=mysql_fetch_object($result333)):
									$funcion_nombre = date("h:i a", strtotime($campo33->funcion));
									$table .= "<tr> \n";
									if ($x==0) $table .= "<td align='left' width='15%' class='info'> $funcion_nombre </td> \n";
									if ($x>0) $table .= "<td align='left' width='15%' class='info'> </td> \n";


									$table .= "<td width='25%' align='left' class='info'>".htmlentities($campo333->precio) ."</td> \n";
									$table .= "<td width='10%' align='right' class='info'>".number_format($campo333->boletos, 0, ',', '.')."</td> \n";
									$table .= "<td width='15%' align='right' class='info'>".number_format($campo333->costo, 2, ',', '.')."</td> \n";
									$table .= "<td width='15%' align='right' class='info'>".number_format($campo333->monto, 2, ',', '.')."</td> \n";
									$table .= "<td width='20%' align='right' class='info'><img src='images/tr.gif'></td> \n";
									$table .= "</tr> \n";
									$x++;
									$t=$t+$campo333->boletos;
									$t1=$t1+$campo333->monto;
									$ts=$ts+$campo333->boletos;
									$ts1=$ts1+$campo333->monto;
									$tf=$tf+$campo333->boletos;
									$tf1=$tf1+$campo333->monto;
									$tp=$tp+$campo333->boletos;
									$tp1=$tp1+$campo333->monto;

				
								endwhile;
								$table .= "<tr> \n";
								$table .= "<td align='right' colspan='2'  class='total-pelicula'><img src='images/tr.gif'>Total Funcion:</td> \n";
								$table .= "<td width='20%' align='right' class='pelicula-total'><b>".number_format($tf, 0, ',', '.')."</b></td> \n";
								$table .= "<td align='left' colspan='2' ><img src='images/tr.gif'></td> \n";
								$table .= "<td width='20%' align='right' class='pelicula-total'><b>".number_format($tf1, 2, ',', '.')."</b></td> \n";
								$table .= "</tr> \n";	


					endwhile;
					$table .= "<tr> \n";
					$table .= "<td align='right' colspan='2'  class='total-pelicula'><img src='images/tr.gif'>Total Pelicula:</td> \n";
					$table .= "<td width='20%' align='right' class='pelicula-total1'><b>".number_format($tp, 0, ',', '.')."</b></td> \n";
					$table .= "<td align='left' colspan='2' ><img src='images/tr.gif'></td> \n";
					$table .= "<td width='20%' align='right' class='pelicula-total1'><b>".number_format($tp1, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";	
				endwhile;
					$table .= "<tr> \n";
					$table .= "<td align='right' colspan='2'  class='total-pelicula'><img src='images/tr.gif'>Total Sala:</td> \n";
					$table .= "<td width='20%' align='right' class='pelicula-total2'><b>".number_format($ts, 0, ',', '.')."</b></td> \n";
					$table .= "<td align='left' colspan='2' ><img src='images/tr.gif'></td> \n";
					$table .= "<td width='20%' align='right' class='pelicula-total2'><b>".number_format($ts1, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";					
			endwhile;		
		endwhile;
		$table .= "<tr> \n";
		$table .= "<td align='RIGHT' colspan='2'   class='total-pelicula'><img src='images/tr.gif'><b>Total General:</b></td> \n";
		$table .= "<td width='15%' align='right' class='pelicula-total3'>".number_format($t, 0, ',', '.')."</td> \n";
		$table .= "<td align='left' colspan='2'   ><img src='images/tr.gif'></td> \n";
		$table .= "<td width='15%' align='right' class='pelicula-total3'>".number_format($t1, 2, ',', '.')."</td> \n";
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