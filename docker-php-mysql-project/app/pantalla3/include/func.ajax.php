<?php
/*
Proyecto: Cine Plus - Taquilla
Archivo:
Asunto: Funciones Comunes de PHP - Ajax
Programador: Oscar A. Cánchica
*/

session_start();

include_once "../../include/bd.inc.php";

$fn = isset($_GET['fn']) ? $_GET['fn'] : 0;

switch($fn):
	case 1:
		$id_operacion = isset($_GET['item']) ? $_GET['item'] : 0;
	
		$qry = "DELETE FROM temp_operacion ";
		$qry .= "WHERE id_operacion = $id_operacion";
		$result = mysql_query($qry) or die("ERROR Fn: Eliminando Item - ".mysql_error());
		
		$consulta = 0;
		$qry_led = "SELECT t.id_session, t.id_operacion, t.id_programacion, t.fecha_operacion, t.id_tipo_transaccion, ";
		$qry_led .= "t.cantidad_transaccion, t.id_precio, t.costo, t.recargo, t.total_transaccion, t.usuario_registro, ";
		$qry_led .= "p.nombre, p.comentario ";
		$qry_led .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
		$qry_led .= "WHERE id_session = '" . $_SESSION['id'] ."'";

		$led_result = mysql_query($qry_led);
		$consulta = $led_result ? 1 : 0;
	
		$table = "";
		//$table = "<table> \n";
		if($consulta == 1):
		$total =0;
			while($campo = mysql_fetch_object($led_result)):
				$table .= "<tr> \n";
				$table .= "<td height='23' class='led-texto' valign='middle'><b>".$campo->cantidad_transaccion."</b>   X    ".$campo->comentario.",    Sub-Total (Bs.) = ". number_format($campo->total_transaccion,2,',','.')."</td> \n";
				$table .= "<td width='25' valign='middle'><a href='javascript:void(0)' onClick='BorrarItem(".$campo->id_operacion.")'><img src='images/img-delete.jpg' border='0'></a></td> \n";
				$table .= "</tr> \n";
				$total = $total + $campo->total_transaccion;
			endwhile;
			$table .= "<tr><td colspan='2'><img src='images/tr.gif'></td></tr> \n";
			$table .= "<tr><td colspan='2' height='33' valign='middle' class='led-total'><b>TOTAL VENTA (Bs.)</b> = ".number_format($total,2,',','.')."</td></tr> \n";
			echo $table;
		endif;
	break;


endswitch;
?>