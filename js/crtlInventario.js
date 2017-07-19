app.controller('inventarioCtrl', function( $scope , $http, $modal ){
	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion:'catTipoProducto'
		}).success(function(data){
			$scope.lstTipoProducto = data;
		})
	}   

	$scope.catMedidas = function(){
		$http.post('consultas.php',{
			opcion:'catMedidas'
		}).success(function(data){
			$scope.lstMedidas = data;
		})
	}

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

	$scope.inventario = function(){
		$http.post('consultas.php',{
			opcion:'lstProductos'
		}).success(function(data){
			$scope.lstInventario = data;
		})
	}
	//registrar tipo de producto
	$scope.registraTipoProdcuto = function(tipoProducto){
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

	$scope.dialIngreso = $modal({scope: $scope,template:'dial.ingreso.html', show: false});

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
	}
});