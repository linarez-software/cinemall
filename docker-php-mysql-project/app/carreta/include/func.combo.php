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
	$qry .= "WHERE activo = $activos ";
endif;
$qry .= "ORDER BY nombre ";
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

function combo_censura2($activos = -1, $seleccion = ""){
$qry = "SELECT nomenclatura, nombre FROM tbl_censura ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos ";
endif;
$qry .= "ORDER BY nomenclatura ";
//echo $qry ;
$result = mysql_query($qry) or die("Error 003-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='censura' class='css-campo-metadata'>";
$tabla .= "<option value='' selectd >- Censuras -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->nomenclatura):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->nomenclatura' $select >$campo->nombre</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}


function combo_cine($activos = -1, $seleccion = 0, $sala = 0){
$qry = "SELECT id_cine, nombre FROM tbl_cine ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 004-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select id='cine' name='cine' class='css-campo-metadata' ";
$tabla .= ($sala==1) ? "onChange='javascript:cargaSalaCine()'>" : ">";
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

function combo_sala($activos = -1, $seleccion = 0, $cine = 0){
$qry_where = "";
$qry = "SELECT id_sala, nombre FROM tbl_sala ";
if ($activos > -1):
	$qry_where .= "WHERE activo = $activos ";
endif;

if(strlen($qry_where)>0	):
	$qry_where .= ($cine > 0) ? "AND id_cine = $cine " : "";
else:
	$qry_where .= ($cine > 0) ? "WHERE id_cine = $cine " : "";
endif;

$qry .= $qry_where;
//echo $qry ;
if($cine == 0):
	$tabla = "";
	$tabla .= "<select id='sala' name='sala' class='css-campo-metadata' disabled='disabled'> \n";
	$tabla .= "<option value=''>- Seleccione un Cine -</option> \n";
	$tabla .= "</select> \n";
else:
	$result = mysql_query($qry) or die("Error 004-func.sala - ".mysql_error());
	$tabla = "";
	$tabla .= "<select id='sala' name='sala' class='css-campo-metadata'>";
	$tabla .= "<option value='0' selectd >- Salas -</option>";
	while($campo = mysql_fetch_object($result)):
		if($seleccion == $campo->id_sala):
			$select = 'selected';
		else:
			$select = '';
		endif;
		$tabla .= "<option value='$campo->id_sala' $select >$campo->nombre</option>";
	endwhile;
	$tabla .= "</select>";
endif;
return $tabla;
}

function combo_usuario($activos = -1, $seleccion = 0){
$qry = "SELECT id_usuario, nombre_completo FROM tbl_usuario ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos";
endif;
//echo $qry ;
$result = mysql_query($qry) or die("Error 005-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='username' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- usuario -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_usuario):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_usuario' $select >$campo->nombre_completo</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}

function combo_usuario_registro($activos = -1, $seleccion = 0){
$qry = "SELECT DISTINCT o.usuario_registro, u.nombre_completo ";
$qry .= "FROM tbl_operacion o INNER JOIN tbl_usuario u ON o.usuario_registro = u.username ";
$qry .= ($activos > -1) ? "WHERE activo = $activos " : "";
$qry .= "ORDER BY u.nombre_completo ";

//echo $qry ;
$result = mysql_query($qry) or die("Error 005-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='usuario_registro' class='css-campo-metadata'>";
$tabla .= "<option value='0' selectd >- usuario -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->usuario_registro):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->usuario_registro' $select >$campo->usuario_registro - $campo->nombre_completo</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}


function combo_nivel($seleccion = 0, $nombre = 'nivel'){
$tabla = "";
$tabla .= "<select name='$nombre' class='css-campo-metadata'>";
$tabla .= "<option value='0' selected >- Nivel -</option>";
$tabla .= "<option value='1' ".(($seleccion == 1) ? ' selected' : '') . ">Administrador</option>";
$tabla .= "<option value='2' ".(($seleccion == 2) ? ' selected' : '') . ">Supervisor</option>";
$tabla .= "<option value='3' ".(($seleccion == 3) ? ' selected' : '') . ">Programador</option>";
$tabla .= "<option value='4' ".(($seleccion == 4) ? ' selected' : '') . ">Taquilla</option>";

$tabla .= "<option value='5' ".(($seleccion == 5) ? ' selected' : '') . ">Caramelos</option>";
$tabla .= "</select>";

return $tabla;
}


function combo_pelicula($activos = -1, $seleccion = 0){
$qry = "SELECT id_pelicula, nombre_espanol FROM tbl_pelicula ";
if ($activos > -1):
	$qry .= "WHERE activo = $activos ";
endif;
$qry .= "ORDER BY nombre_espanol ";
//echo $qry ;
$result = mysql_query($qry) or die("Error 006-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='pelicula' id='pelicula' class='css-campo-metadata'>";
$tabla .= "<option value='0' selected >- Pel&iacute;culas -</option>";
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


function combo_hora($seleccion = 0, $nombre = 'hora', $js = ''){
$tabla = "";
$tabla .= "<select name='$nombre' id='$nombre' class='css-campo-metadata' $js>";
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
$tabla .= "<option value='12' ".(($seleccion == '12') ? ' selected' : '') . ">12</option>";

$tabla .= "</select>";

return $tabla;
}

function combo_minuto($seleccion = -1, $nombre = 'minuto', $js = ''){
$tabla = "";
$tabla .= "<select name='$nombre' id='$nombre' class='css-campo-metadata' $js>";
$tabla .= "<option value='-1' selected >- Min -</option>";
for($i=0; $i<=55; $i=$i+5){
	$valor = $i;
	if($i <	10) $valor = '0'.$i;
	$tabla .= "<option value='$valor' ".(($seleccion == $valor) ? ' selected' : '') . ">$valor</option>";
}

$tabla .= "</select>";

return $tabla;
}

function combo_funcion($seleccion = -1, $nombre = 'funcion'){
$tabla = "";
$tabla .= "<select name='$nombre' class='css-campo-metadata'>";
$tabla .= "<option value='-1' selected >- Todas -</option>";
//$tabla .= "<option value='".DIURNO."' ".(($seleccion == $valor) ? ' selected' : '') . ">Diurno</option>";
$tabla .= "<option value='".MATINEE."' ".(($seleccion == $valor) ? ' selected' : '') . ">Matinee</option>";
$tabla .= "<option value='".VESPERTINA."' ".(($seleccion == $valor) ? ' selected' : '') . ">Vespertina</option>";
$tabla .= "<option value='".INTERMEDIA."' ".(($seleccion == $valor) ? ' selected' : '') . ">Intermedia</option>";
$tabla .= "<option value='".NOCHE."' ".(($seleccion == $valor) ? ' selected' : '') . ">Noche</option>";
$tabla .= "<option value='".MEDIANOCHE."' ".(($seleccion == $valor) ? ' selected' : '') . ">Media Noche</option>";

$tabla .= "</select>";

return $tabla;
}

function combo_funcion_distribuidor($seleccion = -1, $nombre = 'funcion'){
$tabla = "";
$tabla .= "<select name='$nombre' class='css-campo-metadata'>";
$tabla .= "<option value='-1' selected >- Todas -</option>";

$tabla .= "<option value='".MATINEE."' ".(($seleccion == $valor) ? ' selected' : '') . ">Matinee</option>";
$tabla .= "<option value='".VIN."' ".(($seleccion == $valor) ? ' selected' : '') . ">V-I-N</option>";

$tabla .= "</select>";

return $tabla;
}

function combo_tipo($seleccion = 0, $nombre = 'tipo'){
$qry = "SELECT id_tipo, nombre_tipo FROM tbc_tipo ";
//echo $qry ;
$result = mysql_query($qry) or die("Error 010-func.combo - ".mysql_error());
$tabla = "";
$tabla .= "<select name='tipo' id='tipo' class='css-campo-metadata'>";
$tabla .= "<option value='0' selected >- Tipo -</option>";
while($campo = mysql_fetch_object($result)):
	if($seleccion == $campo->id_tipo):
		$select = 'selected';
	else:
		$select = '';
	endif;
	$tabla .= "<option value='$campo->id_tipo' $select >$campo->nombre_tipo</option>";
endwhile;
$tabla .= "</select>";

return $tabla;
}
?>