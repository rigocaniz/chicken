CREATE PROCEDURE consultaDetalleOrdenMenu( _action VARCHAR(20), _idDetalleOrdenMenu INT, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15) )
BEGIN
	DECLARE _estadoActualDetalle INT DEFAULT NULL;
	DECLARE _estadoActualOrden INT DEFAULT NULL;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	SELECT idEstadoDetalleOrden INTO _estadoActualDetalle FROM detalleOrdenMenu WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO detalleOrdenMenu (idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable)
		VALUES (_idOrdenCliente, _idMenu, _cantidad, 1, _idTipoServicio, @usuario, _usuarioResponsable );

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
		UPDATE detalleOrdenMenu SET
			usuarioResponsable = _usuarioResponsable
		WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

		SELECT 'success' AS 'respuesta', 'Cambio de responsable exitoso' AS 'mensaje';

	ELSEIF _action = 'factura' THEN
		IF ( ( _estadoActualDetalle <= 5  ) OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET
				idFactura            = _idFactura,
				idEstadoDetalleOrden = IF( !ISNULL( _idFactura ), 5, 4 )
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Cambio de factura exitoso' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'Estado actual no permite modificación de factura' AS 'mensaje';
		END IF;

	ELSEIF _action = 'tipoServicio' THEN
		IF ( ( _estadoActualDetalle != 6 AND _estadoActualDetalle != 10 ) OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET
				idTipoServicio = _idTipoServicio
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'menu-cantidad' THEN
		IF ( _estadoActualDetalle = 1 OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET 
				idMenu   = _idMenu,
				cantidad = _cantidad
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
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
			UPDATE detalleOrdenMenu SET 
				idOrdenCliente = _idOrdenCliente
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$
