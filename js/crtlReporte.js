app.controller('reporteCtrl', function( $scope, $http ){
	$scope.reporteMenu = 1;

	$scope.fechaInicio = '';
	$scope.fechaFinal  = '';

	$scope.ventas = {};
	$scope.consultarVentas = function() {
		$http.post('consultas.php', {opcion: 'getVentasFecha', fechaInicio: $scope.fechaInicio, fechaFinal: $scope.fechaFinal})
		.success(function(data ){
			console.log(data);
			$scope.ventas = data || {};
			if( !data.encontrado )
				alertify.notify( data.mensaje, data.respuesta, 4 );
		});

	};

});