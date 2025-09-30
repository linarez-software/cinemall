<?php
$qry = "SELECT DISTINCT f.id_pelicula ";
$qry .= "FROM tbl_programacion f INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "WHERE fecha_programacion = '$fecha' and foto<>''  ";
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);

//$intervalo = intval($peliculas/2);
$intervalo = 1;

if(!isset($_SESSION["intermin2"])){
	 $_SESSION["intermin2"]=0;
	 }
else
	{
	$_SESSION["intermin2"]+=1;
		 if($_SESSION["intermin2"]>=$peliculas){
		 	$_SESSION["intermin2"]=0;
		 }
	 }
$qry = "SELECT DISTINCT p.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, p.sinopsis,foto  ";
$qry .= "FROM ";
$qry .= "  tbl_pelicula p ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE  ";
$qry .= " listar=1 and foto<>'' ";
$qry .= "ORDER BY p.nombre_corto ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";

$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, p.sinopsis,foto ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= " and foto<>'' ";
$qry .= "ORDER BY 1 ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";

$programacion = mysql_query($qry);
?>

<table id="tabla-pelicula" width="100%" style='background-color: black:;border-color: white; ' height="10%" cellpadding="0" cellspacing="0" border="1">
<?php 
$table = "";
$par = 0;
for($p=1; $p<=$intervalo; $p++):
	if($campo = mysql_fetch_object($programacion)):
		$id_pelicula = $campo->id_pelicula;
		if($par==0):
			$par++;
			$tr_fondo = "";
		else:
			$par=0;
			$tr_fondo = "par";
		endif;
		$table .= "<tr  height='10' class='pelicula $tr_fondo' style='background-color: black; '> \n"; 
		$table .= "<td align='center' width='10%' class='censura2'>$campo->nomenclatura</td> \n";
		$table .= "<td align='left' class='pelicula borde-abajo borde-derecho' style='font-size:40px;color:white;font-family:New Century Schoolbook;' colspan='4'><b>".utf8_encode($campo->nombre_espanol)."</b></td></tR> </table>\n";
		
		/*Busco las funciones para esta película*/
		$qry = "SELECT id_programacion, hora_inicio ";
		$qry .= "FROM tbl_programacion ";
		$qry .= "WHERE fecha_programacion = '$fecha' ";
		$qry .= "AND activo = 1 ";
		$qry .= "AND id_pelicula = $id_pelicula ";
		$qry .= "AND TIME_TO_SEC(hora_inicio) + ".($tiempo_expiracion * 60). " >= TIME_TO_SEC(now()) ";
		$qry .= "ORDER BY hora_inicio ASC";
		//echo $qry . "<br>";
		$table .='<table id="tabla-pelicula" width="100%" style="background-color: black;"  height="90%" cellpadding="0" cellspacing="0" border="0">
';
		$table .= "<tr  height='5%' class='pelicula $tr_fondo' style='background-color: black; '> \n"; 
		
		$funcion_result = mysql_query($qry);
		for($i=1; $i<=5; $i++):
			if($funcion = mysql_fetch_object($funcion_result)):
				$hora_limite = strtotime($funcion->hora_inicio) + ($tiempo_expiracion * 60); 
				$color = ($hora_limite >= time()) ? "funcion-disponible" : "funcion-agotada";
				$table .= "<td width='13%' class='borde-derecho borde-abajo $color' style='font-size:24px;color:white;font-family:New Century Schoolbook, TeX Gyre Schola, serif' align='center' valign='middle'><b>".formato_hora($funcion->hora_inicio)."</b></td> \n";
			else:
				$table .= "<td width='13%' class='borde-derecho borde-abajo $color' align='center' valign='middle'>&nbsp;</td> \n";
			endif;
		endfor;
		$table .= "<tr class='pelicula $par' style='background-color: black: '> \n"; 
		$table .= "<td height='20%' align='center' class='pelicula' colspan=8 style='background-color: black;'><img width='100%' height='700px'  src='../admin/$campo->foto'></td> \n";
		$table .= "</tr> \n";
		
	
	endif;
endfor;
mysql_free_result($funcion_result);
echo $table;
?>
</table>