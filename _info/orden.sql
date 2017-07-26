CREATE PROCEDURE consultaOrdenCliente( _action VARCHAR(20), _idOrdenCliente INT, _numeroTicket INT, _usuarioResponsable VARCHAR(15), _idEstadoOrden INT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO ordenCliente ( numeroTicket, usuarioPropietario, usuarioResponsable, idEstadoOrden, fechaRegistro ) 
			VALUES ( _numeroTicket, @usuario, IFNULL( _usuarioResponsable, @usuario ), 1, NOW() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID()AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE ordenCliente SET 
			numeroTicket       = _numeroTicket,
			usuarioResponsable = _usuarioResponsable
		WHERE idOrdenCliente = _idOrdenCliente;
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSEIF _action = 'status' THEN
		UPDATE ordenCliente SET idEstadoOrden = _idEstadoOrden
			WHERE idOrdenCliente = _idOrdenCliente;
		SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$


CREATE PROCEDURE consultaDetalleOrdenMenu( _action VARCHAR(20), _idDetalleOrdenMenu INT, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15) )
BEGIN
	DECLARE _estadoActualDetalle INT DEFAULT NULL;
	DECLARE _estadoActualOrden INT DEFAULT NULL;
	DECLARE _perteneceCombo BOOLEAN;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	SELECT idEstadoDetalleOrden, perteneceCombo  INTO  _estadoActualDetalle, _perteneceCombo
		FROM detalleOrdenMenu WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' AND _cantidad != 1 THEN
		SELECT 'danger' AS 'respuesta', 'No es posible agregar más de un menú a la vez' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO detalleOrdenMenu (idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo )
		VALUES (_idOrdenCliente, _idMenu, _cantidad, 1, _idTipoServicio, @usuario, _usuarioResponsable, 0 );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'estado' THEN
		IF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET
				idEstadoDetalleOrden = _idEstadoDetalleOrden
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Cambio de estado guardado correctamente' AS 'mensaje';
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

		# SI EL OrdenCliente ESTA EN ESTADO: Finalizado (3), Cancelado (10)
		ELSEIF ( ( _estadoActualOrden = 3 OR _estadoActualOrden = 10 ) AND !@isAdmin ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar asignación, por estado actual de la Orden del Cliente' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 5 OR _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
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


CREATE PROCEDURE _comboDetalleMenu( _idDetalleOrdenCombo INT, _idCombo INT, _idTipoServicio INT, _idEstadoDetalleOrden INT, _usuarioResponsable VARCHAR( 15 ), _idOrdenCliente INT )
BEGIN
	DECLARE finCursor BOOLEAN DEFAULT FALSE;
	DECLARE _idMenu INT;
    DECLARE _cantidad INT;

    # DECLARACION DE CURSOR PARA OBTENER DETALLE DE COMBO
    DEClARE cursorMenu CURSOR FOR 
		SELECT idMenu, cantidad FROM comboDetalle WHERE idCombo = _idCombo;

	# SI YA NO HAY MAS DETALLE DE COMBO
    DECLARE CONTINUE HANDLER FOR NOT FOUND 
		SET finCursor = TRUE;

	OPEN cursorMenu;

	loopMenu: LOOP

		FETCH cursorMenu INTO _idMenu, _cantidad;
        
		IF finCursor THEN 
			LEAVE loopMenu;
		END IF;
        
        # MIENTRAS EXISTA CANTIDAD, SE AGREGA UNO A UNO
        WHILE _cantidad >= 1 DO
			INSERT INTO detalleOrdenMenu
				( idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo )
			VALUES ( _idOrdenCliente, _idMenu, 1, _idEstadoDetalleOrden, _idTipoServicio, @usuario, _usuarioResponsable, 1 );
            
            SET @idDetalleOrdenMenu = LAST_INSERT_ID();
            
            INSERT INTO detalleComboMenu( idDetalleOrdenMenu, idDetalleOrdenCombo ) 
				VALUES ( @idDetalleOrdenMenu, _idDetalleOrdenCombo );
			
            SET _cantidad = _cantidad - 1;
        END WHILE;

	END LOOP loopMenu;

	CLOSE cursorMenu;
END$$



CREATE PROCEDURE consultaDetalleOrdenCombo( _action VARCHAR(20), _idDetalleOrdenCombo INT, _idOrdenCliente INT, _idCombo INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15) )
BEGIN
	DECLARE _estadoActualDetalle INT DEFAULT NULL;
	DECLARE _estadoActualOrden INT DEFAULT NULL;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	SELECT idEstadoDetalleOrden INTO _estadoActualDetalle FROM detalleOrdenCombo WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' AND _cantidad != 1 THEN
		SELECT 'danger' AS 'respuesta', 'No es posible agregar más de un combo a la vez' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO detalleOrdenCombo (idOrdenCliente, idCombo, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable)
		VALUES (_idOrdenCliente, _idCombo, _cantidad, 1, _idTipoServicio, @usuario, _usuarioResponsable );

		SET @idDetalleOrdenCombo = LAST_INSERT_ID();

		# INGRESA DETALLE DE MENU DE COMBO
		CALL _comboDetalleMenu( @idDetalleOrdenCombo, _idCombo, _idTipoServicio, 1, _usuarioResponsable, _idOrdenCliente );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', @idDetalleOrdenCombo AS 'id';

	ELSEIF _action = 'estado' THEN
		IF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			UPDATE detalleOrdenCombo SET
				idEstadoDetalleOrden = _idEstadoDetalleOrden
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

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

		# SI EL OrdenCliente ESTA EN ESTADO: Finalizado (3), Cancelado (10)
		ELSEIF ( ( _estadoActualOrden = 3 OR _estadoActualOrden = 10 ) AND !@isAdmin ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar asignación, por estado actual de la Orden del Cliente' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 5 OR _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
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


CREATE OR REPLACE VIEW _vMenuCombo AS
SELECT
	dcm.idDetalleOrdenMenu,
	dcm.idDetalleOrdenCombo,
	cp.precio AS 'precioCombo',
	c.combo
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
	dom.cantidad,
	m.idMenu,
	m.menu,
	dom.perteneceCombo,
	m.descripcion,
	IFNULL( m.imagen, '' ) AS 'imagen',
	mp.precio,
	mc.combo,
	mc.precioCombo,
	edo.idEstadoDetalleOrden,
	edo.estadoDetalleOrden,
	ts.idTipoServicio,
	ts.tipoServicio,
	dm.idDestinoMenu,
	dm.destinoMenu,
	dom.usuarioResponsable,
	u.nombres,
	u.codigo,
	mc.idDetalleOrdenCombo,
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
	LEFT JOIN menuPrecio AS mp
		ON m.idMenu = mp.idMenu AND dom.idTipoServicio = mp.idTipoServicio AND !dom.perteneceCombo
	LEFT JOIN ordenCliente AS oc
		ON dom.idOrdenCliente = oc.idOrdenCliente
	LEFT JOIN _vMenuCombo AS mc
		ON dom.idDetalleOrdenMenu = mc.idDetalleOrdenMenu
ORDER BY dom.idDetalleOrdenMenu DESC
;



