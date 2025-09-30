<?php   
		include("main/valida.php");
		include("main/conexiones.php");
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
	window.open ("reportes/reporte_inventario3.php?almacen=" + document.form1.almacen.value ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=10,height=10"); 

}
function valid()
{
	document.form1.action='reporte_inventario4.php?genera=2';
	document.form1.submit();
}
</script>

<div align="center">
<form method="post" name="form1" action="" >
		<?php
		$sql="delete  from pv_tbc_inventarios_toma ;";
		$result = mysql_query($sql);
		for($i=0;$i<=$_GET["cantidad"];$i++)
		{
			$name="id$i";
		 	$name2="e$i";
			if($_POST[$name2]=="") $exis=0;
			if($_POST[$name2]!="") $exis=$_POST[$name2];
			
		 	$sql="insert into pv_tbc_inventarios_toma select *,'$exis' from pv_tbc_inventarios where id=$_POST[$name]";
			$result = mysql_query($sql);
		 }
		
		?>
        
        <table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
		<tr>
			<td colspan="3"><?php include "head.php";?></td>
		</tr>
		<tr>
			<td width="134" height="100%" valign="top" align="left"><?php include "menu_movimientos.php";?></td>
			<td width="990" align="left" valign="top">
		
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
						<td width="100%" align="right" valign="middle"><a href="#" onClick="imprimir();"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;
			    <a href="#" onClick="imprimir();" class="titulo">Imprimir</a></td>
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
							pv_tbc_inventarios.ID,(SELECT max(NOMBRE_PROVEEDOR) FROM pv_tbl_productos_proveedores aa inner join pv_tbc_proveedores b on aa.CODIGO_PROVEEDOR=b.CODIGO_PROVEEDOR where CODIGO_PRODUCTO=pv_tbc_productos.CODIGO_PRODUCTO )prov,
							pv_tbc_inventarios.EXISTENCIA_NUEVA
							FROM
							pv_tbc_productos
							Inner Join pv_tbc_unidades ON pv_tbc_productos.CODIGO_UNIDAD_AGRUPADA = pv_tbc_unidades.CODIGO_UNIDAD_AGRUPADA
							Inner Join pv_tbc_inventarios_toma pv_tbc_inventarios ON pv_tbc_productos.CODIGO_PRODUCTO = pv_tbc_inventarios.CODIGO_PRODUCTO
		
							where CODIGO_ALMACEN=$_POST[almacen] order by prov,2
					";
					  
					$fec=explode("/",$_POST['desde']);
					$fec2=explode("/",$_POST['hasta']);
					echo"<tr  valign='middle' height=30>";
					echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='7'><b>Almacen: $dep</b></td>";
					echo"</tr>";
					echo"<tr  valign='middle' height=30>";
					echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='7'><b>Inventario</b></td>";
					echo"</tr>";
					echo"<tr  valign='middle'>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Distribuidor</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Producto</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Existencia Sistema</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Existencia Fisica</b></td>";
					echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto Fisica</b></td>";
					echo"</tr>";		
					$result = mysql_query($sql);
					$i=0;
					$t1=0;
					$t2=0;
					$t3=0;
					$t4=0;
					
					while ($fila=mysql_fetch_array($result)){
							   echo"<input type='hidden' value='$fila[6]' name='p$i'>";
							  echo"<input type='hidden' value='$fila[7]' name='id$i'>";
							  echo"<tr height='20'>";
							  echo"	<td valign='middle' class='titulo' align='left'>".$fila[8]."</td>";
							  echo"	<td valign='middle' class='titulo' align='left'>".$fila[1]."</td>";
							  echo"	<td valign='middle' class='titulo2' align='left'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[6],2))))."</td>";
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],0))))."</td>";
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5]*$fila[6],2))))."</td>";	
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[9],2))))."</td>";	
							  echo"	<td valign='middle' class='titulo2' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5]*$fila[9],2))))."</td>";	
							  $i++;
							  $t1+=$fila[5];
							  $t2+=($fila[5]*$fila[6]);
							  $t3+=$fila[9];
							  $t4+=($fila[9]*$fila[6]);
							  
					  }	
					 
					?>
                    <tr>
                    	<td colspan="3" class="TITULO" align="right">Total General:</td>
                        <td class="TITULO" align="right"><?php echo number_format($t1,2);?></td>
                        <td class="TITULO" align="right"><?php echo number_format($t2,2);?></td>
                        <td class="TITULO" align="right"><?php echo number_format($t3,2);?></td>
                        <td class="TITULO" align="right"><?php echo number_format($t4,2);?></td>
                        
                    </tr>
				  </table>
				  <input type="hidden"  value="<?php echo $_POST[almacen];?>"   name="almacen">
				<input type="hidden"  value="<?php echo ($i-1);?>" name="cant">
				<br>
				<div align="center"> <img src="images/check.JPG" title="Procesar" width="30" height="30" onClick="valid();">&nbsp;&nbsp;</div>
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
