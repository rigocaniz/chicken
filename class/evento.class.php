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


 	function guardarMenuEvento( $menu )
 	{
		$respuesta = "";
		$mensaje   = "";
		$id        = null;

		$idEvento       = (int)$menu->idEvento;
		$idMenu         = (int)$menu->idMenu;
		$cantidad       = (int)$menu->cantidad;
		$precioUnitario = (double)$menu->precioUnitario;
		$comentario     = $this->con->real_escape_string( $menu->comentario );
		$otroMenu       = $this->con->real_escape_string( $menu->otroMenu );
		$tipo           = $menu->tipo;
		$accion         = $menu->accion;

		if ( !( $idEvento > 0 ) )
			$msgError = "Debe guardar antes el evento";

		else if ( !( $idMenu > 0 AND ( $tipo == 'menu' OR $tipo == 'combo' ) ) )
			$msgError = "Especifique un menú valido";

		else if ( strlen( $otroMenu ) < 4 AND $tipo == 'otroMenu' )
			$msgError = "Ingrese un Menú valido";

		if ( !( $cantidad > 0 ) )
			$msgError = "La cantidad debe ser mayor a cero";

		else if ( !( $precioUnitario > 0 ) )
			$msgError = "El precio debe ser mayor a cero";

		else
		{
			if ( $tipo == 'otroMenu' )
				$idMenu = 'NULL';

	 		if( $accion == 'insert' ):
	 			$sql = "CALL consultaMenuEvento( 'insert', NULL, {$idEvento}, {$idMenu}, {$cantidad}, {$precioUnitario}, '{$comentario}' )";

	 			$rs = $this->con->query( $sql );
	 			if ( $rs AND $row = $rs->fetch_object() )
	 			{
					$respuesta = $row->respuesta;
					$mensaje   = $row->mensaje;
	 				if ( $row->respuesta == 'success' )
						$id = $row->id;
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
			'id'        => $id,
		);
 	}
}

?>