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
function editar(codigo)
{
	window.open ("edit_proveedores.php?insert=1&id="+   codigo ,"Help","scrollBars=yes,resizable=no,toolbar=no,menubar=yes,top =10,left=10,location=no,directories=no,width=800,height=400"); 
}
function valida()
{
	   window.open ("add_proveedores.php?insert=1", "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=800,height=400");  

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
    
	<form method="post" name="form1" action="Consulta_facturas.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
 			  include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='4' align='center'>Listado de Proveedores</td>";
			  echo"</tr>";
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='css-elemento-menu' colspan='4' align='center'><a href='#' onClick='valida();' class='css-elemento-menu'>AGREGAR</a></td>";
			  echo"</tr>";
			
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Id</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Descripcion</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>RIF</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"</tr>";
			  include_once("main/class.paginado.php");	  
			  $query="select * from pv_tbc_proveedores order by 2";
			  $pagina = $_GET["pagina"];
 	  		  $rs = new paginado($cnn);
			  $rs->pagina($pagina);
			  $rs->porPagina(20);
			  $rs->propagar("");
			  
			  if(!$rs->query($query)){
				die($rs->error());
			  }
			  $i=0;	
			  while ($fila=$rs->obtenerArray()){
			  	  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'>";
				  echo"	<td class='titulo' align='center'>".$fila[0]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[1]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[2]."</td>";
				  echo"  <td align='center'><img src='images/lapiz.gif' title='Editar' onclick='editar(".$fila[0].")'></td>";
				  echo"</tr>";	  
				}
				echo "</table>";
				echo "<center><FONT face='Verdana, Arial, Helvetica, sans-serif' size=1>".$rs->anterior()." - ".$rs->nroPaginas()." - ".$rs->siguiente()."</font></center>";
			  
			  ?>
              
          
	</form>
    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
