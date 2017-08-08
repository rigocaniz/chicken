app.controller('facturaCtrl', function( $scope, $http, $modal ){
	$scope.dialCliente  = $modal({scope: $scope,template:'dial.nuevo.cliente.html', show: false, backdrop:false, keyboard: false });
	
	$scope.nuevoCliente = function(){
		$scope.dialCliente.show();
	}
});