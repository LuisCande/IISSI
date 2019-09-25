<?php

function alta_valoracion($conexion,$valoracion) {

	try {
		$consulta = "CALL CREARVALORACIONEMAIL(:asunto, :descripcion, :email)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':asunto',$valoracion["asunto"]);
		$stmt->bindParam(':descripcion',$valoracion["descripcion"]);
		$stmt->bindParam(':email',$valoracion["email"]);

		$stmt->execute();

	} catch(PDOException $e) {

		return false;
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
    }
}

function consultarTodasValoraciones($conexion) {
	$consulta = "SELECT * FROM VALORACION"
		. " ORDER BY ASUNTO";
    return $conexion->query($consulta);
}

?>
