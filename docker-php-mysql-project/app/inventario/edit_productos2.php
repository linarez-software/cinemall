<?php
session_start(); 
include_once "../include/bd.inc.php";
include_once "include/func.combo.php";

?>
<link rel="stylesheet" type="text/css" href="main/styles2.css">
<link rel="stylesheet" type="text/css" href="css/styles.css">
<script type="text/javascript" src="js/func.global.js"></script>
<link rel="stylesheet" type="text/css" href="include/calendario/calendar-win2k-cold-1.css">
<script type="text/javascript" src="include/calendario/calendar.js"></script>
<script type="text/javascript" src="include/calendario/calendar-setup.js"></script>
<script type="text/javascript" src="include/calendario/lang/calendar-es.js"></script>
<script type="text/javascript">

function numerico(valor){ 
	cad = valor.toString(); 
	for (var i=0; i<cad.length; i++) { 
		var caracter = cad.charAt(i); 
		if ((caracter<"0" || caracter>"9" )  &&(caracter!="." )&&(caracter!="," ))
			return false; 
		} 
	return true; 
	}
function valida()
{
 document.form1.action="edit_productos2.php?insert=0&id=" +document.form1.id.value;
 document.form1.submit();
}
function elimina(id)
{
 document.form1.action="edit_productos2.php?elimina=0&id=" +document.form1.id.value + "&id2=" + id;
 document.form1.submit();
}

</script>
<HTML>
<HEAD>
<TITLE>- CINE -</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/> 
</HEAD>
<BODY>


<FORM METHOD=POST ACTION=add_producto.php name="form1">
<?php
if (($_GET["elimina"]==0)&& isset($_GET["elimina"]))
{
	//$gerencia=($_SESSION['id_estado']==0) ? $_SESSION["id_dependencia"] : $_POST['gerencia'];
	
	$sql="delete from pv_tbl_productos_proveedores where codigo_producto= ";
	$sql.="$_GET[id] and CODIGO_PROVEEDOR= $_GET[id2]; ";
    $result = mysql_query($sql);
	
	echo"<script>document.form1.action='edit_productos2.php?id=$_GET[id]';</script>";	
	echo"<script>document.form1.submit();</script>";
	exit();
}


if (($_GET["insert"]==0)&& isset($_GET["insert"]))
{
	//$gerencia=($_SESSION['id_estado']==0) ? $_SESSION["id_dependencia"] : $_POST['gerencia'];
	
	$sql="insert into pv_tbl_productos_proveedores values( ";
		$sql.="$_GET[id] , ";
		$sql.="$_POST[origen])";
    $result = mysql_query($sql);

	echo"<script>document.form1.action='edit_productos2.php?id=$_GET[id]';</script>";	
	echo"<script>document.form1.submit();</script>";
	exit();
}
else
{
	$query="SELECT *
             FROM pv_tbc_productos 
             WHERE CODIGO_PRODUCTO=$_GET[id]";
	$result = mysql_query($query) or die(mysql_error());
	while($campo = mysql_fetch_array($result)):
	   $DESCRIPCION_PRODUCTO       =$campo[1];
	   $MARCA      =$campo[2];
	   $CODIGO_UNIDAD_AGRUPADA      =$campo[3];
	   $CANTIDAD_UNIDAD_AGRUPADA      =$campo[4];
	   $PRECIO      =$campo[5];
	   $CODIGO_IMPUESTO      =$campo[6];
	   $CODIGO_BARRA      =$campo[7];
	   $usu      =$campo[8];
	   $fecha      =$campo[9];
	   
	endwhile;
}
?>
<P><br>
<table width="90%" border="0" align="center">
  <tr>
    <td width="5%"><br></td>
	<td colspan="3"><br>

	<?php
		$lb_title="Editar Proveedor de productos";
		include("main/open_table.php");
		?>
       
        <table width="100%" align="center" border="0">
          <tr>
             <td width="249" class="TITULO" colspan="2" align="center">
            <tr height="30">
             <td width="249" class="etiqueta"><b>*</b> Descripción:</td>
            <td width="915">
            <input type="text" class="input" name="producto" readonly="readonly" id="producto" size="50" maxlength="100" value="<?php echo $DESCRIPCION_PRODUCTO;?>"  onKeyPress="return(SoloLetras(event))" >
            <input type="hidden" name="id" value="<?php echo $_GET[id];?>"></td>
          </tr>
          <tr>
             <td width="249" class="etiqueta" colspan="2" align="center">Listado</td>
          </tr>
		  <tr>
             <td width="249" class="etiqueta" colspan="2" align="center">
             <table width="75%" cellpadding="0" cellspacing="0" border="0" align="center">
                 <tr>
                     <td class="etiqueta">Proveedor:
                     </td>
                     <td>
                     <?php
                    echo "<select  name='origen' class='input' onchange='proveedor()'>;";
                    $result = mysql_query("SELECT
                            *
                            FROM
                            pv_tbc_proveedores order by 1
                            ");
					$prov="";
					while ($rs=mysql_fetch_array($result)){
							if ($prov=="") $prov=$rs[0];
                            echo"<option value='".$rs[0]."' $selected>".$rs[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                            }
                    echo "</select>";
					?>
                     </td>
                     
                 </tr>
                 <tr height="45">
                 <td align="center" colspan="2">
                 	<img src="images/btn_anadir_casa.png" title="Guardar" onClick="valida();">
                 </td>
                 </tr>
                 <tr height="">
                 <td align="center" colspan="2">
                 	<table width="100%" cellpadding="0" cellspacing="0" border="1" align="center">
                    <?php
					echo"<tr bgcolor='#F1F1F1'><td class='etiqueta'>Nro</td>";
					echo"<td class='etiqueta' colspan=2>Descripcion</td></tr>";
					$sql="SELECT pv_tbc_proveedores.NOMBRE_PROVEEDOR,pv_tbl_productos_proveedores.CODIGO_PROVEEDOR FROM pv_tbc_proveedores Inner Join pv_tbl_productos_proveedores ON pv_tbc_proveedores.CODIGO_PROVEEDOR = pv_tbl_productos_proveedores.CODIGO_PROVEEDOR where codigo_producto=$_GET[id]";
					$result = mysql_query($sql) or die(mysql_error());
					$i=1;
					while($campo = mysql_fetch_array($result)):
						echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'><td class='contenido'>$i</td>";
						echo"<td class='contenido'>$campo[0]</td>";
						echo"<td class='contenido' width='5%'><img src='images/equis.gif' title='Anular' onclick='elimina(".$campo[1].")'></td></tr>";
						$i++;
					endwhile;
					echo"<tr><td class='etiqueta' colspan=3 bgcolor='#F1F1F1'>&nbsp;</td></tr>";
					?>
                    </table>
                 </td>
                 </tr>
             </table>
             </td>
          </tr>

           
            </table>
        <?php
		include("main/Close_table.php");
		?>
		</td>
	  </tr>
	  <tr>
    	<td height="50" width="5%">&nbsp;
		</td>
		  <td width="45%" align="center"></td>
		  <td width="15%" align="center">
			<img src="images/Salir.GIF" onClick="window.opener.location.href='productos.php';window.close();" alt="Cerrar">
		</td>
		  <td width="45%" align="center">&nbsp;</td>
	  </tr>
	</table>

</P>

</FORM>
</BODY>
</HTML>
