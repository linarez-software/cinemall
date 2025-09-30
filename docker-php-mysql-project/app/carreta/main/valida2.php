<?PHP

session_start(); 
if(($_SESSION["login_usu"]=="")|| ($_SESSION["entrada"]=="T"))
{
	session_unset();
	session_destroy();
	header("Location: index.php");
}
?>