DELIMITER $$

DROP TRIGGER IF EXISTS UPDATE_ESTADO_PEDIDO$$

CREATE TRIGGER UPDATE_ESTADO_PEDIDO AFTER UPDATE ON pedido

FOR EACH ROW
BEGIN
	IF OLD.estado != NEW.estado THEN
		IF(NEW.estado != 'T') THEN
			INSERT INTO tracking(id_pedido, id_estado, usuario_creacion, fecha_creacion) 
			VALUES  (
					 OLD.id_pedido, 
					 NEW.estado, 
					 (SELECT usuario FROM usuario WHERE id_usuario = OLD.id_usuario), 
					 NOW()
					);
		END IF;
	END IF;

END$$