<?php
/*
Proyecto: Cine Plus - Admin
Archivo: func.ajax.php
Asunto: Funciones Comunes de PHP - Ajax
Programador: Oscar A. Cánchica
Fecha: 23/10/2006
*/
session_start();
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 

include_once "../../include/bd.inc.php";
include "func.combo.php";

$fn = isset($_GET['fn']) ? $_GET['fn'] : 0;

switch($fn):
	case 1: //Carga de Sala - Cine
		$id_cine = isset($_GET['cine']) ? $_GET['cine'] : 0;
		
		echo combo_sala(1, 0, $id_cine);
		break;

	case 2: //Actualiza Entrada
		$id_programacion = isset($_GET['programacion']) ? $_GET['programacion'] : 0;
		$id_precio = isset($_GET['precio']) ? $_GET['precio'] : 0;
		$actual = isset($_GET['actual']) ? $_GET['actual'] : 0;
		$valor = isset($_GET['valor']) ? $_GET['valor'] : 0;

		if($valor != $actual):
			$qry_fn = "SELECT * FROM tbl_operacion WHERE id_programacion = $id_programacion AND id_precio = $id_precio ";
			$qry_fn .= "ORDER BY cantidad_transaccion ";
			$result_fn = mysql_query($qry_fn);
			$records = mysql_num_rows($result_fn);
			//echo "records " . $records . "<br>";
			if($actual > $valor):
				$dif = $actual - $valor;
				$operador = "-";
			else:
				$dif = $valor - $actual;
				$operador = "+";
			endif;
			if($records > $dif):
				$factor = 1;
				$ultimo = 0;
			else:
				$factor = intval($dif/$records);
				$ultimo = $factor + ($dif % $records);
			endif;
			$c = 0;
			//echo "dif $dif, factor $factor ultimo $ultimo";
			while($campo=mysql_fetch_object($result_fn)):
				$id_operacion = $campo->id_operacion;
				$c++;
				if($c == $records):
					$factor = $ultimo;
				endif;
				//echo $campo->id_operacion." - ".$campo->cantidad_transaccion." - "."$campo->cantidad_transaccion$operador$factor"."<br>";
				if($c <= $records && $c <= $dif):
					$qry_fn2 = "UPDATE tbl_operacion SET cantidad_transaccion = cantidad_transaccion $operador $factor ";
					$qry_fn2 .= "WHERE id_operacion = $id_operacion ";
					//echo $qry_fn2 . "<br>";
					$result_fn2 = mysql_query($qry_fn2);
					
					/*Actualizo Montos*/
					$qry_fn3 = "UPDATE tbl_operacion SET total_transaccion = cantidad_transaccion * costo ";
					$qry_fn3 .= "WHERE id_programacion = $id_programacion AND id_precio = $id_precio  ";
					$result_fn3 = mysql_query($qry_fn3);
					
					/*Elimino Transacciones con Montos en 0*/
					$qry_fn4 = "DELETE FROM tbl_operacion  ";
					$qry_fn4 .= "WHERE total_transaccion = 0 ";
					$result_fn4 = mysql_query($qry_fn4);
				endif;
			endwhile;
			
		endif;	
		
		break;
		
		case 3: //Carga de Hora Funcion
			$pelicula = isset($_GET['pelicula']) ? $_GET['pelicula'] : 0;
			$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : 0;
		
			$qry = "SELECT DISTINCT hora_inicio FROM tbl_programacion ";
			$qry .= "WHERE fecha_programacion = '$fecha' ";
			$qry .= "AND id_pelicula = $pelicula ";
			$qry .= "ORDER BY hora_inicio ";
			//echo $qry ;
		
			$result = mysql_query($qry) or die("Error 006-combo - ".mysql_error());
			$tabla = "";
			$tabla .= "<select name='hora_inicio' class='css-campo-metadata'>";
			$tabla .= "<option value='0' selected >- Hora -</option>";
			while($campo = mysql_fetch_object($result)):
				$tabla .= "<option value='$campo->hora_inicio'>" . date('h:i a', strtotime($campo->hora_inicio)) . "</option>";
			endwhile;
			$tabla .= "</select>";
			echo $tabla;
		break;
		
		case 4: //Verifica la disponibilidad de un horario
			$cine = isset($_GET['cine']) ? $_GET['cine'] : 0;
			$pelicula = isset($_GET['pelicula']) ? $_GET['pelicula'] : 0;
			$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : 0;
			$hora = isset($_GET['hora']) ? $_GET['hora'] : 0;
			$minu = isset($_GET['minu']) ? $_GET['minu'] : 0;
			$sala = isset($_GET['sala']) ? $_GET['sala'] : 0;
			$hora_inicial = date('H:i', strtotime($hora.':'.$minu.':00 pm'));
			
			$dia = substr($fecha, 0,2);
			$mes = substr($fecha, 3,2);
			$anno = substr($fecha, 6,4);
			$nueva_fecha = $anno.'-'.$mes.'-'.$dia;
			
			$qry = "SELECT * FROM tbl_programacion ";
			$qry .= "WHERE fecha_programacion = '$nueva_fecha' ";
			$qry .= "AND id_pelicula = $pelicula ";
			$qry .= "AND id_cine = $cine ";
			$qry .= "AND hora_inicio = '$hora_inicial' ";
			$qry .= "AND id_sala = $sala ";
			//echo $qry ;
			
			if($pelicula > 0):	
				$result = mysql_query($qry) or die("Error 006-combo - ".mysql_error());
				$registros = mysql_num_rows($result);
				echo $registros;
			endif;

		break;

endswitch;
?>