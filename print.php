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

$factura   = new Factura();
$idFactura = (int)$_GET[ 'id' ];
$type      = isset( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : 'g';

$principalFactura = (object)$factura->lstFacturas( $idFactura )[ 0 ];
$detalleFactura   = $factura->detalleOrdenFactura( $idFactura );
//var_dump( $principalFactura );
?>
<style type="text/css">
	table {
		border-collapse: collapse
	}
	td {
		border: 0px solid black;
	}
	.text-right{
		text-align: right;
	}
	.text-center{
		text-align: center;
	}
</style>

<table border="0" cellpadding="2">
	<tbody>
		<tr>
			<td><b>NIT:</b></td>
			<td><?= $principalFactura->nit; ?></td>
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
		if( $type == 'g' ):
?>
		<tr>
			<td>1</td>
			<td>Consumo de alimentos</td>
			<td class='text-center'></td>
			<td class='text-right'></td>
			<td class='text-right'><?= number_format( (double)$principalFactura->total, 2 ); ?></td>
		</tr>
<?php
		else:
			foreach ( $detalleFactura AS $ixDetalle => $detalle ) {
				$noOrden  = (int)$ixDetalle + 1;
				$subtotal = number_format( (double)$detalle->precioMenu * $detalle->cantidad, 2 );
?>
				<tr>
					<td><?= $noOrden; ?></td>
					<td><?= $detalle->descripcion; ?></td>
					<td class='text-center'><?= $detalle->cantidad; ?></td>
					<td class='text-right'><?= $detalle->precioMenu; ?></td>
					<td class='text-right'><?= $subtotal; ?></td>
				</tr>
<?php
			}
		endif;
		
	endif;
 ?>
 	<tr>
 		<td colspan="4" class='text-right'><b>TOTAL</b></td>
 		<td class='text-right'><b><?= number_format( (double)$principalFactura->total, 2 ); ?></b></td>
 	</tr>
	</tbody>
</table>
<script type="text/javascript">
	window.onload = function() { 
		window.print(); 
		window.close();
	}
</script>