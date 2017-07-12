# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO
	CALL definirSesion( _usuario varchar(15) );
# ANTES DE EJECURAR ALGUNA CONSULTA DEBE LLAMARSE EL PROCEDIMIENTO


/* ########## +++++++++++++ consultaProducto +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaProducto( 'insert', NULL, producto VARCHAR(45), idTipoProducto INT, idMedida INT, perecedero BOOLEAN, cantidadMinima DOUBLE(10,2), cantidadMaxima DOUBLE(10,2), disponibilidad DOUBLE(10,2), importante BOOLEAN );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaProducto( 'update', idProducto INT, producto VARCHAR(45), idTipoProducto INT, idMedida INT, perecedero BOOLEAN, cantidadMinima DOUBLE(10,2), cantidadMaxima DOUBLE(10,2), disponibilidad DOUBLE(10,2), importante BOOLEAN );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Error, producto duplicado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaMedida +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaMedida( 'insert', NULL, medida VARCHAR(45) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaMedida( 'update', idMedida INT, medida VARCHAR(45) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Medida duplicada' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaTipoProducto +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaTipoProducto( 'insert', NULL, _tipoProducto VARCHAR(45) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaTipoProducto( 'update', _idTipoProducto INT, _tipoProducto VARCHAR(45) );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	DUPLICADO:
		SELECT 'danger' AS 'respuesta', 'Tipo de Producto duplicado' AS 'mensaje';
	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaIngreso +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaIngreso( 'insert', NULL, _cantidad DOUBLE(10,2), _idProducto INT );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
delete
	CALL consultaIngreso( 'delete', _idIngreso INT, NULL, NULL );
	RETORNA: SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

ERRORES:	
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ consultaReajusteInventario +++++++++++++########*/
# >>> ACTION <<<
insert
	CALL consultaReajusteInventario( 'insert', NULL, _cantidad DOUBLE(10,2), _observacion TEXT, _esIncremento BOOLEAN );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
update
	CALL consultaReajusteInventario( 'update', _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT, _esIncremento BOOLEAN );
	RETORNA: SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

ERRORES:
	ERROR NO CONTROLADO:
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	
	SESION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
	ACCION NO VALIDA:
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';


/* ########## +++++++++++++ VISTAS +++++++++++++########*/
SELECT
	idProducto,
	producto,
	idMedida,
	medida,
	idTipoProducto,
	tipoProducto,
	perecedero,
	cantidadMinima,
	cantidadMaxima,
	disponibilidad,
	importante,
	usuarioProducto,
	fechaProducto
FROM lstProducto;


SELECT
	idIngreso,
	idProducto,
	producto,
	idMedida,
	medida,
	idTipoProducto,
	tipoProducto,
	perecedero,
	cantidadMinima,
	cantidadMaxima,
	disponibilidad,
	importante,
	cantidad,
	usuarioIngreso,
	fechaIngreso
FROM lstIngresoProducto;

SELECT
	idReajusteInventario,
	idProducto,
	producto,
	idMedida,
	medida,
	idTipoProducto,
	tipoProducto,
	perecedero,
	cantidadMinima,
	cantidadMaxima,
	disponibilidad,
	importante,
	cantidad,
	observacion,
	esIncremento,
	usuarioReajuste,
	fechaReajuste
FROM lstReajusteProducto;

