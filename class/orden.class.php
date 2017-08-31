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
		$data->numeroTicket = isset( $data->numeroTicket ) ? (int)$data->numeroTicket	 : "NULL";

		$validar = new Validar();

		// SI USUARIO RESPONSABLE ESTA DEFINIDO
		if ( isset( $data->usuarioResponsable ) AND strlen( $data->usuarioResponsable ) > 3 )
			$usuarioResponsable = "'" . $this->con->real_escape_string( $validar->validarTexto( $data->usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) ) . "'";

		// VALIDACIONES
		if( $accion == 'insert' ):
			// OBLIGATORIOS
			$numeroTicket = $validar->validarEntero( $data->numeroTicket, NULL, TRUE, 'El No. de Ticket no es válido' );

		else:

			// SETEO DE VARIABLES
			$data->idOrdenCliente = (int)$data->idOrdenCliente > 0	? (int)$data->idOrdenCliente : "NULL";
			$data->idEstadoOrden  = isset( $data->idEstadoOrden )	? (int)$data->idEstadoOrden	 : "NULL";

			// OBLIGATORIOS
			$idOrdenCliente = $validar->validarEntero( $data->idOrdenCliente, NULL, TRUE, 'El No. de Orden no es válido' );

			if( $accion == 'update' ):
				$numeroTicket = $validar->validarEntero( $data->numeroTicket, NULL, TRUE, 'El No. de Ticket es válido' );
			
			endif;

		endif;


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:
		 	$this->con->query( "START TRANSACTION" );

	 		$sql = "CALL consultaOrdenCliente( '{$accion}', {$idOrdenCliente}, {$numeroTicket}, {$usuarioResponsable}, {$idEstadoOrden} )";
	 		
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 		@$this->con->next_result();
		 		$this->con->query( "COMMIT" );// CONFIRMA TRANSACCION

	 			$this->respuesta = $row->respuesta;
	 			$this->mensaje   = $row->mensaje;
	 			if( $accion == 'insert' AND $row->respuesta == 'success' )
	 				$this->data = (int)$row->id;

	 			if ( $accion == 'cancel' AND $row->respuesta == 'success' )
	 				$this->ordenPrincipalCancelada( $idOrdenCliente );
	 		}
	 		else{
		 		$this->con->query( "ROLLBACK" );
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}

 		endif;

 		return $this->getRespuesta();
 	}


 	// CONSULTA DETALLE ORDEN MENU
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

		$data->idOrdenCliente = (int)$data->idOrdenCliente > 0 ? (int)$data->idOrdenCliente : "NULL";

		if ( true ):
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
		 		
		endif;

 		return $this->getRespuesta();
 	}


 	// CONSULTA DETALLE ORDEN COMBO
 	function consultaDetalleOrdenCombo( $accion, $data )
 	{
 		
 		// INICIALIZACIÓN DE VARIABLES
		$idDetalleOrdenMenu = "NULL";
		$idOrdenCliente     = "NULL";
		$idCombo            = "NULL";
		$cantidad           = "NULL";
		$idTipoServicio     = "NULL";
		$usuarioResponsable = "NULL";
		$idFactura          = "NULL";

		// SETEO DE VARIABLES
		$data->idDetalleOrdenMenu = (int)$data->idDetalleOrdenMenu > 0 		? (int)$data->idDetalleOrdenMenu	: "NULL";
		$data->idOrdenCliente     = (int)$data->idOrdenCliente > 0			? (int)$data->idOrdenCliente		: "NULL";
		$data->idCombo            = (int)$data->idCombo > 0					? (int)$data->idCombo				: "NULL";
		$data->cantidad           = (int)$data->cantidad > 0					? (int)$data->cantidad				: "NULL";
		$data->idTipoServicio     = (int)$data->idTipoServicio > 0			? (int)$data->idTipoServicio		: "NULL";
		$data->idFactura          = (int)$data->idFactura > 0					? (int)$data->idFactura				: "NULL";
		$data->usuarioResponsable = strlen( $data->usuarioResponsable ) > 3	? $data->usuarioResponsable			: "NULL";

		$validar = new Validar();

		// VALIDACIONES
		if( $accion == 'insert' ):

			// OBLIGATORIOS
			$idOrdenCliente     = $validar->validarEntero( $data->idOrdenCliente, NULL, TRUE, 'El No. de la Orden del cliente no es válida' );
			$idCombo            = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El No. del Menu de la Orden no es válido' );
			$cantidad           = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );
			$idTipoServicio     = $validar->validarEntero( $data->idTipoServicio, NULL, TRUE, 'El No. del Tipo de Servicio no es válido' );
			$usuarioResponsable = $this->con->real_escape_string( $validar->validarTexto( $usuarioResponsable, NULL, TRUE, 8, 16, "Usuario responsable" ) );

		else:
			$idDetalleOrdenMenu = $validar->validarEntero( $data->idDetalleOrdenMenu, NULL, TRUE, 'El No. del Detalle de Orden no es válido' );

			if( $accion == 'estado' ):
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

	 		$sql = "CALL consultaDetalleOrdenCombo( '{$accion}', {$idDetalleOrdenMenu}, {$idCombo}, {$cantidad}, {$idEstadoDetalleOrden}, {$idTipoServicio}, {$idFactura}, {$usuarioResponsable} );";

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


 	// GUARDAR DETALLE ORDEN
 	function guardarDetalleOrden( $idOrdenCliente, $lstDetalleOrden, $accionOrden )
 	{
 		// SI EL ARREGLO ES MAYOR A CERO
 		if( is_array( $lstDetalleOrden ) AND count( $lstDetalleOrden ) ):

 			$validar = new Validar();

 			$idOrdenesMenu = $idOrdenesCombo = "";
		 	
		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalleOrden as $ix => $item ):
				$item->idMenu         = (int)$item->idMenu > 0			? (int)$item->idMenu			: 0;
				$item->cantidad       = (int)$item->cantidad > 0		? (int)$item->cantidad			: 0;
				$item->idTipoServicio = (int)$item->idTipoServicio > 0	? (int)$item->idTipoServicio	: 0;

				if ( $item->tipoMenu == 'menu' )
					$sql = "CALL consultaDetalleOrdenMenu( 'insert', NULL, {$idOrdenCliente}, {$item->idMenu}, {$item->cantidad}, NULL, {$item->idTipoServicio}, NULL, NULL );";
				
				else if ( $item->tipoMenu == 'combo' )
					$sql = "CALL consultaDetalleOrdenCombo( 'insert', NULL, {$idOrdenCliente}, {$item->idMenu}, {$item->cantidad}, NULL, {$item->idTipoServicio}, NULL, NULL );";

		 		if( $rs = $this->con->query( $sql ) ){
		 			@$this->con->next_result();
		 			if( $row = $rs->fetch_object() ) {
						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;

						// SI SE GUARDO VA CONCATENANDO LOS IDS GENERADOS
						if ( $this->respuesta == 'success' ) {
							if ( $item->tipoMenu == 'menu' )
								$idOrdenesMenu .= $row->ids;

							else if ( $item->tipoMenu == 'combo' )
								$idOrdenesCombo .= $row->ids;
						}
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 			break;
		 		}
		 		
		 		if ( $this->respuesta == 'danger' )
		 			break;

			endforeach;

			if ( $this->respuesta == 'success' ) {
		 		$this->con->query( "COMMIT" );

		 		// SI ES NUEVO
			 	if ( $accionOrden == 'nuevo' ) {
				 	$infoNode = (object)array(
						'accion' => 'ordenNueva',
						'data'   => array(
							'ordenCliente'    => $this->lstOrdenCliente( 1, NULL, $idOrdenCliente ),
							'lstMenuAgregado' => $this->lstMenuAgregado( $idOrdenesMenu, $idOrdenesCombo ),
					 	),
					);
			 	}
			 	// SI ES AGREGAR
			 	else{
			 		$infoNode = (object)array(
						'accion' => 'ordenAgregar',
						'data'   => array(
							'ordenCliente'        => $this->lstOrdenCliente( 1, NULL, $idOrdenCliente ),
							'detalleOrdenCliente' => $this->lstDetalleOrdenCliente( $idOrdenCliente ),
							'lstMenuAgregado'     => $this->lstMenuAgregado( $idOrdenesMenu, $idOrdenesCombo ),
					 	),
					);
			 	}

			 	// SI LA CLASE NO EXISTE SE LLAMA
			 	if ( !class_exists( "Redis" ) )
			 		include 'redis.class.php';

			 	// ENVIA LOS DATOS POR MEDIO DE REDIS
			 	$red = new Redis();
				$red->messageRedis( $infoNode );
			}
		 	else
		 		$this->con->query( "ROLLBACK" );


 		else:
			$this->respuesta = 'danger';
			$this->mensaje   = 'No ha agregado ningun producto a la orden.';
 		endif;
 	}

 	public function lstMenuAgregado( $txtIdMenu, $txtIdCombo, $idOrdenCliente = 0 )
 	{
 		$lst = array();

 		if ( strlen( $txtIdMenu ) > 0 )
 			$txtIdMenu = substr( str_replace("_", " OR idDetalleOrdenMenu = ", $txtIdMenu ), 3 );

 		if ( strlen( $txtIdCombo ) > 0 )
 			$txtIdCombo = substr( str_replace("_", " OR idDetalleOrdenCombo = ", $txtIdCombo ), 3 );

 		$sql = "SELECT 
					idDetalleOrdenMenu,
				    idMenu,
				    menu,
				    imagen,
				    precio,
				    idEstadoDetalleOrden,
				    estadoDetalleOrden,
				    idTipoServicio,
				    tipoServicio,
				    idDestinoMenu,
				    destinoMenu,
				    usuarioResponsable,
				    fechaRegistro,
				    perteneceCombo,
    				idDetalleOrdenCombo,
    				idOrdenCliente
				FROM vOrdenes
				WHERE ";

		// SI CONSULTA TODO DETALLE DE ORDEN PRINCIPAL
		if ( $idOrdenCliente > 0 ):
			if( $rs = $this->con->query( $sql . " idOrdenCliente = " . $idOrdenCliente ) ){
	 			while ( $row = $rs->fetch_object() ) {
					$row->idDetalleOrdenMenu   = (int)$row->idDetalleOrdenMenu;
					$row->idMenu               = (int)$row->idMenu;
					$row->precio               = (double)$row->precio;
					$row->idEstadoDetalleOrden = (int)$row->idEstadoDetalleOrden;
					$row->idTipoServicio       = (int)$row->idTipoServicio;
					$row->idDestinoMenu        = (int)$row->idDestinoMenu;
					$row->perteneceCombo       = (int)$row->perteneceCombo;
					$row->idDetalleOrdenCombo  = (int)$row->idDetalleOrdenCombo;
	 				$lst[] = $row;
	 			}
	 		}
		endif;


		// SI SE AGREGO AL MENOS UN MENU
		if ( strlen( $txtIdMenu ) > 2 AND $idOrdenCliente === 0 ):
	 		if( $rs = $this->con->query( $sql . $txtIdMenu ) ){
	 			while ( $row = $rs->fetch_object() ) {
					$row->idDetalleOrdenMenu   = (int)$row->idDetalleOrdenMenu;
					$row->idMenu               = (int)$row->idMenu;
					$row->precio               = (double)$row->precio;
					$row->idEstadoDetalleOrden = (int)$row->idEstadoDetalleOrden;
					$row->idTipoServicio       = (int)$row->idTipoServicio;
					$row->idDestinoMenu        = (int)$row->idDestinoMenu;
					$row->perteneceCombo       = (int)$row->perteneceCombo;
					$row->idDetalleOrdenCombo  = (int)$row->idDetalleOrdenCombo;
	 				$lst[] = $row;
	 			}
	 		}
		endif;

		// SI SE AGREGO AL MENOS UN COMBO
		if ( strlen( $txtIdCombo ) > 2 AND $idOrdenCliente === 0 ):
	 		if( $rs = $this->con->query( $sql . $txtIdCombo ) ){
	 			while ( $row = $rs->fetch_object() ) {
	 				$row->idDetalleOrdenMenu   = (int)$row->idDetalleOrdenMenu;
					$row->idMenu               = (int)$row->idMenu;
					$row->precio               = (double)$row->precio;
					$row->idEstadoDetalleOrden = (int)$row->idEstadoDetalleOrden;
					$row->idTipoServicio       = (int)$row->idTipoServicio;
					$row->idDestinoMenu        = (int)$row->idDestinoMenu;
					$row->perteneceCombo       = (int)$row->perteneceCombo;
					$row->idDetalleOrdenCombo  = (int)$row->idDetalleOrdenCombo;
	 				$lst[] = $row;
	 			}
	 		}
		endif;

 		return $lst;
 	}

 	// CONSULTA INFORMACION DE ORDEN DE CLIENTE
 	public function lstOrdenCliente( $idEstadoOrden, $limite = NULL, $idOrdenCliente = NULL )
 	{
		$lst            = NULL;
		$limit          = "";
		$limite         = is_null( $limite ) ? 15 : (int)$limite;
		$idOrdenCliente = (int)$idOrdenCliente;

		// SI EL ESTADO ES DIFERENTE A PENDIENTE Y LIMITE ES MAYOR A CERO
 		if ( $limite > 0 AND $idEstadoOrden > 1 )
 			$limit = " LIMIT " . $limite;

 		if ( $idOrdenCliente > 0 )
			$where = " WHERE idOrdenCliente = " . $idOrdenCliente;

		else
			$where = " WHERE idEstadoOrden = " . $idEstadoOrden;

 		$sql = "SELECT 
					idOrdenCliente, numeroTicket, usuarioResponsable, idEstadoOrden, estadoOrden, fechaRegistro, numMenu
				FROM vOrdenCliente $where ORDER BY idOrdenCliente ASC " . $limit;

		if( $rs = $this->con->query( $sql ) ) {
 			if ( $idOrdenCliente > 0 AND ( $row = $rs->fetch_object() ) ) {
				$lst = $row;
 			}
 			else {
				while ( $row = $rs->fetch_object() )
 					$lst[] = $row;
 			}
 		}

 		return $lst;
 	}


 	public function lstDetalleOrdenCliente( $idOrdenCliente, $todo = false )
 	{
		$lst   = array();
		$total = 0;
		$where = "";

		if ( !$todo )
			$where = " AND idEstadoDetalleOrden != 10 ";

 		$sql = "SELECT 
				    idDetalleOrdenMenu,
				    cantidad,
				    idMenu,
				    menu,
				    perteneceCombo,
				    imagen,
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
				$img = ( $row->perteneceCombo ? $row->imagenCombo : $row->imagen );

				$img = file_exists( $img ) ? $img : 'img-menu/notFound.png';

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

				// REVISA SI YA EXISTE MENU
				foreach ( $lst as $ix => $item ):
					if ( 	$row->idCombo == $item->idCombo 
						AND $row->idMenu == $item->idMenu 
						AND $row->idTipoServicio == $item->idTipoServicio ) 
					{
						$index = $ix;
						break;
					}
				endforeach;

				// SI NO EXISTE EN LISTADO
				if ( $index == -1 ) {
					$index = count( $lst );
					// AGREGA UNA NUEVA ORDEN
					$lst[ $index ] = (object)array(
						'idCombo'        => $row->idCombo,
						'idMenu'         => $row->idMenu,
						'esCombo'        => $row->perteneceCombo,
						'cantidad'       => 0,
						'precio'         => $precioMenu,
						'subTotal'       => 0,
						'descripcion'    => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'imagen'         => ( strlen( (string)$img ) ? $img : 'img-menu/notFound.png' ),
						'idTipoServicio' => $row->idTipoServicio,
						'tipoServicio'   => $row->tipoServicio,
						'lstDetalle'     => array()
					);
				}

				$lst[ $index ]->cantidad += $row->cantidad;
				$lst[ $index ]->subTotal = ( $lst[ $index ]->cantidad * $precioMenu );

				// SUMA EL TOTAL DE LA ORDEN
				$total += (double)$precioMenu;

				// AGREGA DETALLE DE ORDEN
				$lst[ $index ]->lstDetalle[] = (object)array(
					'idDetalleOrdenMenu' => $row->idDetalleOrdenMenu,
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
				);
			}
 		}

 		return array(
			'lst'   => $lst,
			'total' => $total
 		);
 	}

 	// LISTA LAS ORDENES POR MENU
 	public function lstOrdenPorMenu( $idEstadoDetalleOrden, $idDestinoMenu )
 	{
		$idEstadoDetalleOrden = (int)$idEstadoDetalleOrden;
		$idDestinoMenu        = (int)$idDestinoMenu;
		$where    = $limit 	  = "";

		$lst = array();

		// SI ESTA DEFINIDO EL USUARIO RESPONSABLE
		$where = " AND ( responsableDetalle = '{$this->sess->getUsuario()}' OR usuarioDetalle = '{$this->sess->getUsuario()}' ) ";

		if ( ( $idEstadoDetalleOrden != 1 AND $idEstadoDetalleOrden != 2 ) )
			$limit = " LIMIT 50";

 		$sql = "SELECT 
					idDetalleOrdenMenu,
				    idOrdenCliente,
				    numeroTicket,
					cantidad,
					idMenu,
					menu,
					codigoMenu,
					perteneceCombo,
					imagen,
					idTipoServicio,
					tipoServicio,
					idDetalleOrdenCombo,
					idCombo,
				    combo,
					imagenCombo,
				    idDestinoMenu,
					destinoMenu,
				    responsableDetalle,
				    usuarioDetalle,
				    responsableOrden,
				    fechaRegistro,
				    tiempoAlerta
				FROM vOrdenes
				WHERE idEstadoDetalleOrden = {$idEstadoDetalleOrden} AND idDestinoMenu = {$idDestinoMenu}
					$where
				ORDER BY idDetalleOrdenMenu ASC " . $limit;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):

				$row->perteneceCombo = (bool)$row->perteneceCombo;

				// AGRUPA POR MENU //////////////////
				$ixMenu = -1;

				foreach ( $lst as $ix => $item ) {
					if ( $item->idMenu == $row->idMenu ) {
						$ixMenu = $ix;
						break;
					}
				}
			
				// SI NO EXISTE SE CREA DATOS MENU
				if ( $ixMenu == -1 ) {
					$ixMenu = count( $lst );

					$lst[] = (object)array(
						'idMenu'       => $row->idMenu,
						'codigoMenu'   => $row->codigoMenu,
						'menu'         => $row->menu,
						'imagen'       => $row->imagen,
						'tiempoAlerta' => $row->tiempoAlerta,
						'numMenus'     => 0,
						'primerTiempo' => $row->fechaRegistro,
						'detalle'      => array(),
					);
				}
				
				// AGREGA DETALL AL MENU
				$lst[ $ixMenu ]->detalle[] = (object)array(
					'perteneceCombo'      => $row->perteneceCombo,
					'idMenu'              => $row->idMenu,
					'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
					'cantidad'            => $row->cantidad,
					'fechaRegistro'       => $row->fechaRegistro,
					'tipoServicio'        => $row->tipoServicio,
					'idTipoServicio'      => $row->idTipoServicio,
					'idCombo'             => $row->idCombo,
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
					'imagenCombo'         => $row->imagenCombo,
				);

				$lst[ $ixMenu ]->numMenus += (int)$row->cantidad;
			endwhile;
		}

		return $lst;
 	}

 	// LISTA LAS ORDENES DEL CLIENTE POR TICKET
 	public function lstOrdenPorTicket( $idEstadoOrden, $idDestinoMenu, $idEstadoDetalleOrden, $idOrdenCliente = NULL )
 	{
		$where = $limit = "";

		$lst = array();

		if ( IS_NULL( $idOrdenCliente ) )
			$where = " AND ( responsableDetalle = '{$this->sess->getUsuario()}' OR usuarioDetalle = '{$this->sess->getUsuario()}' ) ";

		else
			$where = " AND idOrdenCliente = {$idOrdenCliente} ";


		//// VALIDA EL ESTADO DE ORDEN ////
		if ( $idEstadoOrden == 'valid' )
			$where .= " AND ( idEstadoOrden = 1 OR idEstadoOrden = 2 ) ";

		else if ( $idEstadoOrden != 'all' )
			$where .= " AND ( idEstadoOrden = {$idEstadoOrden} ) ";


		//// SI EL DESTINO ESTA DEFINIDO ////
		if ( $idDestinoMenu != 'all' AND (int)$idDestinoMenu > 0 )
			$where .= " AND idDestinoMenu = {$idDestinoMenu} ";


		//// SI EL DESTINO ESTA DEFINIDO ////
		if ( $idEstadoDetalleOrden == 'valid' )
			$where .= " AND ( idEstadoDetalleOrden BETWEEN 1 AND 4 ) ";

		else if ( $idEstadoDetalleOrden != 'all' )
			$where .= " AND idEstadoDetalleOrden = {$idEstadoDetalleOrden} ";


		$sql = "SELECT
					idOrdenCliente,
				    idEstadoOrden,
				    numeroTicket,
				    responsableOrden,
				    idDetalleOrdenMenu,
				    cantidad,
				    idMenu,
				    menu,
				    idEstadoDetalleOrden,
				    estadoDetalleOrden,
				    tiempoAlerta,
				    perteneceCombo,
				    imagen,
				    idDetalleOrdenCombo,
				    idCombo,
				    combo,
				    imagenCombo,
				    idTipoServicio,
				    tipoServicio,
				    responsableDetalle,
				    usuarioDetalle,
				    fechaRegistro
				FROM vOrdenes 
				WHERE TRUE $where
				ORDER BY idDetalleOrdenCombo ASC, idDetalleOrdenMenu ASC " . $limit;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):
				$row->perteneceCombo = (bool)$row->perteneceCombo;
				$img = ( $row->perteneceCombo ? $row->imagenCombo : $row->imagen );

				// AGRUPA POR TICKET //////////////////
				$ixTicket = -1;

				foreach ( $lst as $ix => $item ) {
					if ( $item->idOrdenCliente == $row->idOrdenCliente ) {
						$ixTicket = $ix;
						break;
					}
				}
			
				// SI NO EXISTE SE CREA DATOS TICKET
				if ( $ixTicket == -1 ) {
					$ixTicket = count( $lst );

					$lst[] = (object)array(
						'idOrdenCliente'   => $row->idOrdenCliente,
						'numeroTicket'     => $row->numeroTicket,
						'responsableOrden' => $row->responsableOrden,
						'total' 		   => (object)array(
							'total'      => 0,
							'pendientes' => 0,
							'cocinando'  => 0,
							'listos'     => 0,
							'servidos'   => 0,
						),
						'lstMenu'          => array(),
					);
				}

				
				$lst[ $ixTicket ]->lstMenu[] = (object)array(
					'idDetalleOrdenMenu'   => $row->idDetalleOrdenMenu,
					'idDetalleOrdenCombo'  => $row->idDetalleOrdenCombo,
					'idEstadoDetalleOrden' => $row->idEstadoDetalleOrden,
					'idCombo'              => $row->idCombo,
					'idMenu'               => $row->idMenu,
					'esCombo'              => $row->perteneceCombo,
					'descripcion'          => ( $row->perteneceCombo ? $row->menu . " (" . $row->combo . ")" : $row->menu ),
					'imagen'               => ( strlen( (string)$img ) ? $img : 'img-menu/notFound.png' ),
					'idTipoServicio'       => $row->idTipoServicio,
					'tipoServicio'         => $row->tipoServicio,
				);

				$lst[ $ixTicket ]->total->total++;

				if ( $row->idEstadoDetalleOrden == 1 )
					$lst[ $ixTicket ]->total->pendientes++;

				else if ( $row->idEstadoDetalleOrden == 2 )
					$lst[ $ixTicket ]->total->cocinando++;

				else if ( $row->idEstadoDetalleOrden == 3 )
					$lst[ $ixTicket ]->total->listos++;

				else if ( $row->idEstadoDetalleOrden == 4 )
					$lst[ $ixTicket ]->total->servidos++;


				//$lst[ $ixTicket ]->lstMenu[ $index ]->cantidad += $row->cantidad;

				/*
				// AGREGA DETALLE DE ORDEN
				$lst[ $ixTicket ]->lstMenu[ $index ]->lstDetalle[] = (object)array(
					'idDetalleOrdenMenu' => $row->idDetalleOrdenMenu,
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
				);*/






				/*

				// AGRUPA POR TICKET //////////////////
				$ixTicket = -1;

				foreach ( $lst as $ix => $item ) {
					if ( $item->idOrdenCliente == $row->idOrdenCliente ) {
						$ixTicket = $ix;
						break;
					}
				}
			
				// SI NO EXISTE SE CREA DATOS TICKET
				if ( $ixTicket == -1 ) {
					$ixTicket = count( $lst );

					$lst[] = (object)array(
						'idOrdenCliente'   => $row->idOrdenCliente,
						'numeroTicket'     => $row->numeroTicket,
						'responsableOrden' => $row->responsableOrden,
						'numMenus'         => 0,
						'lstMenu'          => array(),
					);
				}
				
				$ixMenu = -1;

				// VERIFICAR SI EXISTE MENU
				foreach ( $lst[ $ixTicket ]->lstMenu as $ix => $item ):

					if ( $item->idTipoServicio == $row->idTipoServicio AND 
						$item->perteneceCombo == $row->perteneceCombo AND 
						$item->idMenu == $row->idMenu ) 
					{
						$ixMenu = $ix;
						break;
					}

				endforeach;

				// AGREGA MENU AL TICKET
				if ( $ixMenu == -1 ):
					$ixMenu = count( $lst[ $ixTicket ]->lstMenu );
					
					$lst[ $ixTicket ]->lstMenu[] = (object)array(
						'perteneceCombo' => $row->perteneceCombo,
						'idMenu'         => $row->idMenu,
						'menu'           => $row->menu,
						'imagen'         => $row->imagen,
						'idCombo'        => $row->idCombo,
						'combo'          => $row->combo,
						'imagenCombo'    => $row->imagenCombo,
						'cantidad'       => 0,
						'idTipoServicio' => $row->idTipoServicio,
						'tipoServicio'   => $row->tipoServicio,
						'detalle'        => array(),
					);
					
					$lst[ $ixTicket ]->numMenus += (int)$row->cantidad;
				endif;


				$lst[ $ixTicket ]->lstMenu[ $ixMenu ]->cantidad += (int)$row->cantidad;
				$lst[ $ixTicket ]->lstMenu[ $ixMenu ]->detalle[] = (object)array(
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
					'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
					'fechaRegistro'       => $row->fechaRegistro,
					'tiempoAlerta'        => $row->tiempoAlerta,
				);
				*/

			endwhile;
		}

		return $lst;
 	}

 	public function menuPorCodigo( $codigoRapido )
 	{
		$datos        = (object)array( "menu" => NULL, "tipoMenu" => NULL );
		$codigoRapido = (int)$codigoRapido;

 		$sql = "SELECT idMenu, codigo, 'menu' AS 'tipoMenu'
					FROM menu WHERE codigo = {$codigoRapido}
				UNION 
				SELECT idCombo AS 'idMenu', codigo, 'combo' AS 'tipoMenu'
					FROM combo WHERE codigo = {$codigoRapido} ";
		if ( $rs = $this->con->query( $sql ) ) {
			if ( $row = $rs->fetch_object() ) {

				// SI ES MENU
				if ( $row->tipoMenu == 'menu' ) {
					$menu = new Menu();
					$info = $menu->lstMenu( 0, NULL, $row->idMenu );

					$datos->tipoMenu = 'menu';
					$datos->menu     = (object)array(
						'idMenu'    => $info->idMenu,
						'menu'      => $info->menu,
						'imagen'    => $info->imagen,
						'cantidad'  => 1,
						'precio'    => 0.00,
						'lstPrecio' => $menu->cargarMenuPrecio( $row->idMenu )
					);
				}

				// SI ES COMBO
				else if ( $row->tipoMenu == 'combo' ) {
					$combo = new Combo();
					$info  = $combo->lstCombo( 0, $row->idMenu );

					$datos->tipoMenu = 'combo';
					$datos->menu     = (object)array(
						'idMenu'    => $info->idCombo,
						'menu'      => $info->combo,
						'imagen'    => $info->imagen,
						'cantidad'  => 1,
						'precio'    => 0.00,
						'lstPrecio' => $combo->cargarComboPrecio( $row->idMenu )
					);
				}

			}
		}

 		return $datos;
 	}

 	public function busquedaTicket( $ticket )
 	{
 		$lst = array();

 		$sql = "SELECT
					idOrdenCliente,
				    numeroTicket,
				    usuarioResponsable,
				    usuarioPropietario,
				    idEstadoOrden,
				    estadoOrden,
				    fechaRegistro,
				    numMenu
				FROM vOrdenCliente 
				WHERE ( DATE( fechaRegistro ) = CURDATE() OR idEstadoOrden = 1 ) AND numeroTicket = {$ticket}
				ORDER BY idOrdenCliente DESC;";

		if ( $rs = $this->con->query( $sql ) ) {

			while( $row = $rs->fetch_object() ) {
				$lst[] = $row;
			}
		}

		return $lst;
 	}

 	// CAMBIA TIPO SERVICIO ORDEN
 	public function cambiarServicio( $idOrdenCliente, $lstDetalle, $idTipoServicio )
 	{

 		if ( count( $lstDetalle ) AND $idOrdenCliente > 0 AND $idTipoServicio > 0 ):
 			$validar = new Validar();

		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalle as $ix => $item ):

				$item->idDetalleOrdenCombo = (int)$item->idDetalleOrdenCombo;
				$item->idDetalleOrdenMenu  = (int)$item->idDetalleOrdenMenu;

				if ( $item->idDetalleOrdenMenu > 0 )
					$sql = "CALL consultaDetalleOrdenMenu( 'tipoServicio', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, NULL, {$idTipoServicio}, NULL, NULL );";
				
				else if ( $item->idDetalleOrdenCombo > 0 )
					$sql = "CALL consultaDetalleOrdenCombo( 'tipoServicio', {$item->idDetalleOrdenCombo}, NULL, NULL, NULL, NULL, {$idTipoServicio}, NULL, NULL );";

		 		if( $rs = $this->con->query( $sql ) ) {
		 			@$this->con->next_result();
		 			if( $row = $rs->fetch_object() ) {
						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la consulta.';
		 			break;
		 		}
		 		
		 		if ( $this->respuesta == 'danger' )
		 			break;

			endforeach;

			// SI SE GUARDO CORRECTAMENTE
			if ( $this->respuesta == 'success' ) {
		 		$this->con->query( "COMMIT" );

			 	$infoNode = (object)array(
					'accion' => 'cambioTipoServicio',
					'data'   => array(
						'idOrdenCliente'      => $idOrdenCliente,
						'detalleOrdenCliente' => $this->lstDetalleOrdenCliente( $idOrdenCliente ),
				 	),
				);
			 	
			 	// SI LA CLASE NO EXISTE SE LLAMA
			 	if ( !class_exists( "Redis" ) )
			 		include 'redis.class.php';

			 	// ENVIA LOS DATOS POR MEDIO DE REDIS
			 	$red = new Redis();
				$red->messageRedis( $infoNode );
			}
			
			// SI OCURRIO ALGUN ERROR
		 	else
		 		$this->con->query( "ROLLBACK" );

 		endif;

 		return $this->getRespuesta();
 	}

 	// SI SE CANCELA LA ORDEN PRINCIPAL
 	public function ordenPrincipalCancelada( $idOrdenCliente )
 	{
	 	// SI LA CLASE NO EXISTE SE LLAMA
	 	if ( !class_exists( "Redis" ) )
	 		include 'redis.class.php';

	 	// ENVIA LOS DATOS POR MEDIO DE REDIS
	 	$red = new Redis();
		$red->messageRedis( 
			(object)array(
				'accion'         => 'ordenPrincipalCancelada',
				'idOrdenCliente' => $idOrdenCliente,
			) 
		);
 	}

 	// CANCELAR ORDEN DE MANERA PARCIAL
 	public function cancelarOrdenParcial( $idOrdenCliente, $lstDetalle )
 	{

		$count       = count( $lstDetalle );
		$nCancelados = 0;

 		if ( $count AND $idOrdenCliente > 0 ):
 			$validar = new Validar();

		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalle as $ix => $item ):

				$item->idDetalleOrdenCombo = (int)$item->idDetalleOrdenCombo;
				$item->idDetalleOrdenMenu  = (int)$item->idDetalleOrdenMenu;

				if ( $item->idDetalleOrdenMenu > 0 )
					$sql = "CALL consultaDetalleOrdenMenu( 'cancel', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, NULL, NULL, NULL, NULL );";
				
				else if ( $item->idDetalleOrdenCombo > 0 )
					$sql = "CALL consultaDetalleOrdenCombo( 'cancel', {$item->idDetalleOrdenCombo}, NULL, NULL, NULL, NULL, NULL, NULL, NULL );";

		 		if( $rs = $this->con->query( $sql ) ) {
		 			@$this->con->next_result();
		 			if( $row = $rs->fetch_object() ) {
						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la consulta.';
		 			break;
		 		}
		 		
		 		if ( $this->respuesta == 'success' )
		 			$nCancelados++;

		 		if ( $this->respuesta == 'danger' )
		 			break;

			endforeach;

			// SI SE GUARDO CORRECTAMENTE
			if ( $this->respuesta == 'success' OR $this->respuesta == 'warning' ) {

		 		$this->con->query( "COMMIT" );
				$this->respuesta = 'success';

				if ( $count == $nCancelados )
		 			$this->mensaje = 'Cancelado correctamente';

				else
		 			$this->mensaje = 'Se cancelaron ' . ( $count - $nCancelados ) . " de " . $count; 

			 	$infoNode = (object)array(
					'accion' => 'cancelarOrdenParcial',
					'data'   => array(
						'idOrdenCliente' => $idOrdenCliente,
						'lstDetalle'     => $lstDetalle,
				 	),
				);
			 	
			 	// SI LA CLASE NO EXISTE SE LLAMA
			 	if ( !class_exists( "Redis" ) )
			 		include 'redis.class.php';

			 	// ENVIA LOS DATOS POR MEDIO DE REDIS
			 	$red = new Redis();
				$red->messageRedis( $infoNode );
			}
			
			// SI OCURRIO ALGUN ERROR
		 	else
		 		$this->con->query( "ROLLBACK" );

 		endif;

 		return $this->getRespuesta();
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