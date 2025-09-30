<?php
/* login.php */
$login = isset($_GET['login']) ? $_GET['login'] : '';

$msj = '';
if($login=='no'):
	$msj = 'Usuario ó Contraseña incorrectos';
endif;

?>

<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/func.glb.js"></script>
</head>
<body>
<table width="300" align="center"  cellpadding="0" cellspacing="0">
	<tr>
		<tr><td width="100%" valign="middle" align="center"><img src="images/logo2.jpg" vspace="9"></td></tr>
		<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Login</td>
	</tr>
<form name="form1" method="post" action="do-login2.php">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="40%" class="css-label-metadata" height="25">&nbsp;&nbsp;Contraseña:</td>
				<td width="60%"><input type="password" name="passwd" class="css-campo-metadata" onKeyPress="onNumero()">
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="right" bgcolor="#999999">
		<input type="hidden" value="2" name="status">
		<input type="Submit" value="Entrar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>	
</tr>
</form>
<tr><td width="100%" height="10"><img src="images/tr.gif"></td></tr>
<tr >
	<td align="center" class="css-mensaje-advertencia">
		<?=$msj;?>
	</td>	
</tr>
</table>
</body>
</html>