app.controller('clienteCtrl', function( $scope, $http ){
	$scope.catTipoCliente = function(){
		$http.post('consultas.php',{
			opcion:'catTiposCliente'
		}).success(function(data){
			$scope.lstTipoCliente = data;
		})
	}   

	$scope.catTipoCliente();

	$scope.cliente  = {
		'nit'       : null,
		'nombre'    : '',
		'cui'       : null,
		'correo'    : '',
		'telefono'  : null,
		'direccion' : '',
		'idTipoCliente' : null,
	};  

	$scope.cancelarCliente = function(){
		$scope.cliente = {};
	}

	$scope.guardarCliente = function(){
		if ($scope.cliente.nombre=='' || !($scope.cliente.idTipoCliente>0) ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese nombre y tipo de cliente para guardar', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"cosultaCliente",
				accion:'insert',
				cliente: $scope.cliente
			}).success(function(data){
				console.log(data);
				alertify.set('notifier','position', 'top-right');
				alertify.notify(data.mensaje,data.respuesta, data.tiempo);
				if ( data.respuesta == "success" ) {
					$scope.cancelarCliente();
				}
			})
		}
	}
});