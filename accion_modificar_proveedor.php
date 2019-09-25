<?php
	session_start();

	if (isset($_SESSION["proveedor"])) {
		$proveedor = $_SESSION["proveedor"];
		unset($_SESSION["proveedor"]);

		require_once("gestionBD.php");
		require_once("gestionarProveedores.php");

		$conexion = crearConexionBD();
		$excepcion = modificar_proveedor($conexion,$proveedor["OID_PROV"],$proveedor["EMAIL"],$proveedor["TELEFONO"],$proveedor["DIRECCION"],$proveedor["WEB"]);
		cerrarConexionBD($conexion);

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_proveedores.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_proveedores.php");
	}
	else Header("Location: consulta_proveedores.php"); // Se ha tratado de acceder directamente a este PHP
?>
