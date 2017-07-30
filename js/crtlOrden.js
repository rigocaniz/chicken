app.controller('crtlOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstMenu = [
		{ idMenu : 1, menu : 'Pollo Frito', img : 'pollo.png' },
		{ idMenu : 2, menu : 'Papas Fritas', img : 'papas.png' },
		{ idMenu : 3, menu : 'Pizza', img : 'pizza.png' },
		{ idMenu : 4, menu : 'Hamburguesa', img : 'hamburguesa.png' },
	];

	$scope.dialOrden        = $modal({scope: $scope,template:'dial.orden.html', show: false});
	$scope.dialMenuCantidad = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false});

	$timeout(function () {
		$scope.dialOrden.show();
	});

	$scope.menuActual = {};
	$scope.seleccionarMenu = function ( menu ) {
		$scope.menuActual          = angular.copy( menu );
		$scope.menuActual.cantidad = 1;
		$scope.dialOrden.hide();
		$scope.dialMenuCantidad.show();
	};
});