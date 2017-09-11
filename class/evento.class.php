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

 	// GUARDA EVENTO
 	function guardarEvento( $accion, $_evento )
 	{
		$respuesta = "";
		$mensaje   = "";
		$lastId  = null;

		$idEstadoEvento        = (int)$_evento->idEstadoEvento;
		$idEvento              = (int)$_evento->idEvento;
		$idCliente             = (int)$_evento->idCliente;
		$numeroPersonas        = (int)$_evento->numeroPersonas;
		$evento                = $this->con->real_escape_string( $_evento->evento );
		$observacion           = $this->con->real_escape_string( $_evento->observacion );
		$anticipo              = (double)$_evento->anticipo;
		$fechaEvento           = $_evento->fechaEventoTxt;
		$horaFinal             = $_evento->horaFinal;
		$horaInicio            = $_evento->horaInicio;
		$descuento             = (double)$_evento->descuento;
		$descripcionDescuento  = $this->con->real_escape_string( $_evento->descripcionDescuento );
		$costoExtra            = (double)$_evento->costoExtra;
		$descripcionCostoExtra = $this->con->real_escape_string( $_evento->descripcionCostoExtra );

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

		else if ( $descuento > 0 AND !( strlen( $descripcionDescuento ) > 4 ) )
			$msgError = "Ingrese descripción de Descuento";

		else if ( $costoExtra > 0 AND !( strlen( $descripcionCostoExtra ) > 4 ) )
			$msgError = "Ingrese descripción de Costro Extra";

		else
		{
	 		if( $accion == 'insert' ):
	 			$sql = "CALL consultaEvento( 'insert', NULL, '{$evento}', {$idCliente}, DATE( '{$fechaEvento}' ), TIME( '{$horaInicio}' ), TIME( '{$horaFinal}' ), '{$observacion}', {$anticipo}, NULL, {$numeroPersonas}, {$descuento}, '{$descripcionDescuento}', {$costoExtra}, '{$descripcionCostoExtra}' )";

	 		elseif( $accion == 'update' ):
	 			$sql = "CALL consultaEvento( 'update', {$idEvento}, '{$evento}', {$idCliente}, DATE( '{$fechaEvento}' ), TIME( '{$horaInicio}' ), TIME( '{$horaFinal}' ), '{$observacion}', {$anticipo}, {$idEstadoEvento}, {$numeroPersonas}, {$descuento}, '{$descripcionDescuento}', {$costoExtra}, '{$descripcionCostoExtra}' )";
	 		endif;

 			$rs = $this->con->query( $sql );
 			if ( $rs AND $row = $rs->fetch_object() )
 			{
				$respuesta = $row->respuesta;
				$mensaje   = $row->mensaje;
 				if ( $row->respuesta == 'success' AND $accion == 'insert' )
					$lastId = $row->id;
 			}
 			else
 			{
 				$respuesta = "danger";
				$mensaje   = "Error al ejecutar la consulta";
 			}

 			@$this->con->next_result();
		}

		if ( isset( $msgError ) )
		{
			$respuesta = "danger";
			$mensaje   = $msgError;
		}

		return array(
			'respuesta' => $respuesta,
			'mensaje'   => $mensaje,
			'idEvento'  => $lastId,
		);
 	}

 	// GUARDA MENU DEL EVENTO
 	function guardarMenuEvento( $menu )
 	{
		$respuesta = "";
		$mensaje   = "";
		$lastId    = null;

		$id             = isset( $menu->id ) ? (int)$menu->id : 0;
		$idEvento       = (int)$menu->idEvento;
		$idMenu         = (int)$menu->idMenu;
		$cantidad       = (int)$menu->cantidad;
		$precioUnitario = (double)$menu->precioUnitario;
		$comentario     = $this->con->real_escape_string( $menu->comentario );
		$otroMenu       = $this->con->real_escape_string( $menu->otroMenu );
		$tipo           = $menu->tipo;
		$accion         = $menu->accion;
		$lstMenuEvento  = array();

		if ( !( $idEvento > 0 ) )
			$msgError = "Debe guardar antes el evento";

		else if ( !( $idMenu > 0 ) AND ( $tipo == 'menu' OR $tipo == 'combo' ) )
			$msgError = "Especifique un menú valido";

		else if ( strlen( $otroMenu ) < 4 AND $tipo == 'otroMenu' )
			$msgError = "Ingrese un Menú valido";

		if ( !( $cantidad > 0 ) )
			$msgError = "La cantidad debe ser mayor a cero";

		else if ( !( $precioUnitario > 0 ) )
			$msgError = "El precio debe ser mayor a cero";

		else
		{
			if ( $accion == 'insert' )
				$id = 'NULL';

			if ( $tipo == 'menu' )
	 			$sql = "CALL consultaMenuEvento( '{$accion}', {$id}, {$idEvento}, {$idMenu}, {$cantidad}, {$precioUnitario}, '{$comentario}' )";

	 		else if ( $tipo == 'combo' )
	 			$sql = "CALL consultaComboEvento( '{$accion}', {$id}, {$idEvento}, {$idMenu}, {$cantidad}, {$precioUnitario}, '{$comentario}' )";

	 		else if ( $tipo == 'otroMenu' ) {
				$idMenu = 'NULL';
	 			$sql = "CALL consultaOtroMenuEvento( '{$accion}', {$id}, {$idEvento}, '{$otroMenu}', {$cantidad}, {$precioUnitario}, '{$comentario}' )";
	 		}

 			$rs = $this->con->query( $sql );
 			@$this->con->next_result();

 			if ( $rs AND $row = $rs->fetch_object() )
 			{
				$respuesta = $row->respuesta;
				$mensaje   = $row->mensaje;
 				if ( $row->respuesta == 'success' AND $accion == 'insert' )
					$lastId = $row->id;
 			}
 			else
 			{
 				$respuesta = "danger";
				$mensaje   = "Error al ejecutar la consulta";
 			}
		}

		if ( isset( $msgError ) )
		{
			$respuesta = "danger";
			$mensaje   = $msgError;
		}

		if ( $respuesta == 'success' )
			$lstMenuEvento = $this->consultaDetalleOrdenEvento( $idEvento );

		return array(
			'respuesta'     => $respuesta,
			'mensaje'       => $mensaje,
			'lastId'        => $lastId,
			'lstMenuEvento' => $lstMenuEvento,
		);
 	}

 	// CONSULTA MENUS DEL EVENTO
 	function consultaDetalleOrdenEvento( $idEvento )
 	{
		$lst      = array();
		$idEvento = (int)$idEvento;
		
		$sql = "CALL consultaDetalleOrdenEvento( {$idEvento} )";

		$rs = $this->con->query( $sql );
		@$this->con->next_result();
		while ( $rs AND $row = $rs->fetch_object() )
			$lst[] = $row;

		return $lst;
 	}

 	// CONSULTA EVENTO
 	function consultaEvento( $idEstadoEvento, $idEvento = NULL )
 	{
		$result   = array();
		$idEvento = (int)$idEvento;
		$limit    = "";
		
		if ( $idEvento > 0 )
			$where = " WHERE idEvento = {$idEvento} "; 

		else
		{
			$where = " WHERE idEstadoEvento = {$idEstadoEvento} "; 

			if ( $idEstadoEvento != 1 )
				$limit = " LIMIT 10 ";
		}

		$sql = "SELECT 
					idEvento,
				    evento,
				    fechaEvento,
				    horaInicio,
				    horaFinal,
				    observacion,
				    anticipo,
				    numeroPersonas,
				    usuario,
				    fechaRegistro,
				    idEstadoEvento,
				    estadoEvento,
				    idCliente,
				    nit,
				    nombre,
				    cui,
				    correo,
				    telefono,
				    direccion,
				    idTipoCliente,
				    tipoCliente,
				    descuento,
				    descripcionDescuento,
				    costoExtra,
				    descripcionCostoExtra
				FROM vEvento " . $where . $limit;

		$rs = $this->con->query( $sql );

		if ( $idEvento > 0 )
		{
			if ( $rs AND $row = $rs->fetch_object() )
			{
				$row->numeroPersonas = (int)$row->numeroPersonas;
				$row->anticipo       = (double)$row->anticipo;
				$row->descuento      = (double)$row->descuento;
				$row->costoExtra     = (double)$row->costoExtra;
				$row->lstMenu        = $this->consultaDetalleOrdenEvento( $row->idEvento );
				$result = $row;
			}
		}
		else
		{
			while ( $rs AND $row = $rs->fetch_object() )
			{
				$row->numeroPersonas = (int)$row->numeroPersonas;
				$row->anticipo       = (double)$row->anticipo;
				$row->descuento      = (double)$row->descuento;
				$row->costoExtra     = (double)$row->costoExtra;
				$row->lstMenu        = $this->consultaDetalleOrdenEvento( $row->idEvento );
				$result[] = $row;
			}

		}
			
		return $result;
 	}
}

?>