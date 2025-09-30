<?php
/* login.php */
session_start();
$login = isset($_GET['login']) ? $_GET['login'] : '';

$msj = '';
if($login=='no'):
	$msj = 'Usuario ó Contraseña incorrectos';
endif;

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<script language="javascript">

function campo_seleccionado(campo){
	document.login.texto.value = campo;
}
function retroceder(){
	campo = document.login.texto.value;
	cont = 0;
	valor = "";
		
	if(campo == 'username'){
		var textbox = document.login.username.value;
	
		while (cont < textbox.length-1) {
			actual = textbox.charAt(cont);
			valor = valor + actual;
			cont++;
  		}
		document.login.username.value = valor;
	}
	
	if(campo == 'passwd'){
		var textbox = document.login.passwd.value;
	
		while (cont < textbox.length-1) {
			actual = textbox.charAt(cont);
			valor = valor + actual;
			cont++;
  		}
		document.login.passwd.value = valor;
	}
}

function marcar(valor){
	campo = document.login.texto.value;
	
	if(campo == 'username'){
		var usuario = document.login.username.value;
		if(usuario.length < 5){
			document.login.username.value = document.login.username.value + valor;
		}
	}
	
	if(campo == 'passwd'){
		var passwd = document.login.passwd.value;
		if(passwd.length < 10){
			document.login.passwd.value = document.login.passwd.value + valor;
		}
	}
}
</script>
</head>

<body >
<table width="100%" height="400" cellpadding="1" cellspacing="1">
	<tr>
	<tr><td valign="middle" align="center" height="40"></td></tr>
	<tr>
	<tr>
		<tr><td width="100%" valign="middle" align="center"><img src="images/logo.jpg" vspace="9"></td></tr>
		<td width="100%" align="center" class="css-label-login" height="30">Iniciar Sesión</td>
	</tr>
<form name="login" method="post" action="dologin.php">
<tr>
	<td valign="middle" height="100" align="center"><table width="220" cellpadding="1" cellspacing="1" align="center" height="80">
		<tr><td align="center"><input name="username" class="campo-login" type="text" border="0" maxlength="5" tabindex="0" onClick="campo_seleccionado(this.name)"></td></tr>
		<tr><td align="left" valign="top" class="css-label-login">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Usuario</td></tr>
		<tr><td align="center"><input name="passwd" class="campo-login" type="password" border="0" maxlength="10" tabindex="1" onClick="campo_seleccionado(this.name)"></td></tr>
		<tr><td align="left" valign="top" class="css-label-login">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contraseña</td></tr>
	</table></td>
</tr>
<tr><td valign="middle" height="280" align="center">
	<table width="220" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-1.gif" onMouseDown="this.src='images/boton-1-on.gif'" onMouseUp="this.src='images/boton-1.gif'" onMouseOut="this.src='images/boton-1.gif'" onClick="marcar(1)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-2.gif" onMouseDown="this.src='images/boton-2-on.gif'" onMouseUp="this.src='images/boton-2.gif'" onMouseOut="this.src='images/boton-2.gif'" onClick="marcar(2)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-3.gif" onMouseDown="this.src='images/boton-3-on.gif'" onMouseUp="this.src='images/boton-3.gif'" onMouseOut="this.src='images/boton-3.gif'" onClick="marcar(3)"></a></td>
	</tr>
	<tr>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-4.gif" onMouseDown="this.src='images/boton-4-on.gif'" onMouseUp="this.src='images/boton-4.gif'" onMouseOut="this.src='images/boton-4.gif'" onClick="marcar(4)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-5.gif" onMouseDown="this.src='images/boton-5-on.gif'" onMouseUp="this.src='images/boton-5.gif'" onMouseOut="this.src='images/boton-5.gif'" onClick="marcar(5)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-6.gif" onMouseDown="this.src='images/boton-6-on.gif'" onMouseUp="this.src='images/boton-6.gif'" onMouseOut="this.src='images/boton-6.gif'" onClick="marcar(6)"></a></td>
	</tr>
	<tr>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-7.gif" onMouseDown="this.src='images/boton-7-on.gif'" onMouseUp="this.src='images/boton-7.gif'" onMouseOut="this.src='images/boton-7.gif'" onClick="marcar(7)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-8.gif" onMouseDown="this.src='images/boton-8-on.gif'" onMouseUp="this.src='images/boton-8.gif'" onMouseOut="this.src='images/boton-8.gif'" onClick="marcar(8)"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-9.gif" onMouseDown="this.src='images/boton-9-on.gif'" onMouseUp="this.src='images/boton-9.gif'" onMouseOut="this.src='images/boton-9.gif'" onClick="marcar(9)"></a></td>
	</tr>
	<tr>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-borrar.gif" onMouseDown="this.src='images/boton-borrar-on.gif'" onMouseUp="this.src='images/boton-borrar.gif'" onMouseOut="this.src='images/boton-borrar.gif'" onClick="retroceder()"></a></td>
		<td width="4"><img src="images/tr.gif" ></td>
		<td width="70" height="70"><a href="#"><img border="0" src="images/boton-0.gif" onMouseDown="this.src='images/boton-0-on.gif'" onMouseUp="this.src='images/boton-0.gif'" onMouseOut="this.src='images/boton-0.gif'" onClick="marcar(0)"></a></td>
		<td width="4"><img src="images/tr.gif"></td>
		<td width="70" height="70"><img src="images/tr.gif"></td>
	</tr>
	<input type="hidden" name="texto" value="user">
	<tr><td colspan="5" align="center">
	<input type="hidden" value="2" name="status"></td></tr>
	<tr><td colspan="5" align="center"><input class="login-boton" type="Submit" value="Entrar"></td>
	</tr>
	</table>
</td></tr>
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
