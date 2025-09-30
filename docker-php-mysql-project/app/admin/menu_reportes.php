<?php
session_start();
include_once "include/func.glb.php";
session_start();
$bgcolor_off = "'#DDDDDD'";
$bgcolor_on = "'#666666'";

$raiz_modulo = 'reportes.php?modulo=';
$modulo_buscar = '&sub=buscar';

?>

<table width="175" height="100%" cellpadding="1" cellspacing="1">
	<tr><td class="css-titulo-menu" height="16">- Detalles -</td></tr>
	
	<!-- Reportes Ventas -->
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Usuario</a></td></tr>
    <tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-usuario2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Usuario Detalle</a></td></tr>
    <?php
	if(($_SESSION['NIVEL']==1)||($_SESSION['NIVEL']==6)):
	?>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Divisas</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas-bruta" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Neta</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-detalle-diario-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Detalle Usuario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-peliculas-semanal" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Peliculas Semanal</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas-semanal" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Sala Semanal</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas-semanal2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Disponibilidad Sala</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-mes" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Mensuales por Dia</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-servicio" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Ventas Servicio</a></td></tr>
	<?php
	endif;
	?>
	 <?php
	if(($_SESSION['NIVEL']==2)):
	?>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-diaria-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Diario por Usuario</a></td></tr>
	<?php
	endif;
	?>
	<!-- Menu Usuario -->

	 <?php
	if(($_SESSION['NIVEL']==3)):
	?>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Divisas</a></td></tr>
	
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-salas-bruta" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Neta</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-detalle-diario-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Diario Detalle Usuario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-diaria-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Diario por Usuario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-diaria-usuario2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Diario por Usuario Detalle</a></td></tr>
	
	<?php
	endif;
	?>
	<!-- Menu Usuario -->

	
	<?php
	if(($_SESSION['NIVEL']==1)||($_SESSION['NIVEL']==6)):
	?>
	<tr><td class="css-titulo-menu" height="16">- Resumen -</td></tr>
	
	<!-- Reportes Ventas -->
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-diaria-usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Diario por Usuario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-diaria-usuario2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Diario por Usuario Detalle</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-resumen-funcion" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen por Funcion/Dia</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-resumen-distribuidor" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen por Distribuidor</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-ventas-distribuidor-semanal" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen Pel&iacute;cula Distribuidor</a></td></tr>
    <!--<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;CNAC</a></td></tr>-->
    <tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac-distribuidor" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;ASOINCI </a></td></tr>
<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac-distribuidor2" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;CNAC </a></td></tr>
<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac-distribuidor3" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;DISTRIBUIDORES </a></td></tr>
<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac-distribuidor4" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Alcaldia</a></td></tr>
<!--<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-cnac-distribuidor5" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;CNAC Boletos Diario</a></td></tr>-->
    <tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-resumen-general" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen General</a></td></tr>
   <!-- <tr bgcolor=<?php //echo $bgcolor_off;?>><td height="18"><a href="<?php //echo $raiz_modulo;?>filtro-resumen-general-d" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Resumen General Distribuidor</a></td></tr>
    <tr bgcolor=<?php //echo $bgcolor_off;?>><td height="18"><a href="<?php //echo $raiz_modulo;?>filtro-detalle-distribuidor" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Liquidaci&oacute;n Distribuidor</a></td></tr>-->
<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>filtro-detalle-proceden" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Peliculas Procedencia</a></td></tr>

	<?php
	endif;
	?>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
	
</table>

