<?php
	session_start();

	if (isset($_REQUEST["OID_V"])){
		$valoracion["OID_US"] = $_REQUEST["OID_US"];
		$valoracion["ASUNTO"] = $_REQUEST["ASUNTO"];
		$valoracion["DESCRIPCION"] = $_REQUEST["DESCRIPCION"];
		$valoracion["FECHAENVIO"] = $_REQUEST["FECHAENVIO"];

		$_SESSION["valoracion"] = $valoracion;

	}
	else
		Header("Location: consulta_valoraciones.php");

?>
