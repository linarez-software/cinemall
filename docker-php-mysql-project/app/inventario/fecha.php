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
<script type="text/javascript" src="js/func.global.js"></script>
<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="main/calendar.js"></script>
<script type="text/javascript" src="main/calendar-setup.js"></script>
<script type="text/javascript" src="main/lang/calendar-es.js"></script>
<script language="javascript">
function guardar()
{
	document.form1.action="fecha.php?i=1";
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
    
	<form method="post" name="form1" action="fecha.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
		      include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  
			  if(isset($_GET["i"]))
			  {
			  
			  	$sql="update pv_fecha set
					fecha='$_POST[fecha]'
					
				";

				
			  	$result = mysql_query($sql) or die(mysql_error());
			  }
			  		
 			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='2' align='center'>Fecha Vip</td>";
			  echo"</tr>";
			 
			   $query="select * from pv_fecha ";
			  $result = mysql_query($query) or die(mysql_error());
			  $i=1;
			  while($fila = mysql_fetch_array($result)){
						  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'>";
						  echo"	<td class='titulo' align='center'>Fecha Vip :</td>";
						  echo"	<td class='titulo' align='left'>";?>
						
                <input type="text" class="input" readonly="readonly" size="20" name="fecha" id="cal-field-1" value="<?php echo $fila[fecha]; ?>">
                <button type="submit" id="cal-button-1" style="calendar">...</button>
				<script type="text/javascript">
                    Calendar.setup({
                      inputField    : "cal-field-1",
                      button        : "cal-button-1",
					  ifFormat      : "%Y/%m/%d",
                      align         : "Tr"
                    });
                </script>
  
						  <?php 
						  echo"</td>";
						  echo"</tr>";	 
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
