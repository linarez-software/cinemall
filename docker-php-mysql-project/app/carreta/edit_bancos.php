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
   if ((document.form1.producto.value=="")  )
   {
   alert("Complete los campos requeridos");
   return(false);
   }
   
 
 document.form1.action="edit_bancos.php?insert=0&id=" +id;
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
	
	$sql="update pv_tbc_bancos set ";
		$sql.="NOMBRE_BANCO ='".strtoupper($_POST[producto])."' ";
		$sql.=" where CODIGO_BANCO =$_GET[id] ";
    $result = mysql_query($sql);
	mysql_close ($enlace);
	echo"<script>window.opener.location.href='Bancos.php';</script>";	
	echo"<script>window.close();</script>";
}
else
{
	$query="SELECT *
             FROM pv_tbc_bancos 
             WHERE CODIGO_BANCO=$_GET[id]";
	$result = mysql_query($query) or die(mysql_error());
	while($campo = mysql_fetch_array($result)):
	   $producto       =$campo[1];
	endwhile;
}
?>
<FORM METHOD=POST ACTION=add_Producto.php name="form1">
<P><br>
<table width="95%" border="0" align="center">
  <tr>
    <td width="5%"><br></td>
	<td colspan="3"><br>

	<?php
		$lb_title="Editar Bancos";
		include("main/open_table.php");
		?>
       
        <table width="100%" align="center" border="0">
	        <tr height="30">
                <td width="200" class="etiqueta">  Descripción:</td>
                <td width="911">
              <input  type="text" class="input" name="producto" id="producto" size="50" maxlength="100" value="<?php echo $producto;?>" ></td>
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
