DELIMITER $$

DROP PROCEDURE IF EXISTS HISTORICO_MAIN_GET$$

CREATE PROCEDURE `HISTORICO_MAIN_GET`(
 IN P_id_usuario INT,
 IN P_id_tipo_usuario VARCHAR(20)
 )
BEGIN

SELECT ROUND(IFNULL(((SUM(detalle_pedido.cantidad*detalle_pedido.precio)/(CASE P_id_tipo_usuario WHEN 'admin' THEN 120 WHEN 'super' THEN 100 WHEN 'cliente' THEN 35 END))),0),1) AS valor, 
ROUND((COUNT(cabecera_pedido.id)/(CASE P_id_tipo_usuario WHEN 'admin' THEN 12 WHEN 'super' THEN 10 WHEN 'cliente' THEN 3.5 END)),1) AS valor2,
CONCAT(
CASE
DATE_FORMAT(cabecera_pedido.fecha,'%m')
WHEN '01' THEN 'Enero' 
WHEN '02' THEN 'Febrero' 
WHEN '03' THEN 'Marzo' 
WHEN '04' THEN 'Abril' 
WHEN '05' THEN 'Mayo' 
WHEN '06' THEN 'Junio' 
WHEN '07' THEN 'Julio' 
WHEN '08' THEN 'Agosto' 
WHEN '09' THEN 'Setiembre' 
WHEN '10' THEN 'Octubre' 
WHEN '11' THEN 'Noviembre' 
WHEN '12' THEN 'Diciembre' 
END
,DATE_FORMAT(cabecera_pedido.fecha,' del %Y') ) AS fecha,
FORMAT((IFNULL(SUM(detalle_pedido.cantidad*detalle_pedido.precio),0)),2) AS total,
FORMAT(COUNT(cabecera_pedido.id),0) AS cantidad
FROM globalstore.cabecera_pedido
INNER JOIN globalstore.detalle_pedido ON cabecera_pedido.id = detalle_pedido.id_cabecera
WHERE cabecera_pedido.fecha < CURDATE()
AND cabecera_pedido.fecha > DATE_SUB(CURDATE(),INTERVAL 19 MONTH)
-- AND cabecera_pedido.fecha > '2012-03-01'
AND cabecera_pedido.id_cliente IN 	(
									 SELECT sucursal.id_cliente 
									 FROM globalstore_b2b.sucursal_usuario 
									 INNER JOIN globalstore_b2b.sucursal ON sucursal_usuario.id_sucursal = sucursal.id_sucursal WHERE 
									 sucursal_usuario.id_usuario = P_id_usuario
									)
GROUP BY ( DATE_FORMAT(cabecera_pedido.fecha,'%y-%m'))
ORDER BY ( DATE_FORMAT(cabecera_pedido.fecha,'%y-%m')) DESC
;

END$$