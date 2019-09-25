<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarProductos.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_REQUEST["OID_PE"])) {
		// Recogemos los datos del formulario
		$carrito["fechaEntrega"] = date("d-m-Y", strtotime($_REQUEST["fechaEntrega"]));
		$carrito["OID_PE"] = $_REQUEST["OID_PE"];
		$carrito["ENVIO"] = $_REQUEST["envio"];
		$carrito["TIPOPAGO"] = $_REQUEST["tipoPago"];
}
		else // En caso contrario, vamos al formulario
			Header("Location: login.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["carrito"] = $carrito;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosCita($conexion, $carrito);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: login.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: consulta_carrito.php');
	} else{
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		comprar_carrito($conexion, $carrito);
		unset($_SESSION['carrito']);
		$_SESSION["exito"] = 1;
		Header('Location: consulta_carrito.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosCita($conexion, $carrito){
	$errores=array();
	// Validación del NIF
	if($carrito["OID_PE"]=="")
		$errores[] = "<p>El OID_PE no puede estar vacía</p>";
	if($carrito["fechaEntrega"]=="")
		$errores[] = "<p>La fecha no puede estar vacía</p>";

		$hoy=date("d-m-Y");
		$fechaini=explode("-",$hoy);
		$fechafin=explode("-",$carrito["fechaEntrega"]);
		if(($fechaini[2]>$fechafin[2])||($fechaini[2]==$fechafin[2] && $fechaini[1]>$fechafin[1])||($fechaini[2]==$fechafin[2] && $fechaini[1]==$fechafin[1] && $fechaini[0]>$fechafin[0]))
		{
	$errores[] ="La fecha acordada es anterior a hoy";
		}
	return $errores;
	}
?>
