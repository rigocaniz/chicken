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

	
	// CARGAR LISTA NIVELES
	function lstNiveles()
	{
		$lstNiveles = array();

		$sql = "SELECT * FROM nivel;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() )
				$lstNiveles[] = $row;
		}
		
		return $lstNiveles;
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
	function lstUsuarios()
	{

		$lstUsuarios = array();

		$sql = "SELECT * FROM usuario;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$lstUsuarios[] = $row;
			}
		}
		
		return $lstUsuarios;
	}



	function consultaUsuario()
	{

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
	function login( $usuario, $clave )
	{
		$validar = new Validar();

		$usuario = $this->con->real_escape_string( $validar->validarTexto( $usuario, NULL, TRUE, 8, 16, "USUARIO" ) );
		$clave   = $this->con->real_escape_string( $validar->validarTexto( $clave, NULL, TRUE, 6, 16, "CONTRASEÑA" ) );

		if( $validar->getIsError() ):
 			$this->respuesta = 'danger';
 			$this->mensaje   = $validar->getMsj();
 		else:

			$sql = "CALL login( '{$usuario}', '{$clave}' )";
			
			if( $rs = $this->con->query( $sql ) ){
				@$this->con->next_result();
				if( $row = $rs->fetch_object() ){
					$this->respuesta = $row->respuesta;
					$this->mensaje   = $row->mensaje;
					if( $this->respuesta == 'success' ){
						// 	CREAR SESION
						$sesion = new Sesion();
						$sesion->setVariable( 'nombre', $row->nombre );
						$sesion->setVariable( 'idNivel', (int)$row->idNivel );
						$sesion->setVariable( 'idPerfil', (int)$row->idPerfil );
						$sesion->setVariable( 'usuario', $usuario );
						header( "Location: index.php" );
					}
				}
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion (SP)';
			}
 		endif;

 		return $this->getRespuesta();
	}


	function getRespuesta()
 	{
 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo
 			);
 	}

}
?>