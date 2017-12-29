<?php 
session_start();

if( !isset( $_SESSION[ 'idPerfil' ] ) && !isset( $_SESSION[ 'codigoUsuario' ] ) && !isset( $_SESSION[ 'usuario' ] ) ):
	echo "<h2>Sesión no válida, vuelva a ingresar.</h2>";
	exit();

elseif( !isset( $_GET[ 'id' ] ) ):
	echo "<h2>Factura no válida.</h2>";
	exit();

elseif( !($_GET[ 'id' ] > 0) ):
	echo "<h2>Identificador de Factura no válido.</h2>";
	exit();

endif;

include 'class/conexion.class.php';
include 'class/facturar.class.php';
include 'class/documento.class.php';

$factura   = new Factura();
$idFactura = (int)$_GET[ 'id' ];
$type      = isset( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : 'g';

// OBTIENE INFORMACION DE FACTURA
$datos = (array)$factura->lstFacturas( $idFactura )[ 0 ];

// OBTIENE DETALLE DE FACTURA
$datos[ 'lstDetalle' ] = $factura->detalleOrdenFactura( $idFactura );

// RENDERIZA EL DOCUMENTO
$documento = new Documento( 1 );

// CIERRA LA CONEXION
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es-GT">
<head>
	<meta charset="UTF-8">
	<title>Factura Print</title>
	<style>
		*{
			font-family: Arial, Tahoma;
		}
	</style>
</head>
<body style="margin: 0;padding: 0;">
	<?php $documento->render( $datos ); ?>
	<script type="text/javascript">
		window.onload = function() { 
			window.print(); 
			window.close();
		}
	</script>
</body>
</html>