<?php 
/**
* CAJA
*/
class Caja
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

	// CONSULTAR HISTORIAL DE CAJAS
 	public function historialCaja( $fecha )
 	{
 		$cajas = new stdClass();
 		$cajas->encontrado = FALSE;
 		$cajas->lstCajas   = [];

 		$where = "";
 		if( $this->sess->getIdPerfil() <> 1 )
 			$where .= "AND usuario = '{$this->sess->getUsuario()}'";

 		$sql = "SELECT * 
 					FROM vstCaja
 				WHERE fechaApertura = '{$fecha}' $where ORDER BY idCaja DESC;";

 		if( $rs = $this->con->query( $sql ) AND $rs->num_rows )
 		{
 			$cajas->encontrado = TRUE;
 			while ( $row = $rs->fetch_object() )
 				$cajas->lstCajas[] = $row;
 				//$row->lstDenominaciones = $this->lstDenominacionesCaja( $row->idCaja );
 		}

 		return $cajas;
 	}


	private function lstDenominacionesCaja( $idCaja )
 	{
 		$lstDenominaciones = new stdClass;
 		$lstDenominaciones->apertura = [];
 		$lstDenominaciones->cierre   = [];

 		$sql = "SELECT * FROM vDenominacionCaja WHERE idCaja = {$idCaja};";

 		if( $rs = $this->con->query( $sql ) AND $rs->num_rows )
 		{
 			while ( $row = $rs->fetch_object() ){
 				if( $row->idEstadoCaja == 1 )
 					$lstDenominaciones->apertura[] = $row;
 				elseif( $row->idEstadoCaja == 2 )
 					$lstDenominaciones->cierre[] = $row;
 			}
 		}

 		return $lstDenominaciones;
 	}

 	// CONSULTAR ESTADO Caja
 	function consultarEstadoCaja()
 	{
 		$fechaApertura = date("Y-m-d");
		$dataCaja = new stdClass();

		$dataCaja->idCaja            = 0;
		$dataCaja->cajaAtrasada      = FALSE;
		$dataCaja->cajero            = $this->sess->getNombre();
		$dataCaja->usuario           = $this->sess->getUsuario();
		$dataCaja->codigoUsuario     = $this->sess->getCodigoUsuario();
		$dataCaja->fechaApertura     = $fechaApertura;
		$dataCaja->efectivoInicial   = (double)0;
		$dataCaja->efectivoFinal     = (double)0;
		$dataCaja->efectivoSobrante  = (double)0;
		$dataCaja->efectivoFaltante  = (double)0;
		$dataCaja->idEstadoCaja      = 2;
		$dataCaja->estadoCaja        = 'Cerrada';
		$dataCaja->totalIngresos     = 0;
		$dataCaja->totalEgresos      = 0;
		$dataCaja->lstDenominaciones = $this->lstDenominaciones( 1 );

 		$sql = "SELECT 
				    idCaja,
				    usuario,
				    fechaApertura,
				    DATE_FORMAT( fechaApertura, '%d/%m/%Y %h:%i %p' ) AS fechaHoraApertura,
				    efectivoInicial,
				    efectivoFinal,
				    efectivoSobrante,
				    efectivoFaltante,
				    idEstadoCaja,
				    estadoCaja,
				    nombres,
				    apellidos,
				    codigo
					FROM
				    	vstCaja
 				WHERE usuario = '{$this->sess->getUsuario()}' AND idEstadoCaja = 1;";
 		
 		if( $rs = $this->con->query( $sql ) AND $rs->num_rows AND $row = $rs->fetch_object() )
 		{
			$row->cajaAtrasada = FALSE;

			if( $row->fechaApertura <> $fechaApertura )
				$row->cajaAtrasada = TRUE;

			//$movimientos = (object)$this->lstMovimientos( NULL, TRUE );
			$dataCaja->idCaja            = (int)$row->idCaja;
			$dataCaja->cajero            = $this->sess->getNombre();
			$dataCaja->cajaAtrasada      = $row->cajaAtrasada;
			$dataCaja->usuario           = $row->usuario;
			$dataCaja->codigoUsuario     = $this->sess->getCodigoUsuario();
			$dataCaja->fechaApertura     = $row->fechaApertura;
			$dataCaja->fechaHoraApertura = $row->fechaHoraApertura;
			$dataCaja->efectivoInicial   = (double)$row->efectivoInicial;
			$dataCaja->efectivoFinal     = (double)$row->efectivoFinal;
			$dataCaja->efectivoSobrante  = (double)$row->efectivoSobrante;
			$dataCaja->efectivoFaltante  = (double)$row->efectivoFaltante;
			$dataCaja->idEstadoCaja      = (int)$row->idEstadoCaja;
			$dataCaja->estadoCaja        = $row->estadoCaja;
			$dataCaja->agregarFaltante   = FALSE;
 		}

 		if( $dataCaja->idCaja )
 		{
	 		$sql = "CALL consultaCuadre( {$dataCaja->idCaja}, 1 );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
	 		{
	 			$this->siguienteResultado();
	 			$dataCaja->totalIngresos = (double)$row->montoDespacho + (double)$row->ingresos;
	 			$dataCaja->totalEgresos  = (double)$row->egresos;
	 		}
 		}

 		return (object)$dataCaja;
 	}


 	// CONSULTA CAJA
 	function consultaCaja( $accion, $data, $total )
 	{
		$idCaja           = "NULL";
		$idEstadoCaja     = "NULL";
		$efectivoInicial  = 0;
		$efectivoFinal    = 0;
		$efectivoSobrante = 0;
		$efectivoFaltante = 0;

 		// SETEO VARIABLES GENERALES
 		$data->combo        = isset( $data->combo ) 		? (string)$data->combo 			: NULL;
 		$data->codigo       = isset( $data->codigo ) 		? (string)$data->codigo 		: NULL;
 		$data->descripcion  = isset( $data->descripcion ) 	? (string)$data->descripcion 	: NULL;
 		$data->idEstadoMenu = isset( $data->idEstadoMenu ) 	? (int)$data->idEstadoMenu 		: NULL;
 		
 		$validar = new Validar();

		if( $accion == 'insert' )
			$data->efectivoInicial = isset( $total ) ? (double)$total : 0;
		else
			$data->efectivoFinal = isset( $total ) ? (double)$total : 0;

		$efectivoInicial = $validar->validarDinero( $data->efectivoInicial, NULL, TRUE, 'el EFECTIVO INICIAL' );
 		// VALIDACIONES
 		if( $accion == 'cierre' ):
			$idCaja        = $validar->validarEntero( $data->idCaja, NULL, TRUE, 'El ID de la CAJA no es válido' );
			$efectivoFinal = (double)$validar->validarDinero( $data->efectivoFinal, NULL, TRUE, 'el EFECTIVO FINAL' );
			$montoDespacho = 0;
			$otrosIngresos = 0;
			$egresos       = 0;
			$totalIngresos = 0;

			// CONSULTA CIERRE DE CAJA (ingresos, egresos, despacho)
 			$sql = "CALL consultaCuadre( {$idCaja}, 1 );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() )
	 		{
				$this->siguienteResultado();
				$montoDespacho = (double)$row->montoDespacho;
				$otrosIngresos = (double)$row->ingresos;
				$egresos       = (double)$row->egresos;
				$totalIngresos = ( $montoDespacho + $otrosIngresos );
	 		}

	 		$montoEfectivo = ( $efectivoFinal - $efectivoInicial );
	 		$montoIngresos = ( $totalIngresos - $egresos );
	
	 		// SI EL MONTO EN EFECTIVO ES MAYOR A LOS INGRESOS
	 		if ( $montoEfectivo > $montoIngresos )
				$efectivoSobrante = ( $montoEfectivo - $montoIngresos );

	 		// SI EL MONTO EN EFECTIVO ES MENOR A LOS INGRESOS
	 		if ( $montoEfectivo < $montoIngresos )
				$efectivoFaltante = ( $montoIngresos - $montoEfectivo );

			// COMPARAR CANTIDADES CONSULTADAS VS EL SISTEMA
			$validar->compararValores( $montoDespacho, ( (double)$data->totalIngresos - $otrosIngresos ), 'Monto de Despacho', 'El sistema', 4 );
			$validar->compararValores( $otrosIngresos, ( (double)$data->totalIngresos - $montoDespacho ), 'Otros Ingresos', 'El sistema', 4 );
			$validar->compararValores( $egresos, ( (double)$data->totalEgresos ), 'cantidad de Egresos', 'El sistema', 4 );
			
			$idEstadoCaja = 2;
 		endif;

 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:

 			$this->con->query( "START TRANSACTION" );

	 		$sql = "CALL consultaCaja( '{$accion}', {$idCaja}, {$idEstadoCaja}, {$efectivoInicial}, {$efectivoFinal}, {$efectivoSobrante}, {$efectivoFaltante} )";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 				$this->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $row->respuesta == 'success' ){
	 					$idEstadoCaja = 1;
	 					$idCaja       = $this->data = $row->id;
	 				}

	 				elseif( $accion == 'cierre' AND $row->respuesta == 'success' ){
	 					$this->mensaje = "Caja cerrada correctamente";

	 					// SI EXISTE SOBRANTE
	 					if ( $efectivoFaltante > 0 )
	 						$this->mensaje .= ", CON FALTANTE EN CAJA.!";

	 					$this->data = (object)array(
	 						'efectivoSobrante' => $efectivoSobrante,
							'efectivoFaltante' => $efectivoFaltante,
	 					);
	 				}
	 				// CONSULTAR ACCION INSERT EN CIERRE
	 				$this->consultaDenominacionCaja( 'insert', $idCaja, $idEstadoCaja, $data->lstDenominaciones );
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion de Caja (SP)';
	 		}

	 		if( $this->respuesta == 'success' )
	 			$this->con->query( "COMMIT" );
	 		else
	 			$this->con->query( "ROLLBACK" );

 		endif;

		return $this->getRespuesta();
 	}


 	// CONSULTA DENOMINACIÓN CAJA
 	function consultaDenominacionCaja( $accion, $idCaja, $idEstadoCaja, $lstDenominaciones )
 	{
 		if( count( $lstDenominaciones ) )
 		{
 			$total = 0;
	 		foreach ( $lstDenominaciones AS $ixDenominacion => $denominaciones ) {
		 		$denominacion = (double)$denominaciones->denominacion;
		 		$cantidad     = isset( $denominaciones->cantidad ) ? (int)$denominaciones->cantidad : 0;

		 		$sql = "CALL consultaDenominacionCaja( '{$accion}', {$idCaja}, {$idEstadoCaja}, '{$denominacion}', {$cantidad} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				if( $this->respuesta == 'danger' )
	 					$this->mensaje   = $row->mensaje;

	 				if ( $this->respuesta == 'success' )
	 					$total += $cantidad;
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la operacion de Denominación (SP)';
		 		}

		 		if( $this->respuesta == 'danger' )
		 			break;
	 		}

	 		if( $this->respuesta != 'danger' AND !$total ){
	 			$this->respuesta = 'warning';
	 			$this->mensaje   = 'No ingreso cantidades en las denominaciones';
	 		}
 		}
 		else
 		{
 			$this->respuesta = 'danger';
		 	$this->mensaje   = 'No se recibio la lista de denominaciones';
 		}

		return $this->getRespuesta();
 	}


 	// CARGAR DENOMINACIONES
	function lstDenominaciones( $estado = NULL )
	{
		$lstDenominaciones = array();
		$where = '';
		if( !is_null( $estado ) )
			$where .= "WHERE estado = {$estado}";

		$sql = "SELECT * FROM denominacion $where;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$row->cantidad       = 0;
				$lstDenominaciones[] = $row;
			}
		}
		
		return $lstDenominaciones;
	}


 	// GUARDA MOVIMIENTO EVENTO
 	function guardarMovimiento( $movimiento )
 	{
		$respuesta = "";
		$mensaje   = "";
		$lastId    = null;

		$id                 = isset( $movimiento->id ) ? (int)$movimiento->id : 'NULL';
		$idTipoMovimiento   = (int)$movimiento->idTipoMovimiento;
		$idEstadoMovimiento = 5;
		$idFormaPago        = 1;
		$motivo             = $this->con->real_escape_string( $movimiento->motivo );
		$monto              = (double)$movimiento->monto;
		$comentario         = isset( $movimiento->comentario ) ? "'" . $this->con->real_escape_string( $movimiento->comentario ) . "'" : "NULL";
		$accion             = $movimiento->accion;

		if ( !( strlen( $motivo ) > 3 ) )
			$msgError = "Motivo demasiado corto";

		else if ( !( $monto > 0 ) )
			$msgError = "Monto no válido, debe ser mayor a cero";

		if ( !( $accion == 'insert' ) )
			$msgError = "Acción no válida";

		else
		{
			$sql = "CALL consultaMovimiento( '$accion', {$id}, {$idTipoMovimiento}, {$idEstadoMovimiento}, {$idFormaPago}, NULL, '{$motivo}', {$monto}, {$comentario} )";

 			$this->con->query( "START TRANSACTION" );

 			$rs = $this->con->query( $sql );
 			@$this->con->next_result();

 			if ( $rs AND $row = $rs->fetch_object() )
 			{
				$respuesta = $row->respuesta;
				$mensaje   = $row->mensaje;
 			}
 			else
 			{
 				$respuesta = "danger";
				$mensaje   = "Error al ejecutar la consulta";
 			}

 			if ( $respuesta == "success" )
 				$this->con->query( "COMMIT" );

 			else
 				$this->con->query( "ROLLBACK" );
		}

		if ( isset( $msgError ) )
		{
			$respuesta = "danger";
			$mensaje   = $msgError;
		}

		return array(
			'respuesta'     => $respuesta,
			'mensaje'       => $mensaje,
		);
 	}

 	// LISTA DE MOVIMIENTOS
	function lstMovimientos( $fecha = null, $validarUsuario = FALSE )
	{
		$lst      = array();
		$ingresos = 0;
		$egresos  = 0;

		$where = 'CURDATE()';
		if( !is_null( $fecha ) )
			$where = " '{$fecha}' ";
		
		if( $validarUsuario )
			$where .= " AND c.usuario = '{$this->sess->getUsuario()}'";

		$sql = "SELECT
					m.motivo,
				    m.monto,
				    tm.idTipoMovimiento,
				    tm.tipoMovimiento,
				    c.usuario AS 'usuarioCaja',
				    em.fechaRegistro
				FROM movimiento AS m
					JOIN logEstadoMovimiento AS em
						ON em.idMovimiento = m.idMovimiento
					JOIN caja AS c
						ON c.idCaja = m.idCaja
					JOIN tipoMovimiento AS tm
						ON tm.idTipoMovimiento = m.idTipoMovimiento
				    JOIN estadoMovimiento AS e
						ON e.idEstadoMovimiento = m.idEstadoMovimiento
				WHERE c.fechaApertura = $where 
				ORDER BY m.idMovimiento DESC";
		//echo $sql;
		$rs = $this->con->query( $sql );
		while( $rs AND $row = $rs->fetch_object() ){
			$row->monto = (double)$row->monto;

			if ( $row->idTipoMovimiento == 3 )
				$ingresos += $row->monto;

			else
				$egresos += $row->monto;

			$lst[] = $row;
		}
		
		return array( 'lstMovimientos' => $lst, 'ingresos' => $ingresos, 'egresos' => $egresos );
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