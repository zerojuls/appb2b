DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDO_SALDO_GET$$

CREATE PROCEDURE `PEDIDO_SALDO_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario INT
 )
BEGIN

	 DECLARE SUCURSAL_ID 		INT(11); 
	 DECLARE CORPORATIVO_ID		INT(11);	
	 DECLARE P_ERROR			VARCHAR(45);
	 
	 SET 	SUCURSAL_ID 	= ( SELECT S.id_sucursal FROM  sucursal_usuario SU 
								INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
								INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
								WHERE U.id_usuario = P_id_usuario );
	 SET	CORPORATIVO_ID 	= ( SELECT id_corporativo FROM sucursal WHERE id_sucursal = SUCURSAL_ID  );
	 SET 	P_ERROR			= 'NO TIENE PERMISOS PARA VISUALIZAR LA TABLA';


CASE P_id_tipo_usuario
		-- TIPO USUARIO ADMIN
		-- CUANDO SE DESEEN SABER TODAS LAS SUCURSALES, INCLUYENDO LAS PRINCIPALES
	WHEN '2' THEN 
		SELECT 		'Sub Total' AS TITULO, IFNULL(SUM(P.valor_venta),0) AS IMPORTE
		FROM  	   	sucursal_usuario SU 
		INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
		INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
		INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
		INNER JOIN corporativo CO ON P.id_cliente = CO.id_cliente
		WHERE  		SU.id_sucursal = SUCURSAL_ID
					AND P.estado = 'T'
		UNION 		
		SELECT 		'Envio' AS TITULO, 0 AS IMPORTE;

	WHEN '6' THEN
		IF((SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = SUCURSAL_ID)>0) -- ES SUCURSAL Y MANEJA UN MONTO PROPIO
		THEN
			SELECT 		'Sub Total' AS TITULO, IFNULL(SUM(P.valor_venta),0) AS IMPORTE
			FROM  	   	sucursal_usuario SU 
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
			WHERE  		SU.id_sucursal = SUCURSAL_ID
						AND P.estado = 'T'
			UNION 		
			SELECT 		'Envio' AS TITULO, 0 AS IMPORTE;
		ELSE -- ES SUCURSAL PERO SE GUIA DE MONTO DEL CORPORATIVO
			SELECT 		'Sub Total' AS TITULO, IFNULL(SUM(P.valor_venta),0) AS IMPORTE
			FROM  	   	sucursal_usuario SU 
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
			WHERE 		SU.id_sucursal = SUCURSAL_ID
						AND P.estado = 'T'
			UNION 		
			SELECT 		'Envio' AS TITULO, 0 AS IMPORTE;
			END IF;
	ELSE SELECT P_ERROR AS Mensaje;
	END CASE;
	
END$$