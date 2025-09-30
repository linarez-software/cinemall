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
  if ((document.form1.producto.value=="") ||(document.form1.marca.value=="") ||(document.form1.dir.value=="") ||(document.form1.tlf.value=="") ||(document.form1.contacto.value==""))
    {
   alert("Complete los campos requeridos");
   return(false);
   }
 document.form1.action="edit_proveedores.php?insert=0&id=" +id;
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
	
	$sql="update pv_tbc_proveedores set ";
		$sql.="NOMBRE_PROVEEDOR ='".strtoupper($_POST[producto])."' ";
		$sql.=",RIF_PROVEEDOR ='$_POST[marca]' ";
		$sql.=",DIRECCION_PROVEEDOR ='$_POST[dir]' ";
		$sql.=",TELEFONO_PROVEEDOR ='$_POST[tlf]' ";
		$sql.=",PERSONA_CONTACTO ='$_POST[contacto]' ";
		$sql.=" where CODIGO_PROVEEDOR =$_GET[id] ";
		
    $result = mysql_query($sql);
	mysql_close ($enlace);
	echo"<script>window.opener.location.href='proveedores.php';</script>";	
	echo"<script>window.close();</script>";
}
else
{
	$query="SELECT *
             FROM pv_tbc_proveedores 
             WHERE CODIGO_PROVEEDOR=$_GET[id]";
	$result = mysql_query($query) or die(mysql_error());
	while($campo = mysql_fetch_array($result)):
	   $NOMBRE_PROVEEDOR       =$campo[1];
	   $RIF_PROVEEDOR      =$campo[2];
	   $DIRECCION_PROVEEDOR      =$campo[3];
	   $TELEFONO_PROVEEDOR      =$campo[4];
	   $PERSONA_CONTACTO      =$campo[5];
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
		$lb_title="Editar Proveedores";
		include("main/open_table.php");
		?>
       
        <table width="100%" align="center" border="0">
          <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Descripción:</td>
            <td width="915">
            <input type="text" class="input" name="producto" id="producto" size="50" maxlength="100" value="<?php echo $NOMBRE_PROVEEDOR;?>"  >					</td>
          </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> RIF:</td>
              <td>
                <input type="text" class="input" name="marca" id="marca" size="50" maxlength="20" value="<?php echo $RIF_PROVEEDOR;?>" ></td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Dirección:</td>
                <td>
                <textarea name="dir" class="input" cols="100" rows="5"><?php echo $DIRECCION_PROVEEDOR;?></textarea>
                </td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Telefonos:</td>
              <td>
                <input type="text" class="input" name="tlf" id="tlf" size="50" maxlength="50" value="<?php echo $TELEFONO_PROVEEDOR;?>" ></td>
            </tr>
            <tr height="30">
                <td width="249" class="etiqueta"><b>*</b> Contacto:</td>
              <td>
                <input type="text" class="input" name="contacto" id="contacto" size="50" maxlength="50" value="<?php echo $PERSONA_CONTACTO;?>" ></td>
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
