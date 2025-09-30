<?php

$fecha_actual = isset($_REQUEST['fecha1']) ? formato_fecha($_REQUEST['fecha1']) : date('Y-m-d', time());
$fecha_impresion = date('d/m/Y H:i a', time());
$cine = isset($_REQUEST['cine']) ? $_REQUEST['cine'] :  0;
$pelicula = isset($_REQUEST['pelicula']) ? $_REQUEST['pelicula'] : 0;

$fec=explode("/", $_REQUEST['fecha1']);
$fecha1 = $fec[2]."-".$fec[1]."-".$fec[0];
$fec=explode("/", $_REQUEST['fecha2']);
$fecha2 = $fec[2]."-".$fec[1]."-".$fec[0];

//phpinfo2();

//echo $fecha_actual ."<br>";
$qry = "
SELECT
	username,
	nombre_completo,
	sum(total),
	sum(tarjeta),
	sum(efectivo),
	sum(dolar),
	sum(Bsdolar),
	fecha_operacion,
	sum(cambio),
	sum(cbs)
FROM
	(
 SELECT
tbl_usuario.username,
tbl_usuario.nombre_completo,
Sum(tbl_operacion.total_transaccion) AS total,
Sum(tbl_operacion.tarjeta)tarjeta,
Sum(tbl_operacion.efectivo)efectivo,
Sum(tbl_operacion.dolar)dolar,
sum(tbl_operacion.tasa*dolar) Bsdolar,
tbl_operacion.fecha_operacion,cambio,(cambio/tasa)cbs
FROM
tbl_usuario
INNER JOIN tbl_operacion ON tbl_operacion.usuario_registro = tbl_usuario.username
where tbl_operacion.fecha_operacion >='$fecha1' and  tbl_operacion.fecha_operacion <='$fecha2' and transmitido<>2
GROUP BY
tbl_usuario.username,
tbl_usuario.nombre_completo,

tbl_operacion.fecha_operacion,cambio,(cambio/tasa)

) a
group by username,
	nombre_completo,
	fecha_operacion

 ";

$result = mysql_query($qry) or die("Error en Consulta: " . mysql_error());

?>
<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
</style>
<script>
function imprim1(){
var printContents = document.getElementById('imp1').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
		w.print();
		w.close();
        return true;}
</script>

<div id='imp1'>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
                <td width="110" valign="middle" align="left">
                <td width="100%" align="right" valign="middle"><a href="#" onClick="imprim1();"><img src="images/printer.gif" border="0"></a>&nbsp;&nbsp;
			    <a href="#" onClick="imprim1();" class="pelicula-nombre">Imprimir</a></td>
                </td>
            </tr>
	<tr>
		<td align="center" colspan="2" width="90%" class="pelicula-nombre-reporte" valign="middle" height="30"><b>Ventas Diarias Taquilla</b></td>
	</tr>
	<tr>
		<td colspan="2">
			 <table width="60%" border="1" cellspacing="0" cellpadding="0"align="center">
            <?php
			
			echo"<tr  valign='middle'>";
			echo"<td bgcolor='#F1F1F1' width='10%' class='pelicula-nombre' align='center'><b>Fecha</b></td>";
			echo"<td bgcolor='#F1F1F1' width='20%' class='pelicula-nombre' align='center'><b>Usuario</b></td>";
			echo"<td bgcolor='#F1F1F1' class='tan(arg)itulo' align='center'><b>Total Venta</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Efectivo Bs</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Tarjetas</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Divisa</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Divisa en Bs</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Total Recibido</b></td>";

			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Cambio Bs</b></td>";
			echo"<td bgcolor='#F1F1F1' class='pelicula-nombre' align='center'><b>Cambio $</b></td>";
			echo"</tr>";		
			$t1=0;
			$t2=0;
			$t3=0;
			$t4=0;
			$t5=0;
			$t6=0;
			$t7=0;
			$t8=0;

			while ($fila=mysql_fetch_array($result)){
					  $fec=explode("-",$fila[7]);
					  echo"<tr>";
					  echo"	<td valign='middle' class='info' align='left'>".$fec[2]."/".$fec[1]."/".$fec[0]."</td>";
					  echo"	<td valign='middle' class='info' align='left'>".$fila[0]."-".$fila[1]."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[2],0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[4],0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[3],0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[5],0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[6],0))))."</td>";
					  echo"	<td bgcolor='#F1F1F1' valign='middle' class='info' align='right'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[6]+$fila[4]+$fila[3],0))))."</b></td>";
					  
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[8],0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[9],0))))."</td>";

					  echo"</tr>";
					$t1+=$fila[2];
					$t2+=$fila[4];
					$t3+=$fila[3];
					$t4+=$fila[5];
					$t5+=$fila[6];
					$t6+=$fila[8];
					$t7+=$fila[9];
					$t8+=($fila[6]+$fila[4]+$fila[3]);
					  
			  }	
					  echo"<tr>";
					  echo"	<td valign='middle' class='info' align='right' colspan=2><b>Total:</b></td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t1,0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t2,0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t3,0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t4,0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t5,0))))."</td>";
					  echo"	<td bgcolor='#F1F1F1' valign='middle' class='info' align='right'><b>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t8,0))))."</b></td>";

					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t6,0))))."</td>";
					  echo"	<td valign='middle' class='info' align='right'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($t7,0))))."</td>";
					  
					  echo"</tr>";
			?>
          </table>
	
	</td>
	</tr>
</table>
</div>