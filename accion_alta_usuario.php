<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");
	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		// Recogemos los datos del formulario
		$nuevoUsuario["dni"] = $_REQUEST["dni"];
		$nuevoUsuario["nombre"] = $_REQUEST["nombre"];
		$nuevoUsuario["primerApellido"] = $_REQUEST["primerApellido"];
		$nuevoUsuario["segundoApellido"] = $_REQUEST["segundoApellido"];
		$nuevoUsuario["telefono"] = $_REQUEST["telefono"];
		$nuevoUsuario["direccion"] = $_REQUEST["direccion"];
		$nuevoUsuario["email"] = $_REQUEST["email"];
		$nuevoUsuario["contraseña"] = $_REQUEST["contraseña"];
		$nuevoUsuario["confirmcontraseña"] = $_REQUEST["confirmcontraseña"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_usuario.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formulario"] = $nuevoUsuario;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosUsuario($conexion, $nuevoUsuario);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_usuario.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_usuario.php');
	} else
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		Header('Location: exito_alta_usuario.php');

///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosUsuario($conexion, $nuevoUsuario){
	$errores=array();
	// Validación del NIF
	if($nuevoUsuario["dni"]=="")
		$errores[] = "<p>El DNI no puede estar vacío</p>";
	else if(!preg_match("/^[0-9]{8}[A-Z]$/", $nuevoUsuario["dni"])){
		$errores[] = "<p>El DNI debe contener 8 números y una letra mayúscula: " . $nuevoUsuario["dni"]. "</p>";
	}

//Validación del Nombre
	if($nuevoUsuario["nombre"]=="")
		$errores[] = "<p>El nombre no puede estar vacío</p>";

	if($nuevoUsuario["primerApellido"]=="" )
		$errores[] = "<p>El primer aperllido no puede estar vacío</p>";

	if($nuevoUsuario["segundoApellido"]=="")
		$errores[] = "<p>El segundo apellido no puede estar vacío</p>";

	if(!preg_match("/^[9|6|7][0-9]{8}$/", $nuevoUsuario["telefono"]))
		$errores[] = "<p>El teléfono debe contener 9 números</p>";

	// Validación de la dirección
	if($nuevoUsuario["direccion"]=="")
		$errores[] = "<p>La dirección no puede estar vacía</p>";

		$check="SELECT * FROM USUARIO WHERE email = '".$nuevoUsuario["email"]."'";
		$emailUnico = (int) total_consulta($conexion, $check);
				if( $emailUnico> 0  ){
				$errores[] = "<p>Ya existe algún usuario con este email</p>"	;
		}

		$check="SELECT * FROM USUARIO WHERE telefono = '".$nuevoUsuario["telefono"]."'";
		$telefonoUnico = (int) total_consulta($conexion, $check);
				if( $telefonoUnico> 0  ){
				$errores[] = "<p>Ya existe algún usuario con este telefono</p>"	;
		}

		$check="SELECT * FROM CLIENTE WHERE dni = '".$nuevoUsuario["dni"]."'";
		$dniUnico = (int) total_consulta($conexion, $check);
				if( $dniUnico> 0  ){
				$errores[] = "<p>Ya existe algún usuario con este dni</p>"	;
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
