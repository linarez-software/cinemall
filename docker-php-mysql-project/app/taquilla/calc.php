<SCRIPT Language="Javascript">
function openCalc()
{	
       var shell = new ActiveXObject("Wscript.shell");
       alert("aa");
       //shell.run("c:\\Windows\\System32\\calc.exe");
       alert("bb");
       shell.run("C:\\factura\\cajaTaquilla.exe");

}


</script>

<?php

echo"<script>openCalc();</script>";
?>