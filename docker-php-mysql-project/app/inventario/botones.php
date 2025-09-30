<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Inventarios</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen"/>
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript">
function guardar()
{
	document.form1.action="botones.php?i=1";
	document.form1.submit();
	
}
function valida()
{
	   window.open ("add_cesta.php?insert=1", "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=600,height=300");  

}


</script>

</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="3"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="134" height="100%" valign="top" align="left"><?php include "menu.php";?></td>
	<td width="990" align="left" valign="top">
    
	<form method="post" name="form1" action="botones.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
		      include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  
			  if(isset($_GET["i"]))
			  {
			  
			  	$sql="update pv_tbc_botones set
					bot1=$_POST[bt1],
					bot2=$_POST[bt2],
					bot3=$_POST[bt3],
					bot4=$_POST[bt4],
					bot5=$_POST[bt5],
					bot6=$_POST[bt6],
					bot7=$_POST[bt7],
					bot8=$_POST[bt8],
					bot9=$_POST[bt9],
					bot10=$_POST[bt10],
					bot11=$_POST[bt11],
					bot12=$_POST[bt12],
					bot13=$_POST[bt13],
					bot14=$_POST[bt14],
					bot15=$_POST[bt15],
					bot16=$_POST[bt16],
					bot17=$_POST[bt17],
					bot18=$_POST[bt18]
					
				";

				
			  	$result = mysql_query($sql) or die(mysql_error());
			  }
			  		
 			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='2' align='center'>Configuracion de Botones</td>";
			  echo"</tr>";
			 
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Boton</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='right'>Producto</td>";
			  echo"</tr>";	  
			  $query="select * from pv_tbc_botones ";
			  $result = mysql_query($query) or die(mysql_error());
			  $i=1;
			  while($fila = mysql_fetch_array($result)){
					for($i=0;$i<18;$i++){				 
						  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'>";
						  echo"	<td class='titulo' align='center'>Boton Nro ".($i+1).":</td>";
						  echo"	<td class='titulo' align='left'>";
						  echo "<select  name='bt".($i+1)."' class='input'>;";
						  $result = mysql_query("SELECT
									*
									FROM
									pv_tbc_productos order by 2
									");
						  echo"<option value='0' $selected><----SELECCIONE UNO----></option>";
						  while ($rs=mysql_fetch_array($result)){
									$selected=($fila[$i]==$rs[0]) ? "selected='selected'" : "";
									echo"<option value='".$rs[0]."' $selected>".$rs[1]."</option>";
									}
						  echo "</select>";				  
						  echo"</td>";
						  echo"</tr>";	 
					}
				}
				echo"<tr height='30'>";
			    echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='2' align='center'><input type='button' onclick='guardar();' value='GUARDAR' class='input'></td>";
			    echo"</tr>";
				echo "</table>";
				
			  ?>
              
          
	</form>
    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
