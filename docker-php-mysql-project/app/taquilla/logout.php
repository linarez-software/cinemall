<?php
session_start();
include_once "../include/bd.inc.php";

$qry_delete = "DELETE FROM temp_operacion WHERE id_session = '" . $_SESSION['id'] ."'";
$result_delete = mysql_query($qry_delete) or die(mysql_error());

$qry_delete = "DELETE FROM temp_operacion_butaca WHERE id_session = '" . $_SESSION['id'] ."'";
$result_delete = mysql_query($qry_delete) or die(mysql_error());

session_unset();
session_destroy();
header("Location: login.php");
//echo "Sesi�n Cerrada Correctamente <br><br>";
//echo "<a href='index.php'>Volver</a>";
//echo "<script>window.close()</script>";
?>
