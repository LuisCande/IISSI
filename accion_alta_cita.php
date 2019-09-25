<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionarCitas.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formularioC"]) ) {
		// Recogemos los datos del formulario
		$nuevaCita["fechaAcordada"] = date("d-m-Y", strtotime($_REQUEST["fechaAcordada"]));
		$nuevaCita["email"] = $_REQUEST["email"];
		$nuevaCita["asunto"] = $_REQUEST["asunto"];
		$nuevaCita["descripcion"] = $_REQUEST["descripcion"];
		$nuevaCita["tipoCita"] = $_REQUEST["tipoCita"];
}

		else // En caso contrario, vamos al formulario
			Header("Location: form_alta_cita.php");


	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formularioC"] = $nuevaCita;

	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
	try{
		$conexion = crearConexionBD();
		$errores = validarDatosCita($conexion, $nuevaCita);
		cerrarConexionBD($conexion);
	}catch(PDOException $e){
		// Mensaje de depuración
		$_SESSION["errores"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p>" . $e->getMessage() . "</p>";
		Header('Location: form_cita.php');
	}

	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_cita.php');
	} else{
		// Si todo va bien, vamos a la página de éxito (inserción del usuario en la base de datos)
		realizar_cita($conexion, $nuevaCita);
		unset($_SESSION['formularioC']);
		Header('Location: consulta_citas.php');
}
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosCita($conexion, $nuevaCita){
	$errores=array();
	// Validación del NIF
	if($nuevaCita["fechaAcordada"]=="")
		$errores[] = "<p>La fecha no puede estar vacía</p>";

		$hoy=date("d-m-Y");
		$fechaini=explode("-",$hoy);
		$fechafin=explode("-",$nuevaCita["fechaAcordada"]);
		if(($fechaini[2]>$fechafin[2])||($fechaini[2]==$fechafin[2] && $fechaini[1]>$fechafin[1])||($fechaini[2]==$fechafin[2] && $fechaini[1]==$fechafin[1] && $fechaini[0]>$fechafin[0]))
		{
	$errores[] ="La fecha acordada es anterior a hoy";
		}


	// Validación del Nombre
	if($nuevaCita["asunto"]=="")
		$errores[] = "<p>El asunto no puede estar vacío</p>";

	// Validación del Nombre
	if($nuevaCita["descripcion"]=="")
		$errores[] = "<p>La descripción no puede estar vacía</p>";

	return $errores;
	}
?>
