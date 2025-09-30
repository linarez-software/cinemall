<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Taquilla</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="css/shadowbox.css" rel="stylesheet" type="text/css" >

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript" src="js/func.glb.js"></script>
<script type="text/javascript" src="js/shadowbox.js"></script>
<script type="text/javascript">
Shadowbox.init({
});
</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="2"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="175" height="100%" valign="top" align="left"><?php include "menu.php";?></td>
	<?php
	echo "<td width='100%' valign='top' align='left'>";
	if(strlen($modulo) > 0):
		include $modulo.$sub_modulo.".php";
	else:
		include "estadisticas.php";
	endif;		
	echo "</td>";
	?>
</tr>
</table>
</body>
</html>
