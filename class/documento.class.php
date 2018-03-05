<?php 
class Documento
{
	var $con         = NULL;
	var $sess        = NULL;
	var $idDocumento = NULL;
	var $documento   = "";
	var $lstCampos   = array();

 	function __construct( $idDocumento )
 	{
 		GLOBAL $conexion, $sesion;

		$this->idDocumento = $idDocumento;
		$this->con         = $conexion;
		$this->sess        = $sesion;

 		$this->consultaDocumento();
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
				WHERE dd.idDocumento = {$this->idDocumento} ";
		$rs = $this->con->query( $sql );

 		while( $rs AND $row = $rs->fetch_object() ):
			$row->idTipoItem    = (int)$row->idTipoItem;
			$row->x             = (int)$row->x;
			$row->y             = (int)$row->y;
			$row->mostrarTitulo = (boolean)$row->mostrarTitulo;
			$row->relativo      = (boolean)$row->relativo;

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
				ORDER BY idColumnaLista ASC";
		$rs = $this->con->query( $sql );

 		while( $rs AND $row = $rs->fetch_object() ):
			$lst[] = $row;
		endwhile;

		return $lst;
	}

	// RENDERIZA TODOS LOS CAMPOS
	function render( $valores )
	{
		echo '<style>.campos{position:absolute}</style>';
		$ultimoY = 0;
		foreach ( $this->lstCampos as $campo ) {
			$label = "";
			$item  = $valores[ $campo->_index ];

			if ( $campo->mostrarTitulo )
				$label = "<b>" . $campo->label . "</b>";

			if ( $campo->idTipoItem == 1 )
				echo "<div class='campos' style='left:{$campo->x}px;top:{$campo->y}px;font-size:{$campo->fontSize}px;'>{$label} {$item}</div>";

			else
			{
				$body = "";
				$cols = "";

				// IMPRIME ENCABEZADO DE TABLA
				foreach ($campo->encabezado as $enc)
					$cols .= "<th width='" . $enc->width . "px'>" . $enc->campo . "</th>";

				// IMPRIME FILAS
				foreach ( $item as $valor ) {
					$valor->precioReal = number_format( $valor->precioReal, 2 );
					$valor->precioMenu = number_format( $valor->precioMenu, 2 );
					$valor->subTotal   = number_format( $valor->subTotal, 2 );
					$valor->descuento  = number_format( $valor->descuento, 2 );
					
					$valor = (array)$valor;
					$body .= "<tr>";
					foreach ($campo->encabezado as $ix => $enc):
						if ( $enc->_index == '$index' )
							$body .= "<td>" . ( $ix + 1 ) . "</td>";

						else
							$body .= "<td>" . $valor[ $enc->_index ] . "</td>";

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
}
?>