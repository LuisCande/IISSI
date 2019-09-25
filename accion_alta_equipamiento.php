<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarProductos.php");
	require_once("paginacion_consulta.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioE"])) {
		// Recogemos los datos del formulario
		$nuevoEquipamiento["codigo"] = $_REQUEST["codigo"];
		$nuevoEquipamiento["nombre"] = $_REQUEST["nombre"];
		$nuevoEquipamiento["descripcion"] = $_REQUEST["descripcion"];
		$nuevoEquipamiento["marca"] = $_REQUEST["marca"];
		$nuevoEquipamiento["precio"] = $_REQUEST["precio"];
		$nuevoEquipamiento["oferta"] = $_REQUEST["oferta"];
		$nuevoEquipamiento["iva"] = $_REQUEST["iva"];
		$nuevoEquipamiento["stock"] = $_REQUEST["stock"];
		$nuevoEquipamiento["stockMinimo"] = $_REQUEST["stockMinimo"];
		$nuevoEquipamiento["codigoProveedor"] = $_REQUEST["codigoProveedor"];
		$nuevoEquipamiento["color"] = $_REQUEST["color"];
		$nuevoEquipamiento["material"] = $_REQUEST["material"];
		$nuevoEquipamiento["talla"] = $_REQUEST["talla"];

}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_equipamiento.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioE"] = $nuevoEquipamiento;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosEquipamiento($conexion, $nuevoEquipamiento);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_alta_equipamiento.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_equipamiento.php');
	} else{
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		alta_equipamiento($conexion, $nuevoEquipamiento);
 		unset($_SESSION['formularioE']);
		Header('Location: consulta_equipamiento.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosEquipamiento($conexion, $nuevoEquipamiento){
	$errores=array();

				if($nuevoEquipamiento["stock"]<$nuevoEquipamiento["stockMinimo"])
		$errores[] = "<p>El stock no puede ser menor que el stock mínimo</p>";


		$check="SELECT * FROM PRODUCTO WHERE codigo = '".$nuevoEquipamiento["codigo"]."'";
		$codigoOcupado = (int) total_consulta($conexion, $check);
				if( $codigoOcupado > 0 ){
		    $errores[] = "<p>Ya existe un producto con este código</p>";
		}

		$check="SELECT * FROM PRODUCTO WHERE nombre = '".$nuevoEquipamiento["nombre"]."'";
		$nombreOcupado = (int) total_consulta($conexion, $check);
				if( $nombreOcupado > 0 ){
				$errores[] = "<p>Ya existe un producto con este nombre</p>";
		}


		$check="SELECT * FROM PROVEEDOR WHERE codigo = '".$nuevoEquipamiento["codigoProveedor"]."'";
		$codigoProve = (int) total_consulta($conexion, $check);
				if( $codigoProve < 1 ){
				$errores[] = "<p>No existe ningún proveedor con este código</p>"	;
		}

				if($nuevoEquipamiento["codigo"]=="" || strlen($nuevoEquipamiento["codigo"])>9)
			$errores[] = "<p>El código no puede estar vacío ni puede superar los 9 dígitos</p>";


				if($nuevoEquipamiento["precio"]=="" || strlen($nuevoEquipamiento["precio"])>11)
			$errores[] = "<p>El precio no puede estar vacío ni puede superar los 11 dígitos</p>";
			else if(!preg_match("/^\d{1,}[.,]{0,1}\d{1,2}$/", $nuevoEquipamiento["precio"])){
				$errores[] = "<p>El precio debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoEquipamiento["precio"]. "</p>";
			}

				if(strlen($nuevoEquipamiento["oferta"])>10)
				$errores[] = "<p>La oferta no puede superar los 10 dígitos</p>";
				else if(!preg_match("/^\d{0,}[.,]{0,1}\d{0,2}$/", $nuevoEquipamiento["oferta"])){
					$errores[] = "<p>El oferta debe estar compuesto de números, una coma o punto y otros dos dígitos como máximo: " . $nuevoEquipamiento["oferta"]. "</p>";
				}

				if(!preg_match("/^[0-9]{1}[.,]{1}[0-9]{2}$/", $nuevoEquipamiento["iva"])){
					$errores[] = "<p>El iva debe estar compuesto de un dígito, una coma o punto y otros dos dígitos: " . $nuevoEquipamiento["iva"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoEquipamiento["stock"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoEquipamiento["stock"]. "</p>";
				}
				if(!preg_match("/^[0-9]+/", $nuevoEquipamiento["stockMinimo"])){
					$errores[] = "<p>El stock debe estar compuesto exclusivamente de dígitos:" . $nuevoEquipamiento["stockMinimo"]. "</p>";
				}
				if(strlen($nuevoEquipamiento["color"]) <= 0 || strlen($nuevoEquipamiento["color"]) > 25){
					$errores[] = "<p>El color no puede estar vacío ni tener más de 25 caracteres:" . $nuevoEquipamiento["color"]. "</p>";
				}
				if(strlen($nuevoEquipamiento["talla"]) <= 0){
					$errores[] = "<p>Debe indicar la talla correctamente: " . $nuevoEquipamiento["talla"]. "</p>";
				}
				if(strlen($nuevoEquipamiento["material"]) <= 0 || strlen($nuevoEquipamiento["material"]) > 25){
					$errores[] = "<p>El material no puede estar vacío ni tener más de 25 caracteres: " . $nuevoEquipamiento["talla"]. "</p>";
				}







	return $errores;
}
?>
