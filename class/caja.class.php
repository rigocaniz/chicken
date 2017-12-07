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


 	// CONSULTAR ESTADO CAJA
 	function consultarEstadoCaja()
 	{
 		$fechaApertura = date("Y-m-d");
	
		$dataCaja = array(
			'idCaja'            => 0,
			'cajaAtrasada'      => FALSE,
			'usuario'           => $this->sess->getUsuario(),
			'codigoUsuario'     => $this->sess->getCodigoUsuario(),
			'fechaApertura'     => $fechaApertura,
			'efectivoInicial'   => (double)0,
			'efectivoFinal'     => (double)0,
			'efectivoSobrante'  => (double)0,
			'efectivoFaltante'  => (double)0,
			'idEstadoCaja'      => 2,
			'estadoCaja'        => 'Cerrada',
			'lstDenominaciones' => $this->lstDenominaciones()
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
	 					'lstDenominaciones' => $this->lstDenominaciones()
	 				);
 			}

 		}

 		return (object)$dataCaja;
 	}


 	function consultaCaja( $accion, $data )
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
		
		$data->efectivoInicial = isset( $data->efectivoInicial ) 	? (double)$data->efectivoInicial : NULL;
		$data->efectivoInicial = (double)$data->efectivoInicial > 0 ? (double)$data->efectivoInicial : NULL;

		$efectivoInicial = $validar->validarDinero( $data->efectivoInicial, NULL, TRUE, 'el EFECTIVO INICIAL' );
 		// VALIDACIONES
 		if( $accion == 'cierre' ):
			$data->idCaja           = isset( $data->idCaja ) 				? (int)$data->idCaja 				: NULL;
			$data->idCaja           = (int)$data->idCaja > 0 				? (int)$data->idCaja 				: NULL;

			$data->idEstadoCaja     = isset( $data->idEstadoCaja ) 			? (int)$data->idEstadoCaja 			: NULL;
			$data->idEstadoCaja     = (int)$data->idEstadoCaja > 0 			? (int)$data->idEstadoCaja 			: NULL;

			$data->efectivoInicial  = isset( $data->efectivoInicial ) 		? (double)$data->efectivoInicial 	: NULL;
			$data->efectivoInicial  = (double)$data->efectivoInicial > 0 	? (double)$data->efectivoInicial 	: NULL;

			$data->efectivoFinal    = isset( $data->efectivoFinal ) 		? (double)$data->efectivoFinal 		: NULL;
			$data->efectivoFinal    = (double)$data->efectivoFinal > 0 		? (double)$data->efectivoFinal 		: NULL;

			$data->efectivoSobrante = isset( $data->efectivoSobrante ) 		? (double)$data->efectivoSobrante 	: NULL;
			$data->efectivoSobrante = (double)$data->efectivoSobrante;

			$data->efectivoFaltante = isset( $data->efectivoFaltante ) 		? (double)$data->efectivoFaltante 	: NULL;
			$data->efectivoFaltante = (double)$data->efectivoFaltante;
 		
			$idCaja           = $validar->validarEntero( $data->idCaja, NULL, TRUE, 'El ID de la CAJA no es válido' );
			$idEstadoCaja     = 2;
			$efectivoFinal    = $validar->validarDinero( $data->efectivoFinal, NULL, TRUE, 'el EFECTIVO FINAL' );
			$efectivoSobrante = $data->efectivoSobrante;
			$efectivoFaltante = $data->efectivoFaltante;

 		endif;

 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:

	 		$sql = "CALL consultaCaja( '{$accion}', {$idCaja}, {$idEstadoCaja}, {$efectivoInicial}, {$efectivoFinal}, {$efectivoSobrante}, {$efectivoFaltante} )";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $row->respuesta == 'success' )
	 					$this->data = $row->id;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion de Caja (SP)';
	 		}

 		endif;

		return $this->getRespuesta();
 	}


 	function consultaDenominacionCaja( $accion, $idCaja, $idEstadoCaja, $denominacion, $cantidad )
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
		
		$data->efectivoInicial = isset( $data->efectivoInicial ) 	? (double)$data->efectivoInicial : NULL;
		$data->efectivoInicial = (double)$data->efectivoInicial > 0 ? (double)$data->efectivoInicial : NULL;

		$efectivoInicial = $validar->validarDinero( $data->efectivoInicial, NULL, TRUE, 'el EFECTIVO INICIAL' );
 		// VALIDACIONES
 		if( $accion == 'cierre' ):
			$data->idCaja           = isset( $data->idCaja ) 				? (int)$data->idCaja 				: NULL;
			$data->idCaja           = (int)$data->idCaja > 0 				? (int)$data->idCaja 				: NULL;

			$data->idEstadoCaja     = isset( $data->idEstadoCaja ) 			? (int)$data->idEstadoCaja 			: NULL;
			$data->idEstadoCaja     = (int)$data->idEstadoCaja > 0 			? (int)$data->idEstadoCaja 			: NULL;

			$data->efectivoInicial  = isset( $data->efectivoInicial ) 		? (double)$data->efectivoInicial 	: NULL;
			$data->efectivoInicial  = (double)$data->efectivoInicial > 0 	? (double)$data->efectivoInicial 	: NULL;

			$data->efectivoFinal    = isset( $data->efectivoFinal ) 		? (double)$data->efectivoFinal 		: NULL;
			$data->efectivoFinal    = (double)$data->efectivoFinal > 0 		? (double)$data->efectivoFinal 		: NULL;

			$data->efectivoSobrante = isset( $data->efectivoSobrante ) 		? (double)$data->efectivoSobrante 	: NULL;
			$data->efectivoSobrante = (double)$data->efectivoSobrante;

			$data->efectivoFaltante = isset( $data->efectivoFaltante ) 		? (double)$data->efectivoFaltante 	: NULL;
			$data->efectivoFaltante = (double)$data->efectivoFaltante;
 		
			$idCaja           = $validar->validarEntero( $data->idCaja, NULL, TRUE, 'El ID de la CAJA no es válido' );
			$idEstadoCaja     = 2;
			$efectivoFinal    = $validar->validarDinero( $data->efectivoFinal, NULL, TRUE, 'el EFECTIVO FINAL' );
			$efectivoSobrante = $data->efectivoSobrante;
			$efectivoFaltante = $data->efectivoFaltante;

 		endif;

 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:

	 		$sql = "CALL consultaCaja( '{$accion}', {$idCaja}, {$idEstadoCaja}, {$efectivoInicial}, {$efectivoFinal}, {$efectivoSobrante}, {$efectivoFaltante} )";
	 		
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $row->respuesta == 'success' )
	 					$this->data = $row->id;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion de Caja (SP)';
	 		}

 		endif;

		return $this->getRespuesta();

 	}


 	// CARGAR DENOMINACIONES
	function lstDenominaciones()
	{
		$lstDenominaciones = array();

		$sql = "SELECT * FROM denominacion;";
		
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