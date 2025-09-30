<table width="100%" height="100%" cellpadding="1" cellspacing="1" align="center">
<tr>
	<td align="center" valign="top" height="100"><a href="index.php"><img src="images/logo_cinesacarigua.gif" border="0"></a></td>
</tr>
<tr>
	<td align="center" valign="middle" height="18"><img src="images/img-taquilla.gif"></td>
</tr>
<tr>
	<td align="center" valign="top" height="18" class="css-label-login"><?=$_SESSION['nombre'];?></td>
</tr>
<tr>
	<td align="center" valign="middle" height="31"><input value="<?=$fecha;?>" name="fecha_programacion" type="text" size="8" readonly="true"></td>
</tr>
<tr>
	<td align="center" valign="middle" height="31"><input value="<?php echo date("h:i a");?>" name="hora_programacion" type="text" size="8" readonly="true"></td>
</tr>
<tr>
	<td align="center" valign="bottom" height="110"><a href="index.php"><img src="images/img-boton-venta.png" border="0" alt="Venta de Boletos" onMouseDown="this.src='images/img-boton-venta-press.png'" onMouseUp="this.src='images/img-boton-venta.png'" onMouseOut="this.src='images/img-boton-venta.png'"></a></td>
</tr>
<tr>
	<td align="center" valign="bottom" height="110"><a href="reportes.php"></a></td>
</tr>
<tr>
	<td ><img src="images/tr.gif"></td>
</tr>
<tr>
	<td align="center" valign="bottom"><a href="logout.php"><img src="images/img-boton-salir.png" border="0" alt="Salir del Sistema" onMouseDown="this.src='images/img-boton-salir-press.png'" onMouseUp="this.src='images/img-boton-salir.png'" onMouseOut="this.src='images/img-boton-salir.png'"></a></td>
</tr>
</table>

