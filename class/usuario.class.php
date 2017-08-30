<?php 
/**
* USUARIO
*/
class Usuario
{
	private $con       = NULL;
	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 5;

	function __construct()
	{
		GLOBAL $conexion;

		if( is_null( $this->con ) )
 			$this->con = $conexion;
	}

	// LIBERAR SIGUIENTE RESULTADO
 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}

	// CARGAR LISTA ESTADOS USUARIO
	function lstEstadoUsuario()
	{
		$lstEstadoUsuario = array();

		$sql = "SELECT * FROM estadoUsuario;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() )
				$lstEstadoUsuario[] = $row;
		}
		
		return $lstEstadoUsuario;
	}

	
	// CARGAR LISTA PERFILES
	function lstPerfiles()
	{
		$lstPerfiles = array();

		$sql = "SELECT * FROM perfil;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() )
				$lstPerfiles[] = $row;
		}
		
		return $lstPerfiles;
	}


	// OBTENER LISTA DE USUARIOS
	function lstUsuarios( $filtro = 'activos' )
	{

		$lstUsuarios = array();
		$where = "";

		if( $filtro == 'activos' )
			$where .= "WHERE idEstadoUsuario = 1";
		else if( $filtro == 'bloqueados' )
			$where .= "WHERE idEstadoUsuario = 2";
		else if( $filtro == 'deshabilitados' )
			$where .= "WHERE idEstadoUsuario = 3";

		$sql = "SELECT * FROM vUsuario $where ;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() )
				$lstUsuarios[] = $row;
		}
		
		return $lstUsuarios;
	}


 	// CONSULTA USUARIO => INSERT / UPDATE
 	function consultaUsuario( $accion, $data )
 	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$usuario   = "NULL";
		$codigo    = "NULL";
		$nombres   = "NULL";
		$apellidos = "NULL";
		$idPerfil  = "NULL";

		// SETEO VARIABLES GENERALES
 		$data->idPerfil        = isset( $data->idPerfil )			? (int)$data->idPerfil 			: NULL;
 		$data->usuario         = isset( $data->usuario )			? (string)$data->usuario 		: NULL;
 		$data->codigo          = isset( $data->codigo )				? (int)$data->codigo 			: NULL;
 		$data->nombres         = isset( $data->nombres )			? (string)$data->nombres 		: NULL;
 		$data->apellidos       = isset( $data->apellidos )			? (string)$data->apellidos 		: NULL;

 		$data->idPerfil        = (int)$data->idPerfil > 0			? (int)$data->idPerfil 			: NULL;
 		$data->usuario         = strlen( $data->usuario ) >= 1		? (string)$data->usuario 		: NULL;
 		$data->codigo          = (int)$data->codigo > 0				? (int)$data->codigo 			: NULL;
 		$data->nombres         = strlen( $data->nombres ) >= 3		? (string)$data->nombres 		: NULL;
 		$data->apellidos       = strlen( $data->apellidos ) >= 2	? (string)$data->apellidos 		: NULL;

 		// VALIDACIONES
		$idPerfil  = $validar->validarEntero( $data->idPerfil, NULL, TRUE, 'El perfil no es válido' );
		$usuario   = $this->con->real_escape_string( $validar->validarTexto( $data->usuario, NULL, TRUE, 8, 16, "USUARIO" ) );
		$codigo    = $validar->validarEntero( $data->codigo, NULL, TRUE, 'El código del usuario no es válido' );
		$nombres   = $this->con->real_escape_string( $validar->validarTexto( $data->nombres, NULL, TRUE, 3, 65, 'el nombre' ) );
		$apellidos = $this->con->real_escape_string( $validar->validarTexto( $data->apellidos, NULL, TRUE, 3, 65, 'los apellidos' ) );

 		// OBTENER RESULTADOS
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();

 		else:
	 		$sql = "CALL consultaUsuario( '{$accion}', '{$usuario}', {$codigo}, '{$nombres}', '{$apellidos}', {$idPerfil} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
	 			
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;

 				if( $this->respuesta == 'success' )
 					$this->tiempo = 2;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


	// ACTUALIZAR ESTADO DEL USUARIO
	function actualizarEstadoUsuario( $data )
	{
		$validar = new Validar();

		$data->idEstadoUsuario = isset( $data->idEstadoUsuario ) ? (int)$data->idEstadoUsuario  : NULL;
		$data->usuario         = isset( $data->usuario )		 ? (string)$data->usuario 		: NULL;
		$data->usuario         = strlen( $data->usuario ) > 1    ? (string)$data->usuario 		: NULL;

		$usuario  = $this->con->real_escape_string( $validar->validarTexto( $data->usuario, NULL, TRUE, 8, 16, "USUARIO" ) );
		$idEstadoUsuario = $validar->validarEntero( $data->idEstadoUsuario, NULL, TRUE, 'El Estado del usuario no es válido' );

		if( $validar->getIsError() ):
 			$this->respuesta = 'danger';
 			$this->mensaje   = $validar->getMsj();
 		else:

			$sql = "CALL actualizarEstadoUsuario( '{$usuario}', {$idEstadoUsuario} );";

			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
				$this->siguienteResultado();

				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				if( $this->respuesta == 'success' )
 					$this->tiempo = 3;
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion de reseteo (USUARIO)';
			}
 		endif;

 		return $this->getRespuesta();
	}
	

	// RESETEAR CLAVE
	function resetearClave( $usuario )
	{
		$validar = new Validar();

		$usuario = $this->con->real_escape_string( $validar->validarTexto( $usuario, NULL, TRUE, 8, 16, "USUARIO" ) );

		if( $validar->getIsError() ):
 			$this->respuesta = 'danger';
 			$this->mensaje   = $validar->getMsj();
 		else:

 			$usuario = strtolower( $usuario );
			$sql = "CALL resetearClave( '{$usuario}' )";
			
			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
				$this->siguienteResultado();

				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				if( $this->respuesta == 'success' )
					$this->tiempo = 3;
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion de reseteo (USUARIO)';
			}
 		endif;

 		return $this->getRespuesta();
	}


	// LOGIN USUARIO
	function login( $usuario, $clave, $codigo = 'NULL' )
	{
		$validar = new Validar();
		$sesion = new Sesion();

		$clave = $this->con->real_escape_string( $validar->validarTexto( $clave, NULL, TRUE, 4, 16, "CONTRASEÑA" ) );

		if ( $codigo === 'NULL' )
			$usuario = $this->con->real_escape_string( $validar->validarTexto( $usuario, NULL, TRUE, 8, 16, "USUARIO" ) );

		else
			$codigo = $this->con->real_escape_string( $validar->validarNumero( $codigo, NULL, TRUE, 1, 4, "CODIGO" ) );

		if( $validar->getIsError() ):
 			$this->respuesta = 'danger';
 			$this->mensaje   = $validar->getMsj();
 		else:

			$sql = "CALL login( '{$usuario}', '{$clave}', {$codigo} );";
			
			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
				$this->siguienteResultado();

				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				if( $this->respuesta == 'success' ){
					// 	CREAR SESION
					$sesion->setVariable( 'nombre', $row->nombre );
					$sesion->setVariable( 'nombreCorto', $row->nombreCorto );
					$sesion->setVariable( 'idPerfil', (int)$row->idPerfil );
					$sesion->setVariable( 'usuario', $row->usuario );
					$sesion->setVariable( 'codigoUsuario', $row->codigoUsuario );
					
					if ( $codigo === 'NULL' )
						header( "Location: ./" );
				}
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion (SP)';
			}
 		endif;

		if ( $codigo === 'NULL' )
 			return $this->getRespuesta();

 		else
 			return array_merge(
 				$this->getRespuesta(),
 				array( 'usuario' => $this->getUsuario( $sesion->getUsuario() ) )
 			);
	}

	// CONSULTA INFORMACION DE USUARIO
	function getUsuario( $usuario )
	{
		$user = NULL;

		$sql = "SELECT
					u.usuario,
				    u.codigo,
				    u.nombres,
				    u.apellidos,
				    u.idEstadoUsuario,
				    u.estadoUsuario,
				    u.idPerfil,
				    u.perfil,
				    dm.idDestinoMenu,
				    dm.destinoMenu
				FROM vUsuario AS u
					LEFT JOIN destinoMenuUsuario AS dmu
						ON u.usuario = dmu.usuario
					LEFT JOIN destinoMenu AS dm
						ON dmu.idDestinoMenu = dm.idDestinoMenu
				WHERE u.usuario = '{$usuario}' ";
			
		$rs = $this->con->query( $sql );

		while( $rs AND $row = $rs->fetch_object() ){
			if ( is_null( $user ) )
				$user = (object)array(
					'usuario'         => $row->usuario,
					'codigo'          => $row->codigo,
					'nombres'         => $row->nombres,
					'apellidos'       => $row->apellidos,
					'idEstadoUsuario' => $row->idEstadoUsuario,
					'estadoUsuario'   => $row->estadoUsuario,
					'idPerfil'        => $row->idPerfil,
					'perfil'          => $row->perfil,
					'lstDestinos'     => array(),
				);
			
			$user->lstDestinos[] = (object)array(
				'idDestinoMenu' => $row->idDestinoMenu,
				'destinoMenu'   => $row->destinoMenu,
			);
		}
		
		return $user;
	}


	function getRespuesta()
 	{
 		return array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo
 			);
 	}

}
?>