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
 	public function getVentasFecha( $filtro, $deFecha, $aFecha )
 	{
 		$ventas = new stdClass();
 		$ventas->total         = 0;
 		$ventas->detalleVentas = [];
 		$ventas->encontrado    = FALSE;

 		$sql = "SELECT * 
					FROM vstFactura AS vF
						JOIN vstDetalleOrdenFactura AS vOf
							ON vF.idFactura = vOf.idFactura
					WHERE vF.fechaFactura AND vF.idEstadoFactura
					BETWEEN '{$deFecha}' AND '{$aFecha}';";

		if( $rs = $this->con->query( $sql ) )
		{
			if( $rs->num_rows )
			{
				$ventas->encontrado = TRUE;
	 			while( $row = $rs->fetch_object() )
	 			{
	 				$iFactura = -1;

	 				if( $filtro == 'factura' )
	 				{
	 					foreach ( $ventas->detalleVentas AS $ixFactura => $factura ) {
	 						if( $factura->idFactura == $row->idFactura )
	 						{
	 							$iFactura = $ixFactura;
	 							break;
	 						}
	 					}
	 				}


	 				if( $iFactura == -1 )
	 				{
	 					$iFactura = count( $ventas );
	 					$ventas->detalleVentas[ $iFactura ] = 
								(object)array(
									'idFactura'      => $row->idFactura,
									'total'          => $row->total,
									'detalleFactura' => array()
			 					);
	 				}


	 				if( $filtro == 'factura' )
	 					$ventas->detalleVentas[ $iFactura ]->detalleFactura[] = $row;

/*
	 				$row->idCombo             = (int)$row->idCombo?:NULL;
					$row->idMenu              = (int)$row->idMenu?:NULL;
					$row->idDetalleOrdenCombo = (int)$row->idDetalleOrdenCombo;
					$row->perteneceCombo      = (int)$row->perteneceCombo;
					
					// SI PERTENECE A COMBO
					if ( $row->perteneceCombo ) {
						$row->idMenu             = 0;
						$row->idDetalleOrdenMenu = 0;
					}
					else{
						$row->idCombo             = 0;
						$row->idDetalleOrdenCombo = 0;
					}

	 				$index = -1;

					// REVISA SI YA EXISTE MENU
					foreach ( $ventas->detalleVentas as $ix => $item ):
						if (
							$row->idTipoServicio == $item->idTipoServicio
							AND ( 
								( $row->perteneceCombo AND $row->perteneceCombo == $item->esCombo AND $row->idCombo == $item->idCombo )
								OR 
								( !$row->perteneceCombo AND $row->perteneceCombo == $item->esCombo AND $row->idMenu == $item->idMenu )
							)
						){
							$index = $ix;
							break;
						}
					endforeach;

					// SI NO EXISTE EN LISTADO => AGREGA UNA NUEVA ORDEN
					if ( $index == -1 )
					{
						$index = count( $ventas->detalleVentas );
						$ventas->detalleVentas[ $index ] = 				
							(object)
								array(
									'idCombo'             => $row->idCombo,
									'idMenu'              => $row->idMenu,
									'esCombo'             => $row->perteneceCombo,
									'cantidad'            => 0,
									'subTotal' 			  => 0,
									'descuento'           => (double)$row->descuento,
									'precioMenu'          => (double)$row->precioMenu,
									'precioReal'          => $row->precioReal,
									'descripcion'         => ( $row->perteneceCombo ? $row->combo : $row->menu ),
									'idTipoServicio'      => $row->idTipoServicio,
									'tipoServicio'        => $row->tipoServicio,
									'comentario'          => $row->comentario,
									'idDetalleOrdenMenu'  => $row->idDetalleOrdenMenu,
									'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
								);
					}

					$ventas->detalleVentas[ $index ]->cantidad += 1;
					$ventas->detalleVentas[ $index ]->subTotal = ( $ventas->detalleVentas[ $index ]->cantidad * $row->precioReal );
					// SUMA EL TOTAL DE LA ORDEN
					$ventas->total += (double)$row->precioMenu;
 		*/
	 			}
			}
			else{
				$ventas->mensaje   = 'No se encontraron resultados';
				$ventas->respuesta = $this->respuesta;
			}
 		}

 		return $ventas;
 	}

}
?>