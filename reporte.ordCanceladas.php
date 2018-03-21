<?php 
session_start();

if( empty( $_SESSION ) ):
	echo "Su sesión ha vencido, inicie nuevamente";
	exit();

elseif( !isset( $_SESSION[ 'usuario' ] ) ):
	echo "Usuario inválido, vuelva a iniciar sesión";
	exit();

elseif( !isset( $_GET[ 'fechaInicio' ] ) ):
	echo "Ingrese la fecha de inicio";
	exit();

elseif( !isset( $_GET[ 'fechaFinal' ] ) ):
	echo "Ingrese la fecha Final";
	exit();

endif;


include 'class/conexion.class.php';
include 'class/reporte.class.php';
include 'class/sesion.class.php';
include 'class/funciones.php';

// DEFINIR SESION USUARIO
$sql = "CALL definirSesion( '{$sesion->getUsuario()}' );";
$conexion->query( $sql );

$deFecha   = $_GET[ 'fechaInicio' ];
$paraFecha = $_GET[ 'fechaFinal' ];

$reporte = new Reporte();
$resultado = $reporte->getOrdenesCanceladas( $deFecha, $paraFecha );
$conexion->close();
unset( $conexion );
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte-". DATE('d-m-Y') ."-ordenes-canceladas.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

?>
<style>
	.num {
		mso-number-format:General;
	}
	.text{
		mso-number-format:"\@";	/*force text*/
	}
	.titulo{
		background-color: #ff3d00;
    	color: #fff;
	}
</style>
<table border="1">
	<thead>
		<tr>
			<th>Cantidad</th>
			<th>Descripcion</th>
			<th>Tipo</th>
			<th>Responsable</th>
			<th>Cancelacion</th>
			<th>Comentarios / Observaciones</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($resultado->detalleOrdenesC AS $ordenCancelada ):
		$ordenCancelada->menu = utf8_decode( $ordenCancelada->menu );
		$ordenCancelada->tipo = utf8_decode( $ordenCancelada->tipo );
		echo "
		<tr>
			<td>{$ordenCancelada->cantidad}</td>
			<td>{$ordenCancelada->menu}</td>
			<td>{$ordenCancelada->tipo}</td>
			<td>{$ordenCancelada->usuarioResponsable}</td>
			<td>{$ordenCancelada->usuarioCancelacion}</td>
			<td>{$ordenCancelada->comentarioCancelacion}</td>
		</tr>";
	endforeach;
?>
	</tbody>
</table>