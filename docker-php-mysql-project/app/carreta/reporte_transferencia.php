<?php
include('main/valida.php');
include('main/conexiones.php');


$sql="SELECT
pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
pv_tbc_encabezado_movimientos.FECHA_EMISION,
a.NOMBRE_ALMACEN AS origen,
pv_tbc_encabezado_movimientos.OBSERVACION_MOVIMIENTO,
tbl_usuario.nombre_completo,
pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO,
b.NOMBRE_ALMACEN AS destino
FROM
pv_tbc_encabezado_movimientos
Inner Join pv_tbc_almacenes AS a ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = a.CODIGO_ALMACEN
Inner Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario
Inner Join pv_tbc_almacenes AS b ON pv_tbc_encabezado_movimientos.ALMACEN_DESTINO = b.CODIGO_ALMACEN
		";
	$sql.=" where TIPO_MOVIMIENTO='TRA' and  ID_MOVIMIENTO=".$_GET["id"];
$result = mysql_query($sql);

while ($rs=mysql_fetch_array($result)){
	$codigo=str_pad($rs["NUMERO_MOVIMIENTO"], 9-strlen($rs["NUMERO_MOVIMIENTO"]) , '0', STR_PAD_LEFT);
	$fecha=explode("-",$rs["FECHA_EMISION"]);
	
	$htm=$htm."			<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
	$htm=$htm."  	<tr  bgcolor='#FF9900'>";
	$htm=$htm."      ";
	$htm=$htm."      <td  colspan='4'  bgcolor='#FF9900' height='70' valign='top'><div align='left'><img src='images/logo.jpg' width='60' height='61' /></div></td>";
	$htm=$htm."    </tr> ";
	$htm=$htm."              <tr>";
	$htm=$htm."                <td colspan='4' align='center'  bgcolor='#F1F1F1' ><div align='center'><B>NOTA DE TRANSFERENCIA ENTRE ALAMCENES</B></div></td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Nro:</td>";
	$htm=$htm."                <td width='25%' align='left'>".$codigo."</td>";
	$htm=$htm."                <td colspan='2'>Emisión:&nbsp;".$fecha[2]."/".$fecha[1]."/".$fecha[0]."</td>";
	$htm=$htm."             </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Origen:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["origen"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Destino:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["destino"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."             <tr>   ";
	$htm=$htm."                <td  width='15%'>&nbsp;&nbsp;Observaciones:</td>";
	$htm=$htm."                <td colspan=3 width='85%' align='left'>     <b>".$rs["OBSERVACION_MOVIMIENTO"]."</b>&nbsp;&nbsp;</td>";
	$htm=$htm."              </tr>";
	$htm=$htm."            </table>";
	$status=$rs["STATUS_MOVIMIENTO"];
	$login=$rs["nombre_completo"];
	
	
					
}	
	
	$htm=$htm."<table width='720' border='1' cellspacing='0' cellpadding='0'  align='center'>";
	$htm=$htm."<tr>";
	$htm=$htm."	<td bgcolor='#F1F1F1'  colspan='4' align='center' >Detalle de NOTA DE TRANSFERENCIA ENTRE ALAMCENES</td>";
	$htm=$htm."</tr>";

	$htm=$htm."<tr>";
	$htm=$htm."	<td width='10%' bgcolor='#F1F1F1'  class='TITULO' align='center'>Id</td>";
	$htm=$htm."	<td width='30%' bgcolor='#F1F1F1'  class='TITULO'  align='center'>Producto</td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1'   class='TITULO' align='left'>Cantidad </td>";
	$htm=$htm."	<td width='20%' bgcolor='#F1F1F1'   class='TITULO' align='left'>UNIDAD </td>";
	$htm=$htm."</tr>";	
	
	  
	
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
					$htm=$htm."<tr>";
					$htm=$htm."	<td width='10%'  class='TITULO2'  align='CENTER'>".$i."</td>";
					$htm=$htm."	<td width='30%'  class='TITULO2'  align='left'>".$rs["DESCRIPCION_UNITARIA"]."</td>";
					$s=strpos($rs["CODIGO_BARRA_PRODUCTO"],"/");
					
					if ($s === false) 
						{
							$htm=$htm."	<td width='30%' class='TITULO2'   align='left'>".$rs["CANTIDAD_UNITARIA"]."</td>";
							$htm=$htm."	<td width='30%'  align='left'>UNIDADES</td>";
							
						}
					else
						{
						$desc=explode("/",$rs["CODIGO_BARRA_PRODUCTO"]);
						$htm=$htm."	<td width='30%'  class='TITULO2'  align='left'>".$desc[1]."</td>";
						$htm=$htm."	<td width='30%' class='TITULO2'   align='left'>".$desc[2]."</td>";
						}
					
					$htm=$htm."</tr>";	
				
				$i++;
				$total=$total+$rs["CANTIDAD_UNITARIA"];
				$total2=$total2+($rs["PRECIO_UNITARIO"]*$rs["CANTIDAD_UNITARIA"]);
				
			} 
		  
		$htm=$htm."      </table>";
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
	$pdf -> Output("TRANSFERENCIA".$codigo.".pdf", "D");
?>