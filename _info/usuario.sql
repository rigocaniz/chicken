DELIMITER $$

CREATE PROCEDURE definirSesion( _usuario varchar(15) )
BEGIN
	
    SET @usuario = NULL, @idPerfil = NULL, @isAdmin = NULL, @idDestinoMenu= NULL;
    
    SELECT usuario, idPerfil, idDestinoMenu INTO @usuario, @idPerfil, @idDestinoMenu
		FROM usuario WHERE usuario = _usuario AND idEstadoUsuario = 1;
END$$

CREATE FUNCTION sesionValida() RETURNS BOOLEAN
BEGIN
	IF !ISNULL( @usuario ) AND !ISNULL( @idPerfil ) THEN
		RETURN TRUE;
    ELSE
		RETURN FALSE;
    END IF;
END$$

CREATE PROCEDURE consultaUsuario( _action VARCHAR(15), _usuario varchar(15), _codigo INT(4), _nombres VARCHAR(65), _apellidos VARCHAR(65), _idPerfil INT, _idDestinoMenu INT )
BEGIN

	# IF DUPLICATE
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Usuario o Código Duplicado' AS 'mensaje';

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO usuario
			(usuario, codigo, clave, nombres, apellidos, fechaRegistro, idEstadoUsuario, idPerfil, usuarioRegistro, idDestinoMenu ) 
		VALUES
			(_usuario, _codigo, _usuario, _nombres, _apellidos, now(), 1, _idPerfil, @usuario, _idDestinoMenu );
			
		INSERT INTO log_estadoUsuario (idEstadoUsuario, usuario, usuarioRegistro, fechaRegistro)
		VALUES (1, _usuario, @usuario, now());
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'update' THEN
		UPDATE usuario SET
			codigo 		  = _codigo,
            nombres 	  = _nombres,
            apellidos 	  = _apellidos,
            idPerfil 	  = _idPerfil,
            idDestinoMenu = _idDestinoMenu
		WHERE usuario = _usuario;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE actualizarEstadoUsuario( _usuario varchar(15), _idEstadoUsuario INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1452
        SELECT 'danger' AS 'respuesta', 'Dato No Existe' AS 'mensaje';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF sesionValida() THEN
        IF _usuario != @usuario THEN
			UPDATE usuario SET idEstadoUsuario = _idEstadoUsuario where usuario = _usuario;
			
			INSERT INTO log_estadoUsuario (idEstadoUsuario, usuario, usuarioRegistro, fechaRegistro)
			VALUES (1, _usuario, @usuario, now());
            
            SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
            
		ELSE
			SELECT 'warning' AS 'respuesta', 'No es posible cambiar el estado a su propio usuario' AS 'mensaje';
		END IF;
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Sesión no válida o acceso denegado' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE login( _usuario varchar(15), _clave varchar(75), _codigo INT )
BEGIN
	# DECLARE VARS
	DECLARE _sesionValida int default 0;
    DECLARE _nombre varchar(90) default '';
    DECLARE _nombreCorto varchar(35) default '';
    DECLARE _idPerfil INT;
    DECLARE _idDestinoMenu INT;
    DECLARE _codigoUsuario INT;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	END;
    
    # VALIDATE USER&PASSWORD
    SELECT 
		IF( idEstadoUsuario = 1, 1, -1 ), usuario, CONCAT(nombres, ' ', apellidos), idPerfil, codigo, nombres, idDestinoMenu
			INTO 
        _sesionValida, _usuario, _nombre, _idPerfil, _codigoUsuario, _nombreCorto, _idDestinoMenu
	FROM usuario 
    WHERE IF( ISNULL( _codigo ), usuario = _usuario, codigo = _codigo ) 
		AND clave = md5( _clave );
    
    # IF SESSION IS VALID
    IF _sesionValida = 1 THEN
		IF _usuario = _clave THEN
			SELECT 'warning' AS 'respuesta', 'Debe de Cambiar su Contraseña antes de ingresar' AS 'mensaje';
		ELSE
			SELECT 'success' AS 'respuesta', 'Autenticado correctamente' AS 'mensaje', _nombre AS 'nombre', 
				_idPerfil AS 'idPerfil', _codigoUsuario AS 'codigoUsuario', _nombreCorto AS 'nombreCorto', _usuario AS 'usuario', _idDestinoMenu AS 'idDestinoMenu';

			INSERT INTO historialAutenticacion (usuario, fechaRegistro, idTipoRespuesta) 
				VALUES(_usuario, now(), 1);
        END IF;
	# IF SESSION IS VALID
    ELSEIF _sesionValida = -1 THEN
		SELECT 'danger' AS 'respuesta', 'Usuario Bloqueado' AS 'mensaje';
        INSERT INTO historialAutenticacion (usuario, fechaRegistro, idTipoRespuesta) 
			VALUES(_usuario, now(), 3);
	# IF SESSION IS NOT VALID
    ELSE
		SELECT 'danger' AS 'respuesta', 'Usuario/Password no valido' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE cambiarClaveUsuario(_usuario varchar(15), _clave varchar(75), _nuevaClave varchar(75))
BEGIN
	# DECLARE VARS
	DECLARE claveAnteriorValida INT DEFAULT 0;
    
    # VALIDATE USER&PASSWORD
    SELECT 1 INTO claveAnteriorValida
		FROM usuario WHERE usuario = _usuario AND clave = md5( _clave );
    
    # IF SESSION IS VALID
    IF claveAnteriorValida = 1 THEN
		UPDATE usuario SET clave = md5( _nuevaClave ) 
			WHERE usuario = _usuario;
            
        SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Clave anterior no válida' AS 'mensaje';
    END IF;
end$$

CREATE PROCEDURE resetearClave( _usuario varchar(15) )
BEGIN
	IF sesionValida() THEN    
		UPDATE usuario SET clave = md5( _usuario ) where usuario = _usuario;

		SELECT 'success' AS 'respuesta', 'Usuario reseteado correctamente' AS 'mensaje';
        
    ELSE
		SELECT 'danger' AS 'respuesta', 'Sesión no válida o acceso denegado' AS 'mensaje';
    END IF;
end$$



CREATE OR REPLACE VIEW vUsuario AS
SELECT
	u.usuario,
    u.codigo,
    u.nombres,
    u.apellidos,
    u.fechaRegistro,
    eu.idEstadoUsuario,
    eu.estadoUsuario,
    p.idPerfil,
    p.perfil,
    u.idDestinoMenu
FROM usuario AS u
	JOIN estadoUsuario AS eu
		ON u.idEstadoUsuario = eu.idEstadoUsuario
	JOIN perfil AS p
		ON p.idPerfil = u.idPerfil;

