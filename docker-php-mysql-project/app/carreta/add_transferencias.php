<?php
session_start();
include_once "../include/bd.inc.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<script type="text/javascript" src="js/func.global.js"></script>
<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="main/calendar.js"></script>
<script type="text/javascript" src="main/calendar-setup.js"></script>
<script type="text/javascript" src="main/lang/calendar-es.js"></script>
<script language="javascript">


function Validart(e)
{
 tecla=(document.all) ? e.keyCode : e.which; 
 tecla2=(e.keyCode);
    if ((tecla>=48 && tecla<=57) || (tecla==8) || (tecla2==9) || (tecla==46)  ){  
       return true; 
    	} 
	else
		{ 
       return false; 
    	} 
}

function numerico(valor){ 
	cad = valor.toString(); 
	for (var i=0; i<cad.length; i++) { 
		var caracter = cad.charAt(i); 
		if ((caracter<"0" || caracter>"9" ) &&(caracter!="."))
			return false; 
		} 
	return true; 
	}

function cancelar()
{
   document.form1.action="add_transferencias.php?cancela=true";
   document.form1.submit();
}
function valida()
{
	if ((document.form1.fecha.value==""))
	 {
	 	alert("Debe indicar la fecha");
		return false;
	 }
	 if ((document.form1.deposito.value==""))
	 {
	 	alert("Debe indicar el deposito de la salida");
		return false;
	 }
	document.form1.action="add_transferencias.php?insert=true";
	 document.form1.submit();
}

</script>
<html>
<head>
<title>Agregar transferencias</title>
</head>
<body>
<div align="center">
<form method="post" name="form1" >
<?php	
			if ($_GET["insert"]==true && isset($_GET["insert"]))
			{
				$c=0;
				$sql="SELECT *
						FROM
						aux_transferencias_detalle2
						";
					$sql.=" where id_usuario=$_SESSION[login_usu] 
						";	
				$result = mysql_query($sql);
				while ($rs=mysql_fetch_array($result)){
					$c=1;
				}
				$sql="SELECT max(NUMERO_MOVIMIENTO)a
						FROM
						pv_tbc_encabezado_movimientos
						";
					$sql.=" where TIPO_MOVIMIENTO='TRA'
						";	
				$result = mysql_query($sql);
				$nro=1;
				while ($rs=mysql_fetch_array($result)){
					$nro=$rs[0]+1;
				}
				if ($c==0) {
					echo"<script>alert('Debe indicar la menos un producto');</script>";	
					echo"<script>history.back(1);</script>";	
				}
				else
				{
					$login=isset($_SESSION["login_usu"])? $_SESSION["login_usu"] : 1;
					$login=($login==0) ? 1 : $login;
					$sql="SELECT
						c.EXISTENCIA_PRODUCTO,
						aux_transferencias_detalle2.cantidad,c.CODIGO_PRODUCTO
						FROM
						pv_tbc_inventarios AS c
						Inner Join aux_transferencias_detalle2 ON aux_transferencias_detalle2.id_producto = c.CODIGO_PRODUCTO

					WHERE
					(c.EXISTENCIA_PRODUCTO-cantidad<0)and aux_transferencias_detalle2.id_usuario=$_SESSION[login_usu] and CODIGO_ALMACEN=".$_POST["deposito"];
					$result = mysql_query($sql);
					$a=0;
					while ($campo1=mysql_fetch_array($result)){
						$a=1;
						$cant=intval($campo1[1]);
						$exis=intval($campo1[0]);
						$sql="select * from  pv_tbc_productos where CODIGO_PRODUCTO=$campo1[2]";
						$result2 = mysql_query($sql);
						$descripcion="";
						while ($campo2=mysql_fetch_array($result2)){
							$descripcion=$campo2[1];
						}
						$cant=str_replace(",","",$cant);
						$cant=str_replace(".","",$cant);
						$exis=str_replace(",","",$exis);
						$exis=str_replace(".","",$exis);
						echo"<script>alert('No hay Existencia para REALIZAR la Transferencia en Producto $descripcion Cantidad: $cant Existencia: $exis');</script>";
						
					}
					
					if($a==0){
						$sql="INSERT INTO pv_tbc_encabezado_movimientos ";
						$sql=$sql."VALUES (NULL,'TRA','$nro','".$_POST["fecha"]."',0,'".$_POST["deposito"]."','".$_POST["destino"]."', '',1,'".$login."','".$_POST["obs"]."', 'EMITIDA',0,CURTIME())";
						$result = mysql_query($sql);
						$id = mysql_insert_id();
						
						$sql="insert into pv_tbl_detalle_movimientos SELECT $id,CONCAT(pv_tbc_productos.CODIGO_BARRA,'/',aux_transferencias_detalle2.UNIDAD,'/',aux_transferencias_detalle2.DESCRIPCION), pv_tbc_productos.CODIGO_PRODUCTO, aux_transferencias_detalle2.cantidad, pv_tbc_productos.DESCRIPCION_PRODUCTO, pv_tbc_productos.PRECIO, pv_tbc_impuestos.PORCENTAJE_IMPUESTO, pv_tbc_productos.CANTIDAD_UNIDAD_AGRUPADA, pv_tbc_productos.CODIGO_UNIDAD_AGRUPADA,0,0,NULL FROM aux_transferencias_detalle2 Inner Join pv_tbc_productos ON aux_transferencias_detalle2.id_producto = pv_tbc_productos.CODIGO_PRODUCTO Inner Join pv_tbc_impuestos ON pv_tbc_productos.CODIGO_IMPUESTO = pv_tbc_impuestos.CODIGO_IMPUESTO  where id_usuario=$_SESSION[login_usu] ";
						$result = mysql_query($sql);
						$sql="SELECT *
							FROM
							aux_transferencias_detalle2
							";
						$sql.=" where id_usuario=$_SESSION[login_usu] 
							";	
						$result = mysql_query($sql);
						while ($rs=mysql_fetch_array($result)){
								$sql="update pv_tbc_inventarios set EXISTENCIA_PRODUCTO=(EXISTENCIA_PRODUCTO - '".$rs[2]."') where CODIGO_ALMACEN=".$_POST["deposito"]." and CODIGO_PRODUCTO=".$rs[1]."";
								$result2 = mysql_query($sql);
								$sql="update pv_tbc_inventarios set EXISTENCIA_PRODUCTO=(EXISTENCIA_PRODUCTO + '".$rs[2]."') where CODIGO_ALMACEN=".$_POST["destino"]." and CODIGO_PRODUCTO=".$rs[1]."";
								$result2 = mysql_query($sql);
								
						}
	
						$sql="delete from aux_transferencias_detalle2 where id_usuario=$_SESSION[login_usu] ";
						$result = mysql_query($sql);
						
						echo"<script>";
						echo"alert('Transferencia Ingresada Satisfactoriamente');";
						echo"window.opener.location.href='transferencias.php';";	
						echo"window.close();";
						echo"</script>";
						exit();
					}
					else
						{
						

						echo"<script>document.form1.action='add_transferencias.php';</script>";	
						echo"<script>document.form1.submit();</script>";	
						exit();
						}
					
				}
				mysql_close ($enlace);				
			}
			if ($_GET["cancela"]==true && isset($_GET["cancela"]))
			{
				
					$sql="delete from aux_transferencias_detalle2 where id_usuario=$_SESSION[login_usu] ";
					$result = mysql_query($sql);
					
					echo"<script>";
					echo"window.close();";
					echo"</script>";
					exit();
			
			}
		
?>

	<table width="720" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center">
			<?php
			$lb_title="Encabezado";
            include("main/open_table.php");
			?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="TITULO" width="20%">&nbsp;</td>
                <td class="TITULO" width="30%">&nbsp;</td>
                <td class="TITULO" width="20%">&nbsp;</td>
                <td class="TITULO" width="30%">&nbsp;</td>
             </tr>
            <tr>
                <td class="TITULO" width="20%">&nbsp;&nbsp;Fecha:</td>
                <td width="30%"  colspan="3" align="left">
                <input type="text" class="input" readonly="readonly" size="20" name="fecha" id="cal-field-1" value="<?php echo date("Y/m/d"); ?>">
                <button type="submit" id="cal-button-1" style="calendar">...</button>
				<script type="text/javascript">
                    Calendar.setup({
                      inputField    : "cal-field-1",
                      button        : "cal-button-1",
					  ifFormat      : "%Y/%m/%d",
                      align         : "Tr"
                    });
                </script>
                </td>
             </tr>
             <tr>   
                <td class="TITULO" width="20%">&nbsp;&nbsp;Origen:</td>
                <td width="30%" align="left" colspan="3">
                <?php
                    echo "<select  name='deposito' class='input'>;";
                    $result = mysql_query("SELECT
                            *
                            FROM
                            pv_tbc_almacenes order by 1
                            ");
                    while ($rs=mysql_fetch_array($result)){
                            echo"<option value='".$rs[0]."' $selected>".$rs[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                            }
                    echo "</select>";
				?>
				</td>
        	</tr>
            <tr>   
                <td class="TITULO" width="20%">&nbsp;&nbsp;Destino:</td>
                <td width="30%" align="left" colspan="3">
                <?php
                    echo "<select  name='destino' class='input'>;";
                    $result = mysql_query("SELECT
                            *
                            FROM
                            pv_tbc_almacenes order by 1
                            ");
                    while ($rs=mysql_fetch_array($result)){
                            echo"<option value='".$rs[0]."' $selected>".$rs[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                            }
                    echo "</select>";
				?>
				</td>
        	</tr>
            <tr height="70">   
                <td class="TITULO" width="20%">&nbsp;&nbsp;Observaciones:</td>
                <td width="30%" align="left" colspan="3">
				<textarea name="obs" cols="95" rows="3" class="input"></textarea>
				</td>
        	</tr>
            </table>
			<?php
			include("main/Close_table.php");
			?>
            
        </td>
        </tr>
        <tr align="center" valign="middle">
        <td class="TITULO">
        <br>Detalle
        </td>
        </tr>
        <tr align="center" valign="top">
        <td colspan="4">
        <iframe width="95%" height="450" src="add_transferencias_aux.php" name="reporte" frameborder="0" scrolling="auto"></iframe>
         
        </td>
        </tr>
		
        <tr height="50" valign="middle">
                <td colspan="8" class="TITULO" align="center">
                <BR>
                <img src="images/btn_guardar_casa.png" onclick="valida();">&nbsp;
                <img src="images/btn_cancelar.png" onclick="cancelar();">
                </td>
        </tr>
	</table>
<div align="center"></div>
</form>
</div>
</body>
</html>
