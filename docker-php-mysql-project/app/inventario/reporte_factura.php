<?php
include('main/valida.php');

?>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen"/>
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<link href="main/styles.css" rel="stylesheet" type="text/css">
<form method="post" name="form1" action="" >

<?php
include('main/conexiones.php');
$qry = "SELECT * FROM tbl_configuracion ";
//$qry .= "WHERE id_pelicula = $idc ";
$result = mysql_query($qry) or die("Error 3001: ".mysql_error());

if($campo = mysql_fetch_object($result)):

	$tasa = $campo->tasa; 

endif;

$sql="SELECT
					pv_tbc_encabezado_movimientos.ID_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.FECHA_EMISION,
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO,
					tbl_usuario.username,
					tbl_usuario.nombre_completo,
					pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN,
					pv_tbc_encabezado_movimientos.CODIGO_CAJA,
					pv_tbc_encabezado_movimientos.MONTO,hora
					FROM
					pv_tbc_encabezado_movimientos
					
					Inner Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario
		";
	$sql.=" where TIPO_MOVIMIENTO='FAC' and  ID_MOVIMIENTO=".$_GET["id"];
$result = mysql_query($sql);

while ($rs=mysql_fetch_array($result)){
	$codigo=str_pad($rs["NUMERO_MOVIMIENTO"], 9-strlen($rs["NUMERO_MOVIMIENTO"]) , '0', STR_PAD_LEFT);
	$fecha=explode("-",$rs["FECHA_EMISION"]);
	$fecha2=$rs["FECHA_EMISION"];
	
	$htm=$htm."			<table width='720' border='1' align='center' cellspacing='0' cellpadding='0'>";
	$htm=$htm."              <tr>";
	$htm=$htm."                <td colspan='4' align='center'  bgcolor='#F1F1F1' ><div align='center'><B>FACTURA</B></div></td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>";
	$htm=$htm."                <td  width='15%'> <b>&nbsp;&nbsp;Nro:</b></td>";
	$htm=$htm."                <td width='25%' align='left'> <b>".$codigo."</b></td>";
	$htm=$htm."                <td colspan='2'> <b>Emisión:&nbsp;".$fecha[2]."/".$fecha[1]."/".$fecha[0]."&nbsp;Hora:&nbsp;".$rs["hora"]."</b></td>";
	$htm=$htm."             </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'> <b>&nbsp;&nbsp;CAJA:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["CODIGO_CAJA"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'><b>&nbsp;&nbsp;Usuario:</b></td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["username"]."&nbsp;".$rs["nombre_completo"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."            </table>";
	$status=$rs["STATUS_MOVIMIENTO"];
	$login=$rs["nombre_completo"];
	 $sql="SELECT tasa
					 
		FROM `tbl_operacion` where fecha_operacion<='".$rs["FECHA_EMISION"]."'and tasa>0 order by fecha_operacion desc ,tasa desc limit 1,1
		";
	
	$result = mysql_query($sql);

	while ($rs=mysql_fetch_array($result)){
		 $tasa=$rs[0];
	}
	
					
}	
	
 
	$htm=$htm."<table width='720' border='1' cellspacing='0' cellpadding='0' align='center'>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='5' align='center'><b>Detalle de Factura</b></td>";
	$htm=$htm."</tr>";

	$htm=$htm."<tr>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1'  align='center'><b>Id</b></td>";
	$htm=$htm."	<td width='50%' bgcolor='#F1F1F1'  align='center'><b>Producto</b></td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1'  align='center'><b>Cantidad Unitaria</b></td>";
	$htm=$htm."	<td width='15%' bgcolor='#F1F1F1'  align='center'><b>Precio</b></td>";
	$htm=$htm."	<td width='15%' bgcolor='#F1F1F1'  align='center'><b>Subtotal</b></td>";
	$htm=$htm."</tr>";	
	
	$sql="SELECT a.*,
			b.DESCRIPCION_UNIDAD_AGRUPADA
		FROM
		pv_tbl_detalle_movimientos AS a Inner Join pv_tbc_unidades AS b ON a.CODIGO_UNIDAD_AGRUPADA = b.CODIGO_UNIDAD_AGRUPADA
		";
	$sql.=" where ID_MOVIMIENTO=".$_GET["id"]."";
	$i=1;
	$total=0;
	$total2=0;
	$result = mysql_query($sql);
			while ($rs=mysql_fetch_array($result)){
					$htm=$htm."<tr>";
					$htm=$htm."	<td width='10%'  align='CENTER'>".$i."</td>";
					$htm=$htm."	<td width='30%'  align='left'>".$rs["DESCRIPCION_UNITARIA"]."</td>";
					$htm=$htm."	<td width='10%'  align='center'>".number_format($rs["CANTIDAD_UNITARIA"],0)." </td>";
					$htm=$htm."	<td width='15%'  align='center'>".number_format(($rs["PRECIO_UNITARIO"]),2)."</td>";
					$htm=$htm."	<td width='15%'  align='center'>".number_format(($rs["CANTIDAD_UNITARIA"]*$rs["PRECIO_UNITARIO"]),2)."</td>";
					$htm=$htm."</tr>";	
				$i++;
				$total=$total+$rs["CANTIDAD_UNITARIA"];
				$total2=$total2+($rs["CANTIDAD_UNITARIA"]*$rs["PRECIO_UNITARIO"]);
				
			} 
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='2' align='right'><b>Total:</b></td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='center'><b>".number_format($total,2)."</b></td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='left'>&nbsp;</td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='center'><b>".number_format(($total2),2)."</b></td>";
	$htm=$htm."</tr>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='2' align='right'><b>Total $:</b></td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='center'></b></td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='left'>&nbsp;</td>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  align='center'><b>".number_format(($total2/$tasa),2)."</b></td>";
	$htm=$htm."</tr>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='5' align='right'><br><br><br></td>";
	$htm=$htm."</tr>";
	  
	$htm=$htm."      </table>";
	echo $htm.$htm2;
		
?>
<br>
<div align="center">
<input type="button" onclick="window.print();" value="           IMPRIMIR           " class="input" />
</div>
</form>