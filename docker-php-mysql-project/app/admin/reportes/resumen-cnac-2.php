<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;
$funcion = isset($_REQUEST['funcion']) ? $_REQUEST['funcion'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=resumen-cnac&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&pelicula=$pelicula&funcion=$funcion&salida=";
//phpinfo();

//echo $fecha_actual ."<br>";
$qry = "SELECT	date(p.fecha_programacion) as fecha_operacion,
				c.nombre as cine,
				s.nombre as sala,  
				pe.nombre_espanol as pelicula, 
				p.hora_inicio as funcion, 
				pr.nombre as boleto, 
				pr.costo as precio, 
				sum(cantidad_transaccion) as espectador, 
				sum(total_transaccion) as recaudacion ";
$qry .= " FROM	tbl_operacion o
	INNER JOIN	tbl_programacion p	ON p.id_programacion = o.id_programacion
	INNER JOIN	tbl_sala s ON p.id_sala = s.id_sala
	INNER JOIN	tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula
	INNER JOIN	tbl_precio pr ON o.id_precio = pr.id_precio
	INNER JOIN	tbl_cine c ON o.id_cine = c.id_cine ";

$qry .= "WHERE o.status = 1 AND ";
$qry .= "p.fecha_programacion >= '$fecha_desde' AND p.fecha_programacion <= '$fecha_hasta' ";
if($pelicula > 0):
	$qry .= "AND  pe.id_pelicula = $pelicula ";
endif;
if($funcion <> 0):
	switch($funcion):
	
		case DIURNO:
			$qry .= "AND (pr.hora_inicio >= '".DIURNO."' AND pr.hora_inicio < '". MATINEE ."') ";
			break;	
		
		case MATINEE:
			$qry .= "AND (pr.hora_inicio >= '".MATINEE."' AND pr.hora_inicio < '". VESPERTINA ."') ";
			break;	
		
		case VESPERTINA:
			$qry .= "AND (pr.hora_inicio >= '".VESPERTINA."' AND pr.hora_inicio < '". INTERMEDIA ."') ";
			break;
			
		case INTERMEDIA:
			$qry .= "AND (pr.hora_inicio >= '".INTERMEDIA."' AND pr.hora_inicio < '". NOCHE ."') ";
			break;
		
		case NOCHE:
			$qry .= "AND (pr.hora_inicio >= '".NOCHE."' AND pr.hora_inicio < '". MEDIANOCHE ."') ";
			break;
			
		case MEDIANOCHE:
			$qry .= "AND pr.hora_inicio >= '".INTERMEDIA."' ";
			break;
			
	endswitch;
endif;

$qry .= " GROUP BY date(p.fecha_programacion),  s.nombre,  pe.nombre_espanol, p.hora_inicio, pr.nombre, pr.costo ";
$qry .= " ORDER BY date(p.fecha_programacion), s.nombre, p.hora_inicio, pr.costo ";

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
			<td width="100%" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')" class="imprimir">Imprimir</a>&nbsp;&nbsp;</td>
            <td width="30" align="right" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')"><img src="images/excel.jpg" border="0"></a>&nbsp;&nbsp;</td>
			<td width="60" align="center" class="imprimir" valign="middle"><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')" class="imprimir">Excel</a></td>
		</tr>
	<?php endif;?>
	<tr>
		
    <td align="left" colspan="2" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen CNAC</td>
	</tr>
	<tr>
		<td colspan="2"><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$funcion_nombre = '';
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='9' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		
		while($campo=mysql_fetch_object($result)):
			if($cine_actual <> $campo->cine):
				$cine_actual = $campo->cine;
				$table .= "<tr> \n";
				$table .= "<td align='left' height='21' colspan='9' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				$table .= "</tr> \n";
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FECHA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >SALA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PELICULA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >HORA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FUNCION</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PRECIO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ESPECTADORES</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >RECAUDACION</td> \n";
				$table .= "</tr> \n";
			endif;
			$table .= "<tr> \n";
			$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_operacion))."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".date("h:i a", strtotime($campo->funcion))."</td> \n";
			/* NOMBRE FUNCION */
			if($campo->funcion >= DIURNO && $campo->funcion < MATINEE):
				$funcion_nombre = "DIURNO";
			endif;
			
			if($campo->funcion >= MATINEE && $campo->funcion < VESPERTINA):
				$funcion_nombre = "MATINEE";
			endif;
			
			if($campo->funcion >= VESPERTINA && $campo->funcion < INTERMEDIA):
				$funcion_nombre = "VESPERTINA";
			endif;
			
			if($campo->funcion >= INTERMEDIA && $campo->funcion < NOCHE):
				$funcion_nombre = "INTERMEDIA";
			endif;

			if($campo->funcion >= NOCHE && $campo->funcion < MEDIANOCHE):
				$funcion_nombre = "NOCHE";
			endif;
				
			if($campo->funcion >= MEDIANOCHE):
				$funcion_nombre = "MEDIANOCHE";
			endif;	
			$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo->boleto."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo->precio."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo->espectador."</td> \n";
			$table .= "<td align='center' height='21' class='reporte'>".number_format($campo->recaudacion, 2, ',', '.')."</td> \n";
			$table .= "</tr> \n";
		endwhile;
	else:
		$table = "<tr> \n";
		$table .= "<td align='left' height='21' class='mensaje-consulta'>No hay datos para los rangos seleccionados</td> \n";
		$table .= "</tr> \n";
	endif;
	echo $table;
	?>	
	</table></td>
	</tr>
</table>