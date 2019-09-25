<?php
function consulta_paginada( $conn, $query, $pag_num, $pag_size )
{
	try {
		$primera = ( $pag_num - 1 ) * $pag_size + 1;
		$ultima  = $pag_num * $pag_size;
		$consulta_paginada =
			 "SELECT * FROM ( "
				."SELECT ROWNUM RNUM, AUX.* FROM ( $query ) AUX "
				."WHERE ROWNUM <= :ultima"
			.") "
			."WHERE RNUM >= :primera";

		$stmt = $conn->prepare( $consulta_paginada );
		$stmt->bindParam( ':primera', $primera );
		$stmt->bindParam( ':ultima',  $ultima  );
		$stmt->execute();
		return $stmt;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}

function total_consulta( $conn, $query )
{
	try {
		$total_consulta = "SELECT COUNT(*) AS TOTAL FROM ($query)";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}
function consulta_email( $conn, $oid_us )
{
	try {
		$total_consulta = " SELECT email AS TOTAL FROM USUARIO WHERE OID_Us = $oid_us";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}

function consulta_usuario( $conn, $email )
{
	try {
		$total_consulta = " SELECT OID_Us AS TOTAL FROM USUARIO WHERE EMAIL = '$email'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}


function consulta_nombre( $conn, $oid_uus )
{
	try {
		$total_consulta = " SELECT NOMBRE AS TOTAL FROM CLIENTE WHERE OID_Us = $oid_uus";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}

function comprobar_tipo_usuario( $conn, $login )
{
	try {
		$total_consulta = " SELECT TIPOUSUARIO AS TOTAL FROM USUARIO WHERE EMAIL = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}
}

function seleccionar_pedido($conn, $login){
	try {
		$total_consulta = " SELECT OID_PE AS TOTAL FROM PEDIDO WHERE (OID_US = '$login' AND estado = 'Espera')";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}

function seleccionar_estado($conn, $login){
	try {
		$total_consulta = " SELECT ESTADO AS TOTAL FROM PEDIDO WHERE OID_Pe = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}
function seleccionar_envio($conn, $login){
	try {
		$total_consulta = " SELECT ENVIO AS TOTAL FROM PEDIDO WHERE OID_Pe = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}
function seleccionar_tipopago($conn, $login){
	try {
		$total_consulta = " SELECT TIPOPAGO AS TOTAL FROM PEDIDO WHERE OID_Pe = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}
function seleccionar_fechaentrega($conn, $login){
	try {
		$total_consulta = " SELECT FECHAENTREGA AS TOTAL FROM PEDIDO WHERE OID_Pe = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}

function seleccionar_preciopedido($conn, $login){
	try {
		$total_consulta = " SELECT PRECIOTOTAL AS TOTAL FROM PEDIDO WHERE OID_Pe = '$login'";

		$stmt = $conn->query($total_consulta);
		$result = $stmt->fetch();
		$total = $result['TOTAL'];
		return  $total;
	}
	catch ( PDOException $e ) {
		$_SESSION['excepcion'] = $e->GetMessage();
		header("Location: excepcion.php");
	}

}
?>
