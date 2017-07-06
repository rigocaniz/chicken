<?php 
/**
* RECETAS
*/
class Receta
{
	
 	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $error     = FALSE;
 	private $sess      = NULL;
 	private $con       = NULL;
 	private $data      = NULL;

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}

 	
	// CONSULTAR LISTA DE COMBOS
 	function lstReceta()
 	{
 		$lstReceta = array();

 		$sql = "SELECT idMenu, idProducto, producto, cantidad, medida, tipoProducto, observacion FROM lstReceta;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstReceta[] = $row;
 			}
 		}

 		return $lstReceta;
 	}


 	// CONSULTAR DATOS RECETA
	function cargarReceta( $idMenu, $idProducto )
	{
		$idMenu     = (int)$idMenu;
		$idProducto = (int)$idProducto;
		$receta   = array();

		$sql = "SELECT 
				    idMenu,
				    idProducto,
				    cantidad,
				    observacion
				FROM
				    lstReceta
				WHERE
				    idMenu = {$idMenu} AND idProducto = {$idProducto};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$receta = $row;
		}

		return $receta;
	}
	

	// CONSULTA RECETA
 	function consultaReceta( $accion, $data )
 	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idMenu      = NULL;
		$idProducto  = NULL;
		$cantidad    = NULL;
		$observacion = NULL;

		// SETEO VARIABLES GENERALES
 		$data->idMenu      = (int)$data->idMenu > 0 AND !esNulo( $data->idMenu )			? (int)$data->idMenu 		: NULL;
 		$data->idProducto  = (int)$data->idProducto > 0 AND !esNulo( $data->idProducto ) 	? (int)$data->idProducto 	: NULL;
 		$data->cantidad    = (double)$data->cantidad > 0 AND !esNulo( $data->cantidad ) 	? (int)$data->cantidad 		: NULL;
 		$data->observacion = strlen( $data->observacion ) > 0 	? (string)$data->observacion : NULL;

 		// VALIDACIONES
 		$idMenu      = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menú no es válido, verifique.' );
		$idProducto  = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );
		$cantidad    = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
		$observacion = $validar->validarTexto( $data->observacion, NULL, !esNulo( $data->observacion ), 15, 1500, 'la observación' );

 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
 		else:
	 		$sql = "CALL consultaReceta( '{$accion}', {$idMenu}, {$idProducto}, {$cantidad}, '{$observacion}' );";
	 		//echo $sql;
	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}
	 		
 		endif;

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