<?php 
session_start();

$data = json_decode( file_get_contents("php://input") );

include 'class/conexion.class.php';
include 'class/cliente.class.php';
include 'class/caja.class.php';
include 'class/combo.class.php';
include 'class/consulta.class.php';
include 'class/facturar.class.php';
include 'class/medida.class.php';
include 'class/menu.class.php';
include 'class/orden.class.php';
include 'class/producto.class.php';
include 'class/sesion.class.php';
include 'class/usuario.class.php';
include 'class/validar.class.php';
include 'class/funciones.php';

// DEFINIR SESION USUARIO
$sql = "CALL definirSesion( '{$sesion->getUsuario()}' );";
$conexion->query( $sql );

$datos = array();

switch ( $data->opcion )
{
	case 'timeNow':
		$datos[ 'fechaActual' ] = DATE( 'Y-m-d' );
		$datos[ 'timeNow' ]     = date("Y-m-d H:i:s");
		echo json_encode( $datos );
		break;

	case 'login':

		if ( !isset( $data->codigoPersonal ) )
			$data->codigoPersonal = 'NULL';

		if ( !isset( $data->usuario ) )
			$data->usuario = 'NULL';

		$usuario = new Usuario();
		echo json_encode( $usuario->login( $data->usuario, $data->clave, $data->codigoPersonal ) );
		break;

	/////////////////////////
	//***** INICIOS
	////////////////////////
	case 'inicioCaja':			// DATOS MEDIDA
		$consulta = new Consulta();
		$caja     = new Caja();
		$datos[ 'cajero' ]   = $sesion->getNombre();
		$datos[ 'dataCaja' ] = $caja->consultarEstadoCaja();

		echo json_encode( $datos );
		break;
	
	case 'inicioAdmin':			// DATOS MEDIDA
		$consulta = new Consulta();
		$datos[ 'lstDestinoMenu' ]   = $consulta->catDestinoMenu();
		$datos[ 'lsTipoMenu' ]       = $consulta->catTipoMenu();
		$datos[ 'lstTiposServicio' ] = $consulta->catTiposServicio();
		$datos[ 'lstEstadosMenu' ]   = $consulta->catEstadoMenu();
		
		echo json_encode( $datos );
		break;

	/////////////////////////
	//***** CAJA
	////////////////////////
	case 'consultaCaja':			// CONSULTA CAJA => INSERT // STATUS
		$caja = new Caja();

		echo json_encode( $caja->consultaCaja( $data->accion, $data->data ) );
		break;

		

	/////////////////////////
	//***** CONSULTA DATOS
	////////////////////////
	case 'cargarMedida':			// DATOS MEDIDA
		$medida = new Medida();
		echo json_encode( $medida->cargarMedida( $data->idMedida ) );
		break;

	case 'cargarTipoProducto':		// DATOS TIPO PRODUCTO
		$producto = new Producto();
		echo json_encode( $producto->cargarTipoProducto( $data->idTipoProducto ) );
		break;

	case 'cargarProducto':			// DATOS PRODUCTO
		$producto = new Producto();
		echo json_encode( $producto->cargarProducto( $data->idProducto ) );
		break;

	case 'cargarMenu':				// DATOS MENU
		$menu = new Menu();
		echo json_encode( $menu->cargarMenu( $data->idMenu ) );
		break;

	case 'cargarMenuPrecio':		// DATOS MENU PRECIO
		$menu = new Menu();
		echo json_encode( $menu->cargarMenuPrecio( $data->idMenu ) );
		break;

	case 'cargarCombo':				// DATOS COMBO
		$combo = new Combo();
		echo json_encode( $combo->cargarCombo( $data->idCombo ) );
		break;

	case 'cargarComboDetalle':		// DATOS COMBO DETALLE
		$combo = new Combo();
		echo json_encode( $combo->cargarComboDetalle( $data->idCombo, $data->idMenu ) );
		break;

	case 'cargarComboPrecio':		// DATOS COMBO DETALLE
		$combo = new Combo();
		echo json_encode( $combo->cargarComboPrecio( $data->idCombo, @$data->idTipoServicio ) );
		break;

	case 'cargarSuperCombo':		// DATOS SUPER COMBO
		$combo = new Combo();
		echo json_encode( $combo->cargarSuperCombo( $data->idSuperCombo ) );
		break;

	case 'cargarSuperComboDetalle':	// DATOS SUPER COMBO DETALLE
		$combo = new Combo();
		echo json_encode( $combo->cargarSuperComboDetalle( $data->idCombo, $data->idSuperCombo ) );
		break;

	case 'cargarSuperComboPrecio':	// DATOS SUPER COMBO PRECIO
		$combo = new Combo();
		echo json_encode( $combo->cargarSuperComboPrecio( $data->idSuperCombo, $data->idTipoServicio ) );
		break;


	/////////////////////////
	//***** CONSULTA LISTAS
	////////////////////////
	case 'lstCombo':				// CARGAR LISTA DE COMBOS
		$combo = new Combo();
		echo json_encode( $combo->lstCombo( @$data->idEstadoMenu, @$data->idCombo ) );
		break;

	case 'lstComboDetalle':			// CARGAR LISTA DE COMBOS DETALLE
		$combo = new Combo();
		echo json_encode( $combo->lstComboDetalle( $data->idCombo ) );
		break;

	case 'lstComboPrecio':			// CARGAR LISTA DE COMBOS PRECIO
		$combo = new Combo();
		echo json_encode( $combo->lstComboPrecio( $data->idCombo ) );
		break;

	case 'lstSuperCombo':			// CARGAR LISTA DE SUPER COMBOS
		$combo = new Combo();
		echo json_encode( $combo->lstSuperCombo() );
		break;

	case 'lstSuperComboDetalle':	// CARGAR LISTA DE SUPER COMBOS DETALLE
		$combo = new Combo();
		echo json_encode( $combo->lstSuperComboDetalle() );
		break;

	case 'lstSuperComboPrecio':		// CARGAR LISTA DE SUPER COMBOS PRECIO
		$combo = new Combo();
		echo json_encode( $combo->lstSuperComboPrecio() );
		break;

	case 'lstMenu':					// CARGAR LISTA DE MENU
		$menu = new Menu();
		echo json_encode( $menu->lstMenu( $data->idTipoMenu, @$data->idEstadoMenu, @$data->idMenu ) );
		break;

	case 'lstMenuPrecio':			// CARGAR LISTA PRECIOS MENU
		$menu = new Menu();
		echo json_encode( $menu->lstMenuPrecio( $data->idMenu ) );
		break;


	/////////////////////////
	//***** CONSULTA CATALOGOS
	////////////////////////
	case 'catFormasPago':				// CARGAR CATALOGO FORMAS DE PAGO
		$consulta = new Consulta();
		echo json_encode( $consulta->catFormasPago() );
		break;

	case 'catEstadosFactura':			// CARGAR CATALOGO TIPOS DE SERVICIOS
		$consulta = new Consulta();
		echo json_encode( $consulta->catEstadosFactura() );
		break;

	case 'catTiposServicio':			// CARGAR CATALOGO TIPOS DE SERVICIOS
		$consulta = new Consulta();
		echo json_encode( $consulta->catTiposServicio() );
		break;

	case 'catMedidas':					// CARGAR CATALOGO MEDIDAS
		$consulta = new Consulta();
		echo json_encode( $consulta->catMedidas() );
		break;

	case 'catEstadoMenu':				// CARGAR CATALOGO ESTADO MENU
		$consulta = new Consulta();
		echo json_encode( $consulta->catEstadoMenu() );
		break;

	case 'catTipoProducto':				// CARGAR CATALOGO TIPOS DE PRODUCTOS
		$consulta = new Consulta();
		echo json_encode( $consulta->catTipoProducto() );
		break;

	case 'catEstadoOrden':				// CARGAR CATALOGO ESTADO ORDEN
		$consulta = new Consulta();
		echo json_encode( $consulta->catEstadoOrden() );
		break;

	case 'catDestinoMenu':				// CARGAR CATALOGO DESTINO MENU
		$consulta = new Consulta();
		echo json_encode( $consulta->catDestinoMenu() );
		break;

	case 'catTiposCliente':				// CARGAR CATALOGO TIPOS DE CLIENTE
		$consulta = new Consulta();
		echo json_encode( $consulta->catTiposCliente() );
		break;

	case 'catEstadoCaja':				// CARGAR CATALOGO ESTADO DE CAJA
		$consulta = new Consulta();		
		echo json_encode( $consulta->catEstadoCaja() );
		break;

	case 'catTipoMenu':				// CARGAR CATALOGO TIPOS DE MENU
		$consulta = new Consulta();
		echo json_encode( $consulta->catTipoMenu() );
		break;
	

	/////////////////////////
	//***** USUARIO
	/////////////////////////
	case 'consultaUsuario':			// CONSULTA ASOCIADO => INSERT / UPDATE
		$usuario = new Usuario();
		echo json_encode( $usuario->consultaUsuario( $data->accion, $data->data ) );
		break;
	
	case 'resetearClave':			// RESETEAR CLAVE DEL USUARIO
		$usuario = new Usuario();
		echo json_encode( $usuario->resetearClave( $data->usuario ) );
		break;

	case 'cargarLstUsuarios':		// CONSULTAR LST USUARIO
		$usuario = new Usuario();
		echo json_encode( $usuario->lstUsuarios( $data->filtro ) );
		break;

	case 'actualizarEstadoUsuario':	// ACTUALIZAR ESTADO DEL USUARIO
		$usuario = new Usuario();
		echo json_encode( $usuario->actualizarEstadoUsuario( $data->data ) );
		break;

	case 'lstEstadoUsuario':		// CONSULTA ESTADOS USUARIO
		$usuario = new Usuario();
		echo json_encode( $usuario->lstEstadoUsuario() );
		break;

	case 'lstPerfiles':				// CONSULTA PERFILES
		$usuario = new Usuario();
		echo json_encode( $usuario->lstPerfiles() );
		break;

	/////////////////////////
	//***** CLIENTE
	/////////////////////////
	case 'consultaCliente':			// CONSULTA CLIENTE
		$cliente = new Cliente();
		echo json_encode( $cliente->consultaCliente( $data->accion, $data->cliente ) );
		break;

	case 'consultarCliente':			// CARGAR CLIENTE
		$cliente = new Cliente();
		echo json_encode( $cliente->consultarCliente( $data->valor ) );
		break;

	/////////////////////////
	//***** PRODUCTO
	/////////////////////////
	case 'cargarFechaCierre':			// INSERT / UPDATE CIERRE DEL DIA
		$producto = new Producto();
		echo json_encode( $producto->cargarFechaCierre( $data->fechaCierre ) );
		break;
		
	case 'cargarLstFacturaCompra':			// INSERT / UPDATE CIERRE DEL DIA
		$producto = new Producto();
		echo json_encode( $producto->cargarLstFacturaCompra() );
		break;

	case 'cargarLstIngresoProducto':		// CONSULTA lSTINGRESOPRODUCTO
		$producto = new Producto();
		echo json_encode( $producto->cargarLstIngresoProducto( $data->idFacturaCompra ) );
		break;		

	case 'consultaCierreDiario':			// INSERT / UPDATE CIERRE DEL DIA
		$producto = new Producto();
		echo json_encode( $producto->consultaCierreDiario( $data->accion, $data->data ) );
		break;

	case 'getListaProductos':			// INSERT / UPDATE LST PRODUCTOS INGRESO
		$producto = new Producto();
		echo json_encode( $producto->getListaProductos( $data->filtro ) );
		break;
		
	case 'consultaFactura':			// INSERT / UPDATE LST PRODUCTOS INGRESO
		$producto = new Producto();
		echo json_encode( $producto->consultaFactura( $data->accion, $data->data ) );
		break;
		
	case 'buscarProducto':			// BUSCAR PRODUCTO(S) POR NOMBRE
		$producto = new Producto();
		echo json_encode( $producto->buscarProducto( $data->nombreProducto ) );
		break;

	case 'consultaProducto':			// ACCION PRODUCTO: INSERT / UPDATE
		$producto = new Producto();
		echo json_encode( $producto->consultaProducto( $data->accion, $data->datos ) );
		break;

	case 'lstProductos':				// CONSULTAR LISTA DE PRODUCTOS
		$producto = new Producto();
		echo json_encode( $producto->lstProductos( $data->groupBy ) );
		break;

	case 'consultaTipoProducto':		// ACCION PRODUCTO: INSERT / UPDATE
		$producto = new Producto();
		echo json_encode( $producto->consultaTipoProducto( $data->accion, $data->datos ) );
		break;

	case 'consultaIngreso':		//	ACCION INGRESO: INSERT / DELETE
		$producto = new Producto();
		echo json_encode( $producto->consultaIngreso( $data->accion, $data->datos ) );
		break;

	case 'consultaReajusteInventario':	//	CONSULTA REAJUSTE INVENTARIO INDIVIDUAL: INSERT
		$producto = new Producto();
		echo json_encode( $producto->consultaReajusteInventario( $data->accion, $data->datos ) );
		break;

	case 'guardarReajusteMasivo':
		$producto = new Producto();
		echo json_encode( $producto->guardarReajusteMasivo( $data->accion, $data->datos ) );
		break;

	/////////////////////////
	//***** MEDIDA
	/////////////////////////
	case 'consultaMedida':			// ACCION MEDIDA: INSERT / UPDATE
		$medida = new Medida();
		echo json_encode( $medida->consultaMedida( $data->accion, $data->datos ) );
		break;

	
	/////////////////////////
	//***** COMBO
	/////////////////////////
	case 'actualizarLstDetalleCombo':		// ACCION DETALLE COMBO: UPDATE
		$combo = new Combo();
		echo json_encode( $combo->actualizarLstDetalleCombo( $data->accion, $data->datos ) );
		break;

	case 'getListaCombos':			// CONSULTAR LISTA DE PRODUCTOS
		$combo = new Combo();
		echo json_encode( $combo->getListaCombos( $data->filtro ) );
		break;

	case 'consultaCombo':			// CONSULTA COMBO: INSERT / UPDATE
		$combo = new Combo();
		echo json_encode( $combo->consultaCombo( $data->accion, $data->datos ) );
		break;

	case 'consultaComboPrecio':		// CONSULTA COMBO PRECIO: INSERT / UPDATE
		$combo = new Combo();
		echo json_encode( $combo->consultaComboPrecio( $data->accion, $data->datos ) );
		break;

	case 'consultaComboDetalle':	// ACCION COMBO DETALLE: INSERT / UPDATE / DELETE
		$combo = new Combo();
		echo json_encode( $combo->consultaComboDetalle( $data->accion, $data->datos ) );
		break;

	case 'consultaSuperCombo':		// ACCION SUPER COMBO: INSERT / UPDATE
		$combo = new Combo();
		echo json_encode( $combo->consultaSuperCombo( $data->accion, $data->datos ) );
		break;

	case 'consultaSuperComboDetalle':	// ACCION SUPER COMBO: INSERT / UPDATE / DELETE
		$combo = new Combo();
		echo json_encode( $combo->consultaSuperComboDetalle( $data->accion, $data->datos ) );
		break;


	/////////////////////////
	//***** MENU
	/////////////////////////
	case 'buscarMenu':			// BUSCAR MENU(S) POR NOMBRE
		$menu = new Menu();
		echo json_encode( $menu->buscarMenu( $data->nombreMenu ) );
		break;

	case 'actualizarLstReceta':		// ACCION DETALLE RECETA: UPDATE
		$menu = new Menu();
		echo json_encode( $menu->actualizarLstReceta( $data->accion, $data->datos ) );
		break;

	case 'consultaReceta':			// ACCION RECETA: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->consultaReceta( $data->accion, $data->datos ) );
		break;

	case 'lstRecetaMenu':			// ACCION MENU: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->lstRecetaMenu( $data->idMenu ) );
		break;
		
	case 'consultaMenu':			// ACCION MENU: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->consultaMenu( $data->accion, $data->datos ) );
		break;

	case 'getListaMenus':			// CONSULTAR LISTA DE PRODUCTOS
		$menu = new Menu();
		echo json_encode( $menu->getListaMenus( $data->filtro ) );
		break;

	case 'consultaMenuPrecio':		// ACCION MENU PRECIO: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->consultaMenuPrecio( $data->accion, $data->datos ) );
		break;

	
	/////////////////////////
	//***** ORDEN
	/////////////////////////
	case 'consultaOrdenCliente':	// ACCION MENU: INSERT / MENU-CANTIDAD / ESTADO / RESPONSABLE / ETC
		$orden = new Orden();
		echo json_encode( $orden->consultaOrdenCliente( $data->accion, $data->datos ) );
		break;

	case 'lstOrdenCliente':	// ORDEN CLIENTE
		if ( !isset( $data->limite ) )
			$data->limite = NULL;

		if ( !isset( $data->idOrdenCliente ) )
			$data->idOrdenCliente = NULL;

		$orden = new Orden();
		echo json_encode( $orden->lstOrdenCliente( $data->idEstadoOrden, $data->limite, $data->idOrdenCliente ) );
		break;

	case 'consultaDetalleOrdenMenu':	// ACCION MENU: INSERT / MENU-CANTIDAD / ESTADO / RESPONSABLE / ETC
		$orden = new Orden();
		echo json_encode( $orden->consultaDetalleOrdenMenu( $data->accion, $data->datos ) );
		break;

	case 'lstDetalleOrdenCliente':
		$orden = new Orden();
		$todo  = isset( $data->todo ) ? $data->todo : false ;
		echo json_encode( $orden->lstDetalleOrdenCliente( $data->idOrdenCliente, $todo ) );
		break;

		/////////////////////////////////////
		//////////////////////////////////
	case 'lstDetalleOrden':// 	prueba
		$factura = new Factura();
		$todo  = isset( $data->todo ) ? $data->todo : false ;
		echo json_encode( $factura->lstDetalleOrdenCliente( $data->idOrdenCliente, $todo ) );
		break;

	case 'consultaFacturaCliente':// 	prueba
		$factura = new Factura();
		echo json_encode( $factura->consultaFacturaCliente( $data->accion, $data->data ) );
		break;
		//////////////////////////////////
		/////////////////////////////////////

	// LISTA DE ORDENES DETALLE => POR DESTINO
	case 'lstOrdenPorMenu':
		$orden = new Orden();
		echo json_encode(
			array( 'lstMenu' => $orden->lstOrdenPorMenu( $data->idEstadoDetalleOrden, $data->idDestinoMenu ) )
		);
		break;

	// LISTA DE ORDENES DETALLE => POR TICKET
	case 'lstOrdenPorTicket':
		$orden = new Orden();
		echo json_encode(
			array( 'lstTicket' => $orden->lstOrdenPorTicket( $data->idEstadoOrden, $data->idDestinoMenu, $data->idEstadoDetalleOrden ) )
		);
		break;

	case 'menuPorCodigo':
		$orden = new Orden();
		echo json_encode( $orden->menuPorCodigo( $data->codigoRapido ) );
		break;

	case 'busquedaTicket':
		$orden = new Orden();
		echo json_encode( $orden->busquedaTicket( $data->ticket ) );
		break;

	// CANCELAR ORDEN PARCIALMENTE
	case 'cancelarOrdenParcial':
		$orden = new Orden();
		echo json_encode( $orden->cancelarOrdenParcial( $data->idOrdenCliente, $data->lstDetalle ) );
		break;

	// CAMBIA SERVICIO DE ORDEN
	case 'cambiarServicio':
		$orden = new Orden();
		echo json_encode( $orden->cambiarServicio( $data->idOrdenCliente, $data->lstDetalle, $data->idTipoServicio ) );
		break;

	// CAMBIA ESTADO DE ORDENES
	case 'cambioEstadoDetalleOrden':
		$orden = new Orden();
		echo json_encode( $orden->cambioEstadoOrden( $data->idEstadoOrden, $data->lstOrdenes ) );
		break;

	// INI - ADMIN. ORDEN
	case 'iniOrdenAdmin':				// CARGAR CATALOGO DESTINO MENU
		$usuario = new Usuario();
		echo json_encode( array( 'usuario' => $usuario->getUsuario( $sesion->getUsuario() ) ) );
		break;


	/////////////////////////
	/////////////////////////
	case 'guardarDetalleOrden':
		$orden = new Orden();
		$orden->guardarDetalleOrden( $data->idOrdenCliente, $data->lstAgregar, $data->accionOrden ) ;
		echo json_encode( $orden->getRespuesta() );
		break;
	
	default:
		# code...
		break;
}

$conexion->close();

?>