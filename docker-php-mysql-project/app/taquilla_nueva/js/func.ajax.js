// Funciones AJAX

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

function venta_temp()
{
	var session=document.getElementById("session").value;
	var username=document.getElementById("username").value;
	var funcion=document.getElementById("funcion").value;
	var precio=document.getElementById("precio").value;
	var costo=document.getElementById("costo").value;
	var boletos=document.getElementById("boletos").value;
	
	var url = "session=" + session + "&username=" + username + "&funcion=" + funcion + "&precio=" + precio + "&costo=" + costo + "&boletos=" + boletos;
	ajax=nuevoAjax();
	ajax.open("GET", "venta-temp.php?"+url, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4)
		{ 
			document.close()
		} 
	}

	ajax.send(null);
}

function guardarVenta(precio, costo, boletos)
{
	var session=document.getElementById("session").value;
	var username=document.getElementById("username").value;
	var funcion=document.getElementById("funcion").value;
	//var precio=document.getElementById("precio").value;
	//var costo=document.getElementById("costo").value;
	//var boletos=document.getElementById("boletos").value;
	var url = "session=" + session + "&username=" + username + "&funcion=" + funcion + "&precio=" + precio + "&costo=" + costo + "&boletos=" + boletos;
	//alert(url);
	ajax=nuevoAjax();
	ajax.open("GET", "venta-temp.php?"+url, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4)
		{ 
			//document.close()
			document.location.reload();
		} 
	}

	ajax.send(null);
}

function BorrarItem(xItem)
{	
	ajax=nuevoAjax();
	ajax.open("GET", "include/func.ajax.php?fn=1&item="+xItem, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4)
		{ 
			//document.getElementById("tabla_led").innerHTML=ajax.responseText;
			document.location.reload()
		} 
	}

	ajax.send(null);
}

function apartaButaca(butaca, funcion, operacion, session){
	ajax=nuevoAjax();
	ajax.open("GET", "include/func.ajax.php?fn=2&but="+butaca+"&idf="+funcion+"&ido="+operacion+"&s="+session, true);
	ajax.onreadystatechange=function() 
	{ 
		if (ajax.readyState==4)
		{ 
			document.location.reload();
		} 
	}
	ajax.send(null);
}