<?php 
/**
* SUBIR ARCHIVOS [ IMAGENES ]
*/

// VARIABLES GENERALES
$mensaje   = "";		// MENSAJE
$respuesta = 0;			// RESPUESTA
$error     = FALSE;		// ERROR
$imagen    = "";		// UBICACIÓN IMAGEN BD

if( !isset( $_POST['id'] ) && !isset( $_POST['tipo'] ) || !isset( $_FILES ) )
{
	$error   = TRUE;
	$mensaje = "Datos inválidos, vuelva a intentarlo";
	exit();
}

else if( getimagesize( $_FILES[ 'imagen' ][ 'tmp_name' ]) == false )
{
	$error   = TRUE;
	$mensaje = "El archivo cargado no es una imagen";
}

else if( $_FILES[ 'imagen' ][ 'size' ] > 200000 )
{
	$error   = TRUE;
	$mensaje = "El archivo supera los 2MB de peso permitido";
}

else
{

	// CONEXION BD
	include 'class/conexion.class.php';
	$id      = $_POST['id'];
	$tipo    = $_POST['tipo'];
	

	// SETEO DE VARIABLES
	$carpeta        = "img-menu/";

	$nombreArchivo  = $_FILES[ 'imagen' ][ 'name' ];
	$tipoArchivo    = $_FILES[ 'imagen' ][ 'type' ];
	$nombreTemporal = $_FILES[ 'imagen' ][ 'tmp_name' ];
	$errorArchivo   = $_FILES[ 'imagen' ][ 'error' ];
	$pesoArchivo    = $_FILES[ 'imagen' ][ 'size' ];

	$idUnico        = uniqid();

	$info           = new SplFileInfo( $_FILES[ 'imagen' ][ 'name' ] );		// OBTENER EXTENSIÓN
	$nombreArchivo  = $id . '_' . $nombreArchivo . '_' . $idUnico;
	$rutaArchivo    = $carpeta . $nombreArchivo; 		// DIRECTORIO + ARCHIVO

	//echo basename( $_FILES["imagen"]["name"] );

	// VALIDAR ARCHIVO CARGADO
	if( move_uploaded_file( $nombreTemporal, $rutaArchivo ) )
	{	
		// INICIALIZAR TRANSACCIÓN
		$conexion->query( "START TRANSACTION" );

		if( $tipo == 'menu' )		// MENU
			$sql = "SELECT imagen FROM menu WHERE idMenu = {$id};"
		
		elseif( $tipo == 'combo' )	// COMBO
			$sql = "SELECT imagen FROM combo WHERE idCombo = {$id};";
		
		elseif( $tipo == 'supercombo' )	// SUPERCOMBO
			$sql = "SELECT imagen FROM superCombo WHERE idSuperCombo = {$id};";
		
		if( $rs = $this->con->query( $sql ) ){
			@$this->con->next_result();
			if( $row = $rs->fetch_object() ){
				$imagen = $row->imagen;
			}
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		}

		// FINALIZAR TRANSACCIÓN
		if( $error )
		{
			$conexion->query( "ROLLBACK" );
			$respuesta = 0;
		}
		else{
			$conexion->query( "COMMIT" );
			$respuesta = 1;
			$mensaje   = "Guardado correctamente.";

			if ( file_exists( $imagen ) )
				unlink( $imagen );
		}

	}
	else{
		$error = TRUE;
		$mensaje = "Error al mover el archivo, vuelva a intentarlo";
	}

	
	// SI HAY ERROR ELIMINAR TEMPORAL
	if( $error )
	{
		if ( file_exists( $rutaArchivo ) )
			unlink( $rutaArchivo );

	}

}


if( $error )
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje, "error" => $mensaje );
else
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje );


echo json_encode( $response ); 

?>