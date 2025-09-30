<script type="text/javascript" src="js/func.global.js"></script>
<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="main/calendar.js"></script>
<script type="text/javascript" src="main/calendar-setup.js"></script>
<script type="text/javascript" src="main/lang/calendar-es.js"></script>

<?php
include('main/valida.php');
include('main/conexiones.php');


	$sql="SELECT
		pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
		pv_tbc_encabezado_movimientos.FECHA_EMISION,
		pv_tbc_almacenes.NOMBRE_ALMACEN,
		pv_tbc_encabezado_movimientos.ORIGEN,
		pv_tbc_encabezado_movimientos.OBSERVACION_MOVIMIENTO,
		tbl_usuario.nombre_completo,
		pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO,
		pv_tbc_proveedores.NOMBRE_PROVEEDOR
		FROM
		pv_tbc_encabezado_movimientos
		Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_DESTINO = pv_tbc_almacenes.CODIGO_ALMACEN
		Inner Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario
		Inner Join pv_tbc_proveedores ON pv_tbc_encabezado_movimientos.CODIGO_CLIENTE = pv_tbc_proveedores.CODIGO_PROVEEDOR
		";
	$sql.=" where TIPO_MOVIMIENTO='REC' and  ID_MOVIMIENTO=".$_GET["id"];
$result = mysql_query($sql);

while ($rs=mysql_fetch_array($result)){
	$codigo=str_pad($rs["NUMERO_MOVIMIENTO"], 9-strlen($rs["NUMERO_MOVIMIENTO"]) , '0', STR_PAD_LEFT);
	$fecha=explode("-",$rs["FECHA_EMISION"]);
	
	$htm=$htm."			<table width='720' align='center'  border='1' cellspacing='0' cellpadding='0'>";
	$htm=$htm."  <tr  bgcolor='#FF9900'>";
	$htm=$htm."      ";
	$htm=$htm."      <td  colspan='4'  bgcolor='#FF9900' height='70' valign='top'><div align='left'><img src='images/logo.jpg' width='60' height='61' /></div></td>";
	$htm=$htm."    </tr> ";
	$htm=$htm."              <tr>";
	$htm=$htm."                <td colspan='4' align='center'  bgcolor='#F1F1F1' ><div align='center'><B>NOTA DE RECEPCION</B></div></td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Nro:</td>";
	$htm=$htm."                <td width='25%' align='left'>".$codigo."</td>";
	$htm=$htm."                <td colspan='2'>Emisión:&nbsp;".$fecha[2]."/".$fecha[1]."/".$fecha[0]."</td>";
	$htm=$htm."             </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Almacen destino:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["NOMBRE_ALMACEN"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Provedor:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["NOMBRE_PROVEEDOR"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Observaciones:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["OBSERVACION_MOVIMIENTO"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."            </table>";
	$status=$rs["STATUS_MOVIMIENTO"];
	$login=$rs["nombre_completo"];
	
	
					
}	
	
	$htm=$htm."<table width='720' border='1' cellspacing='0' align='center' cellpadding='0'>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='10' align='center'>Detalle de Recepcion</td>";
	$htm=$htm."</tr>";

	$htm=$htm."<tr>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1'  align='center'>Id</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1' class='TITULO' align='left'>Producto</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1' class='TITULO' align='center'>Unidades</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Unidad Agrupada</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Cantidad Agrupada</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Costo</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>% Desc.</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Monto</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>P. Venta</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Monto</td>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>% Venta</td>";
	$htm=$htm."</tr>";	
	
	$sql="SELECT a.*,
			b.DESCRIPCION_UNIDAD_AGRUPADA
		FROM
		pv_tbl_detalle_movimientos AS a Inner Join pv_tbc_unidades AS b ON a.CODIGO_UNIDAD_AGRUPADA = b.CODIGO_UNIDAD_AGRUPADA
		";
	$sql.=" where ID_MOVIMIENTO=".$_GET["id"]." ";
	$i=1;
	$total=0;
	$total2=0;
	$sub=0;
	$desc=0;
	$subv=0;
	$descv=0;
	$result = mysql_query($sql);
			while ($rs=mysql_fetch_array($result)){
					$htm=$htm."<tr>";
					$htm=$htm."	<td class='TITULO2' align='CENTER'>".$i."</td>";
					$htm=$htm."	<td class='TITULO2' align='left'>".$rs["DESCRIPCION_UNITARIA"]."</td>";
					$htm=$htm."	<td class='TITULO2' align='left'>".number_format($rs["CANTIDAD_UNITARIA"],0)." </td>";
					$htm=$htm."	<td class='TITULO2' align='left'>$rs[DESCRIPCION_UNIDAD_AGRUPADA]</td>";
					$htm=$htm."	<td class='TITULO2' align='left'>".number_format(($rs["CANTIDAD_UNITARIA"]/$rs["CANTIDAD_AGRUPADA"]),2)."</td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format($rs["costo"],3)." </td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format($rs["descuento"],2)." </td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format(($rs["costo"]*$rs["CANTIDAD_UNITARIA"]*(1-($rs["descuento"]/100))),2)." </td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format($rs["PRECIO_UNITARIO"],2)." </td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format(($rs["PRECIO_UNITARIO"]*$rs["CANTIDAD_UNITARIA"]),2)." </td>";
					$htm=$htm."	<td class='TITULO2' align='right'>".number_format(($rs["PRECIO_UNITARIO"]-($rs["costo"]*(1+($rs["PORCENTAJE_IMPUESTO"]/100))))/$rs["PRECIO_UNITARIO"],2)." </td>";
					$htm=$htm."</tr>";	
				$i++;
				$iva=($rs["PORCENTAJE_IMPUESTO"]);
				$sub+=($rs["CANTIDAD_UNITARIA"]*$rs["costo"]);
				$desc+=($rs["CANTIDAD_UNITARIA"]*$rs["costo"]*$rs["descuento"]/100);
				$subv+=($rs["PRECIO_UNITARIO"]*$rs["CANTIDAD_UNITARIA"]);
				$descv+=0;
			} 
			$htm=$htm."<tr>";
			$htm=$htm."	<td class='TITULO' align='right' colspan=6>Subtotal:</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($sub,2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($subv*100/112,2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right'></td>";
			$htm=$htm."</tr>";
			$htm=$htm."<tr>";
			$htm=$htm."	<td class='TITULO' align='right' colspan=6>Descuento:</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($desc,2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format(0,2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right'></td>";
			$htm=$htm."</tr>";
			$htm=$htm."<tr>";
			$htm=$htm."	<td class='TITULO' align='right' colspan=6>Base:</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format(($sub-$desc),2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($subv*100/($iva+100),2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right'></td>";
			$htm=$htm."</tr>";
			$htm=$htm."<tr>";
			$htm=$htm."	<td class='TITULO' align='right' colspan=6>Iva 12 %:</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format(($sub-$desc)*($iva/100),2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($subv*$iva/($iva+100),2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right'></td>";
			$htm=$htm."</tr>";
			$htm=$htm."<tr>";
			$htm=$htm."	<td class='TITULO' align='right' colspan=6>Total:</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format(($sub-$desc)*(1+($iva/100)),2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right' colspan=2>".number_format($subv,2)."</td>";
			$htm=$htm."	<td class='TITULO2' align='right'></td>";
			$htm=$htm."</tr>";
	$htm=$htm."      </table>";
	echo $htm.$htm2;
	exit();
	include_once ('pdf/html2fpdf.php');  
	$pdf = new html2fpdf(); // Generamos un objeto nuevo html2fpdf  
	$pdf -> AddPage(); // Añadimos una página 
	$pdf -> WriteHTML($htm); // Indicamos la variable con el contenido que queremos incluir en el pdf  
	if ($status=="ANULADA")
		{
			$pdf->SetY(100);
			$pdf->SetFont22('Arial','B',40);
			$pdf->Cell(0,10,"ANULADA ",0,0,'C');
		}
	if($i>40){	
		
		if ($status!="ANULADA")	$pdf->Code39(75,235,$codigo,1,10);
		$pdf -> AddPage(); 
		$pdf -> WriteHTML($htm2); // Indicamos la variable con el contenido que queremos incluir en el pdf  
		if ($status=="ANULADA")
			{
				$pdf->SetY(100);
				$pdf->SetFont22('Arial','B',40);
				$pdf->Cell(0,10,"ANULADA ",0,0,'C');
				}
		
	}
	if ($status!="ANULADA")	{
			$pdf->SetY(-45);
			$pdf->SetFont2('Arial','B',9);
			$pdf->Cell(200,0,"REALIZADO POR:",0,0,'C');
			//----------
			$pdf->SetFont2('Arial','B',7);
			$pdf->SetY(-35);
			$pdf->Cell(200,10,$login,0,0,'C');
	}
	//$pdf->image('images/botom.jpg',33,268,200,13);
	if ($status=="ANULADA")
		{
			$pdf->SetY(100);
			$pdf->SetFont22('Arial','B',40);
			$pdf->Cell(0,10,"ANULADA ",0,0,'C');
		}
	$pdf -> Output("RECEPCION".$codigo.".pdf", "D");
?>