<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");
	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		// Recogemos los datos del formulario
		$nuevoUsuario["telefono"] = $_REQUEST["telefono"];
		$nuevoUsuario["email"] = $_REQUEST["email"];
		$nuevoUsuario["contraseña"] = $_REQUEST["contraseña"];
		$nuevoUsuario["confirmcontraseña"] = $_REQUEST["confirmcontraseña"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_dependiente.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formulario"] = $nuevoUsuario;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosDependiente($conexion, $nuevoUsuario);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_dependiente.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_dependiente.php');
	} else
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		Header('Location: exito_alta_dependiente.php');

///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosDependiente($conexion, $nuevoUsuario){
	$errores=array();

	if(!preg_match("/^[9|6|7][0-9]{8}$/", $nuevoUsuario["telefono"]))
		$errores[] = "<p>El teléfono debe contener 9 números</p>";


		$check="SELECT * FROM USUARIO WHERE telefono = '".$nuevoUsuario["telefono"]."'";
		$telefonoUnico = (int) total_consulta($conexion, $check);
				if( $telefonoUnico> 0  ){
				$errores[] = "<p>Ya existe algún usuario con este telefono</p>"	;
		}

	// Validación del email
	if($nuevoUsuario["email"]=="")
		$errores[] = "<p>El email no puede estar vacío</p>";
	else if(!filter_var($nuevoUsuario["email"], FILTER_VALIDATE_EMAIL))
		$errores[] = "<p>El email es incorrecto: " . $nuevoUsuario["email"]. "</p>";



	// Validación de la contraseña
	if(!isset($nuevoUsuario["contraseña"]) || strlen($nuevoUsuario["contraseña"])<8)
		$errores [] = "<p>Contraseña no válida: debe tener al menos 8 caracteres</p>";
	else if(!preg_match("/[a-z]+/", $nuevoUsuario["contraseña"]) ||
		!preg_match("/[A-Z]+/", $nuevoUsuario["contraseña"]) || !preg_match("/[0-9]+/", $nuevoUsuario["contraseña"]))
		$errores[] = "<p>Contraseña no válida: debe contener letras mayúsculas y minúsculas y dígitos</p>";
	else if($nuevoUsuario["contraseña"] != $nuevoUsuario["confirmcontraseña"])
		$errores[] = "<p>La confirmación de contraseña no coincide con la contraseña</p>";

	return $errores;
	}
?>
