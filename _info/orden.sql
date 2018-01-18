DELIMITER $$

CREATE PROCEDURE consultaOrdenCliente( _action VARCHAR(20), _idOrdenCliente INT, _numeroTicket INT, _usuarioResponsable VARCHAR(15), _idEstadoOrden INT, _usuarioBarra VARCHAR(15), _comentario TEXT )
BEGIN
	DECLARE _tktPendiente BOOLEAN DEFAULT FALSE;
	DECLARE _estadoActualOrden INT DEFAULT 0;
    DECLARE _ordenesPreparacion INT DEFAULT 0;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		
		IF !ISNULL( _numeroTicket ) THEN
			SELECT TRUE INTO _tktPendiente FROM ordenCliente 
				WHERE numeroTicket = _numeroTicket AND ( idEstadoOrden = 1 OR idEstadoOrden = 2 ) AND numMenu > 0
			LIMIT 1;
		END IF;
        
        IF _tktPendiente THEN
			SELECT 'danger' AS 'respuesta', 'Error, EXISTE una orden pendiente con este # de Ticket' AS 'mensaje';
        
        ELSE
			INSERT INTO ordenCliente ( numeroTicket, usuarioPropietario, usuarioResponsable, idEstadoOrden, fechaRegistro, numMenu, usuarioBarra ) 
				VALUES ( _numeroTicket, @usuario, IFNULL( _usuarioResponsable, @usuario ), 1, NOW(), 0, IFNULL( _usuarioBarra, @usuario ) );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID()AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente 
			WHERE idOrdenCliente = _idOrdenCliente;

		IF _estadoActualOrden = 1 OR _estadoActualOrden = 2 THEN
			UPDATE ordenCliente SET 
				numeroTicket       = _numeroTicket,
				usuarioResponsable = _usuarioResponsable
			WHERE idOrdenCliente = _idOrdenCliente;
			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

		ELSE

			SELECT 'danger' AS 'respuesta', 'Estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'cancel' THEN

		SET @comentario = _comentario;
		
        SELECT COUNT(*) INTO _ordenesPreparacion FROM detalleOrdenMenu 
			WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden > 1 AND idEstadoDetalleOrden != 10;
		
        IF _ordenesPreparacion > 0 THEN
			SELECT 'danger' AS 'respuesta', 'Error, existen menús en preparación' AS 'mensaje';
        
        ELSE
			# CANCELAR ORDENES
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            UPDATE detalleOrdenSuperCombo SET idEstadoDetalleOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            
            # CANCELA ORDEN PRINCIPAL
            UPDATE ordenCliente SET idEstadoOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            
			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
        END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaDetalleOrdenMenu( _action VARCHAR(20), _idDetalleOrdenMenu INT, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15), _observacion TEXT, _comentario TEXT )
BEGIN
	DECLARE _estadoActualDetalle INT;
	DECLARE _estadoActualOrden INT;
    DECLARE _idMenuActual INT;
    DECLARE _numEstadoAnterior INT;
	DECLARE _perteneceCombo BOOLEAN;
    DECLARE _sinIngredientes BOOLEAN DEFAULT FALSE;
	DECLARE _ids TEXT DEFAULT '';
	DECLARE _yaFacturado BOOLEAN DEFAULT FALSE;
    DECLARE _seCocina BOOLEAN DEFAULT TRUE;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	IF !ISNULL( _idDetalleOrdenMenu ) THEN
		SELECT dom.idEstadoDetalleOrden, dom.perteneceCombo, dom.idMenu, IFNULL( _idOrdenCliente, dom.idOrdenCliente ), 
			IFNULL( _idTipoServicio, dom.idTipoServicio), IF( !ISNULL( dof.idFactura ), TRUE, FALSE )
		INTO
			_estadoActualDetalle, _perteneceCombo, _idMenuActual, _idOrdenCliente, 
            _idTipoServicio, _yaFacturado
		FROM detalleOrdenMenu AS dom
			JOIN menu AS m 
				ON dom.idMenu = m.idMenu
                
			LEFT JOIN detalleOrdenFactura AS dof
				ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
                
		WHERE dom.idDetalleOrdenMenu = _idDetalleOrdenMenu
		LIMIT 1;
	END IF;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		# SUMA CANTIDAD DE MENUS ORDENADOS POR CLIENTE
        UPDATE ordenCliente SET numMenu = numMenu + _cantidad WHERE idOrdenCliente = _idOrdenCliente;
        
        SELECT seCocina INTO _seCocina FROM menu WHERE idMenu = _idMenu;
		
        WHILE _cantidad > 0 DO
			INSERT INTO detalleOrdenMenu 
				(idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo, observacion )
			VALUES (_idOrdenCliente, _idMenu, 1, IF( _seCocina, 1, 3 ), _idTipoServicio, @usuario, IFNULL( _usuarioResponsable, @usuario ), 0, _observacion );

			SET _ids = CONCAT( _ids, '_', LAST_INSERT_ID() );
			
			UPDATE menu SET top = top + 1 WHERE idMenu = _idMenu;
            
            SET _cantidad = _cantidad - 1;
        END WHILE;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', _ids AS 'ids';

	ELSEIF _action = 'cancel' THEN
		SET @comentario = _comentario;
        
        # SI ES DIFERENTE A PENDIENTE
		IF _estadoActualDetalle != 1 THEN
			SELECT 'warning' AS 'respuesta', 'Estado actual no permite cancelar' AS 'mensaje';
            
		ELSE
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 10
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
			
            # DESCUENTA DE NUMERO MENUS DE TICKET
            IF ! _perteneceCombo THEN
				UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            END IF;

			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
		END IF;
        
    ELSEIF _action = 'estado' THEN
		SET @comentario = _comentario;
        
        # SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _idEstadoDetalleOrden = 6 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
        
		ELSEIF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			
            # SI SE VA EMPEZAR A COCINAR, VERIFICA EXISTENCIA DE PRODUCTO
            IF _idEstadoDetalleOrden = 2 THEN
				SELECT TRUE INTO _sinIngredientes 
                FROM receta AS r
					JOIN producto AS p
						ON r.idProducto = p.idProducto AND p.disponibilidad < r.cantidad
                WHERE r.idMenu = _idMenuActual LIMIT 1;
			END IF;
            
            # SI NO EXISTEN INGREDIENTES SUFICIENTES
			IF _sinIngredientes THEN
				SELECT 'danger' AS 'respuesta', 'No hay suficientes ingredientes para prepara el Menú' AS 'mensaje';
            
            # SI NO EXISTEN NINGUN INCONVENIENTE
            ELSE
				# CAMBIA ESTADO A DETALLE DE ORDEN
				UPDATE detalleOrdenMenu    SET   idEstadoDetalleOrden = _idEstadoDetalleOrden
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
                
                # NUMERO DE ORDENES CON ESTADO ANTERIOR
                SELECT COUNT( * ) INTO _numEstadoAnterior
					FROM detalleOrdenMenu WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden < _idEstadoDetalleOrden ;


				# SI ES SERVIDO Y YA SE ENCUENTRA FACTURADO, CAMBIA DETALLE A FACTURADO
				IF _idEstadoDetalleOrden = 4 AND _yaFacturado THEN
					UPDATE detalleOrdenMenu    SET   idEstadoDetalleOrden = 6
					WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
				END IF;
				
                # SI PERTENECE A COMOB
                IF _perteneceCombo THEN
                	SET @detalleComboPendiente = NULL;

					# NUMERO DE ORDENES CON ESTADO ANTERIOR
					SELECT SUM( IF( dom.idEstadoDetalleOrden < _idEstadoDetalleOrden, 1, 0 ) ), dcmo.idDetalleOrdenCombo
						INTO @detalleComboPendiente, @idDetalleOrdenCombo
					FROM detalleComboMenu AS dcm
						JOIN detalleComboMenu AS dcmo
							ON dcmo.idDetalleOrdenCombo = dcm.idDetalleOrdenCombo
						LEFT JOIN detalleOrdenMenu AS dom
							ON dom.idDetalleOrdenMenu = dcmo.idDetalleOrdenMenu
								AND dcm.idDetalleOrdenMenu != dom.idDetalleOrdenMenu
					WHERE dcm.idDetalleOrdenMenu = _idDetalleOrdenMenu;
                    
                    # SI DETALLE ES IGUAL A CERO CAMBIA DE ESTADO COMBO
                    IF @detalleComboPendiente = 0 THEN
						UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = _idEstadoDetalleOrden 
							WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

						# VERIFICA SI YA ESTA FACTURADO
						SELECT TRUE INTO _yaFacturado FROM detalleOrdenFactura WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

						# SI TODOS LOS MENUS DEL COMBO ESTAN SERVIDOS Y ESTA FACTURADO, CAMBIA ESTADO DE COMBO A FACTURADO
						IF _idEstadoDetalleOrden = 4 AND _yaFacturado THEN
							UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 6 
								WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

						END IF;

                    END IF;
                END IF;
				
                # CAMBIA ESTADO A ORDEN PRINCIPAL SI TODOS CAMBIARON
                IF _numEstadoAnterior = 0 THEN
					UPDATE ordenCliente SET idEstadoOrden = _idEstadoDetalleOrden
						WHERE idOrdenCliente = _idOrdenCliente AND idEstadoOrden < _idEstadoDetalleOrden;
				END IF;
                
                # DESCUENTA DE NUMERO MENUS DE TICKET
				IF ! _perteneceCombo AND _idEstadoDetalleOrden = 10 THEN
					UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
				END IF;

				SELECT 'success' AS 'respuesta', 'Cambio de estado guardado correctamente' AS 'mensaje';
            END IF;
		
        ELSE
			SELECT 'danger' AS 'respuesta', 'No se puede retornar a un estado anterior' AS 'mensaje';
		END IF;

	ELSEIF _action = 'responsable' THEN
		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSE
			UPDATE detalleOrdenMenu SET
				usuarioResponsable = _usuarioResponsable
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Cambio de responsable exitoso' AS 'mensaje';
		END IF;

	ELSEIF _action = 'tipoServicio' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ( ( _estadoActualDetalle != 6 AND _estadoActualDetalle != 10 ) OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET
				idTipoServicio = _idTipoServicio
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'menu' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ( _estadoActualDetalle = 1 OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET 
				idMenu = _idMenu
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'asignarOtroCliente' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ISNULL( _estadoActualDetalle ) THEN
			SELECT 'danger' AS 'respuesta', 'Existe información faltante' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar la asignación, por estado actual de pedido' AS 'mensaje';
			
		ELSE
			UPDATE detalleOrdenMenu SET 
				idOrdenCliente = _idOrdenCliente
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE _comboDetalleMenu( _idDetalleOrdenCombo INT, _idCombo INT, _idTipoServicio INT, _idEstadoDetalleOrden INT, _usuarioResponsable VARCHAR( 15 ), _idOrdenCliente INT, _observacion TEXT )
BEGIN
	DECLARE finCursor BOOLEAN DEFAULT FALSE;
	DECLARE _idMenu INT;
    DECLARE _cantidad INT;
    DECLARE _seCocina BOOLEAN;

    # DECLARACION DE CURSOR PARA OBTENER DETALLE DE COMBO
    DEClARE cursorMenu CURSOR FOR 
		SELECT cd.idMenu, cd.cantidad, m.seCocina
        FROM comboDetalle AS cd
			JOIN menu AS m
				ON cd.idMenu = m.idMenu
                
        WHERE cd.idCombo = _idCombo;

	# SI YA NO HAY MAS DETALLE DE COMBO
    DECLARE CONTINUE HANDLER FOR NOT FOUND 
		SET finCursor = TRUE;

	OPEN cursorMenu;

	loopMenu: LOOP

		FETCH cursorMenu INTO _idMenu, _cantidad, _seCocina;
        
		IF finCursor THEN 
			LEAVE loopMenu;
		END IF;
        
        # MIENTRAS EXISTA CANTIDAD, SE AGREGA UNO A UNO
        WHILE _cantidad >= 1 DO
			INSERT INTO detalleOrdenMenu
				( idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo, observacion )
			VALUES ( _idOrdenCliente, _idMenu, 1, IF( _seCocina, _idEstadoDetalleOrden, 3 ), _idTipoServicio, @usuario, _usuarioResponsable, 1, _observacion );
            
            SET @idDetalleOrdenMenu = LAST_INSERT_ID();
            
            INSERT INTO detalleComboMenu( idDetalleOrdenMenu, idDetalleOrdenCombo ) 
				VALUES ( @idDetalleOrdenMenu, _idDetalleOrdenCombo );
			
            SET _cantidad = _cantidad - 1;
        END WHILE;

	END LOOP loopMenu;

	CLOSE cursorMenu;
END$$

CREATE PROCEDURE consultaDetalleOrdenCombo( _action VARCHAR(20), _idDetalleOrdenCombo INT, _idOrdenCliente INT, _idCombo INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15), _observacion TEXT, _comentario TEXT )
BEGIN
	DECLARE _estadoActualDetalle INT;
	DECLARE _estadoActualOrden INT;
    DECLARE _ids TEXT DEFAULT '';
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	SELECT idEstadoDetalleOrden, IFNULL( _idOrdenCliente, idOrdenCliente ), IFNULL( _idTipoServicio, idTipoServicio )
		INTO _estadoActualDetalle, _idOrdenCliente, _idTipoServicio
	FROM detalleOrdenCombo WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		# SUMA CANTIDAD DE MENUS ORDENADOS POR CLIENTE
        UPDATE ordenCliente SET numMenu = numMenu + _cantidad WHERE idOrdenCliente = _idOrdenCliente;
            
		WHILE _cantidad > 0 DO

			INSERT INTO detalleOrdenCombo (idOrdenCliente, idCombo, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, observacion )
			VALUES (_idOrdenCliente, _idCombo, 1, 1, _idTipoServicio, @usuario, IFNULL( _usuarioResponsable, @usuario ), _observacion );

			UPDATE combo SET top = top + 1 WHERE idCombo = _idCombo;

			SET @idDetalleOrdenCombo = LAST_INSERT_ID();
            
            SET _ids = CONCAT( _ids, '_', @idDetalleOrdenCombo );

			# INGRESA DETALLE DE MENU DE COMBO
			CALL _comboDetalleMenu( @idDetalleOrdenCombo, _idCombo, _idTipoServicio, 1, IFNULL( _usuarioResponsable, @usuario ), _idOrdenCliente, _observacion );

			SET _cantidad = _cantidad - 1;
		END WHILE;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', _ids AS 'ids';
        
	ELSEIF _action = 'cancel' THEN
		
        SET @comentario = _comentario;
    
        # SI ES DIFERENTE A PENDIENTE
		IF _estadoActualDetalle != 1 THEN
			SELECT 'warning' AS 'respuesta', 'Estado actual no permite cancelar' AS 'mensaje';
            
		ELSE
			UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 10
				WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
			
            # DESCUENTA DE NUMERO MENUS ORDEN
            UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            
			# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idEstadoDetalleOrden = 10;

			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
		END IF;

	ELSEIF _action = 'estado' THEN
		
        SET @comentario = _comentario;
        
        # SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _idEstadoDetalleOrden = 6 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
        
		ELSEIF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			UPDATE detalleOrdenCombo SET
				idEstadoDetalleOrden = _idEstadoDetalleOrden
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
            
            # DESCUENTA DE NUMERO MENUS ORDEN SI ES CANCELAR
            IF _idEstadoDetalleOrden = 10 THEN
				UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            END IF;

			# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idEstadoDetalleOrden = _idEstadoDetalleOrden;

			SELECT 'success' AS 'respuesta', 'Cambio de estado realizado correctamente' AS 'mensaje';
            
		ELSE
			SELECT 'danger' AS 'respuesta', 'No se puede retornar a un estado anterior' AS 'mensaje';
            
		END IF;

	ELSEIF _action = 'responsable' THEN
		UPDATE detalleOrdenCombo SET
			usuarioResponsable = _usuarioResponsable
		WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

		# ACTUALIZA RESPONSABLE DETALLE DE COMBO
		UPDATE detalleOrdenMenu AS dom
			JOIN detalleComboMenu AS dcm
				ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
					AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
		SET dom.usuarioResponsable = _usuarioResponsable;

		SELECT 'success' AS 'respuesta', 'Cambio de responsable exitoso' AS 'mensaje';

	ELSEIF _action = 'tipoServicio' THEN
		IF ( ( _estadoActualDetalle != 6 AND _estadoActualDetalle != 10 ) OR @isAdmin ) THEN
			UPDATE detalleOrdenCombo SET
				idTipoServicio = _idTipoServicio
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

			# ACTUALIZA TIPO SERVICIO DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idTipoServicio = _idTipoServicio;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'asignarOtroCliente' THEN

		IF ISNULL( _estadoActualDetalle ) THEN
			SELECT 'danger' AS 'respuesta', 'Existe información faltante' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar la asignación, por estado actual de pedido' AS 'mensaje';
			
		ELSE
			UPDATE detalleOrdenCombo SET 
				idOrdenCliente = _idOrdenCliente
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

			# ACTUALIZA ORDEN CLIENTE DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idOrdenCliente = _idOrdenCliente;

			SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE FUNCTION obtenerDisponiblidad( _idMenu INT, _idCombo INT, _cantidad INT )
RETURNS TEXT
BEGIN
	DECLARE _resultado TEXT;
    
	IF !ISNULL( _idMenu ) AND _cantidad > 0 THEN
		SELECT
			GROUP_CONCAT(
				CONCAT(
					( r.cantidad * _cantidad ), '#i#',
					p.disponibilidad, '#i#',
					p.producto, '#i#',
					m.medida
				) SEPARATOR '=r='
			) 	 INTO 	_resultado
		FROM receta AS r
			
			JOIN producto AS p
				ON r.idProducto = p.idProducto
				
			JOIN medida AS m
				ON m.idMedida = p.idMedida
                
		WHERE r.idMenu = _idMenu AND p.disponibilidad < ( r.cantidad * _cantidad ) LIMIT 1;
        
	ELSEIF !ISNULL( _idCombo ) AND _cantidad > 0 THEN
		
        SELECT
			GROUP_CONCAT(
				CONCAT(
					( cd.cantidad * r.cantidad * _cantidad ), '#i#',
					p.disponibilidad, '#i#',
					p.producto, '#i#',
					m.medida
				) SEPARATOR '=r='
			) 	 INTO 	_resultado
		FROM comboDetalle AS cd
			
            JOIN receta AS r
				ON r.idMenu = cd.idMenu
			
			JOIN producto AS p
				ON r.idProducto = p.idProducto
				
			JOIN medida AS m
				ON m.idMedida = p.idMedida
                
		WHERE cd.idCombo = _idCombo AND p.disponibilidad < ( cd.cantidad * r.cantidad * _cantidad ) LIMIT 1;
        
	END IF;

	RETURN _resultado;
END$$







CREATE OR REPLACE VIEW _vMenuCombo AS
SELECT
	dcm.idDetalleOrdenMenu,
	dcm.idDetalleOrdenCombo,
	cp.precio AS 'precioCombo',
	c.combo,
    c.idCombo,
    c.imagen AS 'imagenCombo',
    doc.idEstadoDetalleOrden AS 'idEstadoDetalleOrdenCombo',
    doc.observacion AS 'observacionCombo'
FROM detalleComboMenu AS dcm
	JOIN detalleOrdenCombo AS doc
		ON dcm.idDetalleOrdenCombo = doc.idDetalleOrdenCombo
	JOIN combo AS c
		ON doc.idCombo = c.idCombo
	JOIN comboPrecio AS cp
		ON doc.idTipoServicio = cp.idTipoServicio AND doc.idCombo = cp.idCombo
;


CREATE OR REPLACE VIEW vOrdenes AS
SELECT
	dom.idDetalleOrdenMenu,
	oc.idOrdenCliente,
	oc.numeroTicket,
    oc.usuarioResponsable AS 'responsableOrden',
    oc.usuarioBarra,
    oc.idEstadoOrden,
	dom.cantidad,
	m.idMenu,
	m.menu,
    m.codigo AS 'codigoMenu',
    m.tiempoAlerta,
	dom.perteneceCombo,
	m.descripcion,
	IFNULL( m.imagen, '' ) AS 'imagen',
	mp.precio,
	mc.idCombo,
    mc.combo,
    mc.imagenCombo,
	mc.precioCombo,
	edo.idEstadoDetalleOrden,
	edo.estadoDetalleOrden,
	ts.idTipoServicio,
	ts.tipoServicio,
	dm.idDestinoMenu,
	dm.destinoMenu,
	dom.usuarioResponsable AS 'responsableDetalle',
    dom.usuario AS 'usuarioDetalle',
    dom.observacion,
	u.nombres,
	u.codigo,
	mc.idDetalleOrdenCombo,
    mc.idEstadoDetalleOrdenCombo,
	bom.fechaRegistro,
	bom.usuario AS 'usuarioRegistro'
FROM detalleOrdenMenu AS dom
	JOIN menu AS m
		ON dom.idMenu = m.idMenu
	JOIN estadoDetalleOrden AS edo
		ON dom.idEstadoDetalleOrden = edo.idEstadoDetalleOrden
	JOIN tipoServicio AS ts
		ON dom.idTipoServicio = ts.idTipoServicio
	JOIN usuario AS u
		ON dom.usuarioResponsable = u.usuario
	JOIN bitacoraOrdenMenu AS bom
		ON dom.idDetalleOrdenMenu = bom.idDetalleOrdenMenu AND dom.idEstadoDetalleOrden = bom.idEstadoDetalleOrden
	JOIN destinoMenu AS dm
		ON dm.idDestinoMenu = m.idDestinoMenu
	JOIN ordenCliente AS oc
		ON dom.idOrdenCliente = oc.idOrdenCliente
	LEFT JOIN menuPrecio AS mp
		ON m.idMenu = mp.idMenu AND dom.idTipoServicio = mp.idTipoServicio AND !dom.perteneceCombo
	LEFT JOIN _vMenuCombo AS mc
		ON dom.idDetalleOrdenMenu = mc.idDetalleOrdenMenu
ORDER BY dom.idDetalleOrdenMenu DESC
;


CREATE VIEW vOrdenCliente AS
SELECT
	idOrdenCliente,
    numeroTicket,
    usuarioPropietario,
    usuarioResponsable,
    usuarioBarra,
    eo.idEstadoOrden,
    eo.estadoOrden,
    fechaRegistro,
    numMenu
FROM ordenCliente AS oc
	JOIN estadoOrden AS eo
		ON oc.idEstadoOrden = eo.idEstadoOrden;



