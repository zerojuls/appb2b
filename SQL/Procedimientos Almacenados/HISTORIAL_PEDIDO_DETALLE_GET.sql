DELIMITER $$

DROP PROCEDURE IF EXISTS HISTORIAL_PEDIDO_DETALLE_GET$$

CREATE PROCEDURE `HISTORIAL_PEDIDO_DETALLE_GET`(
 IN P_id_pedido INT
)
BEGIN
		SELECT 		PD.id_pedido_detalle, PD.id_pedido, PR.codigo AS codigo_producto, PR.nombre, 
					M.nombre AS marca, U.nombre AS unidad, PD.cantidad, PD.precio
		FROM 		pedido_detalle PD
		INNER JOIN 	pedido P ON PD.id_pedido=P.id_pedido
		INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
		INNER JOIN 	unidad U ON PD.id_unidad=U.id_unidad
		INNER JOIN 	marca M ON PR.id_marca=M.id_marca
		WHERE P.id_pedido = P_id_pedido;
END$$