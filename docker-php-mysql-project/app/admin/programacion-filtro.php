<?php

$modulo = PROGRAMACION;

$fecha_actual = date("d/m/Y");
$fecha_programacion = isset($_POST['fecha_programacion']) ? $_POST['fecha_programacion'] : $fecha_actual;
$cine = isset($_POST['cine']) ? $_POST['cine'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

?>

<script language="JavaScript" src="js/func.calendario.js"></script>
<script language="JavaScript" src="js/conf.calendario.js"></script>

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Programación</td>
</tr>
<form name="programacion" method="post" action="<?php echo $modulo;?>">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata" valign="middle"><span style="font-size:16px">Fecha:</span></td>
				<td width="81%" valign="middle" height="26"><table width="100%" cellpadding="0" cellspacing="0"><tr>
					<td valign="middle" width="80"><input name="fecha_programacion" type="text" class="css-programacion-fecha" size="12" value="<?php echo isset($fecha_programacion) ? $fecha_programacion : $fecha_actual;?>" readonly>&nbsp;&nbsp;</td>
					<td valign="middle" width="32"><a href="javascript:showCal('calendario');"><img src="images/img-calendario.gif" border="0" onMouseOut="this.src='images/img-calendario.gif'" onMouseOver="this.src='images/img-calendario-on.gif'"></a></td>
					<td align="left" class="css-info-metadata">&nbsp;&nbsp;Fecha de la Programacion</td></tr></table>
				</td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata"><span style="font-size:16px">Cine:</span></td>
				<td width="81%"><span style="font-size:16px"><?php echo combo_cine(1, (isset($cine) ? $cine : 0));?></span>
				<span class="css-info-metadata">&nbsp;&nbsp;Seleccione el cine a programar</span></td>
			</tr>
		</table>
	</td>
</tr>
<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Buscar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>
</tr>
</form>
</table>

