<?php
session_start();
include_once "include/func.glb.php";

$bgcolor_off = "'#DDDDDD'";
$bgcolor_on = "'#666666'";

$raiz_modulo = 'configuracion.php?modulo=';
$modulo_buscar = '&sub=buscar';

?>

<table width="175" height="100%" cellpadding="1" cellspacing="1">
	<tr><td class="css-titulo-menu" height="16">- Configuración -</td></tr>
	
	<!-- Datos Empresa -->
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Datos Empresa</a></td></tr>
	
	<?php
				
if($_SESSION['NIVEL']==1):
?><!-- Menu Usuario -->
	<tr><td class="css-subtitulo-menu">&nbsp;&nbsp;Usuario</td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>usuario" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Nuevo Usuario</a></td></tr>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="18"><a href="<?php echo $raiz_modulo;?>usuario<?php echo $modulo_buscar;?>" class="css-elemento-menu">&nbsp;&nbsp;&nbsp;Editar Usuario</a></td></tr>
	<?php
	endif;
?>
	<tr bgcolor=<?php echo $bgcolor_off;?>><td height="100%"><img src="images/tr.gif"></td></tr>
	
</table>

