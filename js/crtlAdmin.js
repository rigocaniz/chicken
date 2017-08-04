app.controller('crtlAdmin', function( $scope , $http, $modal ){

	$scope.inventarioMenu = 1;

	$scope.dialIngreso     = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdministrar = $modal({scope: $scope,template:'dialAdmin.ingreso.html', show: false, backdrop: 'static'});

	$scope.dialAdministrarAbrir = function(){
		$scope.dialAdministrar.show();
	};

	$scope.dialAdministrarCerrar = function(){
		$scope.dialAdministrar.hide();
	};

	$scope.inicio = function()
	{
		
	}

	$scope.filter = {
		pagina: 1,
		limite: 25,
		orden: 'ASC'
	};

	$scope.filtro = {
		filter : [
			{ filter: 'idMedida', value : 8 },
		],
		order : [
			{ by: 'idMedia', order: 'asc' },
		],
		limit : 25,
		page : 1,
	};

	$scope.editarAccion = function( id, accion, producto ){
		console.log( 'producto::: ', producto );

		$scope.id     = id;
		$scope.accion = accion;

		if ( $scope.id > 0 ){

			$http.post('consultas.php',{
				opcion     : 'cargarProducto',
				idProducto : $scope.id
			}).success(function(data){
				console.log(data);
				$scope.producto = data;
			})

			$scope.accion = 'update';
		}

		$scope.dialAdministrarAbrir();
	};



	$scope.listaMenu = function(){
		$http.post('consultas.php',{
			opcion : 'lstMenu',
			filtro : $scope.filtro
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