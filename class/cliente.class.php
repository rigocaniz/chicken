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
 			$cliente->idCliente = strlen( (int)$cliente->idCliente ) > 0 ? (int)$cliente->idCliente : NULL;
 			$idCliente          = $validar->validarNumero( $cliente->idCliente, NULL, TRUE, 1  );
 		endif; 		

 		// SET
 		$cliente->nit           = isset( $cliente->nit )  			? (string)$cliente->nit 		: NULL;
 		$cliente->nombre        = isset( $cliente->nombre ) 		? (string)$cliente->nombre 		: NULL;
 		$cliente->cui           = isset( $cliente->cui )			? (double)$cliente->cui 		: NULL;
 		$cliente->correo        = isset( $cliente->correo ) 		? (string)$cliente->correo 		: NULL;
 		$cliente->telefono      = isset( $cliente->telefono ) 		? (int)$cliente->telefono 		: NULL;
 		$cliente->direccion     = isset( $cliente->direccion ) 		? (string)$cliente->direccion 	: NULL;
 		$cliente->idTipoCliente = isset( $cliente->idTipoCliente ) 	? (int)$cliente->idTipoCliente 	: NULL;

 		// VALIDAR
		$nit           = $validar->validarNit( $cliente->nit, NULL, !esNulo( $cliente->nit ) );
		$nombre        = $medida = $this->con->real_escape_string( $validar->validarNombre( $cliente->nombre, NULL, !esNulo( $cliente->nombre ) ) );
		$cui           = $validar->validarCui( $cliente->cui, NULL, !esNulo( $cliente->cui ) );
		$correo        = $medida = $this->con->real_escape_string( $validar->validarCorreo( $cliente->correo, NULL, !esNulo( $cliente->correo ) ) );
		$telefono      = $validar->validarTelefono( $cliente->telefono, NULL, !esNulo( $cliente->telefono ) );
		$direccion     = $medida = $this->con->real_escape_string( $validar->validarDireccion( $cliente->direccion, NULL, !esNulo( $cliente->direccion ) ) );
		$idTipoCliente = $validar->validarEntero( $cliente->idTipoCliente, NULL, TRUE );

 		if( $validar->getIsError() ):
 			
 			$this->respuesta = 'warning';
 			$this->mensaje   = $validar->getMsj();
 			$this->tiempo    = $validar->getTiempo();

 		else:
 			$sql = "CALL consultaCliente( '{$accion}',{$idCliente},'{$nit}', '{$nombre}', '{$cui}', '{$correo}', '{$telefono}', '{$direccion}', {$idTipoCliente} )";
 		
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


 	function consultarCliente( $valor )
 	{
 		$dataCliente = array();
 		$comp = "";
 		if( strlen($valor) >= 8  AND strlen($valor) < 13 ){
 			$comp = "nit='$valor'";
 		}
 		elseif( is_numeric($valor) AND strlen($valor) == 13 ){
 			$comp = "cui=$valor";
 		}
 		else{
 			$valor= str_replace(' ','%', $valor);
			$comp = "nombre like '%{$valor}%'";
 		}

	 	$sql = "SELECT *from vstCliente where $comp";
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