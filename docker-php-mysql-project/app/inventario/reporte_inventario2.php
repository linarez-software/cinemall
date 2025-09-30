<?php   if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Inventario.xls");
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
<script type="text/javascript" src="js/func.global.js"></script>
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
	window.open ("reportes/reporte_inventario.php?almacen=" + document.form1.almacen.value ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=10,height=10"); 

}
function valid(i)
{
	document.form1.action='reporte_inventario3.php?insert=1&almacen=' + document.form1.almacen.value + "&cantidad=" + document.form1.cant.value;
	document.form1.submit();
}
</script>

<div align="center">

<?php
		if($_GET["tipo1"]!="1"){
		?>
		
		<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
		<tr>
			<td colspan="3"><?php include "head.php";?></td>
		</tr>
		<tr>
			<td width="134" height="100%" valign="top" align="left"><?php include "menu_movimientos.php";?></td>
			<td width="990" align="left" valign="top">
		<?php
		}
		else
		{
		echo "<br><br>";
		}
		?>
		
		<form method="post" name="form1" action="" >
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="110" valign="middle" align="left">
						<?php
						if($_GET["tipo1"]=="2"){
						?>
						<td width="100%" align="right" valign="middle"></td>
						 <?php
						}
						?>
						</td>
					</tr>
					<tr>
						<td width="110" valign="middle" align="left">
						<?php
						if($_GET["tipo1"]=="1"){
						?>
						<img src="images/logo.jpg" hspace="4" vspace="4">
						 <?php
						}
						?>
						</td>
					</tr>
					<tr>
						<td height="2">
						
						<img src="images/tr.gif">
					   
						</td>
					</tr>
					</table>
					<?php
						if($_GET["tipo1"]=="1"){
						?>
					<br>
					<br>
					<br>
					<br><br>
					 <?php
						}
					 ?>
		
					<table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
					<?php
					$result = mysql_query("SELECT *
									FROM
									pv_tbc_almacenes
									where CODIGO_ALMACEN=$_POST[almacen]
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
							pv_tbc_inventarios.EXISTENCIA_PRODUCTO,
							pv_tbc_productos.PRECIO,
							pv_tbc_inventarios.ID,(SELECT max(NOMBRE_PROVEEDOR) FROM pv_tbl_productos_proveedores aa inner join pv_tbc_proveedores b on aa.CODIGO_PROVEEDOR=b.CODIGO_PROVEEDOR where CODIGO_PRODUCTO=pv_tbc_productos.CODIGO_PRODUCTO )prov
							FROM
							pv_tbc_productos
							Inner Join pv_tbc_unidades ON pv_tbc_productos.CODIGO_UNIDAD_AGRUPADA = pv_tbc_unidades.CODIGO_UNIDAD_AGRUPADA
							Inner Join pv_tbc_inventarios ON pv_tbc_productos.CODIGO_PRODUCTO = pv_tbc_inventarios.CODIGO_PRODUCTO
		
							where CODIGO_ALMACEN=$_POST[almacen] order by prov,2
					";
					  
					$fec=explode("/",$_POST['desde']);
					$fec2=explode("/",$_POST['hasta']);
					echo"<tr  valign='middle' height=30>";
					echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='6'><b>Almacen: $dep</b></td>";
					echo"</tr>";
					echo"<tr  valign='middle' height=30>";
					echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='6'><b>Inventario</b></td>";
					echo"</tr>";
					echo"<tr  valign='middle'>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Distribuidor</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Producto</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Existencia Sistema</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Existencia Fisica</b></td>";
					echo"</tr>";		
					$result = mysql_query($sql);
					$i=0;
					while ($fila=mysql_fetch_array($result)){
							   echo"<input type='hidden' value='$fila[6]' name='p$i'>";
							  echo"<input type='hidden' value='$fila[7]' name='id$i'>";
							  echo"<tr height='20'>";
							  echo"	<td valign='middle' class='titulo' align='left'>".$fila[8]."</td>";
							  echo"	<td valign='middle' class='titulo' align='left'>".$fila[1]."</td>";
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],0))))."</td>";
							  echo"	<td valign='middle' class='titulo2' align='left'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[6],2))))."</td>";
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5]*$fila[6],2))))."</td>";	
							  echo"	<td valign='middle' class='titulo2' align='center'><input class='input'  size=10 align='left' type='text' value='' name='e$i' onKeyPress='return(SoloNumero(event))' onKeyUp='valida($i);'></td>";	
							  $i++;
					  }	
					 	$sql="delete  from pv_tbc_inventarios_toma ;";
						$result = mysql_query($sql);
					?>
				  </table>
				  <input type="hidden"  value="<?php echo $_POST[almacen];?>"   name="almacen">
				<input type="hidden"  value="<?php echo ($i-1);?>" name="cant">
				<br>
				<div align="center"> <img src="images/resetear.jpg" title="Generar" width="48" height="50" onClick="valid();">&nbsp;&nbsp;</div>
				<br>
				<br>
				
		</form>
		
			</td>
			<td width="99"></td>
		</tr>
		</table>
		 </div>
</body>
</html>
