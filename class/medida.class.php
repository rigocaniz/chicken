<?php 
/**
* Medida
*/
class Medida
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

 	
 	// LIBERAR SIGUIENTE RESULTADO
 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}


 	// CONSULTAMEDIDA // INSERT // UPDATE
	function consultaMedida( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idMedida = 'NULL';
		$medida   = NULL;

		// SETEO VARIABLES GENERALES
 		$data->medida = isset( $data->medida ) ? (string)$data->medida : NULL;

 		// VALIDACIONES
 		if( $accion == 'update' ):
 			$data->idMedida = isset( $data->idMedida ) ? (int)$data->idMedida : NULL;
 			$idMedida       = $validar->validarEntero( $data->idMedida, NULL, TRUE, 'El ID del Menú no es válido, verifique.' );
 		endif;

 		$medida = $this->con->real_escape_string( $validar->validarTexto( $data->medida, NULL, TRUE, 3, 45, 'en la descripcíón de la medida' ) );

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaMedida( '{$accion}', {$idMedida}, '{$medida}' );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			$this->siguienteResultado();

	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;

	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


	// CONSULTAR DATOS MEDIDA
	function cargarMedida( $idMedida )
	{
		$idMedida = (int)$idMedida;
		$medida   = array();

		$sql = "SELECT idMedida, medida FROM medida WHERE idMedida = {$idMedida};";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() )
				$medida = $row;
		}

		return $medida;
	}


	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
 	{

 		if( $this->respuesta == 'success' )
 			$this->tiempo = 4;
 		elseif( $this->respuesta == 'warning')
 			$this->tiempo = 5;
 		elseif( $this->respuesta == 'danger')
 			$this->tiempo = 6;

 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}

}
?>