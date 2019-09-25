<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarValoraciones.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioV"])) {
		// Recogemos los datos del formulario
		$nuevaValoracion["asunto"] = $_REQUEST["asunto"];
		$nuevaValoracion["descripcion"] = $_REQUEST["descripcion"];
		$nuevaValoracion["email"] = $_REQUEST["email"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_valoracion.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioV"] = $nuevaValoracion;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosValoracion($conexion, $nuevaValoracion);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_valoracion.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_valoracion.php');
	} else {
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		alta_valoracion($conexion, $nuevaValoracion);
		unset($_SESSION['formularioV']);
		Header('Location: consulta_valoraciones.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosValoracion($conexion, $nuevaValoracion){
	$errores=array();
	// Validación del asunto
	if($nuevaValoracion["asunto"]=="" || strlen($nuevaValoracion["asunto"])>25)
		$errores[] = "<p>El asunto no puede estar vacío o tener más de 25 caracteres</p>";

	// Validación de la descripcion
	if($nuevaValoracion["descripcion"]=="")
		$errores[] = "<p>La descripción no puede estar vacía</p>";

	return $errores;
	}
?>
