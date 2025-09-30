<?php   if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Entradas.xls");
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
							where CODIGO_ALMACEN like'$_GET[almacen]'
							");
					while ($rs=mysql_fetch_array($result)){
							$dep=$rs[1];
					}
					
			if ($_GET[almacen]=='%') $dep="TODOS";
			$sql = " SELECT
					pv_tbc_productos.CODIGO_PRODUCTO,
					pv_tbc_productos.DESCRIPCION_PRODUCTO,
					pv_tbc_productos.MARCA,
					pv_tbl_detalle_movimientos.PRECIO_UNITARIO,
					SUM(pv_tbl_detalle_movimientos.CANTIDAD_UNITARIA) CANT,
					SUM(pv_tbl_detalle_movimientos.CANTIDAD_UNITARIA*pv_tbl_detalle_movimientos.PRECIO_UNITARIO) MON
					FROM
					pv_tbc_encabezado_movimientos
					Inner Join pv_tbl_detalle_movimientos ON pv_tbc_encabezado_movimientos.ID_MOVIMIENTO = pv_tbl_detalle_movimientos.ID_MOVIMIENTO
					Inner Join pv_tbc_productos ON pv_tbl_detalle_movimientos.CODIGO_PRODUCTO = pv_tbc_productos.CODIGO_PRODUCTO
					WHERE
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA' and ALMACEN_DESTINO like '$_GET[almacen]'  and tipo_movimiento='ENT'
					and FECHA_EMISION >='$_GET[desde]' and FECHA_EMISION<='$_GET[hasta]'
					GROUP BY 
					pv_tbc_productos.CODIGO_PRODUCTO,
					pv_tbc_productos.DESCRIPCION_PRODUCTO,
					pv_tbc_productos.MARCA,
					pv_tbl_detalle_movimientos.PRECIO_UNITARIO
					order by 2

			";
			  
			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Entradas</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Entradas por productos<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Almacen: $dep</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Producto</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Cantidad Unitaria</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Total</b></td>";
			echo"</tr>";	
			$t=0;	
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  echo"<tr>";
					  echo"	<td valign='middle' align='left'>".$fila[1]." ".$fila[2]."</td>";
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[4],2))))."</td>";
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</td>";
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],2))))."</td>";	
					  $t+=$fila[5];	
			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='right' colspan=3><b>Total:</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
			echo"</tr>";	
			?>
          </table>
</form>
</div>
</body>
</html>
