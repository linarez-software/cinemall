<?php
session_start();
if(!isset($_SESSION['LOGIN'])):
	header("Location: login.php");
endif;
unset($_SESSION['LOGIN2']);
$modulo = isset($_GET['modulo']) ? $_GET['modulo'] : '';
$sub_modulo = isset($_GET['sub']) ? '-'.$_GET['sub'] : '';
include_once "../include/bd.inc.php";

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
<link rel="stylesheet" type="text/css" href="main/calendar-win2k-cold-1.css">
<script type="text/javascript" src="main/calendar.js"></script>
<script type="text/javascript" src="main/calendar-setup.js"></script>
<script type="text/javascript" src="main/lang/calendar-es.js"></script>

<script language="javascript">
function valida(tipo1)
{
	if(document.form1.tipo.value==1) {
		document.form1.action="reporte_recepciones2.php?tipo1=" + tipo1;
	}
	if(document.form1.tipo.value==2) 
		{
		document.form1.action="reporte_recepciones.php?tipo1=" + tipo1;
		}
	if(document.form1.tipo.value==3) 
		{
		document.form1.action="reporte_recepciones3.php?tipo1=" + tipo1;
		}
	
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
	<td width="134" height="100%" valign="top" align="left"><?php include "menu_reportes.php";?></td>
	<td width="990" align="left" valign="top">
    
	<form method="post" name="form1" action="Consulta_facturas.php" >
			<table width="400" border="0" align="center">
              <tr><td>
			<?php
			$lb_title="Recepciones de  Mercancias";
			include "main/open_table.php";
			?>
                <table width="400" border="1" align="center">
                  <tr>
                    <td class="TITULO" colspan="2" align="center">Recepciones</td>
                  </tr>
                  <tr height="25">
                    <td class="TITULO" width="80">Almacen:</td>
                    <td align="left">&nbsp;
                    <?php
                    echo "<select  name='almacen' class='input'>;";
                    $result = mysql_query("SELECT
                            *
                            FROM
                            pv_tbc_almacenes order by 1
                            ");
					echo"<option value='%' $selected>---SELECCIONAR TODOS---</option>";
                    while ($rs=mysql_fetch_array($result)){
                            echo"<option value='".$rs[0]."' $selected>".$rs[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                            }
                    echo "</select>";
					?>
                    </td>
                  </tr>
                  <tr height="25">
                    <td class="TITULO" width="80">Proveedor:</td>
                    <td align="left">&nbsp;
                    <?php
                    echo "<select  name='origen' class='input' onchange='proveedor()'>;";
                    $result = mysql_query("SELECT
                            *
                            FROM
                            pv_tbc_proveedores order by 1
                            ");
					$prov="";
					echo"<option value='%' $selected>---SELECCIONAR TODOS---</option>";
                    while ($rs=mysql_fetch_array($result)){
							if ($prov=="") $prov=$rs[0];
                            echo"<option value='".$rs[0]."' $selected>".$rs[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
                            }
                    echo "</select>";
					?>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" class="titulo" >Desde:&nbsp;</td><td>&nbsp;
                    <input class="input"  name="desde" type="text" size="12" maxlength="15" readonly="readonly" id="cal-field-1" value="<?php echo date("Y/m/d");?>">
                    <button type="submit" id="cal-button-1" style="calendar">...</button>
                    <script type="text/javascript">
                        Calendar.setup({
                          inputField    : "cal-field-1",
                          button        : "cal-button-1",
                          align         : "Tr"
                        });
                    </script>
                    </td>
                    
                  </tr>
                  <tr><td align="left" class="titulo" >
                   Hasta:&nbsp;</td><td>&nbsp;
                    <input class="input"  name="hasta" type="text" size="12" maxlength="15" readonly="readonly" id="cal-field-2" value="<?php echo date("Y/m/d");?>">
                    <button type="submit" id="cal-button-2" style="calendar">...</button>
                    <script type="text/javascript">
                        Calendar.setup({
                          inputField    : "cal-field-2",
                          button        : "cal-button-2",
                          align         : "Tr"
                        });
                    </script>
                    </td>
                    
                  </tr>
                  <tr height="35">
                    <td align="LEFT" class="titulo">
                    
                    Tipo:&nbsp;<td>&nbsp;
                    <select name="tipo" class="input">
                    <option value="1" selected="selected">-----------LISTADO------------</option>
                    <option value="3">-----------PRODUCTOS----------</option>
                    <option value="2">----------- Resumen PRODUCTOS----------</option>
                    </select>
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="2" align="center">
                    <img src="images/buscars.jpg" title="Generar" width="20" height="20" onClick="valida(2);">&nbsp;&nbsp;
					<img src="images/xls.jpg" title="Generar" width="20" height="20" onClick="valida(1);">
                    </td>
                  </tr>
                </table>
                <?php
                include "main/close_table.php";
                ?>
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
