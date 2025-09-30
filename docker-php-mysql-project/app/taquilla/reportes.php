<?php
session_start();
include_once "../include/bd.inc.php";
include 'include/func.glb.php';
include 'include/config.inc.php';

if(!isset($_SESSION['login'])):
	header("Location: login.php");
endif;

$fecha = date('d/m/Y');
$dia_semana = date('w');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cines Plus - Taquilla</title>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/reportes.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/func.ajax.js"></script>

</head>

<body topmargin="7" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
	<td align="center" valign="top" height="100%"><? include "menu.php";?></td>
	<td align="left" width="850" height="100%" valign="top"><? include "reportes/ventas_salas_taquilla.php";?></td>
	<td align="left" width="850" height="100%" valign="top"><? include "reportes/ventas_salas.php";?></td>
</tr>
</table>

</body>
</html>
