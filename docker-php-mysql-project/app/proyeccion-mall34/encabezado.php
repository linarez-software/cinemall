<?php
session_start();
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

$fecha = date('Y-m-d');
$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$limite = isset($_SESSION['limite']) ? $_SESSION['limite'] : 0;
$tiempo_expiracion = expiracion_funcion();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Multicines El Dorado</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
var pagina="index.php"

function redireccionar() 
{
	document.location.reload()
} 

setTimeout ("redireccionar()", 10000);

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
myclock="<b><font size='3'>Hora:</font></br>"+hours+":"+minutes+" <font size='3'>"+dn+"</font></b>"
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

<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="40" valign="middle" align="center" class="censura" height="150">C<br/>E<br/>N<br/>S<br/>U<br/>R<br/>A</td>
    <td width="80%" valign="middle" align="center" class="logo"><img src="images/logo_cine_mall.jpg" border="0" /></td>
	<td class="hora"><div id="liveclock"></div></td>
</tr>
</table>
    
