<?php
$qry = "SELECT DISTINCT * ";
$qry .= "FROM pv_tbc_productos p ";
$qry .= "WHERE  foto<>'' and listado=1";
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
//$intervalo = intval($peliculas/2);
$intervalo = 1;

if(!isset($_SESSION["intermin2"])){
	 $_SESSION["intermin2"]=0;
	 }
else
	{
	$_SESSION["intermin2"]+=1;
		 if($_SESSION["intermin2"]>=$peliculas){
		 	$_SESSION["intermin2"]=0;
		 }
	 }
$qry = "SELECT DISTINCT * ";
$qry .= "FROM ";
$qry .= "  pv_tbc_productos p ";
$qry .= "WHERE  ";
$qry .= " listado=1 and foto<>'' ";
$qry .= "ORDER BY DESCRIPCION_PRODUCTO ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";

$programacion = mysql_query($qry);
?>
<div class="image">
<table id="tabla-pelicula" width="95%" height="100%" cellpadding="0" cellspacing="0" border="0">
<?php 
$table = "";
$par = 0;
for($p=1; $p<=$intervalo; $p++):
	if($campo = mysql_fetch_object($programacion)):
		$table .= "<tr> \n"; 
		$table .= "<td height='20%' align='center' class='pelicula' colspan=7><img width='100%' height='100%' src='../inventario/$campo->foto' heoght='95%'></td> \n";
		$table .= "</tr> \n";
		$precio=number_format($campo->precio,2);
	
		$DESCRIPCION_PRODUCTO=$campo->DESCRIPCION_PRODUCTO;
	
	endif;
endfor;

echo $table;
?>
</table>
<h2><span><?php echo $DESCRIPCION_PRODUCTO;?><br /></span><div class="prec" ><?php echo $precio;?></div></h2>
</div>