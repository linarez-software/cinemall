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
	document.form1.action="reporte_ventas3_diarias_his.php?tipo1=" + tipo1;
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
			$lb_title="Ventas";
			include "main/open_table.php";
			?>
                <table width="400" border="1" align="center">
                  <tr>
                    <td class="TITULO" colspan="2" align="center">Ventas</td>
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
