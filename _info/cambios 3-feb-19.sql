-- MySQL Workbench Synchronization
-- Generated: 2019-02-03 14:59
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Rigo Caniz

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `dbchurchill`.`factura` 
ADD COLUMN `idOrdenCliente` INT(11) NULL DEFAULT NULL AFTER `descripcion`,
ADD INDEX `fk_factura_ordenCliente1_idx` (`idOrdenCliente` ASC);

ALTER TABLE `dbchurchill`.`factura` 
ADD CONSTRAINT `fk_factura_ordenCliente1`
  FOREIGN KEY (`idOrdenCliente`)
  REFERENCES `dbchurchill`.`ordenCliente` (`idOrdenCliente`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


DELIMITER $$

USE `dbchurchill`$$
CREATE DEFINER = CURRENT_USER TRIGGER `dbChurchill`.`detalleOrdenFactura_AFTER_INSERT` AFTER INSERT ON `detalleOrdenFactura` FOR EACH ROW
BEGIN
	DECLARE _idOrdenCliente INT DEFAULT 0;
	
    IF IFNULL( NEW.idDetalleOrdenMenu, 0 ) > 0 THEN
		SELECT idOrdenCliente INTO _idOrdenCliente
        FROM detalleOrdenMenu WHERE idDetalleOrdenMenu = NEW.idDetalleOrdenMenu;
	
    ELSEIF IFNULL( NEW.idDetalleOrdenCombo, 0 ) > 0 THEN
		SELECT idOrdenCliente INTO _idOrdenCliente
        FROM detalleOrdenCombo WHERE idDetalleOrdenCombo = NEW.idDetalleOrdenCombo;
	
    ELSEIF IFNULL( NEW.idMenuPersonalizado, 0 ) > 0 THEN
		SELECT idOrdenCliente INTO _idOrdenCliente
        FROM menuPersonalizado WHERE idMenuPersonalizado = NEW.idMenuPersonalizado;
        
    END IF;
    
    # ACTUALIZA idOrdenCliente DE FACTURA
    IF _idOrdenCliente > 0 THEN
		UPDATE factura SET
			idOrdenCliente = _idOrdenCliente
		WHERE idFactura = NEW.idFactura;
        
    END IF;
END$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

/* ==== ACTUALIZA ORDENES VIGENTES ====
*/
#SET @usuario = 'restaurante'; !!!!!!!!!!!!!!!!!REVISAR
UPDATE factura AS f
	JOIN
	(SELECT
		gfac.idFactura,
		IFNULL( dom.idOrdenCliente, IFNULL( doc.idOrdenCliente, mp.idOrdenCliente ) )AS 'idOrdenCliente'
	FROM
		(SELECT
			dof.idDetalleOrdenMenu, dof.idDetalleOrdenCombo, dof.idMenuPersonalizado, dof.idFactura
		FROM detalleOrdenFactura AS dof
		GROUP BY dof.idFactura
		)gfac
		
		LEFT JOIN detalleOrdenMenu AS dom
			ON dom.idDetalleOrdenMenu = gfac.idDetalleOrdenMenu
		
		LEFT JOIN detalleOrdenCombo AS doc
			ON doc.idDetalleOrdenCombo = gfac.idDetalleOrdenCombo
		
		LEFT JOIN menuPersonalizado AS mp
			ON mp.idMenuPersonalizado = gfac.idMenuPersonalizado
	)idOr
		ON f.idFactura = idOr.idFactura

	# ACTUALIZAR ID ORDEN CLIENTE
	SET f.idOrdenCliente = idOr.idOrdenCliente
;




