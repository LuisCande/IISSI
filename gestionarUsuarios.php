<?php
  /*
     * #===========================================================#
     * #	Este fichero contiene las funciones de gestión
     * #	de usuarios de la capa de acceso a datos
     * #==========================================================#
     */


function alta_usuario($conexion,$usuario) {
	try {
		$consulta = "CALL crearCliente(:email, :telefono, :contraseña, :nombre, :primerApellido, :segundoApellido, :dni, :direccion)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':email',$usuario["email"]);
		$stmt->bindParam(':telefono',$usuario["telefono"]);
		$stmt->bindParam(':contraseña',$usuario["contraseña"]);
	//	$stmt->bindParam(':tipoUsuario',$usuario["tipoUsuario"]);
		$stmt->bindParam(':nombre',$usuario["nombre"]);
		$stmt->bindParam(':primerApellido',$usuario["primerApellido"]);
		$stmt->bindParam(':segundoApellido',$usuario["segundoApellido"]);
		$stmt->bindParam(':dni',$usuario["dni"]);
		$stmt->bindParam(':direccion',$usuario["direccion"]);

		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}


function alta_dependiente($conexion,$usuario) {
	try {
		$consulta = "CALL crearDependiente(:email, :telefono, :contraseña)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':email',$usuario["email"]);
		$stmt->bindParam(':telefono',$usuario["telefono"]);
		$stmt->bindParam(':contraseña',$usuario["contraseña"]);

		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}


function consultarUsuario($conexion,$email,$contraseña) {
 	$consulta = "SELECT COUNT(*) AS TOTAL FROM USUARIO WHERE email=:email AND contraseña=:contraseña";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':email',$email);
	$stmt->bindParam(':contraseña',$contraseña);
	$stmt->execute();
	return $stmt->fetchColumn();
}
?>
