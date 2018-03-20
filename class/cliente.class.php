<?php
/**
 * 
 */

class Cliente
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

 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}


 	// GUARDAR // ACTUALIZAR CLIENTE
 	function consultaCliente( $accion, $cliente )
 	{
 		$validar   = new Validar();
 		$idCliente = "NULL";

 		if( $accion == 'update' ):
 			$cliente->idCliente = (int)$cliente->idCliente > 0 ? (int)$cliente->idCliente : NULL;
 			$idCliente          = $validar->validarNumero( $cliente->idCliente, NULL, TRUE, 1  );
 		endif;

 		// SETEO DE VARIABLES
 		$cliente->nit           = isset( $cliente->nit )  					? (string)$cliente->nit 			: NULL;
 		$cliente->nit           = strlen( $cliente->nit )  					? (string)$cliente->nit 			: NULL;
 		$cliente->nombre        = isset( $cliente->nombre ) 				? (string)$cliente->nombre 			: NULL;
 		$cliente->nombre        = strlen( $cliente->nombre ) 				? (string)$cliente->nombre 			: NULL;
		$cliente->cui 			= isset( $cliente->cui ) 					? (string)$cliente->cui 			: NULL;
 		$cliente->correo        = isset( $cliente->correo ) 				? (string)$cliente->correo 			: NULL;
 		$cliente->correo        = strlen( $cliente->correo ) 				? (string)$cliente->correo 			: NULL;
 		$cliente->telefono      = isset( $cliente->telefono ) 				? (int)$cliente->telefono 			: NULL;
 		$cliente->telefono      = (int)$cliente->telefono > 0 				? (int)$cliente->telefono 			: NULL;
 		$cliente->direccion     = isset( $cliente->direccion ) 				? (string)$cliente->direccion 		: NULL;
 		$cliente->direccion     = strlen( $cliente->direccion ) 			? (string)$cliente->direccion 		: NULL;
 		$cliente->idTipoCliente = isset( $cliente->idTipoCliente ) 			? (int)$cliente->idTipoCliente 	 	: NULL;
 		$cliente->idTipoCliente = (int)$cliente->idTipoCliente > 0 			? (int)$cliente->idTipoCliente 	 	: NULL;

 		// VALIDAR
		$nit           = $validar->validarNit( $cliente->nit, NULL, !esNulo( $cliente->nit ) );
		$nombre        = $this->con->real_escape_string( $validar->validarNombre( $cliente->nombre, NULL, !esNulo( $cliente->nombre ) ) );
		$cui           = $validar->validarCui( $cliente->cui, NULL, !esNulo( $cliente->cui ) );
		$correo        = $this->con->real_escape_string( $validar->validarCorreo( $cliente->correo, NULL, !esNulo( $cliente->correo ) ) );
		$telefono      = $validar->validarTelefono( $cliente->telefono, NULL, !esNulo( $cliente->telefono ) );
		$direccion     = $this->con->real_escape_string( $validar->validarDireccion( $cliente->direccion, NULL, !esNulo( $cliente->direccion ) ) );
		$idTipoCliente = $validar->validarEntero( $cliente->idTipoCliente, NULL, TRUE );

 		if( $validar->getIsError() ):
 			
 			$this->respuesta = 'warning';
 			$this->mensaje   = $validar->getMsj();
 			$this->tiempo    = $validar->getTiempo();

 		else:
 			$sql = "CALL consultaCliente( '{$accion}',{$idCliente},'{$nit}', '{$nombre}', '{$cui}', '{$correo}', '{$telefono}', '{$direccion}', {$idTipoCliente} )";

 			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
 				$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				if( $this->respuesta == 'success' AND $accion == 'insert' )
 					$this->data = (int)$row->id;
 			}
 			else{
 				$this->respuesta = 'danger';
 				$this->mensaje   = 'Error al ejecutar la operacion';
 			}

 		endif;

 		return $this->getRespuesta();
 	}

 	// CONSULTAR CLIENTE
 	public function consultarCliente( $valor )
 	{
 		$where = "";
 		$tipo  = "";
 		
 		$resultado = new stdClass();

 		$resultado->encontrado    = FALSE;
 		$resultado->lstResultados = [];

 		if( strlen($valor) >= 2  AND strtoupper( $valor ) == 'CF' OR strtoupper( $valor ) == 'C/F' )
 			$where = "nit = 'CF'";
 		
 		elseif( preg_match('/^[0-9]{8}$/', $valor ) )
 			$where = " !ISNULL( telefono ) AND telefono = $valor";

 		else if( preg_match('/^[0-9-\s]{5,10}$/', $valor ) AND strlen( $valor ) >= 5  AND strlen( $valor ) <= 10 ){
 			$tipo  = "nit";
 			$where = "nit = '$valor'";
 		}

 		elseif( preg_match('/^[0-9-\s]{13,15}$/', $valor ) AND strlen( $valor) >= 13 AND strlen( $valor) <= 15 ){
 			$tipo  = "cui";
 			$where = "cui = $valor";
 		}

 		else {
 			$tipo  = "nombre";
 			$valor= str_replace(' ','%', $valor);
			$where = "nombre LIKE '%{$valor}%'  LIMIT 20";
 		}

	 	$sql = "SELECT * FROM vstCliente where $where ;";

	 	if( $rs = $this->con->query( $sql ) AND $rs->num_rows > 0 ){
	 		$resultado->encontrado = TRUE;
	 		while( $row = $rs->fetch_object() )
	 			$resultado->lstResultados[] = $row;
	 	}
	 	else{
	 		$resultado->lstResultados[] = 
				array(
					'idCliente'     => null,
					'nit'           => $tipo == 'nit' 	 ? $valor 							: '',
					'nombre'        => $tipo == 'nombre' ? str_replace( '%', ' ', $valor )  : '',
					'cui'           => $tipo == 'cui' 	 ? $valor 							: '',
					'correo'        => '',
					'telefono'      => '',
					'direccion'     => '',
					'idTipoCliente' => 1,
		 		);
	 	}

	 	return $resultado;
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