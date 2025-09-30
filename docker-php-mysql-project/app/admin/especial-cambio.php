<?php
/* login.php */
$login = isset($_GET['login']) ? $_GET['login'] : '';

$msj = '';

if(!empty($_POST['status'])):
	include_once "../include/bd.inc.php";
	$password = $_POST['passwd'];
	$query = "UPDATE tbl_configuracion SET especial = '" . strtoupper($password) . "' ";
	$result = mysql_query($query);
	$msj = "Clave Especial Actualizada con Exito";
	
endif;
?>

<html>
<head>
<title>Clave Especial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function validar(formulario){
	var campo3 = formulario.passwd.value;
	var campo4 = formulario.passwd2.value;

	if(campo3.length > 0 && campo3.length <4){
		alert('La contraseña debe tener un minimo de 5 digitos.');
		return false;
	}
	if(campo3.length > 0){
		if(campo4.length ==0){
			alert('Repetir la Contraseña no puede ser vacio.');
			return false;
		}
	}
	if(campo3 != campo4){
		alert('Los campos de contraseña no coinciden');
		return false;
	}
}

</script>
</head>
<body>
<table width="300" align="center"  cellpadding="0" cellspacing="0">
	<tr>
		<tr><td width="100%" valign="middle" align="center"><img src="images/logo2.jpg" vspace="9"></td></tr>
		<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Cambiar Contraseña Especial</td>
	</tr>
<form name="form1" method="post" action="" onSubmit="return validar(this);">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="40%" class="css-label-metadata" height="25">&nbsp;&nbsp;Contraseña:</td>
				<td width="60%"><input type="password" name="passwd" class="css-campo-metadata" >
			</tr>
            <tr bgcolor="#DDDDDD">
				<td width="40%" class="css-label-metadata" height="25">&nbsp;&nbsp;repita:</td>
				<td width="60%"><input type="password" name="passwd2" class="css-campo-metadata" >
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="right" bgcolor="#999999">
		<input type="hidden" value="2" name="status">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>	
</tr>
</form>
<tr><td width="100%" height="10"><img src="images/tr.gif"></td></tr>
<tr >
	<td align="center" class="css-mensaje-advertencia">
		<?php echo $msj;?>
	</td>	
</tr>
</table>
</body>
</html>