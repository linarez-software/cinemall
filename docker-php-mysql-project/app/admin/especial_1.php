<html>
<head>
<title>Reporte Especial</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css" />
<?php 
include_once "../include/bd.inc.php";
include "include/func.glb.php";


$fecha_actual = date("d/m/Y");
$fecha_programacion = isset($_POST['fecha_programacion']) ? $_POST['fecha_programacion'] : $fecha_actual;
$pelicula = isset($_POST['pelicula']) ? $_POST['pelicula'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$hora_i = isset($_POST['hora_i']) ? $_POST['hora_i'] : '';
$minu_i = isset($_POST['minu_i']) ? $_POST['minu_i'] : '';

?>
	<?php

	$fecha=formato_fecha($fecha_programacion);
	$hora_inicial = formato_hora($hora_i.':'.$minu_i.':00 pm', 'H:i');

		$consulta="select sum(cantidad_transaccion) as Cantidad,
		sum(total_transaccion) as Total
		from tbl_operacion inner join tbl_programacion 
		on tbl_operacion.id_programacion=tbl_programacion.id_programacion
		and fecha_operacion='$fecha' and 
		tbl_programacion.id_pelicula='$pelicula' and hora_inicio = '$hora_inicial'";
		//echo $consulta;
		
		$ejecucion=mysql_query($consulta,$cnn);
		if(!$rs=mysql_fetch_array($ejecucion)){
			echo "No se vendieron boletos...";
		}else{
			$cantidad=$rs['Cantidad'];
			$total=$rs['Total'];
		}
		?>
		
		<script language="JavaScript">
		function verificar(me){
		var indicador=0;
			if(me.valor.value==""){
			alert("Debe Introducir un Valor Numerico");
			me.valor.focus();
			indicador=1;
			me.action="especial_1.php";
			}
			if(indicador==0)
				me.action="especial2.php";
		}
		</script>
</head>

<body>

<?php include "head.php";?>

		<?php
		echo "<center><form name=form1 method=post action=especial2.php><table borde=0>
			<tr>
				<td align=right class=css-menu>Boletos Vendidos en el Dia: </td>
				<td align=left><input type=text name=transacciones value='$cantidad' size=10 maxlength=2 ></td>
			</tr>
			<tr>
				<td align=right class=css-menu>Total en Bolívares: </td>
				<td align=left><input type=text name=total value='$total' size=10 maxlength=2 ></td>
			</tr>
			<tr>
				<td class=css-menu align=right>Sumar<input type=radio name=modo value=1 checked></td>
				<td class=css-menu align=left>Restar<input type=radio name=modo value=2></td>
			</tr>
			<tr>
				<td align=right><input type=text name=valor size=4 maxlength=2></td>
				<td><input type=submit value=Modificar class=css-boton-metadata onClick=verificar(document.forms.form1);></td>
			</tr>
			
			
			
		<input type=hidden name=status value=1>
		<input type=hidden name=fecha value='$fecha'>
		<input type=hidden name=pelicula value='$pelicula'>
		<input type=hidden name=hora value='$hora_inicial'>
		
			</table></form></center>	";		
	echo "</td>";
	
	

	
	?>
</tr>
</table>

</body>
</html>
