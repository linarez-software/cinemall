<?php
session_start();
include_once "../include/bd.inc.php";
include "include/config.inc.php";
include "include/func.glb.php";

//Datos Empresa
$qry = "SELECT nombre_empresa, direccion, rif, nit ";
$qry .= "FROM tbl_configuracion ";
$result_empresa = mysql_query($qry);
if($campo_empresa = mysql_fetch_object($result_empresa)):
	$empresa_nombre = $campo_empresa->nombre_empresa;
	$empresa_direccion = $campo_empresa->direccion;
	$empresa_rif = $campo_empresa->rif;
	$empresa_nit = $campo_empresa->nit;
endif;

//Datos Cine
$qry = "SELECT nombre ";
$qry .= "FROM tbl_cine ";
$qry .= "WHERE id_cine = " . CINE_TAQUILLA;
$result_cine = mysql_query($qry);
if($campo_cine = mysql_fetch_object($result_cine)):
	$cine_nombre = $campo_cine->nombre;
endif;

?>
<head>
<SCRIPT Language="Javascript">
function Imprimir()
{
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, 2); WebBrowser1.outerHTML = ""; window.close();
}


</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ticket</title>
<style type="text/css">
.precio {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #000000;
	border: 0px #000000 solid;
}
.precio-nino {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	color: #000000;
	border: 1px #000000 solid;
}
.pelicula {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 15px;
	font-style:italic;
	color: #000000;
}
.impuesto {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: normal;
	color: #000000;
}
.empresa {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: italic;
	color: #000000;
	font-weight: normal;
}
.empresa1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 7px;
	font-style: italic;
	color: #000000;
}
.empresa11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: italic;
	color: #000000;
}
.sala {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-style: normal;
	color: #000000;
}
.sala2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 48px;
	font-weight:bold;
	font-style: normal;
	color: #000000;
}
.sala22 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	font-weight:bold;
	font-style: normal;
	color: #000000;
}

.fecha {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
	font-style: normal;
	color: #000000;
	font-weight:bold;
}
.fecha2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-style: normal;
	color: #000000;
	font-weight:bold;
}
-->
</style>
</head>
<!---->
<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" onLoad="Imprimir()" onUnload="window.opener.location='index.php'">
<?php

$fecha_operacion = date("Y-m-d H:i:s");

	$qry = "SELECT t.id_operacion, t.id_programacion, t.fecha_operacion, t.id_tipo_transaccion, ";
$qry .= "t.cantidad_transaccion, t.id_precio, t.costo, t.recargo, t.total_transaccion, t.usuario_registro, t.porcentaje_distribuidor, ";
$qry .= "p.nombre as precio, p.comentario, p.marcar, p.marca ";
$qry .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
$qry .= "WHERE id_session = '" . $_SESSION['id'] ."'";
//echo $qry;
$temp_venta = mysql_query($qry);

while($campo_venta = mysql_fetch_object($temp_venta)):

$qry_id = "SELECT MAX(id_operacion) as id_operacion FROM tbl_operacion ";
$qry_id .= "WHERE id_cine = ".CINE_TAQUILLA;
$result_id = mysql_query($qry_id);
if($campo_id = mysql_fetch_object($result_id)):
	$id_operacion = $campo_id->id_operacion + 1;
else:
	$id_operacion = 1;
endif;
$hora_operacion = date("H:i:s");
$qry_insert = "INSERT INTO tbl_operacion (id_operacion, id_cine, id_programacion, fecha_operacion, id_tipo_transaccion, cantidad_transaccion, id_precio, ";
$qry_insert .= "costo, recargo, total_transaccion, usuario_registro, status, porcentaje_distribuidor, hora_operacion) VALUES ( ";
$qry_insert .= $id_operacion.",".CINE_TAQUILLA.", ";
$qry_insert .= "$campo_venta->id_programacion, '$fecha_operacion', $campo_venta->id_tipo_transaccion, $campo_venta->cantidad_transaccion, $campo_venta->id_precio, ";
$qry_insert .= "$campo_venta->costo, $campo_venta->recargo, $campo_venta->total_transaccion, $campo_venta->usuario_registro, 1, $campo_venta->porcentaje_distribuidor, '$hora_operacion') ";
//echo $qry_insert;
$result_insert = mysql_query($qry_insert) or die(mysql_error());

$nro_boleto = CINE_TAQUILLA . str_pad($id_operacion, 5,"0", STR_PAD_LEFT);
$hora_impresion = date("h:i a");
$fecha_impresion = date("d/m/Y");

$qry_datos = "SELECT f.id_sala, f.id_cine, f.id_pelicula, f.hora_inicio, f.fecha_programacion as fecha, ";
$qry_datos .= "p.nombre_espanol as pelicula, s.nombre as sala ,p.id_tipo ";
$qry_datos .= "FROM tbl_programacion f INNER JOIN tbl_pelicula p ";
$qry_datos .= "ON f.id_pelicula = p.id_pelicula ";
$qry_datos .= "INNER JOIN tbl_sala s ";
$qry_datos .= "ON f.id_sala = s.id_sala ";
$qry_datos .= "WHERE f.id_programacion = $campo_venta->id_programacion ";
//echo $qry_datos;
$result_datos = mysql_query($qry_datos) or die(mysql_error());
if($campo_datos = mysql_fetch_object($result_datos)):

for($i=1; $i<=$campo_venta->cantidad_transaccion; $i++):
?>
<table width="250" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="250" valign="top" align="center"><img src="images/logo_cinesacarigua.gif" border="0" height="60" width="60	"></td>
    </tr>
    <tr>
		<td width="250" align="center">
            <span class="empresa"><?=$empresa_nombre;?></span><br>
            <span class="empresa1"><?=$empresa_direccion;?></span><br>
            <span class="empresa1">Rif. <?=$empresa_rif;?></span><br>				
        </td>
    </tr>
    
    <tr height="30" valign="bottom">
		<td width="250"><table width="100%" cellpadding="0" cellspacing="0" class="sala"><tr>
            <td align="right"><span class="fecha"><?php echo date("d/m/Y",strtotime($campo_datos->fecha));?></span></td>
            <td align="right"><span class="fecha"><?php echo formato_hora($campo_datos->hora_inicio);?></span></td>
        </tr></table></td>
    </tr>
	<tr>
		<td width="250" class="pelicula" height="35" align="left" valign="middle">
			<b><?=$campo_datos->pelicula;?></b>
        </td>
    </tr>
    <tr>
    	<td width="250"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
            <td height="19">
            	<?php 
				$class_precio = "precio";
				$p = "";
				if($campo_venta->marcar==1):
					$class_precio = "precio-nino";
					//$p = "N";
					$p = empty($campo_venta->marca) ? "N" : strtoupper($campo_venta->marca);
				endif;

				$dd = ($campo_datos->id_tipo)==1 ? "" : "3D";
			
				
				?>
            	<span class="<?php echo $class_precio;?>"><b><?=$campo_venta->precio;?></b>  Bsf. <?=$campo_venta->costo;?></span>&nbsp;<br>
                <!--<span class="impuesto">Imp. Espec. Pub. Incluido</span><br>-->
                <span class="impuesto">EXENTO DE IVA</span><br>
                <span class="empresa11"><?=$hora_impresion;?> <?=$fecha_impresion;?><br>
                 <?=$_SESSION['username'];?>/<?=$nro_boleto;?>&nbsp;<?php echo $i."/".$campo_venta->cantidad_transaccion;?></span><br>
            </td>
            <td width="76" valign="middle" align="center">
                <span class="sala2"><?php echo $dd.$p;?>S<?php echo $campo_datos->sala;?></span>
            </td>
        </tr></table></td>
    </tr>
    <tr>
    	<td width="250" height="15" valign="middle" align="center">
        	<span class="empresa11">- CLIENTE -</span><br>
            - - - - - - - - - - - - - - - - - - - - - - - -
        </td>
    </tr>
           
	<tr>
    	<td width="250" valign="top" align="center"><img src="images/tr.gif" height="10" border="0"></td>
    </tr>
    <tr>
		<td width="250" align="center">
            <span class="empresa"><?=$empresa_nombre;?></span><br>
            <span class="empresa1"><?=$empresa_direccion;?></span><br>
            <span class="empresa1">Rif. <?=$empresa_rif;?></span><br>				
        </td>
    </tr>
    <tr>
		<td width="76" valign="middle" align="center" class="sala22">
                <?php echo $dd.$p;?>S<?php echo $campo_datos->sala;?>
            </td>
    </tr>
	<tr>
		<td width="250"><table width="100%" cellpadding="0" cellspacing="0" class="sala"><tr>
           <td align="right"><span class="fecha"><?php echo date("d/m/Y",strtotime($campo_datos->fecha));?></span></td>
            <td align="right"><span class="fecha"><?php echo formato_hora($campo_datos->hora_inicio);?></span></td>
        </tr></table></td>
    </tr>
	<tr>
		<td width="250" class="pelicula" height="35" align="left" valign="middle">
			<b><?=$campo_datos->pelicula;?></b>
        </td>
    </tr>
    <tr>
		<td width="250">
            <span class="precio"><?=$campo_venta->comentario;?>  Bsf. <?=$campo_venta->costo;?></span><br>
            <!--<span class="impuesto">Imp. Espec. Pub. Incluido</span><br>-->
			<span class="impuesto">EXENTO DE IVA</span><br>
            <span class="empresa11"><?=$hora_impresion;?> <?=$fecha_impresion;?>  <?=$_SESSION['username'];?>/<?=$nro_boleto;?>&nbsp;<?php echo $i."/".$campo_venta->cantidad_transaccion;?></span><br>
            <center><span class="empresa11">- COPIA -</span></center>
        </td>
    </tr>
    <tr>
    	<td width="250" height="15" valign="middle" align="center">- - - - - - - - - - - - - - - - - - - - - - - -</td>
    </tr>
</table>
<?php
endfor;
endif;
endwhile;

$qry_delete = "DELETE FROM temp_operacion WHERE id_session = '" . $_SESSION['id'] ."'";
$result_delete = mysql_query($qry_delete) or die(mysql_error());

?>

</body>
</html>
