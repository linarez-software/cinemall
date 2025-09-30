<?php   
		include("main/conexiones.php");
				$sql="select *  from pv_tbc_inventarios_toma";
				echo $sql;
				$result = mysql_query($sql);
				while ($fila=mysql_fetch_array($result)){
				
					$sql="update pv_tbc_inventarios_toma set EXISTENCIA_PRODUCTO ='$fila[4]' where id=$fila[0];";
					//echo $sql."<br>";
					$result2 = mysql_query($sql);
				}
				echo"<script>alert('Inventario Actualizado');</script>";
				echo"<script>document.location.href='linventarios2.php';</script>";
		
?>