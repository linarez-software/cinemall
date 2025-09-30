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
	<?php
	if($_SESSION['NIVEL']==3){
	?>
        <!-- Menu Usuario -->
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="recepcion.php" class="css-elemento-menu">Recepciones</a></td></tr>
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="facturas.php" class="css-elemento-menu">Facturas</a></td></tr>
	<?php
	}
	else{
	?>  
    	<tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="recepcion.php" class="css-elemento-menu">Recepciones</a></td></tr>
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="entradas.php" class="css-elemento-menu">Ajustes Entradas</a></td></tr>
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="salidas.php" class="css-elemento-menu">Ajustes Salidas</a></td></tr>
	    <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="transferencias.php" class="css-elemento-menu">Transferencias</a></td></tr>
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="facturas.php" class="css-elemento-menu">Facturas</a></td></tr>      
        <tr bgcolor=<?=$bgcolor_off;?>><td height="18"><a href="linventarios2.php" class="css-elemento-menu">Cargar Inventarios</a></td></tr>      
        
	<?php
	}
	?>        
	<tr bgcolor=<?=$bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
	
</table>

