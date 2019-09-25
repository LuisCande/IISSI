<?php
	session_start();

	if (isset($_REQUEST["OID_CI"])){
		$cita["FECHAACORDADA"] = $_REQUEST["FECHAACORDADA"];
		$cita["OID_US"] = $_REQUEST["OID_US"];
		$cita["ASUNTO"] = $_REQUEST["ASUNTO"];
		$cita["DESCRIPCION"] = $_REQUEST["DESCRIPCION"];
		$cita["TIPOCITA"] = $_REQUEST["TIPOCITA"];


		$_SESSION["cita"] = $cita;

	}
	else
		Header("Location: consulta_citas.php");

?>
