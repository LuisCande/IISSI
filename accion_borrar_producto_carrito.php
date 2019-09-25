<?php
	session_start();

	if (isset($_SESSION["carrito"])) {
		$carrito = $_SESSION["carrito"];
		unset($_SESSION["carrito"]);

		require_once("gestionBD.php");
		require_once("gestionarProductos.php");

		$conexion = crearConexionBD();
		$excepcion = quitar_producto_carrito($conexion,$carrito["OID_LP"]);
		cerrarConexionBD($conexion);

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_carrito.php";
			Header("Location: excepcion.php");
		}
		else Header("Location: consulta_carrito.php");
	}
	else Header("Location: consulta_carrito.php"); // Se ha tratado de acceder directamente a este PHP
?>
