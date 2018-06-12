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
		$caja    = new Caja();

		$detalleCaja = $caja->consultarEstadoCaja();

		//print_r( $data );
		if( $detalleCaja->idEstadoCaja != 1 ):
			$this->respuesta = 'danger';
	 		$this->mensaje   = 'Su <b>CAJA</b> se encuentra <b>' . strtoupper( $caja->consultarEstadoCaja()->estadoCaja ) . "</b> debe aperturarla para poder facturar";

		elseif( !count( $data->lstDetalle ) ):
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
			$descripcion     = 'NULL';

			// SETEO VARIABLES
	 		$data->idEstadoFactura = isset( $data->idEstadoFactura ) 	? (int)$data->idEstadoFactura 		: NULL;
	 		$data->idCliente 	   = isset( $data->cliente->idCliente ) ? (int)$data->cliente->idCliente 	: NULL;
	 		$data->nombre          = isset( $data->cliente->nombre ) 	? (string)$data->cliente->nombre 	: NULL;
	 		$data->direccion       = isset( $data->cliente->direccion ) ? (string)$data->cliente->direccion : NULL;

	 		if( $accion == 'update' ):
		 		$data->idFactura = isset( $data->idFactura ) ? (int)$data->idFactura : NULL;
		 		$data->idFactura = (int)$data->idFactura > 0 ? (int)$data->idFactura : NULL;

	 			$idFactura = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El ID de la FACTURA no es válida' );
	 		endif;

			$idEstadoFactura = $validar->validarEntero( $data->idEstadoFactura, NULL, TRUE, 'El estado de la factura no es válido' );
			$idCliente       = $validar->validarEntero( $data->idCliente, NULL, TRUE, 'El Código del Cliente no es válido' );
			$nombre          = $this->con->real_escape_string( $validar->validarTexto( $data->nombre, NULL, TRUE, 3, 60, 'el nombre del combo' ) );
			$direccion       = $this->con->real_escape_string( $validar->validarTexto( $data->direccion, NULL, TRUE, 3, 75, ' dirección del cliente' ) );

			$total           = (double)$data->detallePago->total;
			$idCaja          = (int)$detalleCaja->idCaja;
			if( $data->detallePago->tipo == 'p' )
			{
				$descripcion = isset( $data->detallePago->descripcion ) ? $data->detallePago->descripcion : '';
				$descripcion = "'" . $this->con->real_escape_string( $validar->validarTexto( $descripcion, NULL, TRUE, 15, 75, ' la descripción de la Factura' ) ) . "'";
			}

	 		// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:
	 		
	 			$this->con->query( "START TRANSACTION" );

				$sql = "CALL consultaFacturaCliente( '{$accion}', {$idFactura}, {$idEstadoFactura}, {$idCliente}, {$idCaja}, '{$nombre}', '{$direccion}', {$total}, {$descripcion} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
		 		{
		 			$this->con->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;

	 				if( $accion == 'insert' AND $this->respuesta == 'success' ) {
	 					$this->data    = $idFactura = (int)$row->id;
	 					$this->tiempo  = 3;
	 				}

 					if( $this->respuesta == 'danger' )
 						$this->respuesta .= ' (Facturar Cliente)';
 					else
 					{
	 					if( $this->respuesta == 'success'  )
	 						$this->consultaFormaPago( $accion, $idFactura, $data->detallePago );
						
						if( $this->respuesta == 'success'  )
	 						$this->consultaDetalleFactura( $accion, $idFactura, $data->lstDetalle );
 					}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error en la instrucción (Facturar Cliente).';
		 		}

		 		if( $this->respuesta == 'success' )
		 			$this->con->query( "COMMIT" );
		 		else
		 			$this->con->query( "ROLLBACK" );
	 		endif;
		endif;

 		return $this->getRespuesta();
	}

	// DETALLE DE FACTURA // INSERT
	private function consultaDetalleFactura( $accion, $idFactura, $lstDetalle )
	{
		$validar   = new Validar();
		$guardados = 0;
		$idFactura = (int)$idFactura;

		foreach ( $lstDetalle AS $ixOrden => $orden )
		{
			$lst                 = array();
			$idDetalleOrdenMenu  = "NULL";
			$idDetalleOrdenCombo = "NULL";
			$justificacion       = "NULL";
			$precio              = (double)$orden->precio;
			$descuentoT          = 0;
			$idOrdenCliente      = $validar->validarEntero( $orden->idOrdenCliente, NULL, TRUE, 'Orden de Cliente no Definida' );
			$idTipoServicio      = $validar->validarEntero( $orden->idTipoServicio, NULL, TRUE, 'Tipo de Servicio no Definido' );
			@$idCombo            = $validar->validarEntero( $orden->idCombo, 0, FALSE, 'No. de Combo inválido' );
			$idMenu              = $validar->validarEntero( $orden->idMenu, 0, FALSE, 'No. de Menu inválido' );
			$cantidad            = $validar->validarEntero( $orden->cantidad, 0, FALSE, 'Menus Seleccionados' );

			// SI ES MENU PERSONALIZADO
			if ( isset( $orden->esPersonalizado ) AND (bool)$orden->esPersonalizado )
			{
				$sql = "SELECT idMenuPersonalizado FROM menuPersonalizado
						WHERE idMenuPersonalizado = {$orden->idMenuPersonalizado} AND idEstadoDetalleOrden = 1 ";
				
				$rs = $this->con->query( $sql );

				if ( $rs AND $row = $rs->fetch_object() ) {
					$orden->descuento = (double)$orden->descuento;

					$sql = "UPDATE menuPersonalizado SET idEstadoDetalleOrden = 6
							WHERE idMenuPersonalizado = {$row->idMenuPersonalizado} ";

					$sqlFac = "INSERT INTO detalleOrdenFactura ( idFactura, idMenuPersonalizado, precioMenu, descuento, usuario, fechaRegistro )
							VALUES( $idFactura, {$row->idMenuPersonalizado}, {$precio}, {$orden->descuento}, '{$this->sess->getUsuario()}', NOW() ) ";

					if( $this->con->query( $sql ) AND $this->con->query( $sqlFac ) )
					{
						$guardados++;
						$this->respuesta = 'success';
						$this->mensaje   = 'Guardado correctamente';
					}
					else{
						$this->respuesta = 'danger';
						$this->mensaje   = 'Error al guardar estado Menú Personalizado';
					}
				}
				else{
					$this->respuesta = 'danger';
					$this->mensaje   = 'Menú Personalizado ya facturado';
				}
			}

			// SI ES MENU O COMBO
			else
			{
				if ( $idCombo )
					$sql = "SELECT 
								idDetalleOrdenCombo
							FROM detalleOrdenCombo
							WHERE idOrdenCliente = {$idOrdenCliente}
								AND idCombo = {$idCombo}
								AND idTipoServicio = {$idTipoServicio}
							    AND idEstadoDetalleOrden <> 6
	                            AND idEstadoDetalleOrden <> 10
							ORDER BY idEstadoDetalleOrden ASC, idDetalleOrdenCombo ASC
							LIMIT {$cantidad}; ";
				else
					$sql = "SELECT
								idDetalleOrdenMenu
							FROM detalleOrdenMenu 
							WHERE idOrdenCliente = {$idOrdenCliente}
								AND idMenu = {$idMenu}
								AND idTipoServicio = {$idTipoServicio}
							    AND idEstadoDetalleOrden <> 6
	                            AND idEstadoDetalleOrden <> 10
							    AND !perteneceCombo
							ORDER BY idEstadoDetalleOrden ASC, idDetalleOrdenMenu ASC
							LIMIT {$cantidad}; ";
				
				$result = $this->con->query( $sql );

				while( $result AND $row = $result->fetch_object() )
			 		$lst[] = $row;

	 			if( isset( $orden->conDescuento ) AND (int)$orden->conDescuento )
					$descuentoT = (double)$orden->descuento;

			 	// SI LOS SELECCIONADOS ES IGUAL AL NUMERO DE DETALLE
			 	if ( count( $lst ) == $cantidad )
			 	{
			 		foreach ( $lst AS $item ):
				 		
				 		$justificacion = "NULL";
				 		$descuento     = 0;

				 		if( $descuentoT )
				 			$justificacion = "'" . $this->con->real_escape_string( $orden->justificacion ) . "'";

				 		if( $descuentoT >= $precio )
				 			$descuento = $precio;

				 		elseif( $descuentoT )
				 			$descuento = $descuentoT;

				 		if ( isset( $item->idDetalleOrdenMenu ) ) {
				 			$idDetalleOrdenMenu = $item->idDetalleOrdenMenu;
				 		}
				 		else
				 			$idDetalleOrdenCombo = $item->idDetalleOrdenCombo;

						$sql = "CALL consultaDetalleFactura( '{$accion}', {$idFactura}, {$idDetalleOrdenMenu}, {$idDetalleOrdenCombo}, {$precio}, {$descuento}, {$justificacion} );";

						if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
						{
							$this->con->siguienteResultado();

							$this->respuesta = $row->respuesta;
							$this->mensaje   = $row->mensaje;
							
							if( $this->respuesta == 'success' )
							{
								$this->tiempo = 2;
								$guardados++;
								if( $descuentoT >= $precio )
									$descuentoT -= $precio;
								elseif( $descuentoT )
						 			$descuentoT = 0;
							}
							elseif( $this->respuesta == 'danger' )
								$this->mensaje .= ' (Detalle Factura)';
						}
						else{
							$this->respuesta = 'danger';
							$this->mensaje   = 'Error al ejecutar la operacion (Detalle Factura)';
						}
						
						if( $this->respuesta == 'danger' )
							break;
			 		endforeach;
				}
			}


			if( $this->respuesta == 'danger' )
				break;
		}
		
		if( !$guardados )
		{
			$this->respuesta = 'danger';
			$this->mensaje   = strlen( $this->mensaje ) ? $this->mensaje : 'No se realizó ningun cobro de la orden';
			$this->tiempo    = 6;
		}
	}


	public function consultaFormaPago( $accion, $idFactura, $detallePago )
	{		
		$guardados = 0;
		$total     = (double)$detallePago->total;
		$idFactura = (int)$idFactura;
		
		// EFECTIVO
		if( $detallePago->efectivo && (double)$detallePago->efectivo > 0  )
		{
			$idFormaPago = 1;

			if( $detallePago->efectivo > $total )
				$monto = $detallePago->efectivo -= ( $detallePago->efectivo - $total );
			else
				$monto = $detallePago->efectivo;

			$sql = "CALL consultaFormaPago( '{$accion}', {$idFactura}, {$idFormaPago}, {$monto} );";

			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
			{
				$this->con->siguienteResultado();
				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				
				if( $this->respuesta == 'success' ) {
					$this->tiempo = 2;
					$guardados++;
					$total -= $detallePago->efectivo;
				}
				elseif( $this->respuesta == 'danger' )
					$this->mensaje .= ' (Forma de Pago)';
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion (Forma de Pago)';
			}
		}
		// TARJETA
		if( $detallePago->tarjeta && (double)$detallePago->tarjeta > 0  )
		{
			$idFormaPago = 2;

			if( $detallePago->tarjeta > $total )
				$monto = $detallePago->tarjeta -= ( $detallePago->tarjeta - $total );
			else
				$monto = $detallePago->tarjeta;

			$sql = "CALL consultaFormaPago( '{$accion}', {$idFactura}, {$idFormaPago}, {$monto} );";

			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
			{
				$this->con->siguienteResultado();
				$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				
				if( $this->respuesta == 'success' ) {
					$this->tiempo = 2;
					$guardados++;
					$total -= $detallePago->tarjeta;
				}
				elseif( $this->respuesta == 'danger' )
					$this->mensaje .= ' (Forma de Pago)';
			}
			else{
				$this->respuesta = 'danger';
				$this->mensaje   = 'Error al ejecutar la operacion (Forma de Pago)';
			}
		}

		// VALIDACIONES
		if( !$guardados )
		{
			$this->respuesta = 'danger';
			$this->mensaje   = 'No se registró ningun monto en la forma de pago';
			$this->tiempo    = 7;
		}
		elseif( $total > 0  )
		{
			$this->respuesta = 'danger';
			$this->mensaje   = 'El Total del monto ingresados no cubre el <b>TOTAL DE LA ORDEN</b>';
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

				if( $agrupado )
					$row->cobrarTodo = TRUE;
				else
					$row->cobrarTodo = FALSE;

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

 	// ACTUALIZAR ESTADO FACTURA CLIENTE
 	public function consultaEstadoFacturaCli( $data )
 	{
		// INICIALIZACIÓN VARIABLES
		$idFactura       = 'NULL';
		$idEstadoNuevo = 'NULL';

		$validar = new Validar();

		// SETEO VARIABLES
		$data->idEstadoNuevo = isset( $data->idEstadoNuevo ) 	? (int)$data->idEstadoNuevo 	: NULL;
		$data->idFactura     = isset( $data->idFactura ) 		? (int)$data->idFactura 		: NULL;
		$idFactura           = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El ID de la FACTURA no es válida' );
		$idEstadoNuevo       = $validar->validarEntero( $data->idEstadoNuevo, NULL, TRUE, 'El nuevo estado de la factura no es válido' );

 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
 		
			$sql = "CALL consultaFacturaCliente( 'status', {$idFactura}, {$idEstadoNuevo}, NULL, NULL, NULL, NULL, NULL, NULL );";

			//echo $sql;
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->con->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;

				if( $this->respuesta == 'danger' )
					$this->respuesta .= ' (Facturar Cliente)';
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error en la instrucción (Facturar Cliente).';
	 		}

	 	endif;

	 	return $this->getRespuesta();
 	}


 	function lstFacturas( $idFactura = NULL, $filtrarPorFecha = NULL, $pendientes = FALSE )
 	{
 		$where = "";
 		if( !is_null( $idFactura ) AND $idFactura > 0 )
 			$where .= "WHERE idFactura = {$idFactura}";

 		elseif( !is_null( $filtrarPorFecha ) AND $filtrarPorFecha ){
 			$fechaRegistro = date("Y-m-d");
 			$where .= "WHERE DATE( fechaRegistro ) = '{$fechaRegistro}'";
 		}
 		elseif( is_null( $filtrarPorFecha ) AND is_null( $filtrarPorFecha ) AND $pendientes )
 			$where .= "WHERE ( idEstadoFactura = 3 OR idEstadoFactura = 2 )";

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
				    DATE_FORMAT( fechaRegistro, '%d/%m/%Y %h:%m %p' ) AS fechaRegistro,
				    siDetalle,
				    descripcion
				FROM
				    vstFactura $where
				ORDER BY fechaRegistro DESC;";
		//	echo $sql;
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$row->siDetalle = (bool)$row->siDetalle;
 				$lstFacturas[]  = $row;
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
				$lstDetalleFactura[ $index ]->subTotal = number_format( $lstDetalleFactura[ $index ]->cantidad * $row->precioReal, 2 );
 			}
 		}

 		$sql = "SELECT mp.cantidad,
					( ( mp.precioUnidad * mp.cantidad ) - dof.descuento )AS 'subTotal',
				    mp.precioUnidad AS 'precio',
				    dof.descuento,
				    mp.descripcion,
				    dof.comentario
				FROM menuPersonalizado AS mp
					JOIN detalleOrdenFactura AS dof
						ON mp.idMenuPersonalizado = dof.idMenuPersonalizado
				WHERE dof.idFactura = {$idFactura} ";

		$rs = $this->con->query( $sql );
		while( $rs AND $row = $rs->fetch_object() ){
			$lstDetalleFactura[] = (object)array(
				'idCombo'             => 0,
				'idMenu'              => 0,
				'esCombo'             => 0,
				'cantidad'            => $row->cantidad,
				'subTotal' 			  => number_format( $row->subTotal, 2 ),
				'descuento'           => (double)$row->descuento,
				'precioMenu'          => (double)$row->precio,
				'precioReal'          => $row->precio,
				'descripcion'         => $row->descripcion,
				'idTipoServicio'      => '',
				'tipoServicio'        => 'NA',
				'comentario'          => $row->comentario,
				'idDetalleOrdenMenu'  => null,
				'idDetalleOrdenCombo' => null
			);
		}

 		return $lstDetalleFactura;
 	}

 	function detalleFacturaEvento( $idFactura )
 	{

 		$lstDetalleFactura = array();

		$sql = "SELECT
					idEventoFactura,
					cantidad,
				    descripcion,
				    subTotal
				FROM eventoFactura WHERE idFactura = {$idFactura} ";
 		
 		$rs = $this->con->query( $sql );
		while( $rs AND $row = $rs->fetch_object() ) {
			$row->cantidad = (int)$row->cantidad;
			$row->subTotal = (double)$row->subTotal;
			$lstDetalleFactura[] = $row;
		}

 		return $lstDetalleFactura;
 	}


	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
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