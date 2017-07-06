app.controller('nuevoEditaCtrl', function( $scope , $http, $modal, $routeParams ){
	$scope.producto       = {
		'producto'       : '',
		'idTipoProducto' : null,
		'idMedida'       : null,
		'perecedero'     : true,
		'cantidadMinima' : null,
		'cantidadMaxima' : null,
		'disponibilidad' : '',
		'importante'     : '',
	};    

	$scope.producto.opcion = $routeParams.accion;
	//$scope.idcasoenviado = $routeParams.idcaso;
	$scope.idProducto   = 0;
	if ($routeParams.idProducto >0 ) {
	$scope.producto.idProducto   = $routeParams.idProducto;
	}

	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion:'catTipoProducto'
		}).success(function(data){
			console.log(data);
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

	$scope.catTipoProducto();
	$scope.catMedidas();  
	//registrar nuevo producto
	$scope.registraNuevoProducto = function(){
		if ( $scope.formProducto.$invalid == true ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese todos los datos requeridos identificados con borde de color verde', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaProducto",
				accion:'insert',
				datos: $scope.producto
			}).success(function(data){
				console.log(data);
				//mensaje aca
				alertify.set('notifier','position', 'top-right');
				alertify.notify(data.mensaje,data.respuesta, data.tiempo);
				if ( data.respuesta == "success" ) {
					$scope.cancelaNuevoProducto();
				}
			})
		}
	}
	//cancelar registro de nuevo producto
	$scope.cancelaNuevoProducto = function(){
		$scope.producto = {};
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
			opcion:'consultarLstProductos'
		}).success(function(data){
			$scope.lstInventario = data;
		})
	}
	//registrar tipo de producto
	$scope.registraTipoProdcuto = function(tipoProducto){
		if ( tipoProducto== undefined || tipoProducto.length>3 ) {
			alert("ingrese tipo de prodcuto");
		}else{
			/*$http.post('include/guardar.php',{
				opcion:"guardarTipoProducto",
				accion:'insert',
				datos: tipoProducto
			}).success(function(data){
				//mensaje aca
				if ( data.datos == 1 ) {
					$scope.tipoProdcuto = '';
				}
			})*/
		}
	}
	//registrar medida de producto
	$scope.registraMedidaProducto = function(medidaProducto){
		if ( medidaProducto== undefined || medidaProducto.length>3 ) {
			alert("ingrese la medida para productos");
		}else{
			/*$http.post('include/guardar.php',{
				opcion:"guardarMedidaProducto",
				accion:'insert',
				datos: medidaProducto
			}).success(function(data){
				//mensaje aca
				if ( data.datos == 1 ) {
					$scope.medidaProducto = '';
				}
			})*/
		}
	}

});