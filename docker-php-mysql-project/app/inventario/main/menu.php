
    <div style="width: 720px;">
	<div class="container4">
	<div class="menu4">
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="A"){
	?>    
        <ul>
        <li class="articles"><a href="#">Movimientos</a>
            <ul>
            <?php
					
					echo"<li class='add_article'><a href='entradas.php'>Entradas</a></li>";
					echo"<li class='add_article'><a href='salida.php'>Salidas</a></li>";
            ?>    
            </ul>
        </li>
		
		<li class="users"><a href="#">Tablas</a>
            <ul>
                <li class="add_user"><a href="depositos.php">Depositos</a></li>
                <li class="add_user"><a href="unidades.php">Unidades de Medida</a></li>
                <li class="add_user"><a href="productos.php">Productos</a></li>
                <li class="add_user"><a href="destino.php">Destinos</a></li>
                
                
           </ul>
        </li>
		<li class="users"><a href="#">Reportes</a>
            <ul>
                <li class="add_user"><a href="Reporte.php">Movimientos por Productos</a></li>
               	<li class="add_user"><a href="Reporte2.php">Listado de Entradas</a></li>
               	<li class="add_user"><a href="Reporte3.php">Listado de Salidas</a></li>
                <li class="add_user"><a href="Reporte4.php">Inventarios</a></li>
           </ul>
        </li>
		<li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="users"><a href="View_Usuarios.php">Usuarios</a>
            
        </li>
       <li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="AO"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					
					echo"<li class='add_article'><a href='despachos.php'>Pedidos</a></li>";
					echo"<li class='add_article'><a href='despachosa.php'>Pedidos Anulados</a></li>";
					echo"<li class='add_article'><a href='recepcion.php'>Recepcion</a></li>";
					echo"<li class='add_article'><a href='despachos_asoc.php'>Consultar Notas</a></li>";
					echo"<li class='add_article'><a href='despachos_apro.php'>Pedidos Por Aprobar</a></li>";
            ?>    
            </ul>
        </li>
		<li class="users"><a href="#">Inventario</a>
            <ul>
                <li class="add_user"><a href="#" onclick="window.open ('add_inventario.php', 'Help','scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=800,height=600'); ">Cargar Inventario</a></li>
           </ul>
        </li>
		<li class="users"><a href="#">Tablas</a>
            <ul>
                <li class="add_user"><a href="productos.php">Productos</a></li>
                <li class="add_user"><a href="refugios.php">Albergues</a></li>
                <li class="add_user"><a href="semanas.php">Productos por Menu</a></li>
           </ul>
        </li>
		<li class="users"><a href="#">Reportes</a>
            <ul>
                <li class="add_user"><a href="Reporte.php">Pedidos por Albergues</a></li>
               	<li class="add_user"><a href="Reporte2.php">Pedidos por Centro de acopio</a></li>
               	<li class="add_user"><a href="Reporte3.php">Pedidos por Estados</a></li>
                <li class="add_user"><a href="Reporte4.php">Listado De Albergues</a></li>
               	<li class="add_user"><a href="Reporte6.php">Auditoria</a></li>
                <li class="add_user"><a href="Reporte7.php">Notas Recibidas</a></li>
                <li class="add_user"><a href="Reporte10.php">Despachos a Albergues</a></li>
                <li class="add_user"><a href="Reporte11.php">Toneladas Por Estado</a></li>
                <li class="add_user"><a href="Reporte12.php">Toneladas Por Estado y Mes</a></li>
                
           </ul>
        </li>
		<li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="users"><a href="View_Usuarios.php">Usuarios</a>
            
        </li>
       <li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="AP"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					echo"<li class='add_article'><a href='despachos.php'>Pedidos Aprobados</a></li>";
					echo"<li class='add_article'><a href='despachosa.php'>Pedidos Anulados</a></li>";
					
					echo"<li class='add_article'><a href='despachos_apro.php'>Pedidos Por Aprobar</a></li>";
					
            ?>    
            </ul>
        </li>
        	<li class="users"><a href="#">Tablas</a>
            <ul>
                <li class="add_user"><a href="refugios.php">Albergues</a></li>
           </ul>
        </li>
		<li class="users"><a href="#">Reportes</a>
            <ul>
                <li class="add_user"><a href="Reporte.php">Pedidos por Albergues</a></li>
               	<li class="add_user"><a href="Reporte2.php">Pedidos por Centro de acopio</a></li>
               	<li class="add_user"><a href="Reporte3.php">Pedidos por Estados</a></li>
                <li class="add_user"><a href="Reporte4.php">Listado De Albergues</a></li>
                <li class="add_user"><a href="Reporte7.php">Notas Recibidas</a></li>
                <li class="add_user"><a href="Reporte10.php">Despachos a Albergues</a></li>
                <li class="add_user"><a href="Reporte11.php">Toneladas Por Estado</a></li>
                <li class="add_user"><a href="Reporte12.php">Toneladas Por Estado y Mes</a></li>
                
               	
           </ul>
        </li>
       <li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="C"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					echo"<li class='add_article'><a href='despachos.php'>Pedidos</a></li>";
            ?>    
            </ul>
        </li>
		<li class="users"><a href="#">Reportes</a>
            <ul>
                <li class="add_user"><a href="Reporte.php">Pedidos por Albergues</a></li>
               	<li class="add_user"><a href="Reporte2.php">Pedidos por Centro de acopio</a></li>
               	<li class="add_user"><a href="Reporte3.php">Pedidos por Estados</a></li>
                <li class="add_user"><a href="Reporte4.php">Listado De Albergues</a></li>
                <li class="add_user"><a href="Reporte7.php">Notas Recibidas</a></li>
                <li class="add_user"><a href="Reporte10.php">Despachos a Albergues</a></li>
                <li class="add_user"><a href="Reporte11.php">Toneladas Por Estado</a></li>
                <li class="add_user"><a href="Reporte12.php">Toneladas Por Estado y Mes</a></li>
                
               	
           </ul>
        </li>
       <li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
     <?php
    session_start(); 
	if($_SESSION["tipo"]=="S"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					echo"<li class='add_article'><a href='recepcion.php'>Recepcion</a></li>";
					echo"<li class='add_article'><a href='despachos_asoc.php'>Consultar Notas</a></li>";
            ?>    
            </ul>
        </li>
		<li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="O"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					
					echo"<li class='add_article'><a href='despachos.php'>Pedidos</a></li>";
					echo"<li class='add_article'><a href='despachosa.php'>Pedidos Anulados</a></li>";
					echo"<li class='add_article'><a href='recepcion.php'>Recepcion</a></li>";
					echo"<li class='add_article'><a href='despachos_asoc.php'>Consultar Notas</a></li>";
					
            ?>    
            </ul>
        </li>
		<li class="users"><a href="#">Inventario</a>
            <ul>
                <li class="add_user"><a href="#" onclick="window.open ('add_inventario.php', 'Help','scrollBars=yes,resizable=no,toolbar=no,menubar=no,top =10,left=10,location=no,directories=no,width=800,height=600'); ">Cargar Inventario</a></li>
           </ul>
        </li>
		<li class="users"><a href="#">Reportes</a>
            <ul>
                <li class="add_user"><a href="Reporte.php">Pedidos por Albergues</a></li>
               	<li class="add_user"><a href="Reporte2.php">Pedidos por Centro de acopio</a></li>
               	<li class="add_user"><a href="Reporte3.php">Pedidos por Estados</a></li>
                <li class="add_user"><a href="Reporte4.php">Listado De Albergues</a></li>
                <li class="add_user"><a href="Reporte7.php">Notas Recibidas</a></li>
                <li class="add_user"><a href="Reporte10.php">Despachos a Albergues</a></li>
                <li class="add_user"><a href="Reporte11.php">Toneladas Por Estado</a></li>
                <li class="add_user"><a href="Reporte12.php">Toneladas Por Estado y Mes</a></li>
                
               	
           </ul>
        </li>
       <li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 
    <?php
    session_start(); 
	if($_SESSION["tipo"]=="M"){
	?>    
        <ul>
        <li class="articles"><a href="#">Pedidos</a>
            <ul>
            <?php
					
					echo"<li class='add_article'><a href='despachos_des.php'>Pedidos Despachados</a></li>";
					echo"<li class='add_article'><a href='despachos_apro_des.php'>Pedidos Por Despachar</a></li>";
					
            ?>    
            </ul>
        </li>
        <li class="users"><a href="#">Reportes</a>
            <ul>
               	<li class="add_user"><a href="Reporte5.php">Pedidos por Estados</a></li>
           </ul>
        </li>
       <li class="add_user"><a href="Cambiar_clave.php">Clave</a>
        </li>
		<li class="settings"><a href="main/outer.php">Salir</a></li>
        </ul>
       </li> 
    <?php
	}
	?> 

    </div>
	</div>
    </div>