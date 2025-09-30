<?php
include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

$cine = 5;
/* Busco las Salas que tenga el cine y que esten activas*/
$qry = "SELECT id_sala, id_cine, nombre FROM tbl_sala ";
$qry .= "WHERE id_cine = $cine AND activo = 1";
$result = mysql_query($qry);
$salas = 0;
if($result):
	$salas = mysql_num_rows($result);
endif;

$fecha = date('Y-m-d');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Multicines El Dorado</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript" type="text/javascript" src="js/func.glb.js"></script>
<script language="JavaScript" type="text/JavaScript">
function redireccionar() 
{
	document.location.reload()
} 

setTimeout ("redireccionar()", 10000);

function show5(){
if (!document.layers&&!document.all&&!document.getElementById)
return

 var Digital=new Date()
 var hours=Digital.getHours()
 var minutes=Digital.getMinutes()
 var seconds=Digital.getSeconds()

var dn="PM"
if (hours<12) dn="AM"
if (hours>12) hours=hours-12
if (hours==0) hours=12

if (minutes<=9) 
	minutes="0"+minutes
 	if (seconds<=9)
 		seconds="0"+seconds
//change font size here to your desire
myclock="<b><font size='3'>Hora:</font></br>"+hours+":"+minutes+" <font size='3'>"+dn+"</font></b>"
if (document.layers){
	document.layers.liveclock.document.write(myclock)
	document.layers.liveclock.document.close()
}
else if (document.all)
	liveclock.innerHTML=myclock
else if (document.getElementById)
	document.getElementById("liveclock").innerHTML=myclock
	setTimeout("show5()",1000)
}

window.onload=show5
 //-->
</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="40" valign="middle" align="center" class="censura" height="150">S<br/>A<br/>L<br/>A</td>
    <td width="80%" valign="middle" align="center" class="logo"><img src="images/logo_cine_mall.jpg" border="0" /></td>
	<td class="hora"><div id="liveclock"></div><br /><span class='css-programacion-fecha'><?php echo dia_semana(formato_fecha($fecha,2))."<br>".formato_fecha($fecha,2); ?></span></td>
</tr>

<?php 
$table = '';
//$table .= "<tr height='16'><td class='css-programacion-fecha'><img src='images/tr.gif' width='40' height='1'><b>".dia_semana(formato_fecha($fecha,2))." - ".formato_fecha($fecha,2)."</b></td></tr> \n";
$table .= "<tr><td width='100%' colspan='3'><table width='100%' align='center'> \n";
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
	$table .= "<td width='44' height='40' class='css-sala' align='center'>$campo->nombre</td> \n";
	// Se inicializan las variables de horario
		
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
	$qry .= "WHERE p.fecha_programacion = '".$fecha."'";
	$qry .= "AND p.id_cine = $cine AND p.id_sala = $sala AND p.activo = 1 ";
	$qry .= "ORDER BY p.hora_inicio ";
	//echo $qry;
	$result2 = mysql_query($qry);
	$table .= "<td height='50'><table width='100%'><tr> \n \r";
	$col = 1;
	while($campo2 = mysql_fetch_object($result2)):
		$hora = $campo2->hora_inicio;
		$id_sala = $campo2->id_sala;
		$id_programacion = $campo2->id_programacion;
		
		$pelicula = $campo2->nombre_corto.'<br>';
		$hora_funcion = "Hora Función: ". formato_hora($hora);
		
		$link = "";
		$link .= "<b>".$pelicula . formato_hora($hora)."</b>";
		
		$table .= "<td class='css-datos-consulta' height='50' width='14%' align='center' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">$link</td> \n";
	
		$col++;
	endwhile;
	for($i=$col; $i<=6; $i++):
		$table .= "<td class='css-datos-consulta' height='50' width='14%' align='center' bgcolor=$bgcolor ".rowSelect($bgcolor,'out')." ". rowSelect($bgcolor_select,'over') .">&nbsp;</td> \n";
	endfor;
	$table .= "</tr></table></td> \n \r";
	$table .= "</tr> \n";
	$reg++;
endwhile;

$table .= "</table></td></tr> \n";
	
echo $table;
?>
</table>
