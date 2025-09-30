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
function Validar()
{
   if ((document.form1.producto.value=="") ||(document.form1.precioVIP.value=="") ||(document.form1.marca.value=="") ||(document.form1.unidad.value=="") ||(document.form1.cant.value=="") ||(document.form1.precio.value=="") ||(document.form1.codigo.value==""))
   {
   alert("Complete los campos requeridos");
   return(false);
   }
 document.form1.action="add_productos.php?insert=0" ;
 document.form1.submit();
}

</script>
<HTML>
<HEAD>
<TITLE>- CINE -</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/> 
</HEAD>
<BODY>
<?php
if ($_GET["insert"]==0)
{
	$sql="insert into pv_tbc_productos values(NULL,'".strtoupper($_POST[producto])."','$_POST[marca]','$_POST[unidadagrupada]','$_POST[cant]','$_POST[precio]','$_POST[impuesto]','$_POST[codigo]','$_POST[categoria]','".$_POST[unidad]."','$_POST[costo]','$_POST[descuento]','$_SESSION[login_usu]',now(),'$_POST[display]','$_POST[imprimible]','$_POST[imprimiblevip]','$_POST[precioVIP]','$_POST[dolar]')"; 
	$result = mysql_query($sql);
	$id = mysql_insert_id();
	$sql="INSERT INTO pv_tbc_inventarios SELECT null,pv_tbc_almacenes.CODIGO_ALMACEN,$id,0 FROM  pv_tbc_almacenes";
	$result = mysql_query($sql);
	/*echo"<script>window.opener.location.href='productos.php';</script>";	*/
	echo"<script>window.close();</script>";
}
?>
<FORM METHOD=POST ACTION=add_producto.php name="form1">
<P><br>
<table width="100%" border="0" align="center">
  <tr>
    <td width="5%"><br></td>
	<td colspan="3"><br>

	<?php
		$lb_title="Agregar productos";
		include("main/open_table.php");
		?>
       
        <table width="98%" align="center" border="0">
  <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Descripción:</td>
            <td width="915">
            <input type="text" class="input" name="producto" id="producto" size="50" maxlength="100" value=""  onKeyPress="return(SoloLetras(event))" >					</td>
          </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Marca:</td>
              <td>
                <input type="text" class="input" name="marca" id="marca" size="50" maxlength="50" value=""  onKeyPress="return(SoloLetras(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Unidad de Agrupada:</td>
              <td>
               <?php
				echo "<select  name='unidadagrupada' class='input' >;";
				$result = mysql_query("SELECT * FROM
						pv_tbc_unidades 
						ORDER BY
						2 ASC
						");
				while ($rs=mysql_fetch_array($result)){
						echo"<option value='".$rs[0]."' $selected>".$rs[1]."</option>";
						}
				echo "</select>";
				?>
              </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Cantidad Agrupada:</td>
              <td>
                <input type="text" class="input" name="cant" id="cant" size="50" maxlength="6" value="0"  onKeyPress="return(SoloNumero(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Cantidad Display:</td>
              <td>
                <input type="text" class="input" name="display" id="display" size="50" maxlength="6" value="0"  onKeyPress="return(SoloNumero(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Precio $:</td>
              <td>
                <input type="text" class="input" name="dolar" id="dolar" size="50" maxlength="10" value="0"  onKeyPress="return(SoloNumeroDec(event))" >          </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Precio:</td>
              <td>
                <input type="text" readonly='readonly' class="input" name="precio" id="precio" size="50" maxlength="10" value="0"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" readonly='readonly' class="etiqueta"><b>*</b>Precio VIP:</td>
              <td>
                <input type="text" class="input" name="precioVIP" id="precioVIP" size="50" maxlength="10" value="0"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Impuesto:</td>
              <td>
               <?php
				echo "<select  name='impuesto' class='input' >;";
				$result = mysql_query("SELECT * FROM
						pv_tbc_impuestos 
						ORDER BY
						3 DESC
						");
				while ($rs=mysql_fetch_array($result)){
						echo"<option value='".$rs[0]."' $selected>".$rs[2]."</option>";
						}
				echo "</select>";
				?> 
                </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Codigo Barra:</td>
              <td>
                <input type="text" class="input" name="codigo" id="codigo" size="50" maxlength="30" value=""  onKeyPress="return(SoloLetras2(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Valor Unidad:</td>
              <td>
                <input type="text" class="input" name="unidad" id="unidad" size="50" maxlength="6" value="1"   onKeyPress='return(SoloNumeromonto(event))'>					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Costo:</td>
              <td>
                <input type="text" class="input" name="costo" id="costo" size="50" maxlength="6" value="<?php echo $costo;?>"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Descuento:</td>
              <td>
                <input type="text" class="input" name="descuento" id="descuento" size="50" maxlength="3" value="<?php echo $descuento;?>"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Categoria:</td>
              <td>
               <?php
				echo "<select  name='categoria' class='input' >;";
				$result = mysql_query("SELECT * FROM
						pv_tbc_categoria 
						ORDER BY
						1 ASC
						");
				while ($rs=mysql_fetch_array($result)){
						echo"<option value='".$rs[0]."' $selected>".$rs[1]."</option>";
						}
				echo "</select>";
				?> 
                </td>
            </tr>
        <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Imprimible:</td>
              <td>
               <?php
				echo "<select  name='imprimible' class='input' >;";
				echo"<option value='0'>NO</option>";
				echo"<option value='1'>SI</option>";
				echo "</select>";
				?> 
                </td>
            </tr>
        <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Imprimible VIP:</td>
              <td>
               <?php
				echo "<select  name='imprimiblevip' class='input' >;";
				echo"<option value='0'>VIP</option>";
				echo"<option value='1'>CARAMELO</option>";
				echo"<option value='2'>COCINA</option>";
				echo "</select>";
				?> 
                </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Listado:</td>
              <td>
               <?php
				echo "<select  name='listado' class='input' >;";
				echo"<option value='0'>NO</option>";
				echo"<option value='1'>SI</option>";
				echo "</select>";
				?> 
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
		  <td width="45%" align="center"><img src="images/guardar.bmp"  width="25" height="25"alt="Guardar" onClick="Javascript:Validar();"></td>
		  <td width="15%" align="center">
			<img src="images/Salir.GIF" onClick="window.close();" alt="Cerrar">
		</td>
		  <td width="45%" align="center">&nbsp;</td>
	  </tr>
	</table>

</P>

</FORM>
</BODY>
</HTML>
