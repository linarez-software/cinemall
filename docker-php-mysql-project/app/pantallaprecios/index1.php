<?php
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cines Acarigua - Barinas, Cartelera</title>
<link href="css/pantalla.css" rel="stylesheet" type="text/css">
<style>
body {overflow:hidden;}
html {overflow:hidden;}
</style>
<script language="javascript">

var pagina="index.php"

function redireccionar() 

{
document.location.reload()
} 

setTimeout ("redireccionar()", 10000);

</script>
<script language="JavaScript" type="text/JavaScript">
 <!--

function show5(){
if (!document.layers&&!document.all&&!document.getElementById)
return

 var Digital=new Date()
 var hours=Digital.getHours()
 var minutes=Digital.getMinutes()
 var seconds=Digital.getSeconds()

var dn="PM"
if (hours<12) dn="AM"
if (hours>12) hours=hours-12
if (hours==0) hours=12

if (minutes<=9) 
	minutes="0"+minutes
 	if (seconds<=9)
 		seconds="0"+seconds
//change font size here to your desire
myclock="<font size='6' face='Arial' color='#FFFFFF' ><b><font size='3'>Hora:</font></br>"+hours+":"+minutes+":"+seconds+" "+dn+"</b></font>"
if (document.layers){
	document.layers.liveclock.document.write(myclock)
	document.layers.liveclock.document.close()
}
else if (document.all)
	liveclock.innerHTML=myclock
else if (document.getElementById)
	document.getElementById("liveclock").innerHTML=myclock
	setTimeout("show5()",1000)
}

window.onload=show5
 //-->
</script>
</head>

<body  bgcolor="#000000" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" background="images/fondo.jpg">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" align="center" border="0">
<tr>
	<td valign="top" width="400"><img src="images/logo_dorado.jpg" border="0"  vspace="10" hspace="10"></td>
	<td width="90%" valign="middle" align="right"><div id="liveclock"></div><br><br><marquee scrolldelay="100" class="marquee">Funciones en <font color="#FF0000">ROJO</font> se encuentran agotadas.</marquee></td>
	<!-- <td width="90%" valign="middle" align="right"><div id="liveclock"></div><br><br><span class="marquee">Los Horarios en <font color="#FF0000">ROJO</font> indican que la función se encuentra agotada.</span></td> -->
</tr>
<tr>
	<td colspan="2" width="100%" height="100%" align="left" valign="top" >
		<table width="100%" align="left">
			<tr>
				<td width="100%" valign="middle"><?php include "peliculas.php";?></td>
			</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>
