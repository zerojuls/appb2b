DELIMITER $$

DROP PROCEDURE IF EXISTS PEDIDO_DETALLE_GET$$

CREATE PROCEDURE `PEDIDO_DETALLE_GET`(
 IN P_id_usuario INT,
 IN P_alias_tipo_usuario VARCHAR(20)
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


	CASE P_alias_tipo_usuario
		-- TIPO USUARIO ADMIN
		-- CUANDO SE DESEEN SABER TODAS LAS SUCURSALES, INCLUYENDO LAS PRINCIPALES
	WHEN 'admin' THEN 
		SELECT 		PD.id_pedido AS ID_PEDIDO, PD.id_pedido_detalle AS ID_PEDIDO_DETALLE, 
					CONCAT(0,0,0,0,0,0,UV.id_tipo_precio,UV.id_producto,UV.id_envase,UV.id_unidad) AS CODIGO_PEDIDO, 
					PR.id_producto AS ID_PRODUCTO, PR.codigo AS CODIGO_PRODUCTO, P.fecha_registro AS FECHA, PR.nombre AS NOMBRE_PRODUCTO, 
					PD.cantidad AS CANTIDAD, PD.precio AS PRECIO, UN.nombre AS UNIDAD, (PD.precio*PD.cantidad) AS PRECIO_COMPRA
		FROM  	   	sucursal_usuario SU 
		INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
		INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
		INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
		INNER JOIN corporativo CO ON P.id_cliente = CO.id_cliente
		INNER JOIN 	pedido_detalle PD ON P.id_pedido = PD.id_pedido
		INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto 
					AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
		INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
		INNER JOIN 	unidad UN ON PD.id_unidad=UN.id_unidad
		WHERE  		SU.id_sucursal = SUCURSAL_ID
					AND P.estado = 'T'
		ORDER BY	codigo_producto;


	WHEN 'cliente' THEN
		IF((SELECT COUNT(id_sucursal) FROM sucursal_categoria_monto WHERE id_sucursal = SUCURSAL_ID)>0) -- ES SUCURSAL Y MANEJA UN MONTO PROPIO
		THEN
			SELECT 		PD.id_pedido AS ID_PEDIDO, PD.id_pedido_detalle AS ID_PEDIDO_DETALLE, 
						CONCAT(0,0,0,0,0,0,UV.id_tipo_precio,UV.id_producto,UV.id_envase,UV.id_unidad) AS CODIGO_PEDIDO, 
						PR.id_producto AS ID_PRODUCTO, PR.codigo AS CODIGO_PRODUCTO, P.fecha_registro AS FECHA, PR.nombre AS NOMBRE_PRODUCTO, 
						PD.cantidad AS CANTIDAD, PD.precio AS PRECIO, UN.nombre AS UNIDAD, (PD.precio*PD.cantidad) AS PRECIO_COMPRA
			FROM  	   	sucursal_usuario SU 
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
			INNER JOIN 	pedido_detalle PD ON P.id_pedido = PD.id_pedido
			INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto 
						AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
			INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
			INNER JOIN 	unidad UN ON PD.id_unidad=UN.id_unidad
			WHERE 		SU.id_sucursal = SUCURSAL_ID
						AND P.estado = 'T'
			ORDER BY	codigo_producto;
		ELSE -- ES SUCURSAL PERO SE GUIA DE MONTO DEL CORPORATIVO
			SELECT 		PD.id_pedido AS ID_PEDIDO, PD.id_pedido_detalle AS ID_PEDIDO_DETALLE, 
					CONCAT(0,0,0,0,0,0,UV.id_tipo_precio,UV.id_producto,UV.id_envase,UV.id_unidad) AS CODIGO_PEDIDO, 
					PR.id_producto AS ID_PRODUCTO, PR.codigo AS CODIGO_PRODUCTO, P.fecha_registro AS FECHA, PR.nombre AS NOMBRE_PRODUCTO, 
					PD.cantidad AS CANTIDAD, PD.precio AS PRECIO, UN.nombre AS UNIDAD, (PD.precio*PD.cantidad) AS PRECIO_COMPRA
			FROM  	   	sucursal_usuario SU 
			INNER JOIN 	usuario U ON SU.id_usuario = U.id_usuario
			INNER JOIN 	sucursal S ON SU.id_sucursal = S.id_sucursal
			INNER JOIN 	pedido P ON SU.id_usuario = P.id_usuario
			INNER JOIN 	pedido_detalle PD ON P.id_pedido = PD.id_pedido
			INNER JOIN	unidad_venta UV ON PD.id_tipo_precio = UV.id_tipo_precio AND PD.id_producto=UV.id_producto 
						AND PD.id_envase=UV.id_envase AND PD.id_unidad=UV.id_unidad
			INNER JOIN 	producto PR ON PD.id_producto=PR.id_producto
			INNER JOIN 	unidad UN ON PD.id_unidad=UN.id_unidad
			WHERE 		SU.id_sucursal = SUCURSAL_ID
						AND P.estado = 'T'
			ORDER BY	codigo_producto;
			END IF;
	ELSE SELECT P_ERROR AS Mensaje;
	END CASE;
END$$