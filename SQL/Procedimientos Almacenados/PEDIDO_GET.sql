DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDO_GET$$

CREATE PROCEDURE `PEDIDO_GET`(
 IN P_id_corporativo INT
 )
BEGIN
	SELECT 		S.id_sucursal AS ID_SUCURSAL, C.id_cliente ID_CLIENTE, TC.nombre AS TIPO_CLIENTE, TI.nombre AS TIPO_IDENTIFICACION, C.documento AS DOCUMENTO, 
				C.codigo_cliente AS CODIGO_CLIENTE, C.codigo_logistico AS CODIGO_LOGISTICO, C.nombre AS NOMBRE, C.direccion AS DIRECCION, 
				D.nombre AS DISTRITO, C.referencia AS REFERENCIA
	FROM 		global_b2b.cliente C
	INNER JOIN 	global_b2b.tipo_cliente TC ON C.id_tipo_cliente = TC.id_tipo_cliente
	INNER JOIN 	global_b2b.tipo_identificacion TI ON C.id_tipo_identificacion = TI.id_tipo_identificacion
	INNER JOIN 	global_b2b.sucursal S ON C.id_cliente = S.id_cliente
	INNER JOIN 	global_b2b.distrito D ON C.id_distrito = D.id_distrito
	WHERE 		C.estado = 'A'
				AND S.id_corporativo = P_id_corporativo
	ORDER BY	TIPO_CLIENTE;
END$$

