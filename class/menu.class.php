<?php 
/**
* MENU
*/
class Menu
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


 	// LST RECETA(S) POR MENU
 	function lstRecetaMenu( $idMenu )
 	{
 		
 		$idMenu = (int)$idMenu;
 		$lstReceta = array();

 		$sql = "SELECT 
				    idMenu,
				    idProducto,
				    producto,
				    cantidad,
				    medida,
				    tipoProducto,
				    observacion
				FROM
				    lstReceta
				WHERE idMenu = {$idMenu};";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$row->idProducto = (int)$row->idProducto;
 				$row->cantidad   = (double)$row->cantidad;
 				$lstReceta[] = $row;
 			}
 		}

 		return $lstReceta;
 	}


 	// ACTUALIZAR LISTA RECETA MENU
 	function actualizarLstReceta( $accion, $lstRecetaProductos )
 	{
 		if( count( $lstRecetaProductos ) ) {

 			foreach ( $lstRecetaProductos AS $data ) {

				// ASIGNACIÓN DE VARIABLES
		 		$idMenu      = (int)$data->idMenu;
		 		$idProducto  = (int)$data->idProducto;
				$cantidad    = (double)$data->cantidad;
				$observacion = strlen( $data->observacion )	? $this->con->real_escape_string( $data->observacion )	: "NULL";

		 		$sql = "CALL consultaReceta( '{$accion}', {$idMenu}, {$idProducto}, {$cantidad}, '{$observacion}' );";
			 		
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
		 	$this->mensaje   = 'No hay productos ingresados en la lista de recetas';
 		}

 		return $this->getRespuesta();
 	}


 	// CONSULTA RECETA
 	function consultaReceta( $accion, $data )
 	{
		$validar = new Validar();

		// INICIALIZACIÓN VAR
		$idMenu      = "NULL";
		$idProducto  = "NULL";
		$cantidad    = "NULL";
		$observacion = "NULL";

		// SETEO VARIABLES GENERALES
 		$data->idMenu      = isset( $data->idMenu )		? (int)$data->idMenu 		: NULL;
 		$data->idProducto  = isset( $data->idProducto )	? (int)$data->idProducto 	: NULL;

 		// VALIDACIONES
 		$idMenu      = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menú no es válido, verifique.' );
		$idProducto  = $validar->validarEntero( $data->idProducto, NULL, TRUE, 'El ID del producto no es válido, verifique.' );

 		if( $accion == 'insert' OR $accion == 'update' )
 		{
 			$data->cantidad    = isset( $data->cantidad )		? (int)$data->cantidad 			: NULL;
 			$data->observacion = isset( $data->observacion )	? (string)$data->observacion	: NULL;

	 		// VALIDACIONES
			$cantidad    = $validar->validarCantidad( $data->cantidad, NULL, TRUE, 1, 2500, 'la cantidad' );
			$observacion = $this->con->real_escape_string( $validar->validarTexto( $data->observacion, NULL, !esNulo( $data->observacion ), 15, 1500, 'la observación' ) );
 		}

 		// OBTENER RESULTADOS
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaReceta( '{$accion}', {$idMenu}, {$idProducto}, {$cantidad}, '{$observacion}' );";
	 		
	 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
	 			$this->siguienteResultado();
	 			
 				$this->respuesta = $row->respuesta;
 				$this->mensaje   = $row->mensaje;
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


	// CONSULTAR DATOS MENU
	function cargarMenu( $idMenu )
	{
		$idMenu = (int)$idMenu;
		$menu   = array();

		$sql = "SELECT 
				    idMenu,
				    menu,
				    imagen,
				    descripcion,
				    idEstadoMenu,
				    idDestinoMenu,
				    codigoMenu AS codigo,
				    tiempoAlerta,
				    seCocina
				FROM
				    lstMenu
				WHERE
				    idMenu = {$idMenu};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() ){
				$row->seCocina = (int)$row->seCocina;
				$menu = $row;
			}
		}

		return $menu;
	}


	// CONSULTAR DATOS MENU PRECIO
	function cargarMenuPrecio( $idMenu )
	{
		$idMenu     = (int)$idMenu;
		$menuPrecio = array();

		$sql = "SELECT 
				    idMenu, 
				    idTipoServicio, 
				    tipoServicio,
				    precio
				FROM
				    lstMenuPrecio
				WHERE
				    idMenu = {$idMenu};";

		if( $rs = $this->con->query( $sql ) ){
			while( $rs AND $row = $rs->fetch_object() )
				$menuPrecio[] = array(
					'idMenu'         => (int)$row->idMenu,
					'idTipoServicio' => (int)$row->idTipoServicio,
					'tipoServicio'   => $row->tipoServicio,
					'precio'         => (double)$row->precio,
				);
		}

		return $menuPrecio;
	}

	// OBTENER TOTAL PRODUCTOS
	function getTotalPagMenu( $limite = 25, $busqueda )
	{
		
		$where = '';
		if( strlen( $busqueda ) ){
			$busqueda = str_replace(" ", "%", $busqueda );
			$where .= " AND  menu LIKE '%{$busqueda}%' ";
		}

		$total = 0;
		$sql = "SELECT COUNT(*) AS total FROM lstMenu {$where};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$total = (int)$row->total;
		}

		$totalPaginas = ceil( $total / $limite );
		
		return $totalPaginas;
	}


	// OBTENER LISTA DE MENUS
	function getListaMenus( $filter )
	{
		$pagina = $filter->pagina > 0 		? (int)$filter->pagina 	: 1;
		$limite = $filter->limite > 0 		? (int)$filter->limite 	: 25;
		$orden  = strlen( $filter->orden ) 	? $filter->orden 		: 'ASC';

		$where = '';
		if( strlen( $filter->busqueda ) ){
			$busqueda = str_replace(" ", "%", $filter->busqueda );
			$where .= " AND menu LIKE '%{$busqueda}%' ";
		}

		$menus = (object)array(
				'totalPaginas' => 0,
				'lstMenus' => array()
			);

		$inicio = ($pagina - 1) * $limite;

		$sql = "SELECT 
					idMenu,
					menu,
					imagen,
					descripcion,
					idEstadoMenu,
					estadoMenu,
					idDestinoMenu,
					destinoMenu,
					idTipoMenu,
					tipoMenu,
					codigoMenu,
					tiempoAlerta,
					seCocina
				FROM
					lstMenu 
				WHERE idEstadoMenu <> 3 {$where} 
				ORDER BY idMenu $orden LIMIT $inicio, $limite;";

		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				if( $row->imagen != '' )
					$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';

				$menu = array(
						'idMenu'        => (int)$row->idMenu,
						'menu'          => $row->menu,
						'imagen'        => $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen,
						'descripcion'   => $row->descripcion,
						'idEstadoMenu'  => (int)$row->idEstadoMenu,
						'estadoMenu'    => $row->estadoMenu,
						'idDestinoMenu' => (int)$row->idDestinoMenu,
						'destinoMenu'   => $row->destinoMenu,
						'idTipoMenu'    => (int)$row->idTipoMenu,
						'tipoMenu'      => $row->tipoMenu,
						'codigo'        => $row->codigoMenu,
						'tiempoAlerta'  => (int)$row->tiempoAlerta,
						'seCocina'  	=> (int)$row->seCocina,
				);

				$menus->lstMenus[] = $menu;
			}
		}

		$menus->totalPaginas = $this->getTotalPagMenu( $limite, $filter->busqueda );

		return $menus;
	}


 	// CONSULTAR LISTA DE MENUS
 	function lstMenu( $idTipoMenu = 0, $idEstadoMenu = NULL, $idMenu = NULL )
 	{
 		$lstMenu = array();

 		$where = "";

 		if ( $idTipoMenu > 0 )
	 		$where = " WHERE idTipoMenu = $idTipoMenu ";

 		if ( !IS_NULL( $idEstadoMenu ) ) {
	 		if ( $where == '' )
	 			$where = " WHERE idEstadoMenu = $idEstadoMenu ";

	 		else
	 			$where .= " AND idEstadoMenu = $idEstadoMenu ";
 		}

 		if ( !is_null( $idMenu ) AND $idMenu > 0 )
	 		$where = " WHERE idMenu = $idMenu ";

 		$sql = "SELECT
 					idMenu,
 					menu,
 					imagen,
 					descripcion,
 					idEstadoMenu,
 					estadoMenu,
 					idDestinoMenu,
 					destinoMenu,
 					idTipoMenu,
 					tipoMenu,
 					tiempoAlerta,
 					seCocina
 				FROM lstMenu $where";
 		
 		if( $rs = $this->con->query( $sql ) ){

 			// SI ES UN MENU
 			if ( !is_null( $idMenu ) AND $idMenu > 0 ) {
 				if( $row = $rs->fetch_object() ){
 					if( $row->imagen != '' )
						$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';
					$row->imagen = $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen;
					$lstMenu     = $row;
	 			}
 			}

 			// SI SON TODOS LOS MENU
 			else{
	 			while( $row = $rs->fetch_object() ){
	 				if( $row->imagen != '' )
						$row->imagen = file_exists( $row->imagen ) ? $row->imagen : 'img-menu/notFound.png';
					
					$row->imagen = $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen;
					$lstMenu[]   = $row;
	 			}
 			}
 		}

 		return $lstMenu;
 	}

	
	// CONSULTAR LISTA DE MENUS
 	function lstMenuPrecio()
 	{
 		$lstMenuPrecio = array();

 		$sql = "SELECT idMenu, precio, idTipoServicio, tipoServicio FROM lstMenuPrecio;";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
 				$lstMenuPrecio[] = $row;
 			}
 		}

 		return $lstMenuPrecio;
 	}


 	// GUARDAR // ACTUALIZAR => MENU
	function consultaMenu( $accion, $data )
 	{
 		
 		if( count( $data->lstPrecios ) ){

	 		$validar = new Validar();

			// INICIALIZACIÓN VAR
			$idMenu        = 'NULL';
			$menu          = NULL;
			$imagen        = "NULL";
			$descripcion   = NULL;
			$idEstadoMenu  = NULL;
			$idDestinoMenu = NULL;

			// SETEO VARIABLES GENERALES
	 		$data->menu          = isset( $data->menu ) 			? (string)$data->menu 			: NULL;
	 		$data->codigo        = isset( $data->codigo ) 			? (string)$data->codigo 		: NULL;
	 		$data->descripcion   = isset( $data->descripcion ) 		? (string)$data->descripcion 	: NULL;
	 		$data->idEstadoMenu  = isset( $data->idEstadoMenu ) 	? (int)$data->idEstadoMenu 		: NULL;
	 		$data->idDestinoMenu = isset( $data->idDestinoMenu ) 	? (int)$data->idDestinoMenu 	: NULL;
	 		$data->idTipoMenu    = isset( $data->idTipoMenu )		? (int)$data->idTipoMenu 		: NULL;
	 		$data->tiempoAlerta  = isset( $data->tiempoAlerta )		? (int)$data->tiempoAlerta 		: NULL;
	 		$data->seCocina      = isset( $data->seCocina )			? (int)$data->seCocina 			: 1;
	 
	 		// VALIDACIONES
	 		if( $accion == 'update' ):
	 			$data->idMenu = isset( $data->idMenu ) ? (int)$data->idMenu : NULL;
	 			$idMenu       = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menú no es válido' );
	 		endif;

			$menu          = $this->con->real_escape_string( $validar->validarTexto( $data->menu, NULL, TRUE, 3, 45, 'el nombre del menu' ) );
			$codigo        = $validar->validarEntero( $data->codigo, NULL, TRUE, 'El código del Menu no es válido' );
			$descripcion   = $this->con->real_escape_string( $validar->validarTexto( $data->descripcion, NULL, TRUE, 3, 1500, 'en la descripcion' ) );
			$idEstadoMenu  = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado Menú no es válido' );
			$idDestinoMenu = $validar->validarEntero( $data->idDestinoMenu, NULL, TRUE, 'El ID del tipo de medida no es válido	' );
			$idTipoMenu    = $validar->validarEntero( $data->idTipoMenu, NULL, TRUE, 'El ID del tipo de menú no es válido' );
			$tiempoAlerta  = $validar->validarEntero( $data->tiempoAlerta, NULL, TRUE, 'El tiempo de respuesta debe ser mayor a 0' );

			// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:
	 			// INICIALIZAR TRANSACCION
	 			$this->con->query( 'START TRANSACTION' );

		 		$sql = "CALL consultaMenu( '{$accion}', {$idMenu}, '{$menu}', {$imagen}, '{$descripcion}', {$idEstadoMenu}, {$idDestinoMenu}, {$idTipoMenu}, {$codigo}, {$tiempoAlerta}, {$data->seCocina} );";

		 		if( $rs = $this->con->query( $sql ) AND $row = $rs->fetch_object() ){
		 			$this->siguienteResultado();

	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				
	 				if( ( $accion == 'insert' OR $accion == 'update' ) AND $this->respuesta == 'success' ){
	 					if( $accion == 'insert' )
	 						$idMenu = $this->data = (int)$row->id;
	 					// INSERTAR PRECIOS
	 					$this->consultaMenuPrecio( 'insert', $idMenu, $data->lstPrecios );
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


 	// GUARDAR // ACTUALIZAR => MENU PRECIO
	function consultaMenuPrecio( $accion, $idMenu, $lstPrecios )
 	{
 		
 		foreach ( $lstPrecios AS $ixPrecio => $precio ) {

 			// SETEO VARIABLES
			$precio->idTipoServicio = (int)$precio->idTipoServicio > 0 ? (int)$precio->idTipoServicio : NULL;

			$validar = new Validar();

			$idTipoServicio = $validar->validarEntero( $precio->idTipoServicio, NULL, TRUE, "ID del servicio {$precio->tipoServicio} inválido" );
			$precio                 = (double)$precio->precio;

			if( $validar->getIsError() ):
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = $validar->getMsj();
	 		
	 		else:
		 		$sql = "CALL consultaMenuPrecio( '{$accion}', {$idMenu}, {$idTipoServicio}, {$precio} );";

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

 	
 	// BUSCAR MENU POR NOMBRE
 	function buscarMenu( $menu )
	{

		$lstMenus = array();

		$sql = "SELECT 
				    idMenu,
				    menu,
				    imagen,
				    descripcion,
				    idEstadoMenu,
				    estadoMenu,
				    idDestinoMenu,
				    destinoMenu
				FROM
				    lstMenu WHERE menu LIKE '%{$menu}%' LIMIT 10;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$menu = array(
						'idMenu'        => (int)$row->idMenu,
						'menu'          => $row->menu,
						'imagen'        => $row->imagen,
						'descripcion'   => $row->descripcion,
						'idEstadoMenu'  => (int)$row->idEstadoMenu,
						'estadoMenu'    => $row->estadoMenu,
						'idDestinoMenu' => (int)$row->idDestinoMenu,
						'destinoMenu'   => $row->destinoMenu
					);

				$lstMenus[] = $menu;
			}
		}

		return $lstMenus;
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

 		return array( 
 				'respuesta' => $this->respuesta,
 				'mensaje'   => $this->mensaje,
 				'tiempo'    => $this->tiempo,
 				'data'      => $this->data
 			);
 	}



}

?>