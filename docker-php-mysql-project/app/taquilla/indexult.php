<?php
include_once "../include/bd.inc.php";

session_start();
if(!isset($_SESSION['login'])):
	header("Location: login.php");
endif;
include "include/config.inc.php";

//Verifico si el usuario esta conectado
$qry_login = "SELECT id_session, username, modulo FROM tbl_conexion WHERE modulo = 2 AND username = '".$_SESSION['username']."'";
$rst_login = mysql_query($qry_login);
$num_login = mysql_num_rows($rst_login);
if($num_login == 0):
	header("Location: logout.php");
else:
	if($campo_login = mysql_fetch_object($rst_login)):
		$session_actual = $campo_login->id_session;
		if($session_actual != $_SESSION['id']):
			header("Location: logout.php");
		endif;
	endif;
endif;

setlocale(LC_TIME, "es_ES.ISO8859-1");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cines Plus - Taquilla</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/shadowbox.css" rel="stylesheet" type="text/css" >

<script type="text/javascript" language="javascript" src="js/func.ajax.js"></script>
<script type="text/javascript" language="javascript" src="js/func.glob.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/shadowbox.js"></script>
<script src="js/func.ajax.js" type="text/javascript"></script>

<script language="javascript">
	function buscar(tipo){
	
	$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                 
                 document.form1.monto.value=mensaje;


             	
            }); 
	alert(mensaje);
}
function ImprimirBoleto(tipo){
	
	$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                 
                 document.form1.monto.value=mensaje;
                 
                 if(parseFloat(mensaje)!=( parseFloat(document.form1.efectivo.value)+parseFloat(document.form1.tarjeta.value))){
						alert("El modo de pago no puede ser distinto al total de la factura ");
						return false;
					}
             	else{
             		if(tipo==0)
						{
							window.open("ticket.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value +"&monto=" + parseFloat(document.form1.tarjeta.value) +"&efectivo=" + parseFloat(document.form1.efectivo.value), "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
					}
					else{
						window.open("ticket_tarjeta.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value , "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
					}
             	}
            }); 
	
	return false;
	
}

Shadowbox.init({	
	language:   "es",
	onClose: function(){ window.location.reload(); }
});
function selecciona_value(objInput) { 

    var valor_input = objInput.value; 
    var longitud = valor_input.length; 

    if (objInput.setSelectionRange) { 
        objInput.focus(); 
        objInput.setSelectionRange (0, longitud); 
    } 
    else if (objInput.createTextRange) { 
        var range = objInput.createTextRange() ; 
        range.collapse(true); 
        range.moveEnd('character', longitud); 
        range.moveStart('character', 0); 
        range.select(); 
    } 
} 

</script>
</head>

<body topmargin="0" leftmargin="10px" rightmargin="0" onload="buscar();">
<form name="form1" onload="buscar();">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" >
<!--<tr>
	<td width='100%' height="96" valign="top"></td>
</tr>-->
<tr>
	<td align="left" width="100%" height="100%" valign="top"><table width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%" height="100%" valign="top"><? include "peliculas.php";?></td>
			<td width="150" height="100%" id="td-precios" valign="top"><? include "precios.php";?></td>
            <td width="150" height="100%" valign="top"><?php include "head.php"; ?></td>
		</tr>
		<tr>
			<td colspan="2"><img src="images/tr.gif"></td>
		</tr>
		<tr>
			<td colspan="2" class="etiqueta"><img src="images/tr.gif"><b>
			<!-- codigo para imp fiscal-->
			Cedula:<input type="text" maxlength="11" size="20" name="cedula" class="campo_login">&nbsp;
            Nombre:<input type="text" maxlength="20" size="50" name="nombre" class="campo_login"></b></td>
		</tr>
		<tr>
			<td valign="bottom" colspan="2"><? include "led.php";?></td>
			<?php
			// Javascript para imprimir boleto
			$boletos = isset($_SESSION['imprimir_boletos']) ? $_SESSION['imprimir_boletos'] : 0;
			$javascript = "";
			$javascript1 = "";
			if($boletos > 0):
				$javascript = ' onClick="ImprimirBoleto(0)"';
				$javascript1 = ' onClick="ImprimirBoleto(1)"';
			endif;
			?>
			<td width="150" valign="middle" align="center">
				<input type="hidden" maxlength="500" size="20" name="monto" class="campo_login" value="0">
				<b>TARJETA</b>&nbsp;<input type="text" maxlength="500" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event)" size="20" name="tarjeta" class="campo_login" value="0" align="right">
				<b>EFECTIVO</b>&nbsp;<input type="text" maxlength="500" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event)" size="20" name="efectivo" class="campo_login" value="0" align="right">
				<div align="center"> <b>Pago Efectivo</b><a href="javascript:void(0)" <?=$javascript;?>><img src="images/img-boton-imprimir.png" border="0" alt="Imprimir Boletos / Pago Efectivo" onMouseDown="this.src='images/img-boton-imprimir-press.png'" onMouseUp="this.src='images/img-boton-imprimir.png'" onMouseOut="this.src='images/img-boton-imprimir.png'"></a></div></td>
		</tr>
	</table></td>
</tr>
</table>
</form>
</body>
</html>
