<?php
session_start();
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

$fecha = date('Y-m-d');
$idf = isset($_GET['idf']) ? $_GET['idf'] : 0;
$limite = isset($_SESSION['limite']) ? $_SESSION['limite'] : 0;
$tiempo_expiracion = expiracion_funcion();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Multicines El Dorado</title>
<link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>

    
