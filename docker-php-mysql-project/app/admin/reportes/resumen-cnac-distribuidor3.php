<?php
error_reporting(0);
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
$qry = "SELECT p.id_programacion,
		date(p.fecha_programacion) as fecha_programacion,
		c.nombre as cine,
		s.nombre as sala, 
		pe.nombre_espanol as pelicula,
		pe.registro_obra as registro_obra,
		fp.formato_proyeccion as formato,
		p.hora_inicio as funcion,d.nombre,cen.nomenclatura ,
				CASE WHEN p.hora_inicio <= '16:30:00' 
				THEN 40
				ELSE (case when (SELECT
				tbl_pelicula_distribuidor.porcentaje
				FROM
				tbl_pelicula_distribuidor
				WHERE
				tbl_pelicula_distribuidor.id_pelicula=p.id_pelicula and
				tbl_pelicula_distribuidor.fecha_inicio<=p.fecha_programacion AND
				tbl_pelicula_distribuidor.fecha_fin>=p.fecha_programacion) is null then 40 ELSE
				(SELECT
				tbl_pelicula_distribuidor.porcentaje
				FROM
				tbl_pelicula_distribuidor
				WHERE
				tbl_pelicula_distribuidor.id_pelicula=p.id_pelicula and
				tbl_pelicula_distribuidor.fecha_inicio<=p.fecha_programacion AND
				tbl_pelicula_distribuidor.fecha_fin>=p.fecha_programacion) end) END  as porcentaje_distribuidor ,d.porcentaje ";
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
    	<td align="left" width="90%" class="titulo-reporte" valign="middle" height="30">CNAC Detallado</td>
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
		$table .= "<td align='center' height='21' colspan='12' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";
		$a1=0;
		$a2=0;
		$a3=0;
		$a4=0;
		$a5=0;
		$a6=0;
		$a7=0;
		$a8=0;
		$a9=0;
		$a10=0;
		$a11=0;
		$a12=0;
		$a13=0;
		$a14=0;
		$a15=0;
		$a16=0;
		$a17=0;
		$a18=0;
		$a19=0;
		$a20=0;
		$a21=0;
		

		while($campo=mysql_fetch_object($result)):
			$id_programacion = $campo->id_programacion;

			if($cine_actual <> $campo->cine):
				$cine_actual = $campo->cine;
				//$table .= "<tr> \n";
				//$table .= "<td align='left' height='21' colspan='9' class='pelicula-nombre'>Cine: <b>$cine_actual</b></td> \n";
				//$table .= "</tr> \n";
				$table .= "<tr> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Nro Semana</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Fecha de Exhibición</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Distribuidora</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Complejo Cinematográfico</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Sala</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >N° Registro de la Obra</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Título de la Obra Cinematográfica</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Idioma</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Formato de Proyección</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Hora de Exhibición</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Censura</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Función</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Tipo de Boleto</td> \n";
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
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Precio del Servicio</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Recaudación  Bruta del Servicio mas IVA e ISEP</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IVA</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >IVA Sobre Recaudación Bruta del Servicio</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ISEP</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >ISEP Sobre Recaudación Bruta  del Servicio</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Base Imponible del Servicio Neto</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Recaudación  Boleto Neto + Servicio Neto</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >% de Participación</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Film Rental</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Fonprocine</td> \n";
				$table .= "<td align='center' height='21' class='encabezado-reporte' >Film Rental Neto de FONPROCINE</td> \n";
					


				$table .= "</tr> \n";
			endif;
			
			$qry = "SELECT	pr.comentario as boleto, 
							o.costo as precio,
							pr.foncine as imp,o.foncine,
							sum(o.cantidad_transaccion*dosporuno) as espectador, 
							sum(o.cantidad_transaccion*o.costo*dosporuno) as recaudacion,municipal,iva,dosporuno ";
			$qry .= "FROM	tbl_operacion o INNER JOIN tbl_precio pr ON o.id_precio = pr.id_precio ";
			$qry .= "WHERE  o.id_programacion = $id_programacion  AND 	fecha_operacion >= '$fecha_desde'  AND 	fecha_operacion <= '$fecha_hasta' ";
			$qry .= "GROUP BY pr.comentario, o.costo,municipal,iva,dosporuno,o.foncine  ";
			$qry .= "ORDER BY pr.id_precio ";
			$r_operacion = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
			$a= array();
			
			//Pregunto si trae registro, para hacer el ciclo o no
			if(mysql_num_rows($r_operacion) > 0):
				while($campo2=mysql_fetch_object($r_operacion)):
					$table .= "<tr> \n";
					$table .= "<td align='center' height='21' class='reporte'> </td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("d/m/Y", strtotime($campo->fecha_programacion))."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->nombre."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->cine."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->sala."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->registro_obra."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->pelicula."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>ESPAÑOL</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->formato."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".date("H:i", strtotime($campo->funcion))."</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".$campo->nomenclatura."</td> \n";

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
					$table .= "<td align='center' height='21' class='reporte'>$campo2->precio</td> \n";
					$table .= "<td align='center' height='21' class='reporte'>".($campo2->espectador*$campo2->dosporuno)."</td> \n";
					$a1=$a1+($campo2->espectador*$campo2->dosporuno);
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->espectador*$campo2->precio, 2, ',', '')."</td> \n";
					$a2+=($campo2->espectador*$campo2->precio);
					
					$table .= "<td align='center' height='21' class='reporte'>".$campo2->precio."</td> \n";
					$a3+=0;
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->espectador*$campo2->precio, 2, ',', '')."</td> \n";
					$a4+=($campo2->espectador*$campo2->precio);
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->iva, 2, ',', '')."</td> \n";
					$a5+=0;
					$recaudacion=$campo2->espectador*$campo2->precio;
					$iva=$campo2->iva/100;
					$isep=$campo2->municipal/100;
					$base=($recaudacion/(1+($isep+$iva)))*$iva;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($base, 2, ',', '')."</td> \n";
					$a6+=($base);
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo2->municipal, 2, ',', '')."</td> \n";
					$a7+=0;
					
					$base1=($recaudacion/(1+($isep+$iva)))*$isep;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($base1, 2, ',', '')."</td> \n";
					$a8+=($base1);
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format($recaudacion-($base1+$base), 2, ',', '')."</td> \n";
					$a9+=($recaudacion-($base1+$base));
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a10+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a11+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a12+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a13+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a14+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a15+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(0, 2, ',', '')."</td> \n";
					$a16+=0;
					$table .= "<td align='right' height='21' class='reporte'>".number_format($recaudacion-($base1+$base), 2, ',', '')."</td> \n";
					$a17+=($recaudacion-($base1+$base));
					$table .= "<td align='right' height='21' class='reporte'>".number_format($campo->porcentaje_distribuidor, 2, ',', '')."</td> \n";
					$a18+=$campo->porcentaje_distribuidor;
					$table .= "<td align='right' height='21' class='reporte'>".number_format(($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100, 2, ',', '')."</td> \n";
					$a19+=((($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100));
					
					$table .= "<td align='right' height='21' class='reporte'>".number_format((($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100)*($campo->porcentaje/100), 2, ',', '')."</td> \n";
					$a20+=((($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100)*($campo->porcentaje/100));
					
					$a=(($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100)-((($recaudacion-($base1+$base))*$campo->porcentaje_distribuidor/100)*($campo->porcentaje/100));
					$table .= "<td align='right' height='21' class='reporte'>".number_format($a, 2, ',', '')."</td> \n";
					$a21+=$a;
					
					$table .= "</tr> \n";
				endwhile;
				
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
				/*$table .= "<td align='center' height='21' class='reporte'>".$funcion_nombre."</td> \n";
				$table .= "<td align='center' height='21' class='reporte' colspan='8'>SIN FUNCION</td> \n";
				$table .= "</tr> \n";*/
			endif;

				
		endwhile;
		$table .= "<tr  class='encabezado-reporte'><td align='right'  colspan='14' class='reporte'><b>Totales:</b></td> \n";
				$table .= "<td  align='right'>".number_format($a1,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a2,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a3,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a4,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a5,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a6,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a7,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a8,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a9, 2,',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a10,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a11, 2,',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a12,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a13,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a14,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a15,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a16,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a17,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a18,2, ',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a19, 2,',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a20, 2,',', '')."</td>";
				$table .= "<td  align='right'>".number_format($a21, 2,',', '')."</td>";
				$table .= "</tr> \n";
	else:
		$table = "<tr> \n";
		$table .= "<td align='left' height='21' class='mensaje-consulta'>No hay datos para los rangos seleccionados</td> \n";
		$table .= "</tr> \n";
	endif;
	echo utf8_decode($table);
	?>	
	</table></td>
	</tr>
</table>