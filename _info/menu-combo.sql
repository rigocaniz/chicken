DELIMITER $$

CREATE FUNCTION codigoDuplicado( _codigo INT, _idMenu INT, _idCombo INT, _idSuperCombo INT )
RETURNS BOOLEAN
BEGIN
	DECLARE _response BOOLEAN DEFAULT TRUE;
	
    IF !ISNULL( _codigo ) AND _codigo > 0 THEN
		SELECT FALSE INTO _response FROM menu WHERE codigo = _codigo AND idMenu != IFNULL( _idMenu, 0 ) AND idEstadoMenu = 1 LIMIT 1;
        SELECT FALSE INTO _response FROM combo WHERE codigo = _codigo AND idCombo != IFNULL( _idCombo, 0 ) AND idEstadoMenu = 1 LIMIT 1;
        SELECT FALSE INTO _response FROM superCombo WHERE codigo = _codigo AND idSuperCombo != IFNULL( _idSuperCombo, 0 ) AND idEstadoMenu = 1 LIMIT 1;
    END IF;
    
    RETURN _response;
END$$

CREATE PROCEDURE consultaReceta( _action VARCHAR(20), _idMenu INT, _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Error, el producto ya existe en el menú ' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO receta ( idMenu, idProducto, cantidad, observacion ) 
			VALUES ( _idMenu, _idProducto, _cantidad, _observacion );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE receta SET 
			cantidad    = _cantidad,
			observacion = _observacion
		WHERE idMenu = _idMenu AND idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'delete' THEN
		DELETE FROM receta WHERE idMenu = _idMenu AND idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaMenu( _action VARCHAR(20), _idMenu INT, _menu VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, 
	_idEstadoMenu INT, _idDestinoMenu INT, _idTipoMenu INT, _codigo INT, _tiempoAlerta INT, _seCocina BOOLEAN )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO menu ( menu, imagen, descripcion, idEstadoMenu, idDestinoMenu, top, idTipoMenu, codigo, tiempoAlerta, seCocina ) 
				VALUES ( _menu, _imagen, _descripcion, _idEstadoMenu, _idDestinoMenu, 0, _idTipoMenu, _codigo, _tiempoAlerta, _seCocina );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, _idMenu, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
            
        ELSE
			UPDATE menu SET 
				menu          = _menu, 
				descripcion   = _descripcion, 
				idEstadoMenu  = _idEstadoMenu, 
				idDestinoMenu = _idDestinoMenu,
				idTipoMenu    = _idTipoMenu,
				codigo 		  = _codigo,
                tiempoAlerta  = _tiempoAlerta,
                seCocina 	  = _seCocina
			WHERE idMenu = _idMenu;
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE menu SET imagen = _imagen WHERE idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaMenuPrecio( _action VARCHAR(20), _idMenu INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062 BEGIN END;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO menuPrecio ( idMenu, idTipoServicio, precio ) 
			VALUES ( _idMenu, _idTipoServicio, _precio ) 
            ON DUPLICATE KEY UPDATE precio =  _precio;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM menuPrecio
			WHERE idMenu = _idMenu AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaCombo( _action VARCHAR(20), _idCombo INT, _combo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, _codigo INT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO combo ( combo, imagen, descripcion, idEstadoMenu, top, codigo ) 
				VALUES ( _combo, _imagen, _descripcion, _idEstadoMenu, 0, _codigo );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, NULL, _idCombo, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			UPDATE combo SET
				combo        = _combo,
				descripcion  = _descripcion,
				idEstadoMenu = _idEstadoMenu,
				codigo 		 = _codigo
			WHERE idCombo = _idCombo;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE combo SET imagen = _imagen WHERE idCombo = _idCombo;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
    
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaComboPrecio( _action VARCHAR(20), _idCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062 BEGIN END;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO comboPrecio ( idCombo, idTipoServicio, precio ) 
			VALUES ( _idCombo, _idTipoServicio, _precio )
            ON DUPLICATE KEY UPDATE precio =  _precio;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM comboPrecio
			WHERE idCombo = _idCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaComboDetalle( _action VARCHAR(20), _idCombo INT, _idMenu INT, _cantidad DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Menu ya registrado para este combo' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO comboDetalle ( idCombo, idMenu, cantidad ) 
			VALUES ( _idCombo, _idMenu, _cantidad );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE comboDetalle SET cantidad = _cantidad
			WHERE idCombo = _idCombo AND idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM comboDetalle WHERE idCombo = _idCombo AND idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaSuperCombo( _action VARCHAR(20), _idSuperCombo INT, _superCombo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, codigo INT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO superCombo ( superCombo, imagen, descripcion, idEstadoMenu, top, codigo ) 
				VALUES ( _superCombo, _imagen, _descripcion, _idEstadoMenu, 0, _codigo );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, _idSuperCombo ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			UPDATE superCombo SET
				superCombo   = _superCombo,
				descripcion  = _descripcion,
				idEstadoMenu = _idEstadoMenu,
				codigo 		 = _codigo
			WHERE idSuperCombo = _idSuperCombo;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE superCombo SET imagen = _imagen WHERE idSuperCombo = _idSuperCombo;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaSuperComboPrecio( _action VARCHAR(20), _idSuperCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Precio para Tipo de Servicio ya registrado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO superComboPrecio ( idSuperCombo, idTipoServicio, precio ) 
			VALUES ( _idSuperCombo, _idTipoServicio, _precio );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE superComboPrecio SET precio = _precio
			WHERE idSuperCombo = _idSuperCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM superComboPrecio 
			WHERE idSuperCombo = _idSuperCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$

CREATE PROCEDURE consultaSuperComboDetalle( _action VARCHAR(20), _idSuperCombo INT, _idCombo INT, _cantidad DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Combo ya registrado para este Super Combo' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO superComboDetalle ( idSuperCombo, idCombo, cantidad ) 
			VALUES ( _idSuperCombo, _idCombo, _cantidad );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE superComboDetalle SET cantidad = _cantidad
			WHERE idSuperCombo = _idSuperCombo AND idCombo = _idCombo;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM superComboDetalle WHERE idSuperCombo = _idSuperCombo AND idCombo = _idCombo;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END$$




CREATE VIEW lstMenu AS
SELECT
	m.idMenu,
	m.menu,
	IFNULL( m.imagen, '' )AS 'imagen',
	m.descripcion,
	em.idEstadoMenu,
	em.estadoMenu,
	dm.idDestinoMenu,
	dm.destinoMenu,
	tm.idTipoMenu,
	tm.tipoMenu,
    m.codigo AS 'codigoMenu',
    m.tiempoAlerta,
    m.seCocina
FROM menu AS m
	JOIN estadoMenu AS em
		ON em.idEstadoMenu = m.idEstadoMenu
	JOIN destinoMenu AS dm
		ON dm.idDestinoMenu = m.idDestinoMenu
	JOIN tipoMenu AS tm
		ON m.idTipoMenu = tm.idTipoMenu
ORDER BY m.top DESC;


CREATE VIEW lstReceta AS
SELECT
	r.idMenu,
	p.idProducto,
	p.producto,
	r.cantidad,
	p.medida,
	p.tipoProducto,
	r.observacion
FROM receta AS r
	JOIN lstProducto AS p
		ON p.idProducto = r.idProducto;


CREATE VIEW lstMenuPrecio AS
SELECT
	mp.idMenu,
	mp.precio,
	ts.idTipoServicio,
	ts.tipoServicio
FROM menuPrecio AS mp
	JOIN tipoServicio AS ts
		ON mp.idTipoServicio = ts.idTipoServicio;


CREATE VIEW lstCombo AS
SELECT
	c.idCombo,
	c.combo,
	IFNULL( c.imagen, '' )AS 'imagen',
	c.descripcion,
	em.idEstadoMenu,
	em.estadoMenu,
    c.codigo AS 'codigoCombo'
FROM combo AS c
	JOIN estadoMenu AS em
		ON c.idEstadoMenu = em.idEstadoMenu
ORDER BY c.top DESC;


CREATE VIEW lstComboDetalle AS
SELECT
	cd.idCombo,
	cd.cantidad,
	m.idMenu,
	m.menu,
	m.imagen,
	m.descripcion,
	m.idEstadoMenu,
	m.estadoMenu
FROM comboDetalle AS cd
	JOIN lstMenu AS m
		ON m.idMenu = cd.idMenu;


CREATE VIEW lstSuperComboPrecio AS
SELECT
	scp.idSuperCombo,
	scp.precio,
	ts.idTipoServicio,
	ts.tipoServicio
FROM superComboPrecio AS scp
	JOIN tipoServicio AS ts
		ON scp.idTipoServicio = ts.idTipoServicio;


CREATE VIEW lstComboPrecio AS
SELECT
	cp.idCombo,
	cp.precio,
	ts.idTipoServicio,
	ts.tipoServicio
FROM comboPrecio AS cp
	JOIN tipoServicio AS ts
		ON cp.idTipoServicio = ts.idTipoServicio;


CREATE VIEW lstSuperCombo AS
SELECT
	sc.idSuperCombo,
	sc.superCombo,
	IFNULL( sc.imagen, '' )AS 'imagen',
	sc.descripcion,
	em.idEstadoMenu,
	em.estadoMenu,
    sc.codigo AS 'codigoSuperCombo'
FROM superCombo AS sc
	JOIN estadoMenu AS em
		ON sc.idEstadoMenu = em.idEstadoMenu
ORDER BY sc.top DESC;


CREATE VIEW lstSuperComboDetalle AS
SELECT
	scd.idSuperCombo,
	scd.cantidad,
	c.idCombo,
	c.combo,
	c.imagen,
	c.descripcion,
	c.idEstadoMenu,
	c.estadoMenu
FROM superComboDetalle AS scd
	JOIN lstCombo AS c
		ON c.idCombo = scd.idCombo;

