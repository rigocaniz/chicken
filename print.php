<?php 

include 'class/conexion.class.php';
include 'class/facturar.class.php';

$factura = new Factura();

$idFactura        = 7;

$principalFactura = (object)$factura->lstFacturas( $idFactura )[ 0 ];
$detalleFactura   = $factura->detalleOrdenFactura( $idFactura );
echo "<pre>";
print_r( $principalFactura );
echo "</pre>";
/*
*/
?>


<table border="1">
	<tbody>
		<tr>
			<td>Nombre</td>
			<td><?= $principalFactura->nombre; ?></td>
			<td>NIT</td>
			<td><?= $principalFactura->idFactura; ?></td>
		</tr>
		<tr>
			<td>Dirección</td>
			<td colspan="4"><?= $principalFactura->direccion; ?></td>
		</tr>
	</tbody>
</table>
<br>

<table border="1">
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