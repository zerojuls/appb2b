
DELIMITER $$

DROP PROCEDURE IF EXISTS HISTORIAL_PEDIDO_GET$$

CREATE PROCEDURE `HISTORIAL_PEDIDO_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario INT
)
BEGIN
SELECT	CONCAT('000002',UV.id_tipo_precio,UV.id_producto,UV.id_envase,UV.id_unidad) AS codigo_pedido,
					P.id_pedido AS id_pedido,
					'active' AS activo, C.nombre AS cliente, C.direccion AS direccion, CA.nombre AS categoria, 
					valor_venta AS monto, 
					LOWER(DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i:%s %p')) AS fecha, 
					(CASE P.estado WHEN 'S' THEN 'Pendiente' WHEN 'D' THEN 'Despachado' WHEN 'L' THEN 'Parcial' WHEN 'E' THEN 'Entregado' WHEN 'P' THEN 'Procesado' WHEN 'N' THEN 'Anulado' WHEN 'T' THEN 'Temporal' END) AS estado
			FROM pedido P
			INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
			INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
			INNER JOIN producto PR ON PD.id_producto = PR.id_producto
			INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
			INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
			INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
			INNER JOIN cliente C ON P.id_cliente = C.id_cliente
			INNER JOIN sucursal S ON C.id_cliente  = S.id_cliente
			INNER JOIN sucursal_usuario SU ON S.id_sucursal = SU.id_sucursal 
			WHERE P.flag_autorizado = 0 AND SU.id_usuario = P_id_usuario AND P.estado IN ('S','D','L','E','P','N')
			GROUP BY PD.id_pedido
			ORDER BY fecha
			-- LIMIT 4
			;
END$$
