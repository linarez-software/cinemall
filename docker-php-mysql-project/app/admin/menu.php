<?php

include_once "include/func.glb.php";
session_start();
$bgcolor_off = "'#DDDDDD'";
$bgcolor_on = "'#666666'";

$raiz_modulo = 'mantenimiento.php?modulo=';
$modulo_buscar = '&sub=buscar';

?>
<?php
				
if($_SESSION['NIVEL']==1):
?>
<table width="175" height="100%" cellpadding="1" cellspacing="1" border="0">
	<tr><td class="css-titulo-menu" height="16">- Pelicula -</td></tr>
	
	<!-- Menu Pelicula -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Pel&iacute;cula</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>pelicula<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Pel&iacute;culas</a></td></tr>


	<tr><td class="css-titulo-menu" height="16">- Cine -</td></tr>
	
	<!-- Menu Cines -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Cines</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>cine<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Cines</a></td></tr>
	
	<!-- Menu Salas -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Salas</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>sala<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Sala</a></td></tr>

	<tr><td width="175" class="css-titulo-menu" height="16">- General -</td></tr>
	
	<!-- Menu Precio -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Precio</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>precio<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Precios</a></td></tr>

	<!-- Menu Censura -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Censura</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>censura<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Censura</a></td></tr>
	
	<!-- Menu Genero -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Genero</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>genero<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Genero</a></td></tr>
	
	<!-- Menu Distribuidor -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Distribuidor</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>distribuidor<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Distribuidor</a></td></tr>
	
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
</table>
<?php
	endif;
?>
<?php
				
if($_SESSION['NIVEL']==7):
?>
<table width="175" height="100%" cellpadding="1" cellspacing="1" border="0">
	<tr><td class="css-titulo-menu" height="16">- Pelicula -</td></tr>
	
	<!-- Menu Pelicula -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Pel&iacute;cula</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>pelicula<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Pel&iacute;culas</a></td></tr>


	
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
</table>
<?php
	endif;
?>
<?php
				
if($_SESSION['NIVEL']==3):
?>
<table width="175" height="100%" cellpadding="1" cellspacing="1" border="0">
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Precio</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>precio<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;M&oacute;dulo Precios</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo 'configuracion.php?modulo=';?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Datos Empresa</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
	
</table>
<?php
	endif;
?>