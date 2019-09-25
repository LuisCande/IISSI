<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");


	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formulario'])) {
		$formulario['email'] = "";
		$formulario['telefono'] = "";
		$formulario['contraseña'] = "";
	//	$formulario['tipoUsuario'] = "Cliente";
		$formulario['nombre'] = "";
		$formulario['primerApellido'] = "";
		$formulario['segundoApellido'] = "";
		$formulario['dni'] = "";
		$formulario['direccion'] = "";
		$_SESSION['formulario'] = $formulario;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formulario = $_SESSION['formulario'];

	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}


	// Creamos una conexión con la BD
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
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
  <script src="js/validacion_cliente_alta_usuario.js" type="text/javascript"></script>
  <title>Motos Cande: Alta de Usuarios</title>
</head>

<body>

	<script>
		// Inicialización de elementos y eventos cuando el documento se carga completamente
		$(document).ready(function() {
			// EJERCICIO 2: Manejador de evento para copiar automáticamente el email como nick del usuario


			// EJERCICIO 3: Manejador de evento del color de la contraseña
			$("#contraseña").on("keyup", function() {
				// Calculo el color
				passwordColor();
			});

			// EJERCICIO 4: Uso de AJAX con JQuery para cargar de manera asíncrona los municipios según la provincia seleccionada
			// Manejador de evento sobre el campo de provincias

				});
			</script>

	<?php
		include_once("cabecera.php");
	?>
<main id="mainregistro">
	<?php
		// Mostrar los erroes de validación (Si los hay)
		if (isset($errores) && count($errores)>0) {
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		foreach($errores as $error){
    			echo $error;
			}
    		echo "</div>";
  		}
	?>

	<form id="altaUsuario" method="get" action="accion_alta_usuario.php" onsubmit="return validateForm()">
		<p><i>&nbsp  Todos los campos son obligatorios </i></p>
		<fieldset><legend>Datos personales</legend>
			<div></div><label for="dni">DNI: </label>
			<input id="dni" name="dni" type="text" placeholder="12345678X" pattern="^[0-9]{8}[A-Z]" title="Ocho dígitos seguidos de una letra mayúscula" value="<?php echo $formulario['dni'];?>" required>
			</div>

			<div><label for="nombre">Nombre:</label>
			<input id="nombre" name="nombre" type="text" size="40" value="<?php echo $formulario['nombre'];?>" required/>
			</div>

			<div><label for="primerApellido">Primer apellido:</label>
			<input id="primerApellido" name="primerApellido" type="text" size="80" value="<?php echo $formulario['primerApellido'];?>" required/>
			</div>

			<div><label for="segundoApellido">Segundo apellido:</label>
			<input id="segundoApellido" name="segundoApellido" type="text" size="80" value="<?php echo $formulario['segundoApellido'];?>" required/>
			</div>

			<div><label for="telefono">Teléfono:</label>
				<input id="telefono" name="telefono" type="text" size="40" value="<?php echo $formulario['telefono'];?>" required/>
			</div>

			<div><label for="direccion">Dirección:</label>
				<input id="direccion" name="direccion" type="text" size="40" value="<?php echo $formulario['direccion'];?>" required/>
			</div>

			<div><label for="email">Email:</label>
			<input id="email" name="email"  type="email" placeholder="usuario@dominio.extension" value="<?php echo $formulario['email'];?>" required/><br>
			</div>

			<div><label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" placeholder="Mínimo 8 caracteres entre letras y dígitos" required oninput="passwordValidation(); "/>
			</div>


			<div><label for="confirmcontraseña">Confirmar contraseña: </label>
				<input type="password" name="confirmcontraseña" id="confirmcontraseña" placeholder="Confirmación de contraseña"  oninput="passwordConfirmation();" required/>
			</div>
		</fieldset>
		<div><input type="submit" value="Enviar" /></div>
	</form>
</main>
	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>

	</body>
</html>
