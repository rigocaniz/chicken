# +++++++++++++++++++++++++++++ INVENTARIO +++++++++++++++++++++++++++++

/* ********************************************************************************************************
		(((((((((((((((((((((((( START - INFO.BACKEND ))))))))))))))))))))))))
*********************************************************************************************************/

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

/* ********************************************************************************************************
		(((((((((((((((((((((((( END - INFO.BACKEND ))))))))))))))))))))))))
*********************************************************************************************************/



/* $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ 
$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ PROCEDIMIENTOS $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ */

CREATE PROCEDURE consultaProducto( _action VARCHAR(20), _idProducto INT, _producto VARCHAR(45), _idTipoProducto INT, _idMedida INT, _perecedero BOOLEAN, _cantidadMinima DOUBLE(10,2), _cantidadMaxima DOUBLE(10,2), _disponibilidad DOUBLE(10,2), _importante BOOLEAN )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Error, producto duplicado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO producto ( producto, idTipoProducto, idMedida, perecedero, cantidadMinima, cantidadMaxima, disponibilidad, importante, fechaRegistro, usuario )
			VALUES ( _producto, _idTipoProducto, _idMedida, _perecedero, _cantidadMinima, _cantidadMaxima, _disponibilidad, _importante, NOW(), @usuario );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE producto SET 
			producto       = _producto, 
			idTipoProducto = _idTipoProducto, 
			idMedida       = _idMedida, 
			perecedero     = _perecedero, 
			cantidadMinima = _cantidadMinima, 
			cantidadMaxima = _cantidadMaxima,
			importante     = _importante
		WHERE idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaMedida( _action VARCHAR(20), _idMedida INT, _medida VARCHAR(45) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Medida duplicada' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO medida VALUES ( _idMedida, _medida );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE medida  SET medida = _medida
			WHERE idMedida = _idMedida;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaTipoProducto( _action VARCHAR(20), _idTipoProducto INT, _tipoProducto VARCHAR(45) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Tipo de Producto duplicado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO tipoProducto VALUES ( _idTipoProducto, _tipoProducto );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE tipoProducto  SET tipoProducto = _tipoProducto
			WHERE idTipoProducto = _idTipoProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaIngreso( _action VARCHAR(20), _idIngreso INT, _cantidad DOUBLE(10,2), _idProducto INT )
BEGIN

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO ingreso ( idProducto, cantidad, usuario, fechaRegistro ) 
			VALUES ( _idProducto, _cantidad, @usuario, NOW() );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'delete' THEN
		DELETE FROM ingreso WHERE idIngreso = _idIngreso;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaReajusteInventario( _action VARCHAR(20), _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT, _esIncremento BOOLEAN )
BEGIN
	DECLARE _disponibilidad DOUBLE(10,2);

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		SELECT disponibilidad INTO _disponibilidad FROM producto WHERE idProducto = _idProducto;

		IF ! _esIncremento AND _disponibilidad < _cantidad THEN
			SELECT 'danger' AS 'respuesta', 'La disponibilidad no puede ser menor a cero' AS 'mensaje';

		ELSE
			INSERT INTO reajusteInventario (idProducto, cantidad, observacion, esIncremento, usuario, fechaRegistro) 
				VALUES ( _idProducto, _cantidad, _observacion, _esIncremento, @usuario, NOW() );
			
			IF _esIncremento THEN
				UPDATE producto SET disponibilidad = disponibilidad + _cantidad
					WHERE idProducto = _idProducto;
			ELSE
				UPDATE producto SET disponibilidad = disponibilidad - _cantidad
					WHERE idProducto = _idProducto;
			END IF;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$




CREATE VIEW lstProducto AS
SELECT
	p.idProducto,
	p.producto,
	m.idMedida,
	m.medida,
	tp.idTipoProducto,
	tp.tipoProducto,
	p.perecedero,
	p.cantidadMinima,
	p.cantidadMaxima,
	p.disponibilidad,
	p.importante,
	p.usuario AS 'usuarioProducto',
	p.fechaRegistro AS 'fechaProducto'
FROM producto AS p
	JOIN medida AS m
		ON m.idMedida = p.idMedida
	JOIN tipoProducto AS tp
		ON tp.idTipoProducto = p.idTipoProducto;

CREATE VIEW lstIngresoProducto AS
SELECT
	i.idIngreso,
	p.idProducto,
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
	i.cantidad,
	i.usuario AS 'usuarioIngreso',
	i.fechaRegistro AS 'fechaIngreso'
FROM lstProducto AS p
	JOIN ingreso AS i ON i.idProducto = p.idProducto;

CREATE VIEW lstReajusteProducto AS
SELECT
	ri.idReajusteInventario,
	p.idProducto,
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
	ri.cantidad,
	ri.observacion,
	ri.esIncremento,
	ri.usuario AS 'usuarioReajuste',
	ri.fechaRegistro AS 'fechaReajuste'
FROM lstProducto AS p
	JOIN reajusteInventario AS ri ON ri.idProducto = p.idProducto;






