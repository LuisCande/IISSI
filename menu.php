<nav>
	<ul class="topnav" id="myTopnav">
					<li><a href="consulta_motor.php">Tienda</a>
						<ul>
							<li><a href="consulta_motor.php">Motos y maquinaria agrícola</a></li>
							<li><a href="consulta_equipamiento.php">Equipamiento</a></li>
							<li><a href="consulta_recambio.php">Recambios</a></li>

						</ul>
					</li>
					<li><?php	include_once("paginacion_consulta.php");
					if (comprobar_tipo_usuario($conexion,$_SESSION['login'])!='Cliente') {	?>
							<a href="consulta_proveedores.php">Proveedores</a>
						<?php } ?></li>
		<li><a href="consulta_valoraciones.php">Valoraciones </a></li>
		<li><a href="consulta_citas.php">Citas </a></li>
		<li><?php
		if (comprobar_tipo_usuario($conexion,$_SESSION['login'])=='Gerente') {	?>
				<a href="form_alta_dependiente.php">Registrar Dependiente</a>
			<?php } ?></li>
	  	<li><a href="consulta_carrito.php">Carrito</a></li>
			<li><a href="consulta_pedidos.php">Pedidos</a></li>
	  	<li><a href="about.php">Sobre nosotros</a></li>

		<li><?php if (isset($_SESSION['login'])) {	?>
				<a href="logout.php">Desconectar</a>
			<?php } ?>
		</li>

		<li class="icon">
			<a href="javascript:void(0);" onclick="myToggleMenu()">&#9776;</a>
		</li>
	</ul>
</nav>
