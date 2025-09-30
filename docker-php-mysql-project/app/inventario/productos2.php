<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';
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
    
	<form method="post" name="form1" action="productos.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
 			  include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  $txt=isset($_GET[txt]) ? $_GET[txt] : $_POST[txt];
			  $txt=strlen($txt)==0 ? $_POST[txt] :$txt;
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='9' align='center' onclick='vista2();'>Imprimir Listado de productos</td>";
			  echo"</tr>";
			  if($_SESSION['NIVEL']==1){
					  echo"<tr>";
					  echo"	<td bgcolor='#F1F1F1' class='css-elemento-menu' colspan='8' align='center'><a href='#' onClick='valida();' class='css-elemento-menu'>AGREGAR</a></td>";
					  echo"</tr>";
			  }
			  echo"<tr height='45'>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='9' align='center'>Filtrar : <input class='input'  name='txt' value='$txt' type='text' size='50' maxlength='10'>&nbsp;&nbsp;<br><input type='submit' value='BUSCAR' class='input' onclick='Validart();'></td>";
			  echo"</tr>";

			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Distribuidor</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Categoria</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Descripcion</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Cod Barra</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Precio</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Precio VIP</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Listado Precio</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"</tr>";
			  include_once("main/class.paginado.php");	  
			   
			  $query="select a.*,b.descripcion_categoria,(SELECT max(NOMBRE_PROVEEDOR) FROM pv_tbl_productos_proveedores aa inner join pv_tbc_proveedores b on aa.CODIGO_PROVEEDOR=b.CODIGO_PROVEEDOR where CODIGO_PRODUCTO=a.CODIGO_PRODUCTO )prov
			   from pv_tbc_productos a inner join pv_tbc_categoria b on a.codigo_categoria=b.codigo_categoria where DESCRIPCION_PRODUCTO LIKE'%$txt%' or descripcion_categoria  LIKE'%$txt%' or CODIGO_BARRA like '%$txt%' order by prov,2";
			  $pagina = $_GET["pagina"];
 	  		  $txt1=isset($_GET[txt]) ? $_GET[txt] : $_POST[txt];
			  $txt1=strlen($txt1)==0 ? $_POST[txt] :$txt1;
			  $rs = new paginado($cnn);
			  $rs->pagina($pagina);
			  $rs->text=$txt1;
			  $rs->porPagina(20);
			  $rs->propagar("");
			  
			  if(!$rs->query($query)){
				die($rs->error());
			  }
			  $i=0;	
			  while ($fila=$rs->obtenerArray()){
			  	  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'>";
				  echo"	<td class='titulo' align='left'  onclick='editar(".$fila[0].")'>".$fila['prov']."</td>";
				  echo"	<td class='titulo' align='left'  onclick='editar(".$fila[0].")'>".$fila["descripcion_categoria"]."</td>";
				  echo"	<td class='titulo' align='left'  onclick='editar(".$fila[0].")'>".$fila[1]."&nbsp;</td>";
				  echo"	<td class='titulo' align='left'>".$fila["CODIGO_BARRA"]."</td>";
				  echo"	<td class='titulo' align='center'>".number_format($fila["PRECIO"],2)."</td>";
				  echo"	<td class='titulo' align='center'>".number_format($fila["preciovip"],2)."</td>";
				  $p=($fila["listado"])==1 ? "SI" : "NO";
				  echo"	<td class='titulo' align='center'>".$p."</td>";
				  echo"  <td align='center'><img src='images/lapiz.gif' title='Editar' onclick='editar(".$fila[0].")'></td>";
				  echo"  <td align='center'><img src='images/images4.jpg' title='Editar' width='20' height='25' onclick='prov(".$fila[0].")'></td>";
				  echo"</tr>";	  
				}
				echo "<tr><td colspan='8' >";
				echo "<center><FONT face='Verdana, Arial, Helvetica, sans-serif' size=1>".$rs->anterior()." - ".$rs->nroPaginas()." - ".$rs->siguiente()."</font></center>";
			  	echo "</td></tr></table>";
				
			  ?>
              
          
	</form>
    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
