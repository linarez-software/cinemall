<?php
include_once "../include/bd.inc.php";

if (($_FILES["foto"]["type"] == "image/bmp") ||($_FILES["foto"]["type"] == "image/png") ||($_FILES["foto"]["type"] == "image/x-png") ||($_FILES["foto"]["type"] == "image/jpeg") || ($_FILES["foto"]["type"] == "image/jpg"))
{
 	$tipo="";
	if($_POST[tipo]==2)$tipo="f";
	
	$ext=substr($_FILES["foto"]["name"],strlen($_FILES["foto"]["name"])-4,4);

	$target_path = "img/"; 
	$target_path = $target_path .$tipo. $_POST[id].$ext; 
	if(move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) 
	{ 
		if($_POST[tipo]==1) $sql="update tbl_pelicula set foto='$target_path' where id_pelicula = $_POST[id]";
		if($_POST[tipo]==2) $sql="update tbl_pelicula set foto2='$target_path' where id_pelicula = $_POST[id]";
		$result = @mysql_query($sql, $cnn) or die("Error 3002: ".mysql_error());
		header ("Location: mantenimiento.php?modulo=peliculafoto&idp=$_POST[id]&error=1") ;
	} 

}
else
{
header ("Location: mantenimiento.php?modulo=peliculafoto&idp=$_POST[id]&error=0") ;
	
}

?>