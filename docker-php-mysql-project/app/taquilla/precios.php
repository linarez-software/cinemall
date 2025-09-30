<?php

$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$nume = isset($_GET['n']) ? $_GET['n'] : 0;
$consulta = 0;
//echo strtotime(time());
//$dia_semana = date('w', strtotime($_GET['fecha']));
if($idf > 0):
	$qry_hora = "SELECT pr.id_programacion, pr.hora_inicio, c.nomenclatura, pe.id_censura, pe.id_tipo, s.id_tipo_sala 
				FROM tbl_programacion pr 
				INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula
				INNER JOIN tbl_censura c ON pe.id_censura = c.id_censura 
				INNER JOIN tbl_sala s ON pr.id_sala = s.id_sala 
				
				WHERE id_programacion = $idf";
	$hora_result = mysql_query($qry_hora);
	if($campo_hora = mysql_fetch_object($hora_result)):
		$hora_funcion = $campo_hora->hora_inicio;
		$censura = $campo_hora->nomenclatura;
		$tipo = $campo_hora->id_tipo;
		$id_censura = $campo_hora->id_censura;
		$id_tipo_sala = $campo_hora->id_tipo_sala;
	endif;
//echo $dia_semana;
	$qry_precios = "SELECT id_precio, nombre, costo, comision, hora_limite, censura, id_tipo_sala, clave ";
	$qry_precios .= "FROM tbl_precio ";
	$qry_precios .= "WHERE activo = 1 AND SUBSTRING(dia, $dia_semana+1, 1) = 1 ";
	$qry_precios .= "AND hora_limite >= '" . $hora_funcion . "' ";
	//$qry_precios .= "AND (censura > '" . $censura . "' or censura is null or censura = '') ";
	$qry_precios .= "AND id_precio NOT IN (SELECT id_precio FROM tbr_precio_censura WHERE id_censura = $id_censura)";
	// Pregunto si es una sala Numerada
	$qry_precios .= "AND id_tipo = " . $tipo . " ";
	$qry_precios .= "AND id_tipo_sala = " . $id_tipo_sala;
	$precios_result = mysql_query($qry_precios);
	//echo $qry_precios;
	$consulta = 1;
endif;

?>

<script language="javascript">

function VentanaBoletos(id, costo, comision, clave){

	var strFeatures = "border=0; dialogWidth=290px; dialogHeight=340px; center=yes; help=no;"
	var strFeatures2 = "border=0; dialogWidth=290px; dialogHeight=380px; center=yes; help=no;"
	var session = document.all.session.value;
	var funcion = document.all.funcion.value;
	var user = document.all.usuario.value;
	var numerada = document.all.numerada.value;
	var boletos = 0;
	var validado = 1;

	if(clave != 0){
		validado = 0;
		var url = "precio_clave.php?idp="+id+"&cl="+clave;
		validado = window.showModalDialog(url, "Permitir Precio", strFeatures2);
	}

	if(validado > 0){
		var url = "boletos.php?idp="+id+"&costo="+costo+"&idf="+funcion+"&session="+session+"&user="+user+"&n="+numerada;
		boletos = window.showModalDialog(url, "Cantidad Boletos", strFeatures);
		
		if(boletos > 0){
			guardarVenta(id, costo, comision, boletos);
			boletos = 0;
		}
	}
}
</script>

<table width="150" height="100%" cellpadding="1" cellspacing="1">
<tr>
	<td height="21" align="center" class="precios-titulo">Precios</td>
</tr>
<?php
if($consulta == 1):

	$table = "";
	$bgcolor = "'#999999'";
	$bgcolorselect = "'#C8C4FF'";
	while($campo_precios = mysql_fetch_object($precios_result)):
		$hora_limite = strtotime($campo_precios->hora_limite);
		//echo "l". $hora_limite . "  t" . time() . "<br>";
		if($hora_limite >= time() or $campo_precios->hora_limite == "00:00:00"):
			$table .= "<tr>";
			$clave = empty($campo_precios->clave) ? 0 : $campo_precios->clave;

			$table .= "
			<td height='41' align='center' class='precios-precios' bgcolor='#999999'" .rowSelect($bgcolor,'out')." ".rowSelect($bgcolorselect,'over').">
			<a href='#' style='color: white' onclick='VentanaBoletos($campo_precios->id_precio, $campo_precios->costo, $campo_precios->comision, $clave);'>$campo_precios->nombre </a></td>";

			
			$table .= "</tr>";
		endif;
	endwhile;
	echo $table;
endif;
?>
<tr>
	<td bgcolor="#CCCCCC">
		<input type="hidden" id="session" name="session" value="<?php echo $_SESSION['id'];?>">
		<input type="hidden" id="funcion" name="funcion" value="<?php echo $idf;?>">
		<input type="hidden" id="usuario" name="usuario" value="<?php echo $_SESSION['username'];?>">
		<input type="hidden" id="numerada" name="numerada" value="<?php echo $nume;?>">
		<input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username'];?>">
		<input type="hidden" id="id_sala" name="id_sala" value="<?php echo !empty($id_sala) ? $id_sala : '';?>">
		<input type="hidden" id="fila" name="fila" value="<?php echo !empty($fila) ? $fila : '';?>">
		<input type="hidden" id="columna" name="columna" value="<?php echo !empty($columna) ? $columna :'';?>">
		
</tr>
</table>

