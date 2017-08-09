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



	// GUARDAR // ACTUALIZAR => INGRESO
	function consultaReajusteInventario( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idProducto  = 'NULL';
 		$cantidad    = 'NULL';
 		$observacion = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto  = (int)$data->idProducto > 0  		? (int)$data->idProducto 		: NULL;
 		$data->cantidad    = (double)$data->cantidad 			? (double)$data->cantidad 		: NULL;
 		$data->observacion = strlen( $data->observacion ) > 0 	? (string)$data->observacion 	: NULL;

 		// VALIDACIONES
		$idProducto   = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );
		$cantidad     = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 50000, 'la cantidad' );
		$observacion  = $this->con->real_escape_string( $validar->validarTexto( $data->observacion, NULL, TRUE, 20, 1500, 'la observación' ) );
		$esIncremento = (int)$data->esIncremento;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaReajusteInventario( '{$accion}', {$idProducto}, {$cantidad}, '{$observacion}', {$esIncremento} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
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


	// GUARDAR // ACTUALIZAR => INGRESO
	function consultaIngreso( $accion, $data )
	{

		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idIngreso  = 'NULL';
 		$cantidad   = 'NULL';
 		$idProducto = 'NULL';

		// SETEO VARIABLES GENERALES
 		$data->idProducto = (int)$data->idProducto > 0 AND !esNulo( $data->idProducto ) ? (int)$data->idProducto 	: NULL;
 		$data->cantidad   = (double)$data->cantidad AND !esNulo( $data->cantidad )		? (double)$data->cantidad 	: NULL;

 		// VALIDACIONES
 		if( $accion == 'delete' ):
 			$data->idIngreso  = (int)$data->idIngreso > 0 AND !esNulo( $data->idIngreso ) ? (int)$data->idIngreso : NULL;
			$idIngreso = $validar->validarEntero( $data->idIngreso, NULL, TRUE, 'El ID de Ingreso no es válido, verifique.' );
 		endif;

		$idProducto = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );
		$cantidad   = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 5000, 'la cantidad' );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
			$sql = "CALL consultaIngreso( '{$accion}', {$idIngreso}, {$cantidad}, {$idProducto} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
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
 		$data->tipoProducto = strlen( $data->tipoProducto ) > 0 ? (string)$data->tipoProducto : NULL;

 		// VALIDACIONES
 		$idTipoProducto = 'NULL';
 		if( $accion == 'update' ):
 			$data->idTipoProducto = (int)$data->idTipoProducto > 0 ? (int)$data->idTipoProducto : NULL;
 			$idTipoProducto       = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'El ID del Tipo de Producto no es válido' );
 		endif;

		$tipoProducto = $validar->validarTexto( $data->tipoProducto, NULL, TRUE, 3, 45, 'el Tipo Producto' );
		
		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaTipoProducto( '{$accion}', {$idTipoProducto}, '{$tipoProducto}' );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
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
 		$data->producto       = strlen( $data->producto ) > 0 	? (string)$data->producto 		: NULL;
 		$data->idTipoProducto = (int)$data->idTipoProducto > 0 	? (int)$data->idTipoProducto 	: NULL;
 		$data->idMedida       = (int)$data->idMedida > 0 		? (int)$data->idMedida 			: NULL;
 		$data->cantidadMinima = (double)$data->cantidadMinima 	? (double)$data->cantidadMinima : NULL;
 		$data->cantidadMaxima = (double)$data->cantidadMaxima 	? (double)$data->cantidadMaxima : NULL;
 		$data->disponibilidad = (double)$data->disponibilidad 	? (double)$data->disponibilidad : NULL;
 		$perecedero           = (int)$data->perecedero;
 		$importante           = (int)$data->importante;

 		// VALIDACIONES
 		$idProducto = 'NULL';
 		if( $accion == 'update' ):
 			$disponibilidad   = "NULL";
 			$data->idProducto = (int)$data->idProducto > 0 ? (int)$data->idProducto : NULL;
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

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
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

		$sql = "SELECT idProducto, producto, tipoProducto FROM lstProducto WHERE producto LIKE '%{$nombreProducto}%' LIMIT 10;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$lstProductos[] = $row;
			}
		}

		return $lstProductos;
	}


	function lstProductos( $filter )
	{

		$pagina = $filter->pagina > 0 		? (int)$filter->pagina 	: 1;
		$limite = $filter->limite > 0 		? (int)$filter->limite 	: 25;
		$orden  = strlen( $filter->orden ) 	? $filter->orden 		: 'ASC';

		$productos = (object)array(
				'totalPaginas' => 0,
				'lstProductos' => array()
			);

		$inicio = ($pagina - 1) * $limite;

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
				ORDER BY idProducto $orden LIMIT $inicio, $limite;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
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
					 	'fechaProducto'   => $row->fechaProducto
					);

				$productos->lstProductos[] = $producto;
			}
		}
		
		$productos->totalPaginas = $this->getTotalProductos( $limite );

		return $productos;
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