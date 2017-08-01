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

 	
 	function consultaCliente( $accion, $cliente )
 	{
 		$validar = new Validar();
 		
 		$idCliente = "NULL";

 		if( $accion == 'update' ):
 			$cliente->idCliente = strlen( (int)$cliente->idCliente ) > 0 ? (int)$cliente->nit : NULL;
 			$idCliente = $validar->validarNumero( $cliente->idCliente, NULL, TRUE, 1  );
 		endif;

 		// SET
 		$cliente->nit           = strlen( (int)$cliente->nit ) > 0 		? (string)$cliente->nit 		: NULL;
 		$cliente->nombre        = strlen( $cliente->nombre ) > 0 		? (string)$cliente->nombre 		: NULL;
 		$cliente->cui           = strlen( (int)$cliente->cui ) > 0 		? (int)$cliente->cui 			: NULL;
 		$cliente->correo        = strlen( $cliente->correo ) > 0 		? (string)$cliente->correo 		: NULL;
 		$cliente->telefono      = strlen( $cliente->telefono ) > 0 		? (int)$cliente->telefono 		: NULL;
 		$cliente->direccion     = strlen( $cliente->direccion ) > 0 	? (string)$cliente->direccion 	: NULL;
 		$cliente->idTipoCliente = strlen( $cliente->idTipoCliente ) > 0 ? (int)$cliente->idTipoCliente 	: NULL;

 		// VALIDAR
		$nit           = $validar->validarNit( $cliente->nit, NULL, !esNulo( $cliente->cui ) );
		$nombre        = $validar->validarNombre( $cliente->nombre, NULL, !esNulo( $cliente->cui ) );
		$cui           = $validar->validarCui( $cliente->cui, NULL, !esNulo( $cliente->cui ) );
		$correo        = $validar->validarCorreo( $cliente->correo, NULL, !esNulo( $cliente->correo ) );
		$telefono      = $validar->validarTelefono( $cliente->telefono, NULL, !esNulo( $cliente->telefono ) );
		$direccion     = $validar->validarDireccion( $cliente->direccion, NULL, !esNulo( $cliente->direccion ) );
		$idTipoCliente = $validar->validarEntero( $cliente->idTipoCliente, NULL, TRUE );

 		if( $validar->getIsError() ):
 			
 			$this->respuesta = 'warning';
 			$this->mensaje   = $validar->getMsj();
 			$this->tiempo    = $validar->getTiempo();

 		else:
 			$sql = "CALL consultaCliente( {$nit}, '{$nombre}', {$cui}, {$correo}, {$telefono}, '{$direccion}', {$idTipoCliente} );";
 			
 			if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
 				@$this->con->next_result();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				if( $this->respuesta == 'success' )
 					$this->data = (int)$row->id;

 			}
 			else{
 				$this->respuesta = 'danger';
 				$this->mensaje   = 'Error al ejecutar la operacion';
 			}

 		endif;

 		return $this->getRespuesta();
 	}


 	function consultarCliente( $tipo, $valor )
 	{
 		$dataCliente = array();

 		$nit    = NULL;
 		$cui    = NULL;
 		$nombre = NULL;

 		if( $tipo == 'nit' ):
 			$nit = $valor;
 		elseif( $tipo== 'cui' ):
 			$cui = $valor;
 		elseif( $tipo == 'nombre' ):
			$nombre = "'" . $valor . "'";
 		endif;

	 	$sql = "CALL consultarCliente( {$nit}, {$nombre}, {$cui} );";
	 	
	 	if( $rs = $this->con->query( $sql ) ){
	 		if( $rs->num_rows > 0 ){
	 			$this->respuesta = 'success';
	 			$this->mensaje   = 'Encontrado';

		 		while( $row = $rs->fetch_object() )
		 			$dataCliente[] = $row;
	 		}else{
	 			$this->respuesta = 'warning';
	 			$this->mensaje   = 'No Encontrado';
	 		}
	 	}
	 	else{
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 	}

	 	return $dataCliente;
 	
 	}

 	function consultarClientes()
 	{

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