// Funciones de Javascript
// Fecha: 23/10/2006
// Hecho por: Oscar A. Cánchica
function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 
	
	return xmlhttp; 
}

function cargaSalaCine()
{
	var valor=document.getElementById("cine").options[document.getElementById("cine").selectedIndex].value;
	
	ajax=nuevoAjax();
	ajax.open("GET", "include/func.ajax.php?fn=1&cine="+valor, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==1)
		{
			// Mientras carga elimino la opcion "Elige pais" y pongo una que dice "Cargando"
			combo=document.getElementById("sala");
			combo.length=0;
			var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
			combo.appendChild(nuevaOpcion); combo.disabled=false;	
		}
		if (ajax.readyState==4)
		{ 
			document.getElementById("td_sala").innerHTML=ajax.responseText;
		} 
	}
	ajax.send(null);
}
function actualizarEntrada(programacion, precio, actual){
	var valor=document.getElementById("ent"+programacion+precio).value;
	
	//alert(valor);
	
	ajax=nuevoAjax();
	ajax.open("GET", "include/func.ajax.php?fn=2&programacion="+programacion+"&precio="+precio+"&actual="+actual+"&valor="+valor, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==1)
		{
 
		}
		if (ajax.readyState==4)
		{ 
			document.filtro.submit();
		} 
	}
	ajax.send(null);
}

function cargarFunciones(pelicula, fecha){
		
	ajax=nuevoAjax();
	ajax.open("GET", "include/func.ajax.php?fn=3&pelicula="+pelicula+"&fecha="+fecha, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==1)
		{
		
		}
		if (ajax.readyState==4)
		{ 
			document.getElementById("div_hora").innerHTML=ajax.responseText;
		} 
	}
	ajax.send(null);
}


function funcionRepetida(){
	
	var fecha=document.getElementById("fecha").value;
	var sala=document.getElementById("ids").value;
	var cine=document.getElementById("idc").value;
	var pelicula=document.getElementById("pelicula").options[document.getElementById("pelicula").selectedIndex].value;
	var hora=document.getElementById("hora_i").options[document.getElementById("hora_i").selectedIndex].value;
	var minu=document.getElementById("minu_i").options[document.getElementById("minu_i").selectedIndex].value;
	
	ajax=nuevoAjax();
	var url = "include/func.ajax.php?fn=4&cine="+cine+"&pelicula="+pelicula+"&fecha="+fecha+"&sala="+sala+"&hora="+hora+"&minu="+minu;
	//alert(url);
	ajax.open("GET", url, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4)
		{ 
			document.getElementById("libre").value=ajax.responseText;
		} 
	}
	ajax.send(null);
}