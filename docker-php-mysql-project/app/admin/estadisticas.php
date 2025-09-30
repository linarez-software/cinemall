<?php
/* registros.php */

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

//Ejecutamos la sentencia SQL
$result=mysql_query("select count(*) as cine from tbl_cine");
if($row=mysql_fetch_object($result)):
	$cine = $row->cine;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as cines from tbl_cine where activo=1");
if($row=mysql_fetch_object($result)):
	$cines = $row->cines;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as cin from tbl_cine where activo=0");
if($row=mysql_fetch_object($result)):
	$cin = $row->cin;
endif;
mysql_free_result($result);


$result=mysql_query("select count(*) as sala from tbl_sala");
if($row=mysql_fetch_object($result)):
	$sala = $row->sala;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as salas from tbl_sala where activo=1");
if($row=mysql_fetch_object($result)):
	$salas = $row->salas;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as sal from tbl_sala where activo=0");
if($row=mysql_fetch_object($result)):
	$sal = $row->sal;
endif;
mysql_free_result($result);


$result=mysql_query("select count(*) as pelicula from tbl_pelicula");
if($row=mysql_fetch_object($result)):
	$pelicula = $row->pelicula;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as peliculas from tbl_pelicula where activo=1");
if($row=mysql_fetch_object($result)):
	$peliculas = $row->peliculas;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as pelicul from tbl_pelicula where activo=0");
if($row=mysql_fetch_object($result)):
	$pelicul = $row->pelicul;
endif;
mysql_free_result($result);


$result=mysql_query("select count(*) as distribuidor from tbl_distribuidor");
if($row=mysql_fetch_object($result)):
	$distribuidor = $row->distribuidor;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as distribuido from tbl_distribuidor where activo=1");
if($row=mysql_fetch_object($result)):
	$distribuido = $row->distribuido;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as distribuid from tbl_distribuidor where activo=0");
if($row=mysql_fetch_object($result)):
	$distribuid = $row->distribuid;
endif;
mysql_free_result($result);



$result=mysql_query("select count(*) as genero from tbl_genero");
if($row=mysql_fetch_object($result)):
	$genero = $row->genero;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as generos from tbl_genero where activo=1");
if($row=mysql_fetch_object($result)):
	$generos = $row->generos;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as gener from tbl_genero where activo=0");
if($row=mysql_fetch_object($result)):
	$gener = $row->gener;
endif;
mysql_free_result($result);



$result=mysql_query("select count(*) as censura from tbl_censura");
if($row=mysql_fetch_object($result)):
	$censura = $row->censura;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as censuras from tbl_censura where activo=1");
if($row=mysql_fetch_object($result)):
	$censuras = $row->censuras;
endif;
mysql_free_result($result);
$result=mysql_query("select count(*) as censur from tbl_censura where activo=0");
if($row=mysql_fetch_object($result)):
	$censur = $row->censur;
endif;
mysql_free_result($result);


?>
<table align="left" width="50%" cellpadding="0" cellspacing="0">
<tr>
	<th height="18" class="css-titulo-metadata">Estadisticas del Sistema</th>
</tr>
<tr><td width="100%">
<table width="100%" cellpadding="1" cellspacing="1">
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Cines</strong> cargados: <?php echo $cine;?>, Activos: <?php echo $cines;?>, No Activos: <?php echo $cin;?></td>
	</tr>
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Salas</strong> cargadas: <?php echo $sala;?>, Activas: <?php echo $salas;?>, No Activas: <?php echo $sal;?></td>
	</tr>
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Películas</strong> cargadas: <?php echo $pelicula;?>, Activas: <?php echo $peliculas;?>, No Activas: <?php echo $pelicul;?></td>
	</tr>
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Distribuidores</strong> cargados: <?php echo $distribuidor;?>, Activos: <?php echo $distribuido;?>, No Activos: <?php echo $distribuid;?></td>
	</tr>
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Generos</strong> cargados: <?php echo $genero;?>, Activos: <?php echo $generos;?>, No Activos: <?php echo $gener;?></td>
	</tr>
	<tr bgcolor="#DDDDDD">
		<td class="css-campo-metadata" height="33">&nbsp;Total de <strong>Censuras</strong> cargadas: <?php echo $censura;?>, Activas: <?php echo $censuras;?>, No Activas: <?php echo $censur;?></td>
	</tr>
</table>
</td></tr>
</table>