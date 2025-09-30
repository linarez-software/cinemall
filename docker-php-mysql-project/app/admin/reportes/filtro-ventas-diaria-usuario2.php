<?php

include_once "include/func.combo.php";
include_once "include/func.glb.php";

$modulo = REPORTES."ventas-diaria-usuario2";

$cine = isset($_POST['cine']) ? $_POST['cine'] : '';
$sala = isset($_POST['sala']) ? $_POST['sala'] : '';
$fecha_actual = date("d/m/Y");
$fecha_programacion = isset($_POST['fecha_programacion']) ? $_POST['fecha_programacion'] : $fecha_actual;
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

?>

<script language="JavaScript" src="js/func.calendario.js"></script>
<script language="JavaScript" src="js/conf.calendario.js"></script>

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Ventas Diarias Usuario</td>
</tr>
<form name="filtro" method="POST" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Cine:</td>
				<td width="81%"><?php echo combo_cine(1, (isset($cine) ? $cine : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata" valign="middle">Fecha Desde:</td>
				<td width="81%" valign="middle" height="26"><table width="100%" cellpadding="0" cellspacing="0"><tr>
					<td valign="middle" width="80"><input name="fecha1" type="text" class="css-campo-metadata" size="12" value="<?php echo isset($fecha_programacion) ? $fecha_programacion : $fecha_actual;?>" readonly>&nbsp;&nbsp;</td>
                    <?php if($_SESSION['NIVEL'] == 1): ?>
					<td valign="middle" width="32"><a href="javascript:showCal('fecha1');"><img src="images/img-calendario.gif" border="0" onMouseOut="this.src='images/img-calendario.gif'" onMouseOver="this.src='images/img-calendario-on.gif'"></a></td>
                    <?php endif; ?>
					<td align="left" class="css-info-metadata">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr></table>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Usuario:</td>
				<td width="81%"><?php echo combo_usuario_registro(1, (isset($usuario_registro) ? $usuario_registro : 0));?></td>
			</tr>
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
	
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Consulta" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>
</tr>
</form>
</table>