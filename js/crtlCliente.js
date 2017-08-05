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
			//validar accion a realizar
			var accion = 'insert';
			if ( $scope.cliente.idCliente > 0 ) {
				accion = 'update';
			}
			
			$http.post('consultas.php',{
				opcion:"consultaCliente",
				accion:accion,
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
				var encontrados = data.length;
				if (encontrados == 1 ) {
					$scope.clienteMenu = 1;
					$scope.cliente = data[0]
					$scope.cliente.cui      = parseInt(data[0].cui);
					$scope.cliente.telefono = parseInt(data[0].telefono);
				}else if( encontrados > 1 ){
					$scope.masEncontrados = 1;
					$scope.lstClienteEncontrado = data;
				}else{
					alertify.set('notifier','position', 'top-right');
 					alertify.notify('Ningún cliente localizado', 'warning', 3);
				}
			})
		}
	};

	$scope.editarCliente = function(cliente){
		$scope.cliente          = angular.copy(cliente);
		$scope.cliente.cui      = parseInt(cliente.cui);
		$scope.cliente.telefono = parseInt(cliente.telefono);
		$scope.masEncontrados   = 0;
		$scope.clienteMenu      = 1;
	};

});