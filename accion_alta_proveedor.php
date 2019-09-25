<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarProveedores.php");
	require_once("paginacion_consulta.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioP"])) {
		// Recogemos los datos del formulario
		$nuevoProveedor["codigo"] = $_REQUEST["codigo"];
		$nuevoProveedor["nombre"] = $_REQUEST["nombre"];
		$nuevoProveedor["email"] = $_REQUEST["email"];
		$nuevoProveedor["telefono"] = $_REQUEST["telefono"];
		$nuevoProveedor["direccion"] = $_REQUEST["direccion"];
		$nuevoProveedor["web"] = $_REQUEST["web"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_proveedor.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioP"] = $nuevoProveedor;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosProveedor($conexion, $nuevoProveedor);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_proveedor.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_proveedor.php');
	} else {
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		alta_proveedor($conexion, $nuevoProveedor);
		//unset($_SESSION['formularioP']);
		Header('Location: consulta_proveedores.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosProveedor($conexion, $nuevoProveedor){
	$errores=array();
	// Validación del NIF
	if($nuevoProveedor["codigo"]=="" || strlen($nuevoProveedor["codigo"])>7)
		$errores[] = "<p>El código no puede estar vacío ni puede superar los 7 dígitos</p>";

	// Validación del Nombre
	if($nuevoProveedor["nombre"]=="")
		$errores[] = "<p>El nombre no puede estar vacío</p>";

	if(!preg_match("/^[9|6|7][0-9]{8}$/", $nuevoProveedor["telefono"]))
		$errores[] = "<p>El teléfono debe contener 9 números y empezar por 9, 6 ó 7</p>";

	// Validación de la dirección
	if($nuevoProveedor["direccion"]=="")
		$errores[] = "<p>La dirección no puede estar vacía</p>";


	// Validación del email
	if($nuevoProveedor["email"]=="")
		$errores[] = "<p>El email no puede estar vacío</p>";
	else if(!filter_var($nuevoProveedor["email"], FILTER_VALIDATE_EMAIL))
		$errores[] = "<p>El email es incorrecto: " . $nuevoProveedor["email"]. "</p>";




		$check="SELECT * FROM PROVEEDOR WHERE codigo = '".$nuevoProveedor["codigo"]."'";
		$codigoProv = (int) total_consulta($conexion, $check);
				if( $codigoProv> 0  ){
				$errores[] = "<p>Ya existe algún proveedor con este código</p>"	;
		}

		$check="SELECT * FROM PROVEEDOR WHERE telefono = '".$nuevoProveedor["telefono"]."'";
		$telefonoUnico = (int) total_consulta($conexion, $check);
				if( $telefonoUnico> 0  ){
				$errores[] = "<p>Ya existe algún proveedor con este teléfono</p>"	;
		}

		$check="SELECT * FROM PROVEEDOR WHERE email = '".$nuevoProveedor["email"]."'";
		$emailUnico = (int) total_consulta($conexion, $check);
				if( $emailUnico> 0  ){
				$errores[] = "<p>Ya existe algún proveedor con este email</p>"	;
		}
		$check="SELECT * FROM PROVEEDOR WHERE nombre = '".$nuevoProveedor["nombre"]."'";
		$nombreUnico = (int) total_consulta($conexion, $check);
				if( $nombreUnico> 0  ){
				$errores[] = "<p>Ya existe algún proveedor con este nombre</p>"	;
		}
return $errores;
}
?>
