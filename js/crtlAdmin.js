app.controller('crtlAdmin', function( $scope , $http){
	$scope.btnMenu = 0;
	$scope.listaMenu = function(){
		$http.post('consultas.php',{
			opcion:'lstMenu'
		}).success(function(data){
			$scope.lstMenus = data;
		})
	}

	$scope.listaCombo = function(){
		$http.post('consultas.php',{
			opcion:'lstCombo'
		}).success(function(data){
			console.log(data);
			$scope.lstCombos = data;
		})
	}
});