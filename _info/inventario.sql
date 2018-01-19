DELIMITER $$

CREATE PROCEDURE consultaProducto( _action VARCHAR(20), _idProducto INT, _producto VARCHAR(45), _idTipoProducto INT, _idMedida INT, _perecedero BOOLEAN, _cantidadMinima DOUBLE(10,2), _cantidadMaxima DOUBLE(10,2), _disponibilidad DOUBLE(10,2), _importante BOOLEAN, _idUbicacion CHAR(1) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Error, producto duplicado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO producto ( producto, idTipoProducto, idMedida, perecedero, cantidadMinima, cantidadMaxima, disponibilidad, importante, idUbicacion, fechaRegistro, usuario )
			VALUES ( _producto, _idTipoProducto, _idMedida, _perecedero, _cantidadMinima, _cantidadMaxima, _disponibilidad, _importante, _idUbicacion, NOW(), @usuario );
		
        SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE producto SET 
			producto       = _producto, 
			idTipoProducto = _idTipoProducto, 
            idUbicacion    = _idUbicacion,
			idMedida       = _idMedida, 
			perecedero     = _perecedero, 
			cantidadMinima = _cantidadMinima, 
			cantidadMaxima = _cantidadMaxima,
			importante     = _importante,
            idUbicacion    = _idUbicacion
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

CREATE PROCEDURE consultaFactura( _action VARCHAR(20), _idFacturaCompra INT, _idEstadoFactura INT, _noFactura VARCHAR(15), _proveedor VARCHAR(45), _fechaFactura DATE, _comentario TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO facturaCompra( idEstadoFactura, noFactura, proveedor, fechaFactura, comentario )
			VALUES ( _idEstadoFactura, _noFactura, _proveedor, _fechaFactura, _comentario );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE facturaCompra SET
			idEstadoFactura = _idEstadoFactura,
			noFactura       = _noFactura,
			proveedor       = _proveedor,
			fechaFactura    = _fechaFactura,
			comentario      = _comentario
		WHERE idFacturaCompra = _idFacturaCompra;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaIngreso( _action VARCHAR(20), _idIngreso INT, _cantidad DOUBLE(10,2), _costo DOUBLE(12,2), _idProducto INT, _idFacturaCompra INT )
BEGIN

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO ingreso ( idFacturaCompra, idProducto, cantidad, costo, usuario, fechaRegistro ) 
			VALUES ( _idFacturaCompra, _idProducto, _cantidad, _costo, @usuario, NOW() );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM ingreso WHERE idIngreso = _idIngreso;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaReajuste( _action VARCHAR(20), _observacion TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO reajuste( observacion, usuario, fechaRegistro) 
			VALUES ( _observacion, @usuario, NOW() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaReajusteProducto( _action VARCHAR(20), _idReajuste INT, _idProducto INT, _cantidad DOUBLE(10,2), _esIncremento BOOLEAN )
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
			INSERT INTO reajusteProducto( idReajuste, idProducto, cantidad, esIncremento ) 
				VALUES ( _idReajuste, _idProducto, _cantidad, _esIncremento );
			
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

CREATE PROCEDURE consultaCuadreProducto( _action VARCHAR(20), _idCuadreProducto INT, _fechaCuadre DATE, _comentario TEXT, _todos BOOLEAN, _idUbicacion CHAR(1), _idEstadoCuadre INT )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062
		SELECT 'info' AS 'respuesta', 'Error, ya se ha realizado el cierre de este día' AS 'mensaje';
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cuadreProducto ( fechaCuadre, comentario, usuario, fechaRegistro, todos, idUbicacion, idEstadoCuadre )
			VALUES ( _fechaCuadre, _comentario, @usuario, NOW(), _todos, _idUbicacion, _idEstadoCuadre );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE cuadreProducto SET 
			fechaCuadre    = _fechaCuadre,
			comentario     = _comentario,
			idEstadoCuadre = _idEstadoCuadre
		WHERE idCuadreProducto = _idCuadreProducto;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSEIF _action = 'status' THEN
		UPDATE cuadreProducto SET idEstadoCuadre = _idEstadoCuadre
		WHERE idCuadreProducto = _idCuadreProducto;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaCuadreProductoDetalle( _action VARCHAR(20), _idCuadreProducto INT, _idProducto INT, _cantidadApertura DOUBLE(10,2), _cantidadCierre DOUBLE(10,2), _diferenciaApertura DOUBLE(10,2), _diferenciaCierre DOUBLE(10,2), _actualizarDisponibilidad BOOLEAN, _idEstadoCuadre INT, _comentarioApertura TEXT, _comentarioCierre TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cuadreProductoDetalle ( idCuadreProducto, idProducto, cantidadApertura, cantidadCierre, diferenciaApertura, diferenciaCierre, comentarioApertura )
			VALUES ( _idCuadreProducto, _idProducto, _cantidadApertura, _cantidadCierre, _diferenciaApertura, _diferenciaCierre, _comentarioApertura );

		# SI ACTUALIZA DISPONIBILIDAD DE PRODUCTO
		IF _actualizarDisponibilidad AND _idEstadoCuadre = 3 THEN
			UPDATE producto SET disponibilidad = _cantidadCierre WHERE idProducto = _idProducto;
		END IF;
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE cuadreProductoDetalle SET
			cantidadCierre   = _cantidadCierre,
			diferenciaCierre = _diferenciaCierre,
			comentarioCierre = _comentarioCierre
		WHERE idCuadreProducto = _idCuadreProducto AND idProducto = _idProducto;

		# SI ACTUALIZA DISPONIBILIDAD DE PRODUCTO
		IF _actualizarDisponibilidad THEN
			UPDATE producto SET disponibilidad = _cantidadCierre WHERE idProducto = _idProducto;
		END IF;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$




CREATE VIEW vCuadreProducto AS
SELECT 
	idCuadreProducto,
    fechaCuadre,
    comentario,
    usuario,
    fechaRegistro AS 'fechaRegistroCuadre',
    todos,
    u.idUbicacion,
    u.ubicacion,
    ec.idEstadoCuadre,
    ec.estadoCuadre
FROM cuadreProducto AS cp
	JOIN estadoCuadre AS ec
		ON ec.idEstadoCuadre = cp.idEstadoCuadre

	JOIN ubicacion AS u
		ON u.idUbicacion = cp.idUbicacion
;

CREATE VIEW vCuadreProductoDetalle AS
SELECT 
	cp.idCuadreProducto,
    cp.fechaCuadre,
    cp.comentario,
    cp.usuario,
    cp.fechaRegistroCuadre,
    cpd.cantidadApertura,
    cpd.cantidadCierre,
    cpd.diferenciaApertura,
    cpd.diferenciaCierre,
    cpd.comentarioApertura,
    cpd.comentarioCierre,
	p.*
FROM vCuadreProducto AS cp
	JOIN cuadreProductoDetalle AS cpd
		ON cp.idCuadreProducto = cpd.idCuadreProducto
	JOIN lstProducto AS p
		ON cpd.idProducto = p.idProducto
;


CREATE VIEW lstReajusteProducto AS
SELECT
	r.idReajuste,
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
	rp.cantidad,
	rp.esIncremento,
	r.observacion,
	r.usuario AS 'usuarioReajuste',
	r.fechaRegistro AS 'fechaReajuste'
FROM reajuste AS r
	JOIN reajusteProducto AS rp ON r.idReajuste = rp.idReajuste
	JOIN lstProducto AS p ON rp.idProducto = p.idProducto
;

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
    u.idUbicacion,
    u.ubicacion,
	p.usuario AS 'usuarioProducto',
	p.fechaRegistro AS 'fechaProducto'
FROM producto AS p
	JOIN medida AS m
		ON m.idMedida = p.idMedida
        
	JOIN tipoProducto AS tp
		ON tp.idTipoProducto = p.idTipoProducto
	
    JOIN ubicacion AS u
		ON u.idUbicacion = p.idUbicacion
;

CREATE VIEW lstIngresoProducto AS
SELECT
	i.idIngreso,
    i.idFacturaCompra,
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
    i.costo,
	i.usuario AS 'usuarioIngreso',
	i.fechaRegistro AS 'fechaIngreso'
FROM lstProducto AS p
	JOIN ingreso AS i ON i.idProducto = p.idProducto;


CREATE VIEW lstFacturaCompra AS
SELECT
	fc.idFacturaCompra,
	fc.noFactura,
	fc.proveedor,
	fc.fechaFactura,
	fc.comentario,
	ef.idEstadoFactura,
	ef.estadoFactura,
	fce.usuario,
	fce.fechaRegistro
FROM facturaCompra AS fc
	JOIN facturaCompraEstado AS fce
		ON fc.idFacturaCompra = fce.idFacturaCompra AND fc.idEstadoFactura = fce.idEstadoFactura
	JOIN estadoFactura AS ef 
		ON fc.idEstadoFactura = ef.idEstadoFactura;




