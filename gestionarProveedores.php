<?php

   function alta_proveedor($conexion,$proveedor) {

	try {
		$consulta = "CALL CREARPROVEEDOR(:codigo, :nombre, :email, :telefono, :direccion, :web)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':codigo',$proveedor["codigo"]);
		$stmt->bindParam(':nombre',$proveedor["nombre"]);
		$stmt->bindParam(':email',$proveedor["email"]);
		$stmt->bindParam(':telefono',$proveedor["telefono"]);
		$stmt->bindParam(':direccion',$proveedor["direccion"]);
		$stmt->bindParam(':web',$proveedor["web"]);

		$stmt->execute();

	} catch(PDOException $e) {
		return false;
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
    }
}

function consultarTodosProveedores($conexion) {
	$consulta = "SELECT * FROM PROVEEDOR"
		. " ORDER BY NOMBRE";
    return $conexion->query($consulta);
}

function quitar_proveedor($conexion,$OID_Prov) {
	try {
		$stmt=$conexion->prepare('CALL QUITAR_PROVEEDOR(:OID_Prov)');
		$stmt->bindParam(':OID_Prov',$OID_Prov);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function modificar_proveedor($conexion,$OID_Prov,$email,$telefono,$direccion,$web) {
	try {
		$stmt=$conexion->prepare('CALL MODIFICAR_PROVEEDOR(:OID_Prov,:email,:telefono,:direccion,:web)');
		$stmt->bindParam(':OID_Prov',$OID_Prov);
		$stmt->bindParam(':email',$email);
		$stmt->bindParam(':telefono',$telefono);
		$stmt->bindParam(':direccion',$direccion);
		$stmt->bindParam(':web',$web);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

?>
