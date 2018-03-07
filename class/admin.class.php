<?php 
/**
* ADMINISTRACION
*/
class Admin
{
	
	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $error     = FALSE;
 	private $sess      = NULL;
 	private $con       = NULL;
 	private $data      = array();

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		
 		if( is_null( $this->con ) )
 			$this->con  = $conexion;
 		
 		if( is_null( $this->sess ) )
 			$this->sess = $sesion;
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


	// CARGAR LISTA PERFILES
	function consultaPerfil( $accion, $data )
	{
		$validar = new Validar();

		$idPerfil = "NULL";
		if( $accion == 'update' ):
 			$idPerfil = (int)$data->idPerfil > 0 ? (int)$data->idPerfil : NULL;
 			$idPerfil = $validar->validarNumero( $data->idPerfil, NULL, TRUE, 1 );
 		endif;

 		$perfil = $this->con->real_escape_string( $validar->validarTexto( $data->perfil, NULL, TRUE, 3, 45, 'el Perfil' ) );

 		if( $validar->getIsError() ): 			
 			$this->respuesta = 'warning';
 			$this->mensaje   = $validar->getMsj();
 			$this->tiempo    = $validar->getTiempo();

 		else:

			$sql = "INSERT INTO perfil ( idPerfil, perfil )
						VALUES( {$idPerfil}, '{$perfil}' )
							ON DUPLICATE KEY UPDATE
								perfil = '{$perfil}';";
			
			if( $rs = $this->con->query( $sql ) )
			{
				$this->respuesta = 'success';
				$this->mensaje = 'Guardado correctamente';
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje = 'Error al ejecutar la instrucción';
			}

 		endif;

		return $this->getRespuesta();
	}


	function asignarModulo( $idPerfil, $idModulo, $asignado )
	{
		$asignado = (int)$asignado;
		
		if( $asignado )
			$sql = "DELETE FROM perfilModulo WHERE idPerfil = {$idPerfil} AND idModulo = {$idModulo};";
		else
			$sql = "INSERT IGNORE INTO perfilModulo VALUES ( {$idPerfil}, {$idModulo});";
		
		if( $rs = $this->con->query( $sql ) ):
			$this->respuesta = 'success';
			$this->mensaje = 'Guardado';
			$this->tiempo = 2;
		else:
			$this->respuesta = 'danger';
			$this->mensaje = 'Error al ejecutar la operacion';
		endif;

		return $this->getRespuesta();
	}


	private function getRespuesta()
 	{
 		return array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo
 			);
 	}


}
?>