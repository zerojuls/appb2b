DELIMITER $$

DROP PROCEDURE IF EXISTS SUCURSALES_GET$$

CREATE PROCEDURE `SUCURSALES_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario INT
 )
BEGIN
 DECLARE SUCURSAL_ID 		INT(11); 
 DECLARE CORPORATIVO_ID		INT(11);
 -- DECLARE P_ERROR			VARCHAR(45);
 SET 	SUCURSAL_ID 	= ( SELECT S.id_sucursal FROM  sucursal_usuario SU 
							INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
							INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
							WHERE U.id_usuario = P_id_usuario );
 SET	CORPORATIVO_ID 	= ( SELECT id_corporativo FROM sucursal WHERE id_sucursal = SUCURSAL_ID  );
 -- SET 	P_ERROR			= 'NO TIENE PERMISOS PARA VISUALIZAR LA TABLA';
	IF (P_id_tipo_usuario = 2)THEN
		SELECT 		SC.id_sucursal AS id_sucursal, 
					CL.nombre AS nombre
		FROM 		sucursal_categoria SC
		INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
		INNER JOIN  corporativo CO ON S.id_corporativo = CO.id_corporativo
		INNER JOIN 	cliente CL ON S.id_cliente = CL.id_cliente
		WHERE 		CO.id_corporativo = CORPORATIVO_ID
					AND S.id_sucursal NOT IN (CORPORATIVO_ID) 
					AND SC.estado = 'A'
		ORDER BY 	nombre
		;
	-- ELSE SELECT P_ERROR AS Mensaje;
	END IF;
END$$