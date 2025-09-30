<?php
session_start();
include_once "../include/bd.inc.php";
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
if(!isset($_SESSION['LOGIN2'])):
	header("Location: login2.php");
endif;
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Taquilla</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link href="ccs/reportes.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript" type="text/javascript" src="js/func.ajax.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="2"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="100%" valign="top" align="left"><?php include "especial-filtro.php";?></td>
</tr>
<tr>
	<?php
	echo "<td width='100%' height='100%' valign='top' align='left'>";
	if(strlen($modulo) > 0):
		include $modulo.$sub_modulo.".php";
	else:
		echo "<img src='images/tr.gif'>";
	endif;		
	echo "</td>";
	?>
</tr>
</table>
</body>
</html>

