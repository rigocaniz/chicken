app.controller('crtlMantenimiento', function( $scope , $http, $modal, $timeout ){

	$scope.menuTab         = 'menu';
	$scope.lstDestinoMenu  = [];
	$scope.lstTipoMenu     = [];
	$scope.lstTipoServicio = [];
	$scope.lstEstadosMenu  = [];
	$scope.lstCombos       = [];
	$scope.lstMenu         = [];
	$scope.menu            = {};
	$scope.combo           = {};

	if( document.getElementById("dial.adminMenu.html") )
		$scope.dialAdminMenu = $modal({scope: $scope,template:'dial.adminMenu.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.recetaMenu.html") )
		$scope.dialRecetaMenu = $modal({scope: $scope,template:'dial.recetaMenu.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.adminCombo.html") )
		$scope.dialAdminCombo = $modal({scope: $scope,template:'dial.adminCombo.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.detalleCombo.html") )
		$scope.dialDetalleCombo = $modal({scope: $scope,template:'dial.detalleCombo.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dialAdmin.producto.html") )
		$scope.dialAdministrar = $modal({scope: $scope,template:'dialAdmin.producto.html', show: false, backdrop: 'static'});
	if( document.getElementById("dial.editarPrecios.html") )
		$scope.dialEditarPrecios = $modal({scope: $scope,template:'dial.editarPrecios.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.editarPreciosC.html") )
		$scope.dialEditarPreciosC = $modal({scope: $scope,template:'dial.editarPreciosC.html', show: false, backdrop: 'static', keyboard: false});
	

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho )
	{
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		if( key == 117 ) {
			if( $scope.modalOpen( 'dialAdminCombo' ) )
				$scope.consultaCombo();

			else if( $scope.modalOpen( 'dialDetalleCombo' ) )
				$scope.actualizarLstDetalleCombo();
			
			else if( $scope.modalOpen( 'dialRecetaMenu' ) )
				$scope.actualizarLstReceta();

			else if( $scope.modalOpen( 'dialAdminMenu' ) )
				$scope.consultaMenu();

			else if( $scope.modalOpen( 'dialEditarPrecios' ) )
				$scope.actualizarPrecios();

			else if( $scope.modalOpen( 'dialEditarPreciosC' ) )
				$scope.actualizarPreciosCombo();
		}
		else if( altDerecho && ( key == 65 || key == 69 ) ) {
			if( !$scope.modalOpen() )
			{
				if( $scope.menuTab == 'menu' )
				{
					if ( key == 65 )
						$scope.agregarMenuCombo( 'menu' );
					else if( key == 69 )
						$scope.consultarMenusPrecios();
				} 
				else if( $scope.menuTab == 'combo' )
				{
					if( key == 65 )
						$scope.agregarMenuCombo( 'combo' );
					else if( key == 69 )
						$scope.consultarComboPrecios();
				}

			}
			else
				alertify.notify('Acción no válida', 'info', 3);

			$timeout(function(){
				$('#nit').focus();
			},175);
		}
	});


	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};


	$scope.$on('cargarLista', function( event, data ){
		if( data == 'menu' )
			$scope.verListaMenu();
		else if( data == 'combo' )
			$scope.verListaCombos();
		else if( data == 'superCombo' )
			$scope.verListaSuperCombos();
	});

	$scope.lstPorPagina = [
		{limite: "25" },
		{limite: "50" },
		{limite: "75" },
		{limite: "100" }
	];

	/*
	$scope.filtro = {
		filter : { filter: 'idMedida', value : 8 },
		order  : { by: 'idMedia', order: 'ASC' },
		limit  : 25,
		page   : 1
	};
	*/
	$scope.filter = {
		pagina   : 1,
		limite   : "25",
		orden    : 'ASC',
		busqueda : ''
	};
	// ACTUALIZAR LST RECETA
	$scope.actualizarLstReceta = function() {
		var objMenu = $scope.objMenu;

		if( !(objMenu.lstRecetaMenu && objMenu.lstRecetaMenu.length > 0) )
			alertify.notify( 'No hay productos ingresados en la lista de recetas', 'warning', 5 );
		else
		{
			$http.post('consultas.php',{
				opcion : 'actualizarLstReceta',
				accion : 'update',
				datos  : objMenu.lstRecetaMenu
			})
			.success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == 'success' ) {
					$scope.dialRecetaMenu.hide();
				}
			});
		}
	};


	// ELIMINAR PRODUCTO DE LA RECETA
	$scope.eliminarProdReceta = function( idMenu, idProducto )
	{
		$http.post('consultas.php',{
			opcion : 'consultaReceta',
			accion : 'delete',
			datos  : {
				idMenu     : idMenu,
				idProducto : idProducto
			}
		})
		.success(function(data){
			console.log(data);
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );

			if ( data.respuesta == 'success' ) {
				$scope.lstRecetaMenu( $scope.objMenu.idMenu, false );
			}
		});
	};

	// BUSCAR MENUS
	$scope.buscarMenu = function( nombreMenu ){
		if( nombreMenu.length && !$scope.menuD.seleccionado ) {
			$http.post('consultas.php',{opcion: 'buscarMenu', nombreMenu: nombreMenu})
			.success(function(data){
				//console.log('buscarMenu::: ',data);
				$scope.lstBusqueda = data;
			});
		}
		else
			$scope.lstBusqueda = [];
	};


	$scope.agregarMenuDetalleCombo = function(){
		var menuD = $scope.menuD;

		if( !(menuD.idMenu) )
			alertify.notify( 'Ingrese una cantidad entera', 'danger', 5 );
		
		else if( !(menuD.idMenu > 0) )
			alertify.notify( 'El código del Menu no es válido', 'danger', 5 );
		
		else if( !(menuD.cantidad && menuD.cantidad > 0) )
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );
		
		else{
			$http.post('consultas.php',{
				opcion : 'consultaComboDetalle',
				accion : 'insert',
				datos  : $scope.menuD
			})
			.success(function(data){
				//console.log(data);
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if( data.respuesta == 'success' ){
					$scope.resetValores( 'comboDetalle' );
					$scope.lstComboDetalle( $scope.objCombo.idCombo, false );
				}
			});
		}
	};

	// ACTUALIZAR LST RECETA
	$scope.actualizarLstDetalleCombo = function() {
		var objCombo = $scope.objCombo;
		//console.log( objCombo );
		if( !(objCombo.lstDetalleCombo && objCombo.lstDetalleCombo.length > 0) )
			alertify.notify( 'No hay productos ingresados en la lista de recetas', 'warning', 5 );
		else
		{
			$http.post('consultas.php',{
				opcion : 'actualizarLstDetalleCombo',
				accion : 'update',
				datos  : objCombo.lstDetalleCombo
			})
			.success(function(data){
				//console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == 'success' )
					$scope.dialDetalleCombo.hide();
			});
		}
	};

	$scope.eliminarDetalleCombo = function( idCombo, idMenu ){
		$http.post('consultas.php',{
			opcion : 'consultaComboDetalle',
			accion : 'delete',
			datos  : {
				idCombo : idCombo,
				idMenu  : idMenu
			}
		})
		.success(function(data){
			//console.log(data);
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );

			if ( data.respuesta == 'success' )
				$scope.lstComboDetalle( $scope.objCombo.idCombo, false );
		});
	};


	// CONSULTAR PRECIOS DE LOS MENUS
	$scope.consultarMenusPrecios = function()
	{
		$http.post('consultas.php',{opcion: 'consultarMenusPrecios'})
		.success(function(data){
			//console.log( data );
			$scope.lstMenusPrecios = data || [];
			if( data.length )
				$scope.dialEditarPrecios.show();
		});
	};

	// CONSULTAR PRECIOS DE LOS COMBOS 
	$scope.consultarComboPrecios = function()
	{
		$http.post('consultas.php',{opcion: 'consultarComboPrecios'})
		.success(function(data){
			//console.log( data );
			$scope.lstCombosPrecios = data || [];
			if( data.length )
				$scope.dialEditarPreciosC.show();
		});
	};

	// CARGAR DETALLE COMBO
	$scope.objCombo = {};
	$scope.cargarDetalleCombo = function( combo )
	{
		$scope.objCombo = combo;
		if( $scope.objCombo.idCombo > 0 ){
			$scope.lstComboDetalle( $scope.objCombo.idCombo, true );
		}
	};

	// CONSULTAR DETALLE COMBO
	$scope.lstComboDetalle = function( idCombo, abrirModal )
	{
		$http.post('consultas.php',{opcion: 'lstComboDetalle', idCombo: idCombo})
		.success(function(data){
			//console.log( data );
			$scope.objCombo.lstDetalleCombo = data;
			if( abrirModal )
				$scope.dialDetalleCombo.show();
		});
	};

	// CARGAR RECETA MENU
	$scope.cargarRecetaMenu = function( menu ) {
		$scope.objMenu = angular.copy( menu );
		if( $scope.objMenu.idMenu > 0 )
			$scope.lstRecetaMenu( $scope.objMenu.idMenu, true );
	};
	

	// CONSULTAR LISTA RECETA MENU
	$scope.lstRecetaMenu = function( idMenu, abrirModal ) {
		$http.post('consultas.php',{opcion: 'lstRecetaMenu', idMenu: idMenu})
		.success(function(data){
			$scope.objMenu.lstRecetaMenu = data;
			if( abrirModal )
				$scope.dialRecetaMenu.show();
		});
	};

	$scope.agregarIngresoProducto = function() {
		var prod = $scope.prod;

		if( !(prod.idProducto && prod.idProducto > 0) )
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );

		else if( !(prod.cantidad && prod.cantidad > 0) )
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );

		else{
			$http.post('consultas.php',{
				opcion : 'consultaReceta',
				accion : 'insert',
				datos  : $scope.prod
			})
			.success(function(data){
				//console.log(data);
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if( data.respuesta == 'success' ){
					$scope.resetValores( 'producto' );
					$scope.lstRecetaMenu( $scope.objMenu.idMenu, false );
				}
			});
		}
	};


	$scope.idxElSeleccionado = -1;
	$scope.seleccionKeyElemento = function( key, elemento ){
		//console.log( key, ":::", $scope.idxElSeleccionado );
		// CODIGO ENTER
		if( key == 13 )
		{
			if( !$scope.lstBusqueda.length )
				alertify.notify( 'No se encontraron resultados', 'info', 5 );

			else if( $scope.lstBusqueda.length == 1 )
				$scope.seleccionarElemento( $scope.lstBusqueda[ 0 ], elemento );

			else if( $scope.idxElSeleccionado != -1 )
				$scope.seleccionarElemento( $scope.lstBusqueda[ $scope.idxElSeleccionado ], elemento );	
		}

		// CODIGO UP
		else if( key == 38 )
		{
			if( $scope.lstBusqueda.length && $scope.idxElSeleccionado > 0 )
				$scope.idxElSeleccionado--;

			else if( $scope.idxElSeleccionado == 0 )
				$scope.idxElSeleccionado = $scope.lstBusqueda.length - 1;
		}
		
		// CODIGO DOWN
		else if( key == 40 ){

			if( $scope.lstBusqueda.length && $scope.idxElSeleccionado + 1 < $scope.lstBusqueda.length )
				$scope.idxElSeleccionado++;

			else if( $scope.lstBusqueda.length && $scope.idxElSeleccionado + 1 )
				$scope.idxElSeleccionado = 0;
		}
	};


	// SELECCIONAR PRODUCTO
	$scope.seleccionarElemento = function( valores, elemento ) {
		//console.log( valores, ' ::: ', elemento );
		if( elemento == 'producto' )
		{
			if( !(valores.idProducto && valores.idProducto > 0) )
				alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
			else
			{
				$scope.prod = {
					idMenu       : $scope.objMenu.idMenu,
					idProducto   : valores.idProducto,
					producto     : valores.producto,
					medida       : valores.medida,
					tipoProducto : valores.tipoProducto,
					observacion  : '',
					cantidad     : null,
					seleccionado : true
				};

				$('#cantidad').focus();
			}
		}
		else if( elemento == 'menu' )
		{
			if( !(valores.idMenu && valores.idMenu > 0) )
				alertify.notify( 'El código del Menu no es válido', 'danger', 5 );
			else
			{
				$scope.menuD = {
					idCombo      : $scope.objCombo.idCombo,
					idMenu       : valores.idMenu,
					menu         : valores.menu,
					estadoMenu   : valores.estadoMenu,
					cantidad     : null,
					seleccionado : true
				};

				$('#cantidad').focus();
			}
		}
		$scope.lstBusqueda = [];
	};


	// BUSCAR PRODUCTOS
	$scope.buscarProducto = function( nombreProducto ){
		//console.log( $scope.prod );
		if( nombreProducto.length && !$scope.prod.seleccionado )
		{
			$http.post('consultas.php',{opcion: 'buscarProducto', nombreProducto: nombreProducto})
			.success(function(data){
				console.log('buscarProducto::: ',data);
				$scope.lstBusqueda = data;
			});
		}
		else
			$scope.lstBusqueda = []
	};

	$scope.realizarBusqueda = function(){
		if( $scope.menuTab == 'menu' )
			$scope.verListaMenu();

		else if( $scope.menuTab == 'combo' )
			$scope.verListaCombos();
	};

	$scope.cargarPaginacion = function( pagina ){
		$scope.filter.pagina = pagina;
		if( $scope.menuTab == 'menu' )
			$scope.verListaMenu();

		else if( $scope.menuTab == 'combo' )
			$scope.verListaCombos();

		else if( $scope.menuTab == 'superCombo' )
			$scope.inventario();
	};


	($scope.inicio = function(){
        $http.post('consultas.php',{
            opcion : 'inicioAdmin'
        }).success(function(data){
//        	console.log( 'inicioAdmin', data );
            $scope.lstDestinoMenu  = data.lstDestinoMenu || [];
            $scope.lstTipoMenu     = data.lsTipoMenu || [];
            $scope.lstTipoServicio = data.lstTiposServicio || [];
            $scope.lstEstadosMenu  = data.lstEstadosMenu || [];
        })
	})();


	// CONSULTAR PRECIOS MENU
	$scope.cargarLstPreciosMenu = function( idMenu ){
		$http.post('consultas.php',{opcion: 'cargarLstPreciosMenu', idMenu: idMenu})
		.success(function(data){
			$scope.menu.lstPrecios = data;
		});
	};

	// CONSULTAR PRECIOS COMBO
	$scope.cargarLstPreciosCombo = function( idCombo ){
		$http.post('consultas.php',{opcion: 'lstComboPrecio', idCombo: idCombo})
		.success(function(data){
			$scope.combo.lstPrecios = data;
		});
	};

	$scope.lstPaginacion = [];
	$scope.generarPaginacion = function( totalPaginas ){
		$scope.lstPaginacion = [];
		for (var i = 1; i <= totalPaginas; i++) {
			$scope.lstPaginacion.push({
				noPagina : i
			});
		}
	};

	// VER LISTA DE MENUS
	$scope.verListaMenu = function(){
		$http.post('consultas.php',{
			opcion : 'getListaMenus',
			filtro : $scope.filter
		}).success(function(data){
//			console.log( 'listaMenu: ', data );
			$scope.lstMenu = data.lstMenus || [];
			$scope.generarPaginacion( data.totalPaginas );
		})
	};
	

	$scope.verListaMenu();
	// VER LISTA DE COMBOS
	$scope.verListaCombos = function(){
		$http.post('consultas.php',{
			opcion : 'getListaCombos',
			filtro : $scope.filter
		}).success(function(data){
			//console.log('lstCombos: ', data);
			$scope.lstCombos = data.lstCombos || [];
			$scope.generarPaginacion( data.totalPaginas );
		})
	};


	$scope.actualizarMenuCombo = function( tipo, data )
	{
		//console.log( 'tipo: ', tipo, ' data: ', data );
		$scope.accion = 'update';
		if( tipo == 'menu' ){
			$scope.menu = angular.copy( data );
			$scope.menu.tabMenu = 'precios';
			$scope.cargarLstPreciosMenu( $scope.menu.idMenu );
			$timeout(function(){
				$scope.dialAdminMenu.show();
			});
		}
		else if( tipo == 'combo' ){
			$scope.combo = angular.copy( data );
			$scope.combo.tabMenu = 'precios';
			$scope.cargarLstPreciosCombo( $scope.combo.idCombo );
			$timeout(function(){
				$scope.dialAdminCombo.show();
			});
		}
	};

	$scope.agregaReceta = function() {
		var prod = $scope.prod, encontrado = false;

		if( !(prod.cantidad && prod.cantidad > 0) )
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );

		else{
			for (var i = 0; i < $scope.menu.lstRecetaMenu.length; i++) {
				if( $scope.menu.lstRecetaMenu[ i ].idProducto == $scope.prod.idProducto ){
					alertify.notify( 'El Producto ya se encuentra agregado', 'info', 5 );
					encontrado = true;
					break;
				}
			}
		}

		if( !encontrado ){
			$scope.menu.lstRecetaMenu.push( $scope.prod );
			$scope.resetValores( 'producto' );
		}
	};

	$scope.eliminarProductoReceta = function( index ){
		$scope.menu.lstRecetaMenu.splice( index, 1 );
	};

	// REGISTRAR MENU
	$scope.consultaMenu = function()
	{
		var menu = $scope.menu;
		
		if( $scope.accion == 'update' && !(menu.idMenu && menu.idMenu > 0) )
			alertify.notify( 'No. de menú no válido', 'info', 3 );

		else if( !( menu.idEstadoMenu && menu.idEstadoMenu > 0 ) )
			alertify.notify( 'Seleccione el estado del Menú', 'info', 4 );

		else if( !( menu.menu && menu.menu.length > 3 ) )
			alertify.notify( 'El nombre del menú debe ser mayor a 3 caracteres', 'info', 5 );
		else if( !( menu.codigo && menu.codigo > 0 ) )
			alertify.notify( 'Ingrese el código del Menu', 'info', 3 );

		else if( !( menu.idDestinoMenu && menu.idDestinoMenu > 0 ) )
			alertify.notify( 'Seleccione el destino del Menú', 'info', 4 );
		
		else if( !( menu.idTipoMenu && menu.idTipoMenu > 0 ) )
			alertify.notify( 'Seleccione el tipo de Menú', 'info', 3 );

		else if( !( menu.tiempoAlerta && menu.tiempoAlerta > 0 ) )
			alertify.notify( 'El tiempo límite debe ser mayor a 0', 'info', 4 );

		else if( !$scope.validarPreciosCM( menu, menu.menu ) )
		{
			$http.post('consultas.php',{
				opcion : "consultaMenu",
				accion : $scope.accion,
				datos  : $scope.menu
			}).success(function(data){
				//console.log(  data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.menu = {};
					$scope.dialAdminMenu.hide();
					$scope.verListaMenu();
				}
			})
		}
	};

	// GUARDAR // ACTUALIZAR PRECIOS COMBO
	$scope.actualizarPreciosCombo = function()
	{
		var error = false;

		for (var i = 0; i < $scope.lstCombosPrecios.length; i++) {
			var combo = $scope.lstCombosPrecios[ i ];
			if( $scope.validarPreciosCM( combo, combo.combo ) ){
				error = true;
				break;
			}
		}
		
		if( !error )
		{
			$http.post('consultas.php',{
				opcion : "actualizarPreciosCombo",
				datos  : $scope.lstCombosPrecios
			}).success(function(data){
				//console.log( data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.data > 0 )
					$scope.dialEditarPreciosC.hide();
			});
		}
	};

	// GUARDAR // ACTUALIZAR PRECIOS MENU
	$scope.actualizarPrecios = function()
	{
		var error = false;

		for (var i = 0; i < $scope.lstMenusPrecios.length; i++) {
			var menuPrecio = $scope.lstMenusPrecios[ i ];
			if( $scope.validarPreciosCM( menuPrecio, menuPrecio.menu ) ){
				error = true;
				break;
			}
		}
		
		if( !error )
		{
			$http.post('consultas.php',{
				opcion : "actualizarPrecios",
				datos  : $scope.lstMenusPrecios
			}).success(function(data){
				//console.log( data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.data > 0 )
					$scope.dialEditarPrecios.hide();
			});
		}
	};

	// VALIDAR PRECIOS DE MENU
	$scope.validarPreciosCM = function( menuCombo, descripcion )
	{
		var error = false;
		if( descripcion.length )
			descripcion = ' <b>=> ' + descripcion + '</b>';

		for (var i = 0; i < menuCombo.lstPrecios.length; i++) {
			if( !( menuCombo.lstPrecios[ i ].precio && menuCombo.lstPrecios[ i ].precio >= 0 ) ){
				alertify.notify( 'Ingrese un precio válido en servicio ' + menuCombo.lstPrecios[ i ].tipoServicio + descripcion, 'warning', 5 );
				error = true;
				break;
			}
		}

		return error;
	};

	// RESETEAR VALORES
	$scope.resetValores = function( accion ){
		$scope.accion == 'insert';
		$scope.filter.busqueda = '';

		if( accion == 'menu' )
		{
			$scope.menu = {
				'idEstadoMenu'  : 1,
				'menu'          : '',
				'idDestinoMenu' : 1,
				'idTipoMenu'    : 1,
				'tiempoAlerta'  : 0,
				'descripcion'   : '',
				'imagen'        : '',
				'subirImagen'   : true,
				'tabMenu'       : 'precios',
				'lstPrecios'    : angular.copy( $scope.lstTipoServicio ),
				'lstRecetaMenu' : []
			};
		}
		else if( accion == 'combo' )
		{
			$scope.combo = {
				'idEstadoMenu'   : 1,
				'combo'          : '',
				'descripcion'    : '',
				'subirImagen'    : true,
				'imagen'         : '',
				'tabMenu'        : 'precios',
				'lstPrecios'     : angular.copy( $scope.lstTipoServicio ),
				'lstMenusCombo' : []
			}
		}
		else if( accion == 'producto' )
		{
			$scope.lstBusqueda = [];
			$scope.prod = {
				idMenu         : null,
				idProducto     : null,
				nombreProducto : '',
				medida         : '',
				cantidad       : null,
				observacion    : '',
				seleccionado   : false
			};
			$timeout(function(){
				$( '#producto' ).focus();
			}, 100);
		}
		else if( accion == 'comboDetalle' )
		{
			$scope.menuD = {
				idCombo      : null,
				idMenu       : null,
				menu         : '',
				cantidad     : null,
				seleccionado : false
			};
			$timeout(function(){
				$( '#menu' ).focus();
			}, 100);
		}
		else if( accion == 'receta' )
		{
			$scope.objMenu     = {};
			$scope.lstBusqueda = [];
			$scope.resetValores( 'producto' );
		}
	};

	// RESETEAR PRODUCTO
	$scope.resetValores( 'receta' );

	$scope.agregarMenuCombo = function( tipo )
	{
		$scope.accion = 'insert';
		$scope.resetValores( tipo );
		if( tipo == 'menu' ){
			$scope.resetValores( 'producto' );
			$scope.dialAdminMenu.show();
			$timeout(function(){
				$('#nombreMenu').focus();
			}, 175);
		}
		else if( tipo == 'combo' ){
			$scope.resetValores( 'comboDetalle' );
			$scope.dialAdminCombo.show();
			$timeout(function(){
				$('#nombreCombo').focus();
			}, 175);
		}
	};


	$scope.agregarMenuC = function(){
		var menuD = $scope.menuD;

		if( !(menuD.idMenu) )
			alertify.notify( 'Ingrese una cantidad entera', 'danger', 5 );
		
		else if( !(menuD.idMenu > 0) )
			alertify.notify( 'El código del Menu no es válido', 'danger', 5 );
		
		else if( !(menuD.cantidad && menuD.cantidad > 0) )
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );
		
		else{
			$scope.combo.lstMenusCombo.push( $scope.menuD );
			$scope.resetValores( 'comboDetalle' );
		}
	};

	$scope.eliminarMenuCombo = function( index ){
		$scope.combo.lstMenusCombo.splice( index, 1 );
	};

	// REGISTRAR COMBO
	$scope.consultaCombo = function()
	{
		var combo = $scope.combo, error = false;

		if( $scope.accion == 'update' && !(combo.idCombo && combo.idCombo > 0) ){
			error = true;
			alertify.notify( 'No. de combo no válido, vuelve a seleccionarlo', 'info', 4 );
		}
		else if( !( combo.idEstadoMenu && combo.idEstadoMenu > 0 )  ){
			error = true;
			alertify.notify( 'Seleccione el estado del combo', 'info', 4 );	
		}
		else if( !( combo.combo && combo.combo.length > 3 ) ){
			error = true;
			alertify.notify( 'El nombre del combo debe ser mayor a 3 caracteres', 'info', 5 );		
		}
		else if( !( combo.codigo && combo.codigo > 0 ) ){
			error = true;
			alertify.notify( 'Ingrese el código del combo', 'info', 4 );		
		}
		else if( !$scope.validarPreciosCM( combo, combo.combo ) )
		{
			$http.post('consultas.php',{
				opcion : "consultaCombo",
				accion : $scope.accion,
				datos  : $scope.combo
			}).success(function(data){
				//console.log( data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.verListaCombos();
					$scope.dialAdminCombo.hide();
					if( combo.subirImagen )
						$scope.$parent.asignarValorImagen( data.data, 'combo' );

					$scope.resetValores( 'combo' );
				}
			});
		}
	};


	($scope.resetValoresProducto = function( accion ) {
		$scope.accionProducto 	  = 'insert';
		$scope.buscarTipoProducto = '';
		$scope.buscarMedida       = '';
		$scope.producto = {
			'producto'       : '',
			'idUbicacion'    : 1,
			'idTipoProducto' : null,
			'idMedida'       : null,
			'perecedero'     : true,
			'cantidadMinima' : null,
			'cantidadMaxima' : null,
			'disponibilidad' : '',
			'importante'     : true
		};
	})();

	($scope.cargarInicio = function(){
		$http.post('consultas.php', {opcion: 'inicioInventario'})
		.success(function(data){
			$scope.lstMedidas      = data.catMedidas || [];
			$scope.lstTipoProducto = data.catTipoProducto || [];
			$scope.lstUbicacion    = data.catUbicacion || [];
		});
	})();

	// INSERTAR / ACTUALIZAR PRODUCTO
	$scope.consultaProducto = function(){
		if ( $scope.$parent.loading )
			return false;

		var producto = $scope.producto, accion = $scope.accionProducto;

		if( accion == 'update' && !(producto.idProducto > 0) )
			alertify.notify( 'No. de producto no definido', 'warning', 5 );
		
		else if( !(producto.producto && producto.producto.length >= 3) )
			alertify.notify( 'El nombre del producto debe ser mayor a 3 caracteres', 'warning', 5 );

		else if( !(producto.idUbicacion && producto.idUbicacion > 0) )
			alertify.notify( 'Seleccione la ubicacion del producto', 'warning', 5 );
		
		else if( !(producto.idTipoProducto && producto.idTipoProducto > 0) )
			alertify.notify( 'Seleccione el tipo de producto', 'warning', 5 );	
		
		else if( !(producto.idMedida && producto.idMedida > 0) )
			alertify.notify( 'Seleccione la medida', 'warning', 5 );	
	
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) )
			alertify.notify( 'La cantidad mínima debe ser mayor a 0', 'warning', 5 );	
		
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) )
			alertify.notify( 'La cantidad máxima debe ser mayor a 0', 'warning', 5 );	
		
		else if( !(producto.disponibilidad && producto.disponibilidad > 0) )
			alertify.notify( 'La disponibilidad debe ser mayor a 0', 'warning', 5 );	
		
		else{

			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaProducto",
				accion : accion,
				datos  : $scope.producto
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				$scope.$parent.hideLoading();

				if ( data.respuesta == "success" ) {
					$scope.dialAdministrar.hide();
					$scope.dialRecetaMenu.show();
					$scope.resetValoresProducto();
				}
			})
		}
	};
});