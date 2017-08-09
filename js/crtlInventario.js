app.controller('inventarioCtrl', function( $scope , $http, $modal, $timeout ){

	// LISTA EL INVENTARIO GENERAL DE PRODUCTOS
	$scope.lstInventario  = [];
	$scope.inventarioMenu = 4;
	$scope.accion         = 'insert';

	$scope.$watch('inventarioMenu', function( _old, _new){
		console.log( _old, _new );
		if( $scope.inventarioMenu == 1 )
			$scope.inventario();
	});


	$scope.dialIngreso     = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdministrar = $modal({scope: $scope,template:'dialAdmin.producto.html', show: false, backdrop: 'static'});

	$scope.dialAdministrarAbrir = function(){
		$scope.dialAdministrar.show();
	};

	$scope.dialAdministrarCerrar = function(){
		$scope.dialAdministrar.hide();
	};

	// OBJ PRODUCTO
	$scope.prod = {
		idProducto     : null,
		nombreProducto : '',
		cantidad       : null,
		seleccionado   : false
	};
	
	$scope.lstProductosIngreso = [];
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


	$scope.cancelarIngreso = function( accion )
	{
		if( accion == 1 ){

			$scope.prod = {
				idProducto     : null,
				nombreProducto : '',
				cantidad       : null,
				seleccionado   : false
			};
			$timeout(function(){
				$('#producto').focus();
			}, 50);
		}
	};


	$scope.quitarProdIngreso = function( index ){
		$scope.lstProductosIngreso.splice( index, 1 );
	};

	$scope.agregarIngresoProducto = function(){
		console.log( "carga" );
		var prod = $scope.prod;

		if( !(prod.idProducto && prod.idProducto > 0) )
		{
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
		}
		else if( !(prod.cantidad && prod.cantidad > 0) ){
			alertify.notify( 'La cantidad ingresada debe ser mayor a 0', 'danger', 5 );
		}
		else{
			$scope.lstProductosIngreso.push({
				idProducto : prod.idProducto,
				producto   : prod.producto,
				cantidad   : prod.cantidad
			});

			$scope.cancelarIngreso( 1 );
			alertify.notify( 'Agregado a la lista', 'success', 3 );
		}
	};


	$scope.seleccionarProducto = function( producto )
	{
		if( !(producto.idProducto && producto.idProducto > 0) ) {
			alertify.notify( 'El código del Producto no es válido', 'danger', 5 );
		}
		else{
			$scope.prod = {
				idProducto   : producto.idProducto,
				producto     : producto.producto,
				cantidad     : null,
				seleccionado : true
			};

			$('#cantidad').focus();

			$scope.lstProductos = [];
		}
	};

	$scope.guardarLstProductosIngreso = function(){
		var error = false;

		if( !($scope.lstProductosIngreso.length) ){
			error = true;
			alertify.notify( 'No ha ingrado ningun producto, verifique', 'info', 5 );
		}
		else{
			for (var i = 0; i < $scope.lstProductosIngreso.length; i++) {
				if( $scope.lstProductosIngreso[i].idProducto ){
					error = true;
					alertify.notify( 'El Código del producto no es válido, verifique', 'warning', 5 );
					break;
				}
				else if( !($scope.lstProductosIngreso[i].cantidad && $scope.lstProductosIngreso[i].cantidad > 0 ) ){
					alertify.notify( 'La cantidad debe ser mayor a 0', 'warning', 5 );
					error = true;
					break;
				}
			}
		}

		if( !error ){
			$http.post('consultas.php',{opcion: 'guardarLstProductosIngreso', data: $scope.lstProductosIngreso})
			.success(function(data){
				console.log(data);
			}).error(function(data){
				console.log(data);
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
			}).error(function(data){
				console.log(data);
			});
		}
		else
			$scope.lstProductos = []

	};

	// TIPOS DE PRODUCTO
	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion:'catTipoProducto'
		}).success(function(data){
			$scope.lstTipoProducto = data;
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

	
	
	$scope.lstPaginacion = [];
	$scope.generarPaginacion = function( totalPaginas ){
		$scope.lstPaginacion = [];
		for (var i = 1; i <= totalPaginas; i++) {
			$scope.lstPaginacion.push({
				noPagina : i
			});
		}
	};


	$scope.cargarPaginacion = function( pagina ){
		$scope.filter.pagina = pagina;
		$scope.inventario();
	};

	$scope.filter = {
		pagina: 1,
		limite: 25,
		orden: 'ASC'
	};


	$scope.buscarTipoProducto = '';
	$scope.resetValores = function( accion ){
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

	};

	($scope.cargarInicio = function(){
		$scope.catMedidas();
		$scope.catTipoProducto();
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
			})
		}
	};


	$scope.inventario = function(){
		$http.post('consultas.php',{
			opcion : 'lstProductos',
			filter : $scope.filter
		}).success(function(data){
			console.log( "::::", data );
			$scope.lstInventario = data.lstProductos;
			$scope.generarPaginacion( data.totalPaginas );
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
		else{
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

		if( $scope.accion == 'update' && !(medida.idMedida && medida.idMedida > 0 ) ){
			alertify.notify( 'El No. de la medida no es válido', 'warning', 5 );
		}
		else if( !(medida.medida && medida.medida.length > 3 ) ){
			alertify.notify( 'La descripcion de la medida debe ser mayor a 3 caracteres', 'warning', 5 );
		}
		else{
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
			})
		}
	}


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
	}


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
	}


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