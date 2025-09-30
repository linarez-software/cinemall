<?php

include_once "include/func.glb.php";

$bgcolor_off = "'#DDDDDD'";
$bgcolor_on = "'#666666'";

$raiz_modulo = 'mantenimiento.php?modulo=';
$modulo_buscar = '&sub=buscar';

?>

<table width="175" height="100%" cellpadding="1" cellspacing="1" border="0">
	<tr><td class="css-titulo-menu" height="16">- Tablas -</td></tr>
	
	<!-- Menu Pelicula -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Tablas Principales</td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="Bancos.php" class="css-elemento-menu">Bancos</a></td></tr>
    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="cesta.php" class="css-elemento-menu">Cesta Tickets</a></td></tr>
    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="impuesto.php" class="css-elemento-menu">Impuestos</a></td></tr>
    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="productos.php" class="css-elemento-menu">Productos</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="proveedores.php" class="css-elemento-menu">Proveedores</a></td></tr>
    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="categoria.php" class="css-elemento-menu">Categorias</a></td></tr>
    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="botones.php" class="css-elemento-menu">Botones</a></td></tr><tr bgcolor=<?=$bgcolor_off;?>>
<?php if ($_SESSION['NIVEL']==1 ){?> <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="configuracion.php" class="css-elemento-menu">Tasa</a></td></tr><?php }?>
<?php if ($_SESSION['NIVEL']==1 ){?> <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="usuario-buscar.php" class="css-elemento-menu">Usuarios</a></td></tr><?php }?>
	
	<tr bgcolor=<?=$bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
</table>

