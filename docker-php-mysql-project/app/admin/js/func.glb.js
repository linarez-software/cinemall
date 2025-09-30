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

function onNumero(e){
	tecla = (document.all) ? e.keyCode : e.which; 
    if (tecla==8) return true; 
    patron =/\d/; // 4
    te = String.fromCharCode(tecla); 
    return patron.test(te); 
}


function imprimir_reporte(url){
	//alert(url);
	window.open("imprimir_reporte.php?"+url, "Imprimiendo", "width=200,height=10,top=400,left=400,scrollbars=NO,titlebar=NO,resizable=NO,toolbar=NO,menubar=NO,directories=NO,location=NO,status=NO")
}
