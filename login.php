<?php
	session_start();

  include_once("gestionBD.php");
 	include_once("gestionarUsuarios.php");

	if (isset($_POST['submit'])){
		$email= $_POST['email'];
		$contraseña = $_POST['contraseña'];

		$conexion = crearConexionBD();
		$num_usuarios = consultarUsuario($conexion,$email,$contraseña);
		cerrarConexionBD($conexion);

		if ($num_usuarios == 0)
			$login = "error";
		else {
			$_SESSION['login'] = $email;
			Header("Location: index.php");
		}
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="icon" href="images/favicon.ico">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Hay que indicar el fichero externo de estilos -->
    <link rel="stylesheet" type="text/css" href="css/motoscande.css" />
  <title>Gestión de biblioteca: Login</title>
</head>

<body>

<?php
	include_once("cabecera.php");
?>

<main id="mainlogin">
	<?php if (isset($login)) {?>
<?php
		echo "<div class=\"error\">";
		echo "Error en la contraseña o no existe el usuario.";
		echo "</div>";
	}
	?>

	<!-- The HTML login form -->
	<form id="login" action="login.php" method="post">
	</br>
		</br>
		<div id="email"><label for="email">&nbsp &nbsp Email: &nbsp &nbsp &nbsp</label><input type="text" name="email" id="email" /></div>
	</br>
		<div id="contraseña"><label for="contraseña">Contraseña: </label><input type="password" name="contraseña" id="contraseña" /></div>
		</br>
		<input id= "boton" type="submit" name="submit" value="Iniciar sesión" />
		</br></br>
	</form>

	<p id="registration">¿No estás registrado? <a href="form_alta_usuario.php">¡Regístrate!</a></br></br></p>

</main>

<?php
	include_once("pie.php");
?>
</body>
</html>
