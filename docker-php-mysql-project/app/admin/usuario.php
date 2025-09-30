<?php
/* usuario.php */

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

$idu = isset($_GET['idu']) ? $_GET['idu'] : '0';
$accion = isset($_POST['accion']) ? $_POST['accion'] : AGREGAR;

$modulo = CONFIGURACION."usuario";

$status = 0;
$mensaje = "";

if($idu > 0):
	$qry = "SELECT id_usuario, nombre_completo, username, passwd, nivel, activo FROM tbl_usuario ";
	$qry .= "WHERE id_usuario = $idu ";
	$result = @mysql_query($qry, $cnn) or die("Error 2001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$nombre_completo = $campo->nombre_completo;
		$username = $campo->username;
		$passwd = $campo->passwd;
		$nivel = $campo->nivel;
		$activo = $campo->activo == 1 ? 'checked' : '';
		$id_usuario = $idu;
		$accion = MODIFICAR;
	endif;

else:
	$id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : '0';
	$nombre_completo = isset($_POST['nombre_completo']) ? strtoupper($_POST['nombre_completo']) : '';
	$username = isset($_POST['username']) ? strtoupper($_POST['username']) : '0';
	$passwd = isset($_POST['passwd']) ? strtoupper($_POST['passwd']) : '';
	$nivel = isset($_POST['nivel']) ? $_POST['nivel'] : 0;
	$activo = isset($_POST['activo']) ? $_POST['activo'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
endif;

if($accion == AGREGAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "INSERT INTO tbl_usuario (nombre_completo, username, passwd, nivel, activo) VALUES (";
	$qry .= "'$nombre_completo', $username, '$passwd', '$nivel', $activo)";
	$result = @mysql_query($qry, $cnn) or die("Error 2007: ".mysql_error());
	if($result):
		$mensaje = "Registro Agregado con Exito";
		unset($id_usuario, $nombre_completo, $username, $passwd, $nivel, $activo);
		$accion = AGREGAR;
	endif;
elseif($accion == MODIFICAR && $status == 1):
	$activo = ($activo == 'on') ? 1 : 0;
	$qry = "UPDATE tbl_usuario SET 
	nombre_completo = '$nombre_completo', 
	username = $username, ";
	if(strlen($passwd)>0):
		$qry.= "passwd = '$passwd', ";
	endif;
	$qry .= "nivel = $nivel, activo = $activo ";
	$qry .= "WHERE id_usuario = $id_usuario";
	//echo $qry;
	$result = mysql_query($qry, $cnn) or die("Error 2003: ".mysql_error());
	if($result):
		$mensaje = "Registro Actualizado con Exito";
		unset($id_usuario, $nombre_completo, $username, $passwd, $nivel, $activo);
		$accion = AGREGAR;
	endif;
endif;


?>
<html>
<head>
<title>Pelicula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="javascript" src="js/func.glb.js"></script>
<script language="javascript">
function validar(formulario){

	var campo1 = formulario.nombre_completo.value;
	var campo2 = formulario.username.value;
	var campo3 = formulario.passwd.value;
	var campo4 = formulario.passwd2.value;
	if((document.form1.nivel.value==1)||(document.form1.nivel.value==6)||(document.form1.nivel.value==2)||(document.form1.nivel.value==3))
	{
		let person = prompt("Inttroduzca la Clave de Autorizacion", "");
		if(person=="281080"){
			
		}
		else
		{
			alert("Clave Invalida");
			return false;
		}
		
	}
	if(campo1.length ==0){
		alert('El Nombre Completo del usuario no puede ser vacio.');
		return false;
	}
	if(campo2.length <4){
		alert('Username no puede ser vacio y debe ser 5 Digitos.');
		return false;
	}
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

function validar2(){
	alert('Paso');
}
</script>
<title>Usuario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="ccs/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0" >
<?php
if (strlen($mensaje)>0):
	$tabla = "<tr><td width='100%' class='css-mensaje-metadata'>$mensaje</td></tr>";
	echo $tabla;
endif;
?>
	<tr>
		<td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Contraseña o Clave del Usuario</td>
	</tr>
<form name="form1" method="post" onSubmit="return validar(this)" action="<?php echo $modulo;?>" >
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Código</td>
				<td width="81%"><input name="id_usuario" type="text" class="css-campo-metadata-id" size="10" value="<?php echo isset($id_usuario) ? $id_usuario : '';?>" readonly>
				<span class="css-info-metadata">&nbsp;&nbsp;Código del Usuario (Campo Automatico)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Nombre Completo:</td>
				<td width="81%"><input name="nombre_completo" type="text" class="css-campo-metadata" size="40" maxlength="30" value="<?php echo isset($nombre_completo) ? $nombre_completo : '';?>">
				<span class="css-info-metadata">&nbsp;&nbsp;Nombre del Usuario (Ej.: Jose Machillanda)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Username:</td>
				<td width="81%"><input name="username" type="text" class="css-campo-metadata" size="10" maxlength="4" value="<?php echo isset($username) ? $username : '';?>" onKeyPress="onNumero()">
				<span class="css-info-metadata">&nbsp;&nbsp;Ingresar solo Numeros (Ej.: 12345)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Introduzca la Contraseña:</td>
				<td width="81%"><input type="password" name="passwd" size="20" maxlength="12">
				<span class="css-info-metadata">&nbsp;&nbsp;Clave para Ingresar al Sistema (Ej.: *****)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Repita la contraseña:</td>
				<td width="81%"><input type="password" name="passwd2" size="20" maxlength="12">
				<span class="css-info-metadata">&nbsp;&nbsp;Repetir Clave para Ingresar al Sistema (Ej.: *****)</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Nivel:</td>
				<td width="81%"><?php echo combo_nivel((isset($nivel) ? $nivel : 0));?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="19%" class="css-label-metadata">Activo:</td>
				<td width="81%"><input name="activo" type="checkbox" class="css-campo-metadata" <?php echo isset($activo) ? $activo : '';?>></td>
			</tr>
		</table>
	</td>
</tr>

<tr bgcolor="#999999">
	<td align="right">
		<input type="hidden" name="accion" value="<?php echo $accion;?>">
		<input type="hidden" name="status" value="1">
		<input type="Submit" value="Guardar" class="css-boton-metadata">&nbsp;&nbsp;&nbsp;
	</td>	
</tr>
</form>
</table>

</body>
</html>
