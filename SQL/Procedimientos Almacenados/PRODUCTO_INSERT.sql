DELIMITER $$

DROP PROCEDURE IF EXISTS PRODUCTO_INSERT$$

CREATE PROCEDURE `PRODUCTO_INSERT`(
IN P_id_sucursal	 	INT(11),
 IN P_id_usuario	 	INT(11),
 IN P_id_tipo_precio 	INT(11),
 IN P_id_producto 		INT(11),
 IN P_id_envase			INT(11),
 IN P_id_unidad 		INT(11),
 IN P_cantidad	 		INT(11)
)
BEGIN 
DECLARE NEXT_ID		INT(11);	
DECLARE MAX_ID		INT(11);
DECLARE precio_tmp 	DECIMAL(9,2); 
DECLARE suma_tmp 	DECIMAL(9,2);
DECLARE CLIENTE_ID 	INT(11); 
DECLARE P_ERROR 	VARCHAR(100);
DECLARE P_USUARIO 	VARCHAR(50);
DECLARE CONTINUE HANDLER FOR SQLSTATE '23000'
SELECT P_ERROR AS Mensaje;
 IF ((SELECT COUNT(id_pedido) FROM pedido WHERE id_usuario = P_id_usuario AND estado = 'T' ) = 0) 
 -- REVISA SI NO SE HA CONFIRMADO EL PEDIDO
THEN 
		-- SET 	P_ERROR = '';
		SET		NEXT_ID := (SELECT IFNULL(auto_increment,'ID NO ENTERO') FROM `information_schema`.TABLES 
							WHERE TABLE_SCHEMA = 'globalstore_b2b' AND TABLE_NAME = 'pedido');
		SET 	precio_tmp := (	SELECT precio 
								FROM cliente_producto 
								WHERE id_cliente = (SELECT C.id_cliente FROM sucursal S INNER JOIN corporativo C ON S.id_corporativo = C.id_corporativo WHERE id_sucursal = P_id_sucursal) 
								AND  id_tipo_precio=P_id_tipo_precio  AND id_producto=P_id_producto AND id_envase=P_id_envase AND id_unidad=P_id_unidad);
		SET		suma_tmp := IFNULL((SELECT valor_venta FROM pedido WHERE id_usuario = P_id_usuario AND estado='T'),0) +  P_cantidad*precio_tmp;
		SET 	CLIENTE_ID 	= ( SELECT S.id_cliente FROM  sucursal_usuario SU 
							INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
							INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
							WHERE U.id_usuario = P_id_usuario );
		SET 	P_USUARIO = (SELECT usuario FROM usuario WHERE id_usuario = P_id_usuario);
		INSERT INTO pedido
					(id_pedido,
					 id_usuario,
					 id_cliente,
					 id_moneda,
					 id_tipo_pago,
					 tipo_pedido,
					 igv,
					 valor_venta,
					 tipo_cambio,
					 fecha_registro,
					 usuario_creacion
					)
		VALUES		(NEXT_ID,
					 P_id_usuario,
					 CLIENTE_ID,
					 1,
					 1,
					 'B',
					 0.18, 
					 suma_tmp, 
					 2.80,
					 NOW(),
					 P_USUARIO
					);
		
		INSERT INTO pedido_detalle
					(id_pedido,
					 id_tipo_precio,
					 id_producto,
					 id_envase,
					 id_unidad,
					 cantidad,
					 precio
					)
		VALUES 		(NEXT_ID,
					 P_id_tipo_precio,
					 P_id_producto,
					 P_id_envase,
					 P_id_unidad,
					 P_cantidad, 
					 precio_tmp
					);
 ELSE

-- PEDIDO AUN SIN CONFIRMAR
	SET	MAX_ID := (SELECT MAX(id_pedido) FROM pedido WHERE id_usuario = P_id_usuario AND estado = 'T');
	IF ((SELECT 	COUNT(id_pedido_detalle) FROM pedido_detalle 
		 WHERE 		id_tipo_precio 	= P_id_tipo_precio 
					AND id_producto = P_id_producto 
					AND id_envase 	= P_id_envase 
					AND id_unidad 	= P_id_unidad 
					AND id_pedido 	= MAX_ID
					AND (SELECT id_usuario FROM pedido WHERE id_pedido = MAX_ID AND id_usuario = P_id_usuario AND estado = 'T') = P_id_usuario
					AND estado = 'T')>0)
	THEN SET P_ERROR = 'Ya existe un producto con esta descripción en el carrito.';
	ELSE
		-- SET 	P_ERROR = 'Producto agregado!!!!!!';
		SET 		precio_tmp := (SELECT precio_compra FROM unidad_venta WHERE id_tipo_precio=P_id_tipo_precio 
							   AND id_producto=P_id_producto AND id_envase=P_id_envase AND id_unidad=P_id_unidad);
		SET			suma_tmp := IFNULL(((SELECT valor_venta FROM pedido WHERE id_usuario = P_id_usuario AND estado='T') +  P_cantidad*precio_tmp),'ERROR EN SUMA!');
		UPDATE 		pedido
		SET			valor_venta = suma_tmp
		WHERE 		id_pedido = MAX_ID AND id_usuario = P_id_usuario AND estado = 'T';
		INSERT INTO pedido_detalle
					(id_pedido,
					 id_tipo_precio,
					 id_producto,
					 id_envase,
					 id_unidad,
					 cantidad,
					 precio
					)
		VALUES 		(MAX_ID,
					 P_id_tipo_precio,
					 P_id_producto,
					 P_id_envase,
					 P_id_unidad,
					 P_cantidad, 
					 precio_tmp
					);
	END IF;
END IF;
SELECT P_ERROR AS Mensaje;
END$$

