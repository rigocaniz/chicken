<?php
/**
 * MODULOS
 */
class Modulo
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

 	function consultarModulosPerfil( $idPerfil, $esSession = FALSE )
	{
		$idPerfil = (int)$idPerfil;
		$lstModulosA = array();

		if( $esSession )
			$sesion = new Sesion();

		$sql = "SELECT m.idModulo, m.modulo, m.habilitado, IF( ISNULL(pm.idPerfil) AND ISNULL(pm.idModulo), FALSE, TRUE ) AS asignado 
					FROM modulo AS m
						LEFT JOIN perfilModulo AS pm
							ON m.idModulo = pm.idModulo AND pm.idPerfil = {$idPerfil}
					WHERE m.habilitado;";
		
		if( $rs = $this->con->query( $sql ) ){
			while ( $row = $rs->fetch_object() ){
				$row->asignado = (int)$row->asignado ? TRUE : FALSE;
				$lstModulosA[] = $row;

				if( $esSession )
					$sesion->setVariable( 'modulo_' . $row->idModulo, $row->asignado );
			}
		}

		return $lstModulosA;
	}

}

?>