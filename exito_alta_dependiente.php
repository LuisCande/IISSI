<?php
	session_start();

	require_once ("gestionBD.php");
	require_once ("gestionarUsuarios.php");
	require_once ("paginacion_consulta.php");


	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		$nuevoUsuario = $_SESSION["formulario"];
		$_SESSION["formulario"] = null;
		$_SESSION["errores"] = null;
	}
	else
		Header("Location: form_alta_dependiente.php");

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
  <title>Motos Cande: Alta de Dependiente realizada con éxito</title>
</head>

<body>
	<?php
		include_once("cabecera.php");

	?>

	<main id="mainregistro">
			<?php include_once("paginacion_consulta.php");
				 if (comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Gerente') {
								if (!alta_dependiente($conexion, $nuevoUsuario)) {?>
										<h1>Se ha registrado el dependiente con el email <?php echo $nuevoUsuario["email"]; ?> correctamente</h1>
											<div >
				   						Pulsa <a href="about.php">aquí</a> para acceder a la página.
										</div>
						<?php }else { ?>
											<h1>El dependiente ya existe en la base de datos.</h1>
											<div >
													Pulsa <a href="form_alta_dependiente.php">aquí</a> para volver al formulario.
											</div>
			<?php } }?>

	</main>

	<?php
		include_once("pie.php");
	?>
</body>
</html>
<?php
	cerrarConexionBD($conexion);
?>
