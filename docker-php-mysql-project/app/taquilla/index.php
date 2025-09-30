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
$tasa=1;
$result = mysql_query("SELECT *
							FROM
							tbl_configuracion
							
							");
					while ($rs=mysql_fetch_object($result)){
							$tasa=$rs->tasa;
					}
setlocale(LC_TIME, "es_ES.ISO8859-1");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cines Plus - Taquilla</title>

<script type="text/javascript" language="javascript" src="js/func.ajax.js"></script>
<script type="text/javascript" language="javascript" src="js/func.glob.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="js/shadowbox.js"></script>
<script src="js/func.ajax.js" type="text/javascript"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/shadowbox.css" rel="stylesheet" type="text/css" >

<script src='js/jquery.inputmask.bundle.min.js'></script>
<script src='js/jquery.maskMoney.min.js'></script>
      <script id="rendered-js" >
$(document).ready(function () {
  $(".custom1").inputmask({ 'alias': 'decimal', 'mask': "[.**]", rightAlign: true });
});

$(".custom2").inputmask('Regex', { regex: "^[0-9]{1,6}(\\.\\d{1,2})?$", rightAlign: true });

$(".custom3").inputmask({ 'alias': 'numeric', allowMinus: false, digits: 2, max: 999.99 });

$(function () {
  $('.custom4').maskMoney();
});
</script>
<script language="javascript">
	function buscar(tipo){
	
	$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                 
                 document.form1.monto.value=mensaje;


             	
            }); 
	alert(mensaje);
}
function formatear (num){
	 num +='';
	 var splitStr = num.split('.');
	 var splitLeft = splitStr[0];
	 var splitRight = splitStr.length > 1 ? "." + splitStr[1] : '';
	 var regx = /(\d+)(\d{3})/;
	 while (regx.test(splitLeft)) {
	 	splitLeft = splitLeft.replace(regx, '$1' + "," + '$2');
	 }
 return splitLeft +splitRight;
 }

function calcula()
{				
				$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                 
                 document.form1.monto.value=mensaje;
                 var dolar=document.form1.tasa.value;
                 var efe=document.form1.efectivo.value;
                 var tar=document.form1.tarjeta.value;
                 var pm=document.form1.pago.value;
                 var mdiv=document.form1.DIVISA.value;
                 

                 pm=pm.replace(",", "");
                 
                 efe=efe.replace(",", "");
                 tar=tar.replace(",", "");
                 if(mdiv=="")mdiv=0;

                 if(dolar=="")dolar=0;
                 if(efe=="")efe=0;
                 if(tar=="")tar=0;
				 if(pm=="")pm=0;

                 var mdolar=(parseFloat(Math.round(dolar * 100) / 100)*parseFloat(Math.round(mdiv * 100) / 100));
                 mdolar=Math.round(mdolar * 100) / 100;
                 var total;
                 total=mdolar+parseFloat(tar)+parseFloat(efe)+parseFloat(pm);
                 
                 var monto_f_dolar=0;

                 monto_f_dolar=mensaje/parseFloat(document.form1.tasa.value);
              	 monto_f_dolar=Math.round(monto_f_dolar * 100) / 100;
				 
                 var vuelto=total-mensaje;
                 vuelto=Math.round(vuelto * 100) / 100;
				 var dolarv=(parseFloat(vuelto)/parseFloat(document.form1.tasa.value));
                 dolarv=Math.round(dolarv * 100) / 100;
				 tar=Math.round(tar * 100) / 100;
				 pm=Math.round(pm * 100) / 100;
				 vuelto2=Math.round(vuelto2 * 100) / 100;
				 vuelto2=Math.round(vuelto2 * 100) / 100;
				 efe=Math.round(efe * 100) / 100;


                 if(monto_f_dolar>mdiv)
                 	{
                 		vuelto2=vuelto;
                 		dolarv=0;
                 	}
                 if(monto_f_dolar<=mdiv)
                 {
                 	var dolarv=Math.floor((parseFloat(vuelto)/parseFloat(document.form1.tasa.value)));
                 	dolarv=Math.round(dolarv * 100) / 100;
				 	var dec=(((parseFloat(vuelto)/parseFloat(document.form1.tasa.value))))-Math.floor((parseFloat(vuelto)/parseFloat(document.form1.tasa.value)));
                 	dec=Math.round(dec * 100) / 100;
				 	var vuelto2=dec*parseFloat(document.form1.tasa.value);
				 	vuelto2=Math.round(vuelto2 * 100) / 100;
				 	
                 	
                 }
				 total=Math.round(total * 100) / 100;

                 document.form1.recibido.value=formatear(total.toFixed(2));

                 document.getElementById("cambio").innerHTML="CAMBIO TOTAL<BR>BS. : "+formatear(vuelto.toFixed(2))+"<BR>BS. : "+(vuelto2.toFixed(2))+"<BR>$ : "+(dolarv.toFixed(2));
                 if(parseFloat(vuelto)>=0) document.getElementById("cambio").style.color = "blue";
                 if(parseFloat(vuelto)<0) document.getElementById("cambio").style.color = "red";

            }); 
	
	return false;

      
}

function format(input)
{
var num = input.value.replace(/\./g,'');
if(!isNaN(num)){
num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
num = num.split('').reverse().join('').replace(/^[\.]/,'');
input.value = num;
}
  
else{ alert('Solo se permiten numeros');
input.value = input.value.replace(/[^\d\.]*/g,'');
}
}
function ImprimirBoleto(tipo){

				existe=0;
				var pm=document.form1.pago.value;
				if(parseFloat(pm)>0)
             		{
             			if(document.form1.ref.value=="")
             			{
             				alert("Indique la referencia bancaria");
             				return false;
             			} else{

             				$.post("buscar.php", {tipo: 'pm',banco: document.form1.banco.value,ref: document.form1.ref.value}, function(mensaje) {
								//alert(mensaje);
								existe=mensaje;
								if (parseFloat(mensaje)==1){
									alert("Este pago movil ya fue cargado en sistema");
									existe=1;
									return false;
								}else{
									$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                
							                 if (parseFloat(mensaje)==0){
							                 	return false;
							                 }
							                 document.form1.monto.value=mensaje;
							                 var dolar=document.form1.tasa.value;

							                 var mdiv=document.form1.DIVISA.value;
							                 if(mdiv=="")mdiv=0;


							                 if(dolar=="")dolar=0;
							                 var mdolar=(parseFloat(Math.round(dolar * 100) / 100)*parseFloat(Math.round(mdiv * 100) / 100));
							                 mdolar=Math.round(mdolar * 100) / 100;
							                 	if(parseFloat(mdiv)>150){
							                 	return false;
							                 }
							                 var efe=document.form1.efectivo.value;
							                 var tar=document.form1.tarjeta.value;
							                 var pm=document.form1.pago.value;
							                 pm=pm.replace(",", "");
							                 efe=efe.replace(",", "");
							                 tar=tar.replace(",", "");
							                 tar=Math.round(tar * 100) / 100;
											 pm=Math.round(pm * 100) / 100;
											 efe=Math.round(efe * 100) / 100;

							                 if(efe=="")efe=0;
							                 if(tar=="")tar=0;
											 if(pm=="")pm=0;


							                 var total;
							                 total=mdolar+parseFloat(efe)+parseFloat(tar)+parseFloat(pm);
							                 total=Math.round(total * 100) / 100;
							                  if ((mdolar==0))
							                 {
							                 	if((parseFloat(tar)!=parseFloat(mensaje))&&(efe==0)&& (parseFloat(pm)==0))
							                 	{
							         				alert("El pago en Tarjeta no Puede dar Cambio ");
													return false;
							                 	}
							                 	if((parseFloat(pm)!=parseFloat(mensaje))&&(efe==0)&& (parseFloat(tar)==0))
							                 	{
							         				alert("El pago en Pago Movil no Puede dar Cambio ");
													return false;
							                 	}
							                 	xx=parseFloat(pm)+parseFloat(tar);
							                 	if((xx!=parseFloat(mensaje))&&(efe==0))
							                 	{
							         				alert("El pago en Pago Movil y tarjeta  no Puede dar Cambio ");
													return false;
							                 	}
							                 }
							                 else
							                 {
							                 	if(parseFloat(tar)>parseFloat(mensaje) && (parseFloat(pm)==0))
							                 	{
							         				alert("El pago en Tarjeta no Puede dar Cambio ");
													return false;
							                 	}
							                 	if(parseFloat(pm)>parseFloat(mensaje) && (parseFloat(tar)==0))
							                 	{
							         				alert("El pago en  Pago Movil no Puede dar Cambio ");
													return false;
							                 	}
							                 }
							                 if(parseFloat(mensaje)>( parseFloat(total))){
													alert("El modo de pago no puede ser distinto al total de la factura ");
													return false;
												}
							             	else{
							             		if(parseFloat(pm)>0)
							             		{
							             			if(document.form1.ref.value=="")
							             			{
							             				alert("Indique la referencia bancaria");
							             				return false;
							             			}
							             		}
							             		if(tipo==0)
													{
														
														//alert(parseFloat(existe));
														if (parseFloat(existe)==0){
															document.getElementById("botones").style.display  = "none";
															window.open("ticket.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value +"&monto=" + parseFloat(tar) +"&efectivo=" + parseFloat(efe)+"&dolar=" + parseFloat(mdiv) +"&tasa=" + parseFloat(dolar) +"&pm=" + parseFloat(pm) +"&ref=" + document.form1.ref.value +"&banco=" + document.form1.banco.value, "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
														}else{
															alert("Este pago movil ya fue cargado en sistema");
															return false;
														}
												}
												else{
													document.getElementById("botones").style.display  = "none";
													window.open("ticket_tarjeta.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value , "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
												}
							             	}
							            }); 
								}
							}); 
             			}
             		}else{
             		$.post("buscar.php", {tipo: 'ente'}, function(mensaje) {
                 
                
							                 if (parseFloat(mensaje)==0){
							                 	return false;
							                 }
							                 document.form1.monto.value=mensaje;
							                 var dolar=document.form1.tasa.value;

							                 var mdiv=document.form1.DIVISA.value;
							                 if(mdiv=="")mdiv=0;


							                 if(dolar=="")dolar=0;
							                 var mdolar=(parseFloat(Math.round(dolar * 100) / 100)*parseFloat(Math.round(mdiv * 100) / 100));
							                 mdolar=Math.round(mdolar * 100) / 100;
							                 	if(parseFloat(mdiv)>150){
							                 	return false;
							                 }
							                 var efe=document.form1.efectivo.value;
							                 var tar=document.form1.tarjeta.value;
							                 var pm=document.form1.pago.value;
							                 pm=pm.replace(",", "");
							                 efe=efe.replace(",", "");
							                 tar=tar.replace(",", "");
							                 tar=Math.round(tar * 100) / 100;
											 pm=Math.round(pm * 100) / 100;
											 efe=Math.round(efe * 100) / 100;

							                 if(efe=="")efe=0;
							                 if(tar=="")tar=0;
											 if(pm=="")pm=0;


							                 var total;
							                 total=mdolar+parseFloat(efe)+parseFloat(tar)+parseFloat(pm);
							                 total=Math.round(total * 100) / 100;
							                  if ((mdolar==0))
							                 {
							                 	if((parseFloat(tar)!=parseFloat(mensaje))&&(efe==0)&& (parseFloat(pm)==0))
							                 	{
							         				alert("El pago en Tarjeta no Puede dar Cambio ");
													return false;
							                 	}
							                 	if((parseFloat(pm)!=parseFloat(mensaje))&&(efe==0)&& (parseFloat(tar)==0))
							                 	{
							         				alert("El pago en Pago Movil no Puede dar Cambio ");
													return false;
							                 	}
							                 	xx=parseFloat(pm)+parseFloat(tar);
							                 	if((xx!=parseFloat(mensaje))&&(efe==0))
							                 	{
							         				alert("El pago en Pago Movil y tarjeta  no Puede dar Cambio ");
													return false;
							                 	}
							                 }
							                 else
							                 {
							                 	if(parseFloat(tar)>parseFloat(mensaje) && (parseFloat(pm)==0))
							                 	{
							         				alert("El pago en Tarjeta no Puede dar Cambio ");
													return false;
							                 	}
							                 	if(parseFloat(pm)>parseFloat(mensaje) && (parseFloat(tar)==0))
							                 	{
							         				alert("El pago en  Pago Movil no Puede dar Cambio ");
													return false;
							                 	}
							                 }
							                 if(parseFloat(mensaje)>( parseFloat(total))){
													alert("El modo de pago no puede ser distinto al total de la factura ");
													return false;
												}
							             	else{
							             		if(parseFloat(pm)>0)
							             		{
							             			if(document.form1.ref.value=="")
							             			{
							             				alert("Indique la referencia bancaria");
							             				return false;
							             			}
							             		}
							             		if(tipo==0)
													{
														
														//alert(parseFloat(existe));
														if (parseFloat(existe)==0){
															document.getElementById("botones").style.display  = "none";
															window.open("ticket.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value +"&monto=" + parseFloat(tar) +"&efectivo=" + parseFloat(efe)+"&dolar=" + parseFloat(mdiv) +"&tasa=" + parseFloat(dolar) +"&pm=" + parseFloat(pm) +"&ref=" + document.form1.ref.value +"&banco=" + document.form1.banco.value, "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
														}else{
															alert("Este pago movil ya fue cargado en sistema");
															return false;
														}
												}
												else{
													document.getElementById("botones").style.display  = "none";
													window.open("ticket_tarjeta.php?cedula=" + document.form1.cedula.value +"&nombre=" + document.form1.nombre.value , "Boletos","width=10,height=10,top=20,left=40,scrollbars=YES,titlebar=NO,resizable=YES,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO");
												}
							             	}
							            }); 
				}
				if (parseFloat(existe)==1){
					return false;
				}
	
	
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
<style type="text/css">
	.campo_login2
	{
		font-size: 10px;
	}
	.campo_login3
	{
		font-size: 12px;
	}
	.titulo
	{
		font-size: 10px;
	}
</style>
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
            <td width="150" height="100%" valign="top" align="left"><?php include "head.php"; ?>
            <table width="150" height="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" align="right" valign="top">
			    	<a href="index.php"><img src="<?=LOGO;?>" border="0"></a><br />
			        <img src="images/img-taquilla.gif"><br />
			        <span class="css-label-login"><b><?php echo $_SESSION['nombre'];?></b></span><br />
			        <span class="fecha-head"><?php 
			        $fec=explode("-",$fecha);
			        echo date("d-m-Y")?><br /><div id="liveclock"></div></span><br /><br />
			        <center><a href="logout.php"><img src="images/img-salir.gif" alt="Salir del Sistema" hspace="5" border="0"></a></center>
			 
			    </td>
			 
				</tr>
			
	            <tr>
	            	<td width="150" align="left" valign="top">
			            <table  cellpadding="0" cellspacing="0" border="0">
						<?php
						// Javascript para imprimir boleto
						$qry_led = "SELECT sum( t.cantidad_transaccion)total ";
						$qry_led .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
						$qry_led .= "INNER JOIN tbl_programacion pr ON t.id_programacion = pr.id_programacion ";
						$qry_led .= "INNER JOIN tbl_pelicula pe ON pr.id_pelicula = pe.id_pelicula ";
						$qry_led .= "WHERE id_session = '" . $_SESSION['id']  ."'";
						$led_result = mysql_query($qry_led);
						$monto="0";
						while($campo = mysql_fetch_object($led_result)):
						    $boletos=($campo->total==NULL) ? 0 : $campo->total;
						endwhile;
						$_SESSION['imprimir_boletos']=$boletos;
						$boletos = isset($_SESSION['imprimir_boletos']) ? $_SESSION['imprimir_boletos'] : 0;
						$javascript = "";
						$javascript1 = "";
						if($boletos > 0):
							$javascript = ' onClick="ImprimirBoleto(0)"';
							$javascript1 = ' onClick="ImprimirBoleto(1)"';
						endif;
						?>
							<input type="hidden" maxlength="500" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event)" size="20"  name="tasa" class="campo_login2" value="<?php echo $tasa;?>" align="right">
							<input type="hidden" maxlength="500" size="20" name="monto" class="campo_login2" value="0">
							<tr><td align="center"><b>DIVISAS&nbsp;</b></td></tr>
							<tr><td><input type="text" style="width: 100%;" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event);calcula();" onkeydown="calcula();" size="20" maxlength="3" name="DIVISA" class="campo_login2" value="0" align="right"></td></tr>
							<tr><td align="center"><b>TARJETA&nbsp;</td></tr>
							<tr><td><input type="text" maxlength="500" style="width: 100%;" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event);calcula();" onkeydown="calcula();" size="20" name="tarjeta" class="custom4 campo_login2" value="0" align="right"></td></tr>
							<tr><td align="center"><b>EFECTIVO BS&nbsp;</td></tr>
							<tr><td align="center"><input type="text" maxlength="500" style="width: 100%;" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event);calcula();" onkeydown="calcula();" size="20" name="efectivo" class="custom4 campo_login2" value="0" align="right"></td></tr>
							<tr><td align="center"><b>PAG MOVIL&nbsp;</td></tr>
							<tr><td><input type="text" maxlength="500" style="width: 100%;" onfocus="selecciona_value(this)" onKeyPress="return onNumero(event);calcula();" onkeydown="calcula();" size="20" name="pago" class="custom4 campo_login2" value="0" align="right"></td></tr>
							<tr><td align="center"><b>BANCO PM&nbsp;</td></tr>
							<tr><td>
								<select class="campo_login2" name ='banco' style="width: 100%;">
									<?php

									$sql="select * from pv_tbc_bancos ";
									$query = mysql_query($sql);
									while($campo = mysql_fetch_array($query)):
										echo"<option value='$campo[0]'>$campo[1]</option>";

									endwhile;
									?>
								</select>
							</td></tr>
							<tr><td align="center"><b>REF PM&nbsp;</td></tr>
							<tr><td><input type="text" maxlength="500" style="width: 100%;" onKeyPress="return onNumero(event);" size="10" name="ref" class="campo_login2" value="" align="right"></td></tr>
							
							
							<tr><td><b>RECIBIDO</b>&nbsp;</td></tr>
							<tr><td><input type="text" readonly="readonly" style="width: 100%;" size="20" maxlength="20" name="recibido" class="campo_login3" value="0" align="right"></td></tr>
							<tr><td>
							<div align="center" id='cambio' style="color: red;font-weight: bold;font-size: 14px;">Cambio:<br> 0,00</div>
							<div align="center" id='botones'> <b></b><a href="javascript:void(0)" <?=$javascript;?>><img src="images/img-boton-imprimir.png" border="0" alt="Imprimir Boletos / Pago Efectivo" onMouseDown="this.src='images/img-boton-imprimir-press.png'" onMouseUp="this.src='images/img-boton-imprimir.png'" onMouseOut="this.src='images/img-boton-imprimir.png'"></a></div></td></tr>

						</table>
					</td>
					</tr>
				</table>
				</td>
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
		</tr>
	</table></td>
</tr>
</table>
</form>
</body>
</html>
