<?php   if($_GET["tipo1"]=="1"){
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: filename=Ventas.xls");
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
	window.open ("reportes/reporte_ventas222_diarias.php?desde=" + document.form1.desde.value + "&hasta=" + document.form1.hasta.value ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=10,height=10"); 

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
			<table width="100%" cellpadding="0" cellspacing="0" align="center">
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
			
			$sql = " SELECT
						DATE_FORMAT(pv_tbc_encabezado_movimientos.FECHA_EMISION,'%d/%m/%Y'),
						pv_tbc_encabezado_movimientos.OBSERVACION_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.CODIGO_CAJA
						FROM
						pv_tbc_encabezado_movimientos
						WHERE
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO = 'FAC' and STATUS_MOVIMIENTO='ANULADA' AND
						FECHA_EMISION >='$_POST[desde]' and FECHA_EMISION<='$_POST[hasta]'
						ORDER BY FECHA_EMISION,NUMERO_MOVIMIENTO
			";

			$fec=explode("/",$_POST['desde']);
			$fec2=explode("/",$_POST['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>Ventas</b></td>";
			echo"</tr height='35'>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='4'><b>FACTURAS ANULADAS<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr height='35'>";	
			echo"<tr  valign='middle' height=30>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Usuario</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>NUMERO</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Caja</b></td>";
			echo"</tr>";	
			
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					 echo"<tr bgcolor='#F1F1F1' height='35'>";
						  echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[0]."</td>";
						  echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[1]."</td>";
						  echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[2]."</td>";
						  echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[3]."</td>";
						  echo"</tr>";
						  
			  }	
			
			?>
          </table>
          <input type="hidden" value="<?php echo $_POST[desde];?>" name="desde">
		  <input type="hidden" value="<?php echo $_POST[hasta];?>" name="hasta">
		
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
?>
</div>
</body>
</html>
