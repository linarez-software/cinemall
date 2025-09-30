function createRequestObject(){
	var request_o; //declarar la variable para manejar el objeto.
	var browser = navigator.appName; //obtener el nombre del navegador.
	if(browser == "Microsoft Internet Explorer"){
		/* Crear el objeto usando el metodo de MSIE. */
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		/* Crear el objeto usando el metodo para otros navegadores. */
		request_o = new XMLHttpRequest();
	}
	return request_o; //devolver el objeto
}
/* La variable http manejara nuestro objeto XMLHttpRequest. */
var http = createRequestObject(); 

/* Función  llamada para obtener la lista de categorias de productos */
function getProducts(){
	indice = document.form1.GRUPO.selectedIndex;
	http.open('get', 'add_producto.php?insert=1&action=1&id='+ document.form1.GRUPO.options[indice].value);
	http.onreadystatechange = handleProducts; 
	http.send(null);
}

/* Función llamada para manipular la lista que fue devuelta por el archivo internal_request.php. */
function handleProducts(){
	if(http.readyState == 4){ //Finalizó la carga de la respuesta.
		var response = http.responseText;
		document.getElementById('product_cage').innerHTML = response;
	}else if(http.readyState == 1){
		document.getElementById('product_cage').innerHTML = 'Cargando...';
	}
}
