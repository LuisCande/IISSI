<?php
	session_start();

	require_once("gestionBD.php");
	require_once("gestionarUsuarios.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		$nuevoUsuario = $_SESSION["formulario"];
		$_SESSION["formulario"] = null;
		$_SESSION["errores"] = null;
	}
	else
		Header("Location: form_alta_usuario.php");

	$conexion = crearConexionBD();

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
  <title>Motos Cande: Alta de Usuario realizada con éxito</title>
</head>

<body>
	<?php
		include_once("cabecera.php");
	?>

	<main id="mainregistro">
		<?php if (!alta_usuario($conexion, $nuevoUsuario)) {
				$_SESSION['login'] = $nuevoUsuario['email'];
		?>
				<h1>Hola <?php echo $nuevoUsuario["nombre"]; ?>, gracias por registrarte</h1>
				<div >
			   		Pulsa <a href="about.php">aquí</a> para acceder a la página.
				</div>
		<?php } else { ?>
				<h1>El usuario ya existe en la base de datos.</h1>
				<div >
					Pulsa <a href="form_alta_usuario.php">aquí</a> para volver al formulario.
				</div>
		<?php } ?>

	</main>

	<?php
		include_once("pie.php");
	?>
</body>
</html>
<?php
	cerrarConexionBD($conexion);
?>
