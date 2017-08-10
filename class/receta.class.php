<?php 
/**
* RECETAS
*/
class Receta
{
	
 	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $error     = FALSE;
 	private $sess      = NULL;
 	private $con       = NULL;
 	private $data      = NULL;

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}

 	
	// CONSULTAR LISTA DE COMBOS
 	function lstReceta()
 	{
 		$lstReceta = array();

 		$sql = "SELECT idMenu, idProducto, producto, cantidad, medida, tipoProducto, observacion FROM lstReceta;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstReceta[] = $row;
 			}
 		}

 		return $lstReceta;
 	}


 	// CONSULTAR DATOS RECETA
	function cargarReceta( $idMenu, $idProducto )
	{
		$idMenu     = (int)$idMenu;
		$idProducto = (int)$idProducto;
		$receta   = array();

		$sql = "SELECT 
				    idMenu,
				    idProducto,
				    cantidad,
				    observacion
				FROM
				    lstReceta
				WHERE
				    idMenu = {$idMenu} AND idProducto = {$idProducto};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$receta = $row;
		}

		return $receta;
	}
	



 	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
 	{

 		if( $this->respuesta == 'success' )
 			$this->tiempo = 4;
 		elseif( $this->respuesta == 'warning')
 			$this->tiempo = 5;
 		elseif( $this->respuesta == 'danger')
 			$this->tiempo = 7;

 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}

}
?>