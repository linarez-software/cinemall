<?php

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$usuario_registro = isset($_REQUEST['usuario_registro']) ? $_REQUEST['usuario_registro'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=ventas-diaria-usuario2&fecha1=".$_REQUEST['fecha1']."&pelicula=$pelicula&cine=$cine&usuario_registro=$usuario_registro&salida=";
//phpinfo();

//Si en el filtro viene la Película busco el nombre
if($pelicula > 0):
	$qry = "SELECT nombre_espanol FROM tbl_pelicula WHERE id_pelicula = ".$pelicula;
	$result = mysql_query($qry);
	if($campo_pelicula=mysql_fetch_object($result)):
		$nombre_pelicula = $campo_pelicula->nombre_espanol;
	endif;
endif;

//echo $fecha_actual ."<br>";
$qry = "SELECT t.usuario_registro, u.nombre_completo, SUM( t.cantidad_transaccion*dosporuno ) AS boletos, SUM( t.total_transaccion ) AS monto, SUM(t.comision*t.cantidad_transaccion) as comision, tp.fecha_programacion, c.nombre as cine ,sum(t.efectivo ) efectivo,sum(t.tarjeta) tarjetas ,sum(t.pm) pm,sum(t.tasa*(t.dolar-(cambio/tasa))) dolar  ";
$qry .= "FROM tbl_operacion t   ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
$qry .= "INNER JOIN tbl_usuario u ON u.username = t.usuario_registro ";
$qry .= "INNER JOIN tbl_cine c ON c.id_cine = t.id_cine ";
$qry .= "INNER JOIN tbl_programacion pr ON t.id_programacion = pr.id_programacion ";
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
	$qry .= "AND pr.id_pelicula = $pelicula ";
endif;
$qry .= "GROUP BY t.usuario_registro, u.nombre_completo, tp.fecha_programacion ";
$qry .= "ORDER BY u.nombre_completo,  tp.fecha_programacion  ";

$result = mysql_query($qry) or die("Error en Consulta: " . mysql_error());

?>
<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
.encabezado-reporte {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	background-color:#666666;
	color:#FFFFFF;
}
.reporte {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color:#666666;
}
</style>


<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<?php
	if($salida == 'pan'):?>
		<tr>
			<td width="100%" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get;?>imp')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			
			<td width="60" align="right" valign="middle" ><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')"><img src="images/excel.jpg" border="0"></a>&nbsp;&nbsp;</td>
					
		</tr>
	<?php endif;?>
	<tr>
		<td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Ventas Diarias Usuario</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$total_cine_monto = 0;
		$total_cine_boletos = 0;
		$total_e = 0;
		$total_d = 0;
		$total_p = 0;
		$total_t = 0;
		
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='6' class='titulo-reporte'>".ucfirst(strftime("%A", strtotime($fecha_actual))) .", ".date('d/m/Y', strtotime($fecha_actual))."</td> \n";
		$table .= "</tr> \n";
		
		while($campo=mysql_fetch_object($result)):

			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Cine:</b> $cine_actual</td> \n";
				$table .= "</tr> \n";
				if(!empty($nombre_pelicula)):
					$table .= "<tr> \n";
					$table .= "<td align='left' height='21' colspan='6' class='pelicula-nombre'><b>Pel&iacute;cula:</b> $nombre_pelicula</td> \n";
					$table .= "</tr> \n";
				endif;
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FECHA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >USUARIO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETOS</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >MONTO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >EFECTIVO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >TARJETAS</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PAGO MOVIL</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >EFECTIVO $</td> \n";
				
				$table .= "<td align='center' height='21' class='encabezado-reporte' ><img src='images/tr.gif'></td> \n";
				$table .= "</tr> \n";
			endif;
			
			$table .= "<tr> \n";
			$table .= "<td width='15%' align='center' class='info' height='32'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
			$table .= "<td width='15%' align='center' class='info'>".htmlentities($campo->nombre_completo)."(".$campo->usuario_registro.")" ."</td> \n";
			$table .= "<td width='15%' align='center' class='info'>".number_format($campo->boletos, 0, ',', '.')."</td> \n";
			$monto = $campo->monto+$campo->comision;
			$table .= "<td width='12%' align='right' class='info'>".number_format($monto, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->efectivo, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->tarjetas, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->pm, 2, ',', '.')."</td> \n";
			$table .= "<td width='12%' align='right' class='info'>".number_format($campo->dolar, 2, ',', '.')."</td> \n";
			$table .= "<td ><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
			$total_e += $campo->efectivo;
			$total_t += $campo->tarjetas;
			$total_p += $campo->pm;
			$total_d += $campo->dolar;
		
			$total_cine_boleto = $total_cine_boleto + $campo->boletos;
			$total_cine_monto = $total_cine_monto + $monto;
			
		endwhile;
		
		$table .= "<tr> \n <td colspan='6' height='7'><img src='images/tr.gif'></td> \n </tr>\n";
		$table .= "<tr> \n";
		$table .= "<td align='right'colspan='2' class='total-pelicula'>Total Cine $cine_actual: </td> \n";
		$table .= "<td align='center' class='cine-total'>".number_format($total_cine_boleto, 0, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_cine_monto, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_e, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_t, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_p, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_d, 2, ',', '.')."</td> \n";
		$table .= "<td ><img src='images/tr.gif'></td> \n";
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

