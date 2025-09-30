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
<body onLoad="window.print(); window.close();">
<div align="center">
<form method="post" name="form1">
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
					pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.FECHA_EMISION,
					pv_tbc_almacenes.NOMBRE_ALMACEN,
					pv_tbc_encabezado_movimientos.MONTO,
					pv_tbc_encabezado_movimientos.hora
					FROM
					pv_tbc_encabezado_movimientos
					Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
					WHERE
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA' and ALMACEN_ORIGEN like '$_GET[almacen]'  and tipo_movimiento='FAC'
					and FECHA_EMISION >='$_GET[desde]' and FECHA_EMISION<='$_GET[hasta]'
					order by 2
			";
	
			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='5'><b>Ventas</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='5'><b>Listado de Ventas<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='5'><b>Almacen: $dep</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Numero</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Hora</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Almacen</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
			echo"</tr>";	
			$t=0;	
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  $fec=explode("-",$fila[2]);	
					  echo"<tr>";
					  echo"	<td valign='middle' align='left'>".$fila[1]."</td>";
					  echo"	<td valign='middle' align='right'>".$fec[2]."/".$fec[1]."/".$fec[0]."</td>";
					  echo"	<td valign='middle' align='center'>".$fila[5]."</td>";
					  echo"	<td valign='middle' align='right'>".$fila[3]."</td>";
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[4],2))))."</td>";	
					  $t+=$fila[4];	
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
