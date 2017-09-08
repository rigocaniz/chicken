<?php 

include 'class/conexion.class.php';
include 'class/facturar.class.php';

$factura = new Factura();

$idFactura        = 10;

$principalFactura = (object)$factura->lstFacturas( $idFactura )[ 0 ];
$detalleFactura   = $factura->detalleOrdenFactura( $idFactura );
/*
echo "<pre>";
print_r( $principalFactura );
echo "</pre>";
*/
?>
<style type="text/css">
	table {
		border-collapse: collapse
	}
	td {
		border: 0px solid black;
	}
	.
</style>

<table border="0" cellpadding="2">
	<tbody>
		<tr>
			<td><b>NIT:</b></td>
			<td><?= $principalFactura->idFactura; ?></td>
		</tr>
		<tr>
			<td><b>Nombre</b>:</td>
			<td><?= $principalFactura->nombre; ?></td>
		</tr>
		<tr>
			<td><b>Dirección</b>:</td>
			<td colspan="4"><?= $principalFactura->direccion; ?></td>
		</tr>
	</tbody>
</table>
<br>

<table border="0" cellpadding="4">
	<thead>
		<tr>
			<th>No.</th>
			<th>Descripción</th>
			<th>Cantidad</th>
			<th>P/U</th>
			<th>Subtotal</th>
		</tr>
	</thead>
	<tbody>
<?php
	if( count( $detalleFactura ) ):
		foreach ( $detalleFactura AS $ixDetalle => $detalle ) {
			echo "
				<tr>
					<td>{$ixDetalle}</td>
					<td>{$detalle->descripcion}</td>
					<td>{$detalle->cantidad}</td>
					<td>{$detalle->precioMenu}</td>
					<td>100</td>
				</tr>
			";
		}
		
	endif;
 ?>
 	<tr>
 		<td colspan="4">TOTAL</td>
 		<td><?= $principalFactura->total; ?></td>
 	</tr>
	</tbody>
</table>