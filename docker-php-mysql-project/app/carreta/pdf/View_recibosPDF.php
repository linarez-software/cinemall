<?php
include('html2fpdf.php');
$pdf="";
$pdf=$pdf."<table width='800' height='600'  border='0' align='left'>";
$pdf=$pdf."  <tr>";
$pdf=$pdf."    <td align='center' valign='top'><table width='80%' border='0' align='center' cellspacing='0'>";
$pdf=$pdf."      <tr>";
$pdf=$pdf."        <td colspan='3' class='Estilo3'>";
$pdf=$pdf."        	<table width='100%' height='100%'  border='0'>";
$pdf=$pdf."            <tr>";
$pdf=$pdf."                <td width='12%' rowspan='5'><img src='images/Logo Minpal.jpg' width='115' height='106'></td>";
$pdf=$pdf."                <td width='75%' align='center' class='Estilo3'>REPÚBLICA BOLIVARIANA DE VENEZUELA</td>";
$pdf=$pdf."                <td width='13%' rowspan='5'><img src='images/Imagen1.png' width='115' height='106'></td>";
$pdf=$pdf."            </tr>";
$pdf=$pdf."            <tr>";
$pdf=$pdf."                <td align='center' class='Estilo3'>MINISTERIO DEL PODER POPULAR PARA LA ALIMENTACIÓN</td>";
$pdf=$pdf."            </tr>";
$pdf=$pdf."            <tr>";
$pdf=$pdf."                <td align='center' class='Estilo3'>FUNDACIÓN PROGRAMA DE ALIMENTOS ESTRATÉGICOS</td>";
$pdf=$pdf."            </tr>";
$pdf=$pdf."            <tr>";
$pdf=$pdf."                <td align='center' class='Estilo3' valign='top'>(FUNDAPROAL)</span></td>";
$pdf=$pdf."		    </tr>";
$pdf=$pdf."            <tr>";
$pdf=$pdf."	        	<td height='22'>&nbsp;</td>";
$pdf=$pdf."    		</tr>";
$pdf=$pdf."			</table>  </td>";
$pdf=$pdf."        </tr>";

$pdf1 = new html2fpdf(); // Generamos un objeto nuevo html2fpdf  
$pdf1 -> AddPage(); // Añadimos una página  
$pdf1 -> WriteHTML($pdf); // Indicamos la variable con el contenido que queremos incluir en el pdf  
$pdf1 -> Output('archivo_pdf.pdf', 'D'); //Generamos el archivo "archivo_pdf.pdf". Ponemos como parametro 'D' para forzar la descarga del archivo.  
 
?>