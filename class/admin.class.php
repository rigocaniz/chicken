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
 		$this->con  = $conexion;
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


	function consultarModulosPerfil( $idModulo )
	{
		$idModulo = (int)$idModulo;
		$lstModulosA = array();

		$sql = "SELECT m.idModulo, m.modulo, m.habilitado, IF( ISNULL(pm.idPerfil) AND ISNULL(pm.idModulo), FALSE, TRUE ) AS asignado 
					FROM modulo AS m
						LEFT JOIN perfilModulo AS pm
							ON m.idModulo = pm.idModulo AND pm.idPerfil = {$idModulo}
					WHERE m.habilitado;";
		
		if( $rs = $this->con->query( $sql ) ){
			while ( $row = $rs->fetch_object() ){
				$row->asignado = (int)$row->asignado ? TRUE : FALSE;
				$lstModulosA[] = $row;
			}
		}

		return $lstModulosA;
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