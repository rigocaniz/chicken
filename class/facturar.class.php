<?php 
/**
* FACTURACIÓN
*/
class Factura
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

 	// CONSULTA FACTURACION => INSERT / UPDATE
	function consultaFacturaCliente( $accion, $data )
	{
		$validar = new Validar();
		$caja = new Caja();

		if( $caja->consultarEstadoCaja()->idEstadoCaja == 1 ):
			//var_dump( $data );
			// INICIALIZACIÓN VAR
			$idFactura       = 'NULL';
			$idEstadoFactura = 'NULL';
			$idCliente       = 'NULL';
			$idCaja          = 'NULL';
			$nombre          = 'NULL';
			$direccion       = 'NULL';
	 		
	 		var_dump( $data->datosCliente );

			// SETEO VARIABLES
	 		$data->idEstadoFactura           = isset( $data->idEstadoFactura ) 	? (int)$data->idEstadoFactura 	: NULL;
	 		$data->idEstadoFactura           = (int)$data->idEstadoFactura > 0 	? (int)$data->idEstadoFactura 	: NULL;

	 		$data->datosCliente->idCliente 	 = isset( $data->datosCliente->idCliente ) 	? (int)$data->datosCliente->idCliente 	: NULL;
	 		$data->datosCliente->idCliente   = (int)$data->datosCliente->idCliente > 0 	? (int)$data->datosCliente->idCliente 	: NULL;

	 		//$data->idCaja                  = isset( $data->idCaja ) 			? (int)$data->idCaja 			: NULL;
	 		//$data->idCaja                  = (int)$data->idCaja > 0 			? (int)$data->idCaja 			: NULL;

	 		$data->datosCliente->nombre      = isset( $data->datosCliente->nombre ) 		? (string)$data->datosCliente->nombre 	: NULL;
	 		$data->nombre      = strlen( $data->datosCliente->nombre ) > 1	? (string)$data->datosCliente->nombre 	: NULL;

	 		$data->datosCliente->direccion   = isset( $data->datosCliente->direccion ) 		? (string)$data->datosCliente->direccion 	: NULL;
	 		$data->direccion   = strlen( $data->datosCliente->direccion ) > 3	? (string)$data->datosCliente->direccion 	: NULL;

	 		//var_dump( $data );

	 		if( $accion == 'update' ):
		 		$data->idFactura = isset( $data->idFactura ) 	? (int)$data->idFactura 	: NULL;
		 		$data->idFactura = (int)$data->idFactura > 0 	? (int)$data->idFactura 	: NULL;

	 			$idFactura = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El ID de la FACTURA no es válida' );
	 		endif;

			$idEstadoFactura = $validar->validarEntero( $data->idEstadoFactura, NULL, TRUE, 'El estado de la factura no es válido' );

			$idCliente       = $validar->validarEntero( $data->datosCliente->idCliente, NULL, TRUE, 'El Código del Cliente no es válido' );
			$idCaja          = $validar->validarEntero( $data->idCaja, NULL, TRUE, 'El Código de CAJA no es válido' );

			$nombre          = $this->con->real_escape_string( $validar->validarTexto( $data->nombre, NULL, TRUE, 3, 60, 'el nombre del combo' ) );
			$direccion       = $this->con->real_escape_string( $validar->validarTexto( $data->direccion, NULL, TRUE, 3, 75, ' dirección del cliente' ) );

	 		// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:

	 			$this->con->query( "START TRANSACTION" );

				$sql = "CALL consultaFacturaCliente( '{$accion}', {$idFactura}, {$idEstadoFactura}, {$idCliente}, {$idCaja}, '{$nombre}', '{$direccion}', {$total} );";

		 		if( $rs = $this->con->query( $sql ) ){
		 			$this->siguienteResultado();

		 			if( $row = $rs->fetch_object() ){
		 				$this->respuesta = $row->respuesta;
		 				$this->mensaje   = $row->mensaje;

		 				if( $accion == 'insert' AND $this->respuesta == 'success' ) {
		 					$this->data = (int)$row->id;
		 				}
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}

		 		if( $this->respuesta == 'danger' )
		 			$this->con->query( "ROLLBACK" );
		 		else
		 			$this->con->query( "ROLLBACK" );
		 			//$this->con->query( "COMMIT" );
	 		endif;

		else:
			$this->respuesta = 'danger';
	 		$this->mensaje   = 'Su <b>CAJA</b> se encuentra <b>' . strtoupper( $caja->consultarEstadoCaja()->estadoCaja ) . "</b> debe aperturarla para poder facturar";
		endif;

 		return $this->getRespuesta();
	}


	private function consultaFacturaMenu( $idMenu, $precio, $cantidad, $tipoServicio, $lstDetalle )
	{
		foreach ($lstDetalle AS $ixDetalle => $detalle ) {
			
		}
	}

	private function consultaFacturaCombo( $idCombo, $precio, $cantidad, $tipoServicio, $lstDetalle )
	{
		foreach ($lstDetalle AS $ixDetalle => $detalle ) {
			
		}
	}


	public function lstDetalleOrdenCliente( $idOrdenCliente, $todo = FALSE, $agrupado = FALSE )
 	{
		$lst   = array();
		$where = "";

		if ( !$todo )
			$where = " AND idEstadoDetalleOrden != 10 ";

 		$sql = "SELECT 
				    idDetalleOrdenMenu,
				    cantidad,
				    idMenu,
				    menu,
				    perteneceCombo,
				    precio,
				    combo,
				    imagenCombo,
				    precioCombo,
				    idEstadoDetalleOrden,
				    estadoDetalleOrden,
				    idTipoServicio,
				    tipoServicio,
				    idDetalleOrdenCombo,
				    idCombo,
				    tiempoAlerta
				FROM
				    vOrdenes
				WHERE
				    idOrdenCliente = {$idOrdenCliente} $where
				GROUP BY IF( perteneceCombo, idDetalleOrdenCombo, idDetalleOrdenMenu ), perteneceCombo;";
				//echo $sql;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ) {

				$row->perteneceCombo = (int)$row->perteneceCombo;
				// SI PERTENECE A COMBO
				if ( $row->perteneceCombo ) {
					$row->idMenu             = 0;
					$row->idDetalleOrdenMenu = 0;
					$precioMenu              = $row->precioCombo;
				}
				else{
					$row->idCombo             = 0;
					$row->idDetalleOrdenCombo = 0;
					$precioMenu               = $row->precio;
				}

				$index = -1;

				$row->cobrarTodo = TRUE;

				$detalle = (object)array(
						'idCombo'          => $row->idCombo,
						'idMenu'           => $row->idMenu,
						'esCombo'          => $row->perteneceCombo,
						'cantidad'         => 0,
						'maximo'           => 0,
						'precio'           => (double)$precioMenu,
						'precioHabilitado' => FALSE,
						'maximoPrecio'     => (double)$precioMenu,
						'cobrarTodo'       => $row->cobrarTodo,
						'descripcion'      => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'idTipoServicio'   => $row->idTipoServicio,
						'tipoServicio'     => $row->tipoServicio,
						'justificacion'    => '',
						'lstDetalle'       => array()
					);

				if( $agrupado )
				{

					// REVISA SI YA EXISTE MENU
					foreach ( $lst as $ix => $item ):
						if ( 
							$row->idCombo == $item->idCombo AND 
							$row->idMenu == $item->idMenu AND 
							$row->idTipoServicio == $item->idTipoServicio
						) {
							$index = $ix;
							break;
						}
					endforeach;


					// SI NO EXISTE EN LISTADO
					if ( $index == -1 ) {
						$index = count( $lst );
						// AGREGA UNA NUEVA ORDEN
						$lst[ $index ] = $detalle;
					}

					$lst[ $index ]->cantidad += $row->cantidad;
					$lst[ $index ]->maximo += $row->cantidad;

					// AGREGA DETALLE DE ORDEN
					$lst[ $index ]->lstDetalle[] = (object)array(
						'precioMenu'          => $precioMenu,
						'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
						'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo
					);

				}
				else{
					$detalle->cantidad = 1;
					$detalle->maximo   = 1;
					$detalle->precioHabilitado = TRUE;
					
					$lst[] = $detalle;
				}
			}
 		}

 		return $lst;
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