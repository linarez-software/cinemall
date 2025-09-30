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
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Configuración</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5">
<tr>
	<td colspan="2"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="180" valign="top" align="left" height="100%"><?php include "menu_configuracion.php";?></td>
	<?php
	echo "<td width='100%' valign='top' align='center'>";
	if(strlen($modulo) > 0):
		include $modulo.$sub_modulo.".php";
	else:
		include "configuracion_empresa.php";
	endif;		
	echo "</td>";
	?>
</tr>
</table>
</body>
</html>
