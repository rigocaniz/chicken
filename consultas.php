<?php 
$data = json_decode( file_get_contents("php://input") );

//var_dump( $data );
include 'class/conexion.class.php';
include 'class/cliente.class.php';
include 'class/combo.class.php';
include 'class/consulta.class.php';
include 'class/medida.class.php';
include 'class/menu.class.php';
include 'class/orden.class.php';
include 'class/producto.class.php';
include 'class/receta.class.php';
include 'class/sesion.class.php';
include 'class/validar.class.php';
include 'class/funciones.php';


$sesion->setVariable( 'usuario', 'TEST' );
$sql = "CALL definirSesion( '{$sesion->getUsuario()}' );";
$conexion->query( $sql );

$datos = array();

switch ( $data->opcion )
{
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

	case 'cargarReceta':			// DATOS RECETA
		$receta = new Receta();
		echo json_encode( $receta->cargarReceta( $data->idMenu, $data->idProducto ) );
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
		echo json_encode( $combo->cargarComboPrecio( $data->idCombo, $data->idTipoServicio ) );
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
		echo json_encode( $combo->lstCombo() );
		break;

	case 'lstComboDetalle':			// CARGAR LISTA DE COMBOS DETALLE
		$combo = new Combo();
		echo json_encode( $combo->lstComboDetalle() );
		break;

	case 'lstComboPrecio':			// CARGAR LISTA DE COMBOS PRECIO
		$combo = new Combo();
		echo json_encode( $combo->lstComboPrecio() );
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
		echo json_encode( $menu->lstMenu() );
		break;

	case 'lstMenuPrecio':			// CARGAR LISTA PRECIOS MENU
		$menu = new Menu();
		echo json_encode( $menu->lstMenuPrecio() );
		break;
	
	case 'lstReceta':				// CARGAR LISTA RECETA
		$receta = new Receta();
		echo json_encode( $receta->lstReceta() );
		break;


	/////////////////////////
	//***** CONSULTA CATALOGOS
	////////////////////////
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

	/////////////////////////
	//***** CLIENTE
	/////////////////////////
	case 'guardarCliente':				// GUARDAR CLIENTE
		$cliente = new Cliente();
		echo json_encode( $cliente->guardarCliente( $data->cliente ) );
		break;

	case 'actualizarCliente':			// ACTUALIZAR CLIENTE
		$cliente = new Cliente();
		$cliente->actualizarCliente( $data->cliente ) ;
		echo json_encode( $cliente->getRespuesta() );
		break;

	case 'consultarCliente':			// CONSULTAR CLIENTE
		$cliente = new Cliente();
		$cliente->consultarCliente( $data->tipo, $data->cliente ) ;
		echo json_encode( $cliente->getRespuesta() );
		break;

	/////////////////////////
	//***** PRODUCTO
	/////////////////////////
	case 'consultaProducto':			// ACCION PRODUCTO: INSERT / UPDATE
		$producto = new Producto();
		echo json_encode( $producto->consultaProducto( $data->accion, $data->datos ) );
		break;

	case 'lstProductos':				// CONSULTAR LISTA DE PRODUCTOS
		$producto = new Producto();
		echo json_encode( $producto->lstProductos() );
		break;

	case 'consultaTipoProducto':		// ACCION PRODUCTO: INSERT / UPDATE
		$producto = new Producto();
		echo json_encode( $producto->consultaTipoProducto( $data->accion, $data->datos ) );
		break;

	/////////////////////////
	//***** MEDIDA
	/////////////////////////
	case 'consultaMedida':			// ACCION RECETA: INSERT / UPDATE
		$medida = new Medida();
		echo json_encode( $medida->consultaMedida( $data->accion, $data->datos ) );
		break;


	/////////////////////////
	//***** RECETAS
	/////////////////////////
	case 'consultaReceta':			// ACCION RECETA: INSERT / UPDATE
		$receta = new Receta();
		echo json_encode( $receta->consultaReceta( $data->accion, $data->datos ) );
		break;

	
	/////////////////////////
	//***** COMBO
	/////////////////////////
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
	case 'consultaMenu':			// ACCION MENU: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->consultaMenu( $data->accion, $data->datos ) );
		break;

	case 'consultaMenuPrecio':		// ACCION MENU PRECIO: INSERT / UPDATE
		$menu = new Menu();
		echo json_encode( $menu->consultaMenuPrecio( $data->accion, $data->datos ) );
		break;

	
	/////////////////////////
	/////////////////////////
	case 'guardarOrdenCliente':
		$orden = new Orden();
		$orden->guardarOrdenCliente( $data->detalleOrden ) ;
		echo json_encode( $orden->getRespuesta() );
		break;
	
	default:
		# code...
		break;
}

$conexion->close();

?>