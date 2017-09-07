app.controller('crtlEvento', function( $scope, $http, $timeout, $modal ){

	$scope.tipo = 'menu';
	$scope.lstMenu = [];

	$scope.menu = {
		cantidad       : 0,
		precioUnitario : 0,
		otroMenu 	   : '',
		idMenu 		   : null,
		comentario     : ''
	};

	// DIALOGOS
	$scope.dialOrden = $modal({scope: $scope,template:'dl.evento.html', show: false, backdrop:false, keyboard: false });

	$scope.consultarMenu = function () {
		var datos            = null;
		$scope.lstMenu       = [];
		$scope.menu.otroMenu = "";
		$scope.menu.idMenu   = '';
		
		if ( $scope.tipo == 'menu' ) 
			datos = { opcion : 'lstMenu', idTipoMenu : 0, idEstadoMenu : 1 };

		if ( $scope.tipo == 'combo' ) 
			datos = { opcion : 'lstCombo', idEstadoMenu : 1 };
	
		// SI SE CONSULTA INFORMACION
		if ( datos != null )
		{
			$http.post('consultas.php', datos )
			.success(function (data) {
				console.log( data );

				if ( $scope.tipo == 'menu' ) 
					$scope.lstMenu = $scope.arrayLst( data, 'idMenu', 'menu' );

				else if ( $scope.tipo == 'combo' )
					$scope.lstMenu = $scope.arrayLst( data, 'idCombo', 'combo' );

				$timeout(function () {

					// SELECCIONA LA PRIMERA OPCION
					if ( $scope.lstMenu.length )
						$scope.menu.idMenu = $scope.lstMenu[ 0 ].idMenu;
				});
			});
		}
	};

	$scope.arrayLst = function ( _array, id, descr ) {
		var array = [];

		for (var ix = 0; ix < _array.length; ix++)
			array.push({
				idMenu : _array[ ix ][ id ],
				menu   : _array[ ix ][ descr ],
				imagen : _array[ ix ].imagen
			});

		return array;
	};

	$scope.$watch('tipo', function (_new) {
		$scope.consultarMenu();
	});
	


	$timeout(function () {
		$scope.dialOrden.show();
	});

	($scope.init = function () {
		// CONSULTA TIPO DE SERVICIOS
		$http.post('consultas.php', { opcion:'catTiposServicio'})
		.success(function (data) {
			if ( data.length ) {
				$scope.lstTipoServicio = data;
				$scope.idTipoServicio  = data[ 0 ].idTipoServicio;
			}
		});

		// CONSULTA TIPO DE MENU
		$http.post('consultas.php', { opcion:'catTipoMenu'})
		.success(function (data) {
			if ( data.length ) {
				$scope.lstTipoMenu = data;
				$scope.idTipoMenu  = data[ 0 ].idTipoMenu;
			}
		});
	})();

});