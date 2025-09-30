<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
//if ($_SESSION['NIVEL'] !=1) header("Location: login.php");

include_once "../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Programación</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript" type="text/javascript" src="js/func.glb.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table width="100%" cellpadding="0" cellspacing="3">
	<tr><td width="100%"><?php include "head.php";?></td></tr>
	<tr><td width="100%"><?php include "programacion-filtro.php";?></td></tr>
	<tr><td width="100%" height="5"><!-- Linea entre Seccion --><img src="images/tr.gif"></td></tr>
	<tr><td width="100%" valign="top"><?php include "programacion-sala.php";?></td></tr>
</table>
</body>
</html>
