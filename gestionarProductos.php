<?php


function alta_motor($conexion,$nuevoMotor) {

	$nuevoMotor["precio"]=str_replace('.', ',', $nuevoMotor["precio"]);
	$nuevoMotor["oferta"]=str_replace('.', ',', $nuevoMotor["oferta"]);
	$nuevoMotor["oferta"]="0,".$nuevoMotor["oferta"];
	$nuevoMotor["iva"]=str_replace('.', ',', $nuevoMotor["iva"]);
	$nuevoMotor["cilindrada"]=$nuevoMotor["cilindrada"].' cc';
	try {
		$consulta = "CALL NUEVOCREARMOTOR(:codigo, :nombre, :descripcion, :marca, :precio, :oferta, :iva, :stock, :stockMinimo, :codigoProveedor, :cilindrada, :estado, :anyoFabricacion, :tipoMotor, :garantia)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':codigo',$nuevoMotor["codigo"]);
		$stmt->bindParam(':nombre',$nuevoMotor["nombre"]);
		$stmt->bindParam(':descripcion',$nuevoMotor["descripcion"]);
		$stmt->bindParam(':marca',$nuevoMotor["marca"]);
		$stmt->bindParam(':precio', $nuevoMotor["precio"]);
		$stmt->bindParam(':oferta',$nuevoMotor["oferta"]);
		$stmt->bindParam(':iva',$nuevoMotor["iva"]);
		$stmt->bindParam(':stock',$nuevoMotor["stock"]);
		$stmt->bindParam(':stockMinimo',$nuevoMotor["stockMinimo"]);
		$stmt->bindParam(':codigoProveedor',$nuevoMotor["codigoProveedor"]);
		$stmt->bindParam(':cilindrada',$nuevoMotor["cilindrada"]);
		$stmt->bindParam(':estado',$nuevoMotor["estado"]);
		$stmt->bindParam(':anyoFabricacion',$nuevoMotor["anyoFabricacion"]);
		$stmt->bindParam(':tipoMotor',$nuevoMotor["tipoMotor"]);
		$stmt->bindParam(':garantia',$nuevoMotor["garantia"]);

		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}

function alta_recambio($conexion,$nuevoRecambio) {

	$nuevoRecambio["precio"]=str_replace('.', ',', $nuevoRecambio["precio"]);
	$nuevoRecambio["oferta"]=str_replace('.', ',', $nuevoRecambio["oferta"]);
	$nuevoRecambio["oferta"]="0,".$nuevoRecambio["oferta"];
	$nuevoRecambio["iva"]=str_replace('.', ',', $nuevoRecambio["iva"]);
	try {
		$consulta = "CALL NUEVOCREARRECAMBIO(:codigo, :nombre, :descripcion, :marca, :precio, :oferta, :iva, :stock, :stockMinimo, :codigoProveedor)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':codigo',$nuevoRecambio["codigo"]);
		$stmt->bindParam(':nombre',$nuevoRecambio["nombre"]);
		$stmt->bindParam(':descripcion',$nuevoRecambio["descripcion"]);
		$stmt->bindParam(':marca',$nuevoRecambio["marca"]);
		$stmt->bindParam(':precio', $nuevoRecambio["precio"]);
		$stmt->bindParam(':oferta',$nuevoRecambio["oferta"]);
		$stmt->bindParam(':iva',$nuevoRecambio["iva"]);
		$stmt->bindParam(':stock',$nuevoRecambio["stock"]);
		$stmt->bindParam(':stockMinimo',$nuevoRecambio["stockMinimo"]);
		$stmt->bindParam(':codigoProveedor',$nuevoRecambio["codigoProveedor"]);
		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}

function alta_equipamiento($conexion,$nuevoEquipamiento) {

	$nuevoEquipamiento["precio"]=str_replace('.', ',', $nuevoEquipamiento["precio"]);
	$nuevoEquipamiento["oferta"]=str_replace('.', ',', $nuevoEquipamiento["oferta"]);
	$nuevoEquipamiento["oferta"]="0,".$nuevoEquipamiento["oferta"];
	$nuevoEquipamiento["iva"]=str_replace('.', ',', $nuevoEquipamiento["iva"]);
	try {
		$consulta = "CALL NUEVOCREAREQUIPAMIENTO(:codigo, :nombre, :descripcion, :marca, :precio, :oferta, :iva, :stock, :stockMinimo, :codigoProveedor, :color, :material, :talla)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':codigo',$nuevoEquipamiento["codigo"]);
		$stmt->bindParam(':nombre',$nuevoEquipamiento["nombre"]);
		$stmt->bindParam(':descripcion',$nuevoEquipamiento["descripcion"]);
		$stmt->bindParam(':marca',$nuevoEquipamiento["marca"]);
		$stmt->bindParam(':precio', $nuevoEquipamiento["precio"]);
		$stmt->bindParam(':oferta',$nuevoEquipamiento["oferta"]);
		$stmt->bindParam(':iva',$nuevoEquipamiento["iva"]);
		$stmt->bindParam(':stock',$nuevoEquipamiento["stock"]);
		$stmt->bindParam(':stockMinimo',$nuevoEquipamiento["stockMinimo"]);
		$stmt->bindParam(':codigoProveedor',$nuevoEquipamiento["codigoProveedor"]);
		$stmt->bindParam(':color',$nuevoEquipamiento["color"]);
		$stmt->bindParam(':material',$nuevoEquipamiento["material"]);
		$stmt->bindParam(':talla',$nuevoEquipamiento["talla"]);


		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}


function consultarTodosProductos($conexion) {
	$consulta = "SELECT * FROM PRODUCTO"
		. " ORDER BY NOMBRE";
    return $conexion->query($consulta);
}

function quitar_producto($conexion,$OID_P) {
	try {
		$stmt=$conexion->prepare('CALL BORRARPRODUCTO(:OID_P)');
		$stmt->bindParam(':OID_P',$OID_P);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function modificar_producto($conexion,$OID_P,$precio,$oferta,$stock,$stockMinimo) {
	$oferta="0,".$oferta;
	try {
		$stmt=$conexion->prepare('CALL UPDATEPRODUCTO(:OID_P,:precio,:oferta,:stock,:stockMinimo)');
		$stmt->bindParam(':OID_P',$OID_P);
		$stmt->bindParam(':precio',$precio);
		$stmt->bindParam(':oferta',$oferta);
		$stmt->bindParam(':stock',$stock);
		$stmt->bindParam(':stockMinimo',$stockMinimo);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function comprar_producto($conexion,$OID_Us,$productos) {
	try {
		$stmt=$conexion->prepare('CALL anadirCarritoDos(:OID_Us,:OID_P)');
		$stmt->bindParam(':OID_Us',$OID_Us);
		$stmt->bindParam(':OID_P',$productos["OID_P"]);
		$stmt->bindParam(':fechaEntrega',$fechaEntrega);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function quitar_producto_carrito($conn, $login){
	try {
		$total_consulta = " DELETE FROM LINEAPEDIDO WHERE OID_LP = '$login'";

		$stmt = $conn->query($total_consulta);
		$stmt->execute();
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}


function comprar_carrito($conexion,$nuevoRecambio) {
	try {
		$consulta = "CALL COMPRARCARRITO(:fechaEntrega, :OID_PE, :envio, :tipoPago)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':fechaEntrega',$nuevoRecambio["fechaEntrega"]);
		$stmt->bindParam(':OID_PE',$nuevoRecambio["OID_PE"]);
		$stmt->bindParam(':envio',$nuevoRecambio["ENVIO"]);
		$stmt->bindParam(':tipoPago',$nuevoRecambio["TIPOPAGO"]);
		$stmt->execute();


	} catch(PDOException $e) {
		return $e->getMessage();
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
  }

}




function comprobar_codigo($conexion,$codigo){
	$check = "SELECT * FROM PRODUCTO WHERE codigo = 123456789";

	return $conexion->query($check);
}

?>
