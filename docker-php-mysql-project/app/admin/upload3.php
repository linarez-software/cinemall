<?php
include_once "../include/bd.inc.php";

if (($_FILES["video"]["type"] == "video/mp4") )
{
 	$tipo="";
	$ext=substr($_FILES["video"]["name"],strlen($_FILES["video"]["name"])-4,4);

	$target_path = "video/"; 
	$target_path = $target_path .$tipo. $_POST[id].$ext;
	if(move_uploaded_file($_FILES['video']['tmp_name'], $target_path)) 
	{ 
		$sql="update tbl_pelicula set video='$target_path' where id_pelicula = $_POST[id]";
		$result = @mysql_query($sql, $cnn) or die("Error 3002: ".mysql_error());
		header ("Location: mantenimiento.php?modulo=peliculavideo&idp=$_POST[id]&error=1") ;
	} 

}
else
{
header ("Location: mantenimiento.php?modulo=peliculavideo&idp=$_POST[id]&error=0") ;
	
}

?>