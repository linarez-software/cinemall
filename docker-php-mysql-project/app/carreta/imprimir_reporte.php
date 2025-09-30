<?php
include_once "../include/bd.inc.php";
include_once "include/func.glb.php";
$salida = isset($_REQUEST['salida']) ? $_REQUEST['salida'] : 'pan';
if($salida == 'exc'):
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename='.$_GET['modulo'].'.xls');
	header('Pragma: no-cache');
	header('Expires: 0');
endif;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reportes</title>
<link href="ccs/reportes.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
</head>

<body <?php echo ($salida=='imp') ? 'onLoad="window.print(); window.close();"' : '';?>>
<?php
include "reportes/".$_GET['modulo'].".php";
?>
</body>
</html>
