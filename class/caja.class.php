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

 	// CATALOGO FORMAS PAGO
 	function catFormasPago()
 	{
 		$lstFormasPago = [];

 		$sql = "SELECT 
				    idFormaPago, 
				    formaPago, 
				    porcentajeRecargo, 
				    montoRecargo
					FROM
						formaPago
					ORDER BY idFormaPago;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstFormasPago[] = $row;
 			}
 		}

 		return $lstFormasPago;
 	}


 	// CONSULTAR ESTADO CAJA
 	function consultarEstadoCaja()
 	{
 		$fechaApertura = date("Y-m-d");
 		
 		$dataCaja = NULL;
 		$sql = "SELECT 
				    idCaja,
				    usuario,
				    efectivoInicial,
				    efectivoFinal,
				    efectivoSobrante,
				    efectivoFaltante,
				    idEstadoCaja,
				    estadoCaja
					FROM
				    	vstCaja
 				WHERE usuario = '{$this->sess->getUsuario()}' AND fechaApertura = '{$fechaApertura}' AND idEstadoCaja = 1;";
 		
 		if( $rs = $this->con->query( $sql ) ){

 			if( $rs->num_rows AND $row = $rs->fetch_object() ){

	 			$datos = array(
	 					'idCaja'           => (int)$row->idCaja,
	 					'usuario'          => $row->usuario,
	 					'efectivoInicial'  => (double)$row->efectivoInicial,
	 					'efectivoFinal'    => (double)$row->efectivoFinal,
	 					'efectivoSobrante' => (double)$row->efectivoSobrante,
	 					'efectivoFaltante' => (double)$row->efectivoFaltante,
	 					'idEstadoCaja'     => (int)$row->idEstadoCaja,
	 					'estadoCaja'       => $row->estadoCaja,
	 				);
 			}
 			else{
 				$datos = array(
					'idCaja'           => (int)0,
					'usuario'          => $this->sess->getUsuario(),
					'fechaApertura'    => $fechaApertura,
					'efectivoInicial'  => (double)0,
					'efectivoFinal'    => (double)0,
					'efectivoSobrante' => (double)0,
					'efectivoFaltante' => (double)0,
					'idEstadoCaja'     => (int)4,
					'estadoCaja'       => 'Cerrada'
				);
 			}

 			$dataCaja = $datos;
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

 		//echo $accion;
 		// SETEO VARIABLES GENERALES
 		$data->combo        = isset( $data->combo ) 		? (string)$data->combo 			: NULL;
 		$data->codigo       = isset( $data->codigo ) 		? (string)$data->codigo 		: NULL;
 		$data->descripcion  = isset( $data->descripcion ) 	? (string)$data->descripcion 	: NULL;
 		$data->idEstadoMenu = isset( $data->idEstadoMenu ) 	? (int)$data->idEstadoMenu 		: NULL;
 		
 		$validar = new Validar();
 		// VALIDACIONES
 		if( $accion == 'insert' ):
 			$data->efectivoInicial = isset( $data->efectivoInicial ) 	? (double)$data->efectivoInicial : NULL;
 			$data->efectivoInicial = (double)$data->efectivoInicial > 0 ? (double)$data->efectivoInicial : NULL;

 			$efectivoInicial = $validar->validarDinero( $data->efectivoInicial, NULL, TRUE, 'el EFECTIVO INICIAL' );
 		endif;


 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();
	 		$this->tiempo    = $validar->getTiempo();
 		else:
	 		//var_dump( $data );

	 		$sql = "CALL consultaCaja( '{$accion}', {$idCaja}, {$idEstadoCaja}, {$efectivoInicial}, {$efectivoFinal}, {$efectivoSobrante}, {$efectivoFaltante} )";

	 		//echo $sql;
	 		
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