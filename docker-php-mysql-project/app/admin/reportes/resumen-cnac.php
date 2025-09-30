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

//Busco todas la programación de acuerdo a la fecha establecida
$qry = "SELECT p.id_programacion,
		date(p.fecha_programacion) as fecha_programacion,
		c.nombre as cine,
		s.nombre as sala, 
		pe.nombre_espanol as pelicula, 
		p.hora_inicio as funcion ";
$qry .= "FROM tbl_programacion p 
		INNER JOIN	tbl_sala s ON p.id_sala = s.id_sala
		INNER JOIN	tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula
		INNER JOIN	tbl_cine c ON p.id_cine = c.id_cine ";
$qry .= "WHERE fecha_programacion >= '$fecha_desde' AND fecha_programacion <= '$fecha_hasta' ";
if($pelicula > 0):
	$qry .= "AND  pe.id_pelicula = $pelicula ";
endif;
if($funcion <> 0):
	switch($funcion):
	
		case MATINEE:
			$qry .= "AND (p.hora_inicio < '". VESPERTINA ."') ";
			break;	
		
		case VESPERTINA:
			$qry .= "AND (p.hora_inicio >= '".VESPERTINA."' AND p.hora_inicio < '". INTERMEDIA ."') ";
			break;
			
		case INTERMEDIA:
			$qry .= "AND (p.hora_inicio >= '".INTERMEDIA."' AND p.hora_inicio < '". NOCHE ."') ";
			break;
		
		case NOCHE:
			$qry .= "AND (p.hora_inicio >= '".NOCHE."' AND p.hora_inicio < '". MEDIANOCHE ."') ";
			break;
			
		case MEDIANOCHE:
			$qry .= "AND p.hora_inicio >= '".MEDIANOCHE."' ";
			break;
			
	endswitch;
endif;
$qry .= "ORDER BY date(fecha_programacion), p.id_sala, p.hora_inicio";

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
        	<td align="right" valign="middle"><table cellpadding="0" cellspacing="0" align="right" >
            	<tr>
					<td width="30" align="right" valign="top" ><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;</td>
					<td width="60" align="center" class="imprimir" valign="top"><a href="#" onclick="imprimir_reporte('<?php echo $get."imp";?>')" class="imprimir">Imprimir</a>&nbsp;&nbsp;</td>
            		<td width="30" align="right" valign="middle" ><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')"><img src="images/excel.jpg" border="0"></a>&nbsp;&nbsp;</td>
					<td width="60" align="center" class="imprimir" valign="top"><a href="#" onclick="imprimir_reporte('<?php echo $get."exc";?>')" class="imprimir">Excel</a></td>
				</tr>
             </table></td>
        </tr>
	<?php endif;?>
	<tr>
		
    <td align="left" width="90%" class="titulo-reporte" valign="middle" height="30">Resumen CNAC</td>
	</tr>
	<tr>
		<td ><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$funcion_nombre = '';
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='10' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		
		while($campo=mysql_fetch_object($result)):
			$id_programacion = $campo->id_programacion;

			if($cine_actual <> $campo->cine):
				$cine_actual = $campo->cine;
				//$table .= "<tr> \n";
				//$table .= "<td align='left' height='21' colspan='9' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				//$table .= "</tr> \n";
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FECHA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >CINE</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >SALA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PELICULA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >HORA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FUNCION</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PRECIO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ESPECTADORES</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >RECAUDACION</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IMPUESTO</td> \n";
				$table .= "</tr> \n";
			endif;
			
			$qry = "SELECT	pr.comentario as boleto, 
							pr.costo as precio,
							pr.foncine as imp,
							sum(cantidad_transaccion) as espectador, 
							sum(total_transaccion) as recaudacion ";
			$qry .= "FROM	tbl_operacion o INNER JOIN tbl_precio pr ON o.id_precio = pr.id_precio ";
			$qry .= "WHERE  o.id_programacion = $id_programacion AND pr.costo > 1 ";
			$qry .= "GROUP BY pr.comentario, pr.costo ";
			$qry .= "ORDER BY pr.id_precio ";
			$r_operacion = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
			//Pregunto si trae registro, para hacer el ciclo o no
			if(mysql_num_rows($r_operacion) > 0):
				while($campo2=mysql_fetch_object($r_operacion)):
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("h:i a", strtotime($campo->funcion))."</td> \n";
					/* NOMBRE FUNCION */
					
					if($campo->funcion < VESPERTINA):
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
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->boleto."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->precio."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->espectador."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($campo2->recaudacion, 2, '.', '')."</td> \n";
					$base_imponible = ($campo2->recaudacion / (($campo2->imp / 100) + 1));
					$impuesto = ($base_imponible * $campo2->imp) / 100;
					$table .= "<td align='center' height='21' class='reporte'>".number_format($impuesto, 2, '.', '')."</td> \n";
					$table .= "</tr> \n";
				endwhile;
			else:
				//Si no trae registros, coloco el encabezado y la palaba SIN FUNCIONES
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
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
				$table .= "<td align='center' height='21' class='reporte' colspan='5'>- SIN FUNCION - </td> \n";
				$table .= "</tr> \n";
			endif;
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