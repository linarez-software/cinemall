<?php 
session_start();

$_SESSION['funcion'] = $_GET['idf'];
$_SESSION['precio'] = $_GET['idp'];
$_SESSION['costo'] = $_GET['costo'];

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cantidad de Boletos</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">

<script language="javascript">
function campo_seleccionado(campo){
	document.login.texto.value = campo;
}
function retroceder(){
	cont = 0;
	valor = "";
		
	var textbox = document.frmboletos.boletos.value;

	while (cont < textbox.length-1) {
		actual = textbox.charAt(cont);
		valor = valor + actual;
		cont++;
	}
	document.frmboletos.boletos.value = valor;

}

function marcar(valor){	

	//var boletos = document.frmboletos.boletos.value;
	//if(boletos.length < 3){
		document.getElementById('boletos').value = valor;
	//}
	//venta_temp();
	Cerrar();	
}

function Cerrar(){
	var boletos = document.getElementById("boletos").value;
	//alert(boletos);
	window.returnValue = boletos;
	window.close();
}


</script>
<script language="javascript" src="js/func.ajax.js"></script>
</head>

<body>
<form name="frmboletos">
<table align="center" width="280" cellpadding="0" cellspacing="5">
	<tr>
		<td align="center"><input height="29" align="middle" type="hidden" id="boletos" name="boletos" class="boletos-campo"></td>
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
			<!-- <input type="button" name="borrar" value="&nbsp;<&nbsp;" class="boletos-boton" onClick="retroceder()">&nbsp;&nbsp;&nbsp; -->
			<input type="button" name="b0" value="&nbsp;10&nbsp;" class="boletos-boton" onClick="marcar(10)">	&nbsp;&nbsp;
			<input type="button" value="&nbsp;X&nbsp;" class="boletos-boton" style="background-color:#FFFFFF; color:#FF0000" onClick="window.close()">
			
			<input type="hidden" id="session" name="session" value="<?php echo $_GET['session'];?>">
			<input type="hidden" id="username" name="username" value="<?php echo $_GET['user'];?>">
			<input type="hidden" id="funcion" name="funcion" value="<?php echo $_GET['idf'];?>">
			<input type="hidden" id="precio" name="precio" value="<?php echo $_GET['idp'];?>">
			<input type="hidden" id="costo" name="costo" value="<?php echo $_GET['costo'];?>">
			<input type="hidden" id="numerada" name="numerada" value="<?php echo $_GET['n'];?>">
			<input type="hidden" id="url" name="url" value="">
		</td>
	</tr>
	<?php
	/*
		if($_GET['n'] > 0):
			echo "<tr><td align='center' class='numerada'>NUMERADA</td></tr>";
		endif;
	
	 */ 
	 ?>
</table>
</form>
</body>
</html>
