<?php

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-salas&fecha1=".$_REQUEST['fecha1']."&cine=$cine&pelicula=$pelicula&salida=";
//phpinfo2();

//echo $fecha_actual ."<br>";

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
		<td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Ventas Diarias Taquilla</td>
	</tr>
	<tr>
		<td colspan="2">

	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	$table = "<tr> \n";
	$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre2'><span style='font-size:9px;color:#000000;'>".dia_semana(date('d/m/Y', strtotime($fecha_actual))).", ".date('d/m/Y', strtotime($fecha_actual))."</span></td> \n";
	echo $table .= "</tr> \n";
	$table="";
	$t1=0;
	$t2=0;
	$qry = "SELECT  tp.fecha_programacion ,DATE_FORMAT(fecha_programacion, '%d/%m/%Y')fec2,ts.id_sala as sala, ts.nombre AS sala2,DATE_FORMAT(fecha_operacion, '%d/%m/%Y') fec3 ";
	$qry .= "FROM tbl_operacion t ";
	$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
	$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
	$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
	$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
	$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
	$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual'  ";
	$qry .= "AND cantidad_transaccion <> 0 ";
	$qry .= "AND t.status IN (1,2) ";
	if($cine > 0):
		$qry .= "AND tp.id_cine = $cine ";
	endif;
	if($pelicula > 0):
		$qry .= "AND tpe.id_pelicula = $pelicula ";
	endif; 
	$qry .= "GROUP BY tp.fecha_programacion,ts.id_sala,fecha_operacion ";
	$qry .= "ORDER BY tp.fecha_programacion,ts.id_sala ";
	$result33 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
	$t1=0;;
	$t2=0;
	

	while($campo222=mysql_fetch_object($result33)):
		$total_sala_boletos=0;
		$total_sala=0;
		$table .= "<tr> \n <td colspan='6' height='7'><b><i>Sala $campo222->sala2</i></b></td> \n </tr>\n";
		$table .= "<tr><td align='right' colspan='6' class='total-pelicula2'>&nbsp;<br></td></tr> \n";
		$qry = "SELECT tp.id_pelicula ";
				$qry .= "FROM tbl_operacion t ";
				$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
				$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
				$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
				$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
				$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
				$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual'  and tp.fecha_programacion='".$campo222->fecha_programacion."' ";
				$qry .= "AND cantidad_transaccion <> 0 ";
				$qry .= "AND t.status IN (1,2)  and tp.id_sala=".$campo222->sala. "   " ;
				if($cine > 0):
					$qry .= "AND tp.id_cine = $cine ";
				endif;
				if($pelicula > 0):
					$qry .= "AND tpe.id_pelicula = $pelicula ";
				endif;
				$qry .= " GROUP BY tp.id_pelicula ";
				
		$result333 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
		

		while($campo2222=mysql_fetch_object($result333)){
				
		
				$qry = "SELECT  tpe.nombre_espanol AS pelicula , tp.id_pelicula ";
				$qry .= "FROM tbl_operacion t ";
				$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
				$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
				$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
				$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
				$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
				$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual'  and tp.fecha_programacion='".$campo222->fecha_programacion."' ";
				$qry .= "AND cantidad_transaccion <> 0 ";
				$qry .= "AND t.status IN (1,2)  and tp.id_sala=".$campo222->sala. "  AND tpe.id_pelicula =$campo2222->id_pelicula     " ;
				if($cine > 0):
					$qry .= "AND tp.id_cine = $cine ";
				endif;
				if($pelicula > 0):
					$qry .= "AND tpe.id_pelicula = $pelicula ";
				endif;
				$qry .= "GROUP BY 1,2 ";
				$qry .= "ORDER BY 2 ";
				$result334 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
				$pelis=""	;
				
				while($campo2=mysql_fetch_object($result334)){
					$table .= "<tr> \n <td colspan='6' height='7'  class='total-pelicula2'><b>$campo2->pelicula </b></td> \n </tr>\n<tr><td align='right' colspan='7' class='total-pelicula2'>&nbsp;</td></tr> \n";
					$qry = "SELECT tper.nombre AS precio, t.costo, t.comision, SUM( t.cantidad_transaccion  ) AS boletos, SUM( t.total_transaccion ) AS monto, ";
					$qry .= 	"tpe.nombre_espanol AS pelicula, ts.nombre AS sala, tc.nombre AS cine, t.id_programacion, t.id_precio, tp.id_cine, ";
					$qry .= "tp.id_sala, tp.id_pelicula, tp.hora_inicio as funcion, tp.fecha_programacion ,dosporuno ";
					$qry .= "FROM tbl_operacion t ";
					$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
					$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
					$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
					$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
					$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
					$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual'  and tp.fecha_programacion='".$campo222->fecha_programacion."' ";
					$qry .= "AND cantidad_transaccion <> 0 ";
					$qry .= "AND t.status IN (1,2)  and tp.id_sala=".$campo222->sala. "  and tp.id_pelicula=$campo2->id_pelicula   " ;
					$qry .= "GROUP BY tp.id_sala, tper.nombre, t.costo, tpe.nombre_espanol, ts.nombre, tc.nombre, t.id_programacion, t.id_precio,  ";
					$qry .= "tp.id_cine, tp.id_pelicula, tp.id_sala,  tp.hora_inicio, tp.fecha_programacion,dosporuno ";
					$qry .= "ORDER BY tp.id_sala,tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.costo ";
					
					$result1 = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
					$pelis=""	;
					$funcion="";
					$total_pelicula_monto = 0;
					$total_pelicula_boletos = 0;
					$totalfuncion = 0;
					while($campo1=mysql_fetch_object($result1)){
							if ($funcion!=date("h:ia", strtotime($campo1->funcion))){
									if ($funcion!=""){

									}
									$table .= "<tr style='height:18'> \n";
									$table .= "<td align='left' width='15%' colspan=6>&nbsp;</td> \n";
									$table .= "</tr> \n";
									$table .= "<tr style='height:16'> \n";
									$table .= "<td align='left' width='15%' class='info2' style=''> ".date("h:ia", strtotime($campo1->funcion))."</td> \n";
							}
							else{
								$table .= "<tr style='height:16'> \n";
								$table .= "<td align='left' width='15%' class='info2' style=''> </td>\n"; 
							}
							$table .= "<td width='25%' align='left' class='info2' style=''>".htmlentities($campo1->precio) ."</td> \n";
							$table .= "<td width='10%' align='right' class='info2' style=''>".number_format($campo1->boletos*$campo1->dosporuno, 0, ',', '.')."</td> \n";
							$table .= "<td width='15%' align='right' class='info2' style=''>".number_format($campo1->costo+$campo1->comision, 2, ',', '.')."</td> \n";
							$monto = ($campo1->boletos * ($campo1->costo+$campo1->comision));
							$table .= "<td width='15%' align='right' class='info2' style=''>".number_format($monto, 2, ',', '.')."</td> \n";
							$table .= "<td width='20%' align='right' class='info2' style=''>&nbsp;</td> \n";
							$table .= "</tr> \n";
							$funcion=date("h:ia", strtotime($campo1->funcion));
							$total_pelicula_monto = $total_pelicula_monto + $monto;
							$total_pelicula_boletos = $total_pelicula_boletos + ($campo1->boletos*$campo1->dosporuno);
							$totalfuncion = $totalfuncion + ($campo->boletos*$campo->dosporuno);
							$total_sala_boletos=$total_sala_boletos + ($campo1->boletos*$campo1->dosporuno);
							$total_sala=$total_sala + $monto;
							$t1=$t1+  ($campo1->boletos*$campo1->dosporuno);
							$t2=$t2 + $monto;
	
						
					}	
					$table .= "<tr ><td align='right' colspan='2' class='total-pelicula2'>Total Pelicula :</td> \n";
					$table .= "<td style='border-top: dotted  1px black;border-bottom: dotted  1px black;    background-color: #66666652;' align='right'  class='total-pelicula2'>".number_format($total_pelicula_boletos, 0, ',', '.')." </td> \n";
					$table .= "<td colspan='2' class='total-pelicula2'></td> \n";
					$table .= "<td style='border-top: dotted  1px black;border-bottom: dotted  1px black;    background-color: #66666652;' align='right' class='total-pelicula2'>".number_format($total_pelicula_monto , 2, ',', '.')." </td> \n</tr>";
					$table .= "<tr><td align='right' colspan='7' class='total-pelicula2'>&nbsp;</td></tr> \n";
					$total_pelicula_boletos=0;
					$total_pelicula_monto =0;
				}
		}
				
					$table .= "<tr><td align='right' colspan='2' class='total-pelicula2'>Total Sala $sala: </td> \n";
					$table .= "<td width='10%' align='right' class='pelicula-total2'>".number_format($total_sala_boletos, 0, ',', '.')."</td> \n";
					$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
					$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
					$table .= "<td width='20%' align='right' class='pelicula-total2'>".number_format($total_sala, 2, ',', '.')."</td> </tr>\n";		
	endwhile;

	echo $table;
	$table = "";
	$table .= "<tr> \n <td colspan='6' height='7'>&nbsp;</td> \n </tr>\n";
	$table .= "<td align='right' colspan='2' class='total-pelicula2'>Total $cine_actual: </td> \n";
	$table .= "<td width='10%' align='right' class='pelicula-total2'>".number_format($t1, 0, ',', '.')."</td> \n";
	$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
	$table .= "<td width='15%' align='right'>&nbsp;</td> \n";
	$table .= "<td width='20%' align='right' class='pelicula-total2'>".number_format($t2, 2, ',', '.')."</td> \n";
	echo $table;
	?>	
	</table></td>
	</tr>
</table>