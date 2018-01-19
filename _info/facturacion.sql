DELIMITER $$

CREATE PROCEDURE consultaFacturaCliente( _action VARCHAR(15), _idFactura INT, _idEstadoFactura INT, _idCliente INT, _idCaja INT, _nombre VARCHAR(60), _direccion VARCHAR(75), _total DOUBLE(12,2) )
BEGIN
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO factura
			( idEstadoFactura, idCliente, idCaja, nombre, direccion, total, fechaFactura, fechaRegistro, usuario ) 
		VALUES
			( 1, _idCliente, _idCaja, _nombre, _direccion, _total, CURDATE(), NOW(), @usuario );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	
    ELSEIF _action = 'update' THEN
		UPDATE factura SET
			idCliente = _idCliente,
            nombre 	  = _nombre,
            direccion = _direccion,
            total 	  = _total
		WHERE idFactura = _idFactura;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
        
	ELSEIF _action = 'status' THEN
		UPDATE factura SET idEstadoFactura = _idEstadoFactura
		WHERE idFactura = _idFactura;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaFormaPago( _action VARCHAR(15), _idFactura INT, _idFormaPago INT, _monto DOUBLE(10,2) )
BEGIN
    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Forma de pago duplicado' AS 'mensaje';
    
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO facturaFormaPago
			( idFactura, idFormaPago, monto ) 
		VALUES
			( _idFactura, _idFormaPago, _monto );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'delete' THEN
		DELETE FROM facturaFormaPago WHERE idFactura = _idFactura AND idFormaPago = _idFormaPago;
    
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE consultaDetalleFactura( _action VARCHAR(15), _idFactura INT, _idDetalleOrdenMenu INT, _idDetalleOrdenCombo INT, _precioMenu DOUBLE(10,2), _descuento DOUBLE(10,2), _comentario TEXT )
BEGIN
	DECLARE _perteneceCombo BOOLEAN DEFAULT FALSE;
    DECLARE _estadoActualDetalle INT;
    DECLARE _idTipoServicio INT;
    DECLARE _yaFacturado BOOLEAN DEFAULT FALSE;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya existe este detalle en la Factura' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN

		# SI ES MENU
		IF !ISNULL( _idDetalleOrdenMenu ) THEN
			SET _idDetalleOrdenCombo = NULL;
            
			SELECT dom.perteneceCombo, dom.idEstadoDetalleOrden, dom.idTipoServicio, IF( !ISNULL( dof.idFactura ), TRUE, FALSE )
				INTO _perteneceCombo, _estadoActualDetalle, _idTipoServicio, _yaFacturado
			FROM detalleOrdenMenu AS dom
				LEFT JOIN detalleOrdenFactura AS dof 
					ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
            WHERE dom.idDetalleOrdenMenu = _idDetalleOrdenMenu LIMIT 1;
			
		# SI ES COMBO
		ELSE
			SET _idDetalleOrdenMenu = NULL;
            
            SELECT doc.idEstadoDetalleOrden, doc.idTipoServicio, IF( !ISNULL( dof.idFactura ), TRUE, FALSE )
				INTO  _estadoActualDetalle, _idTipoServicio, _yaFacturado
			FROM detalleOrdenCombo AS doc
				LEFT JOIN detalleOrdenFactura AS dof 
					ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo
            WHERE doc.idDetalleOrdenCombo = _idDetalleOrdenCombo LIMIT 1;
		END IF;


		# SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
		
        # SI YA ESTA FACTURADO MUESTRA ERROR
        ELSEIF ( _yaFacturado ) THEN
			SELECT 'info' AS 'respuesta', 'Detalle de Orden ya facturado' AS 'mensaje';
            
		# SI PERTENECE A COMBO
		ELSEIF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'Error, detalle pertenece a combo' AS 'mensaje';

		# SI NO EXISTE NINGUN INCONVENIENTE
		ELSE

            # SI ES MENU
            IF !ISNULL( _idDetalleOrdenMenu ) THEN
				UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 6 
					WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu 		AND idEstadoDetalleOrden = 4;

			# SI ES COMBO
			ELSE
				UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 6
					WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo 	AND idEstadoDetalleOrden = 4;
					
				# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
				UPDATE detalleOrdenMenu AS dom

					JOIN detalleComboMenu AS dcm
						ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
							AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
							AND dom.idEstadoDetalleOrden = 4

				SET dom.idEstadoDetalleOrden = 6;
			END IF;
            
            # GUARDAR DETALLE DE FACTURA
			INSERT INTO detalleOrdenFactura
				( idFactura, idDetalleOrdenMenu, idDetalleOrdenCombo, precioMenu, descuento, comentario, usuario, fechaRegistro ) 
			VALUES
				( _idFactura, _idDetalleOrdenMenu, _idDetalleOrdenCombo, _precioMenu, _descuento, _comentario, @usuario, NOW() );
				
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		END IF;
	
    ELSEIF _action = 'update' THEN
		UPDATE detalleOrdenFactura SET
			precioMenu = _precioMenu,
            descuento  = _descuento,
            comentario = _comentario
		WHERE idFactura = _idFactura AND idDetalleOrdenMenu = _idDetalleOrdenMenu 
			AND idDetalleOrdenCombo = _idDetalleOrdenCombo;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
        
	ELSEIF _action = 'delete' THEN

		IF !ISNULL( _idDetalleOrdenMenu ) THEN
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 4 
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

		ELSE
			UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 4
				WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
		END IF;

		DELETE FROM detalleOrdenFactura WHERE idFactura = _idFactura 
			AND idDetalleOrdenMenu = _idDetalleOrdenMenu AND idDetalleOrdenCombo = _idDetalleOrdenCombo;
    
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END$$

CREATE PROCEDURE detalleFacturaEvento( _idFactura INT, _idEvento INT )
BEGIN
	DECLARE _perteneceCombo BOOLEAN DEFAULT FALSE;
    DECLARE _estadoActualDetalle INT;
    DECLARE _idTipoServicio INT;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    # MENUS DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, descripcion, subTotal, fechaRegistros ) 
    SELECT
    	_idEvento, _idFactura, CONCAT( em.cantidad, ' ', m.menu ), ( em.cantidad * em.precioUnitario), NOW()
    FROM eventoMenu AS em
    	JOIN menu AS m 		ON em.idMenu = m.idMenu
    WHERE em.idEvento = _idEvento;

    # COMBOS DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, descripcion, subTotal, fechaRegistros ) 
    SELECT
    	_idEvento, _idFactura, CONCAT( ec.cantidad, ' ', c.combo ), ( ec.cantidad * ec.precioUnitario), NOW()
    FROM eventoCombo AS ec
    	JOIN combo AS c 		ON ec.idCombo = c.idCombo
    WHERE ec.idEvento = _idEvento;

    # MENU PERSONALIZADO DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, descripcion, subTotal, fechaRegistros ) 
    SELECT
    	_idEvento, _idFactura, otroMenu, ( cantidad * precioUnitario), NOW()
    FROM otroMenu
    WHERE idEvento = _idEvento;

    # OTRO SERVICIO DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, descripcion, subTotal, fechaRegistros ) 
    SELECT
    	_idEvento, _idFactura, otroServicio, ( cantidad * precioUnitario), NOW()
    FROM otroServicio
    WHERE idEvento = _idEvento;
END$$


CREATE VIEW vstFormaPago AS
SELECT 
	ffp.idFactura,
    ffp.monto,
    fp.idFormaPago,
    fp.formaPago
FROM facturaFormaPago AS ffp
	JOIN formaPago AS fp
		ON ffp.idFormaPago = fp.idFormaPago


CREATE OR REPLACE VIEW vstFactura AS
SELECT 
	f.idFactura,
    f.idCliente,
    c.nit,
    f.idCaja,
    f.nombre,
    f.direccion,
    f.total,
    f.fechaFactura,
    f.usuario,
    ef.idEstadoFactura,
    ef.estadoFactura,
    f.fechaRegistro
FROM factura AS f
	JOIN vstCliente AS c
		ON c.idCliente = f.idCliente
	JOIN estadoFactura AS ef
		ON f.idEstadoFactura = ef.idEstadoFactura


CREATE OR REPLACE VIEW vstDetalleOrdenFactura AS
SELECT
	dof.idFactura,
	mn.idOrdenCliente,
	mn.numeroTicket,
	mn.idDetalleOrdenMenu,
	mn.idMenu,
	mn.menu,
	mn.imagen,
	mn.perteneceCombo,
	mn.idDetalleOrdenCombo,
	mn.idCombo,
    mn.combo,
    mn.imagenCombo,
	mn.idTipoServicio,
	mn.tipoServicio,
	mn.usuarioRegistro,
	dof.precioMenu,
	dof.descuento,
	( dof.precioMenu - dof.descuento ) AS 'precioReal',
	dof.comentario,
	dof.usuario AS 'usuarioFacturaDetalle',
	dof.fechaRegistro AS 'fechaFacturaDetalle'
FROM vOrdenes AS mn
	JOIN detalleOrdenFactura AS dof ON 
	(
		( !ISNULL( mn.idDetalleOrdenMenu ) AND mn.idDetalleOrdenMenu = dof.idDetalleOrdenMenu )
		OR
		( !ISNULL( mn.idDetalleOrdenCombo ) AND mn.idDetalleOrdenCombo = dof.idDetalleOrdenCombo )
	)
;