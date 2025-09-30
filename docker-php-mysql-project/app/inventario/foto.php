<?php
session_start();
include_once "../include/bd.inc.php";
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';
$qry = "SELECT * from pv_tbc_productos ";
	$qry .= "WHERE CODIGO_PRODUCTO = $_GET[id] ";
	$result = @mysql_query($qry, $cnn) or die("Error 3001: ".mysql_error());

	if($campo = mysql_fetch_object($result)):
		$CODIGO_PRODUCTO = $campo->CODIGO_PRODUCTO;
		$DESCRIPCION_PRODUCTO = $campo->DESCRIPCION_PRODUCTO;
		$foto = $campo->foto;
	endif;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $_SESSION['CINE'];?> - Cines Plus - Administrativo Inventarios</title>
<link href="ccs/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen"/>
<link href="main/styles2.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script language="javascript" type="text/javascript" src="js/funciones-comunes.js"></script>
<script language="javascript">
function prov(codigo)
{
	window.open ("edit_productos2.php?insert=1&id="+   codigo ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=800,height=400"); 
}
function editar(codigo)
{
	window.open ("edit_productos.php?insert=1&id="+   codigo ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=800,height=600"); 
}
function valida()
{
	   window.open ("add_productos.php?insert=1", "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=800,height=600");  

}

function vista2()
{
		window.open ("productosImp.php", "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=750,height=800");  


}
function foto()
{
		document.form1.action="foto.php";  
		document.form1.submit();  
		

}

</script>

</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="3"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="134" height="100%" valign="top" align="left"><?php include "menu.php";?></td>
	<td width="990" align="left" valign="top">
    <form method="post" action="upload.php" enctype="multipart/form-data">
	        <table width="100%" cellpadding="0" cellspacing="0">
        <?php
        $erro = isset($_GET['error']) ? $_GET['error'] : '2';
        if (($erro)==1):
            $tabla = "<tr><td width='100%' class='css-mensaje-metadata'>Archivo subido carrectamente</td></tr>";
            echo $tabla;
        endif;
        if (($erro)==0):
            $tabla = "<tr><td width='100%' class='css-mensaje-metadata'>Archivo Invalido</td></tr>";
            echo $tabla;
        endif;
        ?>
        <tr>
            <td width="100%" class="css-titulo-metadata" height="18">&nbsp;&nbsp;Fotos Productos</td>
        </tr>
        
        <tr>
            <td>
                <table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        
                    <tr bgcolor="#DDDDDD">
                        <td width="19%" class="css-label-metadata">Codigo:</td>
                        <td width="81%"><input name="id_pelicula" type="text"  readonly='readonly' class="css-campo-metadata-id" size="10" value="<?php echo isset($CODIGO_PRODUCTO) ? $CODIGO_PRODUCTO : '';?>" readonly>
                        <input name="id" type="hidden"   value="<?php echo isset($CODIGO_PRODUCTO) ? $CODIGO_PRODUCTO : '';?>" >
                        <input name="url" type="hidden"   value="<?php echo isset($url) ? $url : '';?>" >
                        </td>
                    </tr>
                    <tr bgcolor="#DDDDDD">
                        <td  width="19%" class="css-label-metadata">Producto:</td>
                        <td width="81%"><input name="nombre_espanol" readonly='readonly' type="text" class="css-campo-metadata" size="40" maxlength="45" value="<?php echo isset($DESCRIPCION_PRODUCTO) ? $DESCRIPCION_PRODUCTO : '';?>">
                        </td>
                    </tr>
                    
                    
                    
                </table>
            </td>
        </tr>
        <tr>
            <td>
            
                <table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        
                    <tr bgcolor="#DDDDDD">
                        <td width="19%" class="css-label-metadata">Imagen:</td>
                        <td width="81%">
                        <input type="hidden" name="MAX_FILE_SIZE" value="990000000" /> 
                        <input type="file" name="foto" class="css-boton-metadata"/> <input type="submit" name="enviar" value="Guardar" class="css-boton-metadata"/>
                        </td>
                    </tr>
                    <tr bgcolor="#DDDDDD">
                        <td width="19%" class="css-label-metadata">Tipo:</td>
                        <td width="81%">
                        <select name="tipo" class="css-boton-metadata">
                            <option value="1">---------Foto 1---------</option>
                        </select>
                        </td>
                    </tr>
                    <tr bgcolor="#DDDDDD">
                        <td  colspan=2  align="center" class="css-label-metadata">Foto </td>
                        
                    </tr>
                    
                    <tr bgcolor="#DDDDDD">
                        <td colspan="2"  align="center" class="css-label-metadata">
                        <table width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
                            <tr bgcolor="#DDDDDD">
                                <td  align="center" class="css-label-metadata"><img src="<?php echo $foto;?>"> </td>
                                
                                
                            </tr>
                            
                            
                        </table>
                        </td>
                        
                    </tr>
                    
                    
                </table>
              
            </td>
        </tr>
        <tr bgcolor="#999999">
            <td align="right">
                <input type="hidden" name="accion" value="<?php echo $accion;?>">
                <input type="hidden" name="status" value="1">
                <!-- <input type="button" value="Buscar" class="css-boton-metadata" onClick="javascript:Buscar()">&nbsp;&nbsp;&nbsp; -->
            </td>
        </tr>
        
        </table>
        </form>	

    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
