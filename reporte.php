<?php 
session_start();

if( empty( $_SESSION ) ):
	exit( "Su sesión ha vencido, inicie nuevamente" );

elseif( !isset( $_SESSION[ 'usuario' ] ) ):
	exit( "Usuario inválido, vuelva a iniciar sesión" );

elseif( !isset( $_GET[ 'fechaInicio' ] ) ):
	exit( "Ingrese la fecha de inicio" );

elseif( !isset( $_GET[ 'fechaFinal' ] ) ):
	exit( "Ingrese la fecha Final" );

elseif( !isset( $_GET[ 'agruparVenta' ] ) ):
	exit( "Parametro Agrupar Venta necesario" );

endif;

include 'class/conexion.class.php';
include 'class/reporte.class.php';
include 'class/sesion.class.php';
include 'class/funciones.php';

// DEFINIR SESION USUARIO
$sql = "CALL definirSesion( '{$sesion->getUsuario()}' );";
$conexion->query( $sql );

$deFecha      = $_GET[ 'fechaInicio' ];
$paraFecha    = $_GET[ 'fechaFinal' ];
$agruparVenta = $_GET[ 'agruparVenta' ];

$reporte = new Reporte();
$resultado = $reporte->getVentasGeneral( $deFecha, $paraFecha, $agruparVenta );
$conexion->close();
unset( $conexion );
/*header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte-". DATE('d-m-Y') ."-ventas.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
*/
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
			<?php if( $agruparVenta == 'dia' ){ ?>
			<th class="titulo">Día</th>

			<?php } if( $agruparVenta == 'mes' ){ ?>
			<th class="titulo">Mes</th>

			<?php } if( $agruparVenta == 'servicio' ){ ?>
			<th class="titulo">Servicio</th>

			<?php } if( in_array( $agruparVenta, array( "dia", "mes", "servicio" ) ) ){ ?>
			<th class="titulo"># Menús</th>
			<th class="titulo">Total Precio</th>
			<th class="titulo">Total Descuento</th>
			<th class="titulo">Total Efectivo</th>
			<th class="titulo">Total Tarjeta</th>

			<?php } if( $agruparVenta == "menu" ){ ?>
			<th class="titulo">Menú</th>
			<th class="titulo"># Menús</th>
			<th class="titulo">Total Precio</th>
			<th class="titulo">Total Descuento</th>

			<?php } if( $agruparVenta == 'factura' ){ ?>
			<th class="titulo"># Factura</th>
			<th class="titulo">Fecha Factura</th>
			<th class="titulo">Hora Factura</th>
			<th class="titulo">NIT</th>
			<th class="titulo">Nombre</th>
			<th class="titulo">Dirección</th>
			<th class="titulo">Teléfono</th>
			<th class="titulo">Efectivo</th>
			<th class="titulo">Tarjeta</th>
			<th class="titulo">Total Factura</th>
			<th class="titulo">Servicio</th>
			<th class="titulo"># Menús</th>
			<th class="titulo">Menú</th>
			<th class="titulo">Total Precio</th>
			<th class="titulo">Total Descuento</th>
			<th class="titulo">Comentario (desc)</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
<?php
	$dias  = array( "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", );
	$meses = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", );
	$idFactura   = 0;
	$infoFactura = null;
	foreach ($resultado AS $ix => $venta ):
		if( $agruparVenta != 'factura' )
			echo "<tr>";

		if( $agruparVenta == 'dia' )
			echo "<td>" . $dias[ (int)$venta->diaSemana - 1 ] . "</td>";

		if( $agruparVenta == 'mes' )
			echo "<td>" . $meses[ (int)$venta->mesFactura - 1 ] . "</td>";

		if( $agruparVenta == 'servicio' )
			echo "<td>" . $venta->servicio . "</td>";

		if( $agruparVenta == 'menu' )
			echo "<td>" . $venta->menuDesc . "</td>";
			
		if( in_array( $agruparVenta, array( "dia", "mes", "servicio" ) ) )
			echo "<td>{$venta->numeroMenus}</td>" .
				"<td class='num'>{$venta->totalPrecio}</td>" .
				"<td class='num'>{$venta->totalDescuento}</td>" .
				"<td class='num'>{$venta->totalEfectivo}</td>" .
				"<td class='num'>{$venta->totalTarjeta}</td>";

		if( $agruparVenta == "menu" )
			echo "<td>{$venta->numeroMenus}</td>" .
				"<td class='num'>{$venta->totalPrecio}</td>" .
				"<td class='num'>{$venta->totalDescuento}</td>";

		if( $agruparVenta == 'factura' )
		{
			if ( $idFactura !== $venta->idFactura AND $idFactura > 0 )
			{
				echo "<tr>
						<td rowspan='$infoFactura->count'>{$infoFactura->idFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->fechaFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->horaFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->nit}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->nombre}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->direccion}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->telefono}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->totalEfectivo}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->totalTarjeta}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->total}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->servicio}</td>
						$infoFactura->txtInit
					</tr>" . $infoFactura->txtDetalle;
			}

			$txtDetalle = "<td>{$venta->numeroMenus}</td>
							<td>{$venta->menuDesc}</td>
							<td>{$venta->totalPrecio}</td>
							<td>{$venta->totalDescuento}</td>
							<td>{$venta->comentario}</td>";

			if ( $idFactura !== $venta->idFactura ) {
				$idFactura = $venta->idFactura;
				$infoFactura = (object)array(
					'idFactura'     => $venta->idFactura,
					'fechaFactura'  => $venta->fechaFactura,
					'horaFactura'   => $venta->horaFactura,
					'nit'           => $venta->nit,
					'nombre'        => $venta->nombre,
					'direccion'     => $venta->direccion,
					'telefono'      => $venta->telefono,
					'totalEfectivo' => $venta->totalEfectivo,
					'totalTarjeta'  => $venta->totalTarjeta,
					'total'         => $venta->total,
					'servicio'      => $venta->servicio,
					'count'         => 1,
					'txtInit'       => $txtDetalle,
					'txtDetalle'    => "",
				);
			}
			else
			{
				$infoFactura->count++;
				$infoFactura->txtDetalle .= "<tr>" . $txtDetalle . "</tr>";
			}

			// SI ES EL ULTIMO ELEMENTO
			if ( ( $ix + 1 ) == count( $resultado ) ) {
				echo "<tr>
						<td rowspan='$infoFactura->count'>{$infoFactura->idFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->fechaFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->horaFactura}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->nit}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->nombre}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->direccion}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->telefono}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->totalEfectivo}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->totalTarjeta}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->total}</td>
						<td rowspan='$infoFactura->count'>{$infoFactura->servicio}</td>
						$infoFactura->txtInit
					</tr>" . $infoFactura->txtDetalle;
			}
		}

		if( $agruparVenta != 'factura' )
			echo "</tr>";
	endforeach;
?>
	</tbody>
</table>