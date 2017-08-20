app.controller('crtlAdmin', function( $scope , $http, $modal, $timeout ){

	$scope.adminMenu       = 'usuarios';
	$scope.accion          = 'insert';

	$scope.dialAddUsuario = $modal({scope: $scope,template:'dial.addUsuario.html', show: false, backdrop: 'static', keyboard: false});
	

	$scope.lstEstadoUsuario = [];
	$scope.cargarLstEstadoUsuario = function(){
		$http.post('consultas.php',{
			opcion : 'lstEstadoUsuario'
		}).success(function(data){
			console.log( data );
			$scope.lstEstadoUsuario = data;
		});
	};

	$scope.lstNiveles = [];
	$scope.cargarLstNiveles = function(){
		$http.post('consultas.php',{
			opcion : 'lstNiveles'
		}).success(function(data){
			console.log( data );
			$scope.lstNiveles = data;
		});
	};

	$scope.lstPerfiles = [];
	$scope.cargarLstPerfiles = function(){
		$http.post('consultas.php',{
			opcion : 'lstPerfiles'
		}).success(function(data){
			console.log( data );
			$scope.lstPerfiles = data;
		});
	};


	($scope.inicio = function(){
		$scope.cargarLstEstadoUsuario();
		$scope.cargarLstNiveles();
		$scope.cargarLstPerfiles();
	})();


	$scope.agregarUsuario = function()
	{
		$scope.accion = 'insert';
		$scope.dialAddUsuario.show();

		$scope.usuario = {
			usuario         : '',
			idEstadoUsuario : 1,
			idNivel         : 1,
			idPerfil        : 1,
			codigo          : null,
			clave           : '',
			nombres         : '',
			apellidos       : ''
		};
	};


});