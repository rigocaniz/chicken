DELIMITER $$

CREATE PROCEDURE consultaCliente( _action VARCHAR(15), _idCliente INT, _nit VARCHAR(15), _nombre VARCHAR(65), _cui VARCHAR(13), 
	_correo VARCHAR(65), _telefono VARCHAR(8),  _direccion VARCHAR(95), _idTipoCliente INT )
BEGIN
	DECLARE _existeCliente BOOLEAN DEFAULT FALSE;
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		SELECT TRUE INTO _existeCliente FROM cliente WHERE nit = _nit AND LENGTH( nit ) > 2 LIMIT 1;
        
        IF _existeCliente THEN
			SELECT 'danger' AS 'respuesta', 'Error, NIT duplicado' AS 'mensaje';
		
        ELSE
			INSERT INTO cliente ( nit, nombre, cui, correo, telefono, direccion, idTipoCliente, fechaRegistro, usuario ) 
				VALUES ( _nit, _nombre, _cui, _correo, _telefono, _direccion, _idTipoCliente, now(), @usuario );
                
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', last_insert_id() AS 'id';
        END IF;
    ELSEIF _action = 'update' THEN
		SELECT TRUE INTO _existeCliente FROM cliente 
			WHERE idCliente != _idCliente AND nit = _nit AND LENGTH( nit ) > 2 LIMIT 1;
        
		IF _existeCliente THEN
			SELECT 'danger' AS 'respuesta', 'Error, NIT ya pertenece a otro cliente' AS 'mensaje';
		
        ELSEIF _idCliente = 1 THEN
			SELECT 'danger' AS 'respuesta', 'No es posible modificar este cliente' AS 'mensaje';
            
        ELSE
			UPDATE cliente SET
				nit 		= _nit, 
				nombre 		= _nombre, 
				cui 		= _cui, 
				correo 		= _correo, 
				telefono 	= _telefono,
				direccion 	= _direccion,
				idTipoCliente = _idTipoCliente
			WHERE idCliente = _idCliente;
			
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$




CREATE VIEW vstCliente AS
SELECT idCliente, nit, nombre, cui, correo, telefono, direccion, c.idTipoCliente, tipoCliente
FROM cliente AS c
	JOIN tipoCliente AS tc ON tc.idTipoCliente = c.idTipoCliente

