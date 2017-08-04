app.controller('clienteCtrl', function( $scope, $http ){
	$scope.clienteMenu = 1;
	$scope.catTipoCliente = function(){
		$http.post('consultas.php',{
			opcion:'catTiposCliente'
		}).success(function(data){
			$scope.lstTipoCliente = data;
		})
	};  

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
				opcion:"consultaCliente",
				accion:'insert',
				cliente: $scope.cliente
			}).success(function(data){
				console.log(data);
				alertify.set('notifier','position', 'top-right');
				alertify.notify(data.mensaje,data.respuesta, data.tiempo);
				if ( data.respuesta == "success" ) {
					$scope.cancelarCliente();
				}
			}).error(function (error, status){
		        $scope.data.error = { message: error, status: status};
		        console.log($scope.data.error.status); 
  			}); 
		}
	};

	$scope.buscarCliente = function(valor){
		if (valor == "" || valor == undefined )  {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese algún dato para buscar', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultarCliente",
				valor: valor
			}).success(function(data){
				console.log(data);
				
			})
		}
	}
});