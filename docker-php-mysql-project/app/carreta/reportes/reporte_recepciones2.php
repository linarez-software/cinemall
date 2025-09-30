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

<form method="post" name="form1" action="Consulta_facturas.php" >
			
            <table width="100%" border="1" cellspacing="0" cellpadding="0"align="center">
             <?php
			$dep="";
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
					pv_tbc_encabezado_movimientos.ORIGEN,OBSERVACION_MOVIMIENTO,NOMBRE_PROVEEDOR,
					sum(CANTIDAD_UNITARIA*PRECIO_UNITARIO) vta,
					sum(CANTIDAD_UNITARIA*costo) csto
					FROM
					pv_tbc_encabezado_movimientos
					Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_DESTINO = pv_tbc_almacenes.CODIGO_ALMACEN
					Inner Join pv_tbc_proveedores ON pv_tbc_encabezado_movimientos.CODIGO_CLIENTE = pv_tbc_proveedores.CODIGO_PROVEEDOR
					Inner Join pv_tbl_detalle_movimientos  ON pv_tbc_encabezado_movimientos.ID_MOVIMIENTO = pv_tbl_detalle_movimientos.ID_MOVIMIENTO
					WHERE
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA' and ALMACEN_DESTINO like '$_GET[almacen]'  and tipo_movimiento='REC'
					and FECHA_EMISION >='$_GET[desde]' and FECHA_EMISION<='$_GET[hasta]'
					and CODIGO_PROVEEDOR like'$_GET[origen]'
					group by 
					pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.FECHA_EMISION,
					pv_tbc_almacenes.NOMBRE_ALMACEN,
					pv_tbc_encabezado_movimientos.MONTO,
					pv_tbc_encabezado_movimientos.ORIGEN,OBSERVACION_MOVIMIENTO,NOMBRE_PROVEEDOR
					order by 2
			";

			$fec=explode("/",$_GET['desde']);
			$fec2=explode("/",$_GET['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='7'><b>Recepciones</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='7'><b>Listado de Recepciones<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='7'><b>Almacen: $dep</b></td>";
			echo"</tr>";
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Numero</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Almacen</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Proveedor</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Observaciones</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio VTA</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Precio Costo</b></td>";
			echo"</tr>";	
			$t=0;	
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  $fec=explode("-",$fila[2]);	
					  echo"<tr>";
					  echo"	<td valign='middle' align='left'>".$fila[1]."</td>";
					  echo"	<td valign='middle' align='right'>".$fec[2]."/".$fec[1]."/".$fec[0]."</td>";
					  echo"	<td valign='middle' align='right'>".$fila[3]."</td>";
					  echo"	<td valign='middle' align='right'>".$fila["NOMBRE_PROVEEDOR"]."</td>";	
					  echo"	<td valign='middle' align='right'>".$fila[6]."</td>";	
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[8],2))))."</td>";	
					  echo"	<td valign='middle' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[9],2))))."</td>";	
					  $t+=$fila[4];	
			  }	
			
			?>
          </table>
</form>
</div>
</body>
</html>
