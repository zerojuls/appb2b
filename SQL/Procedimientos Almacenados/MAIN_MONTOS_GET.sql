DELIMITER $$

DROP PROCEDURE IF EXISTS MAIN_MONTOS_GET$$


CREATE PROCEDURE `MAIN_MONTOS_GET`(
 IN P_id_usuario INT
 -- IN P_id_tipo_usuario INT
)
BEGIN

	-- PEDIDO DETALLE SIMPLE
	SELECT 		COUNT(PD.cantidad) AS cantidad, IFNULL(SUM(P.valor_venta),0) AS monto_comprado, P.id_cliente
				, IFNULL((CASE WHEN (C.fecha_max_pedidos = 0) THEN 'SIN CIERRE' ELSE C.fecha_max_pedidos END),'SIN PEDIDOS')
	FROM 		pedido P
	INNER JOIN 	pedido_detalle PD ON P.id_pedido = PD.id_pedido
	INNER JOIN 	usuario U ON P.id_usuario = U.id_usuario
	INNER JOIN 	cliente C ON P.id_cliente = C.id_cliente
	WHERE 		P.id_usuario = P_id_usuario
	AND 		P.estado = 'S'
	;


END$$