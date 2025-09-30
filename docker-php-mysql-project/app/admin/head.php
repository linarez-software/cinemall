<?php
session_start();
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="110" valign="middle" align="left"><img src="images/logo.jpg" hspace="4" vspace="4">
		<div align="right" style="font-weight: bold;">Usuario:<?php echo $_SESSION['USER2'] ;?>&nbsp;</div></td>
	</tr>
	<tr>
		<td width="100%" align="left" bgcolor="#FF9900">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" align="left"><img src="images/tr.gif"></td>
				<?php
				
				if($_SESSION['NIVEL']==1):

					echo '<td width="120" align="center" height="18"><a href="programacion.php" class="css-menu">Programaci&oacute;n</a></td>';
				
				
					echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
					echo "<td width='120' align='center' height='18'><a href='configuracion.php' class='css-menu'>Configuraci&oacute;n</a></td>";
					echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				endif;
				if($_SESSION['NIVEL']==7):

					echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
					
				endif;
				if($_SESSION['NIVEL']==3):

					echo '<td width="120" align="center" height="18"><a href="programacion.php" class="css-menu">Programaci&oacute;n</a></td>';
				
				
					echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
					echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				endif;
				if($_SESSION['NIVEL']==2):
					echo '<td width="120" align="center" height="18"><a href="programacion.php" class="css-menu">Programaci&oacute;n</a></td>';
					echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				endif;
				?>
                <td width="100" align="center" height="18"><a href="logout.php" class="css-menu">Salir</a></td>
                <?php
				if($_SESSION['NIVEL']==1):
					echo "<td align='right' height='18'><a href='especial.php' class='css-menu' accesskey='E'>&nbsp;</a></td>";
				endif;
				?>
                <td align="center"><img src="images/tr.gif"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="2"><img src="images/tr.gif"></td>
	</tr>
</table>

