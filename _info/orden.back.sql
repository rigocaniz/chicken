# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO
	CALL definirSesion( _usuario varchar(15) );
# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO


/* ########## +++++++++++++ consultaDetalleOrdenMenu +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaDetalleOrdenMenu( 'insert', NULL, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), NULL, _idTipoServicio INT, NULL, _usuarioResponsable VARCHAR(15) );
menu-cantidad
	CALL consultaDetalleOrdenMenu( 'menu-cantidad', _idDetalleOrdenMenu INT, NULL, _idMenu INT, _cantidad DOUBLE(10,2), NULL, NULL, NULL, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

estado
	CALL consultaDetalleOrdenMenu( 'estado', _idDetalleOrdenMenu INT, NULL, NULL, NULL, _idEstadoDetalleOrden INT, NULL, NULL, NULL )
	RETORNA: SELECT 'success' AS 'respuesta', 'Cambio de estado guardado correctamente' AS 'mensaje';

responsable
	CALL consultaDetalleOrdenMenu( 'responsable', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, NULL, NULL, _usuarioResponsable VARCHAR(15) );

factura
	CALL consultaDetalleOrdenMenu( 'factura', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, NULL, _idFactura INT, NULL );

tipoServicio
	CALL consultaDetalleOrdenMenu( 'tipoServicio', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, _idTipoServicio INT, NULL, NULL );

asignarOtroCliente
	CALL consultaDetalleOrdenMenu( 'asignarOtroCliente', _idDetalleOrdenMenu INT, _idOrdenCliente INT, NULL, NULL, NULL, NULL, NULL, NULL );


ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Error, producto duplicado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';

	ERROR: MODIFICACION MENU-CANTIDAD:
		SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';

	ERROR: MODIFICACION estado:
		SELECT 'danger' AS 'respuesta', 'No se puede retornar a un estado anterior' AS 'mensaje';

	ERROR: factura:
		SELECT 'danger' AS 'respuesta', 'Estado actual no permite modificación de factura' AS 'mensaje';

	ERROR: tipoServicio:
		SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';

	ERROR: asignarOtroCliente:
			SELECT 'danger' AS 'respuesta', 'Existe información faltante' AS 'mensaje';
			SELECT 'danger' AS 'respuesta', 'No es posible realizar asignación, por estado actual de la Orden del Cliente' AS 'mensaje';
			SELECT 'danger' AS 'respuesta', 'No es posible realizar la asignación, por estado actual de pedido' AS 'mensaje';
