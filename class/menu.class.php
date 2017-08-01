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
		$idMenu = (int)$idMenu;
		$menuPrecio = array();

		$sql = "SELECT 
				    idMenu, 
				    idTipoServicio, 
				    precio
				FROM
				    lstMenuPrecio
				WHERE
				    idMenu = {$idMenu};";
		
		if( $rs = $this->con->query( $sql ) ){
			while( $rs AND $row = $rs->fetch_object() )
				$menuPrecio[] = (object)array(
					'idMenu'         => (int)$row->idMenu,
					'idTipoServicio' => (int)$row->idTipoServicio,
					'precio'         => (double)$row->precio,
				);
		}

		return $menuPrecio;
	}


 	// CONSULTAR LISTA DE MENUS
 	function lstMenu( $idTipoMenu = 0 )
 	{
 		$lstMenu = array();

 		$where = "";

 		if ( $idTipoMenu > 0 )
	 		$where = " WHERE idTipoMenu = $idTipoMenu ";

 		$sql = "SELECT idMenu, menu, imagen, descripcion, idEstadoMenu, estadoMenu, idDestinoMenu, destinoMenu FROM lstMenu $where";
 		
 		if( $rs = $this->con->query( $sql ) ){
 			while( $row = $rs->fetch_object() ){
				$row->imagen = $row->imagen == '' ? 'notFound.png' : $row->imagen;
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
		$descripcion   = $validar->validarTexto( $data->descripcion, NULL, TRUE, 3, 45, 'el nombre del descripcion' );
		$idEstadoMenu  = $validar->validarEntero( $data->idEstadoMenu, NULL, TRUE, 'El ID del estado Menú no es válido, verifique.' );
		$idDestinoMenu = $validar->validarEntero( $data->idDestinoMenu, NULL, TRUE, 'El ID del tipo de medida no es válido, verifique.' );
		$idTipoMenu    = $validar->validarEntero( $data->idTipoMenu, NULL, TRUE, 'El ID del tipo de menú no es válido, verifique.' );


		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaMenu( '{$accion}', {$idMenu}, '{$menu}', {$imagen}, '{$descripcion}', {$idEstadoMenu}, {$idDestinoMenu}, {$idTipoMenu} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 				if( $accion == 'insert' AND $this->respuesta == 'success' )
	 					$this->data = (int)$row->id;
	 			}
	 		}
	 		else{
	 			$this->respuesta = 'danger';
	 			$this->mensaje   = 'Error al ejecutar la instrucción.';
	 		}
	 		
 		endif;

 		return $this->getRespuesta();
 	}


 	// GUARDAR // ACTUALIZAR => MENU PRECIO
	function consultaMenuPrecio( $accion, $data )
 	{
 		$validar = new Validar();

		// INICIALIZACIÓN VAR
 		$idMenu         = NULL;
 		$idTipoServicio = NULL;		

		// SETEO VARIABLES GENERALES
		$data->idTipoServicio = (int)$data->idTipoServicio > 0 	? (int)$data->idTipoServicio 	: NULL;
		$data->precio         = (double)$data->precio 	 		? (double)$data->precio 		: NULL;

		$idMenu         = $validar->validarEntero( $data->idMenu, NULL, TRUE, 'El ID del menú no es válido, verifique.' );
		$idTipoServicio = $validar->validarEntero( $data->idTipoServicio, NULL, TRUE, 'El ID del tipo de servicio no es válido, verifique.' );
		
		$precio = "NULL";
		if( $accion <> 'delete' ):
			$data->idMenu = (int)$data->idMenu > 0 	? (int)$data->idMenu : NULL;
			$precio = $validar->validarCantidad( $data->precio, NULL, TRUE, 1, 2500, 'el precio del menú' );
		endif;

		// OBTENER RESULTADO DE VALIDACIONES
 		if( $validar->getIsError() ):
	 		$this->respuesta = 'danger';
	 		$this->mensaje   = $validar->getMsj();

 		else:
	 		$sql = "CALL consultaMenuPrecio( '{$accion}', {$idMenu}, {$idTipoServicio}, {$precio} );";

	 		if( $rs = $this->con->query( $sql ) ){
	 			@$this->con->next_result();
	 			if( $row = $rs->fetch_object() ){
	 				$this->respuesta = $row->respuesta;
	 				$this->mensaje   = $row->mensaje;
	 			}
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