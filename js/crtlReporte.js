app.controller('reporteCtrl', function( $scope, $http, $filter ){
	$scope.reporteMenu = 1;

	$scope.fechaInicio = null;
	$scope.fechaFinal  = null;
	$scope.ventas      = {};
	$scope.filtro      = 'combo';

	$scope.consultarVentas = function()
	{
		if( !($scope.fechaInicio) )
			alertify.notify( 'Ingrese fecha de Inicio', 'info', 3 );

		else if( !($scope.fechaFinal) )
			alertify.notify( 'Ingrese fecha Final', 'info', 3 );

		else
		{
			$scope.$parent.showLoading();

			var 
				fechaInicio = $filter('date')($scope.fechaInicio, "yyyy-MM-dd"),
				fechaFinal = $filter('date')($scope.fechaFinal, "yyyy-MM-dd");

			$http.post('consultas.php', {
				opcion      : 'getVentasFecha',
				filtro: $scope.filtro,
				fechaInicio : fechaInicio,
				fechaFinal  : fechaFinal
			})
			.success(function(data ){
				console.log(data);
				$scope.ventas = data || {};
				if( !data.encontrado )
					alertify.notify( data.mensaje, data.respuesta, 4 );

				$scope.$parent.hideLoading();
			}).error(function(data){
				$scope.$parent.hideLoading();
			});
		}
	};

});