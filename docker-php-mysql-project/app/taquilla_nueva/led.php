<?php

$consulta = 0;
$qry_led = "SELECT t.id_session, t.id_operacion, t.id_programacion, t.fecha_operacion, t.id_tipo_transaccion, ";
$qry_led .= "t.cantidad_transaccion, t.id_precio, t.costo, t.recargo, t.total_transaccion, t.usuario_registro, ";
$qry_led .= "p.nombre, p.comentario, pe.nombre_corto, pr.hora_inicio, numerada, butacas, pr.id_sala ";
$qry_led .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
$qry_led .= "INNER JOIN tbl_programacion pr ON t.id_programacion = pr.id_programacion ";
$qry_led .= "INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula ";
$qry_led .= "WHERE id_session = '" . $_SESSION['id']  ."'";
//echo $qry_led;
$led_result = mysql_query($qry_led);
$consulta = $led_result ? 1 : 0;

$_SESSION['imprimir_boletos'] = mysql_num_rows($led_result);

?>
<table id="tabla_led" width="100%" height="130" cellpadding="1" cellspacing="1" class="led-borde">
	<div id="zona_led">
	<?php
	if($consulta == 1):
		$table = "";
		$total =0;
		while($campo = mysql_fetch_object($led_result)):
			$table .= "<tr> \n";
			$link = "";
			$butacas_seleccionadas = "";
			if(($campo->numerada==1)):
				$butacas_seleccionadas = 0;
				// BUSCO SI HAY BUTACAS PRESELECCIONADAS PARA ESTA FUNCIÓN
				$qry_selecionadas = "SELECT butaca FROM temp_operacion_butaca ";
				$qry_selecionadas .= "WHERE id_operacion = $campo->id_operacion AND id_programacion = $campo->id_programacion AND id_session = '$campo->id_session'";
				$rst_selecionadas = mysql_query($qry_selecionadas);
				$butacas = array();
				$buta = 0;
				
				while($campo_s = mysql_fetch_object($rst_selecionadas)):
					$butacas[$buta]=$campo_s->butaca;
					$buta++;
				endwhile;
				
				if($buta > 0):
					$insert_butacas = "UPDATE temp_operacion SET butacas =  '".implode(",", $butacas)."' WHERE id_operacion = ".$campo->id_operacion;
					$update_selecionadas = mysql_query($insert_butacas);
					$butacas_seleccionadas =  "<span style='color:#F30'>". implode(",", $butacas)."</span>";
				endif;
				
				/* Si es una sala Numerada busco el tamaño del mapa */
				$qry_butaca = "SELECT fila, columna FROM tbl_sala ";
				$qry_butaca .= "WHERE id_sala = $campo->id_sala ";
				$rst_butaca = mysql_query($qry_butaca);
				if($campo_b = mysql_fetch_object($rst_butaca)):
					$fila = $campo_b->fila;
					$columna = $campo_b->columna;
				endif;
				$alto = !empty($fila) ? (($fila + 3) * 63) : 0;
				$ancho = !empty($columna) ? (($columna + 5) * 52)  : 0;
				$link = "<a rel='shadowbox;height=$alto;width=$ancho' href='sala-mapa.php?ids=".$campo->id_sala."&idf=".$campo->id_programacion."&session=".$_SESSION['id']."&ido=".$campo->id_operacion."&cant=".$campo->cantidad_transaccion."'>[SELECIONE BUTACAS]</a>";
			endif;		
			$table .= "<td height='23' class='led-texto' valign='middle'><b>".$campo->cantidad_transaccion."</b>   X    ".$campo->comentario.", ".$campo->nombre_corto." - ".date("h:i a", strtotime($campo->hora_inicio)).", Bs. = <b>". number_format($campo->total_transaccion,2,',','.')."</b>&nbsp;&nbsp;";
			$table .= (!empty($butacas_seleccionadas) ? $butacas_seleccionadas : '');
			$table .= "&nbsp;&nbsp;".$link."</td>\n";
			$table .= "<td width='25' valign='middle'><a href='javascript:BorrarItem(".$campo->id_operacion.")'><img src='images/img-delete.jpg' border='0'></a></td>\n";
			$table .= "</tr> \n";
			$total = $total + $campo->total_transaccion;
		endwhile;
		$table .= "<tr><td colspan='2'><img src='images/tr.gif'></td></tr> \n";
		$table .= "<tr><td colspan='2' height='50' valign='middle' class='led-total'>TOTAL VENTA (Bs.)= <b>".number_format($total,2,',','.')."</b></td></tr> \n";
		echo $table;
	endif;
	?>
	</div>
</table>
