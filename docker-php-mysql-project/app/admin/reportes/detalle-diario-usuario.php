<?php

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_REQUEST['sala']) ? $_REQUEST['sala'] : 0;
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$usuario_registro = isset($_REQUEST['usuario_registro']) ? $_REQUEST['usuario_registro'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=detalle-diario-usuario&fecha1=".$_REQUEST['fecha1']."&pelicula=pelicula&cine=$cine&usuario_registro=$usuario_registro&salida=imp";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT t.usuario_registro, u.nombre_completo, pe.nombre_espanol as pelicula, pre.nombre as precio, SUM( t.cantidad_transaccion ) AS boletos, SUM( t.total_transaccion ) AS monto, tp.fecha_programacion, c.nombre as cine, ";
$qry .= "pre.costo, pre.comision ,dosporuno   ";
$qry .= "FROM tbl_operacion t   ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_usuario u ON u.username = t.usuario_registro ";
$qry .= "INNER JOIN tbl_cine c ON c.id_cine = t.id_cine ";
$qry .= "INNER JOIN tbl_programacion pr ON t.id_programacion = pr.id_programacion ";
$qry .= "INNER JOIN tbl_precio pre ON t.id_precio = pre.id_precio ";
$qry .= "INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula ";
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
$qry .= "GROUP BY t.usuario_registro, u.nombre_completo, tp.fecha_programacion, pe.nombre_espanol, pre.nombre,dosporuno  ";
$qry .= "ORDER BY u.nombre_completo,  tp.fecha_programacion, pe.nombre_espanol, pre.nombre  ";
//echo $qry;
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
			<td width="100%" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get;?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get;?>')" class="imprimir">Imprimir</a></td>
		</tr>
	<?php endif;?>
	<tr>
		<td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Detalle Diarios Usuario</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$usuario_actual = "";
		$pelicula_actual = "";
		$total_usuario_monto = 0;
		$total_usuario_boletos = 0;
		$total_cine_monto = 0;
		$total_cine_boletos = 0;

		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='4' class='titulo-reporte'>".ucfirst(strftime("%A", strtotime($fecha_actual))) .", ".date('d/m/Y', strtotime($fecha_actual))."</td> \n";
		$table .= "</tr> \n";
		
		while($campo=mysql_fetch_object($result)):
			
			/* ENCABEZADO CINE */
			if($cine_actual != $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'><b>Cine:</b> $cine_actual</td> \n";
				$table .= "</tr> \n";
				
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PELICULA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PRECIO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETOS</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >MONTO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' ><img src='images/tr.gif'></td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO USUARIO */
			if($usuario_actual != htmlentities($campo->nombre_completo)."(".$campo->usuario_registro.")"):
				$usuario_actual = htmlentities($campo->nombre_completo)."(".$campo->usuario_registro.")";
				if($total_usuario_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='right'colspan='2' class='total-pelicula'>Total Usuario $usuario_actual: </td> \n";
					$table .= "<td align='center' class='cine-total'>".number_format($total_usuario_boletos, 0, ',', '.')."</td> \n";
					$table .= "<td align='right' class='cine-total'>".number_format($total_usuario_monto, 2, ',', '.')."</td> \n";
					$table .= "<td ><img src='images/tr.gif'></td> \n";
					$table .= "</tr> \n";
				endif;
				
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='4' class='pelicula-nombre'><b>Usuario:</b> ".htmlentities($campo->nombre_completo)."(".$campo->usuario_registro.")" ."</td> \n";
				$table .= "</tr> \n";
				
				$total_usuario_monto = 0;
				$total_usuario_boletos = 0;
				$pelicula_actual = "";
			endif;
			
			/* ENCABEZADO PELICULA */
			if($pelicula_actual != $campo->pelicula && !empty($pelicula_actual)):
				$table .= "<tr> \n";
				$table .= "<td height='21' colspan='4'><img src='images/tr.gif'></td> \n";
				$table .= "</tr> \n";
			endif;
			$pelicula_actual = $campo->pelicula;
			
			$table .= "<tr> \n";
			$table .= "<td width='35%' align='left' class='info'>".$campo->pelicula."</td> \n";
			$table .= "<td width='20%' align='center' class='info'>".$campo->precio."</td> \n";
			$table .= "<td width='15%' align='center' class='info'>".number_format($campo->boletos*$campo->dosporuno, 0, ',', '.')."</td> \n";
			$monto = ($campo->boletos * ($campo->costo+$campo->comision));
			$table .= "<td width='15%' align='right' class='info'>".number_format($monto, 2, ',', '.')."</td> \n";
			$table .= "<td ><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
			
			$total_cine_boleto = $total_cine_boleto + ($campo->boletos*$campo->dosporuno);
			$total_cine_monto = $total_cine_monto + $monto;
			$total_usuario_monto = $total_usuario_monto + $monto;
			$total_usuario_boletos = $total_usuario_boletos + ($campo->boletos*$campo->dosporuno);
			
		endwhile;
		if($total_usuario_boletos > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right'colspan='2' class='total-pelicula'>Total Usuario $usuario_actual: </td> \n";
			$table .= "<td align='center' class='cine-total'>".number_format($total_usuario_boletos, 0, ',', '.')."</td> \n";
			$table .= "<td align='right' class='cine-total'>".number_format($total_usuario_monto, 2, ',', '.')."</td> \n";
			$table .= "<td ><img src='images/tr.gif'></td> \n";
			$table .= "</tr> \n";
		endif;
		
		$table .= "<tr> \n <td colspan='4' height='7'><img src='images/tr.gif'></td> \n </tr>\n";
		$table .= "<tr> \n";
		$table .= "<td align='right'colspan='2' class='total-pelicula'>Total Cine $cine_actual: </td> \n";
		$table .= "<td align='center' class='cine-total'>".number_format($total_cine_boleto, 0, ',', '.')."</td> \n";
		$table .= "<td align='right' class='cine-total'>".number_format($total_cine_monto, 2, ',', '.')."</td> \n";
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

