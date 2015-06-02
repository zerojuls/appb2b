DELIMITER $$

DROP PROCEDURE IF EXISTS MONTOS_GENERALES_GET$$

CREATE PROCEDURE `MONTOS_GENERALES_GET`(
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
	IF( (SELECT COUNT(id_usuario) FROM usuario WHERE id_usuario = P_id_usuario AND P_id_tipo_usuario = 'admin')=1 ) THEN
	-- TIPO USUARIO ADMIN
	-- CUANDO SE DESEEN SABER TODAS LAS SUCURSALES, INCLUYENDO LAS PRINCIPALES
	-- ES CORPORATIVO
	-- WHEN '2' THEN 
			SELECT  	SC.id_corporativo_categoria, S.id_corporativo, SC.id_categoria, 
						-- M.monto_asignado, M.monto_minimo, M.maximo_pedidos, 
						(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 1) AS monto_asignado,
						(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 2) AS monto_minimo,
						(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 3) AS maximo_pedidos,
						'Corporativo' AS saldo
			FROM 		sucursal_categoria SC
			INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
			INNER JOIN 	categoria C ON SC.id_categoria = C.id_categoria
			INNER JOIN 	corporativo CO ON S.id_corporativo = CO.id_corporativo
			INNER JOIN 	corporativo_categoria_monto CCM ON SC.id_corporativo_categoria = CCM.id_corporativo_categoria
			INNER JOIN 	monto M ON CCM.id_monto = M.id_monto
			WHERE 		CO.id_corporativo = CORPORATIVO_ID
			GROUP BY 	SC.id_corporativo_categoria
			;
	-- TIPO USUARIO CLIENTE
	-- CUANDO SE DESEE SABER UNA SUCURSAL EN ESPECIFICO
	-- ES SUCURSAL
	ELSE
		IF( (SELECT COUNT(id_usuario) FROM usuario WHERE id_usuario = P_id_usuario AND P_id_tipo_usuario = 'cliente')=1 ) THEN	
			IF((SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = SUCURSAL_ID)>0) -- ES SUCURSAL Y MANEJA UN MONTO PROPIO
			THEN
					SELECT 		SC.id_sucursal, SC.id_corporativo_categoria, SC.id_categoria, 
								-- M.monto_asignado, M.monto_minimo, M.maximo_pedidos, 
								(SELECT valor FROM sucursal_categoria_monto WHERE id_monto = 1) AS monto_asignado,
								(SELECT valor FROM sucursal_categoria_monto WHERE id_monto = 2) AS monto_minimo,
								(SELECT valor FROM sucursal_categoria_monto WHERE id_monto = 3) AS maximo_pedidos,
								SC.monto_acumulado AS saldo
					FROM 		sucursal_categoria_monto SCM
					INNER JOIN 	sucursal_categoria SC ON SCM.id_categoria = SC.id_categoria AND SCM.id_sucursal = SC.id_sucursal
					INNER JOIN 	monto M ON SCM.id_monto = M.id_monto
					WHERE 		SC.id_sucursal = SUCURSAL_ID
					;
			ELSE -- ES SUCURSAL PERO SE GUIA DE MONTO DEL CORPORATIVO
					SELECT  	SC.id_corporativo_categoria, S.id_corporativo, SC.id_categoria, 
								-- M.monto_asignado, M.monto_minimo, M.maximo_pedidos, 
								(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 1) AS monto_asignado,
								(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 2) AS monto_minimo,
								(SELECT valor FROM corporativo_categoria_monto WHERE id_monto = 3) AS maximo_pedidos,
								SC.monto_acumulado AS saldo
					FROM 		sucursal_categoria SC
					INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
					INNER JOIN 	categoria C ON SC.id_categoria = C.id_categoria
					INNER JOIN 	corporativo CO ON S.id_corporativo = CO.id_corporativo
					INNER JOIN 	corporativo_categoria_monto CCM ON SC.id_corporativo_categoria = CCM.id_corporativo_categoria
					INNER JOIN 	monto M ON CCM.id_monto = M.id_monto
					WHERE 		CO.id_corporativo = CORPORATIVO_ID
					AND 		SC.id_sucursal NOT IN (SELECT id_sucursal FROM sucursal_categoria_monto)
					;
			END IF;
		ELSE  -- SUPERVISOR
			SELECT  	0 AS id_corporativo_categoria, 0 AS id_corporativo,
						0 AS id_categoria, 'Supervisor' AS monto_asignado, 0 AS monto_minimo, 0 AS maximo_pedidos, 'Supervisor' AS saldo;
		END IF;
	
	END IF;
END$$