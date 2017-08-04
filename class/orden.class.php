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

 	
 	// CONSULTA ORDEN ACCIONES
 	function consultaOrdenCliente( $accion, $data )
 	{

 		// INICIALIZACIÓN DE VARIABLES
		$idOrdenCliente     = "NULL";
		$numeroTicket       = "NULL";
		$usuarioResponsable = "NULL";
		$idEstadoOrden      = "NULL";

		// SETEO DE VARIABLES
		$data->numeroTicket       = (int)$data->numeroTicket > 0			? (int)$data->numeroTicket	 : "NULL";
		$data->usuarioResponsable = strlen( $data->usuarioResponsable ) > 3	? $data->usuarioResponsable	 : "NULL";

		$validar = new Validar();

		// VALIDACIONES
		if( $accion == 'insert' ):
			// OBLIGATORIOS
			$numeroTicket       = $validar->validarEntero( $data->numeroTicket, NULL, TRUE, 'El No. de Ticket no es válido' );
			$usuarioResponsable = "'" . $this->con->real_escape_string( $validar->validarTexto( $data->usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) ) . "'";
		
		else:

			// SETEO DE VARIABLES
			$data->idOrdenCliente = (int)$data->idOrdenCliente > 0	? (int)$data->idOrdenCliente : "NULL";
			$data->idEstadoOrden  = (int)$data->idEstadoOrden > 0	? (int)$data->idEstadoOrden	 : "NULL";

			// OBLIGATORIOS
			$idOrdenCliente = $validar->validarEntero( $data->idOrdenCliente, NULL, TRUE, 'El No. de Orden no es válido' );

			if( $accion == 'update' ):
				$numeroTicket       = $validar->validarEntero( $data->numeroTicket, NULL, TRUE, 'El No. de Ticket es válido' );
				$usuarioResponsable = "'" . $this->con->real_escape_string( $validar->validarTexto( $data->usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) ) . "'";
			
			elseif( $accion == 'status' ):
				$idEstadoOrden = $validar->validarEntero( $data->idEstadoOrden, NULL, TRUE, 'El ID del estado de Orden no es válido' );

			endif;

		endif;


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:
	 		$sql = "CALL consultaOrdenCliente( '{$accion}', {$idOrdenCliente}, {$numeroTicket}, {$usuarioResponsable}, {$idEstadoOrden} )";
	 		
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->respuesta = $row->respuesta;
	 			$this->mensaje   = $row->mensaje;
	 			if( $accion == 'insert' AND $row->respuesta == 'success' )
	 				$this->data = (int)$row->id;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}

 		endif;

 		return $this->getRespuesta();
 	}


 	function consultaDetalleOrdenMenu( $accion, $data )
 	{
 		
 		// INICIALIZACIÓN DE VARIABLES
		$idDetalleOrdenMenu   = "NULL";
		$idOrdenCliente       = "NULL";
		$idMenu               = "NULL";
		$cantidad             = "NULL";
		$idEstadoDetalleOrden = "NULL";
		$idTipoServicio       = "NULL";
		$idFactura            = "NULL";
		$usuarioResponsable   = "NULL";

		// SETEO DE VARIABLES
		$data->idDetalleOrdenMenu   = (int)$data->idDetalleOrdenMenu > 0 		? (int)$data->idDetalleOrdenMenu	: "NULL";
		$data->idOrdenCliente       = (int)$data->idOrdenCliente > 0			? (int)$data->idOrdenCliente		: "NULL";
		$data->idMenu               = (int)$data->idMenu > 0					? (int)$data->idMenu				: "NULL";
		$data->cantidad             = (int)$data->cantidad > 0					? (int)$data->cantidad				: "NULL";
		$data->idEstadoDetalleOrden = (int)$data->idEstadoDetalleOrden > 0		? (int)$data->idEstadoDetalleOrden	: "NULL";
		$data->idTipoServicio       = (int)$data->idTipoServicio > 0			? (int)$data->idTipoServicio		: "NULL";
		$data->idFactura            = (int)$data->idFactura > 0					? (int)$data->idFactura				: "NULL";
		$data->usuarioResponsable   = strlen( $data->usuarioResponsable ) > 3	? $data->usuarioResponsable			: "NULL";

		$validar = new Validar();

		// VALIDACIONES
		if( $accion == 'insert' ):
			$idDetalleOrdenMenu   = "NULL";
			$idEstadoDetalleOrden = "NULL";
			$idFactura            = "NULL";

			// OBLIGATORIOS
			$idOrdenCliente     = $validar->validarEntero( $data->idOrdenCliente, NULL, TRUE, 'El No. de la Orden del cliente no es válida' );
			$idMenu             = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El No. del Menu de la Orden no es válido' );
			$cantidad           = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );
			$idTipoServicio     = $validar->validarEntero( $data->idTipoServicio, NULL, TRUE, 'El No. del Tipo de Servicio no es válido' );
			$usuarioResponsable = $this->con->real_escape_string( $validar->validarTexto( $usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) );

		else:
			$idDetalleOrdenMenu = $validar->validarEntero( $data->idDetalleOrdenMenu, NULL, TRUE, 'El No. del Detalle de Orden no es válido' );

			if( $accion == 'menu-cantidad' ):
				$idMenu   = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El No. del Menu de la Orden no es válido' );
				$cantidad = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );

			elseif( $accion == 'estado' ):
				$idEstadoDetalleOrden = $validar->validarEntero( $data->idEstadoDetalleOrden, NULL, TRUE, 'El No. del Estado de Orden no es válido' );

			elseif( $accion == 'responsable' ):
				$usuarioResponsable = $this->con->real_escape_string( $validar->validarTexto( $usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) );

			elseif( $accion == 'factura' ):
				$idFactura = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El No. de Factura no es válido' );

			elseif( $accion == 'tipoServicio' ):
				$idTipoServicio = $validar->validarEntero( $data->idTipoServicio, NULL, TRUE, 'El No. del Tipo de Servicio no es válido' );

			elseif( $accion == 'asignarOtroCliente' ):
				$usuarioResponsable = $this->con->real_escape_string( $validar->validarTexto( $usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) );
			endif;
		endif;


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:

	 		$sql = "CALL consultaDetalleOrdenMenu( '{$accion}', {$idDetalleOrdenMenu}, {$idMenu}, {$cantidad}, {$idEstadoDetalleOrden}, {$idTipoServicio}, {$idFactura}, {$usuarioResponsable} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
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