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


 	// CONSULTAR ESTADO Caja
 	function consultarEstadoCaja()
 	{
 		$fechaApertura = date("Y-m-d");
	
		$dataCaja = array(
			'idCaja'            => 0,
			'cajaAtrasada'      => FALSE,
			'cajero'            => $this->sess->getNombre(),
			'usuario'           => $this->sess->getUsuario(),
			'codigoUsuario'     => $this->sess->getCodigoUsuario(),
			'fechaApertura'     => $fechaApertura,
			'efectivoInicial'   => (double)0,
			'efectivoFinal'     => (double)0,
			'efectivoSobrante'  => (double)0,
			'efectivoFaltante'  => (double)0,
			'idEstadoCaja'      => 2,
			'estadoCaja'        => 'Cerrada',
			'lstDenominaciones' => $this->lstDenominaciones( 1 )
		);

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
 		
 		if( $rs = $this->con->query( $sql ) ){

 			if( $rs->num_rows AND $row = $rs->fetch_object() ){

				$row->cajaAtrasada = FALSE;
 				if( $row->fechaApertura <> $fechaApertura )
 					$row->cajaAtrasada = TRUE;

	 			$dataCaja = array(
	 					'idCaja'            => (int)$row->idCaja,
	 					'cajero'            => $this->sess->getNombre(),
	 					'cajaAtrasada'      => $row->cajaAtrasada,
	 					'usuario'           => $row->usuario,
	 					'codigoUsuario'     => $this->sess->getCodigoUsuario(),
	 					'fechaApertura'     => $row->fechaApertura,
	 					'fechaHoraApertura' => $row->fechaHoraApertura,
	 					'efectivoInicial'   => (double)$row->efectivoInicial,
	 					'efectivoFinal'     => (double)$row->efectivoFinal,
	 					'efectivoSobrante'  => (double)$row->efectivoSobrante,
	 					'efectivoFaltante'  => (double)$row->efectivoFaltante,
	 					'idEstadoCaja'      => (int)$row->idEstadoCaja,
	 					'estadoCaja'        => $row->estadoCaja,
	 					'totalCierre'       => 0,
	 					'egresos'           => 0,
	 					'agregarFaltante'   => FALSE,
	 					'lstDenominaciones' => $this->lstDenominaciones( 1 )
	 				);
 			}

 		}

 		return (object)$dataCaja;
 	}


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
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
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