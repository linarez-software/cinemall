<?php

$consulta = 0;
$qry_precios = "SELECT id_precio, nombre, comentario, costo, hora_limite ";
$qry_precios .= "FROM tbl_precio ";
$qry_precios .= "WHERE activo = 1  AND costo > 1 AND SUBSTRING(dia, $dia_semana+1, 1) = 1 AND (LENGTH(contenido) = 0 OR contenido IS NULL) ";
$precios_result = mysql_query($qry_precios);
$cantidad = mysql_num_rows($precios_result);
//$cantidad = floatval($cantidad) / 3;

?>
<table id="tabla-pies" width="100%" cellpadding="0" cellspacing="0"  border="0">
<tr>
	<td height="36" colspan="<?php echo $cantidad;?>" valign="middle" class="precio">Precios:</td>
</tr>
<?php
$table = "";
$bgcolor = "'#999999'";
$bgcolorselect = "'#C8C4FF'";
$columna = 1;
$table .= "<tr>";
//$table .= "<tr><td width='10px' rowspan='2'><img src='images/tr.gif'></td>";
while($campo_precios = mysql_fetch_object($precios_result)):
	$hora_limite = strtotime($campo_precios->hora_limite);
	if($hora_limite >= time() or $campo_precios->hora_limite == "00:00:00"):
		if($columna==1 or $columna==3 or $columna==5 or $columna==7 or $columna==9):
			$table .= "<td><table>";
		endif;
		$table .= "<tr><td valign='middle' align='center' class='precio-detalle'><b>" . strtolower($campo_precios->nombre) .":&nbsp;</b>";
		$table .= number_format($campo_precios->costo,2,",",".")."&nbsp;Bsf.</td></tr>";
		//$table .= "<td width='10px' align='center' class='precio-detalle'>|</td>";
		if($columna==2 or $columna==4 or $columna==6 or $columna==8 or $columna==10):
			$table .= "</table></td>";
		endif;
		$columna++;
	endif;
	//else:
//		$table .= "<td valign='middle' bgcolor='#98ACC5'>&nbsp;&nbsp;</td>";
//		$table .= "<td width='10px' bgcolor='#152227'><img src='images/tr.gif'></td>";
endwhile;
$table .= "<td width='30'>&nbsp;</td>";
$table .= "</tr> \n";
echo $table;
?>
</table>
