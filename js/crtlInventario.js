app.controller('inventarioCtrl', function( $scope , $http, $modal ){

	$scope.inventarioMenu = 1;

	$scope.$watch('inventarioMenu', function( _old, _new){
		console.log( _old, _new );
		if( $scope.inventarioMenu == 1 )
			$scope.inventario();
	});

	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion:'catTipoProducto'
		}).success(function(data){
			$scope.lstTipoProducto = data;
		})
	};


	$scope.catMedidas = function(){
		$http.post('consultas.php',{
			opcion:'catMedidas'
		}).success(function(data){
			$scope.lstMedidas = data;
		})
	};

	$scope.resetValues = function( accion ){

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

	};

	($scope.cargarInicio = function(){
		$scope.catMedidas();
		$scope.catTipoProducto();
		$scope.resetValues( 1 );
	})();

	//lista el inventario general de todos los productos
	$scope.lstInventario = [];
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



	$scope.editarAccion = function( id, accion ){

		$scope.id     = id;
		$scope.accion = accion;

		if ( $scope.id > 0 ) {
			$scope.accion = 'update';
		}


		if ($scope.id > 0 ) {

			$http.post('consultas.php',{
				opcion:'cargarProducto',
				idProducto: $scope.id
			}).success(function(data){
				console.log(data);
				$scope.producto = data;
			})

		}

		$scope.dialAdministrarAbrir();

	};

	//cancelar registro de nuevo producto
	$scope.cancelaNuevoProducto = function(){
		$scope.producto = {};
	};


	//registrar nuevo producto
	$scope.consultaProducto = function(){
		var producto = $scope.producto, accion = $scope.accion;
		console.log( producto );

		if( accion == 'update' && !(producto.idProducto > 0) ){
			alertify.notify( 'No. de producto no definido', 'danger', 5 );
		}
		else if( !(producto.producto && producto.producto.length >= 3) ){
			alertify.notify( 'El nombre del producto debe ser mayor a 3 caracteres', 'info', 5000 );
		}
		else if( !(producto.idTipoProducto && producto.idTipoProducto > 0) ){
			alertify.notify( 'Seleccione el tipo de producto', 'danger', 5 );	
		}

		if ( $scope.formProducto.$invalid == true ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese todos los datos requeridos identificados con borde de color verde', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion : "consultaProducto",
				accion : $scope.accion,
				datos  : $scope.producto
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == "success" ) {
					$scope.resetValues( 1 );
					$scope.inventario();
					$scope.dialAdministrarCerrar();
				}
			})
		}
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


	// REGISTRAR TIPO PRODUCTO
	$scope.registraTipoProdcuto = function( tipoProducto ){
		if ( tipoProducto== undefined || !tipoProducto.length>3 ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese tipo de producto', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaTipoProducto",
				accion:'insert',
				datos: {tipoProducto:tipoProducto}
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.tipoProducto = '';
					$scope.catTipoProducto();
				}
			})
		}
	}
	//registrar medida de producto
	$scope.registraMedidaProducto = function(medidaProducto){
		if ( medidaProducto== undefined || !medidaProducto.length>3 ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese la medida para productos', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaMedida",
				accion:'insert',
				datos: {medida:medidaProducto}
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.medidaProducto = '';
					$scope.catMedidas();
				}
			})
		}
	}

	$scope.dialIngreso     = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdministrar = $modal({scope: $scope,template:'dialAdmin.producto.html', show: false, backdrop: 'static'});

	$scope.dialAdministrarAbrir = function(){
		$scope.dialAdministrar.show();
	};

	$scope.dialAdministrarCerrar = function(){
		$scope.dialAdministrar.hide();
	};

	$scope.ingresar = function(idProducto, nombreProducto){
		$scope.dialIngreso.show();
		$scope.idProducto     = idProducto;
		$scope.nombreProducto = nombreProducto;
	}

	$scope.guardaIngreso = function(idProducto,cantidad){
		if (!(cantidad>0) ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese la cantidad', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaIngreso",
				accion:'insert',
				datos: {idProducto:idProducto,cantidad:cantidad}
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.cantidad = '';
					$scope.dialIngreso.hide();
					$scope.inventario();
				}
			})
		}
	};

});