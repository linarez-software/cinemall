<?php
session_start();
include_once "../include/bd.inc.php";
include "include/config.inc.php";
include "include/func.glb.php";

setlocale(LC_TIME, "es_ES");
//Datos Empresa
$municipal = 10;
$foncine =5;
$qry = "SELECT nombre_empresa, direccion, rif, nit,municipal,fomprocine ";
$qry .= "FROM tbl_configuracion ";
$result_empresa = mysql_query($qry);
if($campo_empresa = mysql_fetch_object($result_empresa)):
	$empresa_nombre = $campo_empresa->nombre_empresa;
	$empresa_direccion = $campo_empresa->direccion;
	$empresa_rif = $campo_empresa->rif;
	$empresa_nit = $campo_empresa->nit;
	$municipal = $campo_empresa->municipal;
	$foncine = $campo_empresa->fomprocine;
endif;

//Datos Cine
$cedula=$_GET["cedula"]=='' ? 0 :$_GET["cedula"];
$nombre=$_GET["nombre"]=='' ? 'CLIENTE' : strtoupper($_GET["nombre"]);

$qry = "SELECT nombre ";
$qry .= "FROM tbl_cine ";
$qry .= "WHERE id_cine = " . CINE_TAQUILLA;
$result_cine = mysql_query($qry);
if($campo_cine = mysql_fetch_object($result_cine)):
	$cine_nombre = $campo_cine->nombre;
endif;
$ip=getRealIP();
$sql="select * from auxiiar where caja='$ip'";
$rest = mysql_query($sql); 
$x=0;
$cant_impresora=1;
while($campo = mysql_fetch_array($rest)):
	$x=1;
	$cant_impresora=$campo[0];
endwhile;
if($x==0){
	$sql="insert into auxiiar values(0,'$ip')";
	$rest = mysql_query($sql); 
}

if(!isset($_SESSION[$ip."contador_factura"]))
{
	$_SESSION[$ip."contador_factura"]=1;
}
else
{
	$_SESSION[$ip."contador_factura"]=$_SESSION[$ip."contador_factura"]+1;
}
 
?>
<head>
<SCRIPT Language="Javascript">
function Imprimir()
{
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, 2); WebBrowser1.outerHTML = ""; 
window.close();
}
function openCalc()
{	
       var shell = new ActiveXObject("Wscript.shell");
       //shell.run("c:\\Windows\\System32\\calc.exe");
       shell.run("C:\\factura\\cajaTaquilla.exe");

}


</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ticket</title>
<style type="text/css">
.precio {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;
	border: 1px #000000 solid;
}
.precio-nino {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #000000;
	border: 1px #000000 solid;
}
.pelicula {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 13px;
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
	font-size: 11px;
	font-style: italic;
	color: #000000;
	font-weight: normal;
}
.empresa1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: italic;
	color: #000000;
}
.empresa12 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 8px;
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
	font-size: 42px;
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
	font-size: 15px;
	font-style: normal;
	color: #000000;
	font-weight:bold;
}
.fecha3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-style: normal;
	color: #000000;
	font-weight:bold;
	border-color: #000000;

}

-->
</style>
</head>
<!---->
<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" onUnload="window.opener.location='index.php'">
<?php

$fecha_operacion = date("Y-m-d H:i:s");
///impresora fiscal
    function getRealIP() {
        
        $a= $_SERVER['REMOTE_ADDR'];
        return $a;
    }


$qry = "SELECT t.id_operacion, t.id_programacion, t.fecha_operacion, t.id_tipo_transaccion, ";
$qry .= "t.cantidad_transaccion, t.id_precio, t.costo, t.comision, t.total_transaccion, t.usuario_registro, t.porcentaje_distribuidor, ";
$qry .= "p.nombre, p.comentario, butacas, numerada, p.contenido, p.marcar, p.marca,p.foncine ";
$qry .= "FROM temp_operacion t INNER JOIN tbl_precio p ON t.id_precio = p.id_precio ";
$qry .= "WHERE id_session = '" . $_SESSION['id'] ."'";
//echo $qry;
$temp_venta = mysql_query($qry);
////IMPRESORA FISCAL
$sql="update  tbl_encabezado set impresa=1 where ip='".getRealIP()."'";
$rest = mysql_query($sql);

$cedula=$_GET["cedula"]=='' ? 0 :$_GET["cedula"];
$nombre=$_GET["nombre"]=='' ? 'CLIENTE' : strtoupper($_GET["nombre"]);
$sql="insert into tbl_encabezado values(NULL,now(),0,'$nombre','$cedula','EMITIDA','0','".getRealIP()."')";
$rest = mysql_query($sql); 
$id_movimiento = mysql_insert_id();
$monto=0;
///////////////
$cant_imp =0;
$precio_imp =0;
$total_imp_f =0;

while($campo_venta = mysql_fetch_object($temp_venta)):

	$municipal = $campo_venta->foncine;
	$prec = $campo_venta->id_precio;
	
	// Pregunto si es una sala númerada
	$incompletas = 0;
	if($campo_venta->numerada == 1 ):
		$butacas_seleccionadas = explode(",", $campo_venta->butacas);
		// Verifico si seleccionaron todas las butacas de acuerdo a la cantidad de entradas
		// en caso contrario no se imprimira el ticket
		if($campo_venta->cantidad_transaccion > count($butacas_seleccionadas) || empty($campo_venta->butacas)):
			$incompletas = 1;
		endif;
	endif;
	
	if($incompletas==0):
		$cant_imp = $campo_venta->cantidad_transaccion;
		$precio_imp =($campo_venta->costo);
		$total_imp_f +=($campo_venta->cantidad_transaccion*$campo_venta->costo);
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
		$qry_insert .= "costo, comision, total_transaccion, usuario_registro, status, porcentaje_distribuidor, hora_operacion,municipal,iva,foncine) VALUES ( ";
		$qry_insert .= $id_operacion.",".CINE_TAQUILLA.", ";
		$qry_insert .= "$campo_venta->id_programacion, '$fecha_operacion', $campo_venta->id_tipo_transaccion, $campo_venta->cantidad_transaccion, $campo_venta->id_precio, ";
		$qry_insert .= "$campo_venta->costo, $campo_venta->comision, $campo_venta->total_transaccion, $campo_venta->usuario_registro, 1, $campo_venta->porcentaje_distribuidor, '$hora_operacion','$municipal',10,'$foncine') ";
		//echo $qry_insert;
		$result_insert = mysql_query($qry_insert) or die(mysql_error());
		
		$municipal = 2;
		$qry_id2 = "SELECT p.foncine  FROM tbl_operacion t inner join tbl_precio p ON t.id_precio = p.id_precio WHERE id_operacion=$id_operacion ";
		$result_id2 = mysql_query($qry_id2);
		if($campo_id2 = mysql_fetch_object($result_id2)):
			$municipal = $campo_id->foncine;
		endif;
		
		$qry_insert = "update  tbl_operacion set municipal='$municipal' where  id_operacion=$id_operacion ";
		$result_id2 = mysql_query($qry_id2);

		$sql="insert into tbl_encabezado_detalle values(NULL,$id_movimiento,'ENTRADAS $cant_imp','$precio_imp','10','$cant_imp','$campo_venta->foncine')";
		$rest = mysql_query($sql); 
	
		///Busco todas las butacas de la operación y programación seleccionada
		$qry_butacas = "SELECT id_operacion, butaca, id_programacion FROM temp_operacion_butaca ";
		$qry_butacas .= "WHERE id_operacion = ".$campo_venta->id_operacion;
		$result_butaca = mysql_query($qry_butacas);
		while($campo_butacas = mysql_fetch_object($result_butaca)):
			$qry_insert_b = "INSERT INTO tbl_operacion_butaca (id_operacion, butaca, id_programacion) VALUES ( ";
			$qry_insert_b .= $id_operacion.", '";
			$qry_insert_b .= $campo_butacas->butaca."',".$campo_butacas->id_programacion.") ";
			//echo $qry_insert;
			$result_insert_b = mysql_query($qry_insert_b) or die(mysql_error());
		endwhile;
		
		$nro_boleto = CINE_TAQUILLA . str_pad($id_operacion, 5,"0", STR_PAD_LEFT);
		$hora_impresion = date("h:i a");
		$fecha_impresion = date("d/m/Y");
		
		$qry_datos = "SELECT f.id_sala, f.id_cine, f.id_pelicula, f.hora_inicio, f.fecha_programacion as fecha, ";
		$qry_datos .= "p.nombre_espanol as pelicula,p.nombre_corto as corto, s.nombre as sala ,p.id_tipo ";
		$qry_datos .= "FROM tbl_programacion f INNER JOIN tbl_pelicula p ";
		$qry_datos .= "ON f.id_pelicula = p.id_pelicula ";
		$qry_datos .= "INNER JOIN tbl_sala s ";
		$qry_datos .= "ON f.id_sala = s.id_sala ";
		$qry_datos .= "WHERE f.id_programacion = $campo_venta->id_programacion ";
		//echo $qry_datos;
		$result_datos = mysql_query($qry_datos) or die(mysql_error());
		if($campo_datos = mysql_fetch_object($result_datos)):
		
			for($i=1; $i<=$campo_venta->cantidad_transaccion; $i++):
			$numerada = $campo_venta->numerada;
			$logo_encabezado = "";
			if($numerada==1):
				$logo_encabezado = "<img src='images/logo-cinemall-taquilla.gif' border='0' height='50' width='50' align='left'>";
			endif;
			$dia=date("l");
			$dia=date("l");

			if ($dia=="Monday") $dia="Lunes";
			if ($dia=="Tuesday") $dia="Martes";
			if ($dia=="Wednesday") $dia="Mi�r.";
			if ($dia=="Thursday") $dia="Jueves";
			if ($dia=="Friday") $dia="Viernes";
			if ($dia=="Saturday") $dia="Sabado";
			if ($dia=="Sunday") $dia="Domingo";
			?>
			<table width="250" border="0" cellpadding="0" cellspacing="0">
				<?php
				if($numerada>=0):
				?>
				<tr>
			    	<td width="250" valign="top" align="center"><img src="images/logo-cinemall-taquilla.gif" border="0" height="25" width="25"></td>
			    </tr>
			    <?php
				endif;
				?>
			    <tr>
					<td width="250" align="center">
						<p>
						<?php //echo $logo_encabezado; ?>
			            <span class="empresa"><?php echo $empresa_nombre;?></span><br>
			            <span class="empresa12"><?php echo $empresa_direccion;?></span><br>
			            <span class="empresa12">Rif. <?php echo $empresa_rif;?></span></p>			
			        </td>
			    </tr>
			        <tr height="30" valign="middle" bgcolor="white">
							<td width="250"><table width="100%" border=1 cellpadding="0" cellspacing="0" class="sala" align="center"><tr>
					            <td align="center"><span class="fecha3"><?php echo $dia.",".date("d/m/Y",strtotime($campo_datos->fecha));?></span></td>
					             <td align="center"><span class="fecha3">&nbsp;&nbsp;<?php echo formato_hora($campo_datos->hora_inicio);?>&nbsp;&nbsp;</span></td>
					        </tr></table></td>
					    </tr>

				<tr>
					<td width="250" class="pelicula" height="35" align="left" valign="middle">
						<b><?php echo $campo_datos->corto;?></b>
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
							$p = empty($campo_venta->marca) ? "" : strtoupper($campo_venta->marca);
						endif;
						$dd = ($campo_datos->id_tipo)==2 ? "3D" : "";
						$dd1 = ($campo_datos->id_tipo)==3 ? "V" : "";
						?>
		            	<span class="<?php echo $class_precio;?>"><b><?php echo $campo_venta->precio;?></b>  Bsf. <?php echo $campo_venta->costo+$campo_venta->comision;?></span>&nbsp;<br>
		                <!--<span class="impuesto">Imp. Espec. Pub. Incluido</span><br>-->
			<span class="impuesto">.</span><br>
		                <span class="empresa1"><?php echo $hora_impresion;?> <?php echo $fecha_impresion;?><br>
		                 <?php echo $_SESSION['username'];?>/<?php echo $nro_boleto;?>&nbsp;<?php echo $i."/".$campo_venta->cantidad_transaccion;?></span><br>
		            </td>
		            <?php
		            	$butaca = "";
						$prefijo = "";
		            	if($numerada == 1):
							$butaca = "-".$butacas_seleccionadas[$i-1];
							$prefijo = "";
						endif;
		           	?>
		            <td width="160" valign="middle" align="right" >
		                <span class="sala2"><?php echo $dd.$dd1.$p.$prefijo;?>S<?php echo $campo_datos->sala;?><?php echo $butaca;?></span>
		            </td>
			        </tr></table></td>
			    </tr>
			    <?php
				if($numerada==1):
				?>
				<tr>
			    	<td height="45 " width="250" valign="middle" align="center" style="border-top: 1px dashed #000;">
						<span style="font-size: 12px; font-family: Verdana;"><i><?php echo $campo_venta->contenido;?></i></span>
					</td>
			    </tr>
			    <?php
				endif;
				?>
				<?php
				if($numerada!=1):
				?>
				<tr>
			    	<td height="45 " width="250" valign="middle" align="center" style="border-top: 1px dashed #000;">
						<span style="font-size: 12px; font-family: Verdana;"><i></i></span>
					</td>
			    </tr>
			    <?php
				endif;
				?>
			    <tr>
			    	<td width="250" height="15" valign="middle" align="center">
			        	<span class="empresa1">- CLIENTE -</span><br>
			            - - - - - - - - - - - - - - - - - - - - - - - -
			        </td>
			    </tr>
			           
				<tr>
			    	<td width="250" valign="top" align="center"><img src="images/tr.gif" height="10" border="0"></td>
			    </tr>
			    <tr>
					<td width="250" align="center">
			            <span class="empresa"><?php echo $empresa_nombre;?></span><br>
			            <span class="empresa1"><?php echo $empresa_direccion;?></span><br>
			            <span class="empresa1">Rif. <?php echo $empresa_rif;?></span><br>				
			        </td>
			    </tr>
			    <tr>
                    <td width="76" valign="middle" align="center" class="sala22">
                            <?php echo $dd.$dd1.$p;?>S<?php echo $campo_datos->sala;?>
                        </td>
                </tr>
                <tr>
                    <td width="250"><table width="100%" cellpadding="0" cellspacing="0" class="sala"><tr>
                       <td align="center"><span class="fecha2"><?php echo $dia.",".date("d/m/Y",strtotime($campo_datos->fecha));?></span></td>
                        <td align="center"><span class="fecha2"><?php echo formato_hora($campo_datos->hora_inicio);?></span></td>
                    </tr></table></td>
                </tr>
                <tr>
					<td width="250" class="pelicula" height="35" align="left" valign="middle">
						<b><?php echo $campo_datos->corto;?></b>
			        </td>
			    </tr>
			    <tr>
					<td width="250">
			            <span class="precio"><?php echo $campo_venta->comentario;?>  Bsf. <?php echo $campo_venta->costo + $campo_venta->comision;?></span><br>
			            <!--<span class="impuesto">Imp. Espec. Pub. Incluido</span><br>-->
						<span class="impuesto">.</span><br>
			            <span class="empresa1"><?php echo $hora_impresion;?> <?php echo $fecha_impresion;?>  <?php echo $_SESSION['username'];?>/<?php echo $nro_boleto;?>&nbsp;<?php echo $i."/".$campo_venta->cantidad_transaccion;?></span><br>
			            <center><span class="empresa1">- COPIA -</span></center>
			        </td>
			    </tr>
			    <tr>
			    	<td width="250" height="15" valign="middle" align="center">- - - - - - - - - - - - - - - - - - - - - - - -</td>
			    </tr>
			</table>
			<?php
			endfor;
		endif;
		
		$qry_delete = "DELETE FROM temp_operacion WHERE id_operacion = " . $campo_venta->id_operacion;
		$result_delete = mysql_query($qry_delete) or die(mysql_error());
		
		$qry_delete = "DELETE FROM temp_operacion_butaca WHERE id_operacion = " . $campo_venta->id_operacion;
		$result_delete = mysql_query($qry_delete) or die(mysql_error());
		
	endif;
endwhile;
if(($cant_impresora % 3)==0)
{
	////IMPRESORA FISCAL
	/*$sql="insert into tbl_encabezado_detalle values(NULL,$id_movimiento,'ENTRADAS $cant_imp','$precio_imp','10','1','2')";
	$rest = mysql_query($sql); */
	//$sql="select * from tbl_operacion where id_cine=".CINE_TAQUILLA." and $id_operacion=$id_operacion";

	$sql="update  tbl_encabezado set monto='$total_imp_f' where id=$id_movimiento";
	$rest = mysql_query($sql);
	echo"<script>openCalc();</script>";
	

}
else
{
	////IMPRESORA FISCAL
	/*$sql="insert into tbl_encabezado_detalle values(NULL,$id_movimiento,'ENTRADAS $cant_imp','$precio_imp','10','1','2')";
	$rest = mysql_query($sql); */
	$sql="update  tbl_encabezado set monto='$total_imp_f',impresa=1 where id=$id_movimiento";
	$rest = mysql_query($sql);

	}

$sql="update  auxiiar set cantidad=cantidad +1 where caja='$ip'";
$rest = mysql_query($sql); 
	
$qry_delete = "DELETE FROM temp_operacion WHERE id_session = '" . $_SESSION['id'] ."'";
//$result_delete = mysql_query($qry_delete) or die(mysql_error());

?>

</body>
</html>
