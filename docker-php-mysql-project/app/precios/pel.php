<?php
session_start();
include_once "../include/bd.inc.php";
include_once "include/config.inc.php";
include_once "include/func.glb.php";

	
$qry = "SELECT *
FROM
pv_tbc_productos
where precio>0  and listado=1
";

$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
//$intervalo = intval($peliculas/2);
$intervalo = $peliculas;
echo $_SESSION["intermin"]."////<br>";
if($_SESSION["intermin"]==""){
	 $_SESSION["intermin"]=0;
	 echo $_SESSION["intermin"]." a ////<br>";
	 }
else
	{
	echo $_SESSION["intermin"]." b ////<br>";
	 $_SESSION["intermin"]+=20;
		 if($_SESSION["intermin"]>$intervalo){
		 $_SESSION["intermin"]=0;
		 echo $_SESSION["intermin"]." c ////<br>";
		 
		 }
	 }
$qry = "SELECT pv_tbc_productos.DESCRIPCION_PRODUCTO, pv_tbc_productos.PRECIO FROM pv_tbc_productos where precio>0 and listado=1  order by 1
limit ".$_SESSION['intermin'].", 20";
echo $qry; 

$programacion = mysql_query($qry);
$nro_productos = mysql_num_rows($programacion);
if($nro_productos<20){
	$qry="delete from aux_pro;";
	$result = mysql_query($qry);
	for($i=1;$i<=(20-$nro_productos);$i++)
	{
		
		$qry="insert into aux_pro values('ZZZZZZZZZZZ$i','');";
		$result = mysql_query($qry);
	}
	$qry = "select * from (select * from (SELECT pv_tbc_productos.DESCRIPCION_PRODUCTO, pv_tbc_productos.PRECIO FROM pv_tbc_productos where precio>0  and listado=1   order by 1 limit ".$_SESSION['intermin'].", 20)b 
		union select * from aux_pro )a ";
		
		$programacion = mysql_query($qry);
}

?>

<table id="tabla-pelicula" width="100%" height="75%" cellpadding="0" cellspacing="0" border="0">
<?php 
$table = "";
$par = 0;
$i=1;
$tr_fondo = "par";
while($campo = mysql_fetch_object($programacion)):
	
		
		if ($i==1)	{
					$tr_fondo=(($par % 2 )==0) ?  "" : "par";
					$table .= "<tr class='pelicula $tr_fondo'> \n"; 
					$par ++;
					
			}
		//$table .= "<table border='0' height='40' width='100%' cellpadding='2' cellspacing='0' align='left' bgcolor='#0066CC' bordercolor='#000000'>\n <tr>\n";
		if(substr($campo->DESCRIPCION_PRODUCTO,1,3)=="ZZZ")
		{
			$table .= "<td width='35%' align='left' class='pelicula borde-abajo borde-derecho' >&nbsp;	</td> \n";
			$table .= "<td width='10%'align='right' class='pelicula borde-abajo borde-derecho' >&nbsp;</td> \n";
			
		}else{
		$table .= "<td width='35%' align='left' class='pelicula borde-abajo borde-derecho' >$campo->DESCRIPCION_PRODUCTO</td> \n";
		$table .= "<td width='10%'align='right' class='pelicula borde-abajo borde-derecho' >".number_format($campo->PRECIO,2)."&nbsp;&nbsp;&nbsp;</td> \n";
		}
		if ($i!=1)
			{
			$i=0;
			$table .= "</tr> \n"; 
			}
		else{
		$table .= "<td width='1%' align='left' bgcolor='#000000' >&nbsp;</td>\n";
		}
		$i=($i==1)? 0 : 1; 
endwhile;
echo $table;
?>
</table>