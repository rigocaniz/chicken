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
				    idDestinoMenu
				FROM
				    lstMenu
				WHERE
				    idMenu = {$idMenu};";
		
		if( $rs = $this->con->query( $sql ) ){
			if( $row = $rs->fetch_object() )
				$menu = $row;
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
	function getTotalPagMenu( $limite = 25 )
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS total FROM lstMenu;";
		
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
					tipoMenu
				FROM
					lstMenu WHERE idEstadoMenu <> 3 ORDER BY idMenu $orden LIMIT $inicio, $limite;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
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
						'tipoMenu'      => $row->tipoMenu
				);

				$menus->lstMenus[] = $menu;
			}
		}

		$menus->totalPaginas = $this->getTotalPagMenu( $limite );

		return $menus;
	}


 	// CONSULTAR LISTA DE MENUS
 	function lstMenu( $idTipoMenu = 0 )
 	{
 		$lstMenu = array();

 		$where = "";

 		if ( $idTipoMenu > 0 )
	 		$where = " WHERE idTipoMenu = $idTipoMenu ";

 		$sql = "SELECT idMenu, menu, imagen, descripcion, idEstadoMenu, estadoMenu, idDestinoMenu, destinoMenu, idTipoMenu, tipoMenu FROM lstMenu $where";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
				$row->imagen = $row->imagen == '' ? 'img-menu/notFound.png' : $row->imagen;
				$lstMenu[]   = $row;
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
	 		$data->menu          = strlen( $data->menu ) > 0 		? (string)$data->menu : NULL;
	 		$data->descripcion   = strlen( $data->descripcion ) > 0 ? (string)$data->descripcion : NULL;
	 		$data->idEstadoMenu  = (int)$data->idEstadoMenu > 0  	? (int)$data->idEstadoMenu : NULL;
	 		$data->idDestinoMenu = (int)$data->idDestinoMenu > 0  	? (int)$data->idDestinoMenu : NULL;
	 		$data->idTipoMenu    = (int)$data->idTipoMenu > 0 		? (int)$data->idTipoMenu : NULL;

	 
	 		// VALIDACIONES
	 		if( $accion == 'update' ):
	 			$data->idMenu = (int)$data->idMenu > 0 ? (int)$data->idMenu : NULL;
	 			$idMenu = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del Menú no es válido, verifique.' );
	 		endif;

			$menu          = $validar->validarTexto( $data->menu, NULL, TRUE, 3, 45, 'el nombre del menu' );
			$descripcion   = $validar->validarTexto( $data->descripcion, NULL, TRUE, 3, 1500, 'en la descripcion' );
			$idEstadoMenu  = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado Menú no es válido, verifique.' );
			$idDestinoMenu = $validar->validarEntero( $data->idDestinoMenu, NULL, TRUE, 'El ID del tipo de medida no es válido, verifique.' );
			$idTipoMenu    = $validar->validarEntero( $data->idTipoMenu, NULL, TRUE, 'El ID del tipo de menú no es válido, verifique.' );


			// OBTENER RESULTADO DE VALIDACIONES
	 		if( $validar->getIsError() ):
		 		$this->respuesta = 'danger';
		 		$this->mensaje   = $validar->getMsj();

	 		else:
	 			// INICIALIZAR TRANSACCION
	 			$this->con->query( 'START TRANSACTION' );

		 		$sql = "CALL consultaMenu( '{$accion}', {$idMenu}, '{$menu}', {$imagen}, '{$descripcion}', {$idEstadoMenu}, {$idDestinoMenu}, {$idTipoMenu} );";

		 		if( $rs = $this->con->query( $sql ) ){
		 			$this->siguienteResultado();
		 			if( $row = $rs->fetch_object() ){
		 				$this->respuesta = $row->respuesta;
		 				$this->mensaje   = $row->mensaje;
		 				
		 				if( ( $accion == 'insert' OR $accion == 'update' ) AND $this->respuesta == 'success' ){
		 					if( $accion == 'insert' )
		 						$idMenu = $this->data = (int)$row->id;
		 					// INSERTAR PRECIOS
		 					$this->consultaMenuPrecio( $accion, $idMenu, $data->lstPrecios );
		 				}
		 			}
		 		}
		 		else{
		 			$this->respuesta = 'danger';
		 			$this->mensaje   = 'Error al ejecutar la instrucción.';
		 		}

		 		// FINALIZAR TRANSACCION
		 		if( $this->respuesta )
		 			$this->con->query( 'COMMIT' );
		 		else
		 			$this->con->query( 'ROLLBACK' );

	 		endif;
 		}else{
 			$this->respuesta = 'info';
		 	$this->mensaje   = 'No hay ingresado los precios del Menu';
 		}

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR => MENU PRECIO
	function consultaMenuPrecio( $accion, $idMenu, $lstPrecios )
 	{
 		
 		foreach ( $lstPrecios AS $ixPrecio => $precio ) {

 			// SETEO VARIABLES GENERALES
			$precio->idTipoServicio = (int)$precio->idTipoServicio > 0 	? (int)$precio->idTipoServicio	: NULL;

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

 	function consultarLstProductos()
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
				    lstMenu;";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $row = $rs->fetch_object() ){
				$producto = array(
						'idMenu'        => (int)$row->idMenu,
						'menu'          => $row->menu,
						'imagen'        => $row->imagen,
						'descripcion'   => $row->descripcion,
						'idEstadoMenu'  => (int)$row->idEstadoMenu,
						'estadoMenu'    => $row->estadoMenu,
						'idDestinoMenu' => (int)$row->idDestinoMenu,
						'destinoMenu'   => $row->destinoMenu,
						'idTipoMenu'    => (int)$row->idTipoMenu,
						'tipoMenu'      => $row->tipoMenu
					);

				$lstMenus[] = $producto;
			}
		}
		else{
			$this->respuesta = 'danger';
			$this->mensaje   = 'Error al ejecutar la operacion (SP)';
		}

		return $lstMenus;
	}

}

?>