<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
include_once "../include/bd.inc.php";
include_once "include/func.glb.php";
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';

setlocale(LC_TIME, "es_ES.ISO8859-1");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Reportes</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link href="ccs/reportes.css" rel="stylesheet" type="text/css">
<!-- <script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script> -->
<script language="javascript" type="text/javascript" src="js/func.glb.js"></script>
<script language="javascript" type="text/javascript" src="js/func.ajax.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="3">
  <tr>
	<td colspan="2"  width="100%"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="180" valign="top" align="left" height="100%"><?php include "menu_reportes.php";?></td>
	<?php
	echo "<td width='100%' valign='top' align='center'>";
	if(strlen($modulo) > 0):
		include "reportes/".$modulo.$sub_modulo.".php";
	else:
		echo "<img src='images/tr.gif'>";
	endif;		
	echo "</td>";
	?>
</tr>
</table>
<!-- <a href="especial.php" target="_blank"><font color="#FFFFFF">d </font></a>  -->
</body>
</html>
