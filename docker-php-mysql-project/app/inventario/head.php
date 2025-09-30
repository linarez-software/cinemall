<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="110" valign="middle" align="left"><img src="images/logo.jpg" hspace="4" vspace="4">
<div align="right" style="font-weight: bold;">Usuario:<?php echo $_SESSION['USER2'] ;?>&nbsp;</div>
		</td>

	</tr>
	<tr>
		<td width="100%" align="left" bgcolor="#FF9900">
			<?php
		if($_SESSION['NIVEL']==1){
		?>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" align="left"><img src="images/tr.gif"></td>
				<td width="120" align="center" height="18"><a href="programacion.php" class="css-menu">Movimientos</a></td>
				<?php
				echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
				echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				?>
                <td width="100" align="center" height="18"><a href="logout.php" class="css-menu">Salir</a></td>
                <td align="center"><img src="images/tr.gif"></td>
			</tr>
		</table>
		<?php
		}
	
		if($_SESSION['NIVEL']==2){
		?>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" align="left"><img src="images/tr.gif"></td>

		
				<?php
				echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
				?>
                <td width="10" align="left"><img src="images/tr.gif"></td>
				<?php
				echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				?>
                <td width="100" align="center" height="18"><a href="logout.php" class="css-menu">Salir</a></td>
                <td align="center"><img src="images/tr.gif"></td>
			</tr>
		</table>
		<?php
		}
		if(($_SESSION['NIVEL']==3)||($_SESSION['NIVEL']==6)){
		?>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" align="left"><img src="images/tr.gif"></td>
						<td width="120" align="center" height="18"><a href="programacion.php" class="css-menu">Movimientos</a></td>
				<?php
				echo "<td width='120' align='center' height='18'><a href='mantenimiento.php' class='css-menu'>Mantenimiento</a></td>";
				?>
				<td width="10" align="left"><img src="images/tr.gif"></td>
				<?php
				echo "<td width='120' align='center' height='18'><a href='reportes.php' class='css-menu'>Reportes</a></td>";
				?>
                <td width="100" align="center" height="18"><a href="logout.php" class="css-menu">Salir</a></td>
                <td align="center"><img src="images/tr.gif"></td>
			</tr>
		</table>
		<?php
		}
		?>
		</td>
	</tr>
	<tr>
		<td height="2"><img src="images/tr.gif"></td>
	</tr>
</table>

