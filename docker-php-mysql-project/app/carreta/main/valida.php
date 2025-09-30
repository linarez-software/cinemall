<?PHP
session_start(); 
if($_SESSION["login_usu"]=="")
{
	session_unset();
	session_destroy();
	header("Location: index.php");
}
?>