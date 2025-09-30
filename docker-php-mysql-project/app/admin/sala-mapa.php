<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);

include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$id_sala = $_GET["ids"];
$qry = "SELECT fila, columna FROM tbl_sala WHERE id_sala = ".$id_sala;

$result = mysql_query($qry);
if($campo = mysql_fetch_object($result)):
	$rows = $campo->fila;
	$cols = $campo->columna;
endif;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Taquilla</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link href="css/mapa.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/func.glb.js"></script>
<script type="text/javascript" src="js/func.ajax.js"></script>

</head>

<body bgcolor="#FFFFFF" oncontextmenu="return false" >
<?php
	// Imprimo Encabezado de Columnas
	echo "<ul>";
	$mitad = ceil($cols/2)+1;
	for($x=0; $x<=$cols; $x++):
		if($x==$mitad ):
			echo "<li class='x'><img src='images/tr.gif' width='45px' ></li>\n";
			$x=$x-1;
			$mitad=0;
		else:
			echo "<li class='x'>".(($x>0)?$x:'')."</li>\n";
		endif;
	endfor;
	echo "</ul>\n";
	
	// Imprimo las Filas de Butacas
	for($f=$rows; $f>0; $f=$f-1):
		$mitad = ceil($cols/2)+1;
		echo "<ul>";
		for($c=0; $c<=$cols; $c++):
			if($c==0):
				echo "<li class='y'>".$filas_sala[$f]."</li>\n";
				$c++;
			endif;
			if($c==$mitad ):
				$c=$c-1;
				$mitad=0;
				echo "<li><img src='images/tr.gif' width='45px'></li>\n";
			else:
				$inhabilitar = 1;
				$morocha = 2;
				$qry_butaca = "SELECT butaca, status FROM tbl_sala_butaca WHERE butaca = '".$filas_sala[$f].$c."'";
				$rst_butaca = mysql_query($qry_butaca);
				$disponible = mysql_num_rows($rst_butaca);
				if($disponible>0 && $campo=mysql_fetch_object($rst_butaca)):
					$ButacaDisponible = ($campo->status==1) ? "butaca-i.png" : "butaca-m.png";
					$inhabilitar = ($campo->status==1) ? 0 : 1; 
					$morocha = ($campo->status==2) ? 0 : 2; 
				else:
					$ButacaDisponible = "butaca-d.png";
				endif;
				
				$hover = 'this.src="images/butaca-s.png"';
				$out = 'this.src="images/'.$ButacaDisponible.'"';
				$url = 'estadoButaca("'.$filas_sala[$f].$c.'", '.$id_sala.','.$inhabilitar.')';
				$url2 = 'estadoButaca("'.$filas_sala[$f].$c.'", '.$id_sala.','.$morocha.')';
				
				echo "<li><a href='#' onclick='$url' oncontextmenu='$url2'><img src='images/$ButacaDisponible' width='45px' height='52px' onmouseover='$hover' onmouseout='$out' title='".$filas_sala[$f].$c."' alt='".$filas_sala[$f].$c."'></a></li>\n";
			endif;
		endfor;
	echo "</ul>\n";
	endfor;
?>
<br>
<ul>
	<li class="leyenda"><img src="images/butaca-d.png" width="25" height="29"></li>
	<li class="leyenda">Disponible</li>
	<li class="leyenda"><img src="images/butaca-m.png" width="25" height="29"></li>
	<li class="leyenda">Loveseat</li>
	<li class="leyenda"><img src="images/butaca-i.png" width="25" height="29"></li>
	<li class="leyenda">Inhabilitada</li>
</ul>
</body>
</html>