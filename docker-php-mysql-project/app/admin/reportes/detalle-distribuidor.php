<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$distribuidor = isset($_REQUEST['distribuidor']) ? $_REQUEST['distribuidor'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=detalle-distribuidor&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&distribuidor=$distribuidor&salida=";

//phpinfo();

//Busco los datos de la configuración
$qry = "SELECT fomprocine ";
$qry .= "FROM tbl_configuracion";
$fc_result= mysql_query($qry) or die("Error en Consulta: " . mysql_error());
if($campo = mysql_fetch_object($fc_result)):
	$imp_fonprocine = $campo->fomprocine;
endif;

//Busco los datos del distribuidor
$qry = "SELECT nombre as nombre_distribuidor ";
$qry .= "FROM tbl_distribuidor WHERE id_distribuidor = ".$distribuidor;
$fc_result= mysql_query($qry) or die("Error en Consulta: " . mysql_error());
if($campo = mysql_fetch_object($fc_result)):
	$nombre_distribuidor = $campo->nombre_distribuidor;
endif;

//Busco todas la programación de acuerdo a la fecha establecida
$qry = "SELECT p.id_programacion,
		date(p.fecha_programacion) as fecha_programacion,
		c.nombre as cine,
		s.nombre as sala, 
		pe.nombre_espanol as pelicula,
		pe.registro_obra as registro_obra,
		fp.formato_proyeccion as formato, 
		pe.id_procedencia as procedencia,
		p.hora_inicio as funcion ";

$qry .= "FROM tbl_programacion p 
		INNER JOIN	tbl_sala s ON p.id_sala = s.id_sala
		INNER JOIN	tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula
		INNER JOIN	tbl_cine c ON p.id_cine = c.id_cine 
		LEFT JOIN	tbc_formato_proyeccion fp ON pe.id_formato_proyeccion = fp.id_formato_proyeccion ";
$qry .= "WHERE fecha_programacion >= '$fecha_desde' AND fecha_programacion <= '$fecha_hasta' ";
$qry .= "AND pe.id_distribuidor = $distribuidor ";
/*if($funcion <> 0):
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
endif;*/
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
    	<td align="left" width="90%" class="titulo-reporte" valign="middle" height="30">Liquidaci&oacute;n Distribuidor</td>
	</tr>
	<tr>
		<td ><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(mysql_num_rows($result) > 0):
		$table = "";
		$cine_actual = "";
		$funcion_nombre = '';

		$total_boletos = 0;
		$total_base = 0;
		$total_recaudacion = 0;
		$total_impuesto = 0;
		$total_fonprocine = 0;
		$total_neto = 0;
		$total_iva = 0;
		$total_film_rental = 0;
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='19' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";

		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='19' class='pelicula-nombre'>Distribuidor: <b>".$nombre_distribuidor."</b></td> \n";
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
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ORD.REG</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PELICULA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FORMATO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >HORA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >FUNCION</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >PRECIO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BOLETOS</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >RECAUDACION</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >BASE IMPONIBLE</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >%</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IVA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' colspan='2'>IMPUESTO</td> \n"; //Esta columna son 2
				$table .= "<td align='center' height='21' class='encabezado-reporte' colspan='2'>FONPROCINE</td> \n"; //Esta columna son 2
				$table .= "<td align='center' height='21' class='encabezado-reporte' >NETO</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' colspan='2'>FILM RENTAL</td> \n"; //Esta columna son 2
				$table .= "</tr> \n";
			endif;
			
			$qry = "SELECT	pr.comentario as boleto, 
							o.costo as precio,
							o.foncine as imp,
							CASE WHEN pr2.hora_inicio <= '16:30:00' 
							THEN 40
							ELSE (case when (SELECT
							tbl_pelicula_distribuidor.porcentaje
							FROM
							tbl_pelicula_distribuidor
							WHERE
							tbl_pelicula_distribuidor.id_pelicula=pr2.id_pelicula and
							tbl_pelicula_distribuidor.fecha_inicio<=pr2.fecha_programacion AND
							tbl_pelicula_distribuidor.fecha_fin>=pr2.fecha_programacion) is null then 40 ELSE
							(SELECT
							tbl_pelicula_distribuidor.porcentaje
							FROM
							tbl_pelicula_distribuidor
							WHERE
							tbl_pelicula_distribuidor.id_pelicula=pr2.id_pelicula and
							tbl_pelicula_distribuidor.fecha_inicio<=pr2.fecha_programacion AND
							tbl_pelicula_distribuidor.fecha_fin>=pr2.fecha_programacion) end) END as porcentaje_distribuidor,o.municipal,o.iva,
							sum(cantidad_transaccion*dosporuno) as espectador, 
							sum(total_transaccion) as recaudacion ";
			$qry .= "FROM	tbl_operacion o INNER JOIN tbl_programacion pr2 ON o.id_programacion = pr2.id_programacion  INNER JOIN tbl_precio pr ON o.id_precio = pr.id_precio ";
			$qry .= "WHERE  o.id_programacion = $id_programacion AND pr.costo > 0 and o.total_transaccion <> 0 and o.status = 1 ";
			$qry .= "GROUP BY pr.comentario, 
							pr.costo ,
							o.foncine,
							o.porcentaje_distribuidor,o.municipal,o.iva ";
			$qry .= "ORDER BY pr.id_precio ";
			$r_operacion = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
			//Pregunto si trae registro, para hacer el ciclo o no
			if(mysql_num_rows($r_operacion) > 0):
				while($campo2=mysql_fetch_object($r_operacion)):
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->registro_obra."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->formato."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("H:i", strtotime($campo->funcion))."</td> \n";
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
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->recaudacion, 2, ',', '.')."</td> \n";
					$base_imponible=($campo2->recaudacion*100)/(100+$campo2->municipal+$campo2->iva);
					$table .= "<td align='center' height='21' class='reporte'>".number_format($base_imponible, 2, ',', '.')."</td> \n";
					
					//IMPUESTO MUNICIPAL
					$impuesto = ($base_imponible * $campo2->municipal) / 100;
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->iva."%</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".number_format($campo2->iva*$base_imponible/100, 2, ',', '.')."</td> \n";

					$table .= "<td align='center' height='21' class='reporte'>".$campo2->municipal."%</td> \n";
					$table .= "<td align='right' height='21' class='reporte'>".number_format($impuesto, 2, ',', '.')."</td> \n";
					//IMPUESTO FONPROCINE
					if($campo->procedencia == 1):
						$imp_fon = 0;
					else:
						$imp_fon = $campo2->imp;
					endif;
					$fonprocine = ($base_imponible * $campo2->imp) / 100;
					$table .= "<td align='center' height='21' class='reporte'>".number_format($imp_fon, 2, ',', '.')."%</td> \n";
					$table .= "<td align='right' height='21' class='reporte'>".number_format($fonprocine, 2, ',', '.')."</td> \n";
					//NETO
					$neto = $base_imponible - $fonprocine;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($neto, 2, ',', '.')."</td> \n";
					//FILM RENTAL
					$porcentaje_distribuidor = ($campo->funcion < VESPERTINA) ? '40' : $campo2->porcentaje_distribuidor;
					$film_rental = ($neto * $porcentaje_distribuidor) / 100;
					$table .= "<td align='center' height='21' class='reporte'>".$porcentaje_distribuidor."%</td> \n";
					$table .= "<td align='right' height='21' class='reporte'>".number_format($film_rental, 2, ',', '.')."</td> \n";
					$table .= "</tr> \n";
					$total_base = $total_base + $base_imponible;
					$total_boletos = $total_boletos + $campo2->espectador;
					$total_iva = $total_iva + ($campo2->iva*$base_imponible/100);
					$total_recaudacion = $total_recaudacion + $campo2->recaudacion;
					$total_impuesto = $total_impuesto + $impuesto;
					$total_fonprocine = $total_fonprocine + $fonprocine;
					$total_neto = $total_neto + $neto;
					$total_film_rental = $total_film_rental + $film_rental;
				endwhile;
			else:
				//Si no trae registros, coloco el encabezado y la palaba SIN FUNCIONES
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->registro_obra."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->formato."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".date("H:i", strtotime($campo->funcion))."</td> \n";
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
				$table .= "<td align='center' height='21' class='reporte' colspan='15'>- SIN FUNCION - </td> \n";
				$table .= "</tr> \n";
			endif;
		endwhile;
		//Fila de Totales
		$table .= "<tr> \n";
		$table .= "<td align='right' height='21' class='info-total2' colspan='10'>TOTALES DISTRIBUIDOR (".$nombre_distribuidor."):</td> \n";

		$table .= "<td align='center' height='21' class='info-total2'>".$total_boletos."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2'>".number_format($total_recaudacion, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2'>".number_format($total_base, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2' colspan='2'>".number_format($total_iva, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2' colspan='2'>".number_format($total_impuesto, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2' colspan='2'>".number_format($total_fonprocine, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2'>".number_format($total_neto, 2, ',', '.')."</td> \n";
		$table .= "<td align='right' height='21' class='info-total2' colspan='2'>".number_format($total_film_rental, 2, ',', '.')."</td> \n";
		$table .= "</tr> \n";
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