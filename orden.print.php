<?php 
session_start();

if( !isset( $_SESSION[ 'idPerfil' ] ) && !isset( $_SESSION[ 'codigoUsuario' ] ) && !isset( $_SESSION[ 'usuario' ] ) ):
	echo "<h2>Sesión no válida, vuelva a ingresar.</h2>";
	exit();

endif;

include 'class/conexion.class.php';
include 'class/orden.class.php';

$tipo       = $_GET[ 'tipo' ];
$facturados = (int)$_GET[ 'facturados' ];

$orden = new Orden();
$lst = $orden->getOrdenesCliente( NULL, NULL );

// SI SE INCLUYE FACTURADOS
if ( $facturados > 0 )
{
	$lst2 = $orden->getOrdenesCliente( 6, $facturados );
	$lst  = array_merge( $lst, $lst2 );
}

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
			margin: 0;
			padding: 0;
		}
		.campos{ margin-top: 15px; font-size:12px; }
		td{
			border-top: solid 1px #ccc;
		}
	</style>
</head>
<body style="margin: 0;padding: 0;">
	<h4>Últimas Ordenes</h4>
	<?php 
	$x = 15;
	$y = 20;
	foreach ($lst as $ixO => $ordenCliente ) {
		echo "<div class ='campos'>{$ordenCliente->idOrdenCliente} | #{$ordenCliente->numeroTicket} | {$ordenCliente->fechaHora} | <u>{$ordenCliente->estadoOrden}</u></div>";

		$body = "";
		foreach ($ordenCliente->lstDetalle as $ixM => $menu) {
			$body .= "<tr>
						<td>" . $menu->cantidad . "</td>
						<td>*" . $menu->codigoMenu . " - " . $menu->menu . "</td>
						<td>" . $menu->idTipoServicio . "</td>
						<td>" . $menu->observacion . "</td>
					</tr>";

		}

		echo '<table border="0" cellpadding="0" style="text-align:left;font-size:11px;">
					<thead>
						<tr>
							<th width=\'25px\'>Cantidad</th>
							<th width=\'70px\'>Menú</th>
							<th width=\'18px\'>TS</th>
							<th width=\'40px\'>Obs.</th>
						</tr>
					</thead>
					<tbody>' . $body . '</tbody>
				</table>';
		$y += 20;
	}
	?>
	<script type="text/javascript">
		window.onload = function() { 
			window.print(); 
			window.close();
		}
	</script>
</body>
</html>