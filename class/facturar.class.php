<?php 
/**
* FACTURACIÓN
*/
class Factura
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

 	// CONSULTA FACTURACION => INSERT / UPDATE
	function consultaFacturaCliente( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idFactura       = 'NULL';
		$idEstadoFactura = 'NULL';
		$idCliente       = 'NULL';
		$idCaja          = 'NULL';
		$nombre          = 'NULL';
		$direccion       = 'NULL';

		// SETEO VARIABLES

 		$data->idEstadoFactura = isset( $data->idEstadoFactura ) 	? (int)$data->idEstadoFactura 	: NULL;
 		$data->idEstadoFactura = (int)$data->idEstadoFactura > 0 	? (int)$data->idEstadoFactura 	: NULL;

 		$data->idCliente       = isset( $data->idCliente ) 			? (int)$data->idCliente 		: NULL;
 		$data->idCliente       = (int)$data->idCliente > 0 			? (int)$data->idCliente 		: NULL;

 		$data->idCaja          = isset( $data->idCaja ) 			? (int)$data->idCaja 			: NULL;
 		$data->idCaja          = (int)$data->idCaja > 0 			? (int)$data->idCaja 			: NULL;

 		$data->nombre          = isset( $data->nombre ) 			? (string)$data->nombre 		: NULL;
 		$data->nombre          = strlen( $data->nombre ) > 1 		? (string)$data->nombre 		: NULL;

 		$data->direccion       = isset( $data->direccion ) 			? (string)$data->direccion 		: NULL;
 		$data->direccion       = strlen( $data->direccion ) > 4		? (string)$data->direccion 		: NULL;

 		
 		if( $accion == 'update' ):
	 		$data->idFactura = isset( $data->idFactura ) 	? (int)$data->idFactura 	: NULL;
	 		$data->idFactura = (int)$data->idFactura > 0 	? (int)$data->idFactura 	: NULL;

 			$idFactura = $validar->validarEntero( $data->idFactura, NULL, TRUE, 'El ID de la FACTURA no es válida' );
 		endif;

		$idEstadoFactura = $validar->validarEntero( $data->idEstadoFactura, NULL, TRUE, 'El estado de la factura no es válido' );
		$idCliente       = $validar->validarEntero( $data->idCliente, NULL, TRUE, 'El Código del Cliente no es válido' );
		$idCaja          = $validar->validarEntero( $data->idCaja, NULL, TRUE, 'El Código de CAJA no es válido' );
		$nombre          = $this->con->real_escape_string( $validar->validarTexto( $data->nombre, NULL, TRUE, 3, 45, 'el nombre del combo' ) );
		$direccion       = $this->con->real_escape_string( $validar->validarTexto( $data->direccion, NULL, TRUE, 3, 45, ' dirección del cliente' ) );

		/*
 		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:


 		endif;
 		*/

 		var_dump( $data );
	}


	public function lstDetalleOrdenCliente( $idOrdenCliente, $todo = false )
 	{
		$lst   = array();
		$where = "";

		if ( !$todo )
			$where = " AND idEstadoDetalleOrden != 10 ";

 		$sql = "SELECT 
				    idDetalleOrdenMenu,
				    cantidad,
				    idMenu,
				    menu,
				    perteneceCombo,
				    precio,
				    combo,
				    imagenCombo,
				    precioCombo,
				    idEstadoDetalleOrden,
				    estadoDetalleOrden,
				    idTipoServicio,
				    tipoServicio,
				    idDetalleOrdenCombo,
				    idCombo,
				    tiempoAlerta
				FROM
				    vOrdenes
				WHERE
				    idOrdenCliente = {$idOrdenCliente} $where
				GROUP BY IF( perteneceCombo, idDetalleOrdenCombo, idDetalleOrdenMenu ), perteneceCombo;";
				//echo $sql;

		if( $rs = $this->con->query( $sql ) ) {
			while ( $row = $rs->fetch_object() ) {

				$row->perteneceCombo = (int)$row->perteneceCombo;
				// SI PERTENECE A COMBO
				if ( $row->perteneceCombo ) {
					$row->idMenu             = 0;
					$row->idDetalleOrdenMenu = 0;
					$precioMenu              = $row->precioCombo;
				}
				else{
					$row->idCombo             = 0;
					$row->idDetalleOrdenCombo = 0;
					$precioMenu               = $row->precio;
				}

				$index = -1;

				// REVISA SI YA EXISTE MENU
				foreach ( $lst as $ix => $item ):
					if ( 	$row->idCombo == $item->idCombo 
						AND $row->idMenu == $item->idMenu 
						AND $row->idTipoServicio == $item->idTipoServicio ) 
					{
						$index = $ix;
						break;
					}
				endforeach;

				// SI NO EXISTE EN LISTADO
				if ( $index == -1 ) {
					$index = count( $lst );
					// AGREGA UNA NUEVA ORDEN
					$lst[ $index ] = (object)array(
						'idCombo'        => $row->idCombo,
						'idMenu'         => $row->idMenu,
						'esCombo'        => $row->perteneceCombo,
						'cantidad'       => 0,
						'maximo'         => 0,
						'precio'         => $precioMenu,
						'cobrarTodo'     => TRUE,
						'descripcion'    => ( $row->perteneceCombo ? $row->combo : $row->menu ),
						'idTipoServicio' => $row->idTipoServicio,
						'tipoServicio'   => $row->tipoServicio,
						'lstDetalle'     => array()
					);
				}

				$lst[ $index ]->cantidad += $row->cantidad;
				$lst[ $index ]->maximo += $row->cantidad;

				// AGREGA DETALLE DE ORDEN
				$lst[ $index ]->lstDetalle[] = (object)array(
					'idDetalleOrdenMenu' => $row->idDetalleOrdenMenu,
					'idDetalleOrdenCombo' => $row->idDetalleOrdenCombo,
				);
			}
 		}

 		return $lst;
 	}

}
?>