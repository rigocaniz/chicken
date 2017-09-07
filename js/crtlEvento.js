app.controller('crtlEvento', function( $scope, $http, $timeout, $modal ){

	$scope.tipo       = 'menu';
	$scope.lstMenu    = [];
	$scope.accionMenu = '';
	$scope.menu = {
		cantidad       : 0,
		precioUnitario : 0,
		otroMenu 	   : '',
		idMenu 		   : null,
		comentario     : ''
	};

	$scope.busquedaCliente = '';
	$scope.evento = {
		evento         : '',
		idCliente      : '',
		nombreCliente  : '',
		fechaEvento    : null,
		horaInicio     : null,
		horaFinal      : null,
		anticipo       : 0,
		numeroPersonas : 0,
		observacion    : ''
	};

	// DIALOGOS
	$scope.dialOrden = $modal({scope: $scope,template:'dl.evento.html', show: false, backdrop:false, keyboard: false });


	$scope.consultarCliente = function () {
		if ( !( $scope.busquedaCliente.length > 3 ) )
			return false;

		$http.post('consultas.php', { opcion : 'consultarCliente', valor: $scope.busquedaCliente } )
		.success(function (data) {
			console.log( data );
		});
	};

	$scope.$watch('busquedaCliente', function (_new) {
		$scope.consultarCliente();
	});



	// CONSULTA MENUS O COMBOS
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

	// AUXILIAR PARA PREPARAR ARREGLO DE LISTADO OBTENIDO
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

	// SI CAMBIA EL TIPO SE REALIZA LA CONSULTA DE MENU
	$scope.$watch('tipo', function (_new) {
		if ( _new.length ) {
			$scope.consultarMenu();

			$timeout(function () {
				if ( _new == 'otroMenu' )
					document.getElementById('inputMenu').focus();
				else
					document.getElementById('selectMenu').focus();
			});
		}
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