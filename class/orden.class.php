<?php
/**
* ORDEN
*/
class Orden
{

 	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $myId      = NULL;
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
		$idEstadoOrden      = "NULL";

		// SETEO DE VARIABLES
		$numeroTicket = ( isset( $data->numeroTicket ) && $data->numeroTicket > 0 ) ? (int)$data->numeroTicket : "NULL";
		$lstUsuarios  = isset( $data->lstU ) ? $data->lstU : array();

		// VERIFICA EL ULTIMO USUARIO
		$usuarioResponsable = $this->sess->getUsuario();

		// SETEO DE VARIABLES
		$validar = new Validar();
		$comentario          = ( isset( $data->comentario ) AND strlen( $data->comentario ) > 3 ) ? "'" . $this->con->real_escape_string( $data->comentario ) . "'" : "NULL";
		$idOrdenCliente      = ( isset( $data->idOrdenCliente ) && (int)$data->idOrdenCliente > 0 )? (int)$data->idOrdenCliente : "NULL";
		$data->idEstadoOrden = isset( $data->idEstadoOrden )	? (int)$data->idEstadoOrden	 : "NULL";


		if( $accion == 'update' ):
			$numeroTicket = $validar->validarEntero( $data->numeroTicket, NULL, TRUE, 'El No. de Ticket es válido' );
		
		endif;


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:
		 	$this->con->query( "START TRANSACTION" );

	 		$sql = "CALL consultaOrdenCliente( '{$accion}', {$idOrdenCliente}, {$numeroTicket}, '{$usuarioResponsable}', {$idEstadoOrden}, $comentario )";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 		@$this->con->next_result();

	 			$this->respuesta = $row->respuesta;
	 			$this->mensaje   = $row->mensaje;
	 			if( $accion == 'insert' AND $row->respuesta == 'success' )
	 				$this->data = (int)$row->id;

	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}

	 		if ( $this->respuesta == 'success' )
	 		{
		 		$this->con->query( "COMMIT" );

	 			if ( $accion == 'cancel' AND $this->respuesta == 'success' )
	 				$this->ordenPrincipalCancelada( $idOrdenCliente );
	 		}
		 	else
		 		$this->con->query( "ROLLBACK" );

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

		$observacion = isset( $data->observacion ) AND strlen( $data->observacion ) > 3 ? "'" . $data->observacion . "'" : 'NULL';

		$idDetalleOrdenMenu = $validar->validarEntero( $data->idDetalleOrdenMenu, NULL, TRUE, 'El No. del Detalle de Orden no es válido' );

	
		// INICIO TRANSACCION
		$this->con->query( "START TRANSACTION" );


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

	 		$sql = "CALL consultaDetalleOrdenMenu( '{$accion}', {$idDetalleOrdenMenu}, {$idMenu}, {$cantidad}, {$idEstadoDetalleOrden}, {$idTipoServicio}, {$idFactura}, {$usuarioResponsable}, {$observacion}, NULL );";

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

		if ( $this->respuesta == 'success')
			$this->con->query( "COMMIT" );

		else
			$this->con->query( "ROLLBACK" );


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
		$observacion = isset( $data->observacion ) AND strlen( $data->observacion ) > 3 ? "'" . $data->observacion . "'" : 'NULL';

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

	 		$sql = "CALL consultaDetalleOrdenCombo( '{$accion}', {$idDetalleOrdenMenu}, {$idCombo}, {$cantidad}, {$idEstadoDetalleOrden}, {$idTipoServicio}, {$idFactura}, {$usuarioResponsable}, {$observacion}, NULL );";

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

			$validar   = new Validar();
			$notificar = false;

 			$idOrdenesMenu = $idOrdenesCombo = "";
		 	
		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalleOrden as $ix => $item ):
				$item->idMenu         = (int)$item->idMenu > 0			? (int)$item->idMenu			: 0;
				$item->cantidad       = (int)$item->cantidad > 0		? (int)$item->cantidad			: 0;
				$item->precio         = (double)$item->precio;
				$item->idTipoServicio = (int)$item->idTipoServicio > 0	? (int)$item->idTipoServicio	: 0;
				$observacion          = ( isset( $item->observacion ) AND strlen( $item->observacion ) > 3 ) ? "'" . $item->observacion . "'" : 'NULL';

				if ( $item->tipoMenu == 'menu' )
					$sql = "CALL consultaDetalleOrdenMenu( 'insert', NULL, {$idOrdenCliente}, {$item->idMenu}, {$item->cantidad}, NULL, {$item->idTipoServicio}, NULL, NULL, {$observacion}, NULL );";
				
				else if ( $item->tipoMenu == 'combo' )
					$sql = "CALL consultaDetalleOrdenCombo( 'insert', NULL, {$idOrdenCliente}, {$item->idMenu}, {$item->cantidad}, NULL, {$item->idTipoServicio}, NULL, NULL, {$observacion}, NULL );";

				// SI NO ES MENU PERSONALIZADO
				if ( $item->tipoMenu != 'personalizado' ):
					$notificar = true; // SI SE VA A NOTIFICAR

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
			 			$this->mensaje   = 'Error al ejecutar la instrucción: ' . $this->con->error;
			 			break;
			 		}
				endif;

				// SI ES UN MENU PERSONALIZADO
				if ( $item->tipoMenu == 'personalizado' ):
					$sql = "INSERT INTO menuPersonalizado ( idOrdenCliente, cantidad, descripcion, precioUnidad, observacion )
							VALUES ( {$idOrdenCliente}, {$item->cantidad}, '{$item->menu}', {$item->precio}, {$observacion} )";

					if ( $this->con->query( $sql ) )
						$this->respuesta = 'success';

					else{
						$this->respuesta = 'danger';
						$this->mensaje   = 'Error al guardar menú personalizado';
					}

				endif;

		 		
		 		if ( $this->respuesta == 'danger' ) break;
			endforeach;

			if ( $this->respuesta == 'success' ) {
		 		$this->con->query( "COMMIT" );
				$this->mensaje = 'Guardado correctamente';

				$this->myId = uniqid();
				$this->data = $this->infoNodeOrden( $idOrdenCliente, TRUE, TRUE, TRUE );

		 		// SI ES NUEVO
			 	if ( $accionOrden == 'nuevo' ) {
				 	$infoNode = (object)array(
						'accion' => 'ordenNueva',
						'data'   => array(
							'idOrdenCliente'  => $idOrdenCliente,
							'myId'            => $this->myId,
							'info'            => $this->data,
					 	),
					);
			 	}
			 	// SI ES AGREGAR
			 	else{
			 		$infoNode = (object)array(
						'accion' => 'ordenAgregar',
						'data'   => array(
							'idOrdenCliente'      => $idOrdenCliente,
							'info'                => $this->data,
							'myId'                => $this->myId,
							'detalleOrdenCliente' => $this->lstDetalleOrdenCliente( $idOrdenCliente ),
					 	),
					);
			 	}

			 	// SI SE NOTIFICA POR NODEJS
			 	if ( $notificar ):

				 	// SI LA CLASE NO EXISTE SE LLAMA
				 	if ( !class_exists( "Redis" ) )
				 		include 'redis.class.php';

				 	// ENVIA LOS DATOS POR MEDIO DE REDIS
				 	$red = new Redis();
					$red->messageRedis( $infoNode );

			 	endif;
			}
		 	else
		 		$this->con->query( "ROLLBACK" );


 		else:
			$this->respuesta = 'danger';
			$this->mensaje   = 'No ha agregado ningun producto a la orden.';
 		endif;
 	}


 	// REASIGNAR DETALLE ORDEN
 	function reasignarDetalleOrden( $idOrdenCliente, $idOrdenClienteDestino, $lstDetalleOrden )
 	{
	 	$this->con->query( "START TRANSACTION" );

		foreach ( $lstDetalleOrden as $ix => $item ):

			if ( $item->idDetalleOrdenMenu > 0 )
				$sql = "CALL consultaDetalleOrdenMenu( 'asignarOtroCliente', {$item->idDetalleOrdenMenu}, {$idOrdenClienteDestino}, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL )";
			
			else if ( $item->tipoMenu == 'combo' )
				$sql = "CALL consultaDetalleOrdenCombo( 'asignarOtroCliente', {$item->idDetalleOrdenCombo}, {$idOrdenClienteDestino}, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ) {
					$this->respuesta = $row->respuesta;
					$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción: ' . $this->con->error;
	 			break;
	 		}
	 		
	 		if ( $this->respuesta == 'danger' ) break;
		endforeach;

		if ( $this->respuesta == 'success' ) {
	 		$this->con->query( "COMMIT" );
		}
	 	else
	 		$this->con->query( "ROLLBACK" );
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
				    fechaRegistro,
				    perteneceCombo,
    				idDetalleOrdenCombo,
    				idOrdenCliente,
    				combo,
    				numeroTicket,
    				codigoMenu,
    				tiempoAlerta,
    				observacion
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
 		if ( $limite > 0 AND $idEstadoOrden > 4 )
 			$limit = " LIMIT " . $limite;

 		if ( $idOrdenCliente > 0 )
			$where = " WHERE idOrdenCliente = " . $idOrdenCliente;

		else if ( $idEstadoOrden == 1 OR $idEstadoOrden == 2 )
			$where = " WHERE ( idEstadoOrden = 1 OR idEstadoOrden = 2 ) ";

		else if ( $idEstadoOrden > 2 )
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
		$lst      = array();
		$lstOtros = array();
		$total    = 0;
		$where    = "";

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
				    idEstadoDetalleOrdenCombo,
				    idCombo,
				    tiempoAlerta,
					GROUP_CONCAT( DISTINCT observacion SEPARATOR ' -.- ' ) AS 'observacion'
				FROM
				    vOrdenes
				WHERE
				    idOrdenCliente = {$idOrdenCliente} $where
				GROUP BY IF( perteneceCombo, idDetalleOrdenCombo, idDetalleOrdenMenu ), idDetalleOrdenMenu
				ORDER BY idDetalleOrdenMenu ASC;";

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):

				$row->idCombo             = (int)$row->idCombo?:NULL;
				$row->idMenu              = (int)$row->idMenu?:NULL;
				$row->idDetalleOrdenCombo = (int)$row->idDetalleOrdenCombo;
				$row->perteneceCombo      = (int)$row->perteneceCombo;
				$img                      = ( $row->perteneceCombo ? $row->imagenCombo : $row->imagen );

				$img = file_exists( $img ) ? $img : 'img-menu/notFound.png';

				// SI PERTENECE A COMBO
				if ( $row->perteneceCombo )
					$precioMenu = $row->precioCombo;

				else
					$precioMenu = $row->precio;

				$index = -1;

				// REVISA SI YA EXISTE MENU
				foreach ( $lst as $ix => $item ):
					if (
						$row->idTipoServicio == $item->idTipoServicio
						AND ( 
							( $row->perteneceCombo AND $row->perteneceCombo == $item->esCombo AND $row->idCombo == $item->idCombo )
							OR 
							( !$row->perteneceCombo AND $row->perteneceCombo == $item->esCombo AND $row->idMenu == $item->idMenu )
						)
					){
						$index = $ix;
						break;
					}
				endforeach;

				// SI NO EXISTE EN LISTADO
				if ( $index == -1 ) {
					$index = count( $lst );
					// AGREGA UNA NUEVA ORDEN
					$lst[ $index ] = (object)array(
						'cobrarTodo'       => TRUE,
						'descuento'        => 0,
						'cantidadRestante' => 0,
						'agrupado'         => TRUE,
						'maximo'           => 0,
						
						'idDetalleOrdenMenu'        => $row->idDetalleOrdenMenu,
						'idDetalleOrdenCombo'       => $row->idDetalleOrdenCombo,
						'comentario' => '',

						'observacion'      => $row->observacion,
						'idCombo'          => $row->idCombo,
						'idMenu'           => $row->idMenu,
						'esCombo'          => $row->perteneceCombo,
						'cantidad'         => 0,
						'precio'           => $precioMenu,
						'subTotal'         => 0,
						'descripcion'      => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'imagen'           => ( strlen( (string)$img ) ? $img : 'img-menu/notFound.png' ),
						'idTipoServicio'   => $row->idTipoServicio,
						'tipoServicio'     => $row->tipoServicio,
						'lstDetalle'       => array(),
						'lstMenus'         => array()
					);
				}

				// AGREGA DETALLE DE ORDEN
				$lst[ $index ]->lstDetalle[] = (object)array(
					'idDetalleOrdenMenu'        => $row->idDetalleOrdenMenu,
					'idDetalleOrdenCombo'       => $row->idDetalleOrdenCombo,
					'idEstadoDetalleOrden'      => $row->idEstadoDetalleOrden,
					'idEstadoDetalleOrdenCombo' => $row->idEstadoDetalleOrdenCombo,
					'observacion' 				=> $row->observacion,
					'cantidad'         => 1,
					'precio'           => $precioMenu,

				);

				// VERIFICA SI EL MENU EXISTE
				$ixMD = -1;
				foreach ( $lst[ $index ]->lstMenus as $ixm => $menu ):

					if ( $row->perteneceCombo AND $row->idDetalleOrdenCombo == $menu->idDetalleOrdenCombo )
					{
						$ixMD = $ixm;
						break;
					}
					
					else if ( !$row->perteneceCombo AND $row->idDetalleOrdenMenu == $menu->idDetalleOrdenMenu )
					{
						$ixMD = $ixm;
						break;
					}
				endforeach;

				// SI NO EXISTE EL MENU SE AGREGA
				if ( $ixMD == -1 )
					$lst[ $index ]->lstMenus[] = (object)array(
						'perteneceCombo'            => $row->perteneceCombo,
						'idDetalleOrdenMenu'        => $row->idDetalleOrdenMenu,
						'idEstadoDetalleOrden'      => $row->idEstadoDetalleOrden,
						'idDetalleOrdenCombo'       => $row->idDetalleOrdenCombo,
						'idEstadoDetalleOrdenCombo' => $row->idEstadoDetalleOrdenCombo,
					);
			endwhile;

			// RECORRE MENUS AGREGADOS
			foreach ( $lst as $ix => $menu )
			{
				$arrCombo = array();
				$count    = 0;
				$limite   = 0;
				// RECORRE DETALLE PARA CONTAR NUMERO REAL DE MENUS Y COMBOS
				foreach ( $menu->lstDetalle as $ixd => $detalle )
				{
					if ( $menu->esCombo )
					{
						$ixC = -1;
						foreach ( $arrCombo as $ic => $combo ) {
							if ( $combo == $detalle->idDetalleOrdenCombo ) {
								$ixC = $ic;
								break;
							}
						}

						// SI NO ESTA EL ID DETALLE ORDEN COMBO
						if ( $ixC == -1 )
						{
							$arrCombo[] = $detalle->idDetalleOrdenCombo;

							if ( $detalle->idEstadoDetalleOrdenCombo < 4 )
								$limite++;
						}
					}
					else{
						$count++;
						
						if ( $detalle->idEstadoDetalleOrden < 4 )
							$limite++;
					}
				}

				$lst[ $ix ]->cantidad         = $menu->esCombo ? count( $arrCombo ) : $count;
				$lst[ $ix ]->limite           = $limite;
				$lst[ $ix ]->seleccionados 	  = $limite;
				$lst[ $ix ]->cantidadRestante = $menu->esCombo ? count( $arrCombo ) : $count;
				$lst[ $ix ]->maximo           = $menu->esCombo ? count( $arrCombo ) : $count;
				$lst[ $ix ]->subTotal         = ( $lst[ $ix ]->cantidad * $menu->precio );
				
				// SUMA EL TOTAL DE LA ORDEN
				$total += (double)$lst[ $ix ]->subTotal;
			}

			$sql = "SELECT
						idMenuPersonalizado,
						idOrdenCliente,
						cantidad,
						descripcion,
						precioUnidad,
						observacion 
					FROM menuPersonalizado 
					WHERE idOrdenCliente = {$idOrdenCliente}";
			$rs = $this->con->query( $sql );

			while ( $rs AND $row = $rs->fetch_object() )
			{
				$row->cantidad     = (int)$row->cantidad;
				$row->precioUnidad = (double)$row->precioUnidad;
				$lstOtros[] = $row;
				$total += ( $row->cantidad * $row->precioUnidad );
			}
 		}

 		return array(
			'lst'      => $lst,
			'lstOtros' => $lstOtros,
			'total'    => $total
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
				    tiempoAlerta,
				    observacion
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
				
				// AGREGA DETALLE AL MENU
				$lst[ $ixMenu ]->detalle[] = (object)array(
					'perteneceCombo'      => $row->perteneceCombo,
					'numeroTicket'        => $row->numeroTicket,
					'idMenu'              => $row->idMenu,
					'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
					'cantidad'            => $row->cantidad,
					'fechaRegistro'       => $row->fechaRegistro,
					'tipoServicio'        => $row->tipoServicio,
					'idTipoServicio'      => $row->idTipoServicio,
					'idCombo'             => $row->idCombo,
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
					'imagenCombo'         => $row->imagenCombo,
					'observacion'         => $row->observacion,
				);

				$lst[ $ixMenu ]->numMenus += (int)$row->cantidad;
			endwhile;
		}

		return $lst;
 	}

 	// LISTA LAS ORDENES DEL CLIENTE POR TICKET
 	public function lstOrdenPorTicket( $idEstadoOrden, $numeroGrupo, $idOrdenCliente = NULL )
 	{
		$where = $limit = "";

		$lst = array();

		//// SOLO ESTADOS VALIDOS ////
		if ( $idEstadoOrden == 'valid' )
			$where = " AND ( o.idEstadoOrden BETWEEN 1 AND 3 ) ";

		//// SI ESTADO ESTA DEFINIDO ////
		else if ( $idEstadoOrden >= 1 )
		{
			$where = " AND idEstadoOrden = {$idEstadoOrden} ";
			$limit = "LIMIT 20";
		}


		// SI ES POR GRUPO
		if ( $numeroGrupo > 0 AND $numeroGrupo != 99 )
			$where .= " AND numeroGrupo = {$numeroGrupo} ";


		// SI ES DE UNA ORDEN ESPECIFICA
		if ( !IS_NULL( $idOrdenCliente ) AND $idOrdenCliente > 0 )
			$where .= " AND o.idOrdenCliente = {$idOrdenCliente} ";


		$sql = "SELECT
					idOrdenCliente,
				    numeroTicket,
				    numeroGrupo,
				    SUM( cantidad )AS 'total',
				    SUM( IF( idEstadoDetalleOrden = 1, cantidad, 0 ) ) AS 'pendientes',
				    SUM( IF( idEstadoDetalleOrden = 2, cantidad, 0 ) ) AS 'cocinando',
				    SUM( IF( idEstadoDetalleOrden = 3, cantidad, 0 ) ) AS 'listos',
				    SUM( IF( idEstadoDetalleOrden = 4, cantidad, 0 ) ) AS 'servidos',
					MIN( IF( idEstadoDetalleOrden BETWEEN 1 AND 3, fechaRegistro, NULL) ) AS 'primerTiempo'
				FROM((SELECT
						o.idOrdenCliente,
						numeroGrupo,
						numeroTicket, 
						SUM( dom.cantidad )AS 'cantidad',
						dom.idEstadoDetalleOrden,
						MIN( fechaRegistro ) AS 'fechaRegistro'
					FROM ordenCliente as o
						JOIN detalleOrdenMenu As dom
							ON o.idOrdenCliente = dom.idOrdenCliente
								AND !dom.perteneceCombo
								AND ( idEstadoDetalleOrden BETWEEN 1 AND 4 )
					WHERE TRUE $where
					GROUP BY o.idOrdenCliente, dom.idEstadoDetalleOrden
					ORDER BY fechaRegistro ASC)
						UNION ALL
					(SELECT
						o.idOrdenCliente, 
						numeroGrupo,
						numeroTicket, 
						SUM( doc.cantidad )AS 'cantidad',
						doc.idEstadoDetalleOrden,
						MIN( fechaRegistro ) AS 'fechaRegistro'
					FROM ordenCliente as o
						JOIN detalleOrdenCombo As doc
							ON o.idOrdenCliente = doc.idOrdenCliente
								AND ( idEstadoDetalleOrden BETWEEN 1 AND 4 )
					WHERE TRUE $where
					GROUP BY o.idOrdenCliente, doc.idEstadoDetalleOrden
					ORDER BY fechaRegistro ASC))ord
				GROUP BY idOrdenCliente
				ORDER BY idOrdenCliente ASC" . $limit;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):
				$row->total      = (int)$row->total;
				$row->pendientes = (int)$row->pendientes;
				$row->cocinando  = (int)$row->cocinando;
				$row->listos     = (int)$row->listos;
				$row->servidos   = (int)$row->servidos;
				$lst[]           = $row;
			endwhile;
		}

		return $lst;
 	}

 	public function menuPorCodigo( $codigoRapido, $cantidad, $idTipoServicio )
 	{
		$datos        = (object)array( "menu" => NULL, "tipoMenu" => NULL );
		$codigoRapido = (int)$codigoRapido;

 		$sql = "SELECT m.idMenu, m.codigo, 'menu' AS 'tipoMenu', mp.precio
					FROM menu AS m
						JOIN menuPrecio AS mp
							ON m.idMenu = mp.idMenu AND mp.idTipoServicio = {$idTipoServicio}
				    WHERE m.codigo = {$codigoRapido}
				UNION 
				SELECT c.idCombo AS 'idMenu', c.codigo, 'combo' AS 'tipoMenu', cp.precio
					FROM combo AS c
						JOIN comboPrecio AS cp
							ON c.idCombo = cp.idCombo AND cp.idTipoServicio = {$idTipoServicio}
				    WHERE c.codigo = {$codigoRapido} ";
		if ( $rs = $this->con->query( $sql ) ) {
			if ( $row = $rs->fetch_object() ) {

				// SI ES MENU
				if ( $row->tipoMenu == 'menu' ) {
					$menu = new Menu();
					$info = $menu->lstMenu( 0, NULL, $row->idMenu );

					$datos->tipoMenu = 'menu';
					$datos->menu     = (object)array(
						'idMenu'               => $info->idMenu,
						'menu'                 => $info->menu,
						'imagen'               => $info->imagen,
						'cantidad'             => 1,
						'precio'               => (double)$row->precio,
						'lstPrecio'            => $menu->cargarMenuPrecio( $row->idMenu ),
						'lstSinDisponibilidad' => $this->obtenerDisponiblidad( $cantidad, $row->idMenu, NULL ),
					);
				}

				// SI ES COMBO
				else if ( $row->tipoMenu == 'combo' ) {
					$combo = new Combo();
					$info  = $combo->lstCombo( 0, $row->idMenu );

					$datos->tipoMenu = 'combo';
					$datos->menu     = (object)array(
						'idMenu'               => $info->idCombo,
						'menu'                 => $info->combo,
						'imagen'               => $info->imagen,
						'cantidad'             => 1,
						'precio'               => (double)$row->precio,
						'lstPrecio'            => $combo->cargarComboPrecio( $row->idMenu ),
						'lstSinDisponibilidad' => $this->obtenerDisponiblidad( $cantidad, NULL, $row->idMenu ),
					);
				}

			}
		}

 		return $datos;
 	}

 	public function precioDisponibilidad( $idMenu, $idCombo, $idTipoServicio, $cantidad )
 	{
		$datos   = (object)array( "precio" => NULL, "lstSinDisponibilidad" => NULL );
		$idMenu  = (int)$idMenu;
		$idCombo = (int)$idCombo;

		if ( $idMenu > 0 )
		{
			$sql = "SELECT precio FROM menuPrecio 
					WHERE idMenu = {$idMenu} AND idTipoServicio = {$idTipoServicio} ";
			$datos->lstSinDisponibilidad = $this->obtenerDisponiblidad( $cantidad, $idMenu, NULL );
		}
		else
		{
			$sql = "SELECT precio FROM comboPrecio 
					WHERE idCombo = {$idCombo} AND idTipoServicio = {$idTipoServicio} ";
			$datos->lstSinDisponibilidad = $this->obtenerDisponiblidad( $cantidad, NULL, $idCombo );
		}

		if ( $rs = $this->con->query( $sql ) ) {

			if ( $row = $rs->fetch_object() )
				$datos->precio = (double)$row->precio;
		}

 		return $datos;
 	}

 	public function obtenerDisponiblidad( $cantidad, $idMenu, $idCombo )
	{
		$sinDisponiblidad = array();
		
		$cantidad = (int)$cantidad;
		$idMenu   = (int)$idMenu ?: 'NULL';
		$idCombo  = (int)$idCombo ?: 'NULL';

		$sql = "SELECT obtenerDisponiblidad( {$idMenu}, {$idCombo}, {$cantidad} ) AS 'resultado'";

		$rs = $this->con->query( $sql );

		if( $rs AND $row = $rs->fetch_object() ):

			$productos = explode( "=r=", $row->resultado );

			foreach ( $productos as $producto )
			{
				$producto = explode( "#i#", $producto );

				if ( count( $producto ) == 4 )
					$sinDisponiblidad[] = array(
						"cantidadRequerida" => $producto[ 0 ],
						"disponibilidad"    => $producto[ 1 ],
						"producto"          => $producto[ 2 ],
						"medida"            => $producto[ 3 ],
					);
			}

		endif;

		return $sinDisponiblidad;
	}

 	public function busquedaTicket( $ticket, $idOrdenCliente = 0 )
 	{
 		$lst = array();

 		if ( (int)$ticket > 0 )
 			$where = " AND numeroTicket = {$ticket} ";

 		else
 			$where = " AND idOrdenCliente = {$idOrdenCliente} ";

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
				/*WHERE DATE( fechaRegistro ) >= DATE_SUB( CURDATE(), INTERVAL 1 DAY ) $where */
				WHERE ( idEstadoOrden <> 5 AND idEstadoOrden <> 6 AND idEstadoOrden <> 10 )  $where
				ORDER BY idOrdenCliente DESC LIMIT 5";

		if ( $rs = $this->con->query( $sql ) ) {
			while( $row = $rs->fetch_object() )
				$lst[] = $row;
		}

		return $lst;
 	}

 	// CAMBIA TIPO SERVICIO ORDEN
 	public function cambiarServicio( $idOrdenCliente, $lstDetalle )
 	{
 		if ( count( $lstDetalle ) AND $idOrdenCliente > 0 ):
 			$validar = new Validar();

		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalle as $ix => $item ):

				$item->idDetalleOrdenCombo = (int)$item->idDetalleOrdenCombo;
				$item->idDetalleOrdenMenu  = (int)$item->idDetalleOrdenMenu;
				$idTipoServicio            = (int)$item->idTipoServicio;

				if ( $item->idDetalleOrdenCombo > 0 )
					$sql = "CALL consultaDetalleOrdenCombo( 'tipoServicio', {$item->idDetalleOrdenCombo}, NULL, NULL, NULL, NULL, {$idTipoServicio}, NULL, NULL, NULL, NULL );";

				else if ( $item->idDetalleOrdenMenu > 0 )
					$sql = "CALL consultaDetalleOrdenMenu( 'tipoServicio', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, NULL, {$idTipoServicio}, NULL, NULL, NULL, NULL );";
				

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
				//'lstDetalle'     => $this->lstMenuAgregado( "", "", $idOrdenCliente ),
			) 
		);
 	}

 	// CANCELAR ORDEN DE MANERA PARCIAL
 	public function cancelarOrdenParcial( $idOrdenCliente, $lstDetalle, $comentario = "" )
 	{
		$nCancelados = 0;

		// SI ES MENU PERSONALIZADO
		if ( !is_array( $lstDetalle ) ):

			$sql = "DELETE FROM menuPersonalizado WHERE idMenuPersonalizado = {$lstDetalle->idMenuPersonalizado}";

		 	if ( $this->con->query( $sql ) ) {
		 		$this->respuesta = 'success';
				$this->mensaje   = 'Cancelado correctamente';
		 	}
		 	else
			{
				$this->respuesta = 'danger';
				$this->mensaje   = 'No se pudo cancelar el menú';
			}

		// SI ES ARREGLO
 		elseif ( count( $lstDetalle ) AND $idOrdenCliente > 0 ):
 			$validar = new Validar();

			$comentario = $this->con->real_escape_string( $comentario );
			$comentario = strlen( $comentario ) > 3 ? "'" . $comentario . "'" : 'NULL';
			
		 	$this->con->query( "START TRANSACTION" );

			foreach ( $lstDetalle as $ix => $item ):

				$item->idDetalleOrdenCombo = (int)$item->idDetalleOrdenCombo;
				$item->idDetalleOrdenMenu  = (int)$item->idDetalleOrdenMenu;

				if ( $item->idDetalleOrdenCombo > 0 )
					$sql = "CALL consultaDetalleOrdenCombo( 'cancel', {$item->idDetalleOrdenCombo}, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, {$comentario} );";

				else if ( $item->idDetalleOrdenMenu > 0 )
					$sql = "CALL consultaDetalleOrdenMenu( 'cancel', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, {$comentario} );";

		 		if( $rs = $this->con->query( $sql ) ) {
		 			@$this->con->next_result();
		 			if( $row = $rs->fetch_object() ) {
						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la consulta.' . $this->con->error;
		 			break;
		 		}
		 		
		 		if ( $this->respuesta == 'success' )
		 			$nCancelados++;

		 		if ( $this->respuesta == 'danger' )
		 			break;

			endforeach;

			// SI SE GUARDO CORRECTAMENTE
			if ( $this->respuesta == 'success' OR $this->respuesta == 'warning' ) {

				if ( $this->respuesta == 'warning' )
				{
					$this->mensaje   = 'No se cancelaron todas las ordenes';
					$this->respuesta = 'success';
				}

		 		$this->con->query( "COMMIT" );
				$this->myId = uniqid();

				$this->data = $this->infoNodeOrden( $idOrdenCliente, TRUE, TRUE, TRUE );

			 	$infoNode = (object)array(
					'accion' => 'cancelarOrdenParcial',
					'myId'   => $this->myId,
					'data'   => array(
						'info'           => $this->data,
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

 	// DETALLE DE TICKET
 	// ORDEN PARA COCINA
 	// DETALLE 

 	public function infoNodeOrden( $idOrdenCliente, $paraMesero = FALSE, $paraCocina = FALSE, $paraTicket = FALSE )
 	{
 		$result = new stdClass();

 		$result->idOrdenCliente = (int)$idOrdenCliente;

 		// SI ES PARA COCINA
 		if ( $paraCocina ):
			$result->paraCocina = $this->consultaOrdenesCocina( 1, 0, 0, $idOrdenCliente );

 		endif;

 		// SI ES PARA MESERO
 		if ( $paraMesero ):
			$result->paraMesero = $this->lstOrdenCliente( 1, NULL, $idOrdenCliente );

 		endif;

 		// SI ES PARA VISTA TICKET
 		if ( $paraTicket ):
			$result->paraTicket = $this->lstOrdenPorTicket( 'valid', 0, $idOrdenCliente );

 		endif;

 		return $result;
 	}


 	// CAMBIA ESTADO DE ORDENES ---> DETALLE
 	public function cambioEstadoOrden( $idEstadoOrden, $lstOrdenes )
 	{
 		$this->con->query( "START TRANSACTION" );

		foreach ( $lstOrdenes as $ix => $item ):
			$sql = "CALL consultaDetalleOrdenMenu( 'estado', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, {$idEstadoOrden}, NULL, NULL, NULL, NULL, NULL );";
			
	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ) {
					$this->respuesta = $row->respuesta;
					$this->mensaje   = $row->mensaje;
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
		 	$infoNode = (object)array(
				'accion' => 'cambioEstadoDetalleOrden',
				'data'   => array( 
					'lstOrdenes'    => $lstOrdenes,
					'idEstadoOrden' => $idEstadoOrden,
				),
			);

		 	// SI LA CLASE NO EXISTE SE LLAMA
		 	if ( !class_exists( "Redis" ) )
		 		include 'redis.class.php';

		 	// ENVIA LOS DATOS POR MEDIO DE REDIS
		 	$red = new Redis();
			$red->messageRedis( $infoNode );
		}
	 	else
	 		$this->con->query( "ROLLBACK" );

	 	return $this->getRespuesta();
 	}

 	// CAMBIA ESTADO DE ORDENES ---> %%%%%%%%%%% COCINA %%%%%%%%%%%
 	public function cambioEstadoCocina( $idEstadoOrden, $info )
 	{
		$cantidadMenus = (int)$info->seleccionados;
		$this->myId    = uniqid();

 		if ( !( $cantidadMenus > 0 ) )
 			return array( 
 				'respuesta' => 'info',
	 			'mensaje'   => 'Nada que hacer',
	 		);

		$idEstadoOrdenActual = ( $idEstadoOrden - 1 );
		$sinExistencia       = false;
		$productos           = "";

		// SI ES PARA COCINAR
		if ( $idEstadoOrden == 2 ):
	 		$sql = "SELECT
						disponibilidad,
					    ( ( r.cantidad * {$cantidadMenus} ) - p.disponibilidad )AS 'faltante',
						producto
					FROM receta AS r
						JOIN producto AS p
							ON r.idProducto = p.idProducto 
								AND p.disponibilidad < ( r.cantidad * {$cantidadMenus} )
					WHERE r.idMenu = {$info->idMenu} ";

	 		$rs = $this->con->query( $sql );
			while( $rs AND $row = $rs->fetch_object() )
			{
				$productos     .= $row->faltante . " " . $row->producto . " faltantes \n";
				$sinExistencia = true;
			}

		endif;

		// SI NO EXISTE EXISTENCIA
		if ( $sinExistencia )
		{
			return array(
 				'respuesta' => 'danger',
	 			'mensaje'   => "No existen suficientes productos: \n" . $productos,
	 		);
		}

 		$this->con->query( "START TRANSACTION" );

 		// SI ES PARA COCINAR
		if ( $idEstadoOrden == 2 ):

	 		// ACTUALIZA INVENTARIO
	 		$sql = "UPDATE producto AS p
					JOIN receta AS r
						ON p.idProducto = r.idProducto AND r.idMenu = {$info->idMenu}
					SET p.disponibilidad = ( p.disponibilidad - ( r.cantidad * {$cantidadMenus} ) );";

			// SI LLEGARA A OCURRIR UN ERROR
			if ( !$this->con->query( $sql ) ) {
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = 'Error al actualizar inventario';
			}

		endif;


		// RECORRE ORDENES DE CLIENTES
		foreach ( $info->lstOrden as $ix => $orden ):

			// OBTENER EL ID DEL DETALLE
			$sql = "SELECT 
						idDetalleOrdenMenu
					FROM detalleOrdenMenu 
					WHERE idOrdenCliente = {$orden->idOrdenCliente} AND idMenu = {$info->idMenu}
						AND idEstadoDetalleOrden = {$idEstadoOrdenActual}
					ORDER BY idDetalleOrdenMenu ASC
					LIMIT {$orden->seleccionados} ";

			$result = $this->con->query( $sql );
			while( $result AND $rowD = $result->fetch_object() )
			{

				// SE ACTUALIZA ESTADO DE DETALLE
				$sql = "CALL consultaDetalleOrdenMenu( 'estado', {$rowD->idDetalleOrdenMenu}, NULL, NULL, NULL, {$idEstadoOrden}, NULL, NULL, NULL, NULL, NULL );";
				
		 		if( $rs = $this->con->query( $sql ) ){
		 			@$this->con->next_result();
		 			if( $row = $rs->fetch_object() ) {
						$this->respuesta = $row->respuesta;
						$this->mensaje   = $row->mensaje;
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}
			}
	 		
	 		if ( $this->respuesta == 'danger' )
	 			break;

		endforeach;

		if ( $this->respuesta == 'success' )
		{
	 		$this->con->query( "COMMIT" );

	 		// SI ES NUEVO
		 	$infoNode = (object)array(
				'accion' => 'cambioEstadoDetalleOrden',
				'data'   => array( 
					'myId' => $this->myId,
					'ordenCocina' => (object)array(
						'idMenu'   => $info->idMenu,
						'lstOrden' => $info->lstOrden,
					),
					'lstOrdenes'    => array(),
					//'lstOrdenes'    => $lstOrdenes,
					'idEstadoOrden' => $idEstadoOrden,
				),
			);

		 	// SI LA CLASE NO EXISTE SE LLAMA
		 	if ( !class_exists( "Redis" ) )
		 		include 'redis.class.php';

		 	// ENVIA LOS DATOS POR MEDIO DE REDIS
		 	$red = new Redis();
			$red->messageRedis( $infoNode );
		}
	 	else
	 		$this->con->query( "ROLLBACK" );

	 	return $this->getRespuesta();
 	}


 	public function usuarioResponsable( $responsable, $lstUsuarios )
 	{
		$usuario  = NULL;
		$groupBy  = "usuarioResponsable";
		$lstU     = array();
		$idPerfil = 3; // cocina

 		if ( $responsable == 'barra' )
 		{
			$groupBy  = "usuarioBarra";
			$idPerfil = 4; // barra
 		}

 		$sql = "SELECT
					$groupBy AS 'usuario',
				    MAX( idOrdenCliente ) AS 'idOrdenCliente'
				FROM ordenCliente
				WHERE numMenu > 0 # AND DATE( fechaRegistro ) = curdate()
				GROUP BY {$groupBy}
				ORDER BY idOrdenCliente ASC
				LIMIT 6";

 		$rs = $this->con->query( $sql );
 		while ( $rs AND $row = $rs->fetch_object() ):

 			$index = -1;
 			foreach ( $lstUsuarios as $ix => $user ) {
 				if ( $user->user == $row->usuario AND $user->idPerfil == $idPerfil ) 
 				{
 					$index = $ix;
 					break;
 				}
 			}

 			// SI SE ENCONTRO SE AGREGA AL LISTADO
 			if ( $index >= 0 )
				$lstU[] = $row->usuario;

		endwhile;

		// RECORRE EL LISTADO DE USUARIOS DE CLIENTES
		foreach ( $lstUsuarios as $ixP => $userCurrent ):

			// SI EL PERFIL ES DIFERENTE SE IGNORA
			if ( $userCurrent->idPerfil != $idPerfil )
				continue;

			$index = -1;
			// RECORRE EL LISTADO DE USUARIOS
			foreach ( $lstU as $ix => $user ):
				if ( $userCurrent->user == $user )
				{
					$index = $ix;
					break;
				}
			endforeach;

			if ( $index == -1 )
			{
				$usuario = $userCurrent->user;
				break;
			}
		endforeach;

		// SI USUARIO SIGUE SIENDO NULO TOMA EL PRIMERO
		if ( count( $lstUsuarios ) AND count( $lstU ) )
			$usuario = $lstU[ 0 ];


		// SI USUARIO ES NULO Y ES BARRA
		if ( is_null( $usuario ) AND $responsable == 'barra' )
			$usuario = "usuarioBarra";

		else if ( is_null( $usuario ) AND $responsable == 'cocina' )
			$usuario = "usuarioCocina";

 		return $usuario;
 	}

 	// CONSULTA ORDENES
 	public function getOrdenesCliente( $idEstadoOrden = NULL, $limite = NULL )
 	{
		$lst = array();

 		$limit = "";
 		$where = " ( idEstadoOrden BETWEEN 1 AND 5 ) ";

		// SI EL ESTADO ES DIFERENTE A PENDIENTE Y LIMITE ES MAYOR A CERO
 		if ( $limite > 0 AND $idEstadoOrden > 1 )
 		{
 			$limit = " LIMIT " . $limite;
			$where = " idEstadoOrden = " . $idEstadoOrden;
 		}

 		$sql = "SELECT
					idOrdenCliente,
				    numeroTicket,
				    usuarioPropietario,
				    estadoOrden,
				    DATE_FORMAT( fechaRegistro, '%d/%m/%Y - %H:%i' )AS 'fechaHora'
				FROM vOrdenCliente 
				WHERE $where
				ORDER BY idOrdenCliente DESC " . $limit;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $rowOrden = $rs->fetch_object() )
			{
				$lst[] = (object)array(
					'idOrdenCliente'     => $rowOrden->idOrdenCliente,
					'numeroTicket'       => $rowOrden->numeroTicket,
					'usuarioPropietario' => $rowOrden->usuarioPropietario,
					'estadoOrden'        => $rowOrden->estadoOrden,
					'fechaHora'          => $rowOrden->fechaHora,
					'lstDetalle'         => array(),
				);

				$sql = "(SELECT
							SUM( cantidad ) AS 'cantidad',
							codigoMenu,
							menu,
							( CASE WHEN idTipoServicio = 1 THEN 'L' 
						        WHEN idTipoServicio = 2 THEN 'R' 
						        WHEN idTipoServicio = 3 THEN 'D' END )AS 'idTipoServicio',
							IFNULL( observacion, '' )AS 'observacion'
						FROM vOrdenes 
						WHERE idOrdenCliente = {$rowOrden->idOrdenCliente} AND !perteneceCombo
							AND idEstadoDetalleOrden != 10
						GROUP BY idMenu)
							UNION
						(SELECT
							SUM( doc.cantidad )AS 'cantidad',
							c.codigo AS 'codigoMenu',
							c.combo,
							( CASE WHEN ts.idTipoServicio = 1 THEN 'L' 
						        WHEN ts.idTipoServicio = 2 THEN 'R' 
						        WHEN ts.idTipoServicio = 3 THEN 'D' END )AS 'idTipoServicio',
							IFNULL( doc.observacion, '' )AS 'observacion'
						FROM detalleOrdenCombo AS doc
							JOIN combo AS c
								ON doc.idCombo = c.idCombo
							JOIN tipoServicio AS ts
								ON doc.idTipoServicio = ts.idTipoServicio
						WHERE doc.idOrdenCliente = {$rowOrden->idOrdenCliente}
							AND doc.idEstadoDetalleOrden != 10
						GROUP BY doc.idCombo, doc.idTipoServicio ) ";

				if( $result = $this->con->query( $sql ) )
				{
					while ( $row = $result->fetch_object() )
						$lst[ count( $lst ) - 1 ]->lstDetalle[] = $row;
				}

 			}
 		}

 		return $lst;
 	}


 	// LISTA DE ORDENS PARA ### COCINA ###
 	public function consultaOrdenesCocina( $idEstadoDetalleOrden, $idDestinoMenu, $numeroGrupo = 0, $idOrdenCliente = NULL )
 	{
 		$idEstadoDetalleOrden = (int)$idEstadoDetalleOrden;
		$where    = $limit 	  = "";

		$lst = array();
		$numeroGrupo = (int)$numeroGrupo;

			
		// SI EL ESTADO ESTA DEFINIDO
		if ( ( $idEstadoDetalleOrden != 1 AND $idEstadoDetalleOrden != 2 ) )
			$limit = " LIMIT 50";

		if ( $idEstadoDetalleOrden > 0 )
			$where .= " AND idEstadoDetalleOrden = {$idEstadoDetalleOrden} ";

		// SI SE VALIDA DESTINO DE MENU
		if ( $idDestinoMenu > 0 )
			$where .= " AND idDestinoMenu = {$idDestinoMenu}";

		if ( !is_null( $idOrdenCliente ) AND $idOrdenCliente > 0 )
			$where .= " AND idOrdenCliente = {$idOrdenCliente} ";

		// SI NUMERO DE GRUPO ESTA ESPECIFICADO, 99 = TODOS
		if ( $numeroGrupo != 99 AND $numeroGrupo > 0 )
			$where .= " AND numeroGrupo = {$numeroGrupo} ";

 		$sql = "SELECT
					idOrdenCliente, 
					numeroGrupo,
					numeroTicket, 
				    SUM( cantidad )AS 'cantidad',
				    idMenu,
				    menu,
				    imagen,
				    idDestinoMenu,
				    codigoMenu,
				    tiempoAlerta,
				    perteneceCombo,
				    idCombo,
				    combo,
				    GROUP_CONCAT( DISTINCT observacion SEPARATOR ' -.- ' )AS 'observacion',
				    MIN( fechaRegistro )AS 'fechaRegistro',
				    idEstadoDetalleOrden
				FROM vOrdenes 
				WHERE TRUE $where
				GROUP BY idOrdenCliente, idMenu, idCombo, idEstadoDetalleOrden
				ORDER BY idMenu ASC, idOrdenCliente ASC, fechaRegistro ASC
				$limit ";

		if( $rs = $this->con->query( $sql ) )
		{
			while ( $row = $rs->fetch_object() ):
				$row->cantidad = (int)$row->cantidad;

				$ixM = -1;
				foreach ($lst as $_ixM => $menu) {
					if ( $menu->idMenu == $row->idMenu AND $menu->idEstadoDetalleOrden == $row->idEstadoDetalleOrden ) {
						$ixM = $_ixM;
						break;
					}
				}

				if ( $ixM === -1 )
				{
					$ixM = count( $lst );

					$lst[] = (object)array(
						'idMenu'               => $row->idMenu,
						'idEstadoDetalleOrden' => $row->idEstadoDetalleOrden,
						'menu'                 => $row->menu,
						'codigoMenu'           => $row->codigoMenu,
						'idDestinoMenu'        => $row->idDestinoMenu,
						'total'                => 0,
						'imagen'               => $row->imagen,
						'tiempoAlerta'         => $row->tiempoAlerta,
						'lstOrden'             => array(),
					);
				}


				$ixO = -1;
				foreach ($lst[ $ixM ]->lstOrden as $_ixO => $orden) {
					if ( $orden->idOrdenCliente == $row->idOrdenCliente ) {
						$ixO = $_ixO;
						break;
					}
				}

				if ( $ixO === -1 )
				{
					$ixO = count( $lst[ $ixM ]->lstOrden );

					$lst[ $ixM ]->lstOrden[] = (object)array(
						'idOrdenCliente' => $row->idOrdenCliente,
						'numeroGrupo'    => $row->numeroGrupo,
						'numeroTicket'   => $row->numeroTicket,
						'fechaRegistro'  => $row->fechaRegistro,
						'observacion'    => "",
						'total'          => 0,
						//'lstDetalle'     => array(),
					);
				}

				// SUMA TOTAL POR MENU
				$lst[ $ixM ]->total += $row->cantidad;

				// SUMA TOTAL POR ORDEN DE CLIENTE
				$lst[ $ixM ]->lstOrden[ $ixO ]->total += $row->cantidad;

				if ( strlen( $row->observacion ) )
				{
					$lst[ $ixM ]->lstOrden[ $ixO ]->observacion .= $row->observacion . " -.- ";
					$lst[ $ixM ]->lstOrden[ $ixO ]->observacion = rtrim( $lst[ $ixM ]->lstOrden[ $ixO ]->observacion, " -.- " );
				}

			endwhile;
		}

		return $lst;
 	}

 	// SERVIR ORDENES DE CLIENTE
 	public function servirMenuCliente( $datos )
 	{
 		// SETEO DE VARIABLES
		$validar = new Validar();

		$idOrdenCliente = $validar->validarEntero( $datos->idOrdenCliente, NULL, TRUE, 'Orden de Cliente no Definida' );
		$idTipoServicio = $validar->validarEntero( $datos->idTipoServicio, NULL, TRUE, 'Tipo de Servicio no Definido' );
		$idCombo        = $validar->validarEntero( $datos->idCombo, 0, FALSE, 'No. de Combo inválido' );
		$idMenu         = $validar->validarEntero( $datos->idMenu, 0, FALSE, 'No. de Menu inválido' );
		$seleccionados  = $validar->validarEntero( $datos->seleccionados, 0, FALSE, 'Menus Seleccionados' );


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() )
 		{
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
	 	}
	 	# SI NO EXISTIO NINGUN ERROR
	 	else if ( $seleccionados )
	 	{
	 		$lst = array();
 			// OBTENER EL ID DEL DETALLE
	 		if ( $idCombo )
				$sql = "SELECT 
							idDetalleOrdenCombo
						FROM detalleOrdenCombo
						WHERE idOrdenCliente = {$idOrdenCliente}
							AND idCombo = {$idCombo}
							AND idTipoServicio = {$idTipoServicio}
						    AND idEstadoDetalleOrden < 4
						ORDER BY idEstadoDetalleOrden ASC, idDetalleOrdenCombo ASC
						LIMIT {$seleccionados}; ";
			else
				$sql = "SELECT
							idDetalleOrdenMenu
						FROM detalleOrdenMenu 
						WHERE idOrdenCliente = {$idOrdenCliente}
							AND idMenu = {$idMenu}
							AND idTipoServicio = {$idTipoServicio}
						    AND idEstadoDetalleOrden < 4
						    AND !perteneceCombo
						ORDER BY idEstadoDetalleOrden ASC, idDetalleOrdenMenu ASC
						LIMIT {$seleccionados}; ";

			$result = $this->con->query( $sql );
			while( $result AND $row = $result->fetch_object() )
		 		$lst[] = $row;

		 	// SI LOS SELECCIONADOS ES IGUAL AL NUMERO DE DETALLE
		 	if ( count( $lst ) == $seleccionados )
		 	{
				$this->con->query( "START TRANSACTION" );

		 		foreach ( $lst as $item ):
		 			
		 			if ( isset( $item->idDetalleOrdenMenu ) )
						$sql = "CALL consultaDetalleOrdenMenu( 'estado', {$item->idDetalleOrdenMenu}, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL );";

					else
						$sql = "CALL consultaDetalleOrdenCombo( 'estado', {$item->idDetalleOrdenCombo}, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL );";
					
			 		if( $rs = $this->con->query( $sql ) ){
			 			@$this->con->next_result();
			 			if( $row = $rs->fetch_object() ) {
							$this->respuesta = $row->respuesta;
							$this->mensaje   = $row->mensaje;
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


				if ( $this->respuesta == 'success' )
				{
			 		//$this->con->query( "ROLLBACK" );
			 		$this->con->query( "COMMIT" );

			 		$this->myId = uniqid();

					$this->data = $this->infoNodeOrden( $idOrdenCliente, TRUE, TRUE, TRUE );

				 	$infoNode = (object)array(
						'accion' => 'cancelarOrdenParcial',
						'myId'   => $this->myId,
						'data'   => array(
							'info'           => $this->data,
							'idOrdenCliente' => $idOrdenCliente,
							'lstDetalle'     => array(),
							//'lstDetalle'     => $lstDetalle,
					 	),
					);

				 	// SI LA CLASE NO EXISTE SE LLAMA
				 	if ( !class_exists( "Redis" ) )
				 		include 'redis.class.php';

				 	// ENVIA LOS DATOS POR MEDIO DE REDIS
				 	$red = new Redis();
					$red->messageRedis( $infoNode );
				}
			 	else
			 		$this->con->query( "ROLLBACK" );
		 	}
		 	else
		 	{
		 		$this->respuesta = 'danger';
	 			$this->mensaje   = "No existen suficientes ordenes";
		 	}
	 	}


	 	return $this->getRespuesta();
 	}


 	// LISTA DE ORDENES PARA FACTURAR
 	public function detalleOrdenFactura( $idOrdenCliente )
 	{
		$lst = array();

		$sql = "SELECT * FROM
				((SELECT
					c.idCombo,
				    NULL AS 'idMenu',
				    c.codigo,
				    c.combo AS 'descripcion',
				    c.imagen,
				    SUM( doc.cantidad )AS 'cantidad',
				    cp.precio,
				    ts.idTipoServicio,
				    ts.tipoServicio,
				    SUM( IF( !ISNULL( dof.idFactura ), 1, 0 ) )AS 'facturado'
				FROM detalleOrdenCombo AS doc
					JOIN combo AS c
						ON c.idCombo = doc.idCombo
					JOIN comboPrecio AS cp
						ON cp.idCombo = c.idCombo 
							AND doc.idTipoServicio = cp.idTipoServicio
					JOIN tipoServicio AS ts
						ON doc.idTipoServicio = ts.idTipoServicio
					LEFT JOIN detalleOrdenFactura AS dof
						ON dof.idDetalleOrdenCombo = doc.idDetalleOrdenCombo
				WHERE doc.idOrdenCliente = {$idOrdenCliente} 
				    AND idEstadoDetalleOrden <> 6
					AND idEstadoDetalleOrden <> 10
					AND ISNULL( dof.idFactura )
				GROUP BY doc.idCombo, doc.idTipoServicio
				ORDER BY doc.idCombo ASC)
					UNION ALL
				(SELECT
					NULL AS 'idCombo',
					m.idMenu,
				    m.codigo,
				    m.menu AS 'descripcion',
				    m.imagen,
				    SUM( dom.cantidad )AS 'cantidad',
				    mp.precio,
				    ts.idTipoServicio,
				    ts.tipoServicio,
				    SUM( IF( !ISNULL( dof.idFactura ), 1, 0 ) )AS 'facturado'
				FROM detalleOrdenMenu AS dom
					JOIN menu AS m 
						ON m.idMenu = dom.idMenu
					JOIN menuPrecio AS mp
						ON mp.idMenu = m.idMenu
							AND dom.idTipoServicio = mp.idTipoServicio
					JOIN tipoServicio AS ts
						ON dom.idTipoServicio = ts.idTipoServicio
					LEFT JOIN detalleOrdenFactura AS dof
						ON dof.idDetalleOrdenMenu = dom.idDetalleOrdenMenu
				WHERE dom.idOrdenCliente = {$idOrdenCliente} 
					AND !dom.perteneceCombo
				    AND idEstadoDetalleOrden <> 6
					AND idEstadoDetalleOrden <> 10
					AND ISNULL( dof.idFactura )
				GROUP BY dom.idMenu, dom.idTipoServicio
				ORDER BY dom.idMenu ASC))dt;";

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):
				$row->idOrdenCliente = (int)$idOrdenCliente;
				$row->cantidad       = (int)$row->cantidad;
				$row->facturado      = (int)$row->facturado;
				$row->pendiente      = ( $row->cantidad - $row->facturado );
				$row->precio         = (double)$row->precio;
				$row->conDescuento   = 0;
				$row->descuento      = '';
				$row->justificacion  = '';
				$row->imagen         = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';
				$lst[]               = $row;
			endwhile;
		}

		$sql = "SELECT
					mp.idOrdenCliente,
					mp.idMenuPersonalizado,
				    mp.cantidad,
				    mp.descripcion,
				    mp.precioUnidad,
				    mp.observacion
				FROM menuPersonalizado AS mp
					LEFT JOIN detalleOrdenFactura AS dof
						ON dof.idMenuPersonalizado = mp.idMenuPersonalizado
				WHERE mp.idOrdenCliente = {$idOrdenCliente} AND ISNULL( dof.idFactura ) AND mp.idEstadoDetalleOrden = 1 ";

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ):
				$row->idMenu          = (int)$row->idMenuPersonalizado;
				$row->esPersonalizado = true;
				$row->idOrdenCliente  = (int)$row->idOrdenCliente;
				$row->cantidad        = (int)$row->cantidad;
				$row->facturado       = 0;
				$row->pendiente       = $row->cantidad;
				$row->precio          = (double)$row->precioUnidad;
				$row->conDescuento    = 0;
				$row->descuento       = '';
				$row->justificacion   = '';
				$row->idTipoServicio  = '';
				$row->imagen          = 'img/otroMenu.png';
				$lst[]                = $row;
			endwhile;
		}

		return $lst;
 	}

 	public function topFechaMenu( $tipoMenu, $deFecha, $paraFecha, $idMenu = NULL, $idCombo = NULL )
 	{
 		$limit = 10;
		$lstResultado = array();

	 	$rs = $this->con->query( "SELECT TIMESTAMPDIFF( DAY, '{$deFecha}', '{$paraFecha}' )AS 'dif'" );
	 	$row = $rs->fetch_object();

	 	if ( $row->dif > 35 )
	 	{
			$this->respuesta = "warning";
			$this->mensaje   = "Rango máximo 35 días";
	 	}
	 	else if ( $row->dif < 0 ){
	 		$this->respuesta = "warning";
			$this->mensaje   = "Rango no válido";
	 	}
	 	else{
	 		$this->respuesta = "success";
			$where   = '';
			$idMenu  = (int)$idMenu;
			$idCombo = (int)$idCombo;

			if ( $idMenu > 0 )
				$where = " AND m.idMenu = $idMenu ";

			else if ( $idCombo > 0 )
				$where = " AND c.idCombo = $idCombo ";

	 		if ( $tipoMenu == 'menu' )
		 		$sql = "SELECT
							m.codigo,
						    m.menu,
						    COUNT( dom.idDetalleOrdenMenu )AS 'total'
						FROM detalleOrdenMenu AS dom
							JOIN ordenCliente AS oc
								ON dom.idOrdenCliente = oc.idOrdenCliente
						        
							JOIN menu AS m
								ON dom.idMenu = m.idMenu
						WHERE
							!dom.perteneceCombo
						    AND dom.idEstadoDetalleOrden = 6
						    AND ( DATE( oc.fechaRegistro ) BETWEEN '{$deFecha}' AND '{$paraFecha}' )
						    $where
						GROUP BY dom.idMenu
						ORDER BY total DESC
						LIMIT $limit ";

			else
				$sql = "SELECT
							c.codigo,
							c.combo AS menu,
							COUNT( doc.idDetalleOrdenCombo )AS 'total'
						FROM detalleOrdenCombo AS doc
							JOIN ordenCliente AS oc
								ON doc.idOrdenCliente = oc.idOrdenCliente
								
							JOIN combo AS c
								ON doc.idCombo = c.idCombo
						WHERE doc.idEstadoDetalleOrden = 6
							AND ( DATE( oc.fechaRegistro ) BETWEEN '{$deFecha}' AND '{$paraFecha}' )
							$where
						GROUP BY doc.idCombo
						ORDER BY total DESC
						LIMIT $limit";

			$rs = $this->con->query( $sql );
			while ( $rs AND $row = $rs->fetch_object() ):
				$lstResultado[] = (object)array(
					'name' => $row->menu . " #" . $row->codigo,
					'data' => array( (int)$row->total ),
				);
			endwhile;
	 	}

	 	return array(
			'respuesta'    => $this->respuesta,
			'mensaje'      => $this->mensaje,
			'lstResultado' => $lstResultado,
	 	);
 	}

 	function getRespuesta()
 	{
 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data,
 				'myId'      => $this->myId
 			);
 	}


}
?>