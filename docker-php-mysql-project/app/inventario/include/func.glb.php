<?
/*
Proyecto: Cine Plus
Archivo:
Asunto: Funciones Comunes de PHP
Programador: Oscar A. Cánchica
*/

/* Constantes de Horarios - Hora en que Inicia */
define("DIURNO", "13:00");
define("MATINEE", "13:00");
define("VESPERTINA", "16:31");
define("INTERMEDIA", "19:00");
define("NOCHE", "21:00");
define("MEDIANOCHE", "22:30");
define("VIN", "23:59");

define("FIN_MATINEE", "16:30");

//ZONA HORARIA
date_default_timezone_set('America/Caracas');

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

function dia_semana($fecha){
	$dia_semana = date("w", strtotime(formato_fecha($fecha)));

	switch($dia_semana):
		case 0:
			return "Domingo";
			break;
		case 1:
			return "Lunes";
			break;
		case 2:
			return "Martes";
			break;
		case 3:
			return "Miercoles";
			break;
		case 4:
			return "Jueves";
			break;
		case 5:
			return "Viernes";
			break;
		case 6:
			return "Sábado";
			break;			
	endswitch;
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

function buscar_Usuario($id){
	$usuario = '';
	$qry = "SELECT id_usuario, nombre FROM tbl_usuario WHERE id_usuario = $id ";
	$buscar_usuario = mysql_query($qry);
	if($campo_usuario= mysql_fetch_object($buscar_usuario)):
		$usuario = $campo_usuario->nombre_completo;
	endif;
	mysql_free_result($buscar_usuario);
	
	return $usuario;
}

function buscar_impuesto_municipal(){
	$impuesto = '';
	$qry = "SELECT municipal FROM tbl_configuracion ";
	$buscar_impuesto = mysql_query($qry);
	if($campo_impuesto= mysql_fetch_object($buscar_impuesto)):
		$impuesto = $campo_impuesto->municipal;
	endif;
	mysql_free_result($buscar_impuesto);
	
	return $impuesto;
}

function buscar_fomprocine(){
	$fomprocine = '';
	$qry = "SELECT fomprocine FROM tbl_configuracion ";
	$buscar_fomprocine = mysql_query($qry);
	if($campo_fomprocine= mysql_fetch_object($buscar_fomprocine)):
		$fomprocine = $campo_fomprocine->fomprocine;
	endif;
	mysql_free_result($buscar_fomprocine);
	
	return $fomprocine;
}

function buscar_alquiler(){
	$alquiler = '';
	$qry = "SELECT alquiler FROM tbl_configuracion ";
	$buscar_alquiler = mysql_query($qry);
	if($campo_alquiler= mysql_fetch_object($buscar_alquiler)):
		$alquiler = $campo_alquiler->alquiler;
	endif;
	mysql_free_result($buscar_alquiler);
	
	return $alquiler;
}
//function 
?>