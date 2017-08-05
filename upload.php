<?php 
/**
* SUBIR ARCHIVOS [ IMAGENES ]
*/

// VARIABLES GENERALES
$mensaje   = "";
$respuesta = 0;
$error     = FALSE;


if( !isset( $_POST['id'] ) && !isset( $_POST['tipo'] ) || !isset( $_FILES ) )
{
	$error = TRUE;
	$mensaje = "Datos inválidos, vuelva a intentarlo";
	exit();
}

else if( getimagesize( $_FILES[ 'imagen' ][ 'tmp_name' ]) == false )
{
	$error   = TRUE;
	$mensaje = "El archivo cargado no es una imagen";
}

else if( $_FILES[ 'imagen' ][ 'size' ] > 20000 )
{
	$error   = TRUE;
	$mensaje = "El archivo supera los 2MB de peso permitido";
}

else{

	// CONEXION BD
	include 'class/conexion.class.php';
	$id      = $_POST['id'];
	$tipo    = $_POST['tipo'];
	

	// SETEO DE VARIABLES
	$carpeta        = "upload/";

	$nombreArchivo  = $_FILES[ 'imagen' ][ 'name' ];
	$tipoArchivo    = $_FILES[ 'imagen' ][ 'type' ];
	$nombreTemporal = $_FILES[ 'imagen' ][ 'tmp_name' ];
	$errorArchivo   = $_FILES[ 'imagen' ][ 'error' ];
	$pesoArchivo    = $_FILES[ 'imagen' ][ 'size' ];

	$info           = new SplFileInfo( $_FILES[ 'imagen' ][ 'name' ] );		// OBTENER EXTENSIÓN
	$nombreArchivo  = $id . '_' . $nombreArchivo;
	$rutaArchivo    = $carpeta . $nombreArchivo; 		// DIRECTORIO + ARCHIVO


	echo basename( $_FILES["imagen"]["name"] );

	// VALIDAR ARCHIVO CARGADO
	if( move_uploaded_file( $nombreTemporal, $rutaArchivo ) )
	{	
		
		// INICIALIZAR TRANSACCIÓN
		$conexion->query( "START TRANSACTION" );


		// FINALIZAR TRANSACCIÓN
		if( $error ){
			$conexion->query( "ROLLBACK" );
			$respuesta = 0;
		}
		else{
			$conexion->query( "COMMIT" );
			$respuesta = 1;
			$mensaje   = "Archivos registrados exitosamente.";
		}

	}
	else{
		$error = TRUE;
		$mensaje = "Error al mover el archivo, vuelva a intentarlo";
	}

	
	// SI HAY ERROR ELIMINAR TEMPORAL
	if( $error )
	{
		if ( file_exists( $rutaArchivo ) ) {
			unlink( $rutaArchivo );
		}

	}

}



if( $error )
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje, "error" => $mensaje );
else
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje );


echo json_encode( $response ); 

?>