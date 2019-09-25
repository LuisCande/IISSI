<?php
	session_start();
	if (isset($_REQUEST["OID_LP"])){
		$carrito["OID_LP"] = $_REQUEST["OID_LP"];
		$carrito["NOMBRE"] = $_REQUEST["nombre"];
		$carrito["PRECIO"] = $_REQUEST["precio"];
		$carrito["OFERTA"] = $_REQUEST["oferta"];
		$carrito["IVA"] = $_REQUEST["iva"];
		$carrito["CANTIDAD"] = $_REQUEST["cantidad"];
		$_SESSION["carrito"] = $carrito;

		if (isset($_REQUEST["borrar"])) Header("Location: accion_borrar_producto_carrito.php");
		else Header("Location: accion_modificar_recambio.php");
	}
	else
	if (isset($_REQUEST["OID_PE"])){

		$carrito['FECHAENTREGA'] = $_REQUEST['fechaEntrega'];
		$carrito["OID_PE"] = $_REQUEST["OID_PE"];
		$carrito["ENVIO"] = $_REQUEST["envio"];
		$carrito["TIPOPAGO"] = $_REQUEST["tipoPago"];

		$_SESSION["carrito"] = $carrito;

		if (isset($_REQUEST["comprar"])) Header("Location: accion_comprar_carrito.php");
		else Header("Location: login.php");

	}
	else
		Header("Location: consulta_carrito.php");

?>
