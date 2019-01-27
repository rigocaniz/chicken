<?php 
class Documento
{
	var $con         = NULL;
	var $sess        = NULL;
	var $idDocumento = NULL;
	var $documento   = "";
	var $lstCampos   = array();

 	function __construct( $idDocumento = NULL )
 	{
 		GLOBAL $conexion, $sesion;

		$this->con  = $conexion;
		$this->sess = $sesion;

 		if ( !is_null( $idDocumento ) )
 		{
			$this->idDocumento = $idDocumento;
	 		$this->consultaDocumento();
 		}
 	}

 	// CONSULTA DOCUMENTO
	private function consultaDocumento()
	{
		$sql = "SELECT idDocumento, documento FROM documento WHERE idDocumento = {$this->idDocumento} ";
		$rs = $this->con->query( $sql );

 		if( $rs AND $row = $rs->fetch_object() )
 		{
			$this->idDocumento = $row->idDocumento;
			$this->documento   = $row->documento;
			$this->consultaDetalleDocumento();
 		}
	}

	// CONSULTA DETALLE DOCUMENTO
	private function consultaDetalleDocumento()
	{
		$sql = "SELECT
					dd.idDocumentoDetalle,
					ti.idTipoItem,
					ti.tipoItem,
					dd.label,
					dd._index,
					dd.x,
					dd.y,
					dd.mostrarTitulo,
					dd.relativo,
					dd.fontSize
				FROM documentoDetalle AS dd
					JOIN tipoItem AS ti
						ON dd.idTipoItem = ti.idTipoItem
				WHERE dd.idDocumento = {$this->idDocumento}
				ORDER BY dd.orden ASC";
		$rs = $this->con->query( $sql );

 		while( $rs AND $row = $rs->fetch_object() ):
			$row->idTipoItem    = (int)$row->idTipoItem;
			$row->x             = (int)$row->x;
			$row->y             = (int)$row->y;
			$row->fontSize      = (int)$row->fontSize;
			$row->mostrarTitulo = (boolean)$row->mostrarTitulo;
			$row->relativo      = (boolean)$row->relativo;
			$row->old           = (object)array(
				'x'             => $row->x,
				'y'             => $row->y,
				'fontSize'      => $row->fontSize,
				'mostrarTitulo' => $row->mostrarTitulo,
			);

			// SI ES UNA LISTA
			if ( $row->idTipoItem == 2 )
				$row->encabezado = $this->lstColumnas( $row->idDocumentoDetalle );

			$this->lstCampos[] = $row;
		endwhile;
	}

	// CONSULTA DETALLE DOCUMENTO
	private function lstColumnas( $idDocumentoDetalle )
	{
		$lst = array();
		$sql = "SELECT idColumnaLista, campo, _index, width
				FROM columnaLista WHERE idDocumentoDetalle = {$idDocumentoDetalle} 
				ORDER BY orden ASC";
		$rs = $this->con->query( $sql );

 		while( $rs AND $row = $rs->fetch_object() ):
 			$row->width = (int)$row->width;
 			$row->old = (object)array(
				'width' => $row->width,
			);
			$lst[] = $row;
		endwhile;

		return $lst;
	}

	public function getDocumento()
	{
		return $this->lstCampos;
	}

	// RENDERIZA TODOS LOS CAMPOS
	function render( $valores )
	{
		$numeroLetras = new NumeroALetras();
		
		$valores[ 'fecha' ]      = date("d") . "&nbsp;&nbsp;&nbsp;" . date("m") . "&nbsp;&nbsp;&nbsp;" . date("Y");
		$valores[ 'totalLetra' ] = $numeroLetras->convertir( $valores[ 'total' ], '', '', TRUE );
		$valores[ 'total' ]      = number_format( $valores[ 'total' ], 2 );
		//$valores[ 'nombre' ]     = ucwords( strtolower( $valores[ 'nombre' ] ) );
		//$valores[ 'direccion' ]  = ucwords( strtolower( $valores[ 'direccion' ] ) );
		$valores[ 'nombre' ]     = strtoupper( $valores[ 'nombre' ] );
		$valores[ 'direccion' ]  = strtoupper( $valores[ 'direccion' ] );

		echo '<style>.campos{position:absolute}</style>';
		$ultimoY = 0;
		foreach ( $this->lstCampos as $campo )
		{
			$label = "";
			$item  = $valores[ $campo->_index ];

			if ( $campo->mostrarTitulo )
				$label = "<b>" . $campo->label . "</b>";

			if ( $campo->idTipoItem == 1 )
				echo "<div class='campos' style='left:{$campo->x}px;top:{$campo->y}px;font-size:{$campo->fontSize}px;'>{$label} {$item}</div>";

			else if ( $campo->idTipoItem == 2 )
			{
				$body        = "";
				$cols        = "";
				$resumen     = "";
				$total       = 0;
				$totalLetras = 0;

				// IMPRIME ENCABEZADO DE TABLA
				foreach ($campo->encabezado as $enc)
				{
					$thTitulo = "";
					if ( $campo->mostrarTitulo )
						$thTitulo = $enc->campo;

					$cols .= "<th width='" . $enc->width . "px' style='width:" . $enc->width . "px'>" . $thTitulo . "</th>";

					if ( $enc->_index == 'descripcion' )
						$resumen .= "<td>" . $valores[ 'descripcion' ] . "</td>";
					
					else if ( $enc->_index == 'subTotal' ){
						$resumen .= "<td>" . $valores[ 'total' ] . "</td>";
						$total   += str_replace(',', '', $valores[ 'total' ] ); 
					}
					else
						$resumen .= "<td>--</td>";
				}

				if ( !$valores[ 'siDetalle' ] )
					$body .= "<tr>$resumen</tr>";

				// IMPRIME FILAS
				foreach ( $item as $valor ) {
					// SI NO ES DETALLE ABORTA RECORRIDO DE FILAS
					if ( !$valores[ 'siDetalle' ] ) break;

					if ( isset( $valor->precioReal ) )
						$valor->precioReal = number_format( $valor->precioReal, 2 );

					if ( isset( $valor->precioMenu ) )
						$valor->precioMenu = number_format( $valor->precioMenu, 2 );

					if ( isset( $valor->descuento ) )
						$valor->descuento  = number_format( $valor->descuento, 2 );

					$valor->subTotal = number_format( $valor->subTotal, 2 );
					
					$valor = (array)$valor;
					$body .= "<tr>";
					foreach ($campo->encabezado as $ix => $enc):
						
						$alineado = "";
						if( $enc->_index == 'precioReal' OR $enc->_index == 'subTotal' )
							$alineado = "style='text-align: right;'";

						if ( $enc->_index == '$index' )
							$body .= "<td>" . ( $ix + 1 ) . "</td>";

						else
							$body .= "<td {$alineado}>" . $valor[ $enc->_index ] . "</td>";

					endforeach;

					$body .= "</tr>";
				}

				$table = '<table border="0" cellpadding="0" style="text-align:left;font-size:' . $campo->fontSize . 'px;">
							<thead>
								<tr>' . $cols . '</tr>
							</thead>
							<tbody>' . $body . '</tbody>
						</table>';

				echo "<div class='campos' style='left:{$campo->x}px;top:{$campo->y}px;'>$table</div>";
			}

			$ultimoY = $campo->y;
		}
	}

	public function guardarDocumento( $lstCampos, $lstColumnas )
	{
		$messageError = NULL;

		foreach ($lstCampos as $ixC => $campo) {

			$set = "";
			
			foreach ($campo->lstCambio as $ix => $cambio)
				$set .= $cambio->cmp . " = " . (int)$cambio->val . ",";

			if ( strlen( $set ) )
			{
				$set = rtrim( $set, "," );
				$sql = "UPDATE documentoDetalle SET $set WHERE idDocumentoDetalle = " . $campo->idDocumentoDetalle;

				if ( !$this->con->query( $sql ) )
				{
					$messageError = "Error al guardar campos: " . $this->con->error;
					break;
				}
			}
		}

		if ( is_null( $messageError ) )
		{
			foreach ($lstColumnas as $ixC => $columna) {
				$sql = "UPDATE columnaLista SET width = {$columna->val} WHERE idColumnaLista = {$columna->idColumnaLista}";

				if ( !$this->con->query( $sql ) )
				{
					$messageError = "Error al guardar columnas: " . $this->con->error;
					break;
				}
			}
		}

		if ( is_null( $messageError ) )
		{
			$response = array(
				'respuesta' => "success",
				'mensaje'   => "Guardado correctamente",
			);
		}
		else
		{
			$response = array(
				'respuesta' => "danger",
				'mensaje'   => $messageError,
			);
		}

		return $response;
	}
}
?>