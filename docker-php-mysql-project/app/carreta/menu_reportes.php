<?php

include_once "include/func.glb.php";

$bgcolor_off = "'#DDDDDD'";
$bgcolor_on = "'#666666'";

$raiz_modulo = 'configuracion.php?modulo=';
$modulo_buscar = '&sub=buscar';

?>

<table width="175" height="100%" cellpadding="1" cellspacing="1">
	<tr><td class="css-titulo-menu" height="16">- Movimientos -</td></tr>
	
	<!-- Datos Empresa -->
	
	<!-- Menu Usuario -->
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas4.php" class="css-elemento-menu">Cuentas Abiertas</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas.php" class="css-elemento-menu">Ventas</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas2d.php" class="css-elemento-menu">Ventas Diarias Divisas</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas2.php" class="css-elemento-menu">Ventas Diarias</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas3.php" class="css-elemento-menu">Reporte Fiscal</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lrecepcion.php" class="css-elemento-menu">Listado Recepciones</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lentradas.php" class="css-elemento-menu">Listado Ajustes Entradas</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lsalidas.php" class="css-elemento-menu">Listado Ajustes Salidas</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="ltransferencias.php" class="css-elemento-menu">Listado Transferencias</a></td></tr>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="linventarios.php" class="css-elemento-menu">Inventarios</a></td></tr>
	<?php if ($_SESSION['NIVEL'] ==1){?>
	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="lventas22.php" class="css-elemento-menu">Facturas Anuladas</a></td></tr>
	<?php }?>
	
	<tr bgcolor=<?=$bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
	
</table>

