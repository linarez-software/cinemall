<?php  include("../main/valida.php");
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

<form method="post" name="form1" action="">
			
            <table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
            <?php
			$sql = " select * from (
						SELECT
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						99,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,
						sum(pv_tbc_encabezado_movimientos.MONTO)monto,2
						FROM
											pv_tbc_encabezado_movimientos
											Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
						WHERE
											pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA'   and tipo_movimiento='FAC'
											and FECHA_EMISION >='$_GET[desde]' and FECHA_EMISION<='$_GET[hasta]'
						GROUP BY
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,2) a
					order by 3,2,5
			";

			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='2'><b>Ventas</b></td>";
			echo"</tr height='35'>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='2'><b>Listado de Ventas Diarias<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr height='35'>";	
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
			echo"</tr>";	
			$t=0;	
			$te=0;	
			$tc=0;	
			$tt=0;	
			$tte=0;	
			$ttc=0;	
			$ttt=0;	
			$ttte=0;	
			$tttc=0;	
			$tttt=0;	
			
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  
						  $fec=explode("-",$fila[FECHA_EMISION]);	
						  echo"<tr height='35'>";
						  echo"	<td valign='middle' align='right' class='contenido2' > ".$fec[2]."/".$fec[1]."/".$fec[0]."</td>";
						   echo"	<td valign='middle' align='right' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</td>";	
						  $t+=$fila[3];	
						  $tte+=$te;	
						  $ttt+=$tt;	
						  $ttc+=$tc;	
						  $ttte+=$te;	
						  $tttt+=$tt;	
						  $tttc+=$tc;	
						  echo"</tr>";
						  
			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' align='right' ><b>Total General:</b></td>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' align='right'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
			echo"</tr>";	
			?>
          </table>
</form>
</div>
</body>
</html>
