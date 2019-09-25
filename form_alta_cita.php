<?php
	session_start();

	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");


	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formularioC'])) {
		$formularioC['email']= $_SESSION['login'];
		$formularioC['fechaAcordada'] = "";
		$formularioC['asunto'] = "";
		$formularioC['descripcion'] = "";
		$formularioC['tipoCita'] = 'Tienda';
		$_SESSION['formularioC'] = $formularioC;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formularioC = $_SESSION['formularioC'];

	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];

		unset($_SESSION["errores"]);
	}


	// Creamos una conexión con la BD
	$conexion = crearConexionBD();
	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente'){
		$_SESSION["excepcion"]= "No tienes permisos de cliente";
		Header("Location: excepcion.php");
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
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>

  <title>Motos Cande: Realizar cita</title>
</head>

<body>

	<?php
		include_once("cabecera.php");
		include_once("menu.php");
	?>
<main id="mainregistro2">
	<?php
		// Mostrar los erroes de validación (Si los hay)
		if (isset($errores) && count($errores)>0) {
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		foreach($errores as $error){
    			echo $error;
			}
    		echo "</div>";
  		}
	?>

	<form id="realizarCita" method="get" action="accion_alta_cita.php" onsubmit="return validateForm()">
		<p><i>&nbsp  Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos de la cita</legend>



				<input id="email" name="email" type="text" hidden value="<?php echo $formularioC['email'];?>" />

			<div></div><label for="fechaAcordada">Fecha:<em>*</em></label>
			<input id="fechaAcordada" name="fechaAcordada" type="date" value="<?php echo $formularioC['fechaAcordada'];?>" required/>
			</div>
      <br></br>

			<div><label for="asunto">Asunto:<em>*</em></label>
			<input id="asunto" name="asunto" type="text" size="80" value="<?php echo $formularioC['asunto'];?>" required/>
			</div>
      <br>

			<div><label for="descripcion">Descripción:<em>*</em></label></br>
				<textarea id="descripcion" name="descripcion"required rows="5" cols="40" maxlength="250"><?php echo $formularioC['descripcion'];?></textarea>
      </div>
      <br>

			<div><label>Tipo de cita:</label>
			<label>
				<input name="tipoCita" type="radio" checked value="Tienda" <?php if($formularioC['tipoCita']=='Tienda') echo ' checked ';?>/>
				Tienda</label>
			<label>
				<input name="tipoCita" type="radio"  value="Taller" <?php if($formularioC['tipoCita']=='Taller') echo ' checked ';?>/>
				Taller</label>
			</div>

		</fieldset>
		<div><input id="botonformulario" type="submit" value="Enviar" /></div>
	</form>
</main>
	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>

	</body>
</html>
