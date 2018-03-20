<?php 
/**
* REPORTES
*/
class Reporte
{
	
	private $respuesta = 'info';
 	private $mensaje   = '';
 	private $tiempo    = 6;
 	private $error     = FALSE;
 	private $sess      = NULL;
 	private $con       = NULL;
 	private $data      = array();

 	function __construct()
 	{
 		GLOBAL $conexion, $sesion;
 		$this->con  = $conexion;
 		$this->sess = $sesion;
 	}


 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}

 	// OBTENER VENTAS POR FECHA
 	public function getOrdenesCanceladas( $deFecha, $paraFecha )
 	{
 		$ventas = new stdClass();
		$ventas->totalCompras   = 0;
		$ventas->detalleOrdenesC = [];
		$ventas->encontrado     = FALSE;

 		$sql = "SELECT 
					fC.fechaFactura, iP.idProducto, iP.producto, iP.medida , iP.tipoProducto,
				    SUM( iP.cantidad ) AS cantidad,
				    SUM( iP.costo ) AS costoTotal
						FROM lstFacturaCompra fC
							JOIN lstIngresoProducto AS iP
								ON ip.idFacturaCompra = fC.idFacturaCompra
						WHERE fC.idEstadoFactura = 1 AND ( fC.fechaFactura BETWEEN '{$deFecha}' AND '{$paraFecha}' )
					GROUP BY fC.fechaFactura, iP.idProducto
					ORDER BY fC.fechaFactura ASC;";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
				$ventas->encontrado = TRUE;
	 			while( $row = $rs->fetch_object() ):
					$ventas->totalCompras      += $row->costoTotal;
					$ventas->detalleOrdenesC[]   = $row;
				endwhile;
			}
 		}

 		return $ventas;
 	}

 	// OBTENER VENTAS POR FECHA
 	public function getComprasFecha( $deFecha, $paraFecha )
 	{
 		$ventas = new stdClass();
		$ventas->totalCompras   = 0;
		$ventas->detalleCompras = [];
		$ventas->encontrado     = FALSE;

 		$sql = "SELECT 
					fC.fechaFactura, iP.idProducto, iP.producto, iP.medida , iP.tipoProducto,
				    SUM( iP.cantidad ) AS cantidad,
				    SUM( iP.costo ) AS costoTotal
						FROM lstFacturaCompra fC
							JOIN lstIngresoProducto AS iP
								ON ip.idFacturaCompra = fC.idFacturaCompra
						WHERE fC.idEstadoFactura = 1 AND ( fC.fechaFactura BETWEEN '{$deFecha}' AND '{$paraFecha}' )
					GROUP BY fC.fechaFactura, iP.idProducto
					ORDER BY fC.fechaFactura ASC;";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
				$ventas->encontrado = TRUE;
	 			while( $row = $rs->fetch_object() ):
					$ventas->totalCompras      += $row->costoTotal;
					$ventas->detalleCompras[]   = $row;
				endwhile;
			}
 		}

 		return $ventas;
 	}


 	// OBTENER VENTAS POR FECHA
 	public function getVentasFecha( $deFecha, $paraFecha )
 	{
 		$ventas = new stdClass();
		$ventas->totalVentas   = 0;
		$ventas->totalEstimado = 0;
		$ventas->detalleVentas = [];
		$ventas->encontrado    = FALSE;

 		$sql = "SELECT
					DATE_FORMAT( f.fechaFactura, '%d/%m/%Y' )AS 'fechaFactura',
				    IFNULL( m.codigo, IFNULL( c.codigo, 'N/A' ) )AS 'codigoMenu',
				    IFNULL( m.menu, IFNULL( c.combo, mp.descripcion ) )AS 'descripcionOrden',
				    COUNT( dof.idFactura )AS 'cantidad',
				    SUM( dof.precioMenu )AS 'totalEstimado',
				    SUM( ( dof.precioMenu - dof.descuento ) )AS 'totalVenta'
				FROM factura AS f
					JOIN detalleOrdenFactura AS dof
						ON f.idFactura= dof.idFactura
				        
					LEFT JOIN detalleOrdenMenu AS dom
						ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
					
				    LEFT JOIN menu AS m
						ON m.idMenu = dom.idMenu
					
				    LEFT JOIN detalleOrdenCombo AS doc
						ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo
					
				    LEFT JOIN combo AS c
						ON c.idCombo = doc.idCombo
				        
					LEFT JOIN menuPersonalizado AS mp
						ON mp.idMenuPersonalizado = dof.idMenuPersonalizado
				WHERE f.idEstadoFactura = 1 
					AND ( f.fechaFactura BETWEEN '{$deFecha}' AND '{$paraFecha}' )
				GROUP BY f.fechaFactura, codigoMenu, descripcionOrden
				ORDER BY f.fechaFactura ASC;";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
				$ventas->encontrado = TRUE;
	 			while( $row = $rs->fetch_object() ):
					$row->totalEstimado      = (double)$row->totalEstimado;
					$row->totalVenta         = (double)$row->totalVenta;
					$ventas->totalEstimado   += $row->totalEstimado;
					$ventas->totalVentas     += $row->totalVenta;
					$ventas->detalleVentas[] = $row;
				endwhile;
			}
 		}

 		return $ventas;
 	}

}
?>