<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarProductos.php");
	require_once("paginacion_consulta.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioR"])) {
		// Recogemos los datos del formulario
		$nuevoRecambio["codigo"] = $_REQUEST["codigo"];
		$nuevoRecambio["nombre"] = $_REQUEST["nombre"];
		$nuevoRecambio["descripcion"] = $_REQUEST["descripcion"];
		$nuevoRecambio["marca"] = $_REQUEST["marca"];
		$nuevoRecambio["precio"] = $_REQUEST["precio"];
		$nuevoRecambio["oferta"] = $_REQUEST["oferta"];
		$nuevoRecambio["iva"] = $_REQUEST["iva"];
		$nuevoRecambio["stock"] = $_REQUEST["stock"];
		$nuevoRecambio["stockMinimo"] = $_REQUEST["stockMinimo"];
		$nuevoRecambio["codigoProveedor"] = $_REQUEST["codigoProveedor"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_recambio.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioR"] = $nuevoRecambio;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosRecambio($conexion, $nuevoRecambio);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_recambio.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_recambio.php');
	} else{
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		alta_recambio($conexion, $nuevoRecambio);
 		unset($_SESSION['formularioR']);
		Header('Location: consulta_recambio.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosRecambio($conexion, $nuevoRecambio){
	$errores=array();
	// Validación del NIF



				if($nuevoRecambio["stock"]<$nuevoRecambio["stockMinimo"])
		$errores[] = "<p>El stock no puede ser menor que el stock mínimo</p>";


		$check="SELECT * FROM PRODUCTO WHERE codigo = '".$nuevoRecambio["codigo"]."'";
		$codigoOcupado = (int) total_consulta($conexion, $check);
				if( $codigoOcupado > 0 ){
		    $errores[] = "<p>Ya existe un producto con este código</p>";
		}
		$check="SELECT * FROM PRODUCTO WHERE nombre = '".$nuevoRecambio["nombre"]."'";
		$nombreOcupado = (int) total_consulta($conexion, $check);
				if( $nombreOcupado > 0 ){
				$errores[] = "<p>Ya existe un producto con este nombre</p>";
		}


		$check="SELECT * FROM PROVEEDOR WHERE codigo = '".$nuevoRecambio["codigoProveedor"]."'";
		$codigoProve = (int) total_consulta($conexion, $check);
				if( $codigoProve < 1 ){
				$errores[] = "<p>No existe ningún proveedor con este código</p>"	;
		}

				if($nuevoRecambio["codigo"]=="" || strlen($nuevoMotor["codigo"])>9)
			$errores[] = "<p>El código no puede estar vacío ni puede superar los 9 dígitos</p>";


				if($nuevoRecambio["precio"]=="" || strlen($nuevoRecambio["precio"])>11)
			$errores[] = "<p>El precio no puede estar vacío ni puede superar los 11 dígitos</p>";
			else if(!preg_match("/^\d{1,}[.,]{0,1}\d{1,2}$/", $nuevoRecambio["precio"])){
				$errores[] = "<p>El precio debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoMotor["precio"]. "</p>";
			}

				if(strlen($nuevoRecambio["oferta"])>10)
				$errores[] = "<p>La oferta no puede superar los 10 dígitos</p>";
				else if(!preg_match("/^\d{0,}[.,]{0,1}\d{0,2}$/", $nuevoRecambio["oferta"])){
					$errores[] = "<p>El oferta debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoMotor["oferta"]. "</p>";
				}

				if(!preg_match("/^[0-9]{1}[.,]{1}[0-9]{2}$/", $nuevoRecambio["iva"])){
					$errores[] = "<p>El iva debe estar compuesto de un dígito, una coma o punto y otros dos dígitos: " . $nuevoMotor["iva"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoRecambio["stock"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoMotor["stock"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoRecambio["stockMinimo"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoMotor["stockMinimo"]. "</p>";
				}

	return $errores;
}
?>
