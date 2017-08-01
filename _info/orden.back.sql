# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO
	CALL definirSesion( _usuario varchar(15) );
# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO


/* ########## +++++++++++++ consultaOrdenCliente +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaOrdenCliente( 'insert', NULL, _numeroTicket INT, _usuarioResponsable VARCHAR(15), NULL );

update
	CALL consultaOrdenCliente( 'update', _idOrdenCliente INT, _numeroTicket INT, _usuarioResponsable VARCHAR(15), NULL );

status
	CALL consultaOrdenCliente( 'status', _idOrdenCliente INT, NULL, NULL, _idEstadoOrden INT );

cancel
	CALL consultaOrdenCliente( 'cancel', _idOrdenCliente INT, NULL, NULL, NULL );



/* ########## +++++++++++++ consultaDetalleOrdenMenu +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaDetalleOrdenMenu( 'insert', NULL, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), NULL, _idTipoServicio INT, NULL, _usuarioResponsable VARCHAR(15) );

menu
	CALL consultaDetalleOrdenMenu( 'menu', _idDetalleOrdenMenu INT, NULL, _idMenu INT, NULL, NULL, NULL, NULL, NULL );

estado
	CALL consultaDetalleOrdenMenu( 'estado', _idDetalleOrdenMenu INT, NULL, NULL, NULL, _idEstadoDetalleOrden INT, NULL, NULL, NULL )

responsable
	CALL consultaDetalleOrdenMenu( 'responsable', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, NULL, NULL, _usuarioResponsable VARCHAR(15) );

tipoServicio
	CALL consultaDetalleOrdenMenu( 'tipoServicio', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, _idTipoServicio INT, NULL, NULL );

asignarOtroCliente
	CALL consultaDetalleOrdenMenu( 'asignarOtroCliente', _idDetalleOrdenMenu INT, _idOrdenCliente INT, NULL, NULL, NULL, NULL, NULL, NULL );



/* ########## +++++++++++++ consultaDetalleOrdenCombo +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaDetalleOrdenCombo( 'insert', NULL, _idOrdenCliente INT, _idCombo INT, _cantidad DOUBLE(10,2), NULL, _idTipoServicio INT, NULL, _usuarioResponsable VARCHAR(15) );

estado
	CALL consultaDetalleOrdenCombo( 'estado', _idDetalleOrdenMenu INT, NULL, NULL, NULL, _idEstadoDetalleOrden INT, NULL, NULL, NULL )

responsable
	CALL consultaDetalleOrdenCombo( 'responsable', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, NULL, NULL, _usuarioResponsable VARCHAR(15) );

tipoServicio
	CALL consultaDetalleOrdenCombo( 'tipoServicio', _idDetalleOrdenMenu INT, NULL, NULL, NULL, NULL, _idTipoServicio INT, NULL, NULL );

asignarOtroCliente
	CALL consultaDetalleOrdenCombo( 'asignarOtroCliente', _idDetalleOrdenMenu INT, _idOrdenCliente INT, NULL, NULL, NULL, NULL, NULL, NULL );



/* ############################################################
 				+++++++++++++ VISTAS +++++++++++++
 ############################################################*/

/* ########## +++++++++++++ vOrdenes +++++++++++++########*/
SELECT 
	idDetalleOrdenMenu,
	idOrdenCliente,
	numeroTicket,
	cantidad,
	idMenu,
	menu,
	perteneceCombo,
	descripcion,
	imagen,
	precio,
	combo,
	precioCombo,
	idEstadoDetalleOrden,
	estadoDetalleOrden,
	idTipoServicio,
	tipoServicio,
	idDestinoMenu,
	destinoMenu,
	usuarioResponsable,
	nombres,
	codigo,
	idDetalleOrdenCombo,
	fechaRegistro,
	usuarioRegistro
FROM vOrdenes ;

