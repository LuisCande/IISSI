
CREATE OR REPLACE TRIGGER actualizacionStockNueva
  AFTER UPDATE ON PEDIDO
  FOR EACH ROW
  DECLARE
  CURSOR C IS
SELECT cantidad, OID_P FROM LineaPedido WHERE OID_Pe=:new.OID_Pe;
v_Cursor C%ROWTYPE;
  contador integer;
  BEGIN
  IF (:old.estado='Espera' AND :new.estado!='Espera')
      THEN
      OPEN C;
FETCH C INTO v_Cursor;
WHILE C%FOUND LOOP
    UPDATE PRODUCTO SET stock= stock - v_Cursor.cantidad WHERE OID_P=v_Cursor.OID_P;
     FETCH C INTO v_Cursor;
 END LOOP;
 CLOSE C;
  END IF;
END;
/  

CREATE OR REPLACE TRIGGER nullenoferta
  BEFORE INSERT ON PRODUCTO
  FOR EACH ROW
  WHEN( NEW.OFERTA IS NULL) 
  BEGIN
  :NEW.OFERTA:=0;
  END;
  /
  
  CREATE OR REPLACE TRIGGER StockMinTrigger
  BEFORE INSERT OR UPDATE ON LINEAPEDIDO
  FOR EACH ROW
  DECLARE Stocky PRODUCTO.STOCK%TYPE;
  BEGIN
  SELECT STOCK - STOCKMINIMO INTO Stocky FROM PRODUCTO WHERE OID_P = :NEW.OID_P;  
   IF (:NEW.CANTIDAD > Stocky) 
  THEN
    RAISE_APPLICATION_ERROR( -20001, 
                             'Lo siento, algunos productos que quieres comprar están agotados' );
  END IF;

  END;
  /
  
  
CREATE OR REPLACE TRIGGER precioTotalNotNull
  BEFORE UPDATE ON PEDIDO
  FOR EACH ROW
  WHEN( NEW.PRECIOTOTAL IS NULL) 
  BEGIN
  :NEW.PRECIOTOTAL:=0;
  END;
  /
  
CREATE OR REPLACE TRIGGER CrearCarrito
  AFTER INSERT ON USUARIO
  FOR EACH ROW
  BEGIN
  INSERT INTO PEDIDO (fechaEntrega,envio,tipoPago,estado,OID_Us)
  VALUES ('30-9-2039','True','Tarjeta','Espera',:new.OID_Us);
  END;
  /
  



CREATE OR REPLACE TRIGGER precioTotalPedido
  AFTER INSERT OR DELETE OR UPDATE ON LINEAPEDIDO
  FOR EACH ROW
  BEGIN
  IF (inserting or updating)
    then
    update PEDIDO set precioTotal=precioTotal+
    (:new.cantidad*:new.precio) where OID_PE=:new.OID_PE;
  END IF;
  IF (updating )
    then
    update PEDIDO set precioTotal=precioTotal-
    (:old.cantidad*:new.precio) where OID_PE=:old.OID_PE;
  END IF;
  IF(deleting)
    then
    update PEDIDO set precioTotal=precioTotal-
    (:old.cantidad*:old.precio) where OID_PE=:old.OID_PE;
  END IF;
END;
/
