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


 	// LIBERAR SIGUIENTE RESULTADO
 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}


 	// CONSULTA FACTURACION => INSERT / UPDATE
	function consultaFacturaCliente( $accion, $data )
	{
		$validar = new Validar();
		$caja    = new Caja();

		$detalleCaja = $caja->consultarEstadoCaja();

		if( $detalleCaja->idEstadoCaja != 1 ):
			$this->respuesta = 'danger';
	 		$this->mensaje   = 'Su <b>CAJA</b> se encuentra <b>' . strtoupper( $caja->consultarEstadoCaja()->estadoCaja ) . "</b> debe aperturarla para poder facturar";
		
		elseif( !count( $data->lstFormasPago ) ):
			$this->respuesta = 'danger';
	 		$this->mensaje   = 'No se recibió las formas de pago';

		elseif( !count( $data->lstOrden ) ):
			$this->respuesta = 'danger';
	 		$this->mensaje   = 'No se recibió el detalle de la orden';

	 	elseif( $detalleCaja->cajaAtrasada ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = ' NO HA REALIZADO EL CIERRE DE SU CAJA DE FECHA/HORA: <b>' . $detalleCaja->fechaHoraApertura . "</b>";
	 		$this->tiempo    = 8;

		else:
			// INICIALIZACIÓN VAR
			$idFactura       = 'NULL';
			$idEstadoFactura = 'NULL';
			$idCliente       = 'NULL';
			$nombre          = 'NULL';
			$direccion       = 'NULL';

			// SETEO VARIABLES
	 		$data->idEstadoFactura           = isset( $data->idEstadoFactura ) 	? (int)$data->idEstadoFactura 	: NULL;
	 		$data->idEstadoFactura           = (int)$data->idEstadoFactura > 0 	? (int)$data->idEstadoFactura 	: NULL;

	 		$data->datosCliente->idCliente 	 = isset( $data->datosCliente->idCliente ) 	? (int)$data->datosCliente->idCliente 	: NULL;
	 		$data->datosCliente->idCliente   = (int)$data->datosCliente->idCliente > 0 	? (int)$data->datosCliente->idCliente 	: NULL;
	 		$data->datosCliente->nombre      = isset( $data->datosCliente->nombre ) 		? (string)$data->datosCliente->nombre 	: NULL;
	 		$data->nombre                    = strlen( $data->datosCliente->nombre ) > 1	? (string)$data->datosCliente->nombre 	: NULL;
	 		$data->datosCliente->direccion   = isset( $data->datosCliente->direccion ) 	? (string)$data->datosCliente->direccion 	: NULL;
	 		$data->direccion   = strlen( $data->datosCliente->direccion ) > 3	? (string)$data->datosCliente->direccion 	: NULL;

	 		if( $accion == 'update' ):
		 		$data->idFactura = isset( $data->idFactura ) 	? (int)$data->idFactura 	: NULL;
		 		$data->idFactura = (int)$data->idFactura > 0 	? (int)$data->idFactura 	: NULL;

	 			$idFactura = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El ID de la FACTURA no es válida' );
	 		endif;

			$idEstadoFactura = $validar->validarEntero( $data->idEstadoFactura, NULL, TRUE, 'El estado de la factura no es válido' );
			$idCliente       = $validar->validarEntero( $data->datosCliente->idCliente, NULL, TRUE, 'El Código del Cliente no es válido' );
			$nombre          = $this->con->real_escape_string( $validar->validarTexto( $data->nombre, NULL, TRUE, 3, 60, 'el nombre del combo' ) );
			$direccion       = $this->con->real_escape_string( $validar->validarTexto( $data->direccion, NULL, TRUE, 3, 75, ' dirección del cliente' ) );

			$total  = (double)$data->total;
			$idCaja = (int)$detalleCaja->idCaja;

	 		// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:

	 			$this->con->query( "START TRANSACTION" );

				$sql = "CALL consultaFacturaCliente( '{$accion}', {$idFactura}, {$idEstadoFactura}, {$idCliente}, {$idCaja}, '{$nombre}', '{$direccion}', {$total} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;

	 				if( $accion == 'insert' AND $this->respuesta == 'success' ) {
	 					$this->data    = $idFactura = (int)$row->id;
	 					$this->tiempo  = 3;
	 				}

 					if( $this->respuesta == 'success'  )
 						$this->consultaFormaPago( $accion, $idFactura, $total, $data->lstFormasPago );

 					if( $this->respuesta == 'success'  )
 						$this->consultaDetalleFactura( $accion, $idFactura, $data->agrupado, $data->lstOrden );

		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}

		 		if( $this->respuesta == 'success' )
		 			$this->con->query( "COMMIT" );
		 			//$this->con->query( "ROLLBACK" );
		 		else
		 			$this->con->query( "ROLLBACK" );
	 		endif;
		endif;

 		return $this->getRespuesta();
	}


	private function consultaDetalleFactura( $accion, $idFactura, $agrupado, $lstOrden )
	{
		$agrupado  = (int)$agrupado;
		$validar   = new Validar();
		$guardados = 0;

		foreach ( $lstOrden AS $ixOrden => $orden ) {

			$idFactura           = (int)$idFactura;
			$idDetalleOrdenMenu  = "NULL";
			$idDetalleOrdenCombo = "NULL";
			$comentario          = "NULL";
			$precioMenu          = (double)$orden->precioMenu;

			$descuento  = (double)$orden->descuento;
			$comentario = "'" . $orden->comentario . "'";

			if( (int)$orden->cobrarTodo ):

				if( $agrupado ) {

					$cantidad = (int)$orden->cantidad;

					foreach ( $orden->lstDetalle as $ixDetalle => $detalle ) {

						if( (int)$orden->esCombo )
							$idDetalleOrdenCombo = (int)$detalle->idDetalleOrdenCombo;
						else
							$idDetalleOrdenMenu = (int)$detalle->idDetalleOrdenMenu;
						
						$sql = "CALL consultaDetalleFactura( '{$accion}', {$idFactura}, {$idDetalleOrdenMenu}, {$idDetalleOrdenCombo}, {$precioMenu}, {$descuento}, {$comentario} );";

						if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){

							$this->siguienteResultado();

							$this->respuesta = $row->respuesta;
							$this->mensaje   = $row->mensaje;
							
							if( $this->respuesta == 'success' ){
								$this->tiempo = 2;
								$guardados++;
							}

							$cantidad--;
						}
						else{
							$this->respuesta = 'danger';
							$this->mensaje   = 'Error al ejecutar la operacion (Detalle Factura)';
						}

						if( $this->respuesta == 'danger' OR $cantidad == 0 )
							break;
					}
				
				}
				else {
					
					if( (int)$orden->esCombo )
						$idDetalleOrdenCombo = (int)$orden->idDetalleOrdenCombo;
					else
						$idDetalleOrdenMenu = (int)$orden->idDetalleOrdenMenu;

					$sql = "CALL consultaDetalleFactura( '{$accion}', {$idFactura}, {$idDetalleOrdenMenu}, {$idDetalleOrdenCombo}, {$precioMenu}, {$descuento}, {$comentario} );";

					if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
						$this->siguienteResultado();

						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;
						
						if( $this->respuesta == 'success' ){
							$this->tiempo = 2;
							$guardados++;
						}
					}
					else{
						$this->respuesta = 'danger';
						$this->mensaje   = 'Error al ejecutar la operacion (Detalle Factura)';
					}

					if( $this->respuesta == 'danger' )
						break;
				}

			endif;
		}

		if( !$guardados ){
			$this->respuesta = 'danger';
			$this->mensaje   = 'No se realizó ningun cobro de la orden';
			$this->tiempo    = 7;
		}
	}


	public function consultaFormaPago( $accion, $idFactura, $total, $lstFormasPago )
	{
		$guardados = 0;
		$total     = (double)$total;

		foreach ( $lstFormasPago AS $ixFormaPago => $formaPago ) {
			if( isset( $formaPago->monto ) && $formaPago->monto > 0  ){

				$idFormaPago = (int)$formaPago->idFormaPago;
				$idFactura   = (int)$idFactura;
				$monto       = (double)$formaPago->monto;

				if( $monto > $total )
					$monto = $monto - ( $monto - $total );

				$sql = "CALL consultaFormaPago( '{$accion}', {$idFactura}, {$idFormaPago}, {$monto} );";
				
				if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
					$this->siguienteResultado();
					$this->respuesta = $row->respuesta;
					$this->mensaje   = $row->mensaje;
					
					if( $this->respuesta == 'success' ) {
						$this->tiempo = 2;
						$guardados++;

						//if( $formaPago->monto >= $total )
							$total -= $monto;
					}
				}
				else{
					$this->respuesta = 'danger';
					$this->mensaje   = 'Error al ejecutar la operacion (Forma de Pago)';
				}

				if( $this->respuesta == 'danger' )
					break;
			}
			
		}

		if( !$guardados ){
			$this->respuesta = 'danger';
			$this->mensaje   = 'No se registró ningun monto en la forma de pago';
			$this->tiempo    = 7;
		}
		elseif( $total > 0  ){
			$this->respuesta = 'warning';
			$this->mensaje   = 'Los montos ingresados no cubren el <b>TOTAL DE LA ORDEN</b>';
			$this->tiempo    = 8;
		}
	}


	public function lstDetalleOrdenCliente( $idOrdenCliente, $todo = FALSE, $agrupado = FALSE )
 	{
		$lst   = array();
		$where = "";

		if ( !$todo )
			$where = " AND idEstadoDetalleOrden = 4 ";

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
						'idCombo'              => $row->idCombo,
						'idMenu'               => $row->idMenu,
						'esCombo'              => $row->perteneceCombo,
						'cantidad'             => 0,
						'maximo'               => 0,
						'descuento'            => 0,
						'precioMenu'           => (double)$precioMenu,
						'cobrarTodo'           => $row->cobrarTodo,
						'descripcion'          => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'idTipoServicio'       => $row->idTipoServicio,
						'tipoServicio'         => $row->tipoServicio,
						'comentario'           => '',
						'idEstadoDetalleOrden' => $row->idEstadoDetalleOrden,
						'estadoDetalleOrden'   => $row->estadoDetalleOrden,
						'idDetalleOrdenMenu'   => $row->idDetalleOrdenMenu,
						'idDetalleOrdenCombo'  => $row->idDetalleOrdenCombo,
						'lstDetalle'           => array(),
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



 	function lstFacturas( $idFactura = NULL, $filtrarPorFecha = NULL  )
 	{
 		$where = "";
 		if( !is_null( $idFactura ) AND $idFactura > 0 )
 			$where .= "WHERE idFactura = {$idFactura}";

 		elseif( !is_null( $filtrarPorFecha ) AND $filtrarPorFecha ){
 			$fechaRegistro = date("Y-m-d");
 			$where .= "WHERE DATE( fechaRegistro ) = '{$fechaRegistro}'";
 		}

 		$lstFacturas = array();

 		$sql = "SELECT 
				    idFactura,
				    idCliente,
				    idCaja,
				    nit,
				    nombre,
				    direccion,
				    total,
				    fechaFactura,
				    usuario,
				    idEstadoFactura,
				    estadoFactura,
				    DATE_FORMAT( fechaRegistro, '%d/%m/%Y %h:%s %p' ) AS fechaRegistro
				FROM
				    vstFactura $where
				ORDER BY fechaRegistro DESC;";

			//	echo $sql;
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstFacturas[] = $row;
 			}
 		}

 		return $lstFacturas;
 	}


 	function detalleOrdenFactura( $idFactura )
 	{

 		$lstDetalleFactura = array();

		$sql = "SELECT 
					idFactura,
					idOrdenCliente,
					numeroTicket,
					idDetalleOrdenMenu,
					idMenu,
					menu,
					imagen,
					perteneceCombo,
					idDetalleOrdenCombo,
					idCombo,
					combo,
					imagenCombo,
					idTipoServicio,
					tipoServicio,
					usuarioRegistro,
					precioMenu,
					descuento,
					precioReal,
					comentario,
					usuarioFacturaDetalle,
					fechaFacturaDetalle
				FROM
				    vstDetalleOrdenFactura
				WHERE
				    idFactura = {$idFactura}
				GROUP BY IF( perteneceCombo, idDetalleOrdenCombo, idDetalleOrdenMenu ), perteneceCombo;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$row->perteneceCombo = (int)$row->perteneceCombo;
				// SI PERTENECE A COMBO
				if ( $row->perteneceCombo ) {
					$row->idMenu             = 0;
					$row->idDetalleOrdenMenu = 0;
					$precioMenu              = $row->precioMenu;
				}
				else{
					$row->idCombo             = 0;
					$row->idDetalleOrdenCombo = 0;
					$precioMenu               = $row->precioMenu;
				}

 				$index = -1;

				$detalle = (object)array(
						'idCombo'             => $row->idCombo,
						'idMenu'              => $row->idMenu,
						'esCombo'             => $row->perteneceCombo,
						'cantidad'            => 0,
						'subTotal' 			  => 0,
						'descuento'           => (double)$row->descuento,
						'precioMenu'          => (double)$precioMenu,
						'precioReal'          => $row->precioReal,
						'descripcion'         => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'idTipoServicio'      => $row->idTipoServicio,
						'tipoServicio'        => $row->tipoServicio,
						'comentario'          => $row->comentario,
						'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
						'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo
					);

				// REVISA SI YA EXISTE MENU
				foreach ( $lstDetalleFactura as $ix => $item ):
					if ( 
						$row->idCombo        == $item->idCombo AND
						$row->idMenu         == $item->idMenu AND
						$row->idTipoServicio == $item->idTipoServicio AND
						$row->precioReal     == $item->precioReal
					) {
						$index = $ix;
						break;
					}
				endforeach;

				// SI NO EXISTE EN LISTADO
				if ( $index == -1 ) {
					$index = count( $lstDetalleFactura );
					// AGREGA UNA NUEVA ORDEN
					$lstDetalleFactura[ $index ] = $detalle;
				}

				$lstDetalleFactura[ $index ]->cantidad += 1;
				$lstDetalleFactura[ $index ]->subTotal = ( $lstDetalleFactura[ $index ]->cantidad * $row->precioReal );
 			}
 		}

 		return $lstDetalleFactura;
 	}


	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
 	{
 		/*
 		if( $this->respuesta == 'success' )
 			$this->tiempo = 4;
 		elseif( $this->respuesta == 'warning')
 			$this->tiempo = 5;
 		elseif( $this->respuesta == 'danger')
 			$this->tiempo = 7;
 		*/

 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}

}
?>