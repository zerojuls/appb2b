
DELIMITER $$

DROP PROCEDURE IF EXISTS CATEGORIA_GET$$

CREATE PROCEDURE `CATEGORIA_GET`(
 IN P_id_categoria CHAR(10)
)
BEGIN

	SELECT 	id_categoria, nombre 
	FROM 	categoria
	WHERE 	estado='A'
			AND id_categoria = P_id_categoria OR P_id_categoria IS NULL
	ORDER BY id_categoria;

END$$



