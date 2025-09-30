<?php
$qry = "SELECT
tbl_pelicula.nombre_espanol,
tbl_pelicula.video,
tbl_pelicula.listar_video
FROM
tbl_pelicula
where listar_video=2 and video<>''";

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
$qry = "SELECT DISTINCT f.id_pelicula, p.nombre_espanol, p.nombre_corto, c.nomenclatura, p.sinopsis,video ";
$qry .= "FROM tbl_programacion f ";
$qry .= "INNER JOIN tbl_pelicula p ON f.id_pelicula = p.id_pelicula ";
$qry .= "LEFT JOIN tbl_censura c ON p.id_censura = c.id_censura ";
$qry .= "WHERE fecha_programacion = '$fecha' ";
$qry .= "AND f.id_cine = ".CINE_TAQUILLA ." and foto<>'' ";
$qry .= "ORDER BY p.nombre_corto ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";

$qry = "SELECT DISTINCT video ";
$qry .= "FROM  ";
$qry .= " tbl_pelicula ";
$qry .= "WHERE listar_video=2 and video<>'' ";
$qry .= "ORDER BY id_pelicula ";
$qry .= "LIMIT ".$_SESSION['intermin2'].",1 ";


$programacion = mysql_query($qry);
?>

<?php 
$table = "";
$par = 0;
for($p=1; $p<=$intervalo; $p++):
	if($campo = mysql_fetch_object($programacion)):
		$pelicula = "../admin/$campo->video";
	endif;
endfor;
?>
<body> 


<form name="form1">
<div align="center" valign='center'>
<video id="myVideo" src="<?php echo $pelicula;?>" width="100%" height="768" autoplay controls>
  
</video>
</div>
<script>
var vid = document.getElementById("myVideo");
vid.onended = function() {
   document.form1.submit();
};
</script>


</form>
</body> 