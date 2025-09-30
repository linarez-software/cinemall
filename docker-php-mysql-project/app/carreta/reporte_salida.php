<?php
include('main/valida.php');
include('main/conexiones.php');


$sql="SELECT
		pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
		pv_tbc_encabezado_movimientos.FECHA_EMISION,
		pv_tbc_almacenes.NOMBRE_ALMACEN,
		pv_tbc_encabezado_movimientos.ORIGEN,
		pv_tbc_encabezado_movimientos.OBSERVACION_MOVIMIENTO,
		tbl_usuario.nombre_completo,STATUS_MOVIMIENTO
		FROM
		pv_tbc_encabezado_movimientos
		Inner Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
		Inner Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario
		";
	$sql.=" where TIPO_MOVIMIENTO='SAL' and  ID_MOVIMIENTO=".$_GET["id"];
$result = mysql_query($sql);

while ($rs=mysql_fetch_array($result)){
	$codigo=str_pad($rs["NUMERO_MOVIMIENTO"], 9-strlen($rs["NUMERO_MOVIMIENTO"]) , '0', STR_PAD_LEFT);
	$fecha=explode("-",$rs["FECHA_EMISION"]);
	
	$htm=$htm."			<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	$htm=$htm."  <tr  bgcolor='#FF9900'>";
	$htm=$htm."      ";
	$htm=$htm."      <td  colspan='4'  bgcolor='#FF9900' height='70' valign='top'><div align='left'><img src='images/logo.jpg' width='60' height='61' /></div></td>";
	$htm=$htm."    </tr> ";
	$htm=$htm."              <tr>";
	$htm=$htm."                <td colspan='4' align='center'  bgcolor='#F1F1F1' ><div align='center'><B>NOTA DE SALIDA</B></div></td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Nro:</td>";
	$htm=$htm."                <td width='25%' align='left'>".$codigo."</td>";
	$htm=$htm."                <td colspan='2'>Emisión:&nbsp;".$fecha[2]."/".$fecha[1]."/".$fecha[0]."</td>";
	$htm=$htm."             </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Almacen Origen:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["NOMBRE_ALMACEN"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Destino:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["ORIGEN"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Observaciones:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["OBSERVACION_MOVIMIENTO"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."            </table>";
	$status=$rs["STATUS_MOVIMIENTO"];
	$login=$rs["nombre_completo"];
	
	
					
}	
	
	$htm=$htm."<table width='720' border='1' cellspacing='0' cellpadding='0'>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='5' align='center'>Detalle de Salida</td>";
	$htm=$htm."</tr>";

	$htm=$htm."<tr>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1'  align='center'>Id</td>";
	$htm=$htm."	<td width='30%' bgcolor='#F1F1F1'  align='center'>Producto</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Cantidad Unitaria</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Unidad Agrupada</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Cantidad Agrupada</td>";
	$htm=$htm."</tr>";	
	
	$htm2="";
	$htm2=$htm2."<table width='720' border='1' cellspacing='0' cellpadding='0'>";
	$htm2=$htm2."<tr>";
	$htm2=$htm2."	<td bgcolor='#F1F1F1'  colspan='5' align='center'><b>Nota Nro: $codigo<b/></td>";
	$htm2=$htm2."</tr>";
	$htm2=$htm2."<tr>";
	$htm2=$htm2."	<td bgcolor='#F1F1F1'  colspan='5' align='center'>Detalle Nota de Salida</td>";
	$htm2=$htm2."</tr>";

	$htm2=$htm2."<tr>";
	$htm2=$htm2."	<td width='10%' bgcolor='#F1F1F1'  align='center'>Id</td>";
	$htm2=$htm2."	<td width='30%' bgcolor='#F1F1F1'  align='center'>Producto</td>";
	$htm2=$htm2."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Cantidad Unitaria</td>";
	$htm2=$htm2."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Unidad Agrupada</td>";
	$htm2=$htm2."	<td width='20%' bgcolor='#F1F1F1'  align='left'>Cantidad Agrupada</td>";
	$htm2=$htm2."</tr>";	  
	
	$sql="SELECT a.*,
			b.DESCRIPCION_UNIDAD_AGRUPADA
		FROM
		pv_tbl_detalle_movimientos AS a Inner Join pv_tbc_unidades AS b ON a.CODIGO_UNIDAD_AGRUPADA = b.CODIGO_UNIDAD_AGRUPADA
		";
	$sql.=" where ID_MOVIMIENTO=".$_GET["id"]." order by 2";
	$i=1;
	$total=0;
	$total2=0;
	$result = mysql_query($sql);
			while ($rs=mysql_fetch_array($result)){
				if($i<=40){
					$htm=$htm."<tr>";
					$htm=$htm."	<td width='10%'  align='CENTER'>".$i."</td>";
					$htm=$htm."	<td width='30%'  align='left'>".$rs["DESCRIPCION_UNITARIA"]."</td>";
					$htm=$htm."	<td width='20%'  align='left'>".number_format($rs["CANTIDAD_UNITARIA"],0)." </td>";
					$htm=$htm."	<td width='20%'  align='left'>$rs[DESCRIPCION_UNIDAD_AGRUPADA]</td>";
					$htm=$htm."	<td width='20%'  align='left'>".number_format(($rs["CANTIDAD_UNITARIA"]/$rs["CANTIDAD_AGRUPADA"]),2)."</td>";
					$htm=$htm."</tr>";	
				}
					else{
						$htm2=$htm2."<tr>";
						$htm2=$htm2."	<td width='10%'  align='CENTER'>".$i."</td>";
						$htm2=$htm2."	<td width='30%'  align='left'>".$rs["DESCRIPCION_UNITARIA"]."</td>";
						$htm2=$htm2."	<td width='20%'  align='left'>".number_format($rs["CANTIDAD_UNITARIA"],0)." </td>";
						$htm2=$htm2."	<td width='20%'  align='left'>$rs[DESCRIPCION_UNIDAD_AGRUPADA]</td>";
						$htm2=$htm2."	<td width='20%'  align='left'>".number_format(($rs["CANTIDAD_UNITARIA"]/$rs["CANTIDAD_AGRUPADA"]),2)." </td>";
						$htm2=$htm2."</tr>";	  
				}	  
				$i++;
				$total=$total+$rs["CANTIDAD_UNITARIA"];
				$total2=$total2+($rs["CANTIDAD_UNITARIA"]/$rs["CANTIDAD_AGRUPADA"]);
				
			} 
	if($i<=40){	
		$htm=$htm."<tr>";
		$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='2' align='right'>Total:</td>";
		$htm=$htm."	<td bgcolor='#F1F1F1'  align='left'>".number_format($total,2)."</td>";
		$htm=$htm."	<td bgcolor='#F1F1F1'  align='left'>&nbsp;</td>";
		$htm=$htm."	<td bgcolor='#F1F1F1'  align='left'>".number_format(($total2),2)."</td>";
		$htm=$htm."</tr>";
		$htm=$htm."<tr>";
		$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='5' align='right'><br><br><br></td>";
		$htm=$htm."</tr>";
		  
		$htm=$htm."      </table>";
	}
	else{
		$htm=$htm."      </table>";
		$htm2=$htm2."<tr>";
		$htm2=$htm2."	<td bgcolor='#F1F1F1'  colspan='2' align='right'>Total:</td>";
		$htm2=$htm2."	<td bgcolor='#F1F1F1'  align='left'>".number_format($total,2)."</td>";
		$htm2=$htm2."	<td bgcolor='#F1F1F1'  align='left'>&nbsp;</td>";
		$htm2=$htm2."	<td bgcolor='#F1F1F1'  align='left'>".number_format(($total2),2)."</td>";
		$htm2=$htm2."</tr>";
		$htm2=$htm2."<tr>";
		$htm2=$htm2."	<td bgcolor='#F1F1F1'  colspan='5' align='right'><br><br><br></td>";
		$htm2=$htm2."</tr>";
		  
		$htm2=$htm2."      </table>";
	}
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
	$pdf -> Output("SALIDA".$codigo.".pdf", "D");
?>