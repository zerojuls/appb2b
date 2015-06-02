DELIMITER $$

DROP PROCEDURE IF EXISTS MAIN_GENERALES_GET$$

CREATE PROCEDURE `MAIN_GENERALES_GET`(
 IN P_id_usuario INT,
  IN P_id_tipo_usuario INT
 )
BEGIN
SELECT 
(
	SELECT COUNT(*) FROM pedido P
	INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
	INNER JOIN producto PR ON PD.id_producto = PR.id_producto
	--
	INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
	--
	INNER JOIN subcategoria SCA ON SCP.id_subcategoria = SCA.id_subcategoria
	--
	INNER JOIN categoria CA ON SCA.id_categoria = CA.id_categoria
	WHERE P.id_usuario IN (SELECT CU2.id_usuario FROM sucursal_usuario SU2 
INNER JOIN sucursal  S2 ON SU2.id_sucursal = S2.id_sucursal
INNER JOIN cliente_usuario CU2 ON S2.id_cliente = CU2.id_cliente
WHERE SU2.id_usuario = 2) AND CA.id_categoria = 1
) AS pedidos_categoria_utiles, -- NUMERO DE PEDIDOS POR CATEGORIA UTILES
(
	SELECT COUNT(*) FROM pedido P
	INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
	INNER JOIN producto PR ON PD.id_producto = PR.id_producto
	INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
	--
	INNER JOIN subcategoria SCA ON SCP.id_subcategoria = SCA.id_subcategoria
	--
	INNER JOIN categoria CA ON SCA.id_categoria = CA.id_categoria
	WHERE P.id_usuario IN (SELECT CU2.id_usuario FROM sucursal_usuario SU2 
INNER JOIN sucursal  S2 ON SU2.id_sucursal = S2.id_sucursal
INNER JOIN cliente_usuario CU2 ON S2.id_cliente = CU2.id_cliente
WHERE SU2.id_usuario = P_id_usuario) AND CA.id_categoria = 4
) AS pedidos_categoria_limpieza, -- NUMERO DE PEDIDOS POR CATEGORIA LIMPIEZA

(
 IFNULL( (	SELECT valor_venta 
				FROM pedido P
				INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
				INNER JOIN producto PR ON PD.id_producto = PR.id_producto
				INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
				--
				INNER JOIN subcategoria SCA ON SCP.id_subcategoria = SCA.id_subcategoria
				--
				INNER JOIN categoria CA ON SCA.id_categoria = CA.id_categoria
				WHERE P.id_pedido = (SELECT MAX(id_pedido) FROM pedido WHERE id_usuario IN (SELECT CU2.id_usuario FROM sucursal_usuario SU2 
INNER JOIN sucursal  S2 ON SU2.id_sucursal = S2.id_sucursal
INNER JOIN cliente_usuario CU2 ON S2.id_cliente = CU2.id_cliente
WHERE SU2.id_usuario = P_id_usuario) )  AND CA.id_categoria = 1
				GROUP BY valor_venta
			) , 0) 
 ) AS monto_comprado_utiles,

(
 IFNULL( (	SELECT valor_venta 
				FROM pedido P
				INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
				INNER JOIN producto PR ON PD.id_producto = PR.id_producto
				INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
				--
				INNER JOIN subcategoria SCA ON SCP.id_subcategoria = SCA.id_subcategoria
				--
				INNER JOIN categoria CA ON SCA.id_categoria = CA.id_categoria
				WHERE P.id_pedido = (SELECT MAX(id_pedido) FROM pedido WHERE id_usuario IN (SELECT CU2.id_usuario FROM sucursal_usuario SU2 
INNER JOIN sucursal  S2 ON SU2.id_sucursal = S2.id_sucursal
INNER JOIN cliente_usuario CU2 ON S2.id_cliente = CU2.id_cliente
WHERE SU2.id_usuario = P_id_usuario) )  AND CA.id_categoria = 4
				GROUP BY valor_venta
			) , 0) 
 ) AS monto_comprado_limpieza,

(	
	(
		CASE 
		WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
				INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
		THEN -- SI TIENE MONTO PROPIO
			( SELECT	( SCM.valor - SSC.monto_acumulado  ) 
				FROM		monto M1 
				INNER JOIN sucursal_categoria_monto SCM ON M1.id_monto = SCM.id_monto
				WHERE M1.id_monto = 1 AND SCM.id_categoria = 1
					) 
		ELSE -- SUCURSAL CON MONTO DE CORPORATIVO
			( SELECT	( CCM.valor - SSC.monto_acumulado ) 
				FROM		monto M1 
				INNER JOIN corporativo_categoria_monto CCM ON M1.id_monto = CCM.id_monto
				WHERE M1.id_monto = 1 AND id_corporativo_categoria = 1
					) 
		END 
	)

) AS saldo_disponible_utiles , -- SALDO DISPONIBLE UTILES

(	
	(
		CASE 
		WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
				INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
		THEN -- SI TIENE MONTO PROPIO
			IFNULL(( SELECT	( SCM.valor - SSC.monto_acumulado  ) 
				FROM		monto M1 
				INNER JOIN sucursal_categoria_monto SCM ON M1.id_monto = SCM.id_monto
				WHERE M1.id_monto = 1 AND SCM.id_categoria = 4
					),0) 
		ELSE -- SUCURSAL CON MONTO DE CORPORATIVO
			IFNULL(( SELECT	( CCM.valor - SSC.monto_acumulado ) 
				FROM		monto M1 
				INNER JOIN corporativo_categoria_monto CCM ON M1.id_monto = CCM.id_monto
				WHERE M1.id_monto = 1 AND id_corporativo_categoria = 2
					),0)
		END 
	)

) AS saldo_disponible_limpieza , -- SALDO DISPONIBLE LIMPIEZA

(	
	(
	CASE 
	WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
			INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
	THEN -- SI TIENE MONTO PROPIO
		IFNULL(( SELECT	( CASE WHEN SCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%Y') ELSE (SELECT CONCAT(SCM.valor,'/',DATE_FORMAT(NOW(),'%m'),'/',YEAR(NOW()))) END ) 
			FROM		monto M1 
			INNER JOIN sucursal_categoria_monto SCM ON M1.id_monto = SCM.id_monto
			WHERE M1.id_monto = 4 AND SCM.id_categoria = 1
				) , 'No asignado')
	ELSE -- SUCURSAL CON MONTO DE CORPORATIVO
		IFNULL(( SELECT	( CASE WHEN CCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%Y') ELSE (SELECT CONCAT(CCM.valor,'/',DATE_FORMAT(NOW(),'%m'),'/',YEAR(NOW()))) END ) 
			FROM		monto M1 
			INNER JOIN corporativo_categoria_monto CCM ON M1.id_monto = CCM.id_monto
			WHERE M1.id_monto = 4 AND id_corporativo_categoria = 1
				) , 'No asignado')
	END 
	)
) AS fecha_cierre_utiles , -- FECHA CIERRE DE COMPRA

(	
	(
	CASE 
	WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
			INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
	THEN -- SI TIENE MONTO PROPIO
		IFNULL(( SELECT	( CASE WHEN SCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%Y') ELSE (SELECT CONCAT(SCM.valor,'/',DATE_FORMAT(NOW(),'%m'),'/',YEAR(NOW()))) END ) 
			FROM		monto M1 
			INNER JOIN sucursal_categoria_monto SCM ON M1.id_monto = SCM.id_monto
			WHERE M1.id_monto = 4 AND SCM.id_categoria = 4
				) , 'No asignado')
	ELSE -- SUCURSAL CON MONTO DE CORPORATIVO
		IFNULL(( SELECT	( CASE WHEN CCM.valor = 0  THEN DATE_FORMAT(LAST_DAY(NOW()),'%d/%m/%Y') ELSE (SELECT CONCAT(CCM.valor,'/',DATE_FORMAT(NOW(),'%m'),'/',YEAR(NOW()))) END ) 
			FROM		monto M1 
			INNER JOIN corporativo_categoria_monto CCM ON M1.id_monto = CCM.id_monto
			WHERE M1.id_monto = 4 AND id_corporativo_categoria = 2
				) , 'No asignado')
	END 
	)
) AS fecha_cierre_limpieza , -- FECHA CIERRE DE COMPRA

(	
	(
	CASE 
	WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
			INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
	THEN -- SI TIENE MONTO PROPIO
		( 	SELECT 	SCM.valor  
			FROM 		monto M2 
			INNER JOIN sucursal_categoria_monto SCM ON M2.id_monto = SCM.id_monto
			WHERE M2.id_monto = 3  AND SCM.id_categoria = 1) 
	ELSE -- SUCURSAL CON MONTO DE CORPORATIVO	
		( 	SELECT 	CCM.valor 
			FROM 		monto M2 
			INNER JOIN corporativo_categoria_monto CCM ON M2.id_monto = CCM.id_monto
			WHERE M2.id_monto = 3  AND id_corporativo_categoria = 1) 
	END 
	)
) AS maximo_pedido_utiles, -- PEDIDOS DISPONIBLES

(	
	(
	CASE 
	WHEN((	SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = ( SELECT S.id_sucursal FROM  cliente_usuario CU 
			INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente WHERE id_usuario = P_id_usuario)) >0) 
	THEN -- SI TIENE MONTO PROPIO
		( 	SELECT 	SCM.valor 
			FROM 		monto M2 
			INNER JOIN sucursal_categoria_monto SCM ON M2.id_monto = SCM.id_monto
			WHERE M2.id_monto = 3  AND SCM.id_categoria = 1) 
	ELSE -- SUCURSAL CON MONTO DE CORPORATIVO	
		( 	SELECT 	CCM.valor 
			FROM 		monto M2 
			INNER JOIN corporativo_categoria_monto CCM ON M2.id_monto = CCM.id_monto
			WHERE M2.id_monto = 3  AND id_corporativo_categoria = 1) 
	END 
	)
) AS maximo_pedido_limpieza -- PEDIDOS DISPONIBLES

FROM pedido P
INNER JOIN pedido_detalle PD ON P.id_pedido = PD.id_pedido
INNER JOIN pedido_detalle PD1 ON P.id_pedido = PD1.id_pedido
INNER JOIN pedido_detalle PD2 ON P.id_pedido = PD2.id_pedido

INNER JOIN producto PR ON PD.id_producto = PR.id_producto
--
INNER JOIN subcategoria_producto SCP ON PR.id_producto = SCP.id_producto
--
INNER JOIN subcategoria SC ON SCP.id_subcategoria = SC.id_subcategoria
--
INNER JOIN sucursal_usuario SU ON P.id_usuario = SU.id_usuario
INNER JOIN sucursal S ON SU.id_sucursal = S.id_sucursal
INNER JOIN categoria CA ON SC.id_categoria = CA.id_categoria
INNER JOIN sucursal_categoria SSC ON S.id_sucursal = SSC.id_sucursal AND CA.id_categoria = SSC.id_categoria
INNER JOIN corporativo_categoria CC ON SSC.id_corporativo_categoria = CC.id_corporativo_categoria

WHERE 
 S.id_sucursal IN ( SELECT 	SU1.id_sucursal 
  							FROM sucursal_usuario SU1 
							WHERE SU1.id_usuario = P_id_usuario)
-- AND CA.id_categoria = 1 -- SE AÑADE CATEGORIA
 AND P.fecha_registro > '2014-03-01' AND P.fecha_registro < '2014-03-31' AND P.estado = 'S' 
;
END$$