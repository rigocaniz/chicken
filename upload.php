<?php 
/**
* SUBIR ARCHIVOS [ IMAGENES ]
*/

define('MB', 1048576);

// VARIABLES GENERALES
$mensaje   = "";        // MENSAJE
$respuesta = 0;         // RESPUESTA
$error     = FALSE;     // ERROR
$imagen    = "";        // UBICACIÓN IMAGEN BD
$tipo      = "";

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

else if( $_FILES[ 'imagen' ][ 'size' ] > (1 * MB) )
{
    $error   = TRUE;
    $mensaje = "El archivo supera 1MB, peso máximo permitido";
}

else
{

    // CONEXION BD
    include 'class/conexion.class.php';
    $id      = (int)$_POST['id'];
    $tipo    = $_POST['tipo'];
 

    // SETEO DE VARIABLES
    $carpeta                = "img-menu/";
    $nombreArchivo          = $_FILES[ 'imagen' ][ 'name' ];
    $tipoArchivo            = $_FILES[ 'imagen' ][ 'type' ];
    $imagenTemporal         = $_FILES[ 'imagen' ][ 'tmp_name' ];
    $errorArchivo           = $_FILES[ 'imagen' ][ 'error' ];
    $pesoArchivo            = $_FILES[ 'imagen' ][ 'size' ];

    $info                   = new SplFileInfo( $nombreArchivo );
    $extension              = $info->getExtension();
    $nombreArchivo          = uniqid() . '_' . $id . "." . $extension;
    list( $width, $height ) = getimagesize( $imagenTemporal );
    $imagenNueva            = imagecreatetruecolor( 400, 400 );
    $thumbnail              = $carpeta.$nombreArchivo;

    switch( $tipoArchivo ){
        case 'image/png':
			imagesavealpha($imagenNueva, true);
			$bkTransparente = imagecolorallocatealpha($imagenNueva, 0, 0, 0, 127);
			imagefill( $imagenNueva, 0, 0, $bkTransparente);
            $source = imagecreatefrompng( $imagenTemporal );
            break;

        case 'image/jpeg':
            $source = imagecreatefromjpeg( $imagenTemporal );
            break;
     }

    imagecopyresized( $imagenNueva, $source, 0, 0, 0, 0, 400, 400, $width, $height );

    switch( $tipoArchivo ){
        case 'image/png':
            imagepng( $imagenNueva, $thumbnail );
            break;

        case 'image/jpeg':
            imagejpeg( $imagenNueva, $thumbnail, 100 );
            break;
    }

    
    // INICIALIZAR TRANSACCIÓN
    $conexion->query( "START TRANSACTION" );

    if( $tipo == 'menu' )       // MENU
        $sql = "SELECT imagen FROM menu WHERE idMenu = {$id};";
    
    elseif( $tipo == 'combo' )  // COMBO
        $sql = "SELECT imagen FROM combo WHERE idCombo = {$id};";
    
    elseif( $tipo == 'supercombo' ) // SUPERCOMBO
        $sql = "SELECT imagen FROM superCombo WHERE idSuperCombo = {$id};";
    
    if( $rs = $conexion->query( $sql ) ){
        if( $row = $rs->fetch_object() )
            $imagen = $row->imagen;

        if( $tipo == 'menu' )       // MENU
            $sql = "UPDATE menu SET imagen = '{$thumbnail}' WHERE idMenu = {$id};";
    
        elseif( $tipo == 'combo' )  // COMBO
            $sql = "UPDATE combo SET imagen = '{$thumbnail}' WHERE idCombo = {$id};";
        
        elseif( $tipo == 'supercombo' ) // SUPERCOMBO
            $sql = "UPDATE superCombo SET imagen = '{$thumbnail}' WHERE idSuperCombo = {$id};";

        if( !$rs = $conexion->query( $sql ) ){
            $error   = TRUE;
            $mensaje = "ERROR al actualizar la imagen, vuelva a intentarlo";
        }

    }
    else{
        $this->respuesta = 'danger';
        $this->mensaje   = 'Error al ejecutar al actualizar la imagen';
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
    

    // SI HAY ERROR ELIMINAR ARCHIVO
    if( $error )
    {
        if ( file_exists( $thumbnail ) )
            unlink( $thumbnail );

    }

}


if( $error )
    $response = array( "accion" => $tipo, "respuesta" => $respuesta, "mensaje" => $mensaje, "error" => $mensaje );
else
    $response = array( "accion" => $tipo, "respuesta" => $respuesta, "mensaje" => $mensaje );


echo json_encode( $response ); 

?>