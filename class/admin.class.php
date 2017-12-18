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