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
   document.form1.action="add_ordenes.php?cancela=true";
   document.form1.submit();
}
function valida()
{
	 if ((document.form1.cantidad.value==""))
	 {
	 	alert("Debe indicar la cantidad de personas en el refugio");
		return false;
	 }
	if (numerico(document.form1.cantidad.value)==false)
	 {
	 	alert("La cantidad debe ser numerica");
		return false;
	 }
	if ((document.form1.cantidad.value<=0))
	 {
	 	alert("La cantidad debe ser mayor a 0");
		return false;
	 }
	 document.form1.action="add_transferencias_aux.php?insert=true";
	 document.form1.submit();
}
function anula(id)
{
	if (confirm("¿Esta seguro de eliminar este producto?")) {
	
	   document.form1.action="add_transferencias_aux.php?elimina=true&id="+ id ;
	   document.form1.submit();
	}
}

</script>
<html>
<?php	
			if ($_GET["insert"]==true && isset($_GET["insert"]))
			{
					if($_POST[unidad]==1) {
						$sql="insert into aux_transferencias_detalle2 value(NULL,$_POST[producto],$_POST[cantidad],0,0,$_SESSION[login_usu],$_POST[cantidad],'UNIDAD');";	
					}
					else
						{
						if($_POST[unidad]==2) {
							$sql="insert into aux_transferencias_detalle2 value(NULL,$_POST[producto],($_POST[cantidad]*(select CANTIDAD_UNIDAD_AGRUPADA from pv_tbc_productos where CODIGO_PRODUCTO=$_POST[producto] )),0,0,$_SESSION[login_usu],$_POST[cantidad],'BULTOS');";	
						}
						else
							{
							$sql="insert into aux_transferencias_detalle2 value(NULL,$_POST[producto],($_POST[cantidad]*(select display from pv_tbc_productos where CODIGO_PRODUCTO=$_POST[producto] )),0,0,$_SESSION[login_usu],$_POST[cantidad],'DISPLAY');";	
							}
						}
					$result2 = mysql_query($sql);
					echo"<script>document.form1.action='add_transferencias_aux.php';</script>";	
					echo"<script>document.form1.submit();</script>";
			}
			if ($_GET["elimina"]==true && isset($_GET["elimina"]))
			{
					$sql="delete from aux_transferencias_detalle2 where id=$_GET[id]";	
					$result2 = mysql_query($sql);
					echo"<script>document.form1.action='add_transferencias_aux.php';</script>";	
					echo"<script>document.form1.submit();</script>";
			}
		
?>
<head>
<title>Agregar transferencias</title>
</head>
<body>
<div align="center">
<form method="post" name="form1" >
	<table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" >
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
             <tr bgcolor="#CCCCCC">
                <td align="left"><b>&nbsp;&nbsp;&nbsp;Producto</b></td>
                <td align="left"><b>&nbsp;&nbsp;&nbsp;Presentacion</b></td>
                
                <td align="center"><b>Cantidad</b></td>
              </tr>
              <tr>
                <td  width="70%" align="left">&nbsp;&nbsp;&nbsp;
                        <?php
						
                        echo "<select  name='producto' class='input'>;";
                        $result = mysql_query("SELECT
								pv_tbc_productos.CODIGO_PRODUCTO,
								pv_tbc_productos.DESCRIPCION_PRODUCTO,
								pv_tbc_productos.MARCA
								FROM
								pv_tbc_productos
								ORDER BY
								pv_tbc_productos.DESCRIPCION_PRODUCTO ASC,
								pv_tbc_productos.MARCA

                                ");
                        while ($rs=mysql_fetch_array($result)){
                                echo"<option value='".$rs[0]."' $selected>".$rs[1]."</option>";
                                }
                        echo "</select>";
                        ?>
                </td>
                <td align="center">
                   <select name="unidad" class="input">
                       <option value="1">UNIDADES</option>
                       <option value="3">DISPLAY</option>
                       <option value="2">BULTOS</option>
                   </select>
                </td>
               <td align="center">
                    <input class='input'  name='cantidad' type='text'  onKeyPress='return(SoloNumero(event))' size='10' value='0'  maxlength='10'>
                </td>
                
              </tr>
              <tr height="50">
              	<td colspan="2" align="center"><img src="images/btn_anadir_casa.png" title="Añadir Producto" onclick="valida();">
                </td>
              </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td align="center">
			<?php
			$lb_title="Encabezado";
            include("main/open_table.php");
			?>
            
        <?php
				echo"<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
					echo"<tr>";
					echo"	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>Id</td>";
					echo"	<td width='50%' bgcolor='#F1F1F1' class='TITULO' align='left'>Cod Barra</td>";
					echo"	<td width='50%' bgcolor='#F1F1F1' class='TITULO' align='left'>Producto</td>";
					echo"	<td width='20%' bgcolor='#F1F1F1' class='TITULO' align='center'>Cantidad</td>";
					echo"	<td width='20%' bgcolor='#F1F1F1' class='TITULO' align='center'>Descripción</td>";
					echo"	<td width='10%' bgcolor='#F1F1F1' class='TITULO' align='center'>&nbsp;</td>";
					echo"</tr>";
					$sql="SELECT
						pv_tbc_productos.DESCRIPCION_PRODUCTO,
						aux_transferencias_detalle2.id_producto,
						aux_transferencias_detalle2.id,
						aux_transferencias_detalle2.UNIDAD,
						aux_transferencias_detalle2.DESCRIPCION,
						pv_tbc_productos.codigo_barra
						FROM
						aux_transferencias_detalle2
						Inner Join pv_tbc_productos ON aux_transferencias_detalle2.id_producto = pv_tbc_productos.CODIGO_PRODUCTO
						Inner Join pv_tbc_unidades ON pv_tbc_productos.CODIGO_UNIDAD_AGRUPADA = pv_tbc_unidades.CODIGO_UNIDAD_AGRUPADA

						";
					$sql.=" where id_usuario=$_SESSION[login_usu] order by 3
						";	
					$result = mysql_query($sql);
					$i=1;
					 while ($rs=mysql_fetch_array($result)){
						echo"<tr>";
						echo"	<td class='TITULO' align='left'>$i</td>";
						echo"	<td class='TITULO' align='left'>$rs[6]</td>";
						echo"	<td class='TITULO' align='left'>$rs[0]</td>";
						echo"	<td class='TITULO' align='center'>".number_format(($rs[3]),2)."</td>";
						echo"	<td class='TITULO' align='center'>$rs[4]</td>";
						echo"  <td align='center'><img src='images/equis.gif' title='Anular' onclick='anula(".$rs[2].")'></td>";
						echo"</tr>";
						$i++;
						}
					$i--;
				echo"</table>";
				echo"<input class=''  name='num' type='hidden' size='10' value='$i'  maxlength='10'>";
		
		
		
		?>
         
       
<div align="center"></div>
</form>
</div>
</body>
</html>
