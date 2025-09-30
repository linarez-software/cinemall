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

function vista2(id)
{
		window.open ("reporte_ventas4_diarias_detalle.php?mesa="+ eval("document.form1.mesa" + id+".value"), "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=750,height=800");  


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
<form method="post" name="form1" action="reportes/reporte_ventas4_diarias.php" >
			<table width="100%" cellpadding="0" cellspacing="0" align="center">
            
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
			$sql = " SELECT  MESA , SUM(  CANTIDAD_UNITARIA *  PRECIO_UNITARIO) monto 
                       FROM  pv_tbl_detalle_movimientos_espera GROUP BY 1			";
			
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='2'><b>Cuentas Abiertas</b></td>";
			echo"</tr height='35'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Mesa</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
			echo"</tr>";	
			$t=0;	
			$i=0;	
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
						  $i++;	
						  echo"<tr height='35'  onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff' onclick='vista2(".$i.")'>";
						  echo"	<td valign='middle' align='center' class='contenido3' ><b>&nbsp;Mesa ".$fila[0]."</b></td>";
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[1],2))))."</td>";	
						  $t+=$fila[1];	
						  
						  echo"</tr>";
						  echo "<input type='hidden' name='mesa$i' value='$fila[0]'>";

			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' align='right'><b>Total General:</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
			echo"</tr>";	
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
