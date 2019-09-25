<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("paginacion_consulta.php");



	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formularioR'])) {
		$formularioR['codigo'] = "";
		$formularioR['nombre'] = "";
		$formularioR['descripcion'] = "";
		$formularioR['marca'] = "";
		$formularioR['precio'] = "";
		$formularioR['oferta'] = "";
		$formularioR['iva'] = "";
		$formularioR['stock'] = "";
		$formularioR['stockMinimo'] = "";
		$formularioR['codigoProveedor'] = "";
		$_SESSION['formularioR'] = $formularioR;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formularioR = $_SESSION['formularioR'];

	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}


	//Creamos una conexión con la BD
	$conexion = crearConexionBD();
	if(comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Cliente'){
		$_SESSION["excepcion"]= "No tienes los permisos suficientes para acceder a esta página";
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
  <script src="js/validacion_cliente_alta_usuario.js" type="text/javascript"></script>
  <title>Motos Cande: Alta de recambios</title>
</head>

<body>



	<?php

	include_once ("cabecera.php");

	include_once ("menu.php");
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

	<form id="altaRecambio" method="get" action="accion_alta_recambio.php" onsubmit="return validateForm()">
		<p><i>&nbsp  Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos del producto</legend>
			<div></div><label for="codigo">Código<em>*</em></label>
			<input id="codigo" name="codigo" type="text"  maxlength="9" pattern="^[0-9]{9}" title="Nueve dígitos" value="<?php echo $formularioR['codigo'];?>" required/>
			</div>
<br></br>
			<div><label for="nombre">Nombre:<em>*</em></label>
			<input id="nombre" name="nombre" type="text" size="40" maxlength="25" value="<?php echo $formularioR['nombre'];?>" required/>
			</div>
<br>
			<div><label for="descripcion">Descripción:</label>
				<br>
				 <textarea id="descripcion" name="descripcion" rows="5" cols="40" maxlength="250"><?php echo $formularioR['descripcion'];?></textarea>

			</div>
<br>
			<div><label for="marca">Marca:<em>*</em></label>
				<input id="marca" name="marca" type="text" size="40" maxlength="25" value="<?php echo $formularioR['marca'];?>" required/>
			</div>
<br>
			<div><label for="precio">Precio:<em>*</em></label>
			<input id="precio" name="precio"  pattern="^\d{1,}[.,]{0,1}\d{1,2}" maxlength="11" title="Por favor introduzca un número decimal válido" size="40" value="<?php echo $formularioR['precio'];?>" required/>
			</div>
<br>
			<div><label for="oferta">Oferta:</label>
					<input id="oferta" name="oferta" type="text" maxlength="10" pattern="^\d{0,2}" title="Por favor introduzca un número válido" size="50" value="<?php echo $formularioR['oferta'];?>" />
			</div>
<br>
			<div><label for="iva">IVA	:<em>*</em></label>
			<input id="iva" name="iva"  pattern="^[0-9]{1}[.,]{1}[0-9]{2}" value="0.21" title="Por favor introduzca un iva válido" size="40" value="<?php echo $formularioR['iva'];?>" required/><br>
			</div>
<br>
			<div><label for="stock">Stock:<em>*</em></label>
			<input id="stock" name="stock"  size="40" maxlength="2" pattern="^[0-9]+" title="Por favor introduzca sólo dígitos" value="<?php echo $formularioR['stock'];?>" required/><br>
			</div>
<br>
			<div><label for="stockMinimo">Stock Mínimo:<em>*</em></label>
			<input id="stockMinimo" name="stockMinimo"  maxlength="2" pattern="^[0-9]+" title="Por favor introduzca sólo dígitos" size="40" value="<?php echo $formularioR['stockMinimo'];?>" required/><br>
			</div>
<br>
			<div><label for="codigoProveedor">Código del proveedor:<em>*</em></label>
			<input id="codigoProveedor" name="codigoProveedor"  size="40" pattern="^[0-9]{7}" onblur="existeProveedor()" title="Siete dígitos" value="<?php echo $formularioR['codigoProveedor'];?>" required/><br>
			</div>
<br>

		</fieldset>
		<div><input type="submit" value="Enviar" /></div>
	</form>
</main>
	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>

	</body>
</html>
