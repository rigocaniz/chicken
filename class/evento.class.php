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

		$idEstadoEvento = (int)$_evento->idEstadoEvento;
		$idEvento       = (int)$_evento->idEvento;
		$idCliente      = (int)$_evento->idCliente;
		$numeroPersonas = (int)$_evento->numeroPersonas;
		$idSalon        = (int)$_evento->idSalon;
		$evento         = $this->con->real_escape_string( $_evento->evento );
		$observacion    = $this->con->real_escape_string( $_evento->observacion );
		$comentario     = isset( $_evento->comentario ) ? "'" . $this->con->real_escape_string( $_evento->comentario ) . "'" : 'NULL';
		$fechaEvento    = $_evento->fechaEventoTxt;
		$horaInicio     = $_evento->horaInicio;
		$horaFinal      = $_evento->horaFinal;

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
	 			$sql = "CALL consultaEvento( 'insert', NULL, '{$evento}', {$idCliente}, DATE( '{$fechaEvento}' ), {$idSalon}, 1, {$numeroPersonas}, TIME( '{$horaInicio}' ), TIME( '{$horaFinal}' ), '{$observacion}', {$comentario} )";

	 		elseif( $accion == 'update' ):
	 			$sql = "CALL consultaEvento( 'update', {$idEvento}, '{$evento}', {$idCliente}, DATE( '{$fechaEvento}' ), {$idSalon}, {$idEstadoEvento}, {$numeroPersonas}, TIME( '{$horaInicio}' ), TIME( '{$horaFinal}' ), '{$observacion}', {$comentario} )";
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
		$horaDespacho   = isset( $menu->horaDespacho ) ? "TIME('" . $menu->horaDespacho . "')" : 'NULL';
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
	 			$sql = "CALL consultaMenuEvento( '{$accion}', {$id}, {$idEvento}, {$idMenu}, {$cantidad}, {$horaDespacho}, {$precioUnitario}, '{$comentario}' )";

	 		else if ( $tipo == 'combo' )
	 			$sql = "CALL consultaComboEvento( '{$accion}', {$id}, {$idEvento}, {$idMenu}, {$cantidad}, {$horaDespacho}, {$precioUnitario}, '{$comentario}' )";

	 		else if ( $tipo == 'otroMenu' ) {
				$idMenu = 'NULL';
	 			$sql = "CALL consultaOtroMenuEvento( '{$accion}', {$id}, {$idEvento}, '{$otroMenu}', {$cantidad}, {$horaDespacho}, {$precioUnitario}, '{$comentario}' )";
	 		}
	 		else if ( $tipo == 'otroServicio' ) {
				$idMenu = 'NULL';
	 			$sql = "CALL consultaOtroServicio( '{$accion}', {$id}, {$idEvento}, '{$otroMenu}', {$cantidad}, {$precioUnitario}, '{$comentario}' )";
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

 	// FACTURAR EVENTO
 	function facturarEvento( $evento )
 	{
		$respuesta     = "";
		$mensaje       = "";
		$idEvento      = (int)$evento->idEvento;
		$totalEvento   = (double)$evento->totalEvento;
		$totalAnticipo = (double)$evento->totalAnticipo;
		$montoEfectivo = (double)$evento->montoEfectivo;
		$montoTarjeta  = (double)$evento->montoTarjeta;
		$cambio        = (double)$evento->cambio;
		$idCliente     = (int)$evento->idCliente;
		$siDetalle     = (int)$evento->siDetalle;
		$descripcion   = strlen( $evento->descripcion ) ? "'" . $evento->descripcion . "'" : 'NULL';
		$nombre        = $evento->nombre;
		$direccion     = $evento->direccion;
		$idFactura 	   = null;

		// OBTENER DATOS DE CAJA
		$caja        = new Caja();
		$detalleCaja = $caja->consultarEstadoCaja();

		// OBTIENE FORMAS DE PAGO
		$lstPago = array();
		if ( $montoEfectivo > 0 )
			$lstPago[] = (object)array( 'idFormaPago' => 1, 'monto' => ( $montoEfectivo - $cambio ) );

		if ( $montoTarjeta > 0 )
			$lstPago[] = (object)array( 'idFormaPago' => 2, 'monto' => $montoTarjeta );


		if ( !( $idEvento > 0 ) )
			$msgError = "Id Evento no válido";

		else if ( ( $totalEvento - $totalAnticipo ) > ( $montoEfectivo + $montoTarjeta ) )
			$msgError = "Monto no puede cubrir evento";

		else if( $detalleCaja->idEstadoCaja != 1 )
			$msgError = 'Su <b>CAJA</b> se encuentra <b>' . strtoupper( $caja->consultarEstadoCaja()->estadoCaja ) . "</b> debe aperturarla para poder facturar";

		 elseif( $detalleCaja->cajaAtrasada )
			$msgError = ' NO HA REALIZADO EL CIERRE DE SU CAJA DE FECHA/HORA: <b>' . $detalleCaja->fechaHoraApertura . "</b>";

		else
		{
			$this->con->query( "START TRANSACTION" );

			// GUARDA INFORMACION DE FACTURA
			$sql = "CALL consultaFacturaCliente( 'insert', NULL, NULL, $idCliente, $detalleCaja->idCaja, '$nombre', '$direccion', $totalEvento, $descripcion )";

			$rs = $this->con->query( $sql );
 			@$this->con->next_result();
 			if ( $rs AND $row = $rs->fetch_object() ) {
				$respuesta = $row->respuesta;
				$mensaje   = $row->mensaje;
 				if ( $row->respuesta == 'success' )
					$idFactura = $row->id;
 			}
 			else {
 				$respuesta = "danger";
				$mensaje   = "Error al ejecutar la consulta";
 			}

			// GUARDA INFORMACION DE PAGO
 			if ( $respuesta == 'success' ) {
				foreach ($lstPago as $pago):
					$sql = "CALL consultaFormaPago( 'insert', $idFactura, $pago->idFormaPago, $pago->monto )";

					$rs = $this->con->query( $sql );
		 			@$this->con->next_result();
		 			if ( $rs AND $row = $rs->fetch_object() ) {
						$respuesta = $row->respuesta;
						$mensaje   = $row->mensaje;
		 			}
		 			else {
		 				$respuesta = "danger";
						$mensaje   = "Error al ejecutar la consulta";
		 			}

		 			if ( $respuesta != 'success' )
		 				break;

				endforeach;
 			}

			
			// GUARDA DETALLE DE FACTURA
 			if ( $respuesta == 'success' ) {
				$sql = "CALL detalleFacturaEvento( $idFactura, $idEvento )";

				$rs = $this->con->query( $sql );
	 			@$this->con->next_result();
	 			if ( $rs AND $row = $rs->fetch_object() ) {
					$respuesta = $row->respuesta;
					$mensaje   = $row->mensaje;
	 			}
	 			else {
	 				$respuesta = "danger";
					$mensaje   = "Error al ejecutar la consulta";
	 			}
	 		}


			// ACTUALIZA FACTURA DE EVENTO
 			if ( $respuesta == 'success' ) {
				$sql = "UPDATE evento SET
							idFactura = $idFactura
						WHERE idEvento = $idEvento ";

	 			if ( !$this->con->query( $sql ) ) {
	 				$respuesta = "danger";
					$mensaje   = "Error al guardar factura en evento";
	 			}
	 		}
	 		
 			if ( $respuesta == 'success' ) {
				$mensaje = "Guardado correctamente";
				$this->con->query( "COMMIT" );
 			}

			else
				$this->con->query( "ROLLBACK" );
		}

		if ( isset( $msgError ) )
		{
			$respuesta = "danger";
			$mensaje   = $msgError;
		}

		return array(
			'respuesta' => $respuesta,
			'mensaje'   => $mensaje,
			'idFactura' => $idFactura,
		);
 	}

 	// GUARDA MOVIMIENTO EVENTO
 	function guardarMovimiento( $movimiento )
 	{
		$respuesta = "";
		$mensaje   = "";
		$lastId    = null;

		$id                 = isset( $movimiento->id ) ? (int)$movimiento->id : 'NULL';
		$idEstadoMovimiento = isset( $movimiento->idEstadoMovimiento ) ? (int)$movimiento->idEstadoMovimiento : 'NULL';
		$idFormaPago        = (int)$movimiento->idFormaPago;
		$idEvento           = (int)$movimiento->idEvento;
		$monto              = (double)$movimiento->monto;
		$motivo             = $this->con->real_escape_string( $movimiento->motivo );
		$comentario         = isset( $movimiento->comentario ) ? "'" . $this->con->real_escape_string( $movimiento->comentario ) . "'" : "NULL";
		$accion             = $movimiento->accion;

		if ( !( $idFormaPago > 0 ) )
			$msgError = "Forma de Pago no válido";

		else if ( !( $monto > 0 ) )
			$msgError = "Monto no válido, debe ser mayor a cero";

		if ( !( strlen( $motivo ) > 3 ) )
			$msgError = "Debe ingresar la descripción";

		else
		{
			if ( $accion == 'insertMove' )
			{
				$idEstadoMovimiento = 5;
	 			$sql = "CALL consultaMovimiento( 'insert', {$id}, 1, {$idEstadoMovimiento}, {$idFormaPago}, {$idEvento}, '{$motivo}', {$monto}, {$comentario} )";
			}

			else if ( $accion == 'deleteMove' )
			{
	 			$sql = "CALL consultaMovimiento( 'delete', {$id}, NULL, NULL, NULL, NULL, NULL, NULL, NULL )";
			}

 			$rs = $this->con->query( $sql );
 			@$this->con->next_result();

 			if ( $rs AND $row = $rs->fetch_object() )
 			{
				$respuesta = $row->respuesta;
				$mensaje   = $row->mensaje;
 				if ( $row->respuesta == 'success' AND $accion == 'insertMove' )
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

		return array(
			'respuesta'     => $respuesta,
			'mensaje'       => $mensaje,
			'lstMovimiento' => $this->lstMovimiento( $idEvento ),
		);
 	}

 	// MOVIMIENTO DE EVENTO
 	function lstMovimiento( $idEvento )
 	{
		$idEvento = (int)$idEvento;
		$lst      = array();

	 	$sql = "SELECT
	 				m.idMovimiento,
	 				m.monto,
	 				m.motivo,
	 				em.idEstadoMovimiento,
	 				em.estadoMovimiento,
	 				tm.idTipoMovimiento,
	 				tm.tipoMovimiento,
	 				fp.idFormaPago,
	 				fp.formaPago,
	 				lem.usuario,
	 				lem.fechaRegistro
	 			FROM movimiento AS m
	 				
	 				JOIN estadoMovimiento AS em
	 					ON m.idEstadoMovimiento = em.idEstadoMovimiento

					JOIN tipoMovimiento AS tm
						ON m.idTipoMovimiento = tm.idTipoMovimiento

					JOIN formaPago AS fp
						ON m.idFormaPago = fp.idFormaPago

					LEFT JOIN logEstadoMovimiento AS lem
						ON m.idMovimiento = lem.idMovimiento AND m.idEstadoMovimiento = lem.idEstadoMovimiento

	 			WHERE m.idEvento = {$idEvento} ";

 		$rs = $this->con->query( $sql );
 		if ( $rs->num_rows ) {
 			while ( $row = $rs->fetch_object() )
 				$lst[] = $row;
 		}

		return $lst;
 	}

 	// CONSULTA MENUS DEL EVENTO
 	function consultaDetalleOrdenEvento( $idEvento )
 	{
		$lst      = array();
		$idEvento = (int)$idEvento;
		
		$sql = "CALL consultaDetalleOrdenEvento( {$idEvento} )";

		$rs = $this->con->query( $sql );
		@$this->con->next_result();
		while ( $rs AND $row = $rs->fetch_object() ){
			$row->cantidad = (double)$row->cantidad;
			$lst[] = $row;
		}

		return $lst;
 	}

 	// CONSULTA EVENTO
 	function consultaEvento( $idEstadoEvento, $idEvento = NULL, $fecha = NULL )
 	{
		$result   = array();
		$idEvento = (int)$idEvento;
		$limit    = "";
		
		if ( $idEvento > 0 )
			$where = " idEvento = {$idEvento} "; 

		elseif ( strlen( $fecha ) === 10 )
			$where = " fechaEvento = '{$fecha}' "; 

		else
		{
			$where = " idEstadoEvento = {$idEstadoEvento} "; 

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
				    idSalon,
				    salon,
				    idFactura
				FROM vEvento WHERE " . $where . $limit;
		$rs = $this->con->query( $sql );

		if ( $idEvento > 0 )
		{
			if ( $rs AND $row = $rs->fetch_object() )
			{
				$row->numeroPersonas = (int)$row->numeroPersonas;
				$row->lstMenu        = $this->consultaDetalleOrdenEvento( $row->idEvento );
				$row->lstMovimiento  = $this->lstMovimiento( $row->idEvento );
				$result = $row;
			}
		}
		else
		{
			while ( $rs AND $row = $rs->fetch_object() )
			{
				$row->numeroPersonas = (int)$row->numeroPersonas;
				$row->lstMenu        = $this->consultaDetalleOrdenEvento( $row->idEvento );
				$row->lstMovimiento  = $this->lstMovimiento( $row->idEvento );
				$result[] = $row;
			}

		}
			
		return $result;
 	}
}

?>