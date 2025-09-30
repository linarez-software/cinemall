<?php

class paginado
{
	var $clave;
	var $cant_resultados;	
	var $cant_paginas;
	var $cant_por_pagina;	
	var $cant_columnas;	
	var $cant_links;	
	var $titulo_cols;	
	var $query;	

////////////////////////////////////////////////////////////////////////////////////////////////

	function paginado($Query,$Cant_por_pagina,$Cant_links,$Titulo_cols,$Clave)
	{
		$this->clave=$Clave;
		$this->titulo_cols=$Titulo_cols;
		$this->cant_links=$Cant_links;
		$this->query=$Query;
		
		include_once("conexion.php");
		$bd=new mySQL("localhost","intramercal","intramercal","UNQXj9Q79dlE7xQ4yN9u");//  
		$bd->conectarBD();
		$bd->ConsultarBD($this->query);
		
		$this->cant_resultados=$bd->numFilas();
		
		if ($Cant_por_pagina==0) 
		{
			if ($this->cant_resultados==0) $this->cant_por_pagina=10;
			else $this->cant_por_pagina=$this->cant_resultados;
		}
		else
		$this->cant_por_pagina=$Cant_por_pagina;		
		
		$this->cant_columnas=$bd->numColumnas();
		
		$aux=$this->cant_resultados/$this->cant_por_pagina;
		$this->cant_paginas=intval($aux)+(($aux-intval($aux))!=0?1:0);
		
		$bd->liberarConsulta();
		$bd->Cerrar();
		unset($bd);
	}	
	
////////////////////////////////////////////////////////////////////////////////////////////////
		
	function valida()
	{
		return ($this->cant_resultados)>0;
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////	
	
	function construir($numpag,$elim,$imp)
	{		
		if ($numpag>$this->cant_paginas) $numpag=1;
		$numpag--;
		
		include_once("conexion.php");
		
		$bd=new mySQL("localhost","intramercal","intramercal","UNQXj9Q79dlE7xQ4yN9u");	
		$bd->conectarBD();		
		$bd->ConsultarBD($this->query);	
		
		for ($i=0;$i<$numpag;$i++)
		for ($j=0;$j<$this->cant_por_pagina;$j++)
		$fila=$bd->Registros();				
		
		if ($elim)
		{
			print "	<form action=\"eliminar.php?cant=";		
			
			if (($numpag+1)==$this->cant_paginas && (($this->cant_resultados)%($this->cant_por_pagina))!=0)
				print (($this->cant_resultados)%($this->cant_por_pagina));
			else 
				print $this->cant_por_pagina;
			
			print "\" method=\"POST\" target=\"_self\">";
		}
				
		print "		<tr>";
		if ($elim) print "			<th style=\"border-left:1px #aaaaaa solid;\"><input type=\"image\" src=\"http://".$_SERVER['HTTP_HOST']."/intramercal/imagenes/eliminar.png\"></th>";
		
		
		for ($ij=0;$ij<$this->cant_columnas;$ij++)	
		{	
			print "			<th";
			if (!$elim && $ij==0)  print " style=\"border-left:1px #aaaaaa solid;\" ";
			print ">".$this->titulo_cols[$ij]."</th>";
		}
		
		print "		</tr>";
		
			for ($i=0;$i<$this->cant_por_pagina;$i++)
			{				
				if (!($fila=$bd->Registros())) break;

				print "		<tr>";
				if ($elim) print "			<td style=\"width: 18px;height: 18px; text-align: center; border-left: 1px #aaaaaa solid;\"><input type=\"checkbox\" style=\"width: 10px;height: 10px;\" name=\"check".$i."\" value=\"".$fila[$this->clave]."\"></td>"; 
				
				
					for ($j=0;$j<$this->cant_columnas;$j++)
					{
						print "			<td style=\"width:220px;\" ";
						if (!$elim && $j==0)  print "style=\"border-left:1px #aaaaaa solid;\" ";
						print "class=\"td".$j."\">";
						if (!$imp) print "				<a class=\"lista\" href=\"consultadetalle.php?nu=".$fila[$this->clave]."\">";
						print $fila[$j];
						if (!$imp) print "</a>";
						print "</td>";
					}
				
							
				print "		</tr>";				
				
			}
		
		$bd->liberarConsulta();
		$bd->Cerrar();
		unset($bd);
		
		if ($elim)print "	</form>";	
		print "	</table>";
		
		
		if ($this->cant_resultados>$this->cant_por_pagina)
		{
			print "	<table id=\"piet\" width=\"575px\" align=\"center\">";
			print "		<tr>";
			print "			<td class=\"pien4\" align=\"center\">Pag: ".($numpag+1)."/".$this->cant_paginas."<br>Cant: ".$this->cant_resultados."</td>";
			print "			<td align=\"right\">";
			
			if ($numpag>0) print "				<a href=\"consultalista.php?c1=".$_GET["c1"]."&c2=".$_GET["c2"]."&c3=".$_GET["c3"]."&c4=".$_GET["c4"]."&c5=".$_GET["c5"]."&c6=".$_GET["c6"]."&c7=".$_GET["c7"]."&pag=".($numpag)."\"><img src=\"http://".$_SERVER['HTTP_HOST']."/intramercal/imagenes/izquierda.png\"></a>";
			else print "				<img src=\"http://".$_SERVER['HTTP_HOST']."/intramercal/imagenes/izquierda.png\">";
			
			print "			</td>";
			print "			<td align=\"center\" width=\"".(($this->cant_links*2+1)*40)."px\">";
			
			
			
			print "				<table id=\"ppie\">";
			print "					<tr>";
			
			for ($ii=$numpag-$this->cant_links+1;$ii<=$numpag;$ii++)
			if ($ii>0)
			print "						<td class=\"pien\"><a href=\"consultalista.php?c1=".$_GET["c1"]."&c2=".$_GET["c2"]."&c3=".$_GET["c3"]."&c4=".$_GET["c4"]."&c5=".$_GET["c5"]."&c6=".$_GET["c6"]."&c7=".$_GET["c7"]."&pag=".$ii."\">".$ii."</a></td>";
			else 
			print "						<td class=\"pien2\">-</td>";		
					
			print "						<td class=\"pien3\">".($numpag+1)."</td>";
			
			for ($ii=$numpag+2;$ii<=$numpag+$this->cant_links+1;$ii++)
			if ($ii<$this->cant_paginas+1)
			print "						<td class=\"pien\"><a href=\"consultalista.php?c1=".$_GET["c1"]."&c2=".$_GET["c2"]."&c3=".$_GET["c3"]."&c4=".$_GET["c4"]."&c5=".$_GET["c5"]."&c6=".$_GET["c6"]."&c7=".$_GET["c7"]."&pag=".$ii."\">".$ii."</a></td>";
			else 
			print "						<td class=\"pien2\">-</td>";
			
			print "					</tr>";
			print "				</table>";
			
			print "			</td>";
			print "			<td align=\"left\">";
			
			
			
			if ($numpag<$this->cant_paginas-1) print "				<a href=\"consultalista.php?c1=".$_GET["c1"]."&c2=".$_GET["c2"]."&c3=".$_GET["c3"]."&c4=".$_GET["c4"]."&c5=".$_GET["c5"]."&c6=".$_GET["c6"]."&c7=".$_GET["c7"]."&pag=".($numpag+2)."\"><img src=\"http://".$_SERVER['HTTP_HOST']."/intramercal/imagenes/derecha.png\"></a>";
			else print"				<img src=\"http://".$_SERVER['HTTP_HOST']."/intramercal/imagenes/derecha.png\">";		
	
			
			print "			</td>		</tr>";
		}
	
		
	}

}
?>