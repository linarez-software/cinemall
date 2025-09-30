<?php

if (isset($cine)):
	/* Busco las Salas que tenga el cine y que esten activas*/
	$qry = "SELECT id_sala, id_cine, nombre FROM tbl_sala ";
	$qry .= "WHERE id_cine = $cine AND activo = 1";
	$result = mysql_query($qry);
	$salas = 0;
	if($result):
		$salas = mysql_num_rows($result);
	endif;
endif;

/* Valido los campos */
if($status == 1):
	if(!($cine > 0)):
		$mensaje_error = "Debe seleccionar un cine.";
		$status = 2;
	endif;
	
	if(strlen($fecha_programacion) <= 1):
		$mensaje_error = "Debe seleccionar una fecha.";
		$status = 2;
	endif;
endif;

?>
<script language="javascript">
	function open_funcion(url){
		window.open(url, "Funciones", "width=520,height=210,top=200,left=70,scrollbars=NO,titlebar=NO,resizable=NO,toolbar=NO,menubar=no,directories=NO,location=NO,status=NO")
	}
</script>

<table width="100%" height="100%" cellpadding="1" cellspacing="1">

<?php 
$table = '';
if(isset($status) && $status == 1):
	$table ="<tr><td width='100%' class='css-titulo-menu' height='16'><img src='images/tr.gif'></td></tr> \n";
	
	$table .="<tr><td width='100%'><table width='100%' align='center' >\n";
	$table .="<tr><td bgcolor='#FFFFFF'><img src='images/tr.gif'></td>\n";
	//$table .="<td><table width='96%' cellpadding='1' cellspacing='1'>\n <tr>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Diurno</td>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Matinee</td>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Vespertina</td>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Intermedia</td>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Noche</td>\n";
	$table .="	<td width='16%' bgcolor='#CCCCCC' class='css-sala' align='center'>Media Noche</td>\n";
	//$table .="</tr></table></td> ";
	$table .="</tr>\n </table>\n";
	$table .="</td></tr> \n"; 
	
	$table .= "<tr><td width='100%'><table width='100%' align='center'> \n";
	$linea = 0;
	$bgcolor_select = "'#FFCC99'";
	$reg = 0;
	while($campo = mysql_fetch_object($result)):
		
		$sala = $campo->id_sala;
		
		if($linea == 0):
			$bgcolor = "'#DDDDDD'";
			$linea = 1;
		else:
			$bgcolor = "'#CCCCCC'";
			$linea = 0;
		endif;
		$table .= "<tr>"."\n";
		$table .= "<td class='css-sala' align='center'>$campo->nombre</td> \n";
		//$table .= "<td width='96%' align='center'>\n <table width='100%' height='100%'>\n <tr> \n";
		//$table .= "<td width='96%' align='center'>\n <table width='100%' height='100%'>\n <tr> \n";
		
		// Se inicializan las variables de horario
		$url = '"funcion.php?ids='.$sala.'&idc='.$campo->id_cine.'&fecha='.$fecha_programacion.'"';
		$link = "";
		if($_SESSION['NIVEL'] <= 1):
			$link .= "<a href='#' class='css-datos-consulta' onclick='javascript:open_funcion($url)'>[Agregar función]</a>";
		endif;
			
		$diurno = $link;
		$matinee = $link;
		$vespertina = $link;
		$intermedia = $link;
		$noche = $link;
		$medianoche = $link;
		
		
		$qry = "SELECT p.id_programacion, p.id_cine, p.id_pelicula, p.id_sala, p.hora_inicio, p.hora_fin, p.fecha_programacion, ";
		$qry .= "pe.nombre_corto ";
		$qry .= "FROM tbl_programacion p  ";
		$qry .= "INNER JOIN tbl_pelicula pe ON p.id_pelicula = pe.id_pelicula ";
		$qry .= "WHERE p.fecha_programacion = '".formato_fecha($fecha_programacion)."'";
		$qry .= "AND p.id_cine = $cine AND p.id_sala = $sala AND p.activo = 1";
		//echo $qry;
		$result2 = mysql_query($qry);
		while($campo2 = mysql_fetch_object($result2)):
			$hora = $campo2->hora_inicio;
			$id_sala = $campo2->id_sala;
			$id_programacion = $campo2->id_programacion;
			
			$pelicula = $campo2->nombre_corto.'<br>';
			$hora_funcion = "Hora Función: ". formato_hora($hora);
			
			$url = '"funcion.php?idp='.$id_programacion.'"';
			$link = "";
			$link .= "<a href='#' class='css-datos-consulta' ";
			if($_SESSION['NIVEL'] <= 2):	
				$link .= "onclick='javascript:open_funcion($url)'";
			endif;
			$link .= "><b>".$pelicula . formato_hora($hora)."</b>";
			$link .= "</a>";
			
			$diurno = ($hora >= DIURNO && $hora < MATINEE) ?  $link : $diurno;
			$matinee = ($hora >= MATINEE && $hora < VESPERTINA) ? $link : $matinee ;
			$vespertina = ($hora >= VESPERTINA && $hora < INTERMEDIA) ? $link : $vespertina;
			$intermedia = ($hora >= INTERMEDIA && $hora < NOCHE) ? $link : $intermedia;
			$noche = ($hora >= NOCHE && $hora < MEDIANOCHE) ? $link : $noche;
			$medianoche = ($hora >= MEDIANOCHE) ? $link : $medianoche;
			
		endwhile;
		$table .= "<td class='css-datos-consulta' height='30' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$diurno</td> \n";
		$table .= "<td class='css-datos-consulta' height='25' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$matinee</td> \n";
		$table .= "<td class='css-datos-consulta' height='25' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$vespertina</td> \n";
		$table .= "<td class='css-datos-consulta' height='25' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$intermedia</td> \n";
		$table .= "<td class='css-datos-consulta' height='25' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$noche</td> \n";
		$table .= "<td class='css-datos-consulta' height='25' align='center' width='16%' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$medianoche</td> \n";
		//$table .= "</tr>\n</table>\n</td>\n";
		$table .= "</tr> \n";
		$reg++;
	endwhile;
	
	if($reg == 0):
		$table .= "<tr bgcolor='#DDDDDD' >"."\n";
		$table .= "<td align='center' height='21' class='css-datos-consulta'>No existen salas cargadas para este cine</td> \n";
		$table .= "</tr> \n";
	endif;
	$table .= "</table></td></tr> \n";
	
elseif($status == 2):
	$table = "<tr>"."\n";
	$table .= "<td align='center' width='60%' height='21' class='css-mensaje-advertencia'>$mensaje_error</td> \n";
	$table .= "</tr> \n";
	
endif;
echo $table;
?>
</table>
