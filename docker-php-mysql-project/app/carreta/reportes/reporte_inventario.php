<?php   if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Inventario.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		}
		include("../main/valida.php");
		include("../main/conexiones.php");
		if($_GET["tipo"]=="0") exit(1);
			$codigo=$_GET["codigo"];
	
?>
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Inventarios</title>
<link href="../ccs/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../css/thickbox.css" type="text/css" media="screen"/>
<link href="../main/styles2.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="../main/calendar-win2k-cold-1.css">
<link href="../main/styles.css" rel="stylesheet" type="text/css">
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
<body onLoad="window.print(); window.close();">
<div align="center">

<form method="post" name="form1" action="Consulta_facturas.php" >
			
            <table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
            <?php
			$result = mysql_query("SELECT *
							FROM
							pv_tbc_almacenes
							where CODIGO_ALMACEN=$_GET[almacen]
							");
					while ($rs=mysql_fetch_array($result)){
							$dep=$rs[1];
					}
			$sql = " SELECT
					pv_tbc_productos.CODIGO_PRODUCTO,
					pv_tbc_productos.DESCRIPCION_PRODUCTO,
					pv_tbc_productos.MARCA,
					pv_tbc_unidades.DESCRIPCION_UNIDAD_AGRUPADA,
					pv_tbc_productos.CANTIDAD_UNIDAD_AGRUPADA,
					pv_tbc_inventarios.EXISTENCIA_PRODUCTO
					FROM
					pv_tbc_productos
					Inner Join pv_tbc_unidades ON pv_tbc_productos.CODIGO_UNIDAD_AGRUPADA = pv_tbc_unidades.CODIGO_UNIDAD_AGRUPADA
					Inner Join pv_tbc_inventarios ON pv_tbc_productos.CODIGO_PRODUCTO = pv_tbc_inventarios.CODIGO_PRODUCTO

					where CODIGO_ALMACEN=$_GET[almacen] order by 2
			";
			  
			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Almacen: $dep</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Inventario</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' width='50%' class='titulo' align='center'><b>Producto</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Cantidad Unitaria</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Unidad Agrupada</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Cantidad Agrupada</b></td>";
			echo"</tr>";		
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  echo"<tr>";
					  echo"	<td valign='middle' class='contenido2' align='left'>".$fila[1]."</td>";
					  echo"	<td valign='middle' class='contenido2' align='left'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],0))))."</td>";
					  echo"	<td valign='middle' class='contenido2' align='left'>".$fila[3]."</td>";
					  echo"	<td valign='middle' class='contenido2' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5]/$fila[4],2))))."</td>";	
			  }	
			 
			?>
          </table>
</form>
</div>
</body>
</html>
