<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarProveedores.php");
require_once ("paginacion_consulta.php");

if (!isset($_SESSION['login']))
	Header("Location: login.php");
else {
	if (isset($_SESSION["proveedor"])) {
		$proveedor = $_SESSION["proveedor"];
		unset($_SESSION["proveedor"]);
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
		$_SESSION["excepcion"]= "No tienes los permisos suficientes para acceder a esta página";
		Header("Location: excepcion.php");
	}

	// La consulta que ha de paginarse
	$query = 'SELECT PROVEEDOR.OID_PROV, PROVEEDOR.NOMBRE, PROVEEDOR.CODIGO, '
			. 'PROVEEDOR.EMAIL, PROVEEDOR.TELEFONO, PROVEEDOR.DIRECCION, PROVEEDOR.WEB '
			. 'FROM PROVEEDOR '
			. 'ORDER BY NOMBRE, CODIGO';

	// Se comprueba que el tamaño de página, página seleccionada y total de registros son conformes.
	// En caso de que no, se asume el tamaño de página propuesto, pero desde la página 1
	$total_registros = total_consulta($conexion, $query);
	$cosa = comprobar_tipo_usuario($conexion,$_SESSION['login']);
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
  <title>Lista de proveedores</title>
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

						<a href="consulta_proveedores.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="consulta_proveedores.php">
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
		<br></br>
	</nav>

	<?php 	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Gerente'){?>
		<p id="registrar"><a href="form_alta_proveedor.php">
		<button type="button" id="botonregistro">Registrar nuevo proveedor</button></a></p>
	<?php }
	else{ ?>
	<br>

	<?php }
	 if($total_registros==0){?>

		<br>
	<h2 id="NingunaCita">	No hay ningún proveedor</h2>
		<br>
	<?php }
		foreach($filas as $fila) {

	?>



	<article class="proveedor">

		<form method="post" action="controlador_proveedores.php">

			<div id="fila_proveedor" class="fila_proveedor">

				<div class="datos_proveedor">

					<input id="OID_PROV" name="OID_PROV"

						type="hidden" value="<?php echo $fila["OID_PROV"]; ?>"/>

					<input id="CODIGO" name="CODIGO"

						type="hidden" value="<?php echo $fila["CODIGO"]; ?>"/>

					<input id="NOMBRE" name="NOMBRE"

						type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>


				<?php

					if (isset($proveedor)
					and ($proveedor["OID_PROV"]
					== $fila["OID_PROV"])) { ?>

						<!-- Editando título -->
<br>
						Email:<input id="email" name="email" type="text" value="<?php echo $fila["EMAIL"]; ?>"/>
<br>
						Teléfono:<input id="telefono" name="telefono" type="text" value="<?php echo $fila["TELEFONO"]; ?>"/>
<br>
						Dirección:<input id="direccion" name="direccion" type="text" value="<?php echo $fila["DIRECCION"]; ?>"/>
<br>
						Web:<input id="web" name="web" type="text" value="<?php echo $fila["WEB"]; ?>"/>
<br>
						<h4><?php echo $fila["NOMBRE"] . " " . $fila["CODIGO"]; ?></h4>

				<?php }	else { ?>

						<!-- mostrando título -->



						<div class="nombre">Nombre: <b><?php echo $fila["NOMBRE"]; ?></b></div>

						<div class="codigo">Codigo: <em><?php echo $fila["CODIGO"]; ?></em></div>

						<div class="email">Email: <em><?php echo $fila["EMAIL"]; ?></em></div>

						<div class="telefono">Teléfono: <em><?php echo $fila["TELEFONO"]; ?></em></div>

						<div class="direccion">Dirección: <em><?php echo $fila["DIRECCION"]; ?></em></div>

						<div class="web">Web: <em><?php echo $fila["WEB"]; ?></em></div>




				<?php } ?>

				</div>



				<div id="botones_fila">

				<?php if (isset($proveedor) and ($proveedor["OID_PROV"] == $fila["OID_PROV"])) { ?>

						<button id="grabar" name="grabar" type="submit" class="editar_fila">

							<img src="images/grabar.png" class="editar_fila" alt="Guardar modificación">

						</button>

				<?php } else { ?>

						<button id="editar" name="editar" type="submit" class="editar_fila">

							<img src="images/editar.jpg" class="editar_fila" alt="Editar proveedor">

						</button>

				<?php } ?>

					<button id="borrar" name="borrar" type="submit" class="editar_fila">

						<img src="images/borrar.jpg" class="editar_fila" alt="Borrar proveedor">

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
