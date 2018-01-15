<?php 
/**
* PRODUCTO
*/
class Producto
{
	private $con       = NULL;
	private $ses       = NULL;
	private $data      = NULL;
	private $error     = FALSE;
	private $respuesta = 'info';
	private $mensaje   = '';
	private $tiempo    = 6;

	function __construct()
	{
		global $conexion, $sesion;
		$this->con = $conexion;
		$this->ses = $sesion;
	}


	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}


 	function cargarFechaCierre( $fechaCierre )
 	{
 		$fechaCierreP = array(
 				'encontrado' => 0
 			);

 		$sql = "SELECT 
				    idCierreDiario,
				     DATE_FORMAT(fechaCierre, '%d/%m/%Y' ) AS fechaCierre,
				    comentario,
				    usuario,
				    DATE_FORMAT(fechaRegistroCierre, '%d/%m/%Y %h:%i %p') AS fechaHora,
				    todos
				FROM
				    vCierreDiario
				WHERE
				    fechaCierre = '{$fechaCierre}';";
 		
 		if( $rs = $this->con->query( $sql ) AND $rs->num_rows > 0 AND $row = $rs->fetch_object() ){

 			$fechaCierreP = array(
					'encontrado'     => 1,
					'idCierreDiario' => $row->idCierreDiario,
					'fechaCierre'    => $row->fechaCierre,
					'comentario'     => $row->comentario,
					'usuario'        => $row->usuario,
					'fechaHora'      => $row->fechaHora,
					'lstProductos'   => $this->cargarCierreDiarioProd( $row->idCierreDiario ),
					'todos'          => (int)$row->todos ? TRUE : FALSE
 				);
 		}

 		return (object)$fechaCierreP;
 	}


 	// CARGAR CIERRE DIARIO PRODUCTO
 	function cargarCierreDiarioProd( $idCierreDiario )
 	{
 		$lstCierreDiarioProd = array();

 		$sql = "SELECT 
 					idCierreDiario, 
					fechaCierre, 
					cantidadCierre, 
					idProducto, 
					producto, 
					idMedida, 
					medida, 
					idTipoProducto, 
					tipoProducto, 
					perecedero, 
					importante
 				FROM vCierreDiarioProducto 
 				WHERE idCierreDiario = {$idCierreDiario};";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$row->importante = (int)$row->importante ? TRUE : FALSE;
 				$lstCierreDiarioProd[] = $row;
 			}
 		}
 		
 		return $lstCierreDiarioProd;
 	}


 	// LST FACTURAS COMPRA
 	function cargarLstFacturaCompra()
 	{
 		$facturaCompra = array();

 		$facturaCompra = (object)array(
 				'pagado'            => 'Pagado',
 				'totalPagadas'      => 0,
 				'pendiente'         => 'Pendiente',
 				'totalPendientes'   => 0,
 				'pagoParcial'       => 'Pagado parcialmente',
 				'totalPagosParcial' => 0,
 				'lstFacturaCompra'  => array()
 			);

 		$sql = "SELECT *, DATE_FORMAT( fechaFactura, '%d/%m/%Y' ) AS fechaFact 
 					FROM lstFacturaCompra
 						ORDER BY idFacturaCompra DESC;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){

 				if( $row->idEstadoFactura == 1 )
 					$facturaCompra->totalPagadas++;
 				if( $row->idEstadoFactura == 2 )
 					$facturaCompra->totalPendientes++;
 				if( $row->idEstadoFactura == 3 )
 					$facturaCompra->totalPagosParcial++;

 				$facturaCompra->lstFacturaCompra[] = $row;
 			}
 		}

 		return $facturaCompra;
 	}


	// LST INGRESO PRODUCTO
 	function cargarLstIngresoProducto( $idFacturaCompra )
 	{
 		$lstIngresoProd = array();

 		$sql = "SELECT *
 					FROM lstIngresoProducto
 						WHERE idFacturaCompra = {$idFacturaCompra}
 						ORDER BY idFacturaCompra DESC;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() )
 				$lstIngresoProd[] = $row;
 		}

 		return $lstIngresoProd;
 	}


	//	CONSULTA CUADRE PRODUCTO
	function consultaCuadreProducto( $accion, $data )
	{
 		if( count( $data->lstProductos ) ){

		 	$idCuadreProducto = "NULL";
		 	$fechaCuadre      = "NULL";
		 	$comentario	      = "NULL";
		 	$todos            = "NULL";

		 	$data->fechaCuadre    = isset( $data->fechaCuadre ) 	? $data->fechaCuadre 			: NULL;
		 	$data->comentario     = isset( $data->comentario ) 	 	? (string)$data->comentario 	: NULL;
		 	$data->idEstadoCuadre = isset( $data->idEstadoCuadre ) 	? (int)$data->idEstadoCuadre 	: NULL;

		 	$validar = new Validar();
		 	if( $accion == 'insert' ) {		 		
			 	$todos             = isset( $data->cierreTodos ) 	? (int)$data->todos 		: 1;
			 	$data->idUbicacion = isset( $data->idUbicacion ) 	? (int)$data->idUbicacion 	: NULL;
			 	$idUbicacion       = $validar->validarEntero( $data->idUbicacion, NULL, TRUE, 'ID ubicación no es válido' );
		 	}
		 	elseif( $accion == 'update' ) {
		 		$data->idCuadreProducto = isset( $data->idCuadreProducto ) ? $data->idCuadreProducto : NULL;
		 		$idCuadreProducto       = $validar->validarEntero( $data->idCuadreProducto, NULL, TRUE, 'ID del Cuadre no es válido' );
		 	}

		 	$idEstadoCuadre = $validar->validarEntero( $data->idEstadoCuadre, NULL, TRUE, 'ID del Estado de Cuadre no es válido' );

		 	// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:
			 	$fechaCuadre    = $data->fechaCuadre;
			 	$comentario     = $this->con->real_escape_string( $data->comentario );
			 	$actualizarDisp = (int)$data->actualizarDisp;

	 			// INICIALIZAR TRANSACCIÓN
				$this->con->query( "START TRANSACTION" );

				$sql = "CALL consultaCuadreProducto( '{$accion}', {$idCuadreProducto}, '{$fechaCuadre}', '{$comentario}', {$todos}, {$idUbicacion}, {$idEstadoCuadre} );";

				//echo $sql;
		 		if( $rs = $this->con->query( $sql ) ){
		 			
		 			$this->siguienteResultado();
		 			if( $row = $rs->fetch_object() ){
		 				$this->respuesta = $row->respuesta;
		 				$this->mensaje   = $row->mensaje;

		 				if( ( $accion == 'insert' OR $accion == 'update' ) AND $this->respuesta == 'success' ){
		 					if( $accion == 'insert' )
		 					 	$idCierreDiario = $this->data = (int)$row->id;

		 					// REALIZAR CIERRA POR PRODUCTO
		 					if( $this->respuesta <> 'danger' )
		 						$this->consultaCuadreProductoDetalle( $accion, $idCierreDiario, $data->lstProductos, $data->cierreTodos, $actualizarDisp );
		 					elseif( $this->respuesta == 'danger' )
		 						$this->mensaje .= " (CUADRE DE PRODUCTOS)";
		 				}
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción (CUADRE DE PRODUCTOS)';
		 		}


		 		if( $this->respuesta == 'success' )
		 			$this->con->query( "COMMIT" );
		 		else
		 			$this->con->query( "ROLLBACK" );

	 		endif;

 		}
 		else{
 			$this->respuesta = 'info';
		 	$this->mensaje   = 'No hay ingresado productos en la lista de cierre';
 		}

 		return $this->getRespuesta();
	}


	// consultaCuadreProductoDetalle
	function consultaCuadreProductoDetalle( $accion, $idCuadreProducto, $lstProductos, $cierreTodos = TRUE, $idEstadoCuadre, $actualizarDisponibilidad = FALSE )
	{
		if( count( $lstProductos ) ) {

			$cierreTodos = (int)$cierreTodos;
			foreach ( $lstProductos AS $producto ) {

				$omitir = FALSE;
				if( !$cierreTodos AND !$producto->importante )
					$omitir = TRUE;

				if( !$omitir )
				{
					$cantidadApertura   = 0;
					$cantidadCierre     = 0;
					$comentarioApertura = '';
					$comentarioCierre   = '';
					$diferenciaApertura = NULL;
					$diferenciaCierre   = NULL;

					$idProducto = (int)$producto->idProducto;
					if( $accion == 'insert' ){
						$cantidadApertura = (double)$producto->disponible;
						$comentarioApertura = $producto->comentario;
						$diferenciaApertura = $producto->disponible - $producto->disponibilidad;
					}
					else{
						$cantidadCierre = (double)$producto->disponible;
						$comentarioCierre = $producto->comentario;
						$diferenciaCierre = $producto->disponible - $producto->disponibilidad;
					}

			 		// REALIZAR CONSULTA
					$sql = "CALL consultaCuadreProductoDetalle( '{$accion}', {$idCuadreProducto}, {$idProducto}, {$cantidadApertura}, {$cantidadCierre}, {$diferenciaApertura}, {$diferenciaCierre}, {$actualizarDisponibilidad}, {$idEstadoCuadre}, '{$comentarioApertura}', '{$comentarioCierre}' );";

			 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
			 			$this->siguienteResultado();

		 				$this->respuesta = $row->respuesta;
		 				if( $this->respuesta == 'danger' )
		 					$this->mensaje = $row->mensaje . " (D. Cuadre Producto)";
			 		}
			 		else{
			 			$this->respuesta = 'danger';
			 			$this->mensaje   = 'Error al ejecutar la instrucción (D. Cuadre Producto)';
			 		}

			 		// DETENER SI HAY ERROR
			 		if( $this->respuesta == 'danger' )
			 			break;
				}
			}
		}
		else {
 			$this->respuesta = 'warning';
		 	$this->mensaje   = 'No hay productos ingresados en la lista de ciere';
 		}

 		return $this->getRespuesta();
	}


	// GUARDAR LST REAJUSTES MASIVOS
 	function guardarReajusteMasivo( $accion, $data )
 	{ 		
 		if( count( $data->lstProductos ) )
 		{
 			// INCIALIZAR TRANSACCIÓN
 			$this->con->query( "START TRANSACTION" );

 			$this->consultaReajuste( $accion, $data->observacion );

 			// GUARDAR REAJUSTE
 			if( $this->respuesta == 'success' AND $this->data > 0 )
 			{
	 			foreach ( $data->lstProductos AS $producto) {	 				
	 				$this->consultaReajusteProducto( $accion, $producto );

	 				if( $this->respuesta == 'danger' )
	 					break;
	 			}
 			}

 			// FINALIZAR TRANSACCIÓN
 			if( $this->respuesta == 'success' )
 				$this->con->query( "COMMIT" );
 			else
 				$this->con->query( "ROLLBACK" );
 		}
 		else{
 			$this->respuesta = 'warning';
 			$this->mensaje   = 'No se realizaron cambios en los productos';
 		}

 		return $this->getRespuesta();
 	}

 	// CONSULTA REAJUSTE => INSERT
 	function consultaReajuste( $accion, $observacion )
 	{
		// SETEO VARIABLES GENERALES
 		$observacion = isset( $observacion )	? (string)$observacion 	: NULL;
 		$observacion = strlen( $observacion )	? (string)$observacion 	: NULL;

 		// VALIDACIONES
 		$validar = new Validar();
		$observacion  = $this->con->real_escape_string( $validar->validarTexto( $observacion, NULL, !esNulo( $observacion ), 20, 1500, 'la observación' ) );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:

			$sql = "CALL consultaReajuste( '{$accion}', '{$observacion}' );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
	 			
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;

 				if( $accion == 'insert' AND $this->respuesta == 'success' )
 					$this->data = (int)$row->id;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}

 		endif;
 	}

 	// CONSULTAR REAJUSTE INVENTARIO INDIVIDUAL
 	function consultaReajusteInventario( $accion, $data )
 	{
 		//var_dump( $accion, $data );
 		$this->con->query( "START TRANSACTION" );
 		$this->consultaReajuste( $accion, $data->observacion );

		if( $this->respuesta == 'success' AND $this->data > 0 )
	 		$this->consultaReajusteProducto( $accion, $data );

		// FINALIZAR TRANSACCIÓN
		if( $this->respuesta == 'success' )
			$this->con->query( "COMMIT" );
		else
			$this->con->query( "ROLLBACK" );
		
		return $this->getRespuesta();
 	}

	// GUARDAR // ACTUALIZAR => INGRESO
	function consultaReajusteProducto( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idProducto  = 'NULL';
 		$cantidad    = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto  = isset( $data->idProducto )  	? (int)$data->idProducto 		: NULL;
 		$data->cantidad    = isset( $data->cantidad )		? (double)$data->cantidad 		: NULL;
 		
 		// VALIDACIONES
		$idProducto   = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido' );
		$cantidad     = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );
		$esIncremento = (int)$data->esIncremento;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaReajusteProducto( '{$accion}', $this->data, {$idProducto}, {$cantidad}, {$esIncremento} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
	 			
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				/*
 				if( $accion == 'insert' AND $this->respuesta == 'success' )
 					$this->data = (int)$row->id;
 				*/
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}			
	 		
 		endif;

 		return $this->getRespuesta();
	}


	// OBTENER LISTA DE PRODUCTOS INGRESO
	function getLstIngreso( $lstProductosIngreso )
	{

		if( count( $lstProductosIngreso ) )
		{
			foreach ($lstProductosIngreso AS $ixProducto => $ixProducto ) {
				
			}
		}
		else
		{
			$this->respuesta = 'warning';
			$this->mensaje   = 'No ha ingrado ningun producto, verifique';
		}

		return $this->getRespuesta();
	}



	function consultaFactura( $accion, $data )
	{
		//var_dump( $data );

		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idFacturaCompra = 'NULL';
 		$idEstadoFactura = 'NULL';
 		$noFactura       = 'NULL';
 		$proveedor       = "NULL";
 		$fechaFactura    = "NULL";
 		$comentario      = "NULL";

		// SETEO VARIABLES GENERALES
 		$data->noFactura       = isset( $data->noFactura )		  	? (string)$data->noFactura 		: NULL;
 		$data->fechaFactura    = isset( $data->fechaFactura )	  	? $data->fechaFactura 			: NULL;
 		$data->idEstadoFactura = isset( $data->idEstadoFactura )  	? (int)$data->idEstadoFactura 	: NULL;
 		$data->idEstadoFactura = (int)$data->idEstadoFactura   		? (int)$data->idEstadoFactura 	: NULL;
 		$data->comentario      = isset( $data->comentario )		  	? (string)$data->comentario 	: NULL;
 		$data->comentario      = strlen( $data->comentario )	  	? (string)$data->comentario 	: NULL;
 		
 		
 		// VALIDACIONES
		$idEstadoFactura = $validar->validarEntero( $data->idEstadoFactura, NULL, TRUE, 'El ID del estado de factura no es válido' );
		$noFactura       = $this->con->real_escape_string( $validar->validarTexto( $data->noFactura, NULL, TRUE, 'El No. de factura no es válido' ) );
 		$proveedor       = isset( $data->proveedor ) ? $this->con->real_escape_string( (string)$data->proveedor ) : NULL;

 		if( $accion == 'update' ) {
 			$data->idFacturaCompra = isset( $data->idFacturaCompra )  ? (int)$data->idFacturaCompra : NULL;
 			$data->idFacturaCompra = (int)$data->idFacturaCompra > 0  ? (int)$data->idFacturaCompra : NULL;
 			$idFacturaCompra       = $validar->validarEntero( $data->idFacturaCompra, NULL, TRUE, 'El ID de la factura no es válida' );
 		}

		$comentario   = $this->con->real_escape_string( $data->comentario );
		$fechaFactura = substr( $data->fechaFactura, 0, -14);
		
		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:

 			// INICIALIZAR TRANSACCION
 			$this->con->query( 'START TRANSACTION' );

			$sql = "CALL consultaFactura( '{$accion}', {$idFacturaCompra}, {$idEstadoFactura}, '{$noFactura}', '{$proveedor}', '{$fechaFactura}', '{$comentario}' );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
	 			
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;

 				if( ( $accion == 'insert' OR $accion == 'update' ) AND $this->respuesta == 'success' ){

 					if( $accion == 'insert' )
 						$this->data = (int)$row->id;
 					else
 						$this->data = $idFacturaCompra;

 					if( $accion == 'insert' AND count( $data->lstProductos ) )
 					{
						foreach ( $data->lstProductos AS $producto ) {

							$this->consultaIngreso( $accion, $this->data, $producto );

							if( $this->respuesta == 'danger' )
								break;
						}
 					}
 					elseif( $accion == 'insert' AND !count( $data->lstProductos ) ){
			 			$this->respuesta = 'warning';
					 	$this->mensaje   = 'No hay productos agregados al listado de compras';
 					}
 				}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción (Factura).';
	 		}			
	 		
 		endif;

		// FINALIZAR TRANSACCION
 		if( $this->respuesta == 'danger' )
 			$this->con->query( 'ROLLBACK' );
 		else
 			$this->con->query( 'COMMIT' );


 		return $this->getRespuesta();
	}


	// GUARDAR // ELIMINAR => INGRESO
	function consultaIngreso( $accion, $idFacturaCompra, $data )
	{

		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idIngreso  = 'NULL';
 		$cantidad   = 'NULL';
 		$idProducto = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto = isset( $data->idProducto ) 	? (int)$data->idProducto  	: NULL;
 		$data->cantidad   = isset( $data->cantidad ) 	? (double)$data->cantidad 	: NULL;
 		$data->costo      = isset( $data->costo ) 		? (double)$data->costo 		: NULL;

 		// VALIDACIONES
 		if( $accion == 'delete' ):
 			$data->idIngreso = isset( $data->idIngreso ) ? (int)$data->idIngreso : NULL;
			$idIngreso = $validar->validarEntero( $data->idIngreso, NULL, TRUE, 'El ID de Ingreso no es válido.' );
		
		else:
			$cantidad   = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 500000, 'la cantidad' );
			$idProducto = $validar->validarEntero( $data->idProducto, NULL, TRUE, "El ID del producto ({$data->producto}) no es válido." );
			$costo      = (double)$data->costo;

 		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaIngreso( '{$accion}', {$idIngreso}, {$cantidad}, {$costo}, {$idProducto}, {$idFacturaCompra} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}			
	 		
 		endif;

 		return $this->getRespuesta();
	}
	

	// GUARDAR // ACTUALIZAR => TIPOPRODUCTO
	function consultaTipoProducto( $accion, $data )
	{
		$validar = new Validar();

		// SETEO VARIABLES GENERALES
 		$data->tipoProducto = isset( $data->tipoProducto ) ? (string)$data->tipoProducto : NULL;

 		// VALIDACIONES
 		$idTipoProducto = 'NULL';
 		if( $accion == 'update' ):
 			$data->idTipoProducto = isset( $data->idTipoProducto ) ? (int)$data->idTipoProducto : NULL;
 			$idTipoProducto       = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'El ID del Tipo de Producto no es válido' );
 		endif;

		$tipoProducto = $medida = $this->con->real_escape_string( $validar->validarTexto( $data->tipoProducto, NULL, TRUE, 3, 45, 'el Tipo Producto' ) );
		
		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaTipoProducto( '{$accion}', {$idTipoProducto}, '{$tipoProducto}' );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				if( $accion == 'insert' AND $this->respuesta == 'success' )
 					$this->data = (int)$row->id;
 			
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}

 		endif;

 		return $this->getRespuesta();
	}


	// GUARDAR // ACTUALIZAR => PRODUCTO
	function consultaProducto( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$producto       = NULL;
 		$idTipoProducto = NULL;
 		$idMedida       = NULL;
 		$cantidadMinima = NULL;
 		$cantidadMaxima = NULL;
 		$disponibilidad = NULL;

		// SETEO VARIABLES GENERALES
 		$data->idUbicacion    = isset( $data->idUbicacion ) 	? (int)$data->idUbicacion 		: NULL;
 		$data->producto       = isset( $data->producto ) 	 	? (string)$data->producto 		: NULL;
 		$data->idTipoProducto = isset( $data->idTipoProducto ) 	? (int)$data->idTipoProducto 	: NULL;
 		$data->idMedida       = isset( $data->idMedida )		? (int)$data->idMedida 			: NULL;
 		$data->cantidadMinima = isset( $data->cantidadMinima ) 	? (double)$data->cantidadMinima : NULL;
 		$data->cantidadMaxima = isset( $data->cantidadMaxima )	? (double)$data->cantidadMaxima : NULL;
 		$data->disponibilidad = isset( $data->disponibilidad )	? (double)$data->disponibilidad : NULL;
 		$perecedero           = (int)$data->perecedero;
 		$importante           = (int)$data->importante;

 		// VALIDACIONES
 		$idProducto = 'NULL';
 		if( $accion == 'update' ):
 			$disponibilidad   = "NULL";
 			$data->idProducto = isset( $data->idProducto ) ? (int)$data->idProducto : NULL;
 			$idProducto       = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );
 		endif;

		$idUbicacion    = $validar->validarEntero( $data->idUbicacion, NULL, TRUE, 'Seleccione la ubicacion del producto' );
		$producto       = $medida = $this->con->real_escape_string( $validar->validarTexto( $data->producto, NULL, TRUE, 3, 45, 'el nombre del producto' ) );
		$idTipoProducto = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'Seleccione el tipo de Producto' );
		$idMedida       = $validar->validarEntero( $data->idMedida, NULL, TRUE, 'Seleccione el tipo de medida' );
		$cantidadMinima = $validar->validarCantidad( $data->cantidadMinima, NULL, TRUE, 1, 50000, 'la cantidad Minima' );
		$cantidadMaxima = $validar->validarCantidad( $data->cantidadMaxima, NULL, TRUE, 1, 50000, 'la cantidad Maxima' );
		
		// DISPONIBILIDAD
		if( $accion == 'insert' )
			$disponibilidad = $validar->validarCantidad( $data->disponibilidad, NULL, TRUE, 1, 50000, 'la disponibilidad' );

		$validar->compararValores( $cantidadMinima, $cantidadMaxima, 'la cantidad Mínima', 'la cantidad máxima', 2 );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaProducto( '{$accion}', {$idProducto}, '{$producto}', {$idTipoProducto}, {$idMedida}, {$perecedero}, {$cantidadMinima}, {$cantidadMaxima}, {$disponibilidad}, {$importante}, {$idUbicacion} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){	 			
	 			$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				if( $accion == 'insert' AND $this->respuesta == 'success' )
 					$this->data = (int)$row->id;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


	// CONSULTAR DATOS MEDIDA
	function cargarTipoProducto( $idTipoProducto )
	{
		$idTipoProducto = (int)$idTipoProducto;
		$tipoProducto   = array();

		$sql = "SELECT idTipoProducto, tipoProducto FROM tipoProducto WHERE idTipoProducto = {$idTipoProducto};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$tipoProducto = $row;
		}

		return $tipoProducto;
	}


	// CONSULTAR DATOS PRODUCTO
	function cargarProducto( $idProducto )
	{
		$idProducto = (int)$idProducto;
		$producto   = array();

		$sql = "SELECT 
				    idProducto,
				    producto,
				    idTipoProducto,
				    idMedida,
				    perecedero,
				    cantidadMinima,
				    cantidadMaxima,
				    disponibilidad,
				    importante
				FROM
				    lstProducto
				WHERE idProducto = {$idProducto};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$row->cantidadMinima = (double)$row->cantidadMinima;
				$row->cantidadMaxima = (double)$row->cantidadMaxima;
				$row->disponibilidad = (double)$row->disponibilidad;
				$row->perecedero     = (int)$row->perecedero ? TRUE : FALSE;
				$row->importante     = (int)$row->importante ? TRUE : FALSE;
				$producto            = $row;
		}

		return $producto;
	}

	
	// OBTENER TOTAL PRODUCTOS
	function getTotalProductos( $limite = 25 )
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS total FROM lstProducto";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$total = (int)$row->total;
		}

		$totalPaginas = ceil( $total / $limite );
		
		return $totalPaginas;
	}


	// BUSCAR PRODUCTO(S)
	function buscarProducto( $nombreProducto )
	{
		$lstProductos = array();

		$sql = "SELECT idProducto, producto, medida, tipoProducto FROM lstProducto WHERE producto LIKE '%{$nombreProducto}%' LIMIT 10;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$lstProductos[] = $row;
			}
		}

		return $lstProductos;
	}


	// REALIZAR APERTURAR / CIERRE DE INVENTARIO
	function accionCuadreProducto( $idUbicacion )
	{ 		
		$detalleCuadre = new StdClass();
		//sleep( 1 );
		$idUbicacion = (int)$idUbicacion;
		$fechaCuadre = date("Y-m-d");

		$sql = "SELECT 
				    idCuadreProducto,
				    fechaCuadre,
				    comentario,
				    usuario,
				    fechaRegistroCuadre,
				    todos,
				    idUbicacion,
				    ubicacion,
				    idEstadoCuadre,
				    estadoCuadre
				FROM
				    vCuadreproducto
				WHERE
				    idUbicacion = {$idUbicacion} AND ( fechaCuadre = 1 OR fechaCuadre = 2 );";
 		
 		if( $rs = $this->con->query( $sql ) AND $rs->num_rows AND $row = $rs->fetch_object() ){
 			
			$row->cierreAtrasada = FALSE;
			if( $row->fechaCuadre <> $fechaCuadre )
				$row->cierreAtrasada = TRUE;

 			$dataCaja = array(
				    'accion'              => 'update',
				    'idCuadreProducto'    => (int)$row->idCuadreProducto,
				    'cierreAtrasada'      => $row->cierreAtrasada,
				    'fechaCuadre'         => $row->fechaCuadre,
				    'comentario'          => $row->comentario,
				    'actualizarDisp'      => TRUE,
				    'usuario'             => $row->usuario,
				    'fechaRegistroCuadre' => $row->fechaRegistroCuadre,
				    'todos'               => $row->todos,
				    'botonBloqueado'      => TRUE,
				    'idUbicacion'         => (int)$row->idUbicacion,
				    'idEstadoCuadre'      => (int)$row->idEstadoCuadre,
				    'estadoCuadre'        => $row->estadoCuadre,
				    'lstProductos'        => $this->getListaProductos( $row->idUbicacion )
 				);
 		}
		else {
			$dataCaja = array(
					'accion'              => 'insert',
					'idCuadreProducto'    => NULL,
					'cierreAtrasada'      => FALSE,
					'fechaCuadre'         => $fechaCuadre,
					'comentario'          => "",
					'actualizarDisp'      => TRUE,
					'usuario'             => $this->ses->getUsuario(),
					'fechaRegistroCuadre' => $fechaCuadre,
					'todos'               => TRUE,
					'botonBloqueado'      => FALSE,
					'idUbicacion'         => $idUbicacion,
					'idEstadoCuadre'      => 1,
					'estadoCuadre'        => 'APERTURAR INVENTARIO',
					'lstProductos'        => $this->getListaProductos( $idUbicacion )
			);

		}

 		return (object)$dataCaja;
	}

	// OBTENER LISTA DE PRODUCTOS UBICACION
	public function getListaProductos( $idUbicacion = NULL )
	{
		$lstProductos = array();

		$sql = "SELECT 
				    idProducto,
				    producto,
				    idMedida,
				    medida,
				    idTipoProducto,
				    tipoProducto,
				    perecedero,
				    cantidadMinima,
				    cantidadMaxima,
				    disponibilidad,
				    importante,
				    idUbicacion,
				    ubicacion
				FROM
				    lstproducto WHERE idUbicacion = {$idUbicacion};";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$lstProductos[] = array(
								 	'idProducto'        => (int)$row->idProducto,
								 	'producto'          => $row->producto,
								 	'medida'            => $row->medida,
								 	'esPerecedero'      => (int)$row->perecedero ? 'SI' : 'NO',
								 	'disponibilidad'    => (double)$row->disponibilidad,
								 	'disponible'        => 0,
								 	'esImportante'      => (int)$row->importante ? 'SI' : 'NO',
								 	'importante'        => (int)$row->importante,
								 	'comentario'        => "",
								 	'agregarComentario' => FALSE,
								 	'alertaComentario'  => FALSE,
								 	'mostrarAlerta'     => FALSE,
								 	'ubicacion'         => $row->ubicacion,
								 	'idUbicacion'       => (int)$row->idUbicacion
								);
			}
		}

		return $lstProductos;
	}


	function lstProductos( $groupBy )
	{
		$lstProductos = array();

		$sql = "SELECT 
				    idProducto,
				    producto,
				    idMedida,
				    medida,
				    idTipoProducto,
				    tipoProducto,
				    perecedero,
				    cantidadMinima,
				    cantidadMaxima,
				    disponibilidad,
				    importante,
				    idUbicacion,
				    ubicacion,
				    usuarioProducto,
				    DATE_FORMAT( fechaProducto, '%d/%m/%Y %h:%i %p' ) AS fechaProducto
				FROM
				    lstProducto
				ORDER BY idProducto;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){

				$iMedida       = -1;
				$iTipoProducto = -1;
				$indexProducto = -1;
				$iProducto     = -1;

				// VER TIPO DE AGRUPACIÓN
				if( $groupBy == 'sinFiltro' ): 		// SIN FILTRO
					foreach ($lstProductos AS $ixProducto => $indexProd) {
						if( $indexProd['indexProd'] == 1 ){
							$indexProducto = $ixProducto;
							break;
						}
					}

				elseif( $groupBy == 'tipoProducto' ): 	// TIPOPRODUCTO
					foreach ( $lstProductos AS $ixTipoProducto => $tipoProducto ) {
						if( $tipoProducto[ 'idTipoProducto' ] == $row->idTipoProducto ){
							$iTipoProducto = $ixTipoProducto;
							break;
						}
					}

				elseif( $groupBy == 'medida' ):	// MEDIDAS
					foreach ($lstProductos AS $ixMedida => $medida) {
						if( $medida['idMedida'] == $row->idMedida ){
							$iMedida = $ixMedida;
							break;
						}
					}

				endif;


				// SI NO EXISTE LO AGREGA
				if( $iTipoProducto == -1 AND $indexProducto == -1 AND $iMedida == -1 ){

					if( $groupBy == 'sinFiltro' ):			// SIN FILTRO
						$indexProducto = count( $lstProductos );

					elseif( $groupBy == 'tipoProducto' ):		// TIPOPRODUCTO
						$iTipoProducto = count( $lstProductos );

					elseif( $groupBy == 'medida' ):			// CLASIFICACION
						$iMedida = count( $lstProductos );

					endif;

					$lstProductos[] = array(
						'indexProd'       => 1,
						'listado'         => 'LISTADO DE PRODUCTOS',
						'idTipoProducto'  => (int) $row->idTipoProducto,
						'tipoProducto'    => strtoupper( $row->tipoProducto ),
						'idMedida'        => (int) $row->idMedida,
						'medida'          => strtoupper( $row->medida ),
						'totalProductos'  => 0,
						'totalStockVacio' => 0,
						'totalAlertas'    => 0,
						'totalStockAlto'  => 0,
						'mostrar'         => TRUE,
						'lstProductos'    => array()
					);
				}


				// SI NO EXISTE INGRESA
				if( $groupBy == 'sinFiltro' ):			// SIN FILTRO
					$ixSolicitud = $indexProducto;

				elseif( $groupBy == 'tipoProducto' ):	// TIPO PRODUCTO
					$ixSolicitud = $iTipoProducto;

				elseif( $groupBy == 'medida' ):			// CLASIFICACION
					$ixSolicitud = $iMedida;
				endif;

				$alertaStock = 0;
				// GENERAR ALERTA STOCK BAJO / ALTO / VACIO
				if( $row->disponibilidad < $row->cantidadMinima ):
					$alertaStock = 1;
				
				elseif( $row->disponibilidad <= $row->cantidadMinima + 15 ):
					$alertaStock = 2;
				
				elseif( $row->disponibilidad > $row->cantidadMaxima ):
					$alertaStock = 3;
				
				endif;

				$producto = array(
					 	'idProducto'      => (int)$row->idProducto,
					 	'producto'        => $row->producto,
					 	'idMedida'        => (int)$row->idMedida,
					 	'medida'          => $row->medida,
					 	'idTipoProducto'  => (int)$row->idTipoProducto,
					 	'tipoProducto'    => $row->tipoProducto,
					 	'perecedero'      => $row->perecedero,
					 	'esPerecedero'    => (int)$row->perecedero ? 'SI' : 'NO',
					 	'cantidadMinima'  => (double)$row->cantidadMinima,
					 	'cantidadMaxima'  => (double)$row->cantidadMaxima,
					 	'disponibilidad'  => (double)$row->disponibilidad,
					 	'cantidad'        => 0,
					 	'importante'      => $row->importante,
					 	'esImportante'    => (int)$row->importante ? 'SI' : 'NO',
					 	'usuarioProducto' => $row->usuarioProducto,
					 	'fechaProducto'   => $row->fechaProducto,
					 	'alertaStock'	  => $alertaStock,
					 	'esIncremento'    => TRUE,
					);

				$lstProductos[ $ixSolicitud ][ 'lstProductos' ][] = $producto;

				if( $alertaStock == 1 )
					$lstProductos[ $ixSolicitud ]['totalStockVacio'] ++;

				if( $alertaStock == 2 )
					$lstProductos[ $ixSolicitud ]['totalAlertas'] ++;

				if( $alertaStock == 3 )
					$lstProductos[ $ixSolicitud ]['totalStockAlto'] ++;


				$lstProductos[ $ixSolicitud ]['totalProductos'] ++;
			}
		}

		return $lstProductos;
	}


	function cargarLstReajusteProducto()
	{
		$lstReajusteProducto = array();

		$sql = "SELECT
					idReajusteInventario,
					idProducto,
					producto,
					idMedida,
					medida,
					idTipoProducto,
					tipoProducto,
					perecedero,
					cantidadMinima,
					cantidadMaxima,
					disponibilidad,
					importante,
					cantidad,
					observacion,
					esIncremento,
					usuarioReajuste,
					fechaReajuste
				FROM lstReajusteProducto;";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $rs->num_rows > 0 ){
				$this->respuesta = 'success';
				while( $row = $rs->fetch_object() )
					$lstReajusteProducto[] = $row;
			}else
				$this->mensaje = 'No ha registros encontrados.';
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		}

		$this->data = $lstReajusteProducto;

		return $this->getRespuesta();
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