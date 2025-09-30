<?php
/*
Proyecto: Cine Plus
Archivo: func.combo.php
Asunto: Carga de Combos 
Programador: Oscar A. Cánchica
*/

function combo_distribuidor($activos = -1, $seleccion = 0){
$qry = "SELECT id_distribuidor, nombre FROM tbl_distribuidor ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 001-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='distribuidor' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- Distribuidores -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_distribuidor):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_distribuidor' $select >$campo->nombre</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}

function combo_genero($activos = -1, $seleccion = 0){
$qry = "SELECT id_genero, nombre FROM tbl_genero ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 002-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='genero' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- Generos -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_genero):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_genero' $select >$campo->nombre</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}

function combo_censura($activos = -1, $seleccion = 0){
$qry = "SELECT id_censura, nombre FROM tbl_censura ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 003-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='censura' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- Censuras -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_censura):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_censura' $select >$campo->nombre</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}

function combo_cine($activos = -1, $seleccion = 0){
$qry = "SELECT id_cine, nombre FROM tbl_cine ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 004-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='cine' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- Cines -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_cine):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_cine' $select >$campo->nombre</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}

function combo_nivel($seleccion = 0){
$tabla = "";
$tabla .= "<select name='cine' class='css-campo-metadata'>";
$tabla .= "<option value='0' selected >- Nivel -</option>";
$tabla .= "<option value='1' ".(($seleccion == 1) ? ' selected' : '') . ">Administrador</option>";
$tabla .= "<option value='2' ".(($seleccion == 2) ? ' selected' : '') . ">Usuario</option>";
$tabla .= "</select>";

return $tabla;
}


function combo_pelicula($activos = -1, $seleccion = 0){
$qry = "SELECT id_pelicula, nombre_espanol FROM tbl_pelicula ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 006-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='pelicula' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- Películas -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_pelicula):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_pelicula' $select >$campo->nombre_espanol</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}


function combo_hora($seleccion = 0, $nombre = 'hora'){
$tabla = "";
$tabla .= "<select name='$nombre' class='css-campo-metadata'>";
$tabla .= "<option value='0' selected >- Hora -</option>";
$tabla .= "<option value='01' ".(($seleccion == '01') ? ' selected' : '') . ">01</option>";
$tabla .= "<option value='02' ".(($seleccion == '02') ? ' selected' : '') . ">02</option>";
$tabla .= "<option value='03' ".(($seleccion == '03') ? ' selected' : '') . ">03</option>";
$tabla .= "<option value='04' ".(($seleccion == '04') ? ' selected' : '') . ">04</option>";
$tabla .= "<option value='05' ".(($seleccion == '05') ? ' selected' : '') . ">05</option>";
$tabla .= "<option value='06' ".(($seleccion == '06') ? ' selected' : '') . ">06</option>";
$tabla .= "<option value='07' ".(($seleccion == '07') ? ' selected' : '') . ">07</option>";
$tabla .= "<option value='08' ".(($seleccion == '08') ? ' selected' : '') . ">08</option>";
$tabla .= "<option value='09' ".(($seleccion == '09') ? ' selected' : '') . ">09</option>";
$tabla .= "<option value='10' ".(($seleccion == '10') ? ' selected' : '') . ">10</option>";
$tabla .= "<option value='11' ".(($seleccion == '11') ? ' selected' : '') . ">11</option>";
$tabla .= "<option value='12' ".(($seleccion == '11') ? ' selected' : '') . ">11</option>";

$tabla .= "</select>";

return $tabla;
}

function combo_minuto($seleccion = -1, $nombre = 'minuto'){
$tabla = "";
$tabla .= "<select name='$nombre' class='css-campo-metadata'>";
$tabla .= "<option value='-1' selected >- Min -</option>";
for($i=0; $i<=55; $i=$i+5){
	$valor = $i;
	if($i <	10) $valor = '0'.$i;
	$tabla .= "<option value='$valor' ".(($seleccion == $valor) ? ' selected' : '') . ">$valor</option>";
}

$tabla .= "</select>";

return $tabla;
}

?>