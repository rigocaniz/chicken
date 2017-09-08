<?php
/**
 * 
 */

class Evento
{
	private $sess = NULL;
	private $con  = NULL;
 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}

 	function guardarEvento( $accion, $_evento )
 	{
		$respuesta = "";
		$mensaje   = "";
		$idEvento  = null;

		$idCliente      = (int)$_evento->idCliente;
		$numeroPersonas = (int)$_evento->numeroPersonas;
		$evento         = $this->con->real_escape_string( $_evento->evento );
		$observacion    = $this->con->real_escape_string( $_evento->observacion );
		$anticipo       = (double)$_evento->anticipo;
		$fechaEvento    = $_evento->fechaEventoTxt;
		$horaFinal      = $_evento->horaFinal;
		$horaInicio     = $_evento->horaInicio;

		if ( !( $idCliente > 0 ) )
			$msgError = "Debe seleccionar un cliente";

		else if ( strlen( $evento ) < 4 )
			$msgError = "La descripción del Evento es muy corto";

		else if ( strlen( $fechaEvento ) != 10 )
			$msgError = "La fecha del evento no es válido";

		else if ( strlen( $horaInicio ) != 5 )
			$msgError = "La hora de Inicio no es válida";

		else if ( strlen( $horaFinal ) != 5 )
			$msgError = "La hora Final no es válida";

		else
		{
	 		if( $accion == 'insert' ):
	 			$sql = "CALL consultaEvento( 'insert', NULL, '{$evento}', {$idCliente}, DATE( '{$fechaEvento}' ), TIME( '{$horaInicio}' ), TIME( '{$horaFinal}' ), '{$observacion}', {$anticipo}, NULL, {$numeroPersonas} )";

	 			$rs = $this->con->query( $sql );
	 			if ( $rs AND $row = $rs->fetch_object() )
	 			{
					$respuesta = $row->respuesta;
					$mensaje   = $row->mensaje;
	 				if ( $row->respuesta == 'success' )
						$idEvento = $row->id;
	 			}
	 			else
	 			{
	 				$respuesta = "danger";
					$mensaje   = "Error al ejecutar la consulta";
	 			}

	 			@$this->con->next_result();
	 		endif;
		}

		if ( isset( $msgError ) )
		{
			$respuesta = "danger";
			$mensaje   = $msgError;
		}

		return array(
			'respuesta' => $respuesta,
			'mensaje'   => $mensaje,
			'idEvento'  => $idEvento,
		);
 	}


 	function consultarCliente( $valor )
 	{
 		$lstClientes = [];
 		$where = "";

 		if( strlen($valor) >= 2  AND strtoupper( $valor ) == 'CF' OR strtoupper( $valor ) == 'C/F' )
 			$where = "nit = 'CF'";

 		else if( preg_match('/^[0-9-\s]{5,10}$/', $valor ) AND strlen( $valor ) >= 5  AND strlen( $valor ) <= 10 )
 			$where = "nit = '$valor'";

 		elseif( preg_match('/^[0-9-\s]{13,15}$/', $valor ) AND strlen( $valor) >= 13 AND strlen( $valor) <= 15 )
 			$where = "cui = $valor";

 		else {
 			$valor= str_replace(' ','%', $valor);
			$where = "nombre LIKE '%{$valor}%'  LIMIT 20";
 		}

	 	$sql = "SELECT * FROM vstCliente where $where ;";
	 	
	 	if( $rs = $this->con->query( $sql ) )
	 		while( $row = $rs->fetch_object() )
	 			$lstClientes[] = $row;

	 	return $lstClientes;
 	}
}

?>