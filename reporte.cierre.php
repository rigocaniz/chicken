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
$resultado = $reporte->getCierreCaja( $deFecha, $paraFecha );
$conexion->close();
unset( $conexion );

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte-". DATE('d-m-Y') ."-cierre-caja.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<style>
	.num {
		mso-number-format:General;
	}
	.text{
		mso-number-format:"\@";
	}
	.titulo{
		background-color: #ff3d00;
    	color: #fff;
	}
</style>
<table border="1">
	<thead>
		<tr>
			<th class="titulo">ID</th>
			<th class="titulo">Fecha Apertura</th>
			<th class="titulo">Hora Apertura</th>
			<th class="titulo">Hora Cierre</th>
			<th class="titulo">Efectivo Inicial</th>
			<th class="titulo">Efectivo Final</th>
			<th class="titulo">Efectivo Sobrante</th>
			<th class="titulo">Efectivo Faltante</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($resultado->detalleCierre AS $cierre ):
		echo "
		<tr>
			<td>{$cierre['idCaja']}</td>
			<td>{$cierre['fechaApertura']}</td>
			<td>{$cierre['horaApertura']}</td>
			<td>{$cierre['horaCierre']}</td>
			<td class='num'>{$cierre['usuario']}</td>
			<td class='num'>{$cierre['nombreUsuario']}</td>
			<td class='num'>{$cierre['efectivoInicial']}</td>
			<td class='num'>{$cierre['efectivoFinal']}</td>
			<td class='num'>{$cierre['efectivoSobrante']}</td>
			<td class='num'>{$cierre['efectivoFaltante']}</td>
		</tr>";
	endforeach;
?>
	</tbody>
</table>