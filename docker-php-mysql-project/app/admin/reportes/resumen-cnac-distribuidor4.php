<?php

include_once "include/func.combo.php";

$distribuidor =isset($_REQUEST['distribuidor']) ?( ($_REQUEST['distribuidor'])=="0" ?'%' : $_REQUEST['distribuidor']) : "%";
$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;
$funcion = isset($_REQUEST['funcion']) ? $_REQUEST['funcion'] : 0;

$reporte_actual = $_GET['modulo'];
$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=$reporte_actual&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&distribuidor=$distribuidor&cine=$cine&pelicula=$pelicula&funcion=$funcion&salida=";
//phpinfo();

//Busco los datos de la configuración
$qry = "SELECT fomprocine ";
$qry .= "FROM tbl_configuracion";
$fc_result= mysql_query($qry) or die("Error en Consulta: " . mysql_error());
if($campo = mysql_fetch_object($fc_result)):
	$imp_fonprocine = $campo->fomprocine;
endif;

//Busco todas la programación de acuerdo a la fecha establecida
$qry = "SELECT p.id_programacion
		";
$qry .= "FROM tbl_programacion p 
		INNER JOIN	tbl_sala s ON p.id_sala = s.id_sala
		INNER JOIN	tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula
		INNER JOIN	tbl_cine c ON p.id_cine = c.id_cine
		INNER JOIN	tbl_distribuidor d ON pe.id_distribuidor = d.id_distribuidor
		INNER JOIN	tbl_censura cen ON pe.id_censura = cen.id_censura
		LEFT JOIN	tbc_formato_proyeccion fp ON pe.id_formato_proyeccion = fp.id_formato_proyeccion ";
$qry .= "WHERE pe.id_distribuidor like '$distribuidor' and fecha_programacion >= '$fecha_desde' AND fecha_programacion <= '$fecha_hasta' ";
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
$qry1 =$qry ;
//echo $qry;
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

<table width="70%" align="left" cellpadding="0" cellspacing="0" border="0">
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
    	<td align="left" width="90%" class="titulo-reporte" valign="middle" height="30">CNAC Boletos</td>
	</tr>
	<tr>
		<td ><table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$table = "";
		$cine_actual = "";
		$funcion_nombre = '';
		
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='12' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Fecha de Exhibición</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Precio del Boleto mas Servicio Sin IVA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >N° de Espectadores</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Recaudación</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Precio Boleto</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Recaudación Bruta del Boleto mas IVA e ISEP</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IVA (%)</td> \n";
				
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IVA Sobre Recaudación Bruta del  Boleto</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ISEP</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ISEP Sobre Recaudación Bruta del Boleto</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Base Imponible del Boleto Neto</td> \n";
				
				$table .= "</tr> \n";

			
			$qry = "SELECT	DATE_FORMAT(fecha_operacion , '%d/%m/%Y') fecha_programacion,pr.nombre, 
							o.costo as precio,
							pr.foncine as imp,
							sum(cantidad_transaccion) as espectador, 
							sum(total_transaccion) as recaudacion,municipal,iva,dosporuno   ";
			$qry .= "FROM	tbl_operacion o INNER JOIN tbl_precio pr ON o.id_precio = pr.id_precio ";
			$qry .= "WHERE  o.id_programacion in ($qry1) AND 	fecha_operacion >= '$fecha_desde'  AND 	fecha_operacion <= '$fecha_hasta' ";
			$qry .= "GROUP BY fecha_operacion ,nombre, o.costo,pr.foncine,municipal,iva,dosporuno   ";
			$qry .= "ORDER BY fecha_operacion  ";
			$r_operacion = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
			
			$t1 = 0;
			$t2 = 0;
			$t3 = 0;
			$t4 = 0;
			$t5 = 0;
			$t6 = 0;
			$t7 = 0;
			$t8 = 0;
			$t9 = 0;
			//Pregunto si trae registro, para hacer el ciclo o no
			
			if(mysql_num_rows($r_operacion) > 0):
				while($campo2=mysql_fetch_object($r_operacion)):
					
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->fecha_programacion."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>$campo2->precio</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".($campo2->espectador*$campo2->dosporuno)."</td> \n";
					$t1=$t1+($campo2->espectador*$campo2->dosporuno);
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->espectador*$campo2->precio, 2, ',', '')."</td> \n";
					$t2=$t2+($campo2->espectador*$campo2->precio);
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->precio."</td> \n";
					$t3=$t3+($campo2->espectador*$campo2->precio);
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->espectador*$campo2->precio, 2, ',', '')."</td> \n";
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->iva, 2, ',', '')."</td> \n";
					$recaudacion=$campo2->espectador*$campo2->precio;
					$iva=$campo2->iva/100;
					$isep=$campo2->municipal/100;
					$base=($recaudacion/(1+($isep+$iva)))*$iva;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($base, 2, ',', '')."</td> \n";
					$t4=$t4+($base);
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->municipal, 2, ',', '')."</td> \n";
					$base1=($recaudacion/(1+($isep+$iva)))*$isep;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($base1, 2, ',', '')."</td> \n";
					$t5=$t5+($base1);
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($recaudacion-($base1+$base), 2, ',', '')."</td> \n";
					$t6=$t6+($recaudacion-($base1+$base));
					
					$table .= "</tr> \n";

				endwhile;
				$table .= "<tr> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >Total :</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' ></td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t1, 2, ',', '')."</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t2, 2, ',', '')."</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' ></td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t3, 2, ',', '')."</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >IVA (%)</td> \n";
				
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t4, 2, ',', '')."</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' ></td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t5, 2, ',', '')."</td> \n";
				$table .= "<td align='right' height='21' class='encabezado-reporte' >".number_format($t6, 2, ',', '')."</td> \n";
				
				$table .= "</tr> \n";
			else:
				//Si no trae registros, coloco el encabezado y la palaba SIN FUNCIONES
				/*$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->registro_obra."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".$campo->formato."</td> \n";
				$table .= "<td align='center' height='21' class='reporte'>".date("H:i", strtotime($campo->funcion))."</td> \n";
				/* NOMBRE FUNCION */
				echo"<br>1";
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
				echo"<br>2";
				/*$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
				$table .= "<td align='center' height='21' class='reporte' colspan='8'>SIN FUNCION</td> \n";
				$table .= "</tr> \n";*/
			endif;
	echo utf8_decode($table);
	?>	
	</table></td>
	</tr>
</table>