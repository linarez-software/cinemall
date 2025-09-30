<?php
session_start();
?>
<head>
<SCRIPT Language="Javascript">
function Imprimir()
{
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, 2); WebBrowser1.outerHTML = ""; 
window.close();
}
function openCalc()
{	
       var shell = new ActiveXObject("Wscript.shell");
       //shell.run("c:\\Windows\\System32\\calc.exe");
       shell.run("C:\\factura\\cajaTaquilla.exe");

}


</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<body>
	
	<script>openCalc();</script>
</body>

	
