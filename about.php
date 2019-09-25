<?php
	session_start();
	require_once ("paginacion_consulta.php");
	require_once ("gestionBD.php");
	$conexion = crearConexionBD();

	if (!isset($_SESSION['login']))
	Header("Location: login.php");

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
  <title>Sobre nosotros</title>
</head>

<body>
<?php
	include_once("cabecera.php");
	include_once("menu.php");
?>
<main>

<h1 id="titulo1">¡Bienvenido a Motos Cande! </h1>

<img id="fachada" src="images/fachada.jpg" alt="Fachada del local">
</br>
<div style="padding-left:25px;"><p>	Situada en la localidad de Zafra (Extremadura) la empresa Motos Cande se fundó hace 30 años y desde entonces llevamos prestando servicio al público con resultados satisfactorios. Es por ello que nos encontremos ante una de las empresas más importantes y conocidas en el mundo del motor de toda la comarca. </p> </div>


<div style="padding-left:25px;"><p> Y es que cuando hablamos de motor nos referimos tanto a todo tipo de motos como a maquinaria agrícola y jardinería e incluso todos sus recambios. Disponemos de un amplio servicio de venta de motocicletas, quads y minimotos, además de todo tipo de accesorios para complemetar tu equipo. Contamos con una amplia variedad en cascos, guantes, pantalones, etc para que no te falte de nada. <a href="consulta_equipamiento.php">¡Mira nuestras rebajas y descuentos!</a></p> </div>
</br>

<div id="fotos">
<img  id="foto1" src="images/tienda1.jpg" alt="Tienda" >
<img  id="foto2" src="images/tienda2.jpg" alt="Tienda" >
</div>

<div style="padding-left:25px;"><p> También ofrecemos el servicio de venta de maquinaria agrícola, forestal y de jardinería con un amplio catálogo. Cabe destacar que somos distribuidores oficiales de las marcas más conocidas del sector como Suzuki, Kawasaki y Stihl.</p></div>

<div  style="padding-left:25px;"><p> ¿Tu moto o motosierra se ha estropeado? No te preocupes, en Motos Cande además contamos con un gran servicio de reparación y de revisiones para poner al día tu motocicleta e incluso disponemos de un magnífico equipo de diagnosis. Si tienes alguna duda sobre nuestro servicio a continuación te mostramos nuestros datos de contacto, sin embargo ¡no dudes en visitarnos!</p></div>
</br>

<iframe id="map"
  width="500"
  height="350"
  frameborder="20" style="border:0"
  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCLBxc2tjzDVkhO47ERBvqM_9GF_geOMm4
    &q=Motos+Cande,Zafra+ES" allowfullscreen>
</iframe>

<div id="contacto">
	<h2><u>Datos de contacto</u></h2>
	<p>Nombre: Motos Cande</p>
	<p>Dirección: Pol. Industrial Los Caños Nave 209, 06300 Zafra (Badajoz)</p>
	<p>Teléfono: 924 55 35 36</p>
	<p>Móvil: +34 654 514 709</p>
	<p>Email: motoscande@gmail.com</p>
</div>

</main>

<?php
	include_once("pie.php");
?>
</body>
</html>
