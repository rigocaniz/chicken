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
 		$data->tipoProducto = strlen( $data->tipoProducto ) > 0 	? (string)$data->tipoProducto 	: NULL;

 		// VALIDACIONES
 		$idTipoProducto = 'NULL';
 		if( $accion == 'update' ):
 			$data->idTipoProducto = (int)$data->idTipoProducto > 0 ? (int)$data->idTipoProducto : NULL;
 			$idTipoProducto       = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'El ID del Tipo Producto no es válido, verifique.' );
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
 		$data->producto       = strlen( $data->producto ) > 0 										? (string)$data->producto 		: NULL;
 		$data->idTipoProducto = (int)$data->idTipoProducto > 0 AND !esNulo( $data->idTipoProducto ) ? (int)$data->idTipoProducto 	: NULL;
 		$data->idMedida       = (int)$data->idMedida > 0 AND !esNulo( $data->idMedida ) 			? (int)$data->idMedida 			: NULL;
 		$data->cantidadMinima = (double)$data->cantidadMinima AND !esNulo( $data->cantidadMinima )	? (double)$data->cantidadMinima : NULL;
 		$data->cantidadMaxima = (double)$data->cantidadMaxima AND !esNulo( $data->cantidadMaxima )	? (double)$data->cantidadMaxima : NULL;
 		$data->disponibilidad = (double)$data->disponibilidad AND !esNulo( $data->disponibilidad )	? (double)$data->disponibilidad : NULL;
 		$perecedero           = (int)$data->perecedero;
 		$importante           = (int)$data->importante;

 		// VALIDACIONES
 		$idProducto = 'NULL';
 		if( $accion == 'update' ):
 			$data->idProducto = (int)$data->idProducto > 0 ? (int)$data->idProducto : NULL;
 			$idProducto = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );
 		endif;

		$producto       = $validar->validarTexto( $data->producto, NULL, TRUE, 3, 45, 'el nombre del producto' );
		$idTipoProducto = $validar->validarEntero( $data->idTipoProducto, NULL, TRUE, 'El ID del tipo de producto no es válido, verifique.' );
		$idMedida       = $validar->validarEntero( $data->idMedida, NULL, TRUE, 'El ID del tipo de medida no es válido, verifique.' );
		$cantidadMinima = $validar->validarCantidad( $data->cantidadMinima, NULL, TRUE, 1, 2500, 'la cantidad Minima' );
		$cantidadMaxima = $validar->validarCantidad( $data->cantidadMaxima, NULL, TRUE, 1, 2500, 'la cantidad Maxima' );
		$disponibilidad = $validar->validarCantidad( $data->disponibilidad, NULL, TRUE, 1, 2500, 'la disponibilidad' );

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
				$producto = $row;
		}

		return $producto;
	}


	function lstProductos()
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
				    lstProducto;";
		
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

				$lstProductos[] = $producto;
			}
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
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