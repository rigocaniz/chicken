
/*
_idCaja # ID DE CAJA GENERADO DE consultaCaja()
_idEstadoCaja # Si es Apertura o Cierre ( 1 || 2 )
_denominacion # Denominación de la Moneda
_cantidad # La cantidad ingresada
*/

CALL consultaDenominacionCaja( 'insert', _idCaja INT, _idEstadoCaja INT, _denominacion DOUBLE(5,2), _cantidad INT );


CREATE PROCEDURE consultaDenominacionCaja( _action VARCHAR(15), _idCaja INT, _idEstadoCaja INT, _denominacion DOUBLE(5,2), _cantidad INT )
BEGIN
    
    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya se registro la denominación para este estado' AS 'mensaje';
        
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
    IF _action = 'insert' THEN
		INSERT INTO caja( idCaja, idEstadoCaja, denominacion, cantidad ) 
			VALUES ( _idCaja, _idEstadoCaja, _denominacion, _cantidad );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end



CREATE VIEW `vDenominacionCaja` AS
SELECT 
	idCaja,
    idEstadoCaja,
    denominacion,
    cantidad,
    ( denominacion * cantidad )AS 'monto'
FROM denominacionCaja