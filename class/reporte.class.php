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
 		
 		if( is_null( $this->con ) )  
 			$this->con  = $conexion;
 		
 		if( is_null( $this->sess ) )  
 			$this->sess = $sesion;
 	}

	
	// OBTENER CIERRE DE CAJA POR RANGO DE FECHAS
 	public function getCierreCaja( $deFecha, $paraFecha )
 	{
 		$cierreCaja = new stdClass();
		$cierreCaja->detalleCierre  = [];
		$cierreCaja->encontrado     = FALSE;
		$cierreCaja->totalSobrante  = 0;
		$cierreCaja->totalFaltante  = 0;
		$cierreCaja->totalEfectivoI = 0;
		$cierreCaja->totalEfectivoF = 0;

 		$sql = "CALL repCierreCaja( '{$deFecha}', '{$paraFecha}' );";

		if( $rs = $this->con->query( $sql ) )
		{
			$this->con->siguienteResultado();
			if( $rs->num_rows )
			{
				$cierreCaja->encontrado = TRUE;
	 			while( $row = $rs->fetch_assoc() )
	 			{
	 				$cierreCaja->totalSobrante 	+= $row[ 'efectivoSobrante' ];
	 				$cierreCaja->totalFaltante 	+= $row[ 'efectivoFaltante' ];
	 				$cierreCaja->totalEfectivoI += $row[ 'efectivoInicial' ];
	 				$cierreCaja->totalEfectivoF += $row[ 'efectivoFinal' ];
					$cierreCaja->detalleCierre[] = $row;
	 			}
			}
 		}

 		return $cierreCaja; 		
 	}


	// OBTENER DESCUENTOS POR RANGO DE FECHAS
 	public function getDescuentos( $deFecha, $paraFecha )
 	{
 		$descuentos = new stdClass();
		$descuentos->detalleDescuentos = [];
		$descuentos->totalDescuentos   = 0;
		$descuentos->encontrado        = FALSE;

 		$sql = "CALL repDescuento( '{$deFecha}', '{$paraFecha}' );";

		if( $rs = $this->con->query( $sql ) )
		{
			$this->con->siguienteResultado();
			if( $rs->num_rows )
			{
				$descuentos->encontrado = TRUE;
	 			while( $row = $rs->fetch_assoc() )
	 			{
	 				$descuentos->totalDescuentos += $row[ 'totalDescuento' ];
					$descuentos->detalleDescuentos[] = $row;
	 			}
			}
 		}

 		return $descuentos; 		
 	}
 	

 	// OBTENER ORDENES CANCELADAS POR RANGO DE FECHAS
 	public function getOrdenesCanceladas( $deFecha, $paraFecha )
 	{
 		$ventas = new stdClass();
		$ventas->detalleOrdenesC = [];
		$ventas->encontrado      = FALSE;

 		$sql = "(SELECT
						oc.idOrdenCliente,
					    oc.numeroTicket,
					    oc.usuarioResponsable,
					    oc.fechaRegistro AS 'fechaOrden',
					    COUNT( dom.idDetalleOrdenMenu )AS 'cantidad',
					    'Menú' AS 'tipo',
					    m.menu,
					    IFNULL( bom.usuario, boc.usuario )AS 'usuarioCancelacion',
					    IFNULL( bom.fechaRegistro, boc.fechaRegistro )AS 'fechaCancelacion',
					    IFNULL( bom.comentario, boc.comentario )AS 'comentarioCancelacion'
					FROM ordenCliente AS oc
						JOIN
							(detalleOrdenMenu AS dom
								JOIN  menu AS m ON dom.idMenu = m.idMenu AND dom.perteneceCombo = 0
								JOIN bitacoraOrdenMenu AS bom ON dom.idDetalleOrdenMenu = bom.idDetalleOrdenMenu AND bom.idEstadoDetalleOrden = 10 )
					        ON oc.idOrdenCliente = dom.idOrdenCliente
						
					    LEFT JOIN bitacoraOrdenCliente AS boc
							ON oc.idOrdenCliente = boc.idOrdenCliente AND boc.idEstadoOrden = 10
					WHERE ( oc.idEstadoOrden = 10 OR dom.idEstadoDetalleOrden = 10 )
						AND ( DATE( oc.fechaRegistro ) BETWEEN '{$deFecha}' AND '{$paraFecha}' )
					GROUP BY oc.idOrdenCliente, m.idMenu, comentarioCancelacion)
					UNION ALL
					(SELECT
						oc.idOrdenCliente,
					    oc.numeroTicket,
					    oc.usuarioResponsable,
					    oc.fechaRegistro AS 'fechaOrden',
					    COUNT( doc.idDetalleOrdenCombo )AS 'cantidad',
					    'Combo' AS 'tipo',
					    c.combo,
					    IFNULL( bocb.usuario, boc.usuario )AS 'usuarioCancelacion',
					    IFNULL( bocb.fechaRegistro, boc.fechaRegistro )AS 'fechaCancelacion',
					    IFNULL( bocb.comentario, boc.comentario )AS 'comentarioCancelacion'
					FROM ordenCliente AS oc
						JOIN
							(detalleOrdenCombo AS doc
								JOIN combo AS c ON doc.idCombo = c.idCombo
								JOIN bitacoraOrdenCombo AS bocb ON doc.idDetalleOrdenCombo = bocb.idDetalleOrdenCombo AND bocb.idEstadoDetalleOrden = 10 )
					        ON oc.idOrdenCliente = doc.idOrdenCliente
					        
						LEFT JOIN bitacoraOrdenCliente AS boc
							ON oc.idOrdenCliente = boc.idOrdenCliente AND boc.idEstadoOrden = 10
					WHERE ( boc.idEstadoOrden = 10 OR doc.idEstadoDetalleOrden = 10 )
						AND ( DATE( oc.fechaRegistro ) BETWEEN '{$deFecha}' AND '{$paraFecha}' )
					GROUP BY oc.idOrdenCliente, c.idCombo, comentarioCancelacion)
					ORDER BY idOrdenCliente;";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
				$ventas->encontrado = TRUE;
	 			while( $row = $rs->fetch_object() )
					$ventas->detalleOrdenesC[]   = $row;
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

 	// OBTENER VENTAS POR FECHA
 	public function getVentasGeneral( $deFecha, $paraFecha, $agruparVenta )
 	{
 		$lst = array();

 		$sql = "CALL repVentasGeneral( '{$deFecha}', '{$paraFecha}', '{$agruparVenta}' )";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
	 			while( $row = $rs->fetch_object() )
 					$lst[] = $row;
			}
 		}

 		return $lst;
 	}
}
?>