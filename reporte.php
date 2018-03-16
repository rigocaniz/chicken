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
$resultado = $reporte->getVentasFecha( $deFecha, $paraFecha );
$conexion->close();
unset( $conexion );
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte-". DATE('d-m-Y') ."-ventas.xls");  //File name extension was wrong
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
			<th class="titulo">Codigo</th>
			<th class="titulo">Descripcion</th>
			<th class="titulo">subtotal Estimado</th>
			<th class="titulo">subtotal Venta</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($resultado->detalleVentas AS $ventas ):
		echo "
		<tr>
			<td>{$ventas->codigoMenu}</td>
			<td>{$ventas->descripcionOrden}</td>
			<td class='num'>{$ventas->totalEstimado}</td>
			<td class='num'>{$ventas->totalVenta}</td>
		</tr>";
	endforeach;
?>
	</tbody>
</table>