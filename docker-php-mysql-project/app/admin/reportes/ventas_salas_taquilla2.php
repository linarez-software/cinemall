<?php

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$usuario_registro = isset($_REQUEST['usuario_registro']) ? $_REQUEST['usuario_registro'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas_salas_taquilla&fecha1=".$_REQUEST['fecha1']."&sala=$sala&cine=$cine&usuario_registro=$usuario_registro&salida=imp";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT t.usuario_registro, tp.id_sala, tper.nombre AS precio, t.costo, t.comision, ( t.cantidad_transaccion ) AS boletos, ( t.total_transaccion ) AS monto, ";
$qry .= "tpe.nombre_espanol AS pelicula, ts.nombre AS sala, tc.nombre AS cine, t.id_programacion, t.id_precio, tp.id_cine, ";
$qry .= "tp.id_sala, tp.id_pelicula, tp.hora_inicio as funcion, tp.fecha_programacion,dosporuno,hora_operacion ";
$qry .= "FROM tbl_operacion t ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual' ";
$qry .= "AND t.status IN (1,2) ";
if($sala > 0):
	$qry .= "AND tp.id_sala = $sala ";
endif;
if($cine > 0):
	$qry .= "AND tp.id_cine = $cine ";
endif;
if($usuario_registro > 0):
	$qry .= "AND t.usuario_registro = $usuario_registro ";
endif;
if($pelicula > 0):
	$qry .= "AND tpe.id_pelicula = $pelicula ";
endif;
//$qry .= "GROUP BY tp.id_sala, tper.nombre, t.costo, t.comision, tpe.nombre_espanol, t.usuario_registro, ts.nombre, tc.nombre, t.id_programacion, t.id_precio,  ";
//$qry .= "tp.id_cine, tp.id_pelicula, tp.id_sala,  tp.hora_inicio, tp.fecha_programacion,dosporuno ";
$qry .= "ORDER BY t.usuario_registro,hora_operacion, tp.id_sala, tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.costo ";
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
		<td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Ventas Usuario Taquilla</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$usuario_actual = "";
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
		$table .= "<td align='center' height='21' colspan='6' class='pelicula-nombre'><span style='font-size:14px;color:#000000;'>".dia_semana(date('d/m/Y', strtotime($fecha_actual))) .", ".date('d/m/Y', strtotime($fecha_actual))."</span></td> \n";
		$table .= "</tr> \n";
		$table .= "<tr> \n";
			$table .= "<td align='left' width='15%' class='pelicula-nombre'><b> Hora</b> </td> \n";
			$table .= "<td align='left' width='15%' class='pelicula-nombre'><b> Pelicula</b> </td> \n";
			$table .= "<td align='left' width='15%' class='pelicula-nombre'><b> Funcion</b> </td> \n";
			$table .= "<td width='25%' align='left' class='pelicula-nombre'><b>Tipo</b></td> \n";
			$table .= "<td width='10%' align='right' class='pelicula-nombre'><b>Boletos</b></td> \n";
			
			$monto = ($campo->boletos * ($campo->costo+$campo->comision));
			$table .= "<td width='15%' align='right' class='pelicula-nombre'>Monto</td> \n";
			$table .= "<td width='20%' align='right' class='pelicula-nombre'><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
		while($campo=mysql_fetch_object($result)):
			/* ENCABEZADO USUARIO */
			$funcion_nombre = date("h:i a", strtotime($campo->funcion));
			$table .= "<tr> \n";
			$table .= "<td align='left' width='15%' class='info'> $campo->hora_operacion </td> \n";
			$table .= "<td align='left' width='15%' class='info'> $campo->pelicula </td> \n";
			$table .= "<td align='left' width='15%' class='info'> $funcion_nombre </td> \n";
			$table .= "<td width='25%' align='left' class='info'>".htmlentities($campo->precio) ."</td> \n";
			$table .= "<td width='10%' align='right' class='info'>".number_format($campo->boletos*$campo->dosporuno, 0, ',', '.')."</td> \n";
				$monto = ($campo->boletos * ($campo->costo+$campo->comision));
			$table .= "<td width='15%' align='right' class='info'>".number_format($monto, 2, ',', '.')."</td> \n";
			$table .= "<td width='20%' align='right' class='info'><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
			
			$funcion_nombre = '';
			$subtotal_boletos = $subtotal_boletos + $campo->boletos;
			$subtotal_monto = $subtotal_monto + $monto;
			
			$total_pelicula_monto = $total_pelicula_monto + $monto;
			$total_pelicula_boletos = $total_pelicula_boletos + $campo->boletos;
		endwhile;
		
		

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

