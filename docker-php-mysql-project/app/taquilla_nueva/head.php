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
myclock="<font size='4' face='Arial' color='#FF8D1C' ><b>"+hours+":"+minutes+":"+seconds+" "+dn+"</b></font>"
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
<table width="150" height="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="150" align="right" valign="top">
    	<a href="index.php"><img src="<?=LOGO;?>" border="0"></a><br />
        <img src="images/img-taquilla.gif"><br />
        <span class="css-label-login"><b><?php echo $_SESSION['nombre'];?></b></span><br />
        <span class="fecha-head"><?php echo $fecha;?><br /><div id="liveclock"></div></span><br /><br />
        <center><a href="logout.php"><img src="images/img-salir.gif" alt="Salir del Sistema" hspace="5" border="0"></a></center>
   <!--     
	<td width="20"><img src="images/tr.gif"></td>
	<td align="left" valign="middle">
    	<table cellpadding="0" cellspacing="0" border="0">
        	<tr>
            	<td><img src="images/img-taquilla.gif"></td>
            </tr>
            <tr>
            	<td class="css-label-login" height="15"></td>
            </tr>
            <tr>
            	<td class="fecha-head" height="15"></td>
            </tr>
        </table>-->
    </td>
    <!--<td align="right" valign="middle"></td>-->
</tr>
</table>