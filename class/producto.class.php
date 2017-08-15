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


//	CREATE PROCEDURE consultaCierreDiario( _action VARCHAR(20), _idCierreDiario INT, _fechaCierre DATE, _comentario TEXT )
	function consultaCierreDiario( $accion, $data )
	{
 		if( count( $data->lstProductos ) ){

		 	$action         = "NULL";
		 	$idCierreDiario = "NULL";
		 	$fechaCierre    = "NULL";
		 	$comentario	    = "NULL";

		 	$data->fechaCierre = isset( $data->fechaCierre ) 	? $data->fechaCierre 			: NULL;
		 	$data->comentario  = isset( $data->comentario ) 	? (string)$data->comentario 	: NULL;

		 	$validar = new Validar();
		 	if( $accion == 'update' )
		 	{
		 		$data->idCierreDiario = isset( $data->idCierreDiario ) ? $data->idCierreDiario : NULL;
		 		$idCierreDiario = $validar->validarEntero( $data->idCierreDiario, NULL, TRUE, 'El ID del Cierre Diario no es válido' );
		 	}

		 	$fechaCierre = $data->fechaCierre;
		 	$comentario  = $this->con->real_escape_string( $data->comentario );

		 	// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:

	 			// INICIALIZAR TRANSACCIÓN
				$this->con->query( "START TRANSACTION" );

				$sql = "CALL consultaCierreDiario( '{$accion}', {$idCierreDiario}, '{$fechaCierre}', '{$comentario}' );";

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
		 						$this->consultaCierreDiarioProducto( $accion, $idCierreDiario, $data->lstProductos );
		 				}
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción de  Cierre.';
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


	// consultaCierreDiarioProducto
	function consultaCierreDiarioProducto( $accion, $idCierreDiario, $lstProductos )
	{
		if( count( $lstProductos ) ) {

			foreach ( $lstProductos AS $producto ) {

				$idProducto               = (int)$producto->idProducto;
				$cantidad                 = (double)$producto->disponibilidad;
				$actualizarDisponibilidad = 1; # (int)$data->actualizarDisponibilidad;

		 		// REALIZAR CONSULTA
				$sql = "CALL consultaCierreDiarioProducto( '{$accion}', {$idCierreDiario}, {$idProducto}, {$cantidad}, {$actualizarDisponibilidad} );";

				//echo $sql;
		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}

		 		// DETENER SI HAY ERROR
		 		if( $this->respuesta == 'danger' )
		 			break;
			}
		}
		else {
 			$this->respuesta = 'warning';
		 	$this->mensaje   = 'No hay productos ingresados en la lista de ciere';
 		}

 		return $this->getRespuesta();
	}


	// GUARDAR // ACTUALIZAR => INGRESO
	function consultaReajusteInventario( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idProducto  = 'NULL';
 		$cantidad    = 'NULL';
 		$observacion = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto  = isset( $data->idProducto )  	? (int)$data->idProducto 		: NULL;
 		$data->cantidad    = isset( $data->cantidad )		? (double)$data->cantidad 		: NULL;
 		$data->observacion = isset( $data->observacion )	? (string)$data->observacion 	: NULL;

 		// VALIDACIONES
		$idProducto   = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido' );
		$cantidad     = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );
		$observacion  = $this->con->real_escape_string( $validar->validarTexto( $data->observacion, NULL, TRUE, 20, 1500, 'la observación' ) );
		$esIncremento = (int)$data->esIncremento;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaReajusteInventario( '{$accion}', {$idProducto}, {$cantidad}, '{$observacion}', {$esIncremento} );";

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

		//$this->con->
		return $this->getRespuesta();
	}



	function guardarLstProductoIngreso( $accion, $data )
	{
		if( count( $data ) )
		{
			// INICIALIZAR TRANSACCION
	 		$this->con->query( 'START TRANSACTION' );

			foreach ( $data AS $producto ) {
				$this->consultaIngreso( $accion, $producto );

				if( $this->respuesta == 'danger' )
					break;
			}

			// FINALIZAR TRANSACCION
	 		if( $this->respuesta == 'danger' )
	 			$this->con->query( 'ROLLBACK' );
	 		else
	 			$this->con->query( 'COMMIT' );

		}
		else{
 			$this->respuesta = 'warning';
		 	$this->mensaje   = 'No hay productos agregados al listado de ingresos';
 		}

 		return $this->getRespuesta();
	}


	// GUARDAR // ACTUALIZAR => INGRESO
	function consultaIngreso( $accion, $data )
	{

		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idIngreso  = 'NULL';
 		$cantidad   = 'NULL';
 		$idProducto = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto = isset( $data->idProducto ) 	? (int)$data->idProducto  : NULL;
 		$data->cantidad   = isset( $data->cantidad ) 	? (double)$data->cantidad : NULL;

 		// VALIDACIONES
 		if( $accion == 'delete' ):
 			$data->idIngreso = isset( $data->idIngreso ) ? (int)$data->idIngreso : NULL;
			$idIngreso = $validar->validarEntero( $data->idIngreso, NULL, TRUE, 'El ID de Ingreso no es válido.' );
 		endif;

		$idProducto = $validar->validarEntero( $data->idProducto, NULL, TRUE, "El ID del producto ({$data->producto}) no es válido." );
		$cantidad   = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 5000, 'la cantidad' );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaIngreso( '{$accion}', {$idIngreso}, {$cantidad}, {$idProducto} );";

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

		$tipoProducto = $validar->validarTexto( $data->tipoProducto, NULL, TRUE, 3, 45, 'el Tipo Producto' );
		
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

		$producto       = $validar->validarTexto( $data->producto, NULL, TRUE, 3, 45, 'el nombre del producto' );
		$idTipoProducto = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'El ID del tipo de producto no es válido, verifique.' );
		$idMedida       = $validar->validarEntero( $data->idMedida, NULL, TRUE, 'El ID del tipo de medida no es válido, verifique.' );
		$cantidadMinima = $validar->validarCantidad( $data->cantidadMinima, NULL, TRUE, 1, 2500, 'la cantidad Minima' );
		$cantidadMaxima = $validar->validarCantidad( $data->cantidadMaxima, NULL, TRUE, 1, 2500, 'la cantidad Maxima' );
		
		// DISPONIBILIDAD
		if( $accion == 'insert' )
			$disponibilidad = $validar->validarCantidad( $data->disponibilidad, NULL, TRUE, 1, 2500, 'la disponibilidad' );

		$validar->compararValores( $cantidadMinima, $cantidadMaxima, 'la cantidad Mínima', 'la cantidad máxima', 2 );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaProducto( '{$accion}', {$idProducto}, '{$producto}', {$idTipoProducto}, {$idMedida}, {$perecedero}, {$cantidadMinima}, {$cantidadMaxima}, {$disponibilidad}, {$importante} );";

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

	// BUSCAR PRODUCTO(S)
	function getListaProductos()
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
		    importante
		FROM
		    lstProducto;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
					$producto = array(
					 	'idProducto'     => (int)$row->idProducto,
					 	'producto'       => $row->producto,
					 	'medida'         => $row->medida,
					 	'esPerecedero'   => (int)$row->perecedero ? 'SI' : 'NO',
					 	'disponibilidad' => (double)$row->disponibilidad,
					 	'disponible'     => (double)$row->disponibilidad,
					 	'esImportante'   => (int)$row->importante ? 'SI' : 'NO'
					);

				$lstProductos[] = $producto;
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
				    usuarioProducto,
				    DATE_FORMAT( fechaProducto, '%d/%m/%Y %h:%i %p' ) AS fechaProducto
				FROM
				    lstProducto
				ORDER BY idProducto;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){

				$iMedida        = -1;
				$iTipoProducto  = -1;
				$indexProducto = -1;
				$iProducto      = -1;

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
						'tipoProducto'    => $row->tipoProducto,
						'idMedida'        => (int) $row->idMedida,
						'medida'          => strtoupper( $row->medida ),
						'totalProductos'  => 0,
						'totalStockVacio' => 0,
						'totalAlertas'    => 0,
						'totalStockAlto'  => 0,
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
				
				elseif( $row->disponibilidad + 50 >= $row->cantidadMaxima ):
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
					 	'importante'      => $row->importante,
					 	'esImportante'    => (int)$row->importante ? 'SI' : 'NO',
					 	'usuarioProducto' => $row->usuarioProducto,
					 	'fechaProducto'   => $row->fechaProducto,
					 	'alertaStock'	  => $alertaStock
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


	function cargarLstIngresoProducto()
	{
		$LstReajusteProducto = array();

		$sql = "SELECT
					idIngreso,
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
					usuarioIngreso,
					fechaIngreso
				FROM lstIngresoProducto;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$LstReajusteProducto[] = $row;
				/*$this->respuesta = $row->respuesta;
				$this->mensaje   = $row->mensaje;
				$this->data      = $row;
				*/
			}
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		}
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