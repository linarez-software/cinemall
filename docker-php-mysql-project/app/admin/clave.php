<html>

<head>
<style TYPE="text/css">
<!--
a:hover {color: #CC0000}
-->
</style>
<script Language="JavaScript"><!--
function validar(formulario)
{

  if (formulario.nombre.value.length < 4)
  {
    alert("Escriba por lo menos 4 caracteres en el campo \"nombre\".");
    formulario.nombre.focus();
    return (false);
  }

  var checkOK = "ABCDEFGHIJKLMN�OPQRSTUVWXYZ�����abcdefghijklmn�opqrstuvwxyz����� ";
  var checkStr = formulario.nombre.value;
  var allValid = true;
  for (i = 0;  i < checkStr.length;  i++)
  {
    ch = checkStr.charAt(i);
    for (j = 0;  j < checkOK.length;  j++)
      if (ch == checkOK.charAt(j))
        break;
    if (j == checkOK.length)
    {
      allValid = false;
      break;
    }
  }
  if (!allValid)
  {
    alert("Escriba s�lo letra caracteres en el campo \"nombre\".");
    formulario.nombre.focus();
    return (false);
  }

  if (formulario.edad.value.length != 2)
  {
    alert("Escriba un valor mayor o igual que \"18\" y menor o igual que \"30\" en el campo \"Edad\".");
    formulario.nombre.focus();
    return (false);
  }

  var checkOK = "0123456789-";
  var checkStr = formulario.edad.value;
  var allValid = true;
  var decPoints = 0;
  var allNum = "";
  for (i = 0;  i < checkStr.length;  i++)
  {
    ch = checkStr.charAt(i);
    for (j = 0;  j < checkOK.length;  j++)
      if (ch == checkOK.charAt(j))
        break;
    if (j == checkOK.length)
    {
      allValid = false;
      break;
    }
    allNum += ch;
  }
  if (!allValid)
  {
    alert("Escriba s�lo d�gito caracteres en el campo \"Edad\".");
    formulario.edad.focus();
    return (false);
  }

  var chkVal = allNum;
  var prsVal = parseInt(allNum);
  if (chkVal != "" && !(prsVal >= "18" && prsVal <= "30"))
  {
    alert("Escriba un valor mayor o igual que \"18\" y menor o igual que \"30\" en el campo \"Edad\".");
    formulario.edad.focus();
    return (false);
  }
  if ((formulario.correo.value.indexOf ('@', 0) == -1)||(formulario.correo.value.length < 5)) { 
    alert("Escriba una direcci�n de correo v�lida en el campo \"Direcci�n de correo\"."); 
    formulario.correo.focus();
    return (false); 
  }
  return (true);
}
//--></script>

<title>Ejemplo de formulario con control de entrada</title>
</head>

<body bgcolor="#ffffff" text="#000000" link="#054BBB" vlink="#006342" background="../images/sombrazul.gif" onLoad="window.defaultStatus='Ejemplo de formulario con control de entrada'; return true">

<table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="480" valign="top" align="left"><table border="0" width="100%">
      <tr>
        <td width="100%">

        <h2><font color="#054BBB">Ejemplo de formulario</font></h2>
        <p>Introduzca sus datos personales: </p>
        <form method="POST" name="registro" onSubmit="return validar(this)" action="formularios.asp">
          <table border="0" width="350" cellspacing="3">
            <tr>
              <td width="50%" bgcolor="#FFF3D6" align="right">Nombre</td>
              <td width="50%" bgcolor="#FFDC88"><input type="text" name="nombre" size="20"></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#FFF3D6" align="right">Edad</td>
              <td width="50%" bgcolor="#FFDC88"><input type="text" name="edad" size="2" maxlength="2"></td>
            </tr>
            <tr>
              <td width="50%" bgcolor="#FFF3D6" align="right">Direcci�n de correo</td>
              <td width="50%" bgcolor="#FFDC88"><input type="text" name="correo" size="20"></td>
            </tr>
          </table>
          <p><input type="submit" value="Enviar datos" name="enviar"></p>
        </form>
        </td>
      </tr>
    </table>

  </tr>
</table>
</body>
</html>
