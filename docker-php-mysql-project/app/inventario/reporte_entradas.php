<?php   if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Entradas.xls");
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
	window.open ("reportes/reporte_entradas.php?desde=" + document.form1.desde.value + "&hasta=" + document.form1.hasta.value + "&almacen=" + document.form1.almacen.value ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=10,height=10"); 

}
</script><div align="center">
<?php
if($_GET["tipo1"]!="1"){
?>

<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="3"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="134" height="100%" valign="top" align="left"><?php include "menu_reportes.php";?></td>
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
                <td width="100%" align="right" valign="middle"><a href="#" onClick="imprimir();"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;
			    <a href="#" onClick="imprimir();" class="titulo">Imprimir</a></td>
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
							where CODIGO_ALMACEN like'$_POST[almacen]'
							");
					while ($rs=mysql_fetch_array($result)){
							$dep=$rs[1];
					}
					
			if ($_POST[almacen]=='%') $dep="TODOS";
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
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA' and ALMACEN_DESTINO like '$_POST[almacen]'  and tipo_movimiento='ENT'
					and FECHA_EMISION >='$_POST[desde]' and FECHA_EMISION<='$_POST[hasta]'
					GROUP BY 
					pv_tbc_productos.CODIGO_PRODUCTO,
					pv_tbc_productos.DESCRIPCION_PRODUCTO,
					pv_tbc_productos.MARCA,
					pv_tbl_detalle_movimientos.PRECIO_UNITARIO
					order by 2
			";
			  
			$fec=explode("/",$_POST['desde']);
			$fec2=explode("/",$_POST['hasta']);
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
					  echo"	<td valign='middle' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[4],2))))."</td>";
					  echo"	<td valign='middle' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</td>";
					  echo"	<td valign='middle' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],2))))."</td>";	
					  $t+=$fila[5];	
			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='right' colspan=3><b>Total:</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
			echo"</tr>";	
			?>
          </table>
          <input type="hidden" value="<?php echo $_POST[desde];?>" name="desde">
		  <input type="hidden" value="<?php echo $_POST[hasta];?>" name="hasta">
		  <input type="hidden" value="<?php echo $_POST[almacen];?>" name="almacen">
		  <input type="hidden" value="<?php echo $_POST[origen];?>" name="origen">

</form>
<?php
if($_GET["tipo1"]!="1"){
?>
    </td>
    <td width="99"></td>
</tr>
</table>
<?php
}
?></div>
</body>
</html>
