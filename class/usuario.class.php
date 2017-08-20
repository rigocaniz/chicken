<?php 
/**
* USUARIO
*/
class Usuario
{
	private $con       = NULL;
	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;

	function __construct()
	{
		GLOBAL $conexion;

		if( is_null( $this->con ) )
 			$this->con = $conexion;
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