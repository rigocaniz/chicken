
CREATE PROCEDURE consultaEvento( _action VARCHAR(15), _idEvento INT, _evento VARCHAR(75), _idCliente INT, _fechaEvento DATE, _idSalon INT, _idEstadoEvento INT, _numeroPersonas INT, _horaInicio TIME, _horaFinal TIME, _observacion TEXT, _comentario TEXT )
COMMENT 'INSERTA / ACTUALIZA EVENTO'
BEGIN

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    SET @comentario = _comentario;
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ELSEIF _action = 'insert' THEN
		INSERT INTO evento ( evento, idCliente, fechaEvento, idSalon, idEstadoEvento, numeroPersonas, horaInicio, horaFinal, observacion, usuario, fechaRegistro ) 
			VALUES ( _evento, _idCliente, _fechaEvento, _idSalon, _idEstadoEvento, _numeroPersonas, _horaInicio, _horaFinal, _observacion, @usuario, now() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE evento SET
			evento         = _evento,
			idCliente      = _idCliente,
			fechaEvento    = _fechaEvento,
			idSalon        = _idSalon,
			idEstadoEvento = _idEstadoEvento, 
			numeroPersonas = _numeroPersonas,
			horaInicio     = _horaInicio,
			horaFinal      = _horaFinal,
			observacion    = _observacion
		WHERE idEvento = _idEvento;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaOrdenEvento( _action VARCHAR(15), _idEvento INT, _idOrdenCliente INT )
COMMENT 'INSERTAR / ELIMINAR ORDEN CLIENTE A EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Error, orden ya pertence a este evento' AS 'mensaje';
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoOrdenCliente ( idEvento, idOrdenCliente, usuario, fechaRegistro ) 
			VALUES ( _idEvento, _idOrdenCliente, @usuario, NOW() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
    ELSEIF _action = 'delete' THEN
		DELETE FROM eventoOrdenCliente WHERE idEvento = _idEvento AND idOrdenCliente = _idOrdenCliente;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaMenuEvento( _action VARCHAR(15), _idEventoMenu INT, _idEvento INT, _idMenu INT, _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR MENU DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoMenu( idEvento, idMenu, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _idMenu, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE eventoMenu SET
			idMenu         = _idMenu,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idEventoMenu = _idEventoMenu;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM eventoMenu WHERE idEventoMenu = _idEventoMenu;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida o no tiene acceso' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaComboEvento( _action VARCHAR(15), _idEventoCombo INT, _idEvento INT, _idCombo INT, _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR COMBO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoCombo( idEvento, idCombo, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _idCombo, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE eventoCombo SET
			idCombo        = _idCombo,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idEventoCombo = _idEventoCombo;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM eventoCombo WHERE idEventoCombo = _idEventoCombo;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaOtroMenuEvento( _action VARCHAR(15), _idOtroMenu INT, _idEvento INT, _otroMenu VARCHAR(45), _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR OTRO COMBO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO otroMenu( idEvento, otroMenu, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _otroMenu, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE otroMenu SET
			otroMenu       = _otroMenu,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idOtroMenu = _idOtroMenu;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM otroMenu WHERE idOtroMenu = _idOtroMenu;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaOtroServicio( _action VARCHAR(15), _idOtroServicio INT, _idEvento INT, _otroServicio VARCHAR(45), _cantidad INT, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR OTRO SERVICIO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO otroServicio( idEvento, otroServicio, cantidad, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _otroServicio, _cantidad, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE otroServicio SET
			otroServicio   = _otroServicio,
			cantidad       = _cantidad,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idOtroServicio = _idOtroServicio;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM otroServicio WHERE idOtroServicio = _idOtroServicio;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$



CREATE PROCEDURE consultaMovimiento( _action VARCHAR(15), _idMovimiento INT, _idCaja INT, _idTipoMovimiento INT, _idEstadoMovimiento INT, _idFormaPago INT, _idEvento INT, _motivo VARCHAR(60), _monto DOUBLE( 10,2 ), _comentario TEXT )
COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR OTRO SERVICIO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
	SET @comentario = _comentario;

    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO movimiento( idCaja, idTipoMovimiento, idEstadoMovimiento, idFormaPago, idEvento, motivo, monto ) 
			VALUES ( _idCaja, _idTipoMovimiento, _idEstadoMovimiento, _idFormaPago, _idEvento, _motivo, _monto );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'status' THEN
		UPDATE movimiento SET idEstadoMovimiento = _idEstadoMovimiento
			WHERE idMovimiento = _idMovimiento;
		
		SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
	
    ELSE

		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$



CREATE PROCEDURE consultaDetalleOrdenEvento( _idEvento INT )
COMMENT 'CONSULTAR DETALLE ORDEN'
BEGIN
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSE
		
		# DETALLE DE MENU
		(SELECT
			em.idEventoMenu AS 'id',
			em.cantidad,
			em.horaDespacho,
			em.precioUnitario,
			( em.cantidad * em.precioUnitario )AS 'subTotal',
			em.fechaRegistro,
			em.comentario,
			m.idMenu AS 'idMenu',
			m.menu AS 'menu',
			m.imagen,
			'menu' AS 'idTipo',
			'Menú' AS 'tipo'
		FROM eventoMenu AS em
			JOIN lstMenu AS m
				ON em.idMenu = m.idMenu
		WHERE em.idEvento = _idEvento)
			UNION ALL
		# DETALLE DE COMBO
		(SELECT
			ec.idEventoCombo AS 'id',
			ec.cantidad,
			ec.horaDespacho,
			ec.precioUnitario,
			( ec.cantidad * ec.precioUnitario )AS 'subTotal',
			ec.fechaRegistro,
			ec.comentario,
			c.idCombo AS 'idMenu',
			c.combo AS 'menu',
			c.imagen,
			'combo' AS 'idTipo',
			'Combo' AS 'tipo'
		FROM eventoCombo AS ec
			JOIN lstCombo AS c
				ON ec.idCombo = c.idCombo
		WHERE ec.idEvento = _idEvento)
			UNION ALL
		# DETALLE DE MENU PERSONALIZADO
		(SELECT
			idOtroMenu AS 'id',
			cantidad,
			horaDespacho,
			precioUnitario,
			( cantidad * precioUnitario )AS 'subTotal',
			fechaRegistro,
			comentario,
			NULL AS 'idMenu',
			otroMenu AS 'menu',
			'' AS 'imagen',
			'otroMenu' AS 'idTipo',
			'Otro Menú' AS 'tipo'
		FROM otroMenu
		WHERE idEvento = _idEvento)
        ORDER BY fechaRegistro ASC;
	END IF;
END$$




CREATE OR REPLACE VIEW vEvento AS
SELECT
	e.idEvento,
    e.evento,
    e.fechaEvento,
    s.idSalon,
    s.salon,
    e.horaInicio,
    e.horaFinal,
    e.observacion,
    e.usuario,
    e.fechaRegistro,
    e.numeroPersonas,
    ee.idEstadoEvento,
    ee.estadoEvento,
    c.idCliente,
    nit,
    nombre,
    cui,
    correo,
    telefono,
    direccion,
    idTipoCliente,
    tipoCliente
FROM evento AS e
	JOIN vstCliente AS c
		ON e.idCliente = c.idCliente
        
	JOIN estadoEvento AS ee
		ON e.idEstadoEvento = ee.idEstadoEvento
	
    JOIN salon AS s
		ON s.idSalon = e.idSalon
;


CREATE OR REPLACE VIEW vOtroServicio AS
SELECT
	idOtroServicio,
	idEvento,
	otroServicio,
	cantidad,
	precioUnitario,
	fechaRegistro,
	usuario
FROM otroServicio
;



