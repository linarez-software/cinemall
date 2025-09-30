<style type="text/css">
	#tabla-precios {
 display:inline-block;
 width:98%;
 align-self: center;
 margin-top:0px;
 		
 display: flex;
  justify-content: center;
  align-items: center;
}

/*Columnas*/

.precio-col {
 display:inline-block;
 background-color:#f3f3f3;
 width:100%;
 max-width:500px;
 border-radius:10px;
 margin-bottom:50px;
 box-shadow: 0px 2px 5px #ddd
}

@media screen and (min-width:768px) {
 .precio-col {
 width:32%;
 float:left;
 margin-right:2%;
 height: 65vh;
 margin-top: 20px;
 vertical-align: middle;
 -webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #000, 0 5px 0 #aaa, 0 6px 1px rgba(0,0,0,.1), 0 0 5px rgba(0,0,0,.1), 0 1px 3px rgba(0,0,0,.3), 0 3px 5px rgba(0,0,0,.2), 0 5px 10px rgba(0,0,0,.25), 0 10px 10px rgba(0,0,0,.2), 0 20px 20px rgba(0,0,0,.15);



 }
 
 .precio-col:last-child {
 margin-right:0
 }
}

/*Headers*/

.precio-col-header {
 background-color:#333;
 padding:10px;
 border-top-left-radius:10px;
 border-top-right-radius:10px
}

.precio-col:nth-child(2) .precio-col-header {
 background-color:#dd9933
}

.precio-col-header h3 {
 color:#f3f3f3;
 text-align:center;
 font-size:30px;
 font-weight:600;
 margin-bottom:0;
 margin-top: 0%;
}

.precio-col-header p {
 text-align:center;
 color:#f3f3f3;
 font-size:34px;
 margin-bottom:0
}

/*Características*/

.precio-col-features {
 padding: 0 20px 20px 20px

}

.precio-col-features p {
 padding:20px 0;
 margin:0;
 text-align:center;
 font-size:26px;
 margin-top: 5%;
 font-weight: bold;
 border-top:1px solid #ddd
}

.precio-col-features p:first-child,
.precio-col-features p:last-child {
 border-top:none
}

/*Comprar*/

.precio-col-comprar {
 padding:10px;
 max-width:250px;
 text-align:center;
 background-color:#dd9933;
 margin: 0 auto 20px;
 border-radius:10px;
 border: 2px solid #dd9933;
 transition: all 0.3s
}

.precio-col-comprar a {
 color:#f3f3f3;
 padding:10px;
 font-size:20px;
 text-transform:uppercase;
 transition: all 0.3s
}

.precio-col-comprar:hover {
 background-color:#f3f3f3;
 transition: all 0.3s
}

.precio-col-comprar:hover a {
 color:#dd9933;
 transition: all 0.3s
}
p {
  width: 0;
  overflow: hidden;
  white-space: nowrap;
  font-size: 2rem;
  margin: 0 auto;
  border-right: 0em solid #18bdec;
  animation: typing 2s steps(38) 0s 1 normal both, blink 1s steps(1) infinite;
}
@keyframes typing {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}
@keyframes blink {
  80% {
    border-color: transparent;
  }
}
/* Animación */

@keyframes beat {
  0%, 50%, 100% { transform: scale(0); }
  50%, 40% { transform: scale(1); }
}


/* Corazón */

.heart{
    position: relative;
    width: 300px;
    height: 400px;
    animation: 10.5s ease 0s infinite beat;
}
.heart:before,
.heart:after{
    position: absolute;
    content: "";
    left: 50px;
    top: 0;
    width: 300px;
    height: 400px;
    background: #fc2e5a;
    border-radius: 50px 50px 0 0;
    transform: rotate(-45deg);
            transform-origin: 0 100%;
}
.heart:after{
    left: 0;
    transform: rotate(45deg);
    transform-origin :100% 100%;
}

</style>
<?php

	
$qry = "SELECT
pv_tbc_productos.DESCRIPCION_PRODUCTO,
pv_tbc_productos.PRECIO
FROM
pv_tbc_productos
where precio>0  and listado=1  in (1,2,3,4,5,6,7,9,14,21,22,23,26,28,31,32,34,35,39,50,52,53,54,56,57,60,63,64,65,66,101,3,418)
order by 1  
";
$result = mysql_query($qry);
$peliculas = mysql_num_rows($result);
//$intervalo = intval($peliculas/2);
$intervalo = $peliculas;
if($_SESSION["intermax"]==""){
	 $_SESSION["intermax"]=3;
	 $_SESSION["intermin"]=0;
	 
	 }
else
	{
	 $_SESSION["intermax"]+=3;
	 $_SESSION["intermin"]+=3;
		 if($_SESSION["intermin"]>=$intervalo){
		 $_SESSION["intermax"]=3;
		 $_SESSION["intermin"]=0;
		 
		 }
	 }
$qry = "SELECT pv_tbc_productos.DESCRIPCION_PRODUCTO, pv_tbc_productos.PRECIO FROM pv_tbc_productos where precio>0 and listado=1 and CODIGO_PRODUCTO in   (1,2,3,4,5,6,7,9,14,21,22,23,26,28,31,32,34,35,39,50,52,53,54,56,57,60,63,64,65,66,101,3,418) order by 1
limit ".$_SESSION["intermin"].", 3";


$programacion = mysql_query($qry);
$nro_productos = mysql_num_rows($programacion);
if($nro_productos<3){
	$qry="delete from aux_pro;";
	$result = mysql_query($qry);
	for($i=1;$i<=(3-$nro_productos);$i++)
	{
		
		$qry="insert into aux_pro values('ZZZZZZZZZZZ$i','');";
		$result = mysql_query($qry);
	}
	$qry = "select * from (select * from (SELECT pv_tbc_productos.DESCRIPCION_PRODUCTO, pv_tbc_productos.PRECIO FROM pv_tbc_productos where precio>0  and listado=1 and CODIGO_PRODUCTO   in (1,2,3,4,5,6,7,9,14,21,22,23,26,28,31,32,34,35,39,50,52,53,54,56,57,60,63,64,65,66,101,3,418) order by 1 limit ".	$_SESSION["intermin"].", 3)b 
		union select * from aux_pro )a ";
		
		$programacion = mysql_query($qry);
}
?>

<?php 
$table = "";
$par = 0;
$i=1;
$tr_fondo = "par";
?>
 
<?php


while($campo = mysql_fetch_object($programacion)):
			$par=1;
		if ($i==1) {
			$table.='<div id="tabla-precios" align="center">';
		}
			$ddd=substr($campo->DESCRIPCION_PRODUCTO, 0,29)."<br>&nbsp;".substr($campo->DESCRIPCION_PRODUCTO, 29,30000);
		$table .= "<div class='precio-col'>
			 <div class='precio-col-header area'>
			 <h3>".number_format($campo->PRECIO,2)." $</h3>
			 </div>

			<div class='precio-col-features'>
			 <p>$ddd</p>
			 <img id='elemento$i' src='$i.jpg' class='heart' width='300px' height='400px'  >
			 </div>


			 </div>\n"; 
	    $i++;
		if ($i==4) {
			$table.='</div>';
			$i=1;
		}	 
endwhile;
if ($i<4) {
			$table.='</div>';
		}	
	
if ($par==0) {
	 		$_SESSION["intermax"]="";
			echo"<script>document.location.reload();</script>";
			die();
		}	
echo utf8_encode($table);
?>

