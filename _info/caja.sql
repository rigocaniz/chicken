DELIMITER $$

CREATE PROCEDURE consultaCaja( _action VARCHAR(15), _idCaja INT, _idEstadoCaja INT, _efectivoInicial DOUBLE(12,2), _efectivoFinal DOUBLE(12,2), _efectivoSobrante DOUBLE(10,2), _efectivoFaltante DOUBLE(10,2) )
BEGIN
    DECLARE _cajaAbierta BOOLEAN DEFAULT FALSE;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
        SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

    ELSEIF _action = 'insert' THEN
        # SI TIENE UNA APERTURA PENDIENTE
        SELECT TRUE INTO _cajaAbierta FROM caja WHERE usuario = @usuario AND idEstadoCaja = 1;
        
        IF _cajaAbierta THEN
            SELECT 'danger' AS 'respuesta', 'Ya tiene aperturada su caja' AS 'mensaje';
        
        ELSE
            INSERT INTO caja
                ( usuario, idEstadoCaja, fechaApertura, efectivoInicial, efectivoFinal, efectivoSobrante, efectivoFaltante ) 
            VALUES
                ( @usuario, 1, CURDATE(), _efectivoInicial, _efectivoFinal, _efectivoSobrante, _efectivoFaltante );
                
            SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;
    
    ELSEIF _action = 'cierre' THEN
        UPDATE caja SET
            idEstadoCaja        = _idEstadoCaja,
            efectivoInicial     = _efectivoInicial,
            efectivoFinal       = _efectivoFinal,
            efectivoSobrante    = _efectivoSobrante,
            efectivoFaltante    = _efectivoFaltante
        WHERE idCaja = _idCaja;
    
        SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    
    ELSE
        SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE consultaDenominacionCaja( _action VARCHAR(15), _idCaja INT, _idEstadoCaja INT, _denominacion DOUBLE(5,2), _cantidad INT )
BEGIN
    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya se registro la denominación para este estado' AS 'mensaje';
        
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    IF _action = 'insert' THEN
        INSERT INTO denominacionCaja( idCaja, idEstadoCaja, denominacion, cantidad ) 
            VALUES ( _idCaja, _idEstadoCaja, _denominacion, _cantidad );
            
        SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
    ELSE
        SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE consultaReajusteCaja( _action VARCHAR(15), _idReajusteCaja INT, _idCaja INT, _monto DOUBLE(10,2), _observacion TEXT )
BEGIN
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
        SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

    ELSEIF _action = 'insert' THEN
    
        INSERT INTO reajusteCaja
            ( idCaja, monto, observacion, usuario, fechaRegistro ) 
        VALUES
            ( _idCaja, _monto, _observacion, @usuario, NOW() );
            
        SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
    
    ELSEIF _action = 'delete' THEN
    
        DELETE FROM reajusteCaja WHERE idReajusteCaja = _idReajusteCaja;
            
        SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
        SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE consultaMovimiento( _action VARCHAR(15), _idMovimiento INT, _idTipoMovimiento INT, 
    _idEstadoMovimiento INT, _idFormaPago INT, _idEvento INT, _motivo VARCHAR(60), _monto DOUBLE(10,2), _comentario TEXT )
BEGIN
    DECLARE _idCaja INT;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
    SET @comentario = _comentario;
    
    # SI TIENE UNA APERTURA PENDIENTE
    SELECT idCaja INTO _idCaja FROM caja WHERE usuario = @usuario AND idEstadoCaja = 1 ORDER BY idCaja DESC LIMIT 1;
    
    # SI LA SESION ES INVALIDA
    IF !sesionValida() THEN
        SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
    
    # SI LA CAJA NO ESTA HABILITADA
    ELSEIF ISNULL( _idCaja ) THEN
        SELECT 'danger' AS 'respuesta', 'No tiene ningúna Caja Abierta' AS 'mensaje';

    ELSEIF _action = 'insert' THEN
    
        INSERT INTO movimiento ( idCaja, idTipoMovimiento, idEstadoMovimiento, idFormaPago, idEvento, motivo, monto ) 
        VALUES ( _idCaja, _idTipoMovimiento, _idEstadoMovimiento, _idFormaPago, _idEvento, _motivo, _monto );
            
        SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
     
    ELSEIF _action = 'delete' AND @isAdmin THEN
    
        DELETE FROM movimiento WHERE idMovimiento = _idMovimiento;
            
        SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
        SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE consultaCuadre( _idCaja INT, _idFormaPago INT )
BEGIN
    DECLARE _montoDespacho DOUBLE(10,2) DEFAULT 0;
    DECLARE _ingresos DOUBLE(10,2) DEFAULT 0;
    DECLARE _egresos DOUBLE(10,2) DEFAULT 0;
    
    SELECT  SUM( ffp.monto )AS 'montoDespacho'
        INTO _montoDespacho
    FROM caja AS c
        JOIN factura AS f               ON c.idCaja = f.idCaja
        JOIN facturaFormaPago AS ffp    ON f.idFactura = ffp.idFactura
    WHERE c.idCaja = _idCaja AND IF( !ISNULL( _idFormaPago ), ffp.idFormaPago = _idFormaPago, TRUE );

    SELECT SUM( IF( tm.ingreso, m.monto, 0 ) )AS 'ingresos', SUM( IF( !tm.ingreso, m.monto, 0 ) )AS 'egresos'
        INTO _ingresos, _egresos
    FROM caja AS c
        JOIN movimiento AS m        ON c.idCaja = m.idCaja
        JOIN tipoMovimiento AS tm   ON m.idTipoMovimiento = tm.idTipoMovimiento
    WHERE c.idCaja = _idCaja AND IF( !ISNULL( _idFormaPago ), m.idFormaPago = _idFormaPago, TRUE ) ;
    
    SELECT _montoDespacho AS 'montoDespacho', _ingresos AS 'ingresos', _egresos AS 'egresos';
END$$



CREATE VIEW `vDenominacionCaja` AS
SELECT 
    idCaja,
    idEstadoCaja,
    denominacion,
    cantidad,
    ( denominacion * cantidad )AS 'monto'
FROM denominacionCaja


CREATE VIEW vstCaja AS
SELECT 
    c.idCaja,
    c.usuario,
    c.fechaApertura,
    c.efectivoInicial,
    c.efectivoFinal,
    c.efectivoSobrante,
    c.efectivoFaltante,
    ec.idEstadoCaja,
    ec.estadoCaja,
    u.nombres,
    u.apellidos,
    u.codigo
FROM caja AS c
    JOIN estadoCaja AS ec
        ON c.idEstadoCaja = ec.idEstadoCaja
    JOIN vUsuario AS u
        ON c.usuario = u.usuario
