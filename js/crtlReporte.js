app.controller('reporteCtrl', function( $scope, $http, $filter ){
	$scope.reporteMenu = 1;

	$scope.fechaInicio = null;
	$scope.fechaFinal  = null;
	$scope.ventas      = {};
	$scope.filtro      = 'combo';

	$scope.consultarVentas = function( accion )
	{
		if( !($scope.fechaInicio) )
			alertify.notify( 'Ingrese fecha de Inicio', 'info', 3 );

		else if( !($scope.fechaFinal) )
			alertify.notify( 'Ingrese fecha Final', 'info', 3 );

		else
		{
			var 
				fechaInicio = $filter('date')( $scope.fechaInicio, "yyyy-MM-dd" ),
				fechaFinal  = $filter('date')( $scope.fechaFinal, "yyyy-MM-dd" );

			if( accion == 'consultar' )
			{
				$scope.$parent.showLoading();

				$http.post('consultas.php', {
					opcion      : 'getVentasFecha',
					fechaInicio : fechaInicio,
					fechaFinal  : fechaFinal
				})
				.success(function(data ){
					//console.log(data);
					$scope.ventas = data || {};
					if( !data.encontrado )
						alertify.notify( 'No se encontraron Resultados', 'warning' );

					$scope.$parent.hideLoading();
				}).error(function(data){
					$scope.$parent.hideLoading();
				});
				
			}
			else if( accion == 'descargar' )
    			window.open('reporte.php?fechaInicio=' + fechaInicio + '&&fechaFinal=' + fechaFinal, '_blank');
		}
	};

});