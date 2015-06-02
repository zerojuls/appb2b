DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDOS_AUTORIZAR_DISPONIBLE_GET$$

CREATE PROCEDURE `PEDIDOS_AUTORIZAR_DISPONIBLE_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario INT
)
BEGIN
-- CASE P_id_tipo_usuario -- TIPO_USUARIO
-- WHEN 'administrador' THEN (SELECT 'NO PUEDE VERLO'); WHEN 'supervisor' THEN (SELECT 'NO PUEDE VERLO'); WHEN 'sucursal' THEN
	CASE WHEN ((SELECT 	COUNT(*)
					FROM pedido P
					INNER JOIN cliente C ON P.id_cliente = C.id_cliente
					INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
					INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
					WHERE 
					P.flag_autorizado IN (1) 
					AND P.estado = 'S'
					AND SU.id_usuario = P_id_usuario) > 0) 
	THEN
		(SELECT 'Pedidos por Autorizar' AS titulo ,C.nombre AS cliente, CA.nombre AS categoria, valor_venta AS monto, DATE_FORMAT(fecha_registro, '%d/%m/%Y') AS fecha
		FROM pedido P
		INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
		INNER JOIN producto PR ON PD.id_producto = PR.id_producto
		INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
		INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
		INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
		INNER JOIN cliente C ON P.id_cliente = C.id_cliente
		INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
		INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
		WHERE P.flag_autorizado = 1 AND SU.id_usuario = P_id_usuario AND P.estado = 'S'
		GROUP BY PD.id_pedido
		ORDER BY fecha
		-- LIMIT 4
		);
	ELSE	
			SELECT 'Pedidos Pendientes' AS titulo, C.nombre AS cliente, CA.nombre AS categoria, valor_venta AS monto, DATE_FORMAT(fecha_registro, '%d/%m/%Y') AS fecha
			FROM pedido P
			INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
			INNER JOIN producto PR ON PD.id_producto = PR.id_producto
			INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
			INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
			INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
			INNER JOIN cliente C ON P.id_cliente = C.id_cliente
			INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
			INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
			WHERE P.flag_autorizado = 0 AND SU.id_usuario = P_id_usuario AND P.estado = 'S'
			GROUP BY PD.id_pedido
			ORDER BY fecha
			-- LIMIT 4
			;
	END CASE;
-- END CASE;
 END$$