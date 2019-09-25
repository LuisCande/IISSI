<?php
  /*
     * #===========================================================#
     * #	Este fichero contiene las funciones de gestión
     * #	de libros de la capa de acceso a datos
     * #==========================================================#
     */


function realizar_cita($conexion,$nuevaCita) {
 try {

	 $consulta = "CALL CREARCITAEMAIL(:fechaAcordada, :asunto, :descripcion, :tipoCita, :email)";
	 $stmt=$conexion->prepare($consulta);
	 $stmt->bindParam(':fechaAcordada',$nuevaCita["fechaAcordada"]);
	 $stmt->bindParam(':asunto',$nuevaCita["asunto"]);
	 $stmt->bindParam(':descripcion',$nuevaCita["descripcion"]);
	 $stmt->bindParam(':tipoCita',$nuevaCita["tipoCita"]);
	 $stmt->bindParam(':email',$nuevaCita["email"]);

	 $stmt->execute();

 } catch(PDOException $e) {
   echo $e;
  return false;
 	// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
 }
}

function consultarCitas($conexion) {
	$consulta = "SELECT * FROM CITA"
		. " ORDER BY ASUNTO";
    return $conexion->query($consulta);
}

?>
