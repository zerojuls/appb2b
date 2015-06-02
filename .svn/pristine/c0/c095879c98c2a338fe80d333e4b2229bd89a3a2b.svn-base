DELIMITER $$

DROP PROCEDURE IF EXISTS PRODUCTO_UPDATE$$

CREATE PROCEDURE `PRODUCTO_UPDATE`(
 IN P_id_usuario 		INT(13), 
 IN P_id_pedido_detalle	INT(11),
 IN P_id_pedido			INT(11),
 IN P_cantidad	 		INT(11)
 )
BEGIN
DECLARE precio_tmp 		DECIMAL(9,2); 
DECLARE suma_tmp 		DECIMAL(9,2);
DECLARE P_ERROR 		VARCHAR(100);
DECLARE CONTINUE HANDLER FOR SQLSTATE '23000'
SELECT P_ERROR;
 
-- PEDIDO AUN SIN CONFIRMAR
		SET 	precio_tmp := ( SELECT 		UV.precio_compra FROM pedido_detalle PD
								INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto 
											AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
								WHERE 		(SELECT id_usuario FROM pedido WHERE id_pedido = P_id_pedido AND estado = 'T') = P_id_usuario
											AND PD.id_pedido_detalle = P_id_pedido_detalle );
		SET		suma_tmp 	:= IFNULL(( SELECT 	cantidad*precio FROM pedido_detalle 
										WHERE 	(SELECT id_usuario FROM pedido WHERE id_pedido = P_id_pedido AND estado = 'T') = P_id_usuario
												AND estado='T' 
												AND id_pedido_detalle NOT IN(P_id_pedido_detalle)
												),0) +  P_cantidad*precio_tmp;
		UPDATE 	pedido
		SET		valor_venta = suma_tmp
		WHERE 	id_pedido = P_id_pedido AND id_usuario = P_id_usuario AND estado = 'T';
		
		UPDATE	pedido_detalle 
		SET 	cantidad = P_cantidad 
		WHERE 	id_pedido_detalle = P_id_pedido_detalle 
				AND (SELECT id_usuario FROM pedido WHERE id_pedido = P_id_pedido AND estado = 'T') = P_id_usuario
				AND estado = 'T';

END$$