<?php
include_once "../../include/bd.inc.php";
session_start();

$fecha_actual = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d', time());;

$fecha_impresion = "Fecha de Impresión: " . date('d/m/Y H:i a', time());

//echo $fecha_actual ."<br>";
$qry = "SELECT tper.nombre AS precio, t.costo, SUM( t.cantidad_transaccion ) AS boletos, SUM( t.total_transaccion ) AS monto, ";
$qry .= "tpe.nombre_espanol AS pelicula, ts.nombre AS sala, tc.nombre AS cine, t.id_programacion, t.id_precio, tp.id_cine, ";
$qry .= "tp.id_sala, tp.id_pelicula, tp.hora_inicio as funcion, tp.fecha_programacion ";
$qry .= "FROM tbl_operacion t ";
$qry .= "INNER JOIN tbl_programacion tp ON t.id_programacion = tp.id_programacion ";
$qry .= "INNER JOIN tbl_pelicula tpe ON tp.id_pelicula = tpe.id_pelicula ";
$qry .= "INNER JOIN tbl_sala ts ON tp.id_sala = ts.id_sala ";
$qry .= "INNER JOIN tbl_cine tc ON tp.id_cine = tc.id_cine ";
$qry .= "INNER JOIN tbl_precio tper ON t.id_precio = tper.id_precio ";
$qry .= "WHERE DATE(t.fecha_operacion) = '$fecha_actual' ";
$qry .= "GROUP BY tper.nombre, t.costo, tpe.nombre_espanol, ts.nombre, tc.nombre, t.id_programacion, t.id_precio,  ";
$qry .= "tp.id_cine, tp.id_sala, tp.id_pelicula, tp.hora_inicio, tp.fecha_programacion ";
$qry .= "ORDER BY ts.nombre, tp.hora_inicio, tper.nombre ";
//echo $qry;
$result = mysql_query($qry);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ventas Por Sala</title>
</head>

<body>
<table width="80%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">VENTAS / SALA</td>
	</tr>
	<tr>
		<td align="center"><?=$fecha_impresion;?></td>
	</tr>
	
	<?php
	if($result):
	$table = "";
	$pelicula_actual = "";
	$funcion_actual = "";
	$subtotal_boletos = 0;
	$subtotal_monto = 0;
	$total_boletos = 0;
	$total_monto = 0;
	$sala_actual = 0;
	while($campo=mysql_fetch_object($result)):
		if($campo->sala != $sala_actual):
			if($subtotal_boletos > 0):
				$table .= "<tr> \n";
				$table .= "<td align='left' height='31'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
				$table .= "<td width='30%' align='center'><img src='../images/tr.gif'></td> \n";
				$table .= "<td width='30%' align='center'>SUB-TOTALES</td> \n";
				$table .= "<td width='30%' align='right'>$subtotal_boletos</td> \n";
				$table .= "<td width='30%' align='right'>".number_format($subtotal_monto, 2, ',', '.')."</td> \n";
				$table .= "</tr> \n </table> \n </td> \n";
				$table .= "</tr> \n";	
			endif;
			$sala_actual = $campo->sala;
			$table .= "<tr> \n";
			$table .= "<td align='left'>SALA: $campo->sala</td> \n";
			$table .= "</tr> \n";
		endif;
		
		if($pelicula_actual != $campo->pelicula):
			$pelicula_actual = $campo->pelicula;
			if($subtotal_boletos > 0):			
				$total_boletos = $total_boletos + $subtotal_boletos;
				$total_monto = $total_monto + $subtotal_monto;
				$subtotal_boletos = 0;
				$subtotal_monto = 0;
			endif;
			
			
			$table .= "<tr> \n";
			$table .= "<td align='left' height='31'>PELÍCULA: $pelicula_actual</td> \n";
			$table .= "</tr> \n";
		endif;
		
		if($funcion_actual != $campo->funcion):
			$funcion_actual = $campo->funcion;
			
			if($subtotal_boletos > 0):
				$table .= "<tr> \n";
				$table .= "<td align='left' height='31'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
				$table .= "<td width='30%' align='center'><img src='../images/tr.gif'></td> \n";
				$table .= "<td width='30%' align='center'>SUB-TOTALES</td> \n";
				$table .= "<td width='30%' align='right'>$subtotal_boletos</td> \n";
				$table .= "<td width='30%' align='right'>".number_format($subtotal_monto, 2, ',', '.')."</td> \n";
				$table .= "</tr> \n </table> \n </td> \n";
				$table .= "</tr> \n";	
			
				$total_boletos = $total_boletos + $subtotal_boletos;
				$total_monto = $total_monto + $subtotal_monto;
				$subtotal_boletos = 0;
				$subtotal_monto = 0;
			endif;
			$table .= "<tr> \n";
			$table .= "<td align='left'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
			$table .= "<td width='30%' align='left'>FUNCION: ". date('h:i a', strtotime($funcion_actual)) . "</td> \n";
			$table .= "<td width='30%' align='center'>PRECIO</td> \n";
			$table .= "<td width='30%' align='center'>CANTIDAD</td> \n";
			$table .= "<td width='30%' align='center'>TOTAL</td> \n";
			$table .= "</tr> \n </table> \n </td> \n";
			$table .= "</tr> \n";
		endif;
		
		$table .= "<tr> \n";
		$table .= "<td align='left'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
		$table .= "<td width='40%' align='left'>".htmlentities($campo->precio) ."</td> \n";
		$table .= "<td width='20%' align='right'>".number_format($campo->costo, 2, ',', '.')."</td> \n";
		$table .= "<td width='20%' align='right'>".number_format($campo->boletos, 0, ',', '.')."</td> \n";
		$table .= "<td width='20%' align='right'>".number_format($campo->monto, 2, ',', '.')."</td> \n";
		$table .= "</tr> \n </table> \n </td> \n";
		$table .= "</tr> \n";

		$subtotal_boletos = $subtotal_boletos + $campo->boletos;
		$subtotal_monto = $subtotal_monto + $campo->monto;
	endwhile;
	
	$table .= "<tr> \n";
	$table .= "<td align='left' height='31'><table width='70%' align='left' cellspading='1' cellspacing='1'> \n <tr> \n";
	$table .= "<td width='30%' align='center'><img src='../images/tr.gif'></td> \n";
	$table .= "<td width='30%' align='center'>SUB-TOTALES</td> \n";
	$table .= "<td width='30%' align='center'>$subtotal_boletos</td> \n";
	$table .= "<td width='30%' align='center'>$subtotal_monto</td> \n";
	$table .= "</tr> \n </table> \n </td> \n";
	$table .= "</tr> \n";	

	$total_boletos = $total_boletos + $subtotal_boletos;
	$total_monto = $total_monto + $subtotal_monto;

	echo $table;
	endif;
	?>	
</table>
</body>
</html>
