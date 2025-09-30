// JavaScript Document
// Funciones Globales

function getkey(e){
	if (window.event) {
		shift= event.shiftKey;
		ctrl= event.ctrlKey;
		alt=event.altKey;
		return window.event.keyCode;
	}
	else if (e) {
		var valor=e.which;
		if (valor>96 && valor<123) {
			valor=valor-32;
		}
		return valor;
	}
	else
		return null;
}
function CedulaFormat(vCedulaName,mensaje,postab,escribo,evento) {
	tecla=getkey(evento);
	vCedulaName.value=vCedulaName.value.toUpperCase();
	vCedulaValue=vCedulaName.value;
	valor=vCedulaValue.substring(2,12);
	var numeros='0123456789/';
	var digit;
	var noerror=true;
	if (shift && tam>1) {
		return false;
	}

	for (var s=0;s<valor.length;s++){
		digit=valor.substr(s,1);
		if (numeros.indexOf(digit)<0) {
			noerror=false;
			break;
		}
	}
	tam=vCedulaValue.length;
	if (escribo) {
		if ( tecla==8 || tecla==37) {
			if (tam>2)
				vCedulaName.value=vCedulaValue.substr(0,tam-1);
			else
				vCedulaName.value='';
			return false;
		}

		if (tam==0 && tecla==69) {
			vCedulaName.value='E-';
			return false;
		}
		if (tam==0 && tecla==86) {
			vCedulaName.value='V-';
			return false;
		}
		else if ((tam==0 && ! (tecla<14 || tecla==69 || tecla==86 || tecla==46)))
			return false;
		else if ((tam>1) && !(tecla<14 || tecla==16 || tecla==46 || tecla==8 || (tecla >= 48 && tecla <= 57) || (tecla>=96 && tecla<=105)))
			return false;
	}
	if (noerror)
	mostrarerror(mensaje +' debe ser una cédula valida\nPor favor reescribala',vCedulaName,postab);
	return false;
} 



function SoloNumero(e){
	var strCheck = '0123456789';
	//var whichCode = (window.Event) ? e.which : e.keyCode;
	var whichCode = e.keyCode;
	
	if (whichCode == 13) return true;  // Enter
	if (whichCode == 8) return true;  // Backspace
	if (whichCode == 9) return true;  // Tab
	if (whichCode == 116) return true;  // F5
	//var whichCode = (window.Event) ? e.which : e.keyCode;
	//alert(e.which);
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false; 
}

function FormatoDecimal(fld, milSep, decSep, e) {
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	//var whichCode = (window.Event) ? e.which : e.keyCode;
	var whichCode = e.keyCode;
	if (whichCode == 13) return true;  // Enter
	if (whichCode == 8) return true;  // Backspace
	if (whichCode == 9) return true;  // Tab
	var whichCode = (window.Event) ? e.which : e.keyCode;
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
		if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) fld.value = '';
	if (len == 1) fld.value = '0'+ decSep + aux;
	// if (len == 2) fld.value = '0'+ decSep + aux;
	if (len > 1) {
		// alert(len)
		aux2 = '';
		for (j = 0, i = len - 3; i >= 0; i--) {
			if (j == 3) {
				aux2 += milSep;
				j = 0;
			}
			aux2 += aux.charAt(i);
			j++;
		} 
		fld.value = '';
		len2 = aux2.length;
		for (i = len2 - 1; i >= 0; i--)
			fld.value += aux2.charAt(i);
		
		fld.value += decSep + aux.substr(len - 2, len);
	}
return false;

}

function IsNumeric(valor) { 
	var log=valor.length; var sw="S"; 
	for (x=0; x<log; x++) { 
		v1=valor.substr(x,1); 
		v2 = parseInt(v1); 
		//Compruebo si es un valor numérico 
		if (isNaN(v2)) { 
			sw= "N";
		} 
	} 

	if (sw=="S") {
		return true;
	} else {
		return false; 
	} 
} 

var primerslap=false; 
var segundoslap=false; 

function formateafecha(fecha) { 

	var long = fecha.length; 
	var dia; 
	var mes; 
	var ano; 
	if ((long>=2) && (primerslap==false)) { 
		dia=fecha.substr(0,2); 
		if ((IsNumeric(dia)==true) && (dia<=31) && (dia!="00")) { 
			fecha=fecha.substr(0,2)+"/"+fecha.substr(3,7); primerslap=true; 
		} else { 
			fecha=""; primerslap=false;
		} 
	} else { 
		dia=fecha.substr(0,1); 
		if (IsNumeric(dia)==false){
			fecha="";
		} 
		if ((long<=2) && (primerslap=true)) {
			fecha=fecha.substr(0,1); primerslap=false; 
		} 
	} 

	if ((long>=5) && (segundoslap==false)){ 
		mes=fecha.substr(3,2); 
		if ((IsNumeric(mes)==true) &&(mes<=12) && (mes!="00")) { 
			fecha=fecha.substr(0,5)+"/"+fecha.substr(6,4); segundoslap=true; 
		} else { 
			fecha=fecha.substr(0,3);; segundoslap=false;
		} 
	} else { 
		if ((long<=5) && (segundoslap=true)) { 
			fecha=fecha.substr(0,4); segundoslap=false; 
		} 
	} 
	
	if (long>=7) { 
		ano=fecha.substr(6,4); 
		if (IsNumeric(ano)==false) { 
			fecha=fecha.substr(0,6); 
		} else { 
			if (long==10){ 
				if ((ano==0) || (ano<1900) || (ano>2100)) { 
					fecha=fecha.substr(0,6); 
				} 
			} 
		} 
	} 
	
	if (long>=10) { 
		fecha=fecha.substr(0,10); 
		dia=fecha.substr(0,2); 
		mes=fecha.substr(3,2); 
		ano=fecha.substr(6,4); 
		// Año no viciesto y es febrero y el dia es mayor a 28 
		if ( (ano%4 != 0) && (mes ==02) && (dia > 28) ) { 
			fecha=fecha.substr(0,2)+"/"; 
		} 
	} 

return (fecha); 
}