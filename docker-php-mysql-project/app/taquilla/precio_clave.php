<?php 
session_start();

$precio = $_GET['idp'];
$clave = $_GET['cl'];
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cantidad de Boletos</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">

<script language="javascript">

function marcar(valor){
	var clave_t = document.getElementById("clave").value
	document.getElementById("clave").value = clave_t + valor;
}

function verificar(){
	
	var xclave = document.getElementById("xclave").value;
	var clave = document.getElementById("clave").value;
	var validar = 1;
	//alert(boletos);
	if(xclave != clave){
		alert("Clave Incorrecta");
		validar = 0;
	}
	window.returnValue = validar;
	window.close();
}


</script>
<script language="javascript" src="js/func.ajax.js"></script>
</head>

<body>
<form name="frmboletos" method="post" action="">
	<table align="center" width="280" cellpadding="0" cellspacing="5">
		<tr>
			<td align="center"><img src="images/candado.png" align="center"></td>
		</tr>
		<tr>
			<td align="center"><input height="29" align="middle" type="password" id="clave" name="clave" maxlength="10" class="boletos-campo"></td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="b1" value="&nbsp;1&nbsp;" class="boletos-boton" onClick="marcar(1)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b2" value="&nbsp;2&nbsp;" class="boletos-boton" onClick="marcar(2)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b3" value="&nbsp;3&nbsp;" class="boletos-boton" onClick="marcar(3)">		</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="b4" value="&nbsp;4&nbsp;" class="boletos-boton" onClick="marcar(4)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b5" value="&nbsp;5&nbsp;" class="boletos-boton" onClick="marcar(5)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b6" value="&nbsp;6&nbsp;" class="boletos-boton" onClick="marcar(6)">		</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="b7" value="&nbsp;7&nbsp;" class="boletos-boton" onClick="marcar(7)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b8" value="&nbsp;8&nbsp;" class="boletos-boton" onClick="marcar(8)">&nbsp;&nbsp;&nbsp;
				<input type="button" name="b9" value="&nbsp;9&nbsp;" class="boletos-boton" onClick="marcar(9)">		
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="b0" value="&nbsp;0&nbsp;" class="boletos-boton" onClick="marcar(0)">	&nbsp;&nbsp;
				<input type="button" value="&nbsp;>&nbsp;" class="boletos-boton" style="background-color:#FFFFFF; color:#FF0000" onClick="verificar()">
				<input type="hidden" name="xclave" id="xclave" value="<?php echo $clave;?>">
			</td>
		</tr>
	</table>
</form>
</body>
</html>
