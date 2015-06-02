DELIMITER $$

DROP FUNCTION IF EXISTS `UCWORDS`$$

CREATE FUNCTION `UCWORDS`(
	origen varchar(1000)
)
RETURNS VARCHAR(1000)
BEGIN

DECLARE i INT DEFAULT 1;
DECLARE cAct, cAnt VARCHAR(1);
DECLARE destino VARCHAR(1000) DEFAULT "";

WHILE i <= CHAR_LENGTH(origen) DO
	SET cAct = LOWER(SUBSTRING(origen, i, 1));
	SET cAnt = CASE WHEN i = 1 THEN ' ' ELSE SUBSTRING(origen, i - 1, 1) END;
	IF 	cAnt IN (' ','&', '"', '_', '?', ';', ':', '!', ',', '-', '/', '(', '.') THEN
	    SET cAct = UPPER(cAct);
	END IF;
	SET destino = CONCAT(destino, cAct);
	SET i = i + 1;
	
END WHILE;

RETURN destino;


END$$

DELIMITER ;