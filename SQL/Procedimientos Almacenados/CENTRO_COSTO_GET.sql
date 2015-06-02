DELIMITER $$

DROP PROCEDURE IF EXISTS CENTRO_COSTO_GET$$

CREATE PROCEDURE `CENTRO_COSTO_GET`(
 IN P_id_usuario	 		INT(11),
 IN P_id_tipo_usuario 		VARCHAR(45)
)
BEGIN 
 DECLARE P_ERROR			VARCHAR(45);
 SET 	P_ERROR			= 'NO TIENE PERMISOS PARA VISUALIZAR LA TABLA';
 
 CASE P_id_tipo_usuario
	-- TIPO USUARIO ADMIN
	-- CUANDO SE DESEEN SABER TODAS LAS SUCURSALES, INCLUYENDO LAS PRINCIPALES
		WHEN 'admin' THEN
			SELECT 
						S.id_sucursal AS id_sucursal, 
						C.codigo_cliente AS codigo_cliente, 
						C.nombre AS nombre, 
						CONCAT(PE.nombre, ' ',PE.apellido) AS responsable,
						U.email AS email,
						C.direccion AS direccion,
						(CASE CU.estado WHEN 'A' THEN 'Activo' END) AS estado
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
			INNER JOIN cliente_usuario CU ON C.id_cliente = CU.id_cliente
			INNER JOIN usuario U ON CU.id_usuario = U.id_usuario
			INNER JOIN persona_usuario PU ON U.id_usuario = PU.id_usuario
			INNER JOIN persona PE ON PU.id_persona = PE.id_persona
			WHERE 		SU.id_usuario = P_id_usuario;
	-- TIPO USUARIO SUPERVISOR
	-- CUANDO SE DESEEN SABER SOLO LAS SUCURSALES
		WHEN 'super' THEN
			SELECT 
						S.id_sucursal AS id_sucursal, 
						C.codigo_cliente AS codigo_cliente, 
						C.nombre AS nombre, 
						CONCAT(PE.nombre, ' ',PE.apellido) AS responsable,
						U.email AS email,
						C.direccion AS direccion,
						(CASE CU.estado WHEN 'A' THEN 'Activo' END) AS estado
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
			INNER JOIN cliente_usuario CU ON C.id_cliente = CU.id_cliente
			INNER JOIN usuario U ON CU.id_usuario = U.id_usuario
			INNER JOIN persona_usuario PU ON U.id_usuario = PU.id_usuario
			INNER JOIN persona PE ON PU.id_persona = PE.id_persona
			WHERE 		SU.id_usuario = P_id_usuario;
		ELSE SELECT P_ERROR AS Mensaje;
	END CASE;
END$$

