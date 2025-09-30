<?php   
if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Cuentas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		}
		include("main/valida.php");
		include("main/conexiones.php");
		if($_GET["tipo"]=="0") exit(1);
			$codigo=$_GET["codigo"];
	
?>

<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Inventarios</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen"/>
<link href="main/styles2.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<link href="main/styles.css" rel="stylesheet" type="text/css">
<html>
<style type="text/css">
<!--
.Estilo3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
<body>
<script language="javascript">
function imprimir()
{
	
}
</script>
<div align="center">

<form method="post" name="form1" action="reportes/reporte_ventas4_diarias.php" >
			
            
            <table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
            <?php
			$sql = " SELECT
				pv_tbl_detalle_movimientos_espera.MESA,
				pv_tbc_productos.DESCRIPCION_PRODUCTO,
				pv_tbl_detalle_movimientos_espera.CANTIDAD_UNITARIA,
				pv_tbl_detalle_movimientos_espera.PRECIO_UNITARIO
				FROM
				pv_tbl_detalle_movimientos_espera
				Inner Join pv_tbc_productos ON pv_tbl_detalle_movimientos_espera.CODIGO_PRODUCTO = pv_tbc_productos.CODIGO_PRODUCTO
				WHERE pv_tbl_detalle_movimientos_espera.MESA='$_GET[mesa]'
			
				
						";
			
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo3' align='center' colspan='4'><b>Cuentas Mesa: $_GET[mesa]</b></td>";
			echo"</tr height='35'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Producto</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Cantidad</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
			echo"</tr>";	
			$t=0;	
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
						  echo"<tr height='35'>";
						  echo"	<td valign='middle' align='center' class='contenido3' ><b>&nbsp; ".$fila[1]."</b></td>";
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[2],2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3]*$fila[2],2))))."</td>";	
						  $t+=($fila[3]*$fila[2]);	
						  echo"</tr>";

			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' colspan='3' align='right'><b>Total General:</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
			echo"</tr>";	
			?>
          </table>
          <div align="center"><img src="images/Salir.GIF" title="Cerrar Ventana" onClick="window.close();"></div>
          <input type="hidden" value="<?php echo $_POST[desde];?>" name="desde">
		  <input type="hidden" value="<?php echo $_POST[hasta];?>" name="hasta">
		
</form>


</div>
</body>
</html>
