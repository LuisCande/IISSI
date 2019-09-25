--  RF-3. Registro en el sistema
--  
--  Como usuario, 
--  quiero registrarme en el sistema, 
--  para facilitar el pedido en futuros pedidos
--
-- 

create or replace procedure crearCliente(
 xemail IN USUARIO.EMAIL%TYPE,
 xtelefono IN USUARIO.TELEFONO%TYPE,
 xcontraseña IN USUARIO."CONTRASEÑA"%TYPE,
 xnombre IN CLIENTE.NOMBRE%TYPE,
 xprimerApellido IN CLIENTE.PRIMERAPELLIDO%TYPE,
 xsegundoApellido IN CLIENTE.SEGUNDOAPELLIDO%TYPE,
 xdni IN CLIENTE.DNI%TYPE,
 xdireccion IN CLIENTE.DIRECCION%TYPE) IS
BEGIN
 INSERT INTO USUARIO (email,contraseña,telefono,tipoUsuario)
 VALUES (xemail,xcontraseña,xtelefono,'Cliente');
 INSERT INTO CLIENTE 
 (nombre,primerApellido,segundoApellido,dni,direccion,OID_US)
 VALUES (xnombre,xprimerApellido,xsegundoApellido,xdni,xdireccion,(SELECT OID_Us FROM USUARIO WHERE email = xemail));
 COMMIT WORK;
End crearCliente;
/

create or replace procedure crearDependiente(
 xemail IN USUARIO.EMAIL%TYPE,
 xtelefono IN USUARIO.TELEFONO%TYPE,
 xcontraseña IN USUARIO."CONTRASEÑA"%TYPE
 ) IS
BEGIN
 INSERT INTO USUARIO (email,contraseña,telefono,tipoUsuario)
 VALUES (xemail,xcontraseña,xtelefono,'Dependiente');
 INSERT INTO DEPENDIENTE (OID_Us)
 VALUES ((SELECT OID_Us FROM USUARIO WHERE email = xemail));
 COMMIT WORK;
End crearDependiente;
/

create or replace procedure crearGerente(
 xemail IN USUARIO.EMAIL%TYPE,
 xtelefono IN USUARIO.TELEFONO%TYPE,
 xcontraseña IN USUARIO."CONTRASEÑA"%TYPE
 ) IS
BEGIN
 INSERT INTO USUARIO (email,contraseña,telefono,tipoUsuario) VALUES
(xemail,xcontraseña,xtelefono,'Gerente');
INSERT INTO GERENTE (OID_Us)
VALUES ((SELECT OID_Us FROM USUARIO WHERE email = xemail));
 COMMIT WORK;
End crearGerente;
/

create or replace procedure crearEquipamiento(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xOID_Prov IN PRODUCTO.OID_PROV%TYPE,
  xcolor IN EQUIPAMIENTO.COLOR%TYPE,
  xmaterial IN EQUIPAMIENTO.MATERIAL%TYPE,
  xtalla IN EQUIPAMIENTO.TALLA%TYPE
  ) IS
BEGIN
 INSERT INTO PRODUCTO (codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov)
 VALUES (xcodigo,xnombre,xdescripcion,xmarca,'Equipamiento',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO EQUIPAMIENTO (color,material,talla,OID_P) 
VALUES (xcolor,xmaterial,xtalla,(SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End crearEquipamiento;
/

create or replace procedure crearRecambio(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xOID_Prov IN PRODUCTO.OID_PROV%TYPE) IS
BEGIN
 INSERT INTO PRODUCTO (codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov) VALUES
(xcodigo,xnombre,xdescripcion,xmarca,'Recambio',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO RECAMBIO (OID_P)
VALUES((SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End crearRecambio;
/

create or replace procedure crearMotor(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xOID_Prov IN PRODUCTO.OID_PROV%TYPE,
  xcilindrada IN MOTOR.CILINDRADA%TYPE,
  xestado IN MOTOR.ESTADO%TYPE,
  xanyoFabricacion IN MOTOR.ANYOFABRICACION%TYPE,
  xtipoMotor IN MOTOR.TIPOMOTOR%TYPE,
  xgarantia IN MOTOR.GARANTIA%TYPE
  ) IS
BEGIN
 INSERT INTO PRODUCTO(codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov)
 VALUES (xcodigo,xnombre,xdescripcion,xmarca,'Motor',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO MOTOR (cilindrada,estado,anyoFabricacion,tipoMotor,garantia,OID_P)
VALUES(xcilindrada,xestado,xanyoFabricacion,xtipoMotor,xgarantia,(SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End crearMotor;
/
--RF-10. Realizar valoraciones
-- 
--Como cliente,
--quiero poder realizar una valoración, 
--para así poder expresar mi opinión sobre el negocio.

create or replace procedure crearValoracion(

 xasunto IN VALORACION.ASUNTO%TYPE,
 xdescripcion IN VALORACION.DESCRIPCION%TYPE,
 xOID_Us IN VALORACION.OID_US%TYPE
 ) IS
BEGIN
 INSERT INTO VALORACION (asunto,descripcion,OID_Us)
 VALUES(xasunto,xdescripcion,xOID_Us);
 COMMIT WORK;
End crearValoracion;
/
create or replace procedure crearValoracionEmail(

 xasunto IN VALORACION.ASUNTO%TYPE,
 xdescripcion IN VALORACION.DESCRIPCION%TYPE,
 xemail IN USUARIO.EMAIL%TYPE
 ) AS
 xOID_Us USUARIO.OID_Us%TYPE;
BEGIN
SELECT OID_Us INTO xOID_Us FROM USUARIO WHERE email = xemail;
 INSERT INTO VALORACION (asunto,descripcion,OID_Us)
 VALUES(xasunto,xdescripcion,xOID_Us);
 COMMIT WORK;
End crearValoracionEmail;
/

--RF-11. Solicitar citas
-- 
--Como cliente,
--quiero poder solicitar una cita, 
--para así poder contactar de forma presencial con la empresa.
--

create or replace procedure crearCita(

 xfechaAcordada IN CITA.FECHAACORDADA%TYPE,
 xasunto IN CITA.ASUNTO%TYPE,
 xdescripcion IN CITA.DESCRIPCION%TYPE,
 xtipoCita IN CITA.TIPOCITA%TYPE,
 xOID_Us IN CITA.OID_US%TYPE
 ) IS
BEGIN
 INSERT INTO CITA (fechaAcordada,asunto,descripcion,tipoCita,OID_US)
VALUES (xfechaAcordada,xasunto,xdescripcion,xtipoCita,xOID_US);
 COMMIT WORK;
End crearCita;
/

create or replace procedure crearCitaEmail(

 xfechaAcordada IN CITA.FECHAACORDADA%TYPE,
 xasunto IN CITA.ASUNTO%TYPE,
 xdescripcion IN CITA.DESCRIPCION%TYPE,
 xtipoCita IN CITA.TIPOCITA%TYPE,
 xemail IN USUARIO.EMAIL%TYPE
 ) AS
  xOID_Us CITA.OID_US%TYPE;
BEGIN
SELECT OID_Us INTO xOID_Us FROM USUARIO WHERE email = xemail;
 INSERT INTO CITA (fechaAcordada,asunto,descripcion,tipoCita,OID_US)
VALUES (xfechaAcordada,xasunto,xdescripcion,xtipoCita,xOID_US);
 COMMIT WORK;
End crearCitaEmail;
/
create or replace procedure crearPedido(
  xfechaEntrega  IN PEDIDO.FECHAENTREGA%TYPE,
  xenvio IN PEDIDO.ENVIO%TYPE,
  xtipoPago  IN PEDIDO.TIPOPAGO%TYPE,
  xOID_Us IN PEDIDO.OID_US%TYPE) IS
  BEGIN
  INSERT INTO PEDIDO (fechaEntrega,envio,tipoPago,estado,OID_Us)
  VALUES (xfechaEntrega,xenvio,xtipoPago,'Espera',xOID_Us);
  COMMIT WORK;
  END crearPedido;
  /  
create or replace procedure crearLineaPedido(
  xcantidad  IN LINEAPEDIDO.CANTIDAD%TYPE,
  xOID_P IN LINEAPEDIDO.OID_P%TYPE,
  xOID_Pe IN LINEAPEDIDO.OID_PE%TYPE) IS
  AUX PRODUCTO.PRECIO%TYPE;
  v_OID_LP LINEAPEDIDO.OID_LP%TYPE;
  BEGIN
  BEGIN
SELECT OID_LP INTO v_OID_LP FROM LINEAPEDIDO WHERE OID_P=xOID_P AND OID_Pe=xOID_Pe;
EXCEPTION
WHEN NO_DATA_FOUND THEN
v_OID_LP:=0;
END;

IF v_OID_LP=0 THEN
SELECT PRECIO*(1-OFERTA) INTO AUX FROM PRODUCTO WHERE OID_P=xOID_P;
  INSERT INTO LINEAPEDIDO(cantidad,precio,iva,OID_P,OID_Pe)
    VALUES (xcantidad,AUX,(SELECT IVA FROM PRODUCTO WHERE OID_P=xOID_P),xOID_P,xOID_Pe);
ELSE
  UPDATE LineaPedido SET cantidad = cantidad+xcantidad WHERE OID_LP=v_OID_LP;
  END IF;
  COMMIT WORK;
  END crearLineaPedido;
  /  
  
create or replace procedure crearProveedor(
 xcodigo IN PROVEEDOR.CODIGO%TYPE,
 xnombre IN PROVEEDOR.NOMBRE%TYPE,
 xemail IN PROVEEDOR.EMAIL%TYPE,
 xtelefono IN PROVEEDOR.TELEFONO%TYPE,
 xdireccion IN PROVEEDOR.DIRECCION%TYPE,
 xweb IN PROVEEDOR.WEB%TYPE
 ) IS
BEGIN
 INSERT INTO PROVEEDOR (codigo,nombre,email,telefono,direccion,web)
 VALUES(xcodigo,xnombre,xemail,xtelefono,xdireccion,xweb);
 COMMIT WORK;
End crearProveedor;
/

--  RF-01
--  Como dependiente,
--  quiero gestionar pedido de un cliente en cada momento,
--  para tener un seguimiento exhaustivo del mismo.

create or replace procedure verPedidos IS
CURSOR C IS
SELECT fechaRealizacion,fechaEntrega,envio,tipoPago,estado,precioTotal,OID_Pe FROM PEDIDO;
v_Cursor C%ROWTYPE;
BEGIN
OPEN C;
FETCH C INTO v_Cursor;
 DBMS_OUTPUT.PUT_LINE(RPAD('Fecha de realizacion:', 25) || RPAD('Fecha de entrega', 25) || 
 RPAD('Envio:', 25) || RPAD('Tipo de pago:', 25) || RPAD('Estado:', 25) || RPAD('Precio Total:', 25) || RPAD('OID_Pe:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 135, '-'));
 WHILE C%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_Cursor.fechaRealizacion, 25) || RPAD(v_Cursor.fechaEntrega, 25) ||RPAD(v_Cursor.envio, 25) ||
 RPAD(v_Cursor.tipoPago, 25) || RPAD(v_Cursor.estado, 25) || RPAD(v_Cursor.precioTotal, 25) || RPAD(v_Cursor.OID_Pe, 25));
 FETCH C INTO v_Cursor;
 END LOOP;
 CLOSE C;
END verPedidos;
/

--RF-4. Carrito de la compra
--
--Como usuario, 
--quiero disponer de la opción de un carrito de la compra, 
--para poder organizar un pedido.
create or replace procedure devolverCarrito(
v_OID_Pe IN PEDIDO.OID_Pe%TYPE)IS
v_PrecioTotal NUMBER(6,2);
 CURSOR C IS
 SELECT nombre, precio, cantidad, precioTotal FROM PEDIDO NATURAL JOIN LineaPedido NATURAL JOIN Producto WHERE v_OID_Pe = OID_Pe;
 v_Carrito C%ROWTYPE;
BEGIN
 SELECT precioTotal INTO v_PrecioTotal FROM PEDIDO WHERE v_OID_Pe = OID_Pe;
 OPEN C;
 FETCH C INTO v_Carrito;
 DBMS_OUTPUT.PUT_LINE(RPAD('Producto:', 25) || RPAD('Precio:', 25) || RPAD('Cantidad', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 135, '-'));
 WHILE C%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_Carrito.nombre, 25) || RPAD(v_Carrito.precio, 25) || RPAD(v_Carrito.cantidad, 25));
 FETCH C INTO v_Carrito;
 END LOOP;
 DBMS_OUTPUT.PUT_LINE(RPAD('Precio total:',25) || RPAD(v_PrecioTotal,25));
 CLOSE C;
END devolverCarrito;
/

--RF-5. Añadir/eliminar/editar productos
--
--Como gerente, 
--quiero poder modificar el catálogo de la tienda online dependiendo de las necesidades de la tienda,
--para ofrecer productos a la venta.


create or replace procedure borrarProducto(
xOID_P IN PRODUCTO.OID_P%TYPE
) IS
BEGIN
DELETE FROM Producto WHERE OID_P = xOID_P;
--   ON DELETE CASCADE NOS PERMITE QUE AL BORRAR EL PRODUCTO TAMBIEN SE BORREN SUS CLASES HIJAS
End borrarProducto;
/



create or replace procedure comprarcarrito(
xfecha IN PEDIDO.FECHAENTREGA%TYPE,
xoid_pe IN PEDIDO.OID_PE%TYPE,
xenvio IN PEDIDO.ENVIO%TYPE,
xtipopago IN PEDIDO.TIPOPAGO%TYPE)
IS
xOid_us USUARIO.OID_US%TYPE;
BEGIN
UPDATE PEDIDO SET
fechaEntrega = xfecha,
envio = xenvio,
tipoPago = xtipoPago,
estado = 'Transito'
WHERE OID_PE=xoid_pe;
SELECT OID_US into xOid_us FROM PEDIDO WHERE OID_Pe = xoid_pe;
crearPedido('30-9-2039','True','Tarjeta',xOid_us);
 END comprarcarrito;
 /


--RF-6. Listar productos
--
--Como cliente,
--quiero que el sistema me ofrezca un conjunto de productos,
--para poder seleccionarlos y comprarlos si lo deseo.


create or replace procedure listarProductos IS
CURSOR C IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov,color,material,talla
 FROM EQUIPAMIENTO NATURAL JOIN PRODUCTO;
 v_equipo C%ROWTYPE;
 CURSOR D IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov,cilindrada,estado,anyoFabricacion,tipoMotor,garantia
 FROM MOTOR NATURAL JOIN PRODUCTO;
 v_motor D%ROWTYPE;
  CURSOR E IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov
 FROM  RECAMBIO NATURAL JOIN PRODUCTO ;
 v_recambio E%ROWTYPE;
 
BEGIN
 OPEN C;
 FETCH C INTO v_equipo;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25)|| 
 RPAD('Color:', 25)|| RPAD('Material:', 25)|| RPAD('Talla:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE C%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_equipo.nombre, 25) || RPAD(v_equipo.descripcion, 25) || RPAD(v_equipo.marca, 25) || RPAD(v_equipo.precio,25) || RPAD(v_equipo.oferta, 25)
|| RPAD(v_equipo.color, 25) || RPAD(v_equipo.material, 25) || RPAD(v_equipo.talla, 25));
 FETCH C INTO v_equipo;
 END LOOP;
 CLOSE C;
  OPEN D;
 FETCH D INTO v_motor;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25)|| 
 RPAD('Cilindradas:', 25)|| RPAD('Estado:', 25)|| RPAD('Año de fabricacion:', 25)||  RPAD('Garantia:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE D%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_motor.nombre, 25) || RPAD(v_motor.descripcion, 25) || RPAD(v_motor.marca, 25) || RPAD(v_motor.precio,25) || RPAD(v_motor.oferta, 25)
|| RPAD(v_motor.cilindrada, 25) || RPAD(v_motor.estado, 25) || RPAD(v_motor.anyoFabricacion, 25)|| RPAD(v_motor.garantia, 25));
 FETCH D INTO v_motor;
 END LOOP;
 CLOSE D;
   OPEN E;
 FETCH E INTO v_recambio;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE E%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_recambio.nombre, 25) || RPAD(v_recambio.descripcion, 25) || RPAD(v_recambio.marca, 25) || RPAD(v_recambio.precio,25) || RPAD(v_recambio.oferta, 25));
 FETCH E INTO v_recambio;
 END LOOP;
 CLOSE E;
 
END listarProductos;
/

--RF-7. Realizar pedido 
--
--Como usuario, 
--quiero poder realizar el pedido,
--para adquirir producto que desee.


create or replace procedure anadirCarrito(
v_OID_Us IN USUARIO.OID_Us%TYPE,
v_OID_P IN PRODUCTO.OID_P%TYPE,
  xfechaEntrega  IN PEDIDO.FECHAENTREGA%TYPE,
  xenvio IN PEDIDO.ENVIO%TYPE,
  xtipoPago  IN PEDIDO.TIPOPAGO%TYPE)IS
v_OID_Pe PEDIDO.OID_Pe%TYPE:=0;
ccc PEDIDO.OID_Us%TYPE;
BEGIN
BEGIN
SELECT OID_Pe INTO v_OID_Pe FROM PEDIDO WHERE (OID_Us=v_OID_Us AND estado='Espera');
EXCEPTION
WHEN NO_DATA_FOUND THEN
v_OID_Pe:=0;

END;

if( v_OID_Pe=0) then
 crearPedido(xfechaEntrega,xenvio,xtipoPago,v_OID_Us);
 end if;
 SELECT OID_Pe INTO ccc FROM PEDIDO WHERE (OID_Us = v_OID_Us  AND estado='Espera');
 CREARLINEAPEDIDO(1,v_OID_P,ccc);
END anadirCarrito;
/


create or replace procedure anadirCarritoDos(
v_OID_Us IN USUARIO.OID_Us%TYPE,
v_OID_P IN PRODUCTO.OID_P%TYPE)IS
v_OID_Pe PEDIDO.OID_Pe%TYPE:=0;
ccc PEDIDO.OID_Us%TYPE;
BEGIN
BEGIN
SELECT OID_Pe INTO v_OID_Pe FROM PEDIDO WHERE (OID_Us=v_OID_Us  AND estado='Espera');
EXCEPTION
WHEN NO_DATA_FOUND THEN
v_OID_Pe:=0;
END;
if( v_OID_Pe=0) then
 crearPedido('30-9-2039','True','Tarjeta',v_OID_Us);
 end if;
 SELECT OID_Pe INTO ccc FROM PEDIDO WHERE (OID_Us = v_OID_Us  AND estado='Espera');
 CREARLINEAPEDIDO(1,v_OID_P,ccc);
END anadirCarritoDos;
/

--RF-8. Gestionar citas
-- 
--Como gerente,
--quiero poder gestionar las citas con los clientes, 
--para poder adecuarlas a mi horario.

create or replace procedure gestionarCitas IS
auxnom VARCHAR2(25);
auxnom2 VARCHAR2(25);
 CURSOR C IS
 SELECT fechaAcordada,asunto,descripcion,tipoCita,OID_US FROM CITA;
 v_Cita C%ROWTYPE;
BEGIN
 OPEN C;
 FETCH C INTO v_Cita;
 DBMS_OUTPUT.PUT_LINE(RPAD('Asunto:', 25) || RPAD('Cliente:', 50) || RPAD('Tipo', 25)|| RPAD('Fecha:', 25)|| RPAD('Descripcion:', 50));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 135, '-'));
 WHILE C%FOUND LOOP
 Select nombre,PRIMERAPELLIDO INTO auxnom,auxnom2 FROM CLIENTE WHERE OID_US=v_Cita.OID_US;
 DBMS_OUTPUT.PUT_LINE(RPAD(v_Cita.asunto, 25) || RPAD(auxnom, 25) || RPAD(auxnom2, 25) || RPAD(v_Cita.tipoCita, 25)|| RPAD(v_Cita.fechaAcordada, 25)|| RPAD(v_Cita.descripcion, 50));
 FETCH C INTO v_Cita;
 END LOOP;
 CLOSE C;
END gestionarCitas;
/

--RF-9: Visualizar valoraciones
-- 
--Como gerente,
--quiero poder visualizar las valoraciones de los clientes, 
--para poder mejorar mi negocio.

create or replace procedure visualizarValoraciones IS
auxnom VARCHAR2(25);
auxnom2 VARCHAR2(25);
 CURSOR C IS
 SELECT fechaEnvio,asunto,descripcion,OID_US FROM Valoracion;
 v_Valoracion C%ROWTYPE;
BEGIN
 OPEN C;
 FETCH C INTO v_Valoracion;
 DBMS_OUTPUT.PUT_LINE(RPAD('Asunto:', 25) || RPAD('Cliente:', 50) || RPAD('Fecha:', 25)|| RPAD('Descripcion:', 50));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 135, '-'));
 WHILE C%FOUND LOOP
 Select nombre,PRIMERAPELLIDO INTO auxnom,auxnom2 FROM CLIENTE WHERE OID_US=v_Valoracion.OID_US;
 DBMS_OUTPUT.PUT_LINE(RPAD(v_Valoracion.asunto, 25) || RPAD(auxnom, 25) || RPAD(auxnom2, 25) || RPAD(v_Valoracion.fechaEnvio, 25)|| RPAD(v_Valoracion.descripcion, 50));
 FETCH C INTO v_Valoracion;
 END LOOP;
 CLOSE C;
END visualizarValoraciones;
/

--RF-12. Consultar pedidos 
--
--Como usuario,
--quiero poder ver el historial de pedidos que he efectuado,
--para poder consultarlos si lo deseo.

create or replace procedure verPedido(
v_OID_Us IN USUARIO.OID_US%TYPE) IS
CURSOR C IS
SELECT fechaRealizacion,fechaEntrega,envio,tipoPago,estado,precioTotal,OID_Pe FROM PEDIDO WHERE OID_Us = v_OID_Us;
v_Cursor C%ROWTYPE;
BEGIN
OPEN C;
FETCH C INTO v_Cursor;
 DBMS_OUTPUT.PUT_LINE(RPAD('Fecha de realizacion:', 25) || RPAD('Fecha de entrega', 25) || 
 RPAD('Envio:', 25) || RPAD('Tipo de pago:', 25) || RPAD('Estado:', 25) || RPAD('Precio Total:', 25) || RPAD('OID_Pe:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 135, '-'));
 WHILE C%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_Cursor.fechaRealizacion, 25) || RPAD(v_Cursor.fechaEntrega, 25) ||RPAD(v_Cursor.envio, 25) ||
 RPAD(v_Cursor.tipoPago, 25) || RPAD(v_Cursor.estado, 25) || RPAD(v_Cursor.precioTotal, 25) || RPAD(v_Cursor.OID_Pe, 25));
 FETCH C INTO v_Cursor;
 END LOOP;
 CLOSE C;
END verPedido;
/

--RF-13: Consultar ofertas 
--
--Como usuario,
--quiero poder ver los productos que estén en oferta, 
--para adquirirlos si lo deseo.

create or replace procedure verOfertas IS
CURSOR C IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov,color,material,talla
 FROM EQUIPAMIENTO NATURAL JOIN PRODUCTO WHERE oferta !=null;
 v_equipo C%ROWTYPE;
 CURSOR D IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov,cilindrada,estado,anyoFabricacion,tipoMotor,garantia
 FROM MOTOR NATURAL JOIN PRODUCTO WHERE oferta !=null;
 v_motor D%ROWTYPE;
  CURSOR E IS
 SELECT nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov
 FROM  RECAMBIO NATURAL JOIN PRODUCTO  WHERE oferta !=null;
 v_recambio E%ROWTYPE;
 
BEGIN
 OPEN C;
 FETCH C INTO v_equipo;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25)|| 
 RPAD('Color:', 25)|| RPAD('Material:', 25)|| RPAD('Talla:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE C%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_equipo.nombre, 25) || RPAD(v_equipo.descripcion, 25) || RPAD(v_equipo.marca, 25) || RPAD(v_equipo.precio,25) || RPAD(v_equipo.oferta, 25)
|| RPAD(v_equipo.color, 25) || RPAD(v_equipo.material, 25) || RPAD(v_equipo.talla, 25));
 FETCH C INTO v_equipo;
 END LOOP;
 CLOSE C;
  OPEN D;
 FETCH D INTO v_motor;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25)|| 
 RPAD('Cilindradas:', 25)|| RPAD('Estado:', 25)|| RPAD('Año de fabricacion:', 25)||  RPAD('Garantia:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE D%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_motor.nombre, 25) || RPAD(v_motor.descripcion, 25) || RPAD(v_motor.marca, 25) || RPAD(v_motor.precio,25) || RPAD(v_motor.oferta, 25)
|| RPAD(v_motor.cilindrada, 25) || RPAD(v_motor.estado, 25) || RPAD(v_motor.anyoFabricacion, 25)|| RPAD(v_motor.garantia, 25));
 FETCH D INTO v_motor;
 END LOOP;
 CLOSE D;
   OPEN E;
 FETCH E INTO v_recambio;
 DBMS_OUTPUT.PUT_LINE(RPAD('Nombre:', 25) || RPAD('Descripcion:', 25) || RPAD('Marca:', 25) || RPAD('Precio:', 25) || RPAD('Oferta:', 25));
 DBMS_OUTPUT.PUT_LINE(LPAD('-', 120, '-'));
 WHILE E%FOUND LOOP
 DBMS_OUTPUT.PUT_LINE(RPAD(v_recambio.nombre, 25) || RPAD(v_recambio.descripcion, 25) || RPAD(v_recambio.marca, 25) || RPAD(v_recambio.precio,25) || RPAD(v_recambio.oferta, 25));
 FETCH E INTO v_recambio;
 END LOOP;
 CLOSE E;
 
END verOfertas;
/

CREATE OR REPLACE PROCEDURE QUITAR_PROVEEDOR (OID_PROV_A_QUITAR IN PROVEEDOR.OID_PROV%TYPE) AS
  NUM_PRODUCTOS NUMBER;
BEGIN
  SELECT COUNT(*) INTO NUM_PRODUCTOS FROM PRODUCTO WHERE PRODUCTO.OID_Prov = OID_PROV_A_QUITAR;
  
  IF (NUM_PRODUCTOS <> 0) THEN
    RAISE_APPLICATION_ERROR(-20600,'No se puede quitar el proveedor porque tiene prodcutos asignados');
  ELSE
    DELETE FROM PROVEEDOR WHERE OID_Prov = OID_PROV_A_QUITAR ;
  END IF;
END;
/

CREATE OR REPLACE PROCEDURE MODIFICAR_PROVEEDOR
(v_OID_Prov IN PROVEEDOR.OID_Prov%TYPE,
v_email IN PROVEEDOR.email%TYPE,
v_telefono IN PROVEEDOR.telefono%TYPE,
v_direccion IN PROVEEDOR.direccion%TYPE,
v_web IN PROVEEDOR.web%TYPE)
 IS
BEGIN
  UPDATE PROVEEDOR set  email=v_email, telefono=v_telefono, direccion=v_direccion, web=v_web WHERE OID_Prov = v_OID_Prov;
 
END;
/


create or replace procedure NUEVOCREARMOTOR(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xcodigoProveedor IN PROVEEDOR.CODIGO%TYPE,
  xcilindrada IN MOTOR.CILINDRADA%TYPE,
  xestado IN MOTOR.ESTADO%TYPE,
  xanyoFabricacion IN MOTOR.ANYOFABRICACION%TYPE,
  xtipoMotor IN MOTOR.TIPOMOTOR%TYPE,
  xgarantia IN MOTOR.GARANTIA%TYPE
  ) AS
  xOID_PROV NUMBER(9);
BEGIN
   SELECT OID_PROV INTO xOID_PROV FROM PROVEEDOR WHERE PROVEEDOR.CODIGO = xcodigoProveedor ;
 INSERT INTO PRODUCTO(codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov)
 VALUES (xcodigo,xnombre,xdescripcion,xmarca,'Motor',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO MOTOR (cilindrada,estado,anyoFabricacion,tipoMotor,garantia,OID_P)
VALUES(xcilindrada,xestado,xanyoFabricacion,xtipoMotor,xgarantia,(SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End NuevoCrearMotor;
/

create or replace procedure NUEVOCREAREQUIPAMIENTO(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xcodigoProveedor IN PROVEEDOR.CODIGO%TYPE,
  xcolor IN EQUIPAMIENTO.COLOR%TYPE,
  xmaterial IN EQUIPAMIENTO.MATERIAL%TYPE,
  xtalla IN EQUIPAMIENTO.TALLA%TYPE
  ) AS
  xOID_PROV NUMBER(9);
BEGIN
   SELECT OID_PROV INTO xOID_PROV FROM PROVEEDOR WHERE PROVEEDOR.CODIGO = xcodigoProveedor ;
 INSERT INTO PRODUCTO(codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov)
 VALUES (xcodigo,xnombre,xdescripcion,xmarca,'Equipamiento',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO EQUIPAMIENTO (color,material,talla,OID_P) 
VALUES (xcolor,xmaterial,xtalla,(SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End NUEVOCREAREQUIPAMIENTO;
/

create or replace procedure NUEVOCREARRECAMBIO(
  xcodigo IN PRODUCTO.CODIGO%TYPE,
  xnombre IN PRODUCTO.NOMBRE%TYPE,
  xdescripcion IN PRODUCTO.DESCRIPCION%TYPE,
  xmarca IN PRODUCTO.MARCA%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xiva IN PRODUCTO.IVA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE,
  xcodigoProveedor IN PROVEEDOR.CODIGO%TYPE
  ) AS
  xOID_PROV NUMBER(9);
BEGIN
   SELECT OID_PROV INTO xOID_PROV FROM PROVEEDOR WHERE PROVEEDOR.CODIGO = xcodigoProveedor ;
 INSERT INTO PRODUCTO(codigo,nombre,descripcion,marca,tipoProducto,precio,oferta,iva,stock,stockMinimo,OID_Prov)
 VALUES (xcodigo,xnombre,xdescripcion,xmarca,'Recambio',xprecio,xoferta,xiva,xstock,xstockMinimo,xOID_Prov);
INSERT INTO RECAMBIO (OID_P)
VALUES((SELECT OID_P FROM PRODUCTO WHERE codigo=xcodigo));
 COMMIT WORK;
End NuevoCrearRecambio;
/

create or replace procedure updateProducto(
  xOID_P IN PRODUCTO.OID_P%TYPE,
  xprecio IN PRODUCTO.PRECIO%TYPE,
  xoferta IN PRODUCTO.OFERTA%TYPE,
  xstock IN PRODUCTO.STOCK%TYPE,
  xstockMinimo IN PRODUCTO.STOCKMINIMO%TYPE
  ) IS
BEGIN

UPDATE PRODUCTO set precio=xprecio,oferta=xoferta,stock=xstock,
stockMinimo=xstockMinimo
WHERE OID_P = xOID_P;
 COMMIT WORK;
End updateProducto;
/
