<?
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus </title>
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<link href="ccs/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript" type="text/javascript" src="js/func.glb.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="3"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="134" height="100%" valign="top" align="left"><?php include "menu_movimientos.php";?></td>
	<td width="990" align="left" valign="top">
    
	
    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
