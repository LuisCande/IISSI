<?php
	session_start();

	$excepcion = $_SESSION["excepcion"];
	unset($_SESSION["excepcion"]);

	if (isset ($_SESSION["destino"])) {
		$destino = $_SESSION["destino"];
		unset($_SESSION["destino"]);
	} else
		$destino = "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="icon" href="images/favicon.ico">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Hay que indicar el fichero externo de estilos -->
    <link rel="stylesheet" type="text/css" href="css/motoscande.css" />
	<script type="text/javascript" src="./js/boton.js"></script>
  <title>Motos Cande: ¡Se ha producido un problema!</title>
</head>

<body>

<?php
	include_once("cabecera.php");

?>
<main id="bodyerror">
	<div>
		<h2>Ups!</h2>
		<?php if ($destino<>"") { ?>
		<p>Ocurrió un problema durante el procesado de los datos. Pulse <a href="<?php echo $destino ?>">aquí</a> para volver a la página principal.</p>
		<?php } else { ?>
		<p>Ocurrió un problema para acceder a la base de datos. </p>
		<?php } ?>
	</div>

	<div class='excepcion'>
		<?php echo "Información relativa al problema: $excepcion"; ?>
	</div>
</main>
<?php
	include_once("pie.php");
?>

</body>
</html>
