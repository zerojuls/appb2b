DELIMITER $$

DROP PROCEDURE IF EXISTS PRODUCTOS_CONSUMIDOS_GET$$

CREATE PROCEDURE `PRODUCTOS_CONSUMIDOS_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario VARCHAR(20)
 )
BEGIN


		SELECT 	(DP.id_producto) AS id_producto, 
				LOWER(P.nombre_corto) AS nombre, 
				IFNULL(((SUM(DP.cantidad))*(CASE P_id_tipo_usuario WHEN 'admin' THEN 1 WHEN 'super' THEN 1 WHEN 'cliente' THEN 3 END)),0) AS valor,
				IFNULL((SUM(DP.cantidad)),0) AS cantidad 
		FROM  globalstore.cabecera_pedido CP
		INNER JOIN globalstore.detalle_pedido DP ON CP.id = DP.id_cabecera
		INNER JOIN producto P ON DP.id_producto = P.id_producto
		WHERE CP.fecha < CURDATE()
		AND CP.fecha > DATE_SUB(CURDATE(),INTERVAL 1 MONTH)
		AND CP.id_cliente IN (	SELECT S.id_cliente FROM  globalstore_b2b.sucursal_usuario SU 
								INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
								INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
								WHERE U.id_usuario = P_id_usuario	)
		GROUP BY DP.id_producto
		ORDER BY valor DESC
		LIMIT 11;

END$$