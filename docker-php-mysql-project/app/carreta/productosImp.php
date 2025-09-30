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

<body onLoad="window.print(); window.close();">
    
	<form method="post" name="form1" action="productos.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
 			  include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  $txt=isset($_GET[txt]) ? $_GET[txt] : $_POST[txt];
			  $txt=strlen($txt)==0 ? $_POST[txt] :$txt;
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='left'>FECHA:____________________________________   CAJA:____________________________________</td>";
			  echo"</tr>";
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='center'>Listado de productos</td>";
			  echo"</tr>";
			  echo"<tr>";
			  echo"	<td  width='15%' bgcolor='#F1F1F1' class='titulo' align='center'>Distribuidor</td>";
			  echo"	<td  width='15%' bgcolor='#F1F1F1' class='titulo' align='center'>Descripcion</td>";
			  echo"	<td width='20%' bgcolor='#F1F1F1' class='titulo' align='center'>Bultos</td>";
			  echo"	<td width='20%' bgcolor='#F1F1F1' class='titulo' align='center'>Display</td>";
			  echo"	<td width='30%' bgcolor='#F1F1F1' class='titulo' align='center'>Unidades</td>";
			  echo"</tr>";
			  echo"<tr height='30'>";
				echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='center'></td>";
				echo"</tr>";
				include_once("main/class.paginado.php");	  
			   
			  $query="select a.*,b.descripcion_categoria,(SELECT max(NOMBRE_PROVEEDOR) FROM pv_tbl_productos_proveedores aa inner join pv_tbc_proveedores b on aa.CODIGO_PROVEEDOR=b.CODIGO_PROVEEDOR where CODIGO_PRODUCTO=a.CODIGO_PRODUCTO )prov
			   from pv_tbc_productos a inner join pv_tbc_categoria b on a.codigo_categoria=b.codigo_categoria 
			   order by prov,2";
			  $pagina = $_GET["pagina"];
 	  		  $txt1=isset($_GET[txt]) ? $_GET[txt] : $_POST[txt];
			  $txt1=strlen($txt1)==0 ? $_POST[txt] :$txt1;
			  $rs = new paginado($cnn);
			  $rs->pagina($pagina);
			  $rs->text=$txt1;
			  $rs->porPagina(500);
			  $rs->propagar("");
			  
			  if(!$rs->query($query)){
				die($rs->error());
			  }
			  $i=0;	
			  $j=1;	
			  while ($fila=$rs->obtenerArray()){
			  	  if($i==20){
				  
						echo"<tr>";
						echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='center'>Listado de productos</td>";
						echo"</tr>";
						echo"<tr>";
						echo"	<td  width='200' bgcolor='#F1F1F1' class='titulo' align='center'>Distribuidor</td>";
						echo"	<td  width='200' bgcolor='#F1F1F1' class='titulo' align='center'>Descripcion</td>";
						echo"	<td width='130' bgcolor='#F1F1F1' class='titulo' align='center'>Bultos</td>";
						echo"	<td width='130' bgcolor='#F1F1F1' class='titulo' align='center'>Display</td>";
						echo"	<td width='140' bgcolor='#F1F1F1' class='titulo' align='center'>Unidades</td>";
						echo"</tr>";
						$i=0;	
						echo"<tr height='30'>";
						echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='center'></td>";
						echo"</tr>";
				  }
			  	  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff' height='30'>";
				  echo"	<td class='titulo2' align='left'  onclick='editar(".$fila[0].")'>".$fila['prov']."</td>";
				  echo"	<td class='titulo2' align='left'  onclick='editar(".$fila[0].")'>".$fila[1]."&nbsp;</td>";
				  echo"  <td align='center'></td>";
				  echo"  <td align='center'></td>";
				  echo"  <td align='center'></td>";
				  echo"</tr>";	  
				  $i++;
				  $j++;
				  
				}
				echo"<tr height='30'>";
			    echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='left'>ENTREGA:________________________________________________________________________</td>";
			    echo"</tr>";
			    echo"<tr>";
				echo"<tr height='30'>";
			    echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='left'>RECIBIDO:________________________________________________________________________</td>";
			    echo"</tr>";
			    echo"<tr>";
				echo"<tr height='30'>";
			    echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='left'>SUPERVISOR:________________________________________________________________________</td>";
			    echo"</tr>";
			    echo"<tr>";
			  	echo "</table>";
				
			  ?>
              
          
	</form>
</body>
</html>
