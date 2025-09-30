// Funciones Javascript	

function consulta_seleccion(fila, valor, fondo){
	//Fila: Es el Tr donde estamos parados
	//Valor: Indica si es OFF u ON
	
	if(valor = 0){
		fila.style.backgroundColor = fondo;
	} else {
		fila.style.backgroundColor = '#FFCC99';
	}		
	
}

function onNumero(){
	var key=window.event.keyCode;//codigo de tecla. 
	if(key < 48 || key > 57){//si no es numero  
		window.event.keyCode=0;//anula la entrada de texto. 
	}	
}

function imprimir_reporte(url){
	//alert(url);
	window.open("imprimir_reporte.php?"+url, "Imprimiendo", "width=200,height=10,top=400,left=400,scrollbars=NO,titlebar=NO,resizable=NO,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO")
}
