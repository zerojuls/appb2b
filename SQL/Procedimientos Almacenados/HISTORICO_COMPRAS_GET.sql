DELIMITER $$

DROP PROCEDURE IF EXISTS HISTORICO_COMPRAS_GET$$

CREATE PROCEDURE `HISTORICO_COMPRAS_GET`(
 IN P_fecha DATETIME
 )
BEGIN
	SELECT
				categoria.nombre AS centro_costo,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=4, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes0,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=5, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes1,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=6, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes2,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=7, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes3,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=8, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes4,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=9, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes5,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=10, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes6,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=11, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes7,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2013' AND MONTH(cabecera_pedido.fecha)=12, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) 	AS mes8,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2014' AND MONTH(cabecera_pedido.fecha)=1, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) AS mes9,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2014' AND MONTH(cabecera_pedido.fecha)=2, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) AS mes10,
				SUM(IF(YEAR(cabecera_pedido.fecha)='2014' AND MONTH(cabecera_pedido.fecha)=3, (detalle_pedido.cantidad*detalle_pedido.precio) ,0)) AS mes11
	FROM 		globalstore.cabecera_pedido 

	INNER JOIN  globalstore.detalle_pedido 	ON cabecera_pedido.id = detalle_pedido.id_cabecera
	INNER JOIN  globalstore.producto 		ON detalle_pedido.id_producto = producto.id
	INNER JOIN  globalstore.cliente 		ON cabecera_pedido.id_cliente = cliente.id
	INNER JOIN  globalstore.categoria 		ON producto.id_categoria = categoria.id

	WHERE 		
				cliente.id_cliente = 13

	GROUP BY 	producto.id_categoria
-- 	LIMIT 10
	;
END$$

