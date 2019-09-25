<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarProductos.php");
	require_once("paginacion_consulta.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioM"])) {
		// Recogemos los datos del formulario
		$nuevoMotor["codigo"] = $_REQUEST["codigo"];
		$nuevoMotor["nombre"] = $_REQUEST["nombre"];
		$nuevoMotor["descripcion"] = $_REQUEST["descripcion"];
		$nuevoMotor["marca"] = $_REQUEST["marca"];
		$nuevoMotor["precio"] = $_REQUEST["precio"];
		$nuevoMotor["oferta"] = $_REQUEST["oferta"];
		$nuevoMotor["iva"] = $_REQUEST["iva"];
		$nuevoMotor["stock"] = $_REQUEST["stock"];
		$nuevoMotor["stockMinimo"] = $_REQUEST["stockMinimo"];
		$nuevoMotor["codigoProveedor"] = $_REQUEST["codigoProveedor"];
		$nuevoMotor["cilindrada"] = $_REQUEST["cilindrada"];
		$nuevoMotor["estado"] = $_REQUEST["estado"];
		$nuevoMotor["anyoFabricacion"] = $_REQUEST["anyoFabricacion"];
		$nuevoMotor["tipoMotor"] = $_REQUEST["tipoMotor"];
		$nuevoMotor["garantia"] = $_REQUEST["garantia"];
}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_motor.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioM"] = $nuevoMotor;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosMotor($conexion, $nuevoMotor);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_motor.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_motor.php');
	} else{
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		alta_motor($conexion, $nuevoMotor);
 		unset($_SESSION['formularioM']);
		Header('Location: consulta_motor.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosMotor($conexion, $nuevoMotor){
	$errores=array();
	// Validación del NIF

				if($nuevoMotor["garantia"]<0 OR $nuevoMotor["garantia"]>7)
		$errores[] = "<p>La garantia no puede ser menor que 0 ni mayor que 7 </p>";

				if($nuevoMotor["stock"]<$nuevoMotor["stockMinimo"])
		$errores[] = "<p>El stock no puede ser menor que el stock mínimo</p>";


		$check="SELECT * FROM PRODUCTO WHERE codigo = '".$nuevoMotor["codigo"]."'";
		$codigoOcupado = (int) total_consulta($conexion, $check);
				if( $codigoOcupado > 0 ){
		    $errores[] = "<p>Ya existe un producto con este código</p>";
		}
		$check="SELECT * FROM PRODUCTO WHERE nombre = '".$nuevoMotor["nombre"]."'";
		$nombreOcupado = (int) total_consulta($conexion, $check);
				if( $nombreOcupado > 0 ){
				$errores[] = "<p>Ya existe un producto con este nombre</p>";
		}


		$check="SELECT * FROM PROVEEDOR WHERE codigo = '".$nuevoMotor["codigoProveedor"]."'";
		$codigoProve = (int) total_consulta($conexion, $check);
				if( $codigoProve < 1 ){
				$errores[] = "<p>No existe ningún proveedor con este código</p>"	;
		}

				if($nuevoMotor["codigo"]=="" || strlen($nuevoMotor["codigo"])>9)
			$errores[] = "<p>El código no puede estar vacío ni puede superar los 9 dígitos</p>";


				if($nuevoMotor["precio"]=="" || strlen($nuevoMotor["precio"])>11)
			$errores[] = "<p>El precio no puede estar vacío ni puede superar los 11 dígitos</p>";
			else if(!preg_match("/^\d{1,}[.,]{0,1}\d{1,2}$/", $nuevoMotor["precio"])){
				$errores[] = "<p>El precio debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoMotor["precio"]. "</p>";
			}

				if(strlen($nuevoMotor["oferta"])>10)
				$errores[] = "<p>La oferta no puede superar los 10 dígitos</p>";
				else if(!preg_match("/^\d{0,}[.,]{0,1}\d{0,2}$/", $nuevoMotor["oferta"])){
					$errores[] = "<p>El oferta debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoMotor["oferta"]. "</p>";
				}

				if(!preg_match("/^[0-9]{1}[.,]{1}[0-9]{2}$/", $nuevoMotor["iva"])){
					$errores[] = "<p>El iva debe estar compuesto de un dígito, una coma o punto y otros dos dígitos: " . $nuevoMotor["iva"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoMotor["stock"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoMotor["stock"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoMotor["stockMinimo"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoMotor["stockMinimo"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoMotor["cilindrada"])){
					$errores[] = "<p>Las cilindradas deben estar compuestas exclusivamente de dígitos:" . $nuevoMotor["cilindrada"]. "</p>";
				}
				if(!preg_match("/^[0-9]{4}$/", $nuevoMotor["anyoFabricacion"])){
					$errores[] = "<p>El año de fabricacion deben ser 4 dígitos exactamente: " . $nuevoMotor["anyoFabricacion"]. "</p>";
				}
				if(!preg_match("/^[0-7]{1}$/", $nuevoMotor["garantia"])){
					$errores[] = "<p>Los años de garantia deben ser un número comprendido entre el 0 y el 7: " . $nuevoMotor["garantia"]. "</p>";
				}







	return $errores;
}
?>
