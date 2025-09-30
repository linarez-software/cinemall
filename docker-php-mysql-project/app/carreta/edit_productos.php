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
function Validar(id)
{
   if ((document.form1.producto.value=="") ||(document.form1.precioVIP.value=="") ||(document.form1.marca.value=="") ||(document.form1.unidad.value=="") ||(document.form1.cant.value=="") ||(document.form1.precio.value=="") ||(document.form1.codigo.value==""))
   {
   alert("Complete los campos requeridos");
   return(false);
   }
 document.form1.action="edit_productos.php?insert=0&id=" +id;
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
	//$gerencia=($_SESSION['id_estado']==0) ? $_SESSION["id_dependencia"] : $_POST['gerencia'];
	
	$sql="update pv_tbc_productos set ";
		$sql.="DESCRIPCION_PRODUCTO ='".strtoupper($_POST[producto])."' ";
		$sql.=",MARCA ='".strtoupper($_POST[marca])."' ";
		$sql.=",CODIGO_UNIDAD_AGRUPADA ='$_POST[unidadagrupada]' ";
		$sql.=",CANTIDAD_UNIDAD_AGRUPADA ='$_POST[cant]' ";
		$sql.=",display ='$_POST[display]' ";
		$sql.=",PRECIO ='$_POST[precio]' ";
		$sql.=",CODIGO_IMPUESTO ='$_POST[impuesto]' ";
		$sql.=",codigo_categoria ='$_POST[categoria]' ";
		$sql.=",preciovip ='$_POST[precioVIP]' ";
		$sql.=",fecha_act =now() ";
		$sql.=",preciodolar ='$_POST[dolar]' ";
		$sql.=",unidad ='$_POST[unidad]' ";
		$sql.=",costo ='$_POST[costo]' ";
		$sql.=",descuento ='$_POST[descuento]' ";
		$sql.=",id_usuario2 ='$_SESSION[login_usu]' ";
		$sql.=",imprimible ='$_POST[imprimible]' ";
		$sql.=",imprimiblevip ='$_POST[imprimiblevip]' ";
		$sql.=",CODIGO_BARRA ='$_POST[codigo]' ";
		$sql.=" where CODIGO_PRODUCTO =$_GET[id] ";

    $result = mysql_query($sql);
	mysql_close ($enlace);
	/*echo"<script>window.opener.location.href='productos.php';</script>";	*/
	echo"<script>window.opener.location.reload();</script>";
	echo"<script>window.close();</script>";
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
	   $categoria=$campo[8];
	   $unidad   =$campo[9];
	   $costo    =$campo[10];
	   $descuento=$campo[11];
	   $usu      =$campo[12];
	   $fecha    =$campo[13];
	   $display    =$campo[14];
	   $imprimible=$campo[15];
	   $imprimiblevip=$campo[16];
	   $preciovip=$campo[17];
	   $listado=$campo[18];
	   $dolar=$campo[18];
	   
	endwhile;
}
?>
?>
<FORM METHOD=POST ACTION=add_producto.php name="form1">
<P><br>
<table width="90%" border="0" align="center">
  <tr>
    <td width="5%"><br></td>
	<td colspan="3"><br>

	<?php
		$lb_title="Editar productos";
		include("main/open_table.php");
		?>
       
        <table width="100%" align="center" border="0">
		  <tr>
             <td width="249" class="etiqueta" colspan="2" align="center">ULTIMA MODIFICACION</td>
          </tr>
          <tr height="30">
             <td width="249" class="TITULO" colspan="2" align="center">
             <?php
			
			$Query1 = "SELECT nombre_completo FROM tbl_usuario WHERE id_usuario = $usu"; 
			$check = mysql_query($Query1) or die("Error en Query1: $Query1. mySQL devolvio " . mysql_error() . '.'); 
			$Results = mysql_num_rows($check); 
			if ($campo = mysql_fetch_object($check)) 
			{
				 echo "".$campo->nombre_completo;
			}
			 $fec =explode("-",substr($fecha,0,10));
			 echo "<br>Dia: ".$fec[2]."/".$fec[1]."/".$fec[0]." Hora: ".substr($fecha,11,10);
			
			
			?>
             </td>
          </tr>
          <tr height="30">
             <td width="249" class="etiqueta"><b>*</b> Descripción:</td>
            <td width="915">
            <input type="text" class="input" name="producto" id="producto" size="50" maxlength="100" value="<?php echo $DESCRIPCION_PRODUCTO;?>"  onKeyPress="return(SoloLetras(event))" >					</td>
          </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Marca:</td>
              <td>
                <input type="text" class="input" name="marca" id="marca" size="50" maxlength="50" value="<?php echo $MARCA;?>"  onKeyPress="return(SoloLetras(event))" >					</td>
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
						$selected=($CODIGO_UNIDAD_AGRUPADA==$rs[0]) ? "selected='selected'" : "";
						echo"<option value='".$rs[0]."' $selected>".$rs[1]."</option>";
						}
				echo "</select>";
				?>
              </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Cantidad Agrupada:</td>
              <td>
                <input type="text" class="input" name="cant" id="cant" size="50" maxlength="6" value="<?php echo $CANTIDAD_UNIDAD_AGRUPADA;?>"  onKeyPress="return(SoloNumero(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Display:</td>
              <td>
                <input type="text" class="input" name="display" id="display" size="50" maxlength="10" value="<?php echo $display;?>"  onKeyPress="return(SoloNumero(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Precio $:</td>
              <td>
                <input type="text" class="input" name="dolar" id="dolar" size="50" maxlength="10" value="<?php echo $dolar;?>"  onKeyPress="return(SoloNumeroDec(event))" >          </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Precio:</td>
              <td>
                <input type="text" readonly='readonly' class="input" name="precio" id="precio" size="50" maxlength="10" value="<?php echo $PRECIO;?>"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Precio VIP:</td>
              <td>
                <input type="text" readonly='readonly' class="input" name="precioVIP" id="precioVIP" size="50" maxlength="6" value="<?php echo $preciovip;?>"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Impuesto:</td>
              <td>
               <?php
				echo "<select  name='impuesto' class='input' >;";
				$result = mysql_query("SELECT * FROM
						pv_tbc_impuestos 
						ORDER BY
						1 ASC
						");
				while ($rs=mysql_fetch_array($result)){
						$selected=($CODIGO_IMPUESTO==$rs[0]) ? "selected='selected'" : "";
						echo"<option value='".$rs[0]."' $selected>".$rs[2]."</option>";
						}
				echo "</select>";
				?> 
                </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Codigo Barra:</td>
              <td>
                <input type="text" class="input" name="codigo" id="codigo" size="50" maxlength="30" value="<?php echo $CODIGO_BARRA;?>"  onKeyPress="return(SoloLetras2(event))" >					</td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Valor Unidad:</td>
              <td>
                <input type="text" class="input" name="unidad" id="unidad" size="50" maxlength="6" value="<?php echo $unidad;?>"   onKeyPress='return(SoloNumeromonto(event))'>					</td>
            </tr>
			<tr height="30">
                <td width="249" class="etiqueta"><b>*</b>Costo:</td>
              <td>
                <input type="text" class="input" name="costo" id="descuento" size="50" maxlength="6" value="<?php echo $costo;?>"  onKeyPress="return(SoloNumeroDec(event))" >					</td>
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
						$selected=($categoria==$rs[0]) ? "selected='selected'" : "";
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
				
				$selected1=($imprimible==0) ? "selected='selected'" : "";
				$selected2=($imprimible==1) ? "selected='selected'" : "";
				echo"<option value='0' $selected1>NO</option>";
				echo"<option value='1' $selected2>SI</option>";
				echo "</select>";
				?> 
                </td>
            </tr>
      <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Imprimible VIP:</td>
              <td>
               <?php
				$selected1=($imprimiblevip==0) ? "selected='selected'" : "";
				$selected2=($imprimiblevip==1) ? "selected='selected'" : "";
				$selected3=($imprimiblevip==2) ? "selected='selected'" : "";
				
				echo "<select  name='imprimiblevip' class='input' >;";
				echo"<option value='0' $selected1>VIP</option>";
				echo"<option value='1' $selected2>CARAMELO</option>";
				echo"<option value='2' $selected3>COCINA</option>";
				echo "</select>";
				?> 
                </td>
            </tr>
			<tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Listado:</td>
              <td>
               <?php
				$selected1=($listado==0) ? "selected='selected'" : "";
				$selected2=($listado==1) ? "selected='selected'" : "";
				echo "<select  name='listado' class='input' >;";
				echo"<option value='0' $selected1>NO</option>";
				echo"<option value='1' $selected2>SI</option>";
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
		  <td width="45%" align="center"><img src="images/guardar.bmp"  width="25" height="25"alt="Guardar" onClick="Javascript:Validar(<?php echo $_GET["id"];?>);"></td>
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
