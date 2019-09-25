<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarProductos.php");
require_once ("paginacion_consulta.php");

if (!isset($_SESSION['login']))
	Header("Location: login.php");
else {
	if (isset($_SESSION["productos"])) {
		$productos = $_SESSION["productos"];
		unset($_SESSION["productos"]);
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

	 	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Cliente'){
	$query = 'SELECT PRODUCTO.OID_PROV, PRODUCTO.NOMBRE, PRODUCTO.CODIGO, '
			. 'PRODUCTO.DESCRIPCION, PRODUCTO.MARCA, PRODUCTO.TIPOPRODUCTO, PRODUCTO.OFERTA, '
			. 'PRODUCTO.IVA, PRODUCTO.STOCK, PRODUCTO.STOCKMINIMO, PRODUCTO.OID_P, PRODUCTO.PRECIO '
			. 'FROM PRODUCTO, RECAMBIO '
			. 'WHERE '
			. 'PRODUCTO.OID_P = RECAMBIO.OID_P AND PRODUCTO.STOCK>PRODUCTO.STOCKMINIMO '
			. 'ORDER BY MARCA, NOMBRE';
		}
		else{
			$query = 'SELECT PRODUCTO.OID_PROV, PRODUCTO.NOMBRE, PRODUCTO.CODIGO, '
					. 'PRODUCTO.DESCRIPCION, PRODUCTO.MARCA, PRODUCTO.TIPOPRODUCTO, PRODUCTO.OFERTA, '
					. 'PRODUCTO.IVA, PRODUCTO.STOCK, PRODUCTO.STOCKMINIMO, PRODUCTO.OID_P, PRODUCTO.PRECIO '
					. 'FROM PRODUCTO, RECAMBIO '
					. 'WHERE '
					. 'PRODUCTO.OID_P = RECAMBIO.OID_P '
					. 'ORDER BY MARCA, NOMBRE';
		}
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
	<link rel="icon" href="images/favicon.ico">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Hay que indicar el fichero externo de estilos -->
    <link rel="stylesheet" type="text/css" href="css/motoscande.css" />
	<script type="text/javascript" src="./js/boton.js"></script>
  <title>Lista de productos</title>
</head>

<body>

<?php

include_once ("cabecera.php");

include_once ("menu.php");
?>



<main>

	 <nav id="navig">

		<div id="enlaces">

			<?php

				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="consulta_recambio.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="consulta_recambio.php">
			<?php if ($total_registros==0){ ?>
				<br>
				<?php } ?>
			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

			Mostrando

			<input id="PAG_TAM" name="PAG_TAM" type="number"

				min="1" max="<?php echo $total_registros; ?>"

				value="<?php echo $pag_tam?>" autofocus="autofocus" />

			entradas de <?php echo $total_registros?>

			<input type="submit" value="Cambiar">

		</form>

	</nav>



	<?php 	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){?>
		<p id="registrar"><a href="form_alta_recambio.php">
		<button type="button"id="botonregistro">Registrar nuevo producto</button></a></p>
	<?php }
	else{ ?>
	<br>

	<?php
	 if($total_registros==0){?>

		<br>
	<h2 id="NingunaCita">	No hay ningun producto en el carrito</h2>
		<br>
	<?php }
	}

		foreach($filas as $fila) {

	?>



	<article class="productos">

		<form method="post" action="controlador_recambios.php">

			<div class="fila_productos">

				<div class="datos_productos">

					<input id="OID_P" name="OID_P"

						type="hidden" value="<?php echo $fila["OID_P"]; ?>"/>

					<input id="CODIGO" name="CODIGO"

						type="hidden" value="<?php echo $fila["CODIGO"]; ?>"/>

						<input id="NOMBRE" name="NOMBRE"

							type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

						<input id="DESCRIPCION" name="DESCRIPCION"

							type="hidden" value="<?php echo $fila["DESCRIPCION"]; ?>"/>

						<input id="MARCA" name="MARCA"

							type="hidden" value="<?php echo $fila["MARCA"]; ?>"/>

						<input id="TIPOPRODUCTO" name="TIPOPRODUCTO"

							type="hidden" value="<?php echo $fila["TIPOPRODUCTO"]; ?>"/>

						<input id="IVA" name="IVA"

							type="hidden" value="<?php echo $fila["IVA"]; ?>"/>

						<input id="STOCK" name="STOCK"

							type="hidden" value="<?php echo $fila["STOCK"]; ?>"/>

						<input id="OID_PROV" name="OID_PROV"

							type="hidden" value="<?php echo $fila["OID_PROV"]; ?>"/>



				<?php

					if (isset($productos)
					and ($productos["OID_P"]
					== $fila["OID_P"])) { ?>

						<!-- Editando título -->
<br>
						Precio:<input id="precio" name="precio" type="text" value="<?php echo $fila["PRECIO"]; ?>"/><em> €</em>
<br><br>
<?php 	$auxOferta 	=str_replace(',', '', $fila["OFERTA"]);?>
						Oferta:<input id="oferta" name="oferta" type="text" pattern="^\d{0,2}" title="Por favor introduzca un número válido" value="<?php echo $auxOferta; ?>"/>%
<br><br>
						Stock :<input id="stock" name="stock" type="text" value="<?php echo $fila["STOCK"]; ?>"/>
<br><br>
						Stock mínimo:<input id="stockMinimo" name="stockMinimo" type="text" value="<?php echo $fila["STOCKMINIMO"]; ?>"/>
<br><br>
						<h4><?php echo $fila["NOMBRE"] . " " . $fila["CODIGO"]; ?></h4>

				<?php }	else { ?>

						<!-- mostrando título -->

						<div class="nombre">Nombre: <b><?php echo $fila["NOMBRE"]; ?></b></div>

						<div class="codigo">Codigo: <em><?php echo $fila["CODIGO"]; ?></em></div>

						<div class="descripcion">Descripción: <em><?php echo $fila["DESCRIPCION"]; ?></em></div>

						<div class="marca">Marca: <em><?php echo $fila["MARCA"]; ?></em></div>

						<div class="precio">Precio: <em><?php echo $fila["PRECIO"]; ?> €</em></div>

						<?php if($fila["OFERTA"]!=NULL && $fila["OFERTA"]!='0'){
							$auxOferta 	=str_replace(',', '', $fila["OFERTA"]);?>
												<div class="oferta">Oferta: <em><?php echo $auxOferta; ?></em>%</div>
	<?php }?>

						<div class="stock">Stock: <em><?php echo $fila["STOCK"]; ?></em></div>

						<?php if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){?>
												<div class="stockMinimo">Stock mínimo: <em><?php echo $fila["STOCKMINIMO"]; ?></em></div>
						<?php }?>



				<?php } ?>

				</div>

				<div id="botones_fila">
	<?php if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){?>
				<?php if (isset($productos) and ($productos["OID_P"] == $fila["OID_P"])) { ?>

						<button id="grabar" name="grabar" type="submit" class="editar_fila">

							<img src="images/grabar.png" class="editar_fila" alt="Guardar modificación">

						</button>

				<?php } else { ?>

						<button id="editar" name="editar" type="submit" class="editar_fila">

							<img src="images/editar.jpg" class="editar_fila" alt="Editar producto">

						</button>

				<?php } ?>

					<button id="borrar" name="borrar" type="submit" class="editar_fila">

						<img src="images/borrar.jpg" class="editar_fila" alt="Borrar producto">

					</button>
	<?php }?>
					<button id="comprar" name="comprar" type="submit" class="editar_fila">

						<img src="images/Comprar.jpg" class="editar_fila" alt="Comprar producto">

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
