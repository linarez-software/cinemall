<?php   
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
<body  onLoad="window.print(); window.close();">
<div align="center">

<form method="post" name="form1" action="" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
            <?php
			
			$sql = " select * from pv_factura_eliminar
						WHERE
											 FECHA >='$_GET[desde]' and FECHA<='$_GET[hasta]'
			";

			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='5'><b>Ventas</b></td>";
			echo"</tr height='35'>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='5'><b>PRODUCTO ELIMINADOS DE LA PANTALLA<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr height='35'>";	
			echo"<tr  valign='middle' height=30>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Usuario</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Producto</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Cantidad</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Caja</b></td>";
			echo"</tr>";	
			
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					 echo"<tr bgcolor='#F1F1F1' height='35'>";
					echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[1]."</td>";
					echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[2]."</td>";
					echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[3]."</td>";
					echo"	<td valign='middle' align='left'  class='contenido3'>".$fila[6]."</td>";
					echo"</tr>";
						  
			  }	
			
			?>
          </table>
</form>
</div>
</body>
</html>
