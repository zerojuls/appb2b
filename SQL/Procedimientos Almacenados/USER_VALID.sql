delimiter $$

DROP PROCEDURE IF EXISTS USER_VALID$$

CREATE PROCEDURE `USER_VALID`(
 IN P_UsuarioId VARCHAR(64),
 IN P_Password VARCHAR(64)
 )
BEGIN
DECLARE TIPO_USUARIO 	INT(11);
SET TIPO_USUARIO 	= ( SELECT id_tipo_usuario FROM usuario WHERE usuario = P_UsuarioId AND clave = P_Password);
	CASE
	WHEN	(TIPO_USUARIO = 1) 
	THEN 	SELECT 		id_usuario, id_tipo_usuario, 'SuperAdmin', 'Olimpo' AS usuario_nombre FROM usuario 
			WHERE 		usuario = P_UsuarioId AND clave = P_Password AND estado='A'
			;
	WHEN	(TIPO_USUARIO = 2) 
	THEN	SELECT 		CONCAT(PE.nombre,' ',PE.apellido) AS nombre_persona, 		-- PERSONA
						TU.nombre AS tipo_usuario, TU.alias AS alias_tipo_usuario, 	-- TIPO USUARIO
						U.id_usuario AS id_usuario, U.usuario AS nombre_usuario,  	-- USUARIO
						CO.id_corporativo AS id_corporativo, 						-- CORPORATIVO
						C2.nombre AS nombre_corporativo, 							-- CORPORATIVO
						CO.imagen AS imagen_corporativo
			FROM 		cliente_usuario CU
			INNER JOIN 	corporativo CO ON CU.id_cliente = CO.id_cliente
			INNER JOIN 	usuario U ON CU.id_usuario = U.id_usuario
			INNER JOIN 	tipo_usuario TU ON U.id_tipo_usuario = TU.id_tipo_usuario
			INNER JOIN 	persona_usuario PU ON U.id_usuario = PU.id_usuario
			INNER JOIN 	persona PE ON PU.id_persona = PE.id_persona
			INNER JOIN	cliente C2 ON CO.id_cliente = C2.id
			WHERE 		U.usuario 	= P_UsuarioId 
						AND U.clave = P_Password
						AND U.estado='A'
			GROUP BY CO.id_corporativo
			;
	WHEN	(TIPO_USUARIO = 3) 
	THEN	SELECT 		CONCAT(PE.nombre,' ',PE.apellido) AS nombre_persona, 		-- PERSONA
						TU.nombre AS tipo_usuario, TU.alias AS alias_tipo_usuario, 	-- TIPO USUARIO
						U.id_usuario AS id_usuario, U.usuario AS nombre_usuario,  	-- USUARIO
						CO.id_corporativo AS id_corporativo, 						-- CORPORATIVO
						C2.nombre AS nombre_corporativo, 							-- CORPORATIVO
						CO.imagen AS imagen_corporativo								-- CORPORATIVO
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	corporativo CO ON S.id_corporativo = CO.id_corporativo
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	tipo_usuario TU ON U.id_tipo_usuario = TU.id_tipo_usuario
			INNER JOIN 	persona_usuario PU ON U.id_usuario = PU.id_usuario
			INNER JOIN 	persona PE ON PU.id_persona = PE.id_persona
			INNER JOIN	cliente C1 ON S.id_cliente = C1.id
			INNER JOIN	cliente C2 ON CO.id_cliente = C2.id
			WHERE 		U.usuario 	= P_UsuarioId 
						AND U.clave = P_Password
						AND U.estado='A'
			GROUP BY CO.id_corporativo
			;
	WHEN	(TIPO_USUARIO = 6) 
	THEN	SELECT 		-- CONCAT(PE.nombre, PE.apellido) AS nombre_persona, 			-- PERSONA -- OCULTADA SOLO PARA LA BD globalstore_test
						UCWORDS(C1.apellido) AS nombre_persona, -- CAMBIO SOLO HECHO PARA BD globalstore_test, HASTA QUE SE AGREGAN LOS DATOS A LA TABLA
						TU.nombre AS tipo_usuario, TU.alias AS alias_tipo_usuario, 	-- TIPO USUARIO
						U.id_usuario AS id_usuario, U.usuario AS nombre_usuario,  	-- USUARIO
						S.id_sucursal AS id_sucursal, C1.nombre AS nombre_sucursal, -- SUCURSAL
						CO.id_corporativo AS id_corporativo, 						-- CORPORATIVO
						C2.nombre AS nombre_corporativo, 							-- CORPORATIVO
						CO.imagen AS imagen_corporativo								-- CORPORATIVO
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	corporativo CO ON S.id_corporativo = CO.id_corporativo
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	tipo_usuario TU ON U.id_tipo_usuario = TU.id_tipo_usuario
			-- INNER JOIN 	persona_usuario PU ON U.id_usuario = PU.id_usuario
			-- INNER JOIN 	persona PE ON PU.id_persona = PE.id_persona
			INNER JOIN	cliente C1 ON S.id_cliente = C1.id -- SE CAMBIO id_cliente POR ID EN BD globalstore_test
			INNER JOIN	cliente C2 ON CO.id_cliente = C2.id -- SE CAMBIO id_cliente POR ID EN BD globalstore_test
			WHERE 		U.usuario 	= P_UsuarioId 
						AND U.clave = P_Password
						AND U.estado='A'
			;
	ELSE 
			SELECT 		-- CONCAT(PE.nombre, PE.apellido) AS nombre_persona, 			-- PERSONA -- OCULTADA SOLO PARA LA BD globalstore_test
						UCWORDS(C1.apellido) AS nombre_persona, -- CAMBIO SOLO HECHO PARA BD globalstore_test, HASTA QUE SE AGREGAN LOS DATOS A LA TABLA
						TU.nombre AS tipo_usuario, TU.alias AS alias_tipo_usuario, 	-- TIPO USUARIO
						U.id_usuario AS id_usuario, U.usuario AS nombre_usuario,  	-- USUARIO
						S.id_sucursal AS id_sucursal, C1.nombre AS nombre_sucursal, -- SUCURSAL
						CO.id_corporativo AS id_corporativo, 						-- CORPORATIVO
						C2.nombre AS nombre_corporativo, 							-- CORPORATIVO
						CO.imagen AS imagen_corporativo								-- CORPORATIVO
			FROM 		sucursal_usuario SU
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	corporativo CO ON S.id_corporativo = CO.id_corporativo
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	tipo_usuario TU ON U.id_tipo_usuario = TU.id_tipo_usuario
			-- INNER JOIN 	persona_usuario PU ON U.id_usuario = PU.id_usuario
			-- INNER JOIN 	persona PE ON PU.id_persona = PE.id_persona
			INNER JOIN	cliente C1 ON S.id_cliente = C1.id -- SE CAMBIO id_cliente POR ID EN BD globalstore_test
			INNER JOIN	cliente C2 ON CO.id_cliente = C2.id -- SE CAMBIO id_cliente POR ID EN BD globalstore_test
			WHERE 		U.usuario 	= P_UsuarioId 
						AND U.clave = P_Password
						AND U.estado='A'
			;
	END CASE;
 END$$