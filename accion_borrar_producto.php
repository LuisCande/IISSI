<?php
	session_start();

	if (isset($_SESSION["productos"])) {
		$productos = $_SESSION["productos"];
		unset($_SESSION["productos"]);

		require_once("gestionBD.php");
		require_once("gestionarProductos.php");

		$conexion = crearConexionBD();
		$excepcion = quitar_producto($conexion,$productos["OID_P"]);
		cerrarConexionBD($conexion);

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_motor.php";
			Header("Location: excepcion.php");
		}
		else Header("Location: consulta_motor.php");
	}
	else Header("Location: consulta_motor.php"); // Se ha tratado de acceder directamente a este PHP
?>
