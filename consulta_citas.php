<?php
session_start();

require_once ("gestionBD.php");
require_once ("gestionarCitas.php");
require_once ("paginacion_consulta.php");

if (!isset($_SESSION['login']))
	Header("Location: login.php");
else {
	if (isset($_SESSION["cita"])) {
		$cita = $_SESSION["cita"];
		unset($_SESSION["cita"]);
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

	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){
	// La consulta que ha de paginarse
	$query = 'SELECT CITA.OID_CI, CITA.OID_US, CITA.FECHAACORDADA, '
			. 'CITA.ASUNTO, CITA.DESCRIPCION, CITA.TIPOCITA '
			. 'FROM CITA '
			. 'ORDER BY OID_US';
		}
		else{
			$usuAux = consulta_usuario($conexion,$_SESSION['login']);
			$query = 'SELECT CITA.OID_CI, CITA.OID_US, CITA.FECHAACORDADA, '
					. 'CITA.ASUNTO, CITA.DESCRIPCION, CITA.TIPOCITA '
					. 'FROM CITA WHERE OID_US = '.$usuAux.''
					. 'ORDER BY OID_US';
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
  <title>Lista de citas</title>
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

						<a href="consulta_citas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="consulta_citas.php">
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
	<?php if(comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Cliente'){?>
		<p id="registrar"><a href="form_alta_cita.php">
	<button type="button"id="botonregistro" >Realizar nueva cita</button></a></p>


<?php }
 if($total_registros==0){?>

	<br>
<h2 id="NingunaCita">	No hay ninguna cita pendiente</h2>
	<br>
<?php }
		foreach($filas as $fila) {

	?>




	<article class="cita">

		<form method="post" action="controlador_citas.php">

			<div class="fila_cita">

				<div class="datos_cita">

					<input id="OID_CI" name="OID_CI"

						type="hidden" value="<?php echo $fila["OID_CI"]; ?>"/>





						<!-- mostrando título -->

						<div class="OID_Us">Email: <b><?php echo consulta_email($conexion,$fila["OID_US"]); ?></b></div>

						<div class="fechaAcordada">Fecha acordada: <em><?php echo $fila["FECHAACORDADA"]; ?></em></div>

						<div class="asunto">Asunto: <em><?php echo $fila["ASUNTO"]; ?></em></div>

						<div class="descripcion">Descripción: <em><?php echo $fila["DESCRIPCION"]; ?></em></div>

						<div class="tipoCita">Tipo de cita: <em><?php echo $fila["TIPOCITA"]; ?></em></div>



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
