
DELIMITER $$

DROP PROCEDURE IF EXISTS MONTOS_ASIGNADOS_GET$$

CREATE PROCEDURE `MONTOS_ASIGNADOS_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario INT
)
BEGIN
	IF((SELECT COUNT(id_sucursal) 
		FROM sucursal_categoria_monto 
		WHERE id_sucursal = ( 	SELECT S.id_sucursal 
								FROM  cliente_usuario CU 
								INNER JOIN sucursal S ON CU.id_cliente = S.id_cliente 
								WHERE id_usuario = P_id_usuario	)) > 0)
	THEN
		SELECT 		C.nombre AS sucursal, 
					-- CA.nombre AS categoria,
					(	CASE CA.id_categoria WHEN 1 THEN CONCAT("<i class='icon-pencil '></i> ",CA.nombre) WHEN 2 THEN CONCAT("<i class='icon-file '></i> ",CA.nombre) WHEN 3 THEN CONCAT("<i class='icon-hdd '></i> ",CA.nombre) WHEN 4 THEN CONCAT("<i class='icon-trash '></i> ",CA.nombre) WHEN 5 THEN CONCAT("<i class='icon-wrench '></i> ",CA.nombre) WHEN 6 THEN CONCAT("<i class='icon-gift '></i> ",CA.nombre) WHEN 7 THEN CONCAT("<i class='icon-leaf '></i> ",CA.nombre) ELSE CONCAT("<i class='icon-star '></i> ",CA.nombre) END)AS categoria,
					SCM.valor AS monto_asignado, 
					SC.monto_acumulado AS monto_acumulado, SCM.valor - SC.monto_acumulado AS monto_disponible
					, CU.id_usuario, S.id_sucursal
		FROM 		sucursal_categoria SC
		INNER JOIN 	categoria CA ON SC.id_categoria = CA.id_categoria 
		INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
		INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
		INNER JOIN 	cliente_usuario CU ON C.id_cliente = CU.id_cliente
		INNER JOIN 	sucursal_categoria_monto SCM ON SC.id_sucursal = SCM.id_sucursal AND CA.id_categoria = SCM.id_categoria
		INNER JOIN 	monto M ON SCM.id_monto = M.id_monto 
		WHERE  		CU.id_usuario = P_id_usuario AND M.id_monto = 1
		;
	ELSE
		SELECT 		C.nombre AS sucursal, 
					(	CASE CA.id_categoria	
						WHEN 1 THEN CONCAT("<i class='icon-pencil '></i> ",CA.nombre) 
						WHEN 2 THEN CONCAT("<i class='icon-file '></i> ",CA.nombre) 
						WHEN 3 THEN CONCAT("<i class='icon-hdd '></i> ",CA.nombre) 
						WHEN 4 THEN CONCAT("<i class='icon-trash '></i> ",CA.nombre) 
						WHEN 5 THEN CONCAT("<i class='icon-wrench '></i> ",CA.nombre) 
						WHEN 6 THEN CONCAT("<i class='icon-gift '></i> ",CA.nombre) 
						WHEN 7 THEN CONCAT("<i class='icon-leaf '></i> ",CA.nombre) 
						ELSE CONCAT("<i class='icon-star '></i> ",CA.nombre) 
					END)AS categoria 
					,CCM.valor AS monto_asignado, 
					SC.monto_acumulado AS monto_acumulado, CCM.valor - SC.monto_acumulado AS monto_disponible
					, CU.id_usuario, S.id_sucursal
		FROM 		corporativo_categoria CC 
		INNER JOIN 	categoria CA ON CC.id_categoria = CA.id_categoria 
		INNER JOIN 	sucursal_categoria SC ON CC.id_corporativo_categoria = SC.id_corporativo_categoria AND CC.id_categoria = SC.id_categoria 
		INNER JOIN 	sucursal S ON SC.id_sucursal = S.id_sucursal
		INNER JOIN 	cliente C ON S.id_cliente = C.id_cliente
		INNER JOIN 	cliente_usuario CU ON C.id_cliente = CU.id_cliente
		INNER JOIN 	corporativo_categoria_monto CCM ON CC.id_corporativo_categoria = CCM.id_corporativo_categoria 
		INNER JOIN 	monto M ON CCM.id_monto = M.id_monto 
		WHERE  
		CU.id_usuario = P_id_usuario AND M.id_monto = 1
		-- SU.id_usuario = 2 AND 
		;
	END IF;
 END$$
