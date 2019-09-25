<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");



	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formularioP'])) {
		$formularioP['codigo'] = "";
		$formularioP['nombre'] = "";
		$formularioP['email'] = "";
		$formularioP['telefono'] = "";
		$formularioP['direccion'] = "";
		$formularioP['web'] = "";
		$_SESSION['formularioP'] = $formularioP;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formularioP = $_SESSION['formularioP'];

	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}


	// Creamos una conexión con la BD
	$conexion = crearConexionBD();
	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Gerente'){
		$_SESSION["excepcion"]= "No tienes los permisos suficientes para acceder a esta página";
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
  <title>Motos Cande: Alta de Proveedores</title>
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

	<form id="altaProveedor" method="get" action="accion_alta_proveedor.php" onsubmit="return validateForm()">
		<p><i>&nbsp   Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos del proveedor</legend>
			<div></div><label for="codigo">Código<em>*</em></label>
			<input id="codigo" name="codigo" type="text"  pattern="^[0-9]{7}" title="Siete dígitos" value="<?php echo $formularioP['codigo'];?>" required>
			</div>

			<div><label for="nombre">Nombre:<em>*</em></label>
			<input id="nombre" name="nombre" type="text" size="40" value="<?php echo $formularioP['nombre'];?>" required/>
			</div>

			<div><label for="telefono">Teléfono:<em>*</em></label>
				<input id="telefono" name="telefono" type="text" size="40" value="<?php echo $formularioP['telefono'];?>" required />
			</div>

			<div><label for="direccion">Dirección:<em>*</em></label>
				<input id="direccion" name="direccion" type="text" size="40" value="<?php echo $formularioP['direccion'];?>" required/>
			</div>

			<div><label for="email">Email:<em>*</em></label>
			<input id="email" name="email"  type="email" placeholder="usuario@dominio.extension" value="<?php echo $formularioP['email'];?>" required/><br>
			</div>

			<div><label for="web">Web:</label>
				<input id="web" name="web" type="text" size="50" value="<?php echo $formularioP['web'];?>" />
			</div>

		</fieldset>
		<div> <input type="submit" value="Enviar" /></div>
	</form>
</main>
	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>

	</body>
</html>
