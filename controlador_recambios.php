<?php
	session_start();

	if (isset($_REQUEST["OID_P"])){
		$productos["OID_P"] = $_REQUEST["OID_P"];
		$productos["OID_PROV"] = $_REQUEST["OID_Prov"];
		$productos["CODIGO"] = $_REQUEST["codigo"];
		$productos["NOMBRE"] = $_REQUEST["nombre"];
		$productos["DESCRIPCION"] = $_REQUEST["descripcion"];
		$productos["MARCA"] = $_REQUEST["marca"];
		$productos["TIPOPRODUCTO"] = $_REQUEST["tipoProducto"];
		$productos["PRECIO"] = $_REQUEST["precio"];
		$productos["OFERTA"] = $_REQUEST["oferta"];
		$productos["IVA"] = $_REQUEST["iva"];
		$productos["STOCK"] = $_REQUEST["stock"];
		$productos["STOCKMINIMO"] = $_REQUEST["stockMinimo"];

		$_SESSION["productos"] = $productos;

		if (isset($_REQUEST["editar"])) Header("Location: consulta_recambio.php");
		else if (isset($_REQUEST["grabar"])) Header("Location: accion_modificar_recambio.php");
		else if (isset($_REQUEST["borrar"])) Header("Location: accion_borrar_recambio.php");
		else Header("Location: accion_comprar_recambio.php");
	}
	else
		Header("Location: consulta_recambio.php");

?>
