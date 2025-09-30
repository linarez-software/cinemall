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
	 tecla=(document.all) ? e.keyCode : e.which; 
 //tecla2=(e.keyCode);
	 
    if ((tecla>=48 && tecla<=57) || (tecla==8) || (tecla==9) || (tecla==13) || (tecla==241)|| (tecla==0)|| (tecla==209)|| (tecla==8) || (tecla==9) || (tecla==13) || (tecla==32) || (tecla==46)){  
       return true; 
    	} 
	else
		{ 
       return false; 
    	} 

}
function onNumero2(e){
	 tecla=(document.all) ? e.keyCode : e.which; 
 //tecla2=(e.keyCode);
	alert(tecla);
    if ((tecla>=48 && tecla<=57) || (tecla==8) || (tecla==9) || (tecla==13) || (tecla==241)|| (tecla==0)|| (tecla==209)|| (tecla==8) || (tecla==9) || (tecla==13) || (tecla==32)){  
       return true; 
    	} 
	else
		{ 
       return false; 
    	} 

}