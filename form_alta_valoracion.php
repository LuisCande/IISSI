<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");


	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formularioV'])) {
		$formularioV['asunto'] = "";
		$formularioV['descripcion'] = "";
		$formularioV['email']= $_SESSION['login'];

		$_SESSION['formularioV'] = $formularioV;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formularioV = $_SESSION['formularioV'];

	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}


	// Creamos una conexión con la BD
	$conexion = crearConexionBD();
	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){
		$_SESSION["excepcion"]= "No tienes permisos de cliente";
		Header("Location: excepcion.php");
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
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
  <script src="js/validacion_cliente_alta_usuario.js" type="text/javascript"></script>
  <title>Motos Cande: Añadir una valoración</title>
</head>

<body>

	<?php

	include_once ("cabecera.php");

	include_once ("menu.php");
	?>
<main id="mainregistro2">
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

	<form id="altaValoracion" method="get" action="accion_alta_valoracion.php" onsubmit="return validateForm()">
		<p><i>&nbsp  Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos de la valoración</legend>

			<div></div><label for="asunto">Asunto:<em>*</em></label>
			<input id="asunto" name="asunto" type="text" size= "40" value="<?php echo $formularioV['asunto'];?>" required></br>
			</div>
</br>
			<div><label for="descripcion">Descripción:<em>*</em></label></br>
		<textarea id="descripcion" name="descripcion" rows="5" cols="40" maxlength="250"><?php echo $formularioV['descripcion'];?></textarea>
			</div>

							<input id="email" name="email" type="text" hidden value="<?php echo $formularioV['email'];?>" />
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
