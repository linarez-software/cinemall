<?php
include_once "../include/bd.inc.php";
include_once "include/func.glb.php";

$id_operacion = $_GET["ido"];
$id_programacion = $_GET["idf"];
$session = $_GET["session"];
$id_sala = $_GET["ids"];
$cant = $_GET["cant"];
$cerrar= "";

//Busco si el total de las Butacas ya estan compradas
$qry = "SELECT id_session, id_operacion, butaca, id_programacion FROM temp_operacion_butaca 
WHERE id_operacion = $id_operacion AND id_programacion = $id_programacion AND id_session = '$session'";
$result = mysql_query($qry);
$existe = mysql_num_rows($result);
if($existe == $cant):
	//echo "<script>window.close();</script>";
endif;

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
<title>Cines Plus - Mapa Sala</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/mapa.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/func.glb.js"></script>
<script type="text/javascript" src="js/func.ajax.js"></script>

</head>

<body bgcolor="#FFFFFF" onunload="window.location.reload();" >
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
					$ButacaDisponible = ($campo->status==1) ? "tr.gif" : "butaca-m.png";
					$inhabilitar = ($campo->status==1) ? 0 : 1; 
					$morocha = ($campo->status==2) ? 0 : 2; 
				else:
					$ButacaDisponible = "butaca-d.png";
				endif;
				
				//BUSCO SI LA BUTACA ESTA PRE SELECCIONADA
				$selecionada = 0;
				$qry_butaca = "SELECT butaca FROM temp_operacion_butaca 
				WHERE id_operacion = $id_operacion AND id_programacion = $id_programacion AND id_session = '$session' AND butaca = '".$filas_sala[$f].$c."'";;
				$rst_butaca = mysql_query($qry_butaca);
				$apartada = mysql_num_rows($rst_butaca);
				if($apartada>0):
					$ButacaDisponible = "butaca-s.png";
					$selecionada = 1;
				endif;
				
				//BUSCO SI LA BUTACA ESTA APARTADA
				$apartada = 0;
				$qry_butaca = "SELECT butaca FROM temp_operacion_butaca 
				WHERE id_programacion = $id_programacion AND butaca = '".$filas_sala[$f].$c."' AND id_operacion <> $id_operacion";
				$rst_butaca = mysql_query($qry_butaca);
				$ocupada = mysql_num_rows($rst_butaca);
				if($ocupada>0):
					$ButacaDisponible = "butaca-n.png";
					$apartada = 1;
				endif;
				
				//BUSCO SI LA BUTACA ESTA OCUPADA
				$ocupada = 0;
				$qry_butaca = "SELECT butaca FROM tbl_operacion_butaca 
				WHERE id_programacion = $id_programacion AND butaca = '".$filas_sala[$f].$c."'";
				$rst_butaca = mysql_query($qry_butaca);
				$ocupada = mysql_num_rows($rst_butaca);
				if($ocupada>0):
					$ButacaDisponible = "butaca-n.png";
					$ocupada = 1;
				endif;
				
				$hover = 'this.src="images/butaca-s.png"';
				$out = 'this.src="images/'.$ButacaDisponible.'"';
				$url = 'apartaButaca("'.$filas_sala[$f].$c.'", '.$id_programacion.','.$id_operacion.',"'.$session.'")';
				//apartaButaca("G5", 23785,10,"2kqsruocj1a9p507vj3mb02vi3")
				
				$li2 = "";
				$li = "<li>\n";
				if(($disponible==0 || $campo->status==2) && $ocupada == 0 && $apartada == 0):
					if($existe<$cant):
						$li .= "<a href='#' onclick='$url'>";
						$li2 = "</a>";
					elseif($selecionada == 1):
						$li .= "<a href='#' onclick='$url'>";
						$li2 = "</a>";
					endif;
				endif;
				$li .= "<img src='images/$ButacaDisponible' width='45px' height='52px' title='".$filas_sala[$f].$c."' alt='".$filas_sala[$f].$c."' border='0'>";
				$li .= $li2 ."</li>\n";
				echo $li;
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
	<li class="leyenda"><img src="images/butaca-s.png" width="25" height="29"></li>
	<li class="leyenda">Seleccionada</li>
	<li class="leyenda"><img src="images/butaca-n.png" width="25" height="29"></li>
	<li class="leyenda">Ocupada</li>
</ul>
</body>
</html>