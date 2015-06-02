+DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDO_MONTOS_GET$$


CREATE PROCEDURE `PEDIDO_MONTOS_GET`(
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
		WHEN 'admin' THEN 
			SELECT 		IFNULL(COUNT(PD.cantidad),0) AS cantidad, 'SIN DATOS' AS monto_comprado
			FROM  		pedido_detalle PD 
			INNER JOIN 	pedido P ON PD.id_pedido = P.id_pedido	
			WHERE  		P.id_usuario = P_id_usuario AND PD.estado='T'
			;
		-- TIPO USUARIO CLIENTE
		WHEN 'cliente' THEN
			SELECT 		IFNULL(COUNT(PD.cantidad),0) AS cantidad, IFNULL(P.valor_venta,0) AS monto_comprado
			FROM  		pedido_detalle PD 
			INNER JOIN 	pedido P ON PD.id_pedido = P.id_pedido	
			WHERE  		P.id_usuario = P_id_usuario AND PD.estado='T'
			;
		ELSE SELECT 'Supervisor' AS cantidad, 'Supervisor' AS monto_comprado; -- SUPERVISOR
	END CASE;
END$$