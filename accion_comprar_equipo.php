<?php
	session_start();
require_once("paginacion_consulta.php");
	if (isset($_SESSION["productos"])) {
		$productos = $_SESSION["productos"];
		unset($_SESSION["productos"]);

		require_once("gestionBD.php");
		require_once("gestionarProductos.php");

		$conexion = crearConexionBD();
		$OID_UsEmail = consulta_usuario($conexion,$_SESSION['login']);
		$excepcion = comprar_producto($conexion,$OID_UsEmail,$productos);
		cerrarConexionBD($conexion);

		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_equipamiento.php";
			Header("Location: excepcion.php");
		}
		else Header("Location: consulta_equipamiento.php");
	}
	else Header("Location: consulta_equipamiento.php"); // Se ha tratado de acceder directamente a este PHP
?>
