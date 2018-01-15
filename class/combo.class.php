<?php
/**
* COMOBO
*/
class Combo
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


	// LIBERAR SIGUIENTE RESULTADO
 	private function siguienteResultado()
 	{
 		if( $this->con->more_results() )
 			$this->con->next_result();
 	}


 	// OBTENER TOTAL COMBOS
	function getTotalPagMenu( $limite = 25, $busqueda )
	{
		
		$where = '';
		if( strlen( $busqueda ) ){
			$busqueda = str_replace(" ", "%", $busqueda );
			$where .= " AND  menu LIKE '%{$busqueda}%' ";
		}

		$total = 0;
		$sql = "SELECT COUNT(*) AS total FROM lstCombo;";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$total = (int)$row->total;
		}

		$totalPaginas = ceil( $total / $limite );
		
		return $totalPaginas;
	}


	// OBTENER LISTA DE COMBOS
	function getListaCombos( $filter )
	{
		$pagina = $filter->pagina > 0 		? (int)$filter->pagina 	: 1;
		$limite = $filter->limite > 0 		? (int)$filter->limite 	: 25;
		$orden  = strlen( $filter->orden ) 	? $filter->orden 		: 'ASC';

		$where = '';
		if( strlen( $filter->busqueda ) ){
			$busqueda = str_replace(" ", "%", $filter->busqueda );
			$where .= " AND combo LIKE '%{$busqueda}%' ";
		}


		$combos = (object)array(
				'totalPaginas' => 0,
				'lstCombos'    => array()
			);

		$inicio = ($pagina - 1) * $limite;

		$sql = "SELECT 
					    idCombo,
					    combo,
					    imagen,
					    descripcion,
					    idEstadoMenu,
					    estadoMenu,
					    codigoCombo
					FROM
					    lstCombo
					WHERE idEstadoMenu <> 3 {$where}
					ORDER BY idCombo $orden LIMIT $inicio, $limite;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				
				if( $row->imagen != '' )
					$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';

				$combo = array(
						'idCombo'      => (int)$row->idCombo,
						'combo'        => $row->combo,
						'imagen'       => $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen,
						'descripcion'  => $row->descripcion,
						'idEstadoMenu' => (int)$row->idEstadoMenu,
						'estadoMenu'   => $row->estadoMenu,
						'codigo'       => $row->codigoCombo
				);

				$combos->lstCombos[] = $combo;
			}
		}

		$combos->totalPaginas = $this->getTotalPagMenu( $limite, $filter->busqueda );

		return $combos;
	}


 	// CONSULTAR DATOS COMBO
	function cargarCombo( $idCombo )
	{
		$idCombo = (int)$idCombo;
		$combo = array();

		$sql = "SELECT 
				    idCombo,
				    combo,
				    imagen,
				    descripcion,
				    idEstadoMenu
				FROM
				    lstCombo
				WHERE
				    idCombo = {$idCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$combo = $row;
		}

		return $combo;
	}


	// CONSULTAR DATOS COMBO DETALLE
	function cargarComboDetalle( $idCombo, $idMenu )
	{
		$idCombo = (int)$idCombo;
		$idMenu  = (int)$idMenu;
		$comboDetalle = array();

		$sql = "SELECT 
				    idCombo,
				    idMenu,
				    cantidad
				FROM
				    lstComboDetalle
				WHERE
				    idCombo = {$idCombo} AND idMenu = {$idMenu};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$comboDetalle = $row;
		}

		return $comboDetalle;
	}


	// CONSULTAR DATOS COMBO PRECIO
	function cargarComboPrecio( $idCombo, $idTipoServicio = NULL )
	{
		$idCombo        = (int)$idCombo;
		$idTipoServicio = (int)$idTipoServicio;
		$comboPrecio = array();

		$where = "";
		if ( $idTipoServicio > 0 )
			$where = " AND idTipoServicio = {$idTipoServicio} ";

		$sql = "SELECT 
				    idCombo,
				    precio,
				    idTipoServicio
				FROM
				    lstComboPrecio
				WHERE
				   idCombo = {$idCombo} " . $where;
		
		if( $rs = $this->con->query( $sql ) ){
			if ( $idTipoServicio > 0 ) {
				if( $row = $rs->fetch_object() )
					$comboPrecio = $row;
			}
			else {
				while( $row = $rs->fetch_object() ) {
					$row->precio   = (double)$row->precio;
					$comboPrecio[] = $row;
				}
			}
		}

		return $comboPrecio;
	}


	// CONSULTAR DATOS SUPER COMBO
	function cargarSuperCombo( $idSuperCombo )
	{
		$idSuperCombo = (int)$idSuperCombo;
		$superCombo = array();

		$sql = "SELECT 
				    idSuperCombo,
				    superCombo,
				    imagen,
				    descripcion,
				    idEstadoMenu
				FROM
				    lstSuperCombo
				WHERE
				   idSuperCombo = {$idSuperCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superCombo = $row;
		}

		return $superCombo;
	}


	// CONSULTAR DATOS SUPER COMBO DETALLE
	function cargarSuperComboDetalle( $idCombo, $idSuperCombo )
	{
		$idCombo      = (int)$idCombo;
		$idSuperCombo = (int)$idSuperCombo;
		$superComboDetalle = array();

		$sql = "SELECT 
				    idCombo,
				    idSuperCombo,
				    cantidad
				FROM
				    lstSuperComboDetalle
				WHERE
				   idCombo = {$idCombo} AND idSuperCombo = {$idSuperCombo};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superComboDetalle = $row;
		}

		return $superComboDetalle;
	}


	// CONSULTAR DATOS SUPER COMBO PRECIO
	function cargarSuperComboPrecio( $idSuperCombo, $idTipoServicio )
	{
		$idSuperCombo   = (int)$idSuperCombo;
		$idTipoServicio = (int)$idTipoServicio;
		$superComboPrecio = array();

		$sql = "SELECT 
				    idSuperCombo,
				    precio,
				    idTipoServicio
				FROM
				    lstSuperComboPrecio
				WHERE
				   idSuperCombo = {$idSuperCombo} AND idTipoServicio = {$idTipoServicio};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$superComboPrecio = $row;
		}

		return $superComboPrecio;
	}


 	// CONSULTAR LISTA DE COMBOS
 	function lstCombo( $idEstadoMenu = NULL, $idCombo = NULL )
 	{
 		$lstCombo = array();

 		$where = "";
 		if ( !IS_NULL( $idEstadoMenu ) )
 			$where = " WHERE idEstadoMenu = $idEstadoMenu ";

 		if ( !IS_NULL( $idCombo ) AND $idCombo > 0 )
 			$where = " WHERE idCombo = $idCombo ";

 		$sql = "SELECT idCombo, combo, imagen, descripcion, idEstadoMenu, estadoMenu, codigoCombo FROM lstCombo " . $where;
 		
 		if( $rs = $this->con->query( $sql ) ){

 			// SI ES UN UNICO COMBO
 			if ( !IS_NULL( $idCombo ) AND $idCombo > 0 ) {
	 			if( $row = $rs->fetch_object() ) {
	 				if( $row->imagen != '' )
						$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';

					$row->imagen = $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen;
					$lstCombo    = $row;
 				}
 			}
 			
 			// SI ES UN LISTADO COMBO
 			else{
	 			while( $row = $rs->fetch_object() ){
	 				if( $row->imagen != '' )
						$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';

	 				$row->imagen = $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen;
	 				$lstCombo[] = $row;
	 			}
 			}
 		}

 		return $lstCombo;
 	}


 	// CONSULTAR LISTA DE COMBOS DETALLE
 	function lstComboDetalle( $idCombo )
 	{
 		$lstComboDetalle = array();
 		$idCombo = (int)$idCombo;

 		$sql = "SELECT 
				    idCombo,
				    cantidad,
				    idMenu,
				    menu,
				    imagen,
				    descripcion,
				    idEstadoMenu,
				    estadoMenu
				FROM
				    lstComboDetalle
				WHERE idCombo = {$idCombo};";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$row->cantidad     = (double)$row->cantidad;
 				$lstComboDetalle[] = $row;
 			}
 		}

 		return $lstComboDetalle;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstComboPrecio( $idCombo )
 	{

 		$idCombo     = (int)$idCombo;
		$lstComboPrecios = array();

		$sql = "SELECT 
				    idCombo,
				    precio,
				    idTipoServicio,
				    tipoServicio
				FROM
				    lstComboPrecio
				WHERE
				    idCombo = {$idCombo};";

		if( $rs = $this->con->query( $sql ) ){
			while( $rs AND $row = $rs->fetch_object() )
				$lstComboPrecios[] = array(
					'idCombo'        => (int)$row->idCombo,
					'idTipoServicio' => (int)$row->idTipoServicio,
					'tipoServicio'   => $row->tipoServicio,
					'precio'         => (double)$row->precio
				);
		}

		return $lstComboPrecios;
 	}



 	// CONSULTAR LISTA DE COMBOS
 	function lstSuperCombo()
 	{
 		$lstSuperCombo = array();

 		$sql = "SELECT idSuperCombo, superCombo, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstSuperCombo;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperCombo[] = $row;
 			}
 		}

 		return $lstSuperCombo;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstSuperComboDetalle()
 	{
 		$lstSuperComboDetalle = array();

 		$sql = "SELECT idSuperCombo, cantidad, idCombo, combo, imagen, descripcion, idEstadoMenu, estadoMenu FROM lstSuperComboDetalle;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperComboDetalle[] = $row;
 			}
 		}

 		return $lstSuperComboDetalle;
 	}


 	// CONSULTAR LISTA DE COMBOS PRECIO
 	function lstSuperComboPrecio()
 	{
 		$lstSuperComboPrecio = array();

 		$sql = "SELECT idSuperCombo, precio, idTipoServicio, tipoServicio FROM lstSuperComboPrecio;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstSuperComboPrecio[] = $row;
 			}
 		}

 		return $lstSuperComboPrecio;
 	}

	
	// GUARDAR // ACTUALIZAR => CONSULTA COMBO
	function consultaCombo( $accion, $data )
 	{
 		if( count( $data->lstPrecios ) ){
 		
	 		$validar = new Validar();

			// INICIALIZACIÓN VAR
			$idCombo      = 'NULL';
			$combo        = NULL;
			$imagen       = "NULL";
			$descripcion  = NULL;
			$idEstadoMenu = NULL;

			// SETEO VARIABLES GENERALES
	 		$data->combo        = isset( $data->combo ) 		? (string)$data->combo 			: NULL;
	 		$data->codigo       = isset( $data->codigo ) 		? (string)$data->codigo 		: NULL;
	 		$data->descripcion  = isset( $data->descripcion ) 	? (string)$data->descripcion 	: NULL;
	 		$data->idEstadoMenu = isset( $data->idEstadoMenu ) 	? (int)$data->idEstadoMenu 		: NULL;
	 
	 		// VALIDACIONES
	 		if( $accion == 'update' ):
	 			$data->idCombo = isset( $data->idCombo ) ? (int)$data->idCombo : NULL;
	 			$idCombo       = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del COMBO no es válido' );
	 		endif;

			$combo        = $this->con->real_escape_string( $validar->validarTexto( $data->combo, NULL, TRUE, 3, 45, 'el nombre del combo' ) );
			$codigo       = $validar->validarEntero( $data->codigo, NULL, TRUE, 'El código del combo no es válido' );
			$descripcion  = $this->con->real_escape_string( $validar->validarTexto( $data->descripcion, NULL, TRUE, 10, 1500, 'la descripcion' ) );
			$idEstadoMenu = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado combo no es válido' );


			// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:

	 			// INICIALIZAR TRANSACCION
	 			$this->con->query( 'START TRANSACTION' );

		 		$sql = "CALL consultaCombo( '{$accion}', {$idCombo}, '{$combo}', {$imagen}, '{$descripcion}', {$idEstadoMenu}, {$codigo} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();
		 			
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;

	 				if( ( $accion == 'insert' OR $accion == 'update' ) AND $this->respuesta == 'success' ){
	 					if( $accion == 'insert' )
	 						$idCombo = $this->data = (int)$row->id;
	 					// INSERTAR PRECIOS
	 					$this->consultaComboPrecio( 'insert', $idCombo, $data->lstPrecios );
	 				}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}


		 		// FINALIZAR TRANSACCION
		 		if( $this->respuesta == 'danger' )
		 			$this->con->query( 'ROLLBACK' );
		 		else
		 			$this->con->query( 'COMMIT' );
		 		
	 		endif;
 		}
 		else{
 			$this->respuesta = 'info';
		 	$this->mensaje   = 'No hay ingresado los precios del Menu';
 		}

 		return $this->getRespuesta();
 	}


 	// ACTUALIZAR LISTA DETALLE COMBO
	function actualizarLstDetalleCombo( $accion, $lstDetalleCombo )
 	{
 		if( count( $lstDetalleCombo ) ) {

 			foreach ( $lstDetalleCombo AS $data ) {

				// ASIGNACIÓN DE VARIABLES
				$idCombo     = (int)$data->idCombo;
				$idMenu      = (int)$data->idMenu;
				$cantidad    = (double)$data->cantidad;

		 		$sql = "CALL consultaComboDetalle( '{$accion}', {$idCombo}, {$idMenu}, {$cantidad} );";
			 		
		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();
	 				
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
		 			if( $this->respuesta == 'danger' )
		 				break;
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		 		}
 			}
 			
 		}
 		else{
 			$this->respuesta = 'warning';
		 	$this->mensaje   = 'No hay menus ingresados en el combo';
 		}

 		return $this->getRespuesta();
 	}


	// GUARDAR // ACTUALIZAR => COMBO PRECIO
	function consultaComboPrecio( $accion, $idCombo, $lstPrecios )
 	{
 		
 		foreach ( $lstPrecios AS $ixPrecio => $precio ) {

	 		$validar = new Validar();

			// SETEO DE VARIABLES
	 		$precio->idTipoServicio = (int)$precio->idTipoServicio > 0 ? (int)$precio->idTipoServicio : NULL;

	 		// VALIDACIÓN
			$idTipoServicio = $validar->validarEntero( $precio->idTipoServicio, NULL, TRUE, "ID del servicio {$precio->tipoServicio} inválido" );
			$precio         = (double)$precio->precio;

			// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:
		 		$sql = "CALL consultaComboPrecio( '{$accion}', {$idCombo}, {$idTipoServicio}, {$precio} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();
		 			
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;

	 				if( $this->respuesta == 'danger' )
	 					break;
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}
		 		
	 		endif;		
 		}
 	}


 	// GUARDAR // ACTUALIZAR // ELIMINAR => COMBO DETALLE
	function consultaComboDetalle( $accion, $data )
	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idCombo  = NULL;
 		$idMenu   = NULL;

		// SETEO VARIABLES GENERALES
		$data->idCombo  = (int)$data->idCombo > 0 	? (int)$data->idCombo 		: NULL;
		$data->idMenu   = (int)$data->idMenu > 0 	? (int)$data->idMenu 		: NULL;

		$idCombo  = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del Combo no es válido, verifique.' );
		$idMenu   = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menu de servicio no es válido, verifique.' );
		
 		$cantidad = "NULL";
		// ELIMINAR
		if( $accion <> 'delete' ):
			$data->cantidad = (int)$data->cantidad > 0 ? (int)$data->cantidad : NULL;
			$cantidad       = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaComboDetalle( '{$accion}', {$idCombo}, {$idMenu}, {$cantidad} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
 				
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


	// GUARDAR // ACTUALIZAR => CONSULTA SUPER COMBO
	function consultaSuperCombo( $accion, $data )
 	{
 		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idSuperCombo = 'NULL';
		$superCombo   = NULL;
		$imagen       = "NULL";
		$descripcion  = NULL;
		$idEstadoMenu = NULL;

		// SETEO VARIABLES GENERALES
 		$data->superCombo   = strlen( $data->superCombo ) > 0 ? (string)$data->superCombo : NULL;
 		$data->descripcion  = strlen( $data->descripcion ) > 0 ? (string)$data->descripcion : NULL;
 		$data->idEstadoMenu = (int)$data->idEstadoMenu > 0 AND !esNulo( $data->idEstadoMenu ) ? (int)$data->idEstadoMenu : NULL;
 
 		// VALIDACIONES
 		if( $accion == 'update' ):
 			$data->idSuperCombo = (int)$data->idSuperCombo > 0 ? (int)$data->idSuperCombo : NULL;
 			$idSuperCombo       = $validar->validarEntero( $data->idSuperCombo, NULL, TRUE, 'El ID del Super Combo no es válido, verifique.' );
 		endif;

		$superCombo   = $this->con->real_escape_string( $validar->validarTexto( $data->superCombo, NULL, TRUE, 3, 45, 'el nombre del Super Combo' ) );
		$descripcion  = $this->con->real_escape_string( $validar->validarTexto( $data->descripcion, NULL, TRUE, 15, 45, 'la descripcion' ) );
		$idEstadoMenu = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado combo no es válido, verifique.' );


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaSuperCombo( '{$accion}', {$idSuperCombo}, '{$superCombo}', {$imagen}, '{$descripcion}', {$idEstadoMenu} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
 				if( $accion == 'insert' AND $this->respuesta == 'success' ){
 					$this->data   = (int)$row->id;
 					$this->tiempo = 3;
 				}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR // ELIMINAR => COMBO DETALLE
	function consultaSuperComboDetalle( $accion, $data )
	{
		 $validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idSuperCombo = NULL;
 		$idCombo      = NULL;

		// SETEO VARIABLES GENERALES
		$data->idSuperCombo = (int)$data->idSuperCombo > 0 	? (int)$data->idSuperCombo 	: NULL;
		$data->idCombo      = (int)$data->idCombo > 0 		? (int)$data->idCombo 		: NULL;

		$idSuperCombo = $validar->validarEntero( $data->idSuperCombo, NULL, TRUE, 'El ID del Super Combo no es válido, verifique.' );
		$idCombo      = $validar->validarEntero( $data->idCombo, NULL, TRUE, 'El ID del Combo no es válido, verifique.' );

 		$cantidad = "NULL";
		// ELIMINAR
		if( $accion <> 'delete' ):
			$data->cantidad = (double)$data->cantidad ? (double)$data->cantidad : NULL;
			$cantidad       = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaSuperComboDetalle( '{$accion}', {$idCombo}, {$idSuperCombo}, {$cantidad} );";

	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();

 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;

	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
	}


 	// OBTENER ARREGLO RESPUESTA
 	private function getRespuesta()
 	{

 		if( $this->respuesta == 'success' )
 			$this->tiempo = 4;
 		elseif( $this->respuesta == 'warning')
 			$this->tiempo = 5;
 		elseif( $this->respuesta == 'danger')
 			$this->tiempo = 7;

 		return $respuesta = array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}

}
?>