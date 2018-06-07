app.controller('reporteCtrl', function( $scope, $http, $filter ){
	$scope.reporteMenu       = 1;
	$scope.fechaInicio       = null;
	$scope.fechaFinal        = null;
	$scope.fechaInicioC      = null;
	$scope.fechaFinalC       = null;
	$scope.fechaInicioOC      = null;
	$scope.fechaFinalOC       = null;
	$scope.fechaInicioD = null;
	$scope.fechaFinalD = null;
	$scope.ventas            = {};
	$scope.ordenesCanceladas = {};
	$scope.filtro            = 'combo';

	// ORDENES CANCELADAS
	$scope.consultarOrdenesC = function( accion )
	{
		if( !($scope.fechaInicioOC) )
			alertify.notify( 'Ingrese fecha de Inicio', 'info', 3 );

		else if( !($scope.fechaFinalOC) )
			alertify.notify( 'Ingrese fecha Final', 'info', 3 );

		else
		{
			var 
				fechaInicio = $filter('date')( $scope.fechaInicioOC, "yyyy-MM-dd" ),
				fechaFinal  = $filter('date')( $scope.fechaFinalOC, "yyyy-MM-dd" );

			if( accion == 'consultar' )
			{
				$scope.$parent.showLoading();

				$http.post('consultas.php', {
					opcion      : 'getOrdenesCanceladas',
					fechaInicio : fechaInicio,
					fechaFinal  : fechaFinal
				})
				.success(function(data ){
					console.log("data ", data);
					$scope.ordenesCanceladas = data || {};
					if( !data.encontrado )
						alertify.notify( 'No se encontraron Resultados', 'warning' );

					$scope.$parent.hideLoading();
				}).error(function(data){
					$scope.$parent.hideLoading();
				});
				
			}
			else if( accion == 'descargar' )
    			window.open('reporte.ordCanceladas.php?fechaInicio=' + fechaInicio + '&fechaFinal=' + fechaFinal, '_blank');
		}
	};

	$scope.descuentos = {};
	$scope.consultarDescuentos = function( accion )
	{
		if( !($scope.fechaInicioD) )
			alertify.notify( 'Ingrese fecha de Inicio', 'info', 3 );

		else if( !($scope.fechaFinalD) )
			alertify.notify( 'Ingrese fecha Final', 'info', 3 );

		else
		{
			var 
				fechaInicio = $filter('date')( $scope.fechaInicioD, "yyyy-MM-dd" ),
				fechaFinal  = $filter('date')( $scope.fechaFinalD, "yyyy-MM-dd" );

			if( accion == 'consultar' )
			{
				$scope.$parent.showLoading();

				$http.post('consultas.php', {
					opcion      : 'getDescuentos',
					fechaInicio : fechaInicio,
					fechaFinal  : fechaFinal
				})
				.success(function(data ){
					//console.log(data);
					$scope.descuentos = data || {};
					if( !data.encontrado )
						alertify.notify( 'No se encontraron Resultados', 'warning' );

					$scope.$parent.hideLoading();
				}).error(function(data){
					$scope.$parent.hideLoading();
				});
				
			}
			else if( accion == 'descargar' )
    			window.open('reporte.descuentos.php?fechaInicio=' + fechaInicio + '&fechaFinal=' + fechaFinal, '_blank');
		}
	};

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
    			window.open('reporte.php?fechaInicio=' + fechaInicio + '&fechaFinal=' + fechaFinal, '_blank');
		}
	};

	$scope.compras = {};
	$scope.consultarCompras = function( accion )
	{
		if( !($scope.fechaInicioC) )
			alertify.notify( 'Ingrese fecha de Inicio', 'info', 3 );

		else if( !($scope.fechaFinalC) )
			alertify.notify( 'Ingrese fecha Final', 'info', 3 );

		else
		{
			var 
				fechaInicio = $filter('date')( $scope.fechaInicioC, "yyyy-MM-dd" ),
				fechaFinal  = $filter('date')( $scope.fechaFinalC, "yyyy-MM-dd" );

			if( accion == 'consultar' )
			{
				$scope.$parent.showLoading();

				$http.post('consultas.php', {
					opcion      : 'getComprasFecha',
					fechaInicio : fechaInicio,
					fechaFinal  : fechaFinal
				})
				.success(function(data ){
					console.log(data);
					$scope.compras = data || {};
					if( !data.encontrado )
						alertify.notify( 'No se encontraron Resultados', 'warning' );

					$scope.$parent.hideLoading();
				}).error(function(data){
					$scope.$parent.hideLoading();
				});
				
			}
			else if( accion == 'descargar' )
    			window.open('reporte.compras.php?fechaInicio=' + fechaInicio + '&fechaFinal=' + fechaFinal, '_blank');
		}
	};

});