<?php
session_start();
include_once "../include/bd.inc.php";
include '../include/func.glb.php';
include '../include/config.inc.php';


$fecha_actual = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d', time());

$fecha_impresion = date('d/m/Y H:i a', time());
$sala = isset($_GET['sala']) ? $_GET['sala'] : 0;

$cine = buscar_cine(CINE_TAQUILLA);

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
if($sala > 0):
	$qry .= "AND ts.id_sala = $sala ";
endif;
$qry .= "GROUP BY tper.nombre, t.costo, tpe.nombre_espanol, ts.nombre, tc.nombre, t.id_programacion, t.id_precio,  ";
$qry .= "tp.id_cine, tp.id_pelicula, tp.id_sala,  tp.hora_inicio, tp.fecha_programacion ";
$qry .= "ORDER BY tpe.nombre_espanol, tp.hora_inicio, ts.nombre, tper.nombre ";
//echo $qry;
$result = mysql_query($qry);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ventas Por Sala</title>
<link href="../css/reportes.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="90%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" align="center" class="titulo-reporte">VENTAS DÍA TAQUILLA</td>
	</tr>
	<tr>
		<td align="left" width="50%" class="cine-nombre"><b><?php echo $cine;?></b></td>
		<td align="right" width="50%" class="fecha"><b>Fecha:</b> <?php echo $fecha_impresion;?></td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
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
		$funcion_nombre = '';
		$total_pelicula_monto = 0;
		$total_pelicula_boletos = 0;
		while($campo=mysql_fetch_object($result)):
			
			/* ENCABEZADO PELICULA */
			if($pelicula_actual != $campo->pelicula):
				$pelicula_actual = $campo->pelicula;
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' ><img src='../images/tr.gif'></td> \n";
					$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
					$table .= "</tr> \n";	
					
					//Total Película
					if($total_pelicula_monto > 0):
						$table .= "<tr> \n";
						$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Película: </td> \n";
						$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
						$table .= "</tr> \n";
						
						$total_pelicula_monto = 0;
						$total_pelicula_boletos = 0;
					endif;
					
					
					$total_boletos = $total_boletos + $subtotal_boletos;
					$total_monto = $total_monto + $subtotal_monto;
					$subtotal_boletos = 0;
					$subtotal_monto = 0;
				endif;
												
				$table .= "<tr> \n";
				$table .= "<td align='left' height='31' colspan='6' class='pelicula-nombre'><b>Película:</b> $pelicula_actual</td> \n";
				$table .= "</tr> \n";
			endif;
			
			/* ENCABEZADO SALA */
			/*if($campo->sala != $sala_actual):
				if($subtotal_boletos > 0):
					$table .= "<tr> \n";
					$table .= "<td align='left' colspan='5' ><img src='../images/tr.gif'></td> \n";
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
					$table .= "<td align='left' colspan='5' ><img src='../images/tr.gif'></td> \n";
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
			$table .= "<td width='20%' align='right' class='info'><img src='../images/tr.gif'></td> \n";
			$table .= "</tr> \n";
			
			$funcion_nombre = '';
			$subtotal_boletos = $subtotal_boletos + $campo->boletos;
			$subtotal_monto = $subtotal_monto + $campo->monto;
			
			$total_pelicula_monto = $total_pelicula_monto + $campo->monto;
			$total_pelicula_boletos = $total_pelicula_boletos + $campo->boletos;
		endwhile;
		
		if($subtotal_boletos > 0):
			$table .= "<tr> \n";
			$table .= "<td align='left' colspan='5' ><img src='../images/tr.gif'></td> \n";
			$table .= "<td width='20%' align='right' class='info'><b>".number_format($subtotal_monto, 2, ',', '.')."</b></td> \n";
			$table .= "</tr> \n";	
		endif;
	
		//Total Película
		if($total_pelicula_monto > 0):
			$table .= "<tr> \n";
			$table .= "<td align='right' colspan='5' class='total-pelicula'>Total Película: </td> \n";
			$table .= "<td width='20%' align='right' class='pelicula-total'>".number_format($total_pelicula_monto, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endif;

		echo $table;
	endif;
	?>	
	</table></td>
	</tr>
</table>
</body>
</html>
