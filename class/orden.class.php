<?php
/**
* ORDEN
*/
class Orden
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

 	function guardarOrdenCliente()
 	{
 		$sql = "";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			
 		}
 		else{
 			$this->respuesta = 'danger';
 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
 		}
 		
 	}


 	function guardarDetalleOrden( $idOrdenCliente, $lstDetalleOrden )
 	{
 		if( count( $lstDetalleOrden ) ):

 			foreach ($lstDetalleOrden AS $ixDetalleOrden ):

 				$sql = "";
 				
 				if( $rs = $this->con->query( $sql ) ){
 					if( $row = $rs->fetch_object() ){
 						$this->respuesta = $row->respuesta;
 						$this->mensaje   = $row->mensaje;

 					}
 				}
 				else{
 					$this->respuesta = 'danger';
 					$this->mensaje   = 'Error al ejecutar la operacion (SP)';
 				}

 				if( $this->respuesta == 'danger' )
 					break;
 				
 			endforeach;

 		else:
 			$this->respuesta = 'danger';
 			$this->mensaje = 'No ha agregado ningun producto a la orden.';
 		endif;
 	}


 	function getRespuesta()
 	{
 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}


}
?>