DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDO_CONFIRM$$

CREATE PROCEDURE `PEDIDO_CONFIRM`(
  IN P_id_usuario INT,
  IN P_id_tipo_usuario INT,
  IN P_id_categoria INT, -- NUEVO
  IN P_id_sucursal INT,
  IN P_observacion VARCHAR(300)
 )
BEGIN
DECLARE P_saldo_contable 			DECIMAL(9,2);
DECLARE P_saldo_sucursal 			DECIMAL(9,2);
DECLARE P_saldo_contable_admin 		DECIMAL(9,2);
DECLARE P_saldo_sucursal_admin 		DECIMAL(9,2);

DECLARE CORPORATIVO_CATEGORIA_ID 	INT(11);
DECLARE MAX_ID						INT(11);
DECLARE SUCURSAL_ID 				INT(11);
DECLARE P_ERROR 					VARCHAR(100);
DECLARE CONTINUE HANDLER FOR SQLSTATE '23000'
SELECT P_ERROR;

SET	MAX_ID 						:= (SELECT MAX(id_pedido) FROM pedido WHERE id_usuario = P_id_usuario AND estado = 'T');
SET CORPORATIVO_CATEGORIA_ID 	:= (SELECT SC.id_corporativo_categoria 
									FROM sucursal_categoria SC
									INNER JOIN sucursal S ON SC.id_sucursal = S.id_sucursal
									INNER JOIN sucursal_usuario SU ON SU.id_sucursal = S.id_sucursal
									INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
									WHERE U.id_usuario = P_id_usuario AND SC.id_categoria = P_id_categoria);
SET SUCURSAL_ID 				:= (SELECT S.id_sucursal FROM  sucursal_usuario SU 
									INNER JOIN usuario U ON SU.id_usuario = U.id_usuario
									INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
									WHERE U.id_usuario = P_id_usuario);

SET P_saldo_contable 			:= (SELECT SC2.monto_acumulado + IFNULL((SELECT valor_venta FROM pedido WHERE id_pedido = MAX_ID ),0)
									FROM 	sucursal_categoria SC2
									WHERE SC2.id_sucursal = SUCURSAL_ID AND SC2.id_categoria = P_id_categoria );

SET P_saldo_sucursal 			:= (SELECT 	SC3.monto_acumulado + IFNULL((SELECT valor_venta FROM pedido WHERE id_pedido = MAX_ID),0)
									FROM 	sucursal_categoria SCM
									INNER JOIN sucursal_categoria SC3 ON SC3.id_sucursal = SCM.id_sucursal
									WHERE 	SCM.id_sucursal = SUCURSAL_ID
									AND SCM.id_categoria = P_id_categoria AND SC3.id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID
									);


SET P_saldo_contable_admin 			:= (SELECT SC2.monto_acumulado + IFNULL((SELECT valor_venta FROM pedido WHERE id_pedido = MAX_ID ),0)
									FROM 	sucursal_categoria SC2
									WHERE SC2.id_sucursal = P_id_sucursal AND SC2.id_categoria = P_id_categoria);

SET P_saldo_sucursal_admin 			:= (SELECT 	SC3.monto_acumulado + IFNULL((SELECT valor_venta FROM pedido WHERE id_pedido = MAX_ID),0)
									FROM 	sucursal_categoria SCM
									INNER JOIN sucursal_categoria SC3 ON SC3.id_sucursal = SCM.id_sucursal
									WHERE 	SCM.id_sucursal = P_id_sucursal
	AND SCM.id_categoria = P_id_categoria AND SC3.id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID
);



	IF((SELECT 	COUNT(id_pedido_detalle) FROM pedido_detalle 
		WHERE 	id_pedido = MAX_ID
				AND (SELECT id_usuario FROM pedido WHERE id_pedido = MAX_ID AND estado = 'T') = P_id_usuario
 				AND estado='T'
		)=0)
	-- REVISA SI HAY PEDIDO PARA CONFIRMAR
	THEN 
		SET 	P_ERROR = 'No hay pedidos por confirmar.';
	ELSE
		UPDATE 	pedido_detalle SET estado = 'S' 
		WHERE 	id_pedido = MAX_ID
		AND 	( 
				 SELECT id_usuario FROM pedido 
				 WHERE id_pedido = MAX_ID
				 AND id_usuario = P_id_usuario AND estado = 'T'
				 ) = P_id_usuario;
		UPDATE 	pedido SET 	flag_autorizado = (SELECT flag_autorizado FROM sucursal WHERE id_sucursal = P_id_sucursal) ,
							estado = (SELECT (CASE flag_autorizado WHEN 0 THEN 'Z' WHEN 1 THEN 'S' END) FROM sucursal WHERE id_sucursal = P_id_sucursal) , 
							observacion = P_observacion 
		WHERE 	id_pedido = MAX_ID AND id_usuario = P_id_usuario AND estado = 'T';
		-- EN PERFIL SOLO PARA CLIENTES O SUCURSALES ACTUALIZA SALDO CONTABLE
		IF((SELECT COUNT(id_usuario) FROM usuario WHERE id_usuario = P_id_usuario AND P_id_tipo_usuario = 2)=1) 
		THEN 
		-- 	SELECT 'ADMIN';
			IF((SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = P_id_sucursal)>0) 
			THEN 
-- 				SELECT 'CON MONTO PROPIO!', P_id_sucursal;
				UPDATE 	sucursal_categoria SET monto_acumulado = P_saldo_sucursal_admin
				WHERE 	id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID 
				AND 	id_sucursal = P_id_sucursal
				AND 	id_categoria = P_id_categoria -- NUEVO
				;
			ELSE 
	-- 		SELECT 'CON MONTO DEL CORPORATIVO!', P_id_sucursal;
				UPDATE 	sucursal_categoria SET monto_acumulado = P_saldo_contable_admin
				WHERE 	id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID
				AND 	id_sucursal NOT IN (SELECT id_sucursal FROM sucursal_categoria_monto)
				AND 	id_categoria = P_id_categoria -- NUEVO
				;
			END IF;
		ELSE
			IF((SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = SUCURSAL_ID)>0) 
			-- ES SUCURSAL Y MANEJA UN MONTO PROPIO
			THEN 
				UPDATE 	sucursal_categoria SET monto_acumulado = P_saldo_sucursal
				WHERE 	id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID 
				AND 	id_sucursal = SUCURSAL_ID
				AND 	id_categoria = P_id_categoria -- NUEVO
				;
			ELSE -- ES SUCURSAL PERO SE GUIA DE MONTO DEL CORPORATIVO
				UPDATE 	sucursal_categoria SET monto_acumulado = P_saldo_contable
				WHERE 	id_corporativo_categoria = CORPORATIVO_CATEGORIA_ID
				-- AND 	id_sucursal NOT IN (SELECT id_sucursal FROM sucursal_categoria_monto)
				AND 	id_sucursal = SUCURSAL_ID
				AND 	id_categoria = P_id_categoria -- NUEVO
				;
			END IF;
		END IF;
	END IF;
	 SELECT 		P_ERROR AS Mensaje;
END$$