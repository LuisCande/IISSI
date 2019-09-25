<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarProductos.php");
require_once ("paginacion_consulta.php");

if (!isset($_SESSION['login']))
	Header("Location: login.php");
else {
	if (isset($_SESSION["carrito"])) {
		$carrito = $_SESSION["carrito"];
		unset($_SESSION["carrito"]);
	}
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];

		unset($_SESSION["errores"]);
	}
	if (isset($_SESSION["exito"])){
		$exito = $_SESSION["exito"];

		unset($_SESSION["exito"]);
	}

	// ¿Venimos simplemente de cambiar página o de haber seleccionado un registro ?
	// ¿Hay una sesión activa?
	if (isset($_SESSION["paginacion"]))
		$paginacion = $_SESSION["paginacion"];

	$pagina_seleccionada = isset($_GET["PAG_NUM"]) ? (int)$_GET["PAG_NUM"] : (isset($paginacion) ? (int)$paginacion["PAG_NUM"] : 1);
	$pag_tam = isset($_GET["PAG_TAM"]) ? (int)$_GET["PAG_TAM"] : (isset($paginacion) ? (int)$paginacion["PAG_TAM"] : 5);

	if ($pagina_seleccionada < 1) 		$pagina_seleccionada = 1;
	if ($pag_tam < 1) 		$pag_tam = 5;

	// Antes de seguir, borramos las variables de sección para no confundirnos más adelante
	unset($_SESSION["paginacion"]);

	$conexion = crearConexionBD();

	$OID_UsEmail = consulta_usuario($conexion,$_SESSION['login']);

	$PedidoSeleccionado= seleccionar_pedido($conexion,$OID_UsEmail);

  $carrito['OID_PE']= $PedidoSeleccionado;
  $carrito['ESTADO']= seleccionar_estado($conexion,$PedidoSeleccionado);
  $carrito['ENVIO']= seleccionar_envio($conexion,$PedidoSeleccionado);
  $carrito['TIPOPAGO']= seleccionar_tipopago($conexion,$PedidoSeleccionado);
  $carrito['FECHAENTREGA']= seleccionar_fechaentrega($conexion,$PedidoSeleccionado);

	$query = 'SELECT PRODUCTO.OID_P, PRODUCTO.NOMBRE, PRODUCTO.OFERTA, '
			. 'PRODUCTO.IVA, PRODUCTO.PRECIO, LINEAPEDIDO.CANTIDAD, LINEAPEDIDO.OID_LP, LINEAPEDIDO.PRECIO AS PRECIOLP 	'
			. 'FROM PRODUCTO INNER JOIN LINEAPEDIDO '
			. 'ON PRODUCTO.OID_P = LINEAPEDIDO.OID_P '
			.	'WHERE '
			. 'LINEAPEDIDO.OID_Pe = '.$PedidoSeleccionado.' '
			. 'ORDER BY NOMBRE';

	// Se comprueba que el tamaño de página, página seleccionada y total de registros son conformes.
	// En caso de que no, se asume el tamaño de página propuesto, pero desde la página 1
	$total_registros = total_consulta($conexion, $query);
	$total_paginas = (int)($total_registros / $pag_tam);

	if ($total_registros % $pag_tam > 0)		$total_paginas++;

	if ($pagina_seleccionada > $total_paginas)		$pagina_seleccionada = $total_paginas;

	// Generamos los valores de sesión para página e intervalo para volver a ella después de una operación
	$paginacion["PAG_NUM"] = $pagina_seleccionada;
	$paginacion["PAG_TAM"] = $pag_tam;
	$_SESSION["paginacion"] = $paginacion;

	$filas = consulta_paginada($conexion, $query, $pagina_seleccionada, $pag_tam);

	cerrarConexionBD($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title></title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" href="css/motoscande.css">
    <link rel="icon" href="images/favicon.ico">
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			$(function(){
		       $('#login').click(function(){
			   $(this).next('#login-content').slideToggle();
			   $(this).toggleClass('active');
			   });
			});
		</script>
	</head>
	<body>
    <?php

    include_once ("cabecera.php");

    include_once ("menu.php");
    ?>
    <main>
    <nav class="acceder">
      <nav id="navig">

      <div id="enlaces">

        <?php

          for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

            if ( $pagina == $pagina_seleccionada) { 	?>

              <span class="current"><?php echo $pagina; ?></span>

        <?php }	else { ?>

              <a href="consulta_carrito.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

        <?php } ?>

      </div>
      <form method="get" action="consulta_carrito.php">
				<?php if ($total_registros==0){ ?>
					<br>
					<?php } ?>
        <input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

        Mostrando

        <input id="PAG_TAM" name="PAG_TAM" type="number"

          min="1" max="<?php echo $total_registros; ?>"

          value="<?php echo $pag_tam?>" autofocus="autofocus" />

        entradas de <?php echo $total_registros;?>

        <input type="submit" value="Cambiar">

      </form>

    </nav>
		   <ul>
		      <li>
		         <a id="login" href="#">Siguiente</a>
		         <div id="login-content">
		            	<form method="post" action="accion_comprar_carrito.php">
										<input id="OID_PE" name="OID_PE"

											type="hidden" value="<?php echo $carrito["OID_PE"]; ?>"/>
	                  <input id="fechaEntrega" name="fechaEntrega" type="date" value="<?php echo date("Y-m-d");?>" required/>

          <div><label>Envio:</label>
          <label>
            <input name="envio" type="radio" value="True" <?php if($carrito['ENVIO']=='True') echo ' checked ';?>/>
            A domicilio</label>
          <label>
            <input name="envio" type="radio" value="False" <?php if($carrito['ENVIO']=='False') echo ' checked ';?>/>
            Recoger</label>
          </div>
          <div><label>Tipo de pago:</label>
          <label>
            <input name="tipoPago" type="radio" value="Tarjeta" <?php if($carrito['TIPOPAGO']=='Tarjeta') echo ' checked ';?>/>
            Tarjeta</label>
          <label>
            <input name="tipoPago" type="radio" value="Transferencia" <?php if($carrito['TIPOPAGO']=='Transferencia') echo ' checked ';?>/>
            Transferencia</label>
          </div>
	<?php $precioTotal = seleccionar_preciopedido($conexion,$PedidoSeleccionado);?>
					<div id="preciototal2"><h4> Precio total: <?php echo $precioTotal; ?>€<h4></div>
					<h4>	<input type="submit" id="pagarpedido" name="comprar" value="Comprar"><h4>

			        </form>
			     </div>
		      </li>
		   </ul>
			 <div id="errorescarrito">
				 <?php if (isset($errores) && count($errores)>0) {
						 echo "<div id=\"div_errores\" class=\"error\">";
					 echo "<h4> Errores en el formulario:</h4>";
						 foreach($errores as $error){
							 echo $error;
					 }
						 echo "</div>";
					 }else if(isset($exito)){
						 echo "<div id=\"div_exito\" class=\"exito\">";
					 echo "<h4> ¡ Exito al realizar la compra !</h4>";
					 }?>
				 </div>
		</nav>
    <?php
     if($total_registros==0){?>

    	<br>
    <h2 id="NingunaCita">	No hay ningún producto en el carrito</h2>
    	<br>
    <?php } else {

    		$precioTotal = seleccionar_preciopedido($conexion,$PedidoSeleccionado);



    	?>


    <div id="preciototal"><h4> Precio total: <?php echo $precioTotal; ?>€<h4></div>

    <?php	}foreach($filas as $fila) {

    	?>



    	<article class="productos">

    		<form method="post" action="controlador_carrito.php">

    			<div class="fila_productos">

    				<div class="datos_productos">

    					<input id="OID_P" name="OID_P"

    						type="hidden" value="<?php echo $fila["OID_P"]; ?>"/>

    						<input id="NOMBRE" name="NOMBRE"

    							type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

    						<input id="IVA" name="IVA"

    							type="hidden" value="<?php echo $fila["IVA"]; ?>"/>

    						<input id="OID_LP" name="OID_LP"

    							type="hidden" value="<?php echo $fila["OID_LP"]; ?>"/>




    						<!-- mostrando título -->
    						<div class="nombre">Nombre: <b><?php echo $fila["NOMBRE"]; ?></b></div>

    						<div class="iva">Iva: <em>0<?php echo $fila["IVA"]; ?></em></div>

    						<?php if($fila["OFERTA"]!=NULL && $fila["OFERTA"]!='0'){
    							$auxOferta 	=str_replace(',', '', $fila["OFERTA"]);?>
    												<div class="oferta">Oferta: <em><?php echo $auxOferta; ?></em>%</div>

    						<div class="precio"><strike>Precio base: <em><?php echo $fila["PRECIO"]; ?></em> € </strike> </div>
    						<div class="precioLP"><h5 style="margin: 0px;">Precio en oferta :<?php echo $fila["PRECIOLP"]; ?>€</h5>   </div>

    	<?php }else{?>

    						<div class="precio">Precio: <em><?php echo $fila["PRECIO"]; ?></em> €</div>
    <?php }?>
    						<div class="cantidad">Cantidad: <em><?php echo $fila["CANTIDAD"]; ?></em></div>







    				</div>



    				<div id="botones_fila">

    					<button id="borrar" name="borrar" type="submit" class="editar_fila">

    						<img src="images/borrar.jpg" class="editar_fila" alt="Borrar producto">

    					</button>

    				</div>

    			</div>

    		</form>

    	</article>



    	<?php } ?>

    </main>



    <?php

    include_once ("pie.php");
    ?>

    </body>

    </html>
