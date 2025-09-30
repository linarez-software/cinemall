<html>
<head>

<style>
a:hover{color:#FF6600;}
a:link{text-decoration: none;}
a:active { text-decoration: none; }
a:visited { text-decoration: none; }
</style>

<?php 
include_once "../../include/bd.inc.php";

$boletosViejos = $_POST['boletosViejos'];
$boletos = $_POST['boletos'];
$costo = $_POST['costo'];
$id_operacion = $_POST['operacion'];
$hora_inicio = $_POST['funcion'];

//para obtener el valor de la opcion sea suma o resta
for($i=0;$i<count($boletosViejos);$i++){
	if($boletos[$i]>$boletosViejos[$i]){
		$opcion[$i]="+";
		$limite[$i]=$boletos[$i]-$boletosViejos[$i];
		}
	else{
		$opcion[$i]="-";
		$limite[$i]=$boletosViejos[$i]-$boletos[$i];
		}
}

for($i=0;$i<count($operacion);$i++){
$str="UPDATE tbl_operacion SET cantidad_transaccion=cantidad_transaccion".$opcion[$i]."'1'  
where id_operacion='$id_operacion[$i]'
and hora_operacion='$hora_inicio[$i]' limit $limite[$i]";

//echo $str."<br>";

$rs=mysql_query($str,$cnn);

}
//para arreglar el total en bolivares por transacciones
$str2="UPDATE tbl_operacion SET total_transaccion=cantidad_transaccion* costo";
$rs2=mysql_query($str2,$cnn);

//para eliminar si hace falta operaciones que queden sin transacciones
$str3="DELETE FROM tbl_operacion WHERE cantidad_transaccion=0";
$rs3=mysql_query($str3,$cnn);

if(!$rs){
		echo("<center><table><tr>
					<td height='21' class='mensaje-consulta'>".die("Error en la Actualizacion".mysql_error())."</td>
				</tr>
				<tr>
					<td align=center><input type=button value=Volver onClick=history.back(-1)></td>					<tr>
			</table>
			</center>");
			;
		}else
			$indicadora=1;	
if($indicadora==1)
	echo("<center><table><tr>
							<td height='21' class='mensaje-consulta'>Registros Actualizados Satisfactoriamente...!</td>
						</tr>
						<tr>
							<td align=center><input type=button value=Volver onClick=history.back(-1)></td>
						<tr>
					</table>
		</center>");

?>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
</body>
</html>
