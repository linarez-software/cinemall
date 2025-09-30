<?
/*
Proyecto: Cine Plus
Archivo:
Asunto: Funciones Comunes de PHP
Programador: Oscar A. Cánchica
*/

/* Constantes de Horarios */
define("DIURNO", "13:00");
define("MATINEE", "15:00");
define("VESPERTINA", "17:00");
define("INTERMEDIA", "19:00");
define("NOCHE", "21:00");
define("MEDIANOCHE", "23:00");

function rowSelect($color, $accion){
	if($accion == 'out'):
		$js = 'onmouseout="this.style.backgroundColor='.$color.';"';
	else:
		$js = 'onmouseover="this.style.backgroundColor='.$color.';"';
	endif;
	
	return $js;
}

function formato_fecha($fecha, $conversion=1){
	if ($conversion==1): // dd/mm/yyyy -> yyyy-mm-dd
		$dia = substr($fecha, 0,2);
		$mes = substr($fecha, 3,2);
		$anno = substr($fecha, 6,4);
		$nueva_fecha = $anno.'-'.$mes.'-'.$dia;
	elseif($conversion==2): // yyyy-mm-dd -> dd/mm/yyyy
		$nueva_fecha = date('d/m/Y', strtotime($fecha));
	endif;
	
	return $nueva_fecha;
}

function formato_hora($hora, $formato = 'h:i a'){
	$nueva_hora = date($formato, strtotime($hora));
	return $nueva_hora;
}

function salas_por_cine($id_cine){
	$cant_salas = 0;
	$qry = "";
	$qry = "SELECT salas FROM tbl_cine WHERE id_cine = $id_cine";
	$result = mysql_query($qry)  or die("Error 001-glb: ".mysql_error());
	if($campo = mysql_fetch_object($result)):
		$cant_salas = $campo->salas;
	endif;
	mysql_free_result($result);
	
	$cargadas = 0;
	$qry = "";
	$qry = "SELECT count(id_sala) as salas_cargadas FROM tbl_sala WHERE id_cine = $id_cine";
	//echo $qry;
	$result = mysql_query($qry) or die("Error 001-glb: ".mysql_error());;
	if($campo = mysql_fetch_object($result)):
		$cargadas = $campo->salas_cargadas;
	endif;
	mysql_free_result($result);
	
	return $cant_salas - $cargadas ;
}

function buscar_cine($id){
	$cine = '';
	$qry = "SELECT id_cine, nombre FROM tbl_cine WHERE id_cine = $id ";
	$buscar_cine = mysql_query($qry);
	if($campo_cine = mysql_fetch_object($buscar_cine)):
		$cine = $campo_cine->nombre;
	endif;
	mysql_free_result($buscar_cine);
	
	return $cine;
}

function buscar_sala($id){
	$sala = '';
	$qry = "SELECT id_sala, nombre FROM tbl_sala WHERE id_sala = $id ";
	$buscar_sala = mysql_query($qry);
	if($campo_sala= mysql_fetch_object($buscar_sala)):
		$sala = $campo_sala->nombre;
	endif;
	mysql_free_result($buscar_sala);
	
	return $sala;
}

function disponibilidad_funcion($idf){
	$sala = '';
	$capacidad = 0;
	$vendidos = 0;
	$qry = "SELECT p.id_sala, s.capacidad ";
	$qry .= "FROM tbl_programacion p INNER JOIN tbl_sala s ON p.id_sala = s.id_sala ";
	$qry .= "WHERE p.id_programacion = $idf ";
	$buscar_sala = mysql_query($qry);
	if($campo_sala= mysql_fetch_object($buscar_sala)):
		$capacidad = $campo_sala->capacidad;
	endif;
	mysql_free_result($buscar_sala);
	
	$qry = "SELECT id_programacion, SUM(cantidad_transaccion) as boletos ";
	$qry .= "FROM tbl_operacion  ";
	$qry .= "WHERE id_programacion = $idf ";
	$qry .= "GROUP BY  id_programacion ";
	$result_vendidos = mysql_query($qry);
	if($campo_operacion= mysql_fetch_object($result_vendidos)):
		$vendidos = $campo_operacion->boletos;
	endif;
	mysql_free_result($result_vendidos);
	
	$disponible = $capacidad - $vendidos;
	return $disponible;
}

function expiracion_funcion(){
	$tiempo = 0;
	$qry = "SELECT limite_funcion FROM tbl_configuracion ";
	$result_tiempo = mysql_query($qry);
	if($campo_tiempo = mysql_fetch_object($result_tiempo)):
		$tiempo = $campo_tiempo->limite_funcion;
	endif;
	mysql_free_result($result_tiempo);
	
	return $tiempo;
}

?>