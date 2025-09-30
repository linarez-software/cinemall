<html>
<head>
<title>Reporte Especial</title>
<?php 
include_once "../../include/bd.inc.php";
$valor=$_POST['valor'];
$fecha=$_POST['fecha'];
$pelicula=$_POST['pelicula'];
$total=$_POST['total'];	//obtengo monto por concepto de ventas del dia anterior
$transacciones=$_POST['transacciones'];
$modo=$_POST['modo'];
$hora=$_POST['hora'];

?>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link href="ccs/reportes.css" rel="stylesheet" type="text/css">
<?php
include_once "../../include/bd.inc.php";
include_once "include/func.combo.php";
include_once "include/func.glb.php";
?>
<?php include "head.php";?>

	<?php
	/*echo "<td width='100%' valign='top' align='center'>";
	if(strlen($modulo) > 0):
		include "reportes/".$modulo.$sub_modulo.".php"; 
	else:
		echo "<img src='images/tr.gif'>";
	endif;*/
	
		$id_programacion="";
		//obtengo los id de programacion correspondintes a esa pelicula en esa fecha
		$pre="select distinct tbl_operacion.id_programacion from tbl_programacion inner join tbl_operacion
		on tbl_operacion.id_programacion=tbl_programacion.id_programacion
		and tbl_programacion.id_pelicula='$pelicula'
		and tbl_operacion.fecha_operacion='$fecha'
		and tbl_programacion.hora_inicio='$hora'";
		//echo $pre;
		$ejecucion1=mysql_query($pre,$cnn);
		$ejec2=$ejecucion1;
		if(!$rs=mysql_fetch_array($ejecucion1)){
			echo "<br><br><center class=css-menu>No se Vendieron Boletos para la Pelicula de ID: ".$pelicula." el Dia: ".$fecha."<br>".mysql_error();
			echo "<a href=especial.php class=css-menu>Volver</a></center>";
		}else{
		$id_programacion=$rs['id_programacion'];
		while($rs1=mysql_fetch_array($ejec2))
				$id_programacion=$id_programacion.",".$rs1['id_programacion'];//se obtienen los id de programacion de esa pelicula en esa fech
		
		
		if($modo==1)		//si lo que se quiere es restar ventas del dia...
			$modificacion=" set cantidad_transaccion=cantidad_transaccion+1,
							total_transaccion=total_transaccion+7000
							where cantidad_transaccion<2 ";
		else
			$modificacion=" set cantidad_transaccion=cantidad_transaccion-1,
							total_transaccion=total_transaccion-7000
							where cantidad_transaccion>=2 ";
	
		$consulta="update tbl_operacion
		$modificacion
		and id_precio=8
		and id_programacion in ('$id_programacion')
		and fecha_operacion='$fecha' limit $valor";
		
		$ejecucion=mysql_query($consulta,$cnn);
		//echo $consulta;		
		if(!$ejecucion){
			echo "<br><br><center class=css-menu>No Se Realizo la Operacion".mysql_error()."<br>";
			echo "<a href=especial.php class=css-menu>Volver</a></center>";
		}else{
			echo "<br><br><center class=css-menu>Se Actualizaron ".mysql_affected_rows($cnn)." Registros...!<br>";
			echo "<a href=especial.php class=css-menu>Volver</a></center>";
			}
	echo "</td>";
	}
	?>
</tr>
</table>
</head>

<body>
<div align="center"> </div>
</body>
</html>