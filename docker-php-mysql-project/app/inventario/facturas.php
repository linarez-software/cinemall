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

function vista2(id)
{
		window.open ("reporte_factura.php?id="+ id, "Help","scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=750,height=800");  


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
    
	<form method="post" name="form1" action="facturas.php" >
			<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left">
            <?php
 			  include_once "../include/bd.inc.php";
			  include_once "include/func.combo.php";
			  $txt=$_POST[txt];
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='8' align='center'>Listado de Facturas</td>";
			  echo"</tr>";
			  echo"<tr height='45'>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' colspan='8' align='center'>Filtrar : <input class='input'  name='txt' value='$txt' type='text' size='50' maxlength='10'>&nbsp;&nbsp;<br><input type='submit' value='BUSCAR' class='input' onclick='Validart();'></td>";
			  echo"</tr>";
			  echo"<tr>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Nro</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Fecha</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Hora</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Caja</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Usuario</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Monto</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'>Status</td>";
			  echo"	<td bgcolor='#F1F1F1' class='titulo' align='center'></td>";
			  echo"</tr>";
			  include_once("main/class.paginado.php");	  
			  $query="SELECT
					pv_tbc_encabezado_movimientos.ID_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.NUMERO_MOVIMIENTO,
					pv_tbc_encabezado_movimientos.FECHA_EMISION,
					pv_tbc_encabezado_movimientos.STATUS_MOVIMIENTO,
					pv_tbc_almacenes.NOMBRE_ALMACEN,
					tbl_usuario.username,
					tbl_usuario.nombre_completo,
					pv_tbc_encabezado_movimientos.ALMACEN_ORIGEN,
					pv_tbc_encabezado_movimientos.CODIGO_CAJA,
					pv_tbc_encabezado_movimientos.MONTO,
					pv_tbc_encabezado_movimientos.hora,
					pv_tbc_encabezado_movimientos.OBSERVACION_MOVIMIENTO
					FROM
					pv_tbc_encabezado_movimientos
					left Join pv_tbc_almacenes ON pv_tbc_encabezado_movimientos.ORIGEN = pv_tbc_almacenes.CODIGO_ALMACEN
					left Join tbl_usuario ON pv_tbc_encabezado_movimientos.CODIGO_USUARIO = tbl_usuario.id_usuario
					WHERE
					pv_tbc_encabezado_movimientos.TIPO_MOVIMIENTO = 'FAC'  and
					(
					NUMERO_MOVIMIENTO LIKE'$txt%' or
					username LIKE'%$txt%' or
					nombre_completo LIKE'%$txt%' or
					MONTO LIKE'%$txt%'  or
					hora LIKE'%$txt%' 
					
					)
					ORDER BY 1 desc,2 desc

			";

			  $pagina = $_GET["pagina"];
 	  		  $rs = new paginado($cnn);
			  $rs->pagina($pagina);
			  $rs->porPagina(100);
			  $rs->propagar("");
			  
			  if(!$rs->query($query)){
				die($rs->error());
			  }
			  $i=0;	
			  while ($fila=$rs->obtenerArray()){
			  	  echo"<tr onMouseOver=bgColor='#D1D1D1' onMouseOut=bgColor='#FFFFff'>";
				  echo"	<td class='titulo' align='center'>".$fila[1]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[2]."</td>";
				  echo"	<td class='titulo' align='center'>".$fila[10]."</td>";
				  echo"	<td class='titulo' align='left'>CAJA ".$fila[8]."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[5]."&nbsp;".$fila[6]."</td>";
				  echo"	<td class='titulo' align='center'>".str_replace("*",".",str_replace(".",",",str_replace(",","*",number_format($fila[9],2))))."</td>";
				  echo"	<td class='titulo' align='left'>".$fila[3]."</td>";
				  echo" <td align='center'><img src='images/buscars.jpg' title='Visualizar' onclick='vista2(".$fila[0].")'></td>";
				  echo"</tr>";	  
				}
				echo "<tr><td colspan='11' >";
				echo "<center><FONT face='Verdana, Arial, Helvetica, sans-serif' size=1>".$rs->anterior()." - ".$rs->nroPaginas()." - ".$rs->siguiente()."</font></center>";
			  	echo "</td></tr></table>";
				
				echo "</table>";

			  ?>
              
          
	</form>
    </td>
    <td width="99"></td>
</tr>
</table>
</body>
</html>
