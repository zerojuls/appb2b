DELIMITER $$

DROP PROCEDURE IF EXISTS PRODUCTO_GET$$

CREATE PROCEDURE `PRODUCTO_GET`(
 IN P_id_categoria INT,
 IN P_id_tipo_precio INT
 )
BEGIN
	SELECT 		P.id_producto,
				P.codigo AS CODIGO, 
				SC.id_categoria AS ID_CATEGORIA,
				LOWER(C.nombre) AS CATEGORIA,
				P.nombre_corto AS PRODUCTO, 
				M.nombre AS MARCA, 
				U.nombre AS UNIDAD,
				UV.precio_compra AS PRECIO
	FROM 		unidad_venta UV
	INNER JOIN 	tipo_precio TP ON UV.id_tipo_precio=TP.id_tipo_precio
	INNER JOIN 	producto P ON  UV.id_producto=P.id_producto
	INNER JOIN 	envase E ON UV.id_envase=E.id_envase
	INNER JOIN 	unidad U ON P.id_unidad = U.id_unidad
	INNER JOIN 	subcategoria SC ON P.id_subcategoria = SC.id_subcategoria
	INNER JOIN 	marca M ON P.id_marca = M.id_marca
	INNER JOIN	categoria C ON SC.id_categoria=C.id_categoria
	WHERE 		SC.id_categoria = P_id_categoria OR P_id_categoria IS NULL
				AND UV.id_tipo_precio = P_id_tipo_precio OR P_id_tipo_precio IS NULL
	ORDER BY 	CODIGO
	;

END$$
