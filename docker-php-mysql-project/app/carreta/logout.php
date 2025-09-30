<?php
#Eliminar todas las variables de session
session_start();
session_unset();
session_destroy();

header("Location: index.php");

?>