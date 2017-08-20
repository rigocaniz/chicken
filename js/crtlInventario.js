app.controller('inventarioCtrl', function( $scope , $http, $modal, $timeout ){

	// LISTA EL INVENTARIO GENERAL DE PRODUCTOS
	$scope.lstInventario  = [];
	$scope.inventarioMenu = 'compras';
	$scope.accion         = 'insert';
	$scope.groupBy        = 'sinFiltro';

	$scope.$watch('inventarioMenu', function( _old, _new){
		console.log( _old, _new );
		if( $scope.inventarioMenu == 'inventario' )
			$scope.inventario();
	});

	$scope.$watch('groupBy', function( _old, _new ){
		if( _old != _new && $scope.inventarioMenu == 'inventario' )
			$scope.inventario();
	});


	$scope.dialIngreso                 = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdministrar             = $modal({scope: $scope,template:'dialAdmin.producto.html', show: false, backdrop: 'static'});
	$scope.dialCierreDiario            = $modal({scope: $scope,template:'dial.cierreDiario.html', show: false, backdrop: 'static'});
	$scope.dialLstFacturaCompra        = $modal({scope: $scope,template:'dial.lstFacturaCompra.html', show: false, backdrop: 'static'});
	$scope.dialEditarFacturaCompra     = $modal({scope: $scope,template:'dial.editarFacturaCompra.html', show: false, backdrop: 'static'});
	$scope.dialVerDetalleFacturaCompra = $modal({scope: $scope,template:'dial.verDetalleFacturaCompra.html', show: false, backdrop: 'static'});	

	$scope.dialAdministrarAbrir = function(){
		$scope.dialAdministrar.show();
	};

	$scope.dialAdministrarCerrar = function(){
		$scope.dialAdministrar.hide();
	};


	$scope.subTotalQuetzales = function()
	{
		var subTotalQuetzales = 0;
		for (var i = 0; i < $scope.compras.lstProductos.length; i++) {
			subTotalQuetzales += parseFloat( $scope.compras.lstProductos[ i ].costo );
		}
		return subTotalQuetzales;

	};

	// OBJ PRODUCTO
	$scope.prod = {
		idProducto     : null,
		nombreProducto : '',
		medida         : '',
		cantidad       : null,
		costo          : null,
		seleccionado   : false
	};

	$scope.compras = {
		noFactura       : 123,
		fechaFactura    : null,
		idProveedor     : 1,
		proveedor       : 'PROVEEDOR 1',
		idEstadoFactura : 1,
		comentario      : '',
		lstProductos    : []
	};
	
	$scope.cierreDiario = {
		idCierreDiario : null,
		fechaCierre    : null,
		comentario     : '',
		lstProductos   : []
	};

	$scope.getListaProductos = function(){
		$http.post('consultas.php',{
			opcion : "getListaProductos",
			filtro : $scope.cierreDiario.cierreTodos
		}).success(function(data){
			console.log( data );
			$scope.cierreDiario.lstProductos = data;
		})
	};

	$scope.realizarCierre = function(){
		$scope.accion == 'insert';
		$scope.cierreDiario = {
			cierreTodos    : true,
			idCierreDiario : null,
			fechaCierre    : angular.copy( $scope.fechaActual ),
			comentario     : '',
			lstProductos   : []
		};

		$scope.getListaProductos();
		$scope.dialCierreDiario.show();
	};	


	$scope.consultaCierreDiario = function(){
		var cierreDiario = $scope.cierreDiario;

		if( $scope.accion == 'update' && !(cierreDiario.idCierreDiario && cierreDiario.idCierreDiario > 0) )
			alertify.notify( 'El código del cierra no es válido', 'warning', 4 );
		
		else if( !(cierreDiario.fechaCierre) )
			alertify.notify( 'Ingrese la fecha de cierre', 'warning', 3 );
		
		else
		{
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaCierreDiario",
				accion : $scope.accion,
				data   : $scope.cierreDiario
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				if( data.respuesta == 'success' )
				{
					$scope.resetValores( 'cierreDiario' );
					$scope.dialCierreDiario.hide();
					$scope.inventario();
				}

				$scope.$parent.hideLoading();
			});

		}
	};

	$scope.idxProducto = -1;
	$scope.seleccionKeyProducto = function( key )
	{
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

	// CANCELAR INGRESO
	$scope.cancelarIngreso = function( accion )
	{
		if( accion == 1 ){

			$scope.prod = {
				idProducto     : null,
				nombreProducto : '',
				cantidad       : null,
				costo : null,
				seleccionado   : false
			};
			$timeout(function(){
				$('#producto').focus();
			}, 50);
		}
	};


	$scope.quitarProdIngreso = function( index ){
		$scope.compras.lstProductos.splice( index, 1 );
	};

	$scope.agregarIngresoProducto = function(){
		var prod = $scope.prod;

		if( !(prod.idProducto && prod.idProducto > 0) )
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );

		else if( !(prod.cantidad && prod.cantidad > 0) )
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );

		else if( !(prod.costo && prod.costo > 0) )
			alertify.notify( 'Ingrese el precio Unitario del producto', 'danger', 5 );

		else{
			$scope.compras.lstProductos.push({
				idProducto : prod.idProducto,
				producto   : prod.producto,
				cantidad   : prod.cantidad,
				costo      : prod.costo
			});

			$scope.cancelarIngreso( 1 );
			alertify.notify( 'Agregado a la lista', 'success', 3 );
			$scope.lstProductos = [];
		}
	};

	// SELECCIONAR PRODUCTO
	$scope.seleccionarProducto = function( producto )
	{
		if( !(producto.idProducto && producto.idProducto > 0) )
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
		
		else
		{
			$scope.prod = {
				idProducto   : producto.idProducto,
				producto     : producto.producto,
				medida       : producto.medida,
				cantidad     : null,
				costo        : null,
				seleccionado : true
			};

			$('#cantidad').focus();
			$scope.lstProductos = [];
		}
	};

	// CONSULTA FACTURA
	$scope.consultaFactura = function( accion ){
		var error = false;
		$scope.accion = accion;

		if( !($scope.compras.lstProductos.length) && $scope.accion == 'insert' ){
			error = true;
			alertify.notify( 'No ha ingresado ningun producto, verifique', 'info', 5 );
		}
		else if( $scope.accion == 'insert' ){
			for (var i = 0; i < $scope.compras.lstProductos.length; i++) {
				var prod = $scope.compras.lstProductos[ i ];

				if( !(prod.idProducto && prod.idProducto > 0) ){
					error = true;
					alertify.notify( 'El Código del producto no es válido, verifique', 'warning', 5 );
					break;
				}
				else if( !(prod.cantidad && prod.cantidad > 0 ) ){
					alertify.notify( 'La cantidad debe ser mayor a 0', 'warning', 5 );
					error = true;
					break;
				}
			}
		}

		if( !error ){
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : 'consultaFactura',
				accion : $scope.accion,
				data   : $scope.accion == 'insert' ? $scope.compras : $scope.facturaCompra
			})
			.success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if( data.respuesta == 'success' )
				{
					if( $scope.accion == 'insert' )
						$scope.resetValores( 'compras' );
					else if( $scope.accion == 'update' )
					{
						$scope.dialEditarFacturaCompra.hide();
						$scope.cargarLstFacturaCompra();
					}
				}

				$scope.$parent.hideLoading();
			});
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
			});
		}
		else
			$scope.lstProductos = []

	};


	$scope.editarFacturaCompra = function( facturaCompra, accion )
	{
		console.log( facturaCompra ) ;
		$scope.facturaCompra = angular.copy( facturaCompra );
		$scope.facturaCompra.fechaFactura = moment( facturaCompra.fechaFactura );
		$scope.dialLstFacturaCompra.hide();
		if( accion == 'editar' )
			$scope.dialEditarFacturaCompra.show();

		if( accion == 'verDetalle' )
		{
			$scope.$parent.showLoading( 'Cargando...' );
			$http.post('consultas.php',{
				opcion          : 'cargarLstIngresoProducto',
				idFacturaCompra : $scope.facturaCompra.idFacturaCompra
			})
			.success(function(data){
				$scope.facturaCompra.lstProductos = data;
				$scope.dialVerDetalleFacturaCompra.show();

				$scope.$parent.hideLoading();
			});

		}

	};

	// LST FACTURAS COMPRA
	$scope.lstFacturaCompra = [];
	$scope.cargarLstFacturaCompra = function(){
		$scope.$parent.showLoading( 'Cargando...' );

		$http.post('consultas.php',{
			opcion : 'cargarLstFacturaCompra'
		}).success(function(data){
			console.log( data );
			$scope.lstFacturaCompra = data;
			if( $scope.lstFacturaCompra.length )
				$scope.dialLstFacturaCompra.show();
			else
				alertify.notify( 'No se encontrarón ingresos', 'info', 4 );

			$scope.$parent.hideLoading();
		})
	};


	// TIPOS DE PRODUCTO
	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion : 'catTipoProducto'
		}).success(function(data){
			$scope.lstTipoProducto = data;
		})
	};


	$scope.lstEstadosFactura = [];
	// ESTADOS FACTURA
	$scope.catEstadosFactura = function(){
		$http.post('consultas.php',{
			opcion:'catEstadosFactura'
		}).success(function(data){
			$scope.lstEstadosFactura = data;
		})
	};
	
	// TIPOS DE MEDIDA
	$scope.catMedidas = function(){
		$http.post('consultas.php',{
			opcion:'catMedidas'
		}).success(function(data){
			$scope.lstMedidas = data;
		})
	};


	$scope.filter = {
		pagina: 1,
		limite: 25,
		orden: 'ASC'
	};


	$scope.buscarTipoProducto = '';
	$scope.resetValores = function( accion )
	{

		$scope.accion             = 'insert';
		$scope.buscarTipoProducto = '';
		$scope.buscarMedida       = '';

		if( accion == 1 ){
			$scope.producto = {
				'producto'       : '',
				'idTipoProducto' : null,
				'idMedida'       : null,
				'perecedero'     : true,
				'cantidadMinima' : null,
				'cantidadMaxima' : null,
				'disponibilidad' : '',
				'importante'     : '',
			};			
		}
		else if( accion == 2 ){
			$scope.itemProducto = {
				idProducto     : null,
				nombreProducto : '',
				cantidad       : 0,
				disponibilidad : null,
				observacion    : '',
				esIncremento   : true
			};
		}

		else if( accion == 4 ){
			$scope.tp = {
				idTipoProducto : null,
				tipoProducto   : ''
			};
		}

		else if( accion == 6 ){
			$scope.medidaProd = {
				idMedida : null,
				medida   : ''
			};
		}

		else if( accion == 'cierreDiario' )
		{
			$scope.cierreDiario = {
				idCierreDiario : null,
				fechaCierre    : null,
				comentario     : '',
				lstProductos   : []
			};
		}

		else if( accion == 'compras' )
		{
			$scope.compras = {
				noFactura       : null,
				fechaFactura    : null,
				idProveedor     : null,
				proveedor       : null,
				idEstadoFactura : 1,
				comentario      : '',
				lstProductos    : []
			};
		}

	};

	($scope.cargarInicio = function(){
		$scope.catMedidas();
		$scope.catTipoProducto();
		$scope.catEstadosFactura();
		$scope.resetValores( 1 );
	})();

	/*$scope.filtro        = {
		filter : [
			{ filter: 'idMedida', value : 8 },
			{ filter: 'idTipoProducto', value : 2 },
		],
		order : [
			{ by: 'idMedia', order: 'asc' },
			{ by: 'idTipoProducto', order: 'desc' }
		],
		limit : 15,
		page : 1,
	};*/


	$scope.filtro = {
		filter : [
			{ filter: 'idProducto', value : 8 }
		],
		order : [
			{ by: 'idTipoProducto', order: 'DESC' }
		],
		limit : 25,
		page : 1,
	};


	$scope.editarAccion = function( id, accion, producto ){
		console.log( 'producto::: ', producto );

		$scope.id     = id;
		$scope.accion = accion;

		if ( $scope.id > 0 ){

			$http.post('consultas.php',{
				opcion     : 'cargarProducto',
				idProducto : $scope.id
			}).success(function(data){
				console.log(data);
				$scope.producto = data;
			})

			$scope.accion = 'update';
		}

		$scope.dialAdministrarAbrir();
	};


	// INSERTAR / ACTUALIZAR PRODUCTO
	$scope.consultaProducto = function(){
		var producto = $scope.producto, accion = $scope.accion;

		if( accion == 'update' && !(producto.idProducto > 0) ){
			alertify.notify( 'No. de producto no definido', 'warning', 5 );
		}
		else if( !(producto.producto && producto.producto.length >= 3) ){
			alertify.notify( 'El nombre del producto debe ser mayor a 3 caracteres', 'warning', 5 );
		}
		else if( !(producto.idTipoProducto && producto.idTipoProducto > 0) ){
			alertify.notify( 'Seleccione el tipo de producto', 'warning', 5 );	
		}
		else if( !(producto.idMedida && producto.idMedida > 0) ){
			alertify.notify( 'Seleccione la medida', 'warning', 5 );	
		}
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) ){
			alertify.notify( 'La cantidad mínima debe ser mayor a 0', 'warning', 5 );	
		}
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) ){
			alertify.notify( 'La cantidad máxima debe ser mayor a 0', 'warning', 5 );	
		}
		else if( !(producto.disponibilidad && producto.disponibilidad > 0) ){
			alertify.notify( 'La disponibilidad debe ser mayor a 0', 'warning', 5 );	
		}
		else{

			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaProducto",
				accion : $scope.accion,
				datos  : $scope.producto
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == "success" ) {
					$scope.resetValores( 1 );
					$scope.inventario();
					$scope.dialAdministrarCerrar();
				}

				$scope.$parent.hideLoading();
			})
		}
	};


	$scope.inventario = function(){
		$http.post('consultas.php',{
			opcion  : 'lstProductos',
			groupBy : $scope.groupBy
		}).success(function(data){
			console.log( "::::", data );
			$scope.lstInventario = data;
		})
	};


	$scope.tp = {
		idTipoProducto : null,
		tipoProducto   : ''
	};
	$scope.editarTipoProducto = function( tipoProducto ){
		$scope.accion = 'update';
		$scope.tp.idTipoProducto = tipoProducto.idTipoProducto;
		$scope.tp.tipoProducto   = tipoProducto.tipoProducto;
		$('#tipoProducto').focus();
	};


	// INSERT / UPDATE TIPO PRODUCTO
	$scope.consultaTipoProducto = function( tipoProducto ){
		var tp = $scope.tp;

		if( $scope.accion == 'update' && !(tp.idTipoProducto && tp.idTipoProducto > 0 ) ){
			alertify.notify( 'El No. del Tipo de producto no es válido', 'warning', 5 );
		}
		else if( !(tp.tipoProducto && tp.tipoProducto.length > 3 ) ){
			alertify.notify( 'La descripción del tipo de producto debe ser mayor a 3 caracteres', 'warning', 5 );
		}
		else
		{
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaTipoProducto",
				accion : $scope.accion,
				datos  : $scope.tp
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.resetValores( 4 );
					$scope.catTipoProducto();
				}

				$scope.$parent.hideLoading();
			})
		}
	};


	$scope.medidaProd = {
		idMedida : null,
		medida   : ''
	};
	$scope.editarMedida = function( medida ){
		$scope.accion = 'update';
		$scope.medidaProd.idMedida = medida.idMedida;
		$scope.medidaProd.medida   = medida.medida;
		$('#medida').focus();
	};

	// INSERTAR / ACTUALIZAR MEDIDA
	$scope.consultaMedida = function(){
		var medida = $scope.medidaProd;

		if( $scope.accion == 'update' && !(medida.idMedida && medida.idMedida > 0 ) )
			alertify.notify( 'El No. de la medida no es válido', 'warning', 5 );

		else if( !(medida.medida && medida.medida.length > 3 ) )
			alertify.notify( 'La descripcion de la medida debe ser mayor a 3 caracteres', 'warning', 5 );
		else
		{
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaMedida",
				accion : $scope.accion,
				datos  : $scope.medidaProd
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.medidaProducto = '';
					$scope.catMedidas();
					$scope.resetValores( 6 );
				}

				$scope.$parent.hideLoading();
			})
		}
	};


	$scope.itemProducto = {
		idProducto     : null,
		nombreProducto : '',
		cantidad       : 0,
		disponibilidad : null,
		observacion    : '',
		esIncremento   : true
	};

	// OBTENER VALORES REAJUSTE
	$scope.ingresarReajuste = function( idProducto, nombreProducto, disponibilidad ){
		$scope.itemProducto.idProducto     = idProducto;
		$scope.itemProducto.nombreProducto = nombreProducto;
		$scope.itemProducto.disponibilidad = disponibilidad;
		$scope.dialIngreso.show();
	};


	$scope.retornarTotal = function()
	{
		var itemProducto = $scope.itemProducto, total = 0;

		if( itemProducto.cantidad ){
			if( itemProducto.esIncremento )
				total = parseFloat( itemProducto.disponibilidad ) + parseFloat( itemProducto.cantidad );
			else
				total = parseFloat( itemProducto.disponibilidad ) - parseFloat( itemProducto.cantidad );

		}else
			total = itemProducto.disponibilidad;

		return total;
	};


	$scope.consultaReajusteInventario = function(){
		var itemProducto = $scope.itemProducto;

		if( !(itemProducto.cantidad && itemProducto.cantidad != 0) ){
 			alertify.notify('La cantidad debe ser diferente a 0', 'warning', 4);
		}
		else if( !(itemProducto.observacion && itemProducto.observacion.length > 20) ){
			alertify.notify('La observación debe tener más de 20 caracteres', 'warning', 4);
		}
		else{
			$http.post('consultas.php',{
				opcion : "consultaReajusteInventario",
				accion : 'insert',
				datos  : $scope.itemProducto
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.inventario();
					$scope.resetValores( 2 );
					$scope.dialIngreso.hide();
				}
			})
		}
	};

});