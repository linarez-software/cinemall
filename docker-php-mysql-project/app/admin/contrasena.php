<html>
<head>
<script language="JavaScript">

function validar(formulario){
   
  var p1 = formulario.passwd.value;
  var p2 = formulario.passwd2.value;
  var espacios = true;
  var cont = 0;

  // Este bucle recorre la cadena para comprobar
  // que no todo son espacios

  if (espacios) {
   	alert ("La contraseña no puede ser todo espacios en blanco");
   	return false;
  }
     if (p1.length == 0 || p2.length == 0) {
   	alert("Los campos de la password no pueden quedar vacios");
   	return false;
  }
   
   
  if (trim(p1.value) != trim(p2.value)) {
   	alert("Las passwords deben de coincidir");
   	return false;
  } else {
   	alert("Todo esta correcto");
   	return true; 
  }
 

}
</script>
<title>Usuario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0" >
	<tr>
		<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Contraseña o Clave del Usuario</td>
	</tr>
<form name="form1" method="post" onSubmit="return validar(this)" action="usuario1.php" >
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Introduzca la Contraseña:</td>
				<td width="81%"><input type="password" name="passwd" size="20" maxlength="10">
				<span class="css-info-metadata">&nbsp;&nbsp;Clave para Ingresar al Sistema (Ej.: *****)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Repita la contraseña:</td>
				<td width="81%"><input type="password" name="passwd2" size="20" maxlength="10">
				<span class="css-info-metadata">&nbsp;&nbsp;Repetir Clave para Ingresar al Sistema (Ej.: *****)</span></td>
			</tr>
		</table>
	</td>
</tr>

<tr bgcolor="#999999">
	<td align="right">
		<input type="submit" value="Enviar" name="B1" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
		<input type="reset" value="Restablecer" name="B2" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>	
</tr>
</form>
</table>

</body>
</html>
