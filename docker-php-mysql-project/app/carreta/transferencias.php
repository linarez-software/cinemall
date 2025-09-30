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
function anula(id)
{
	if (confirm("¿Esta seguro de Anular esta salida?")) {
	
	   document.form1.action="transferencias.php?elimina=true&id="+ id ;
	   document.form1.submit();
	}
}
function valida()
{
	   window.open ("add_transferencias.php", "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=750,height=800");  

}

function vista(id)
{
	   document.form1.action="reporte_transferencia.php?id="+ id ;
	   document.form1.submit();

}
function vista2(id)
{
		window.open ("reporte_transferencia2.php?id="+ id, "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=750,height=800");  


}

</script>

</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="5" border="0">
<tr>
	<td colspan="3"><?php include "head.php";?></td>
</tr>
<tr>
	<td width="134" height="100%" valign="top" align="left"><?php include "menu_movimientos.php";?></td>
	<td width="990" align="left" valign="top">
    
	<form method="post" name="form1" action="Consulta_facturas.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
 			  include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='6' align='center'>Listado de Transferencias entre Alamcenes</td>";
			  echo"</tr>";
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='css-elemento-menu' colspan='6' align='center'><a href='#' onClick='valida();' class='css-elemento-menu'>AGREGAR</a></td>";
			  echo"</tr>";
			
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Nro</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Fecha</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Destino</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Origen</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"</tr>";
			  include_once("main/class.paginado.php");	  
			  $query="SELECT
						pv_tbc_encabezado_movimientos.ID_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
						pv_tbc_encabezado_movimientos.FECHA_EMISION,
						pv_tbc_encabezado_movimientos.ALMACEN_DESTINO,
						B.NOMBRE_ALMACEN AS destino,
						A.NOMBRE_ALMACEN AS origen
						FROM
						pv_tbc_encabezado_movimientos
						Inner Join pv_tbc_almacenes AS B ON pv_tbc_encabezado_movimientos.ALMACEN_DESTINO = B.CODIGO_ALMACEN
						Inner Join pv_tbc_almacenes AS A ON pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN = A.CODIGO_ALMACEN
						WHERE
						pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO = 'TRA'
						ORDER BY
						2 DESC
				";
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
				  echo"	<td class='titulo' align='center'>".$fila[1]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[2]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[4]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[5]."</td>";
				  echo" <td align='center'><img src='images/buscars.jpg' title='Visualizar' onclick='vista2(".$fila[0].")'></td>";
				  echo" <td align='center'><img src='images/pdf.jpg' height=20 width=20 title='Visualizar' onclick='vista(".$fila[0].")'></td>";
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
