<?php
	session_start();

	if (isset($_REQUEST["OID_PROV"])){
		$proveedor["OID_PROV"] = $_REQUEST["OID_PROV"];
		$proveedor["CODIGO"] = $_REQUEST["CODIGO"];
		$proveedor["NOMBRE"] = $_REQUEST["NOMBRE"];
		$proveedor["EMAIL"] = $_REQUEST["email"];
		$proveedor["TELEFONO"] = $_REQUEST["telefono"];
		$proveedor["DIRECCION"] = $_REQUEST["direccion"];
		$proveedor["WEB"] = $_REQUEST["web"];

		$_SESSION["proveedor"] = $proveedor;

		if (isset($_REQUEST["editar"])) Header("Location: consulta_proveedores.php");
		else if (isset($_REQUEST["grabar"])) Header("Location: accion_modificar_proveedor.php");
		else /* if (isset($_REQUEST["borrar"])) */ Header("Location: accion_borrar_proveedor.php");
	}
	else
		Header("Location: consulta_proveedores.php");

?>
