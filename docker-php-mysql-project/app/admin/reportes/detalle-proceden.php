<?php
include_once "include/func.combo.php";

$fecha_desde = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_hasta = isset($_REQUEST['fecha2']) ? formato_fecha($_REQUEST['fecha2']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$procedencia = isset($_REQUEST['procedencia']) ? $_REQUEST['procedencia'] : 0;

$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
$get = "modulo=detalle-proceden&fecha1=".$_REQUEST['fecha1']."&fecha2=".$_REQUEST['fecha2']."&cine=$cine&procedencia=$procedencia&salida=";

//phpinfo();

//Busco los datos de la configuración
$qry = "SELECT fomprocine ";
$qry .= "FROM tbl_configuracion";
$fc_result= mysql_query($qry) or die("Error en Consulta: " . mysql_error());
if($campo = mysql_fetch_object($fc_result)):
	$imp_fonprocine = $campo->fomprocine;
endif;

//Busco los datos del distribuidor
$qry = "SELECT procedencia as nombre_procedencia ";
$qry .= "FROM tbc_procedencia WHERE id_procedencia = ".$procedencia;
$fc_result= mysql_query($qry) or die("Error en Consulta: " . mysql_error());
if($campo = mysql_fetch_object($fc_result)):
	$nombre_procedencia = $campo->nombre_procedencia;
endif;

//Busco todas la programación de acuerdo a la fecha establecida

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
    	<td align="left" width="90%" class="titulo-reporte" valign="middle" height="30">Peliculas por procedencia</td>
	</tr>
	<tr>
		<td ><table width="100%" cellpadding="1" cellspacing="1" border="1">
	<?php
	
		$table = "";
		$cine_actual = "";
		setlocale(LC_TIME, 'Spanish');
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='3' class='pelicula-nombre'>Lapso del ".formato_fecha($fecha_desde,2)." al ".formato_fecha($fecha_hasta,2)."</td> \n";
		$table .= "</tr> \n";

		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' colspan='3' class='pelicula-nombre'>Distribuidor: <b>".$nombre_procedencia."</b></td> \n";
		$table .= "</tr> \n";		
		$table .= "<tr> \n";
		$table .= "<td align='center' height='21' class='encabezado-reporte' >PELICULA</td> \n";
		$table .= "<td align='center' height='21' class='encabezado-reporte' >INICIO</td> \n";
		$table .= "<td align='center' height='21' class='encabezado-reporte' >FIN</td> \n";
		$table .= "</tr> \n";
	
			
		$qry = "SELECT 
			tbl_pelicula.id_pelicula,
			tbl_pelicula.nombre_corto,
			tbl_pelicula.id_procedencia,min(fecha_programacion) minimo,max(fecha_programacion) maximo
			FROM
			tbl_pelicula
			Inner Join tbl_programacion ON tbl_pelicula.id_pelicula = tbl_programacion.id_pelicula
			WHERE id_procedencia =$procedencia and tbl_programacion.activo=1 and tbl_programacion.fecha_programacion>='$fecha_desde' and tbl_programacion.fecha_programacion<='$fecha_hasta'
			GROUP BY 1,2,3
			ORDER BY nombre_corto 
				";

		$r_operacion = mysql_query($qry) or die("Error en Consulta: " . mysql_error());
		//Pregunto si trae registro, para hacer el ciclo o no
		while($campo2=mysql_fetch_object($r_operacion)):
			$table .= "<tr> \n";
			$table .= "<td align='center' height='21' class='reporte'>".$campo2->nombre_corto."</td> \n";
			$fec=explode("-",$campo2->minimo);
			$table .= "<td align='center' height='21' class='reporte'>".$fec[2]."/".$fec[1]."/".$fec[0]."</td> \n";
			$fec=explode("-",$campo2->maximo);
			$table .= "<td align='center' height='21' class='reporte'>".$fec[2]."/".$fec[1]."/".$fec[0]."</td> \n";
			$table .= "</tr> \n";
		endwhile;
		echo $table;
	?>	
	</table></td>
	</tr>
</table>