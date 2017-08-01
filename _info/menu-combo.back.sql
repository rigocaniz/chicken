# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO
	CALL definirSesion( _usuario varchar(15) );
# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO


/* ########## +++++++++++++ consultaReceta +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaReceta( 'insert', _idMenu INT, _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
update
	CALL consultaReceta( 'update', _idMenu INT, _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Error, registro duplicado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaMenu +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaMenu( 'insert', NULL, _menu VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, _idDestinoMenu INT, _idTipoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaMenu( 'update', _idMenu INT, _menu VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, _idDestinoMenu INT, _idTipoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaMenuPrecio +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaMenuPrecio( 'insert', _idMenu INT, _idTipoServicio INT, _precio DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
update
	CALL consultaMenuPrecio( 'update', _idMenu INT, _idTipoServicio INT, _precio DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
delete
	CALL consultaMenuPrecio( 'delete', _idMenu INT, _idTipoServicio INT, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Precio para Tipo de Servicio ya registrado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaCombo +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaCombo( 'insert', NULL, _combo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaCombo( 'update', _idCombo INT, _combo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaComboPrecio +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaComboPrecio( 'insert', _idCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
update
	CALL consultaComboPrecio( 'update', _idCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
delete
	CALL consultaComboPrecio( 'delete', _idCombo INT, _idTipoServicio INT, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Precio para Tipo de Servicio ya registrado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaComboDetalle +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaComboDetalle( 'insert', _idCombo INT, _idMenu INT, _cantidad DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
update
	CALL consultaComboDetalle( 'update', _idCombo INT, _idMenu INT, _cantidad DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
delete
	CALL consultaComboDetalle( 'delete', _idCombo INT, _idMenu INT, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Menu ya registrado para este combo' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaSuperCombo +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaSuperCombo( 'insert', NULL, _superCombo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaSuperCombo( 'update', _idSuperCombo INT, _superCombo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';

/* ########## +++++++++++++ consultaSuperComboDetalle +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaSuperComboDetalle( 'insert', NULL, _idCombo INT, _cantidad DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
update
	CALL consultaSuperComboDetalle( 'update', _idSuperCombo INT, _idCombo INT, _cantidad DOUBLE(10,2) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
delete
	CALL consultaSuperComboDetalle( 'delete', _idSuperCombo INT, _idCombo INT, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Combo ya registrado para este Super Combo' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ VISTA +++++++++++++########*/

SELECT
	idMenu,
	idProducto,
	producto,
	cantidad,
	medida,
	tipoProducto,
	observacion
FROM lstReceta;

SELECT
	idMenu,
	menu,
	imagen,
	descripcion,
	idEstadoMenu,
	estadoMenu,
	idDestinoMenu,
	destinoMenu
FROM lstMenu;

SELECT
	idMenu,
	precio,
	idTipoServicio,
	tipoServicio
FROM lstMenuPrecio;


SELECT
	idCombo,
	combo,
	imagen,
	descripcion,
	idEstadoMenu,
	estadoMenu
FROM lstCombo;

SELECT
	idCombo,
	cantidad,
	idMenu,
	menu,
	imagen,
	descripcion,
	idEstadoMenu,
	estadoMenu
FROM lstComboDetalle;

SELECT
	idCombo,
	precio,
	idTipoServicio,
	tipoServicio
FROM lstComboPrecio;


SELECT
	idSuperCombo,
	superCombo,
	imagen,
	descripcion,
	idEstadoMenu,
	estadoMenu
FROM lstSuperCombo;

SELECT
	idSuperCombo,
	cantidad,
	idCombo,
	combo,
	imagen,
	descripcion,
	idEstadoMenu,
	estadoMenu
FROM lstSuperComboDetalle;

SELECT
	idSuperCombo,
	precio,
	idTipoServicio,
	tipoServicio
FROM lstSuperComboPrecio;

