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
	window.open ("reportes/reporte_ventas2_diarias.php?desde=" + document.form1.desde.value + "&hasta=" + document.form1.hasta.value ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=10,height=10"); 

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
			$result = mysql_query("SELECT *
							FROM
							pv_tbc_almacenes
							where CODIGO_ALMACEN like'$_POST[almacen]'
							");
					while ($rs=mysql_fetch_array($result)){
							$dep=$rs[1];
					}
			if ($_POST[almacen]=='%') $dep="TODOS";
			$sql = " select * from (SELECT
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.CODIGO_CAJA,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,
						sum(pv_tbc_encabezado_movimientos.MONTO)monto,1,username,nombre_completo ,CODIGO_USUARIO
						FROM
											pv_tbc_encabezado_movimientos
											Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
											Inner Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario 	
											
						WHERE
											pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA'   and tipo_movimiento='FAC'
											and FECHA_EMISION >='$_POST[desde]' and FECHA_EMISION<='$_POST[hasta]'
						GROUP BY
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.CODIGO_CAJA,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,1,username,nombre_completo ,CODIGO_USUARIO
						union
						SELECT
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						99,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,
						sum(pv_tbc_encabezado_movimientos.MONTO)monto,2,'' ,'',''
						FROM
											pv_tbc_encabezado_movimientos
											Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
						WHERE
											pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA'   and tipo_movimiento='FAC'
											and FECHA_EMISION >='$_POST[desde]' and FECHA_EMISION<='$_POST[hasta]'
						GROUP BY
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,2) a
					order by 3,2,5
			";

			$fec=explode("/",$_POST['desde']);
			$fec2=explode("/",$_POST['hasta']);
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='9'><b>Ventas</b></td>";
			echo"</tr height='35'>";
			echo"<tr  valign='middle' height=30>";
			echo"<td  valign='middle' bgcolor='#F1F1F1' class='titulo' align='center' colspan='9'><b>Listado de Ventas Diarias en Divisas<br>Desde:&nbsp;".$fec[2]."/".$fec[1]."/".$fec[0]."&nbsp;&nbsp;&nbsp;Hasta:&nbsp;".$fec2[2]."/".$fec2[1]."/".$fec2[0]." </b></td>";
			echo"</tr height='35'>";	
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Caja</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Usuario</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Efectivo Bs</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Pago Movil</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Tarjetas</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Efectivo $</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='titulo' align='center'><b>Divisas en Bs</b></td>";
			echo"<td bgcolor='#F1F1F1' class='titulo' align='center'><b>Monto</b></td>";
			echo"</tr>";	
			$t=0;	
			$te=0;	
			$td=0;	
			$td2=0;	
			$tc=0;	
			$tt=0;	
			$tte=0;	
			$ttd=0;	
			$ttd2=0;	
			$ttc=0;	
			$ttt=0;	
			$ttte=0;	
			$tttd2=0;	
			$tttd=0;	
			$tttc=0;	
			$tttt=0;	
			
			$result = mysql_query($sql);
			while ($fila=mysql_fetch_array($result)){
					  if ($fila["CODIGO_CAJA"]==99)
					  {
					  	  $fec=explode("-",$fila[2]);	
						  echo"<tr bgcolor='#F1F1F1' height='35'>";
						  echo"	<td valign='middle' align='right' colspan=3 class='contenido3'><b>TOTAL VENTA DIA ".$fec[2]."/".$fec[1]."/".$fec[0].":</b></td>";
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tte,2))))."</b></td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($ttc,2))))."</b></td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($ttt,2))))."</b></td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($ttd,2))))."</b></td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($ttd2,2))))."</b></td>";	
						  echo"	<td valign='middle' align='center' class='contenido3'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</b></td>";	
						  echo"</tr>";
						  $tte=0;	
						  $ttd=0;	
						  $ttd2=0;	
						  $ttc=0;	
						  $ttt=0;	
					  }
					  else
					  	{
						  $fec=explode("-",$fila[2]);	
						  echo"<tr height='35'>";
						  echo"	<td valign='middle' align='right' class='contenido2' > ".$fec[2]."/".$fec[1]."/".$fec[0]."</td>";
						  echo"	<td valign='middle' align='center' class='contenido3' ><b>&nbsp;CAJA ".$fila["CODIGO_CAJA"]."</b></td>";
						  echo"	<td valign='middle' align='left' class='contenido2' >".$fila["username"]." ".$fila["nombre_completo"]."</td>";
						  $sql="SELECT CODIGO_CAJA,CODIGO_INSTRUMENTO_PAGO,CODIGO_USUARIO,
							sum(MONTO_PAGO*CANTIDAD_INSTRUMENTO*tasa)monto,sum(MONTO_PAGO*CANTIDAD_INSTRUMENTO),TIPO_TARJETA FROM
							pv_tbc_encabezado_movimientos						
							Inner Join pv_tbl_pagos 
							ON pv_tbc_encabezado_movimientos.ID_MOVIMIENTO = pv_tbl_pagos.ID_MOVIMIENTO WHERE
							pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO <> 'ANULADA'   and tipo_movimiento='FAC'
							and FECHA_EMISION ='$fila[2]' 
							and CODIGO_USUARIO=$fila[CODIGO_USUARIO]
							and CODIGO_CAJA=$fila[CODIGO_CAJA]
							GROUP BY
							pv_tbc_encabezado_movimientos.CODIGO_CAJA,
							CODIGO_INSTRUMENTO_PAGO,CODIGO_USUARIO,TIPO_TARJETA ";
						  $te=0;	
						  $td=0;	
						  $td2=0;	
						  $tc=0;	
						  $tt=0;	
						  $result1 = mysql_query($sql);
						  while ($fila1=mysql_fetch_array($result1)){	
						  		if($fila1[1]==1)$te=$fila1[3];
								if($fila1[1]==2)$tc=$fila1[3];
								if($fila1[1]==6)$td=$fila1[4];
								if($fila1[1]==6)$td2=$fila1[3];
								
								if($fila1[1]==5 && $fila1[5]=='PA')$tc+=$fila1[3];
								if($fila1[1]==5 && $fila1[5]!='PA')$tt+=$fila1[3];
								
						  }
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($te,2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tc,2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tt,2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($td,2))))."</td>";	
						  echo"	<td valign='middle'  bgcolor='#c1c1Fc' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($td2,2))))."</td>";	
						  echo"	<td valign='middle' align='center' class='contenido2' >".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],2))))."</td>";	
						  $t+=$fila[3];	
						  $tte+=$te;	
						  $ttd+=$td;	
						  $ttd2+=$td2;	
						  $ttt+=$tt;	
						  $ttc+=$tc;
						  $ttte+=$te;	
						  $tttd+=$td;	
						  $tttd2+=$td2;	
						  $tttt+=$tt;	
						  $tttc+=$tc;
						
						  echo"</tr>";
						  }
			  }	
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' align='right' colspan=3><b>Total General:</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($ttte,2))))."</b></td>";
			echo"<td bgcolor='#c1c1Fc' class='contenido3'  align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tttc,2))))."</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tttt,2))))."</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tttd,2))))."</b></td>";
			echo"<td  bgcolor='#c1c1Fc' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($tttd2,2))))."</b></td>";
			echo"<td bgcolor='#F1F1F1' class='contenido3' align='center'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t,2))))."</b></td>";
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
