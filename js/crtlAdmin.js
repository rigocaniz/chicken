app.controller('crtlAdmin', function( $scope , $http, $modal, $timeout ){

	$scope.menuTab         = 'combo';
	$scope.accion          = 'insert';
	$scope.lstDestinoMenu  = [];
	$scope.lstTipoMenu     = [];
	$scope.lstTipoServicio = [];
	$scope.lstEstadosMenu  = [];
	$scope.lstCombos       = [];
	$scope.lstMenu         = [];
	$scope.menu            = {};
	$scope.combo           = {};

	$scope.dialAdminMenu    = $modal({scope: $scope,template:'dial.adminMenu.html', show: false, backdrop: 'static', keyboard: false});
	$scope.dialRecetaMenu   = $modal({scope: $scope,template:'dial.recetaMenu.html', show: false, backdrop: 'static', keyboard: false});

	$scope.dialAdminCombo   = $modal({scope: $scope,template:'dial.adminCombo.html', show: false, backdrop: 'static', keyboard: false});
	$scope.dialDetalleCombo = $modal({scope: $scope,template:'dial.detalleCombo.html', show: false, backdrop: 'static', keyboard: false});

	$scope.$on('cargarLista', function( event, data ){
		console.log( ":::", event, ":::", data );
		if( data == 'menu' )
			$scope.verListaMenu();
		else if( data == 'combo' )
			$scope.verListaCombos();
		else if( data == 'superCombo' )
			$scope.verListaSuperCombos();
	});

	$scope.filtro = {
		filter : { filter: 'idMedida', value : 8 },
		order  : { by: 'idMedia', order: 'ASC' },
		limit  : 25,
		page   : 1
	};

	$scope.filter = {
		pagina: 1,
		limite: 10,
		orden: 'ASC'
	};

	$scope.actualizarLstReceta = function()
	{
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
			}).error(function(data){
				console.log(data);
			});
		}
		
	}

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
		}).error(function(data){
			console.log(data);
		});
	};

	// CARGAR DETALLE COMBO
	$scope.objCombo = {};
	$scope.cargarDetalleCombo = function( combo )
	{
		//console.log( 'combo::: ', combo );
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
			console.log( data );
			$scope.objCombo.lstDetalleCombo = data;
			if( abrirModal )
				$scope.dialDetalleCombo.show();
		});
	};


	
	// CARGAR RECETA MENU
	$scope.objMenu = {};
	$scope.cargarRecetaMenu = function( menu )
	{
		console.log( 'menu::: ', menu );
		$scope.objMenu = menu;
		if( $scope.objMenu.idMenu > 0 ){
			$scope.lstRecetaMenu( $scope.objMenu.idMenu, true );
		}
	};
	
	$scope.lstRecetaMenu = function( idMenu, abrirModal )
	{
		$http.post('consultas.php',{opcion: 'lstRecetaMenu', idMenu: idMenu})
		.success(function(data){
			$scope.objMenu.lstRecetaMenu = data;
			if( abrirModal )
				$scope.dialRecetaMenu.show();
		});
	};

	
	$scope.prod = {
		idMenu         : null,
		idProducto     : null,
		nombreProducto : '',
		medida         : '',
		cantidad       : null,
		observacion    : '',
		seleccionado   : false
	};
	$scope.agregarIngresoProducto = function()
	{
		var prod = $scope.prod;

		if( !(prod.idProducto && prod.idProducto > 0) )
		{
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
		}
		else if( !(prod.cantidad && prod.cantidad > 0) ){
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );
		}
		else{
			$http.post('consultas.php',{
				opcion : 'consultaReceta',
				accion : 'insert',
				datos  : $scope.prod
			})
			.success(function(data){
				console.log(data);
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if( data.respuesta == 'success' ){
					$scope.resetValores( 'producto' );
					$scope.lstRecetaMenu( $scope.objMenu.idMenu, false );
				}
			});
		}
	};


	$scope.idxProducto = -1;
	$scope.seleccionKeyProducto = function( key ){
		console.log( key, ":::", $scope.idxProducto );

		// CODIGO ENTER
		if( key == 13 ){

			if( !$scope.lstProductos.length )
				alertify.notify( 'No se encontro el producto ingresaoo', 'info', 5 );

			else if( $scope.lstProductos.length == 1 )
				$scope.seleccionarProducto( $scope.lstProductos[ 0 ] );

			else if( $scope.idxProducto != -1 )
				$scope.seleccionarProducto( $scope.lstProductos[ $scope.idxProducto ] );
			
		}

		// CODIGO UP
		else if( key == 38 ){

			if( $scope.lstProductos.length && $scope.idxProducto > 0 )
				$scope.idxProducto--;

			else if( $scope.idxProducto == 0 )
				$scope.idxProducto = $scope.lstProductos.length - 1;
		}
		
		// CODIGO DOWN
		else if( key == 40 ){

			if( $scope.lstProductos.length && $scope.idxProducto + 1 < $scope.lstProductos.length )
				$scope.idxProducto++;

			else if( $scope.lstProductos.length && $scope.idxProducto + 1 )
				$scope.idxProducto = 0;
		}
	};


	// SELECCIONAR PRODUCTO
	$scope.seleccionarProducto = function( producto )
	{
		if( !(producto.idProducto && producto.idProducto > 0) )
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
		else{
			$scope.prod = {
				idMenu       : $scope.objMenu.idMenu,
				idProducto   : producto.idProducto,
				producto     : producto.producto,
				medida       : producto.medida,
				observacion  : '',
				cantidad     : null,
				seleccionado : true
			};

			$('#cantidad').focus();

			$scope.lstProductos = [];
		}
	};


	// BUSCAR PRODUCTOS
	$scope.lstProductos = [];
	$scope.buscarProducto = function( nombreProducto ){
		if( nombreProducto.length && !$scope.prod.seleccionado )
		{
			$http.post('consultas.php',{opcion: 'buscarProducto', nombreProducto: nombreProducto})
			.success(function(data){
				console.log('buscarProducto::: ',data);
				$scope.lstProductos = data;
			}).error(function(data){
				console.log(data);
			});
		}
		else
			$scope.lstProductos = []

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
            $scope.lstDestinoMenu  = data.lstDestinoMenu || [];
            $scope.lstTipoMenu     = data.lsTipoMenu || [];
            $scope.lstTipoServicio = data.lstTiposServicio || [];
            $scope.lstEstadosMenu  = data.lstEstadosMenu || [];
        })
	})();

	// CONSULTAR PRECIOS MENU
	$scope.cargarLstPreciosMenu = function( idMenu )
	{
		$http.post('consultas.php',{opcion: 'cargarMenuPrecio', idMenu: idMenu})
		.success(function(data){
			$scope.menu.lstPrecios = data;
		});
	};

	// CONSULTAR PRECIOS COMBO
	$scope.cargarLstPreciosCombo = function( idCombo )
	{
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
			console.log( 'listaMenu: ', data );
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
			console.log('lstCombos: ', data);
			$scope.lstCombos = data.lstCombos || [];
			$scope.generarPaginacion( data.totalPaginas );
		})
	};


	$scope.actualizarMenuCombo = function( tipo, data )
	{
		console.log( 'tipo: ', tipo, ' data: ', data );

		$scope.accion = 'update';
		if( tipo == 'menu' ){
			$scope.menu = data;
			$scope.cargarLstPreciosMenu( $scope.menu.idMenu );
			$timeout(function(){
				$scope.dialAdminMenu.show();
			});
		}

		else if( tipo == 'combo' ){
			$scope.combo = data;
			$scope.cargarLstPreciosCombo( $scope.combo.idCombo );
			$timeout(function(){
				$scope.dialAdminCombo.show();
			});
		}
	};

	$scope.agregarMenuCombo = function( tipo ){
		console.log( 'tipo: ', tipo );
		
		$scope.accion = 'insert';
		if( tipo == 'menu' ){
			$scope.menu = {
				'idEstadoMenu'  : 1,
				'menu'          : '',
				'idDestinoMenu' : 1,
				'idTipoMenu'    : 1,
				'descripcion'   : '',
				'imagen'        : '',
				'subirImagen'   : true,
				'lstPrecios'    : angular.copy( $scope.lstTipoServicio )
			};

			$scope.dialAdminMenu.show();
		}

		else if( tipo == 'combo' ){
			$scope.combo = {
				'idEstadoMenu' : 1,
				'combo'        : '',
				'descripcion'  : '',
				'subirImagen'  : true,
				'imagen'       : '',
				'lstPrecios'   : angular.copy( $scope.lstTipoServicio )
			}
			$scope.dialAdminCombo.show();
		}

	};
	
	// AGREGAR PRECIOS SEGÚN TIPO SERVICIO
	$scope.precios = {
		idTipoServicio : null,
		precio         : 0
	};

	$scope.agregaPrecio = function( tipo ){
		var precio = $scope.precios;
		console.log( $scope.menu );
		var error = false;

		if( tipo == 'menu' )
		{

			if( !(precio.idTipoServicio && precio.idTipoServicio > 0 ) )
			{
				alertify.notify( 'Seleccione el tipo de servicio', 'warning', 5 );
			}
			else if( !(precio.precio && precio.precio > 0 ) ){
				alertify.notify( 'El precio debe ser mayor a 0', 'warning', 5 );
			}
			else{
				$scope.menu.lstPrecios.push({
					idTipoServicio : precio.idTipoServicio,
					precio         : parseFloat( precio.precio ),
					editar         : false
				});
				alertify.notify( 'Agregado', 'success', 4 );

				$scope.precios = {
					idTipoServicio : null,
					precio         : 0
				};
			}

		}
		
	};


	// REGISTRAR MENU
	$scope.consultaMenu = function(){
		var menu = $scope.menu, error = false;
			console.log($scope.menu);
		if( $scope.accion == 'update' && !(menu.idMenu && menu.idMenu > 0) ){
			error = true;
			alertify.notify( 'No. de menú no válido', 'info', 5 );
		}
		else if( !( menu.idEstadoMenu && menu.idEstadoMenu > 0 )  ){
			error = true;
			alertify.notify( 'Seleccione el estado del Menú', 'info', 5 );	
		}
		else if( !( menu.menu && menu.menu.length > 3 ) ){
			error = true;
			alertify.notify( 'El nombre del menú debe ser mayor a 3 caracteres', 'info', 5 );		
		}
		else if( !( menu.idDestinoMenu && menu.idDestinoMenu > 0 ) ){
			error = true;
			alertify.notify( 'Seleccione el destino del Menú', 'info', 5 );	
		}
		else if( !( menu.idTipoMenu && menu.idTipoMenu > 0 ) ){
			error = true;
			alertify.notify( 'Seleccione el tipo de Menú', 'info', 5 );	
		}
		else if( !( menu.descripcion && menu.descripcion.length > 15 ) ){
			error = true;
			alertify.notify( 'La descripción del menú debe ser mayor a 15 caracteres', 'info', 5 );		
		}
		else{
			for (var i = 0; i < menu.lstPrecios.length; i++) {
				if( !( menu.lstPrecios[ i ].precio && menu.lstPrecios[ i ].precio >= 0 ) ){
					alertify.notify( 'Ingrese un precio válido en el servicio ' + menu.lstPrecios[ i ].tipoServicio, 'warning', 4000 );
					error = true;
					break;
				}
			}
		}
		
		if( !error ){
			$http.post('consultas.php',{
				opcion : "consultaMenu",
				accion : $scope.accion,
				datos  : $scope.menu
			}).success(function(data){
				console.log(  data );
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


	$scope.resetValores = function( accion ){
		console.log( accion );
		$scope.accion == 'insert';

		if( accion == 'menu' )
		{
			$scope.menu = {
				'idEstadoMenu'  : 1,
				'menu'          : '',
				'idDestinoMenu' : 1,
				'idTipoMenu'    : 1,
				'descripcion'   : '',
				'imagen'        : '',
				'subirImagen'   : true,
				'lstPrecios'    : []
			};
		}
		else if( accion == 'combo' )
		{
			$scope.combo = {
				'idEstadoMenu' : 1,
				'combo'        : '',
				'descripcion'  : '',
				'subirImagen'  : true,
				'imagen'       : '',
			}

		}
		else if( accion == 'producto' )
		{
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

		else if( accion == 'receta' )
		{
			$scope.objMenu      = {};
			$scope.lstProductos = [];
			$scope.resetValores( 'producto' );
		}
	};

	$scope.registraCombo = function(){
		var combo = $scope.combo, error = false;

		if( $scope.accion == 'update' && !(combo.idCombo && combo.idCombo > 0) ){
			error = true;
			alertify.notify( 'No. de combo no válido, vuelve a seleccionarlo', 'info', 5 );
		}
		else if( !( combo.idEstadoMenu && combo.idEstadoMenu > 0 )  ){
			error = true;
			alertify.notify( 'Seleccione el estado del combo', 'info', 5 );	
		}
		else if( !( combo.combo && combo.combo.length > 3 ) ){
			error = true;
			alertify.notify( 'El nombre del combo debe ser mayor a 3 caracteres', 'info', 5 );		
		}
		else if( !( combo.descripcion && combo.descripcion.length > 15 ) ){
			error = true;
			alertify.notify( 'La descripción del combo debe ser mayor a 15 caracteres', 'info', 5 );		
		}
		else{
			for (var i = 0; i < combo.lstPrecios.length; i++) {
				if( !( combo.lstPrecios[ i ].precio && combo.lstPrecios[ i ].precio >= 0 ) ){
					alertify.notify( 'Ingrese un precio válido en el servicio ' + combo.lstPrecios[ i ].tipoServicio, 'warning', 4000 );
					error = true;
					break;
				}
			}
		}

		if( !error ) {
			$http.post('consultas.php',{
				opcion : "consultaCombo",
				accion : $scope.accion,
				datos  : $scope.combo
			}).success(function(data){
				console.log( data );
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


});