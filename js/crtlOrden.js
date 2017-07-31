app.controller('crtlOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstTipoServicio = [];
	$scope.lstMenu = [
		{ idMenu : 1, menu : 'Pollo Frito', img : 'pollo.png' },
		{ idMenu : 2, menu : 'Papas Fritas', img : 'papas.png' },
		{ idMenu : 3, menu : 'Pizza', img : 'pizza.png' },
		{ idMenu : 4, menu : 'Hamburguesa', img : 'hamburguesa.png' },
	];

	$scope.ordenActual = {
		noTicket   : '',
		lstAgregar : [],
		lstPedidos : []
	};

	$scope.menuActual = {};
	$scope.noTicket   = '';

	// DIALOGOS
	$scope.dialOrden        = $modal({scope: $scope,template:'dial.orden.nueva.html', show: false, backdrop:false});
	$scope.dialOrdenCliente = $modal({scope: $scope,template:'dial.orden.cliente.html', show: false, backdrop:false});
	$scope.dialOrdenMenu    = $modal({scope: $scope,template:'dial.orden-menu.html', show: false, backdrop:false});
	$scope.dialMenuCantidad = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false, backdrop:false});

	// CONSULTA TIPO DE SERVICIOS
	$http.post('consultas.php', { opcion:'catTiposServicio'})
	.success(function (data) {
		console.log( data );
		$scope.lstTipoServicio = data;
	});

	// MUESTRA DIALOGO INGRESO DE TICKET
	$scope.nuevaOrden = function () {
		$scope.noTicket = '';
		$scope.dialOrden.show();
		$timeout(function () {
			document.getElementById('noTicket').focus();
		},100);
	};

	$scope.agregarOrden = function () {
		if ( parseInt( $scope.noTicket ) > 0 ) {
			$scope.dialOrden.hide();
			$scope.dialOrdenCliente.show();
			$scope.ordenActual = {
				noTicket   : parseInt( $scope.noTicket ),
				lstAgregar : [ {idMenu : 3, menu : "mi menu", cantidad : 5} ],
				lstPedidos : []
			};
		} else {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Número de Ticket NO Válido', 'danger', 4);
		}
	};

	$scope.seleccionarMenu = function ( menu ) {
		$scope.menuActual                = angular.copy( menu );
		$scope.menuActual.cantidad       = 1;
		$scope.menuActual.idTipoServicio = '1';
		$scope.menuActual.tipoServicio   = '';
		$scope.dialOrdenMenu.hide();
		$scope.dialMenuCantidad.show();
	};

	$scope.agregarAPedido = function () {
		if ( !( $scope.menuActual.cantidad > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('La cantidad debe ser mayor a cero', 'danger', 4);
		}
		else if ( !( $scope.menuActual.idTipoServicio > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Debe seleccionar un Tipo de Servicio', 'danger', 4);
		}
		else{
			var tipoServicio = $scope.descripcion( 'lstTipoServicio', 'idTipoServicio', $scope.menuActual.idTipoServicio, 'tipoServicio' );

			$scope.ordenActual.lstAgregar.unshift({
				idMenu         : $scope.menuActual.idMenu,
				menu           : $scope.menuActual.menu,
				cantidad       : $scope.menuActual.cantidad,
				tipoServicio   : tipoServicio,
				idTipoServicio : $scope.menuActual.idTipoServicio
			});
			$scope.dialOrdenCliente.show();
			$scope.dialMenuCantidad.hide();
		}
	};

	$scope.descripcion = function ( arr, id, _value, _return ) {
		var descrip = '';
		for (var i = 0; i < $scope[ arr ].length; i++) {
			if ( $scope[ arr ][ i ][ id ] == _value )
				descrip = $scope[ arr ][ i ][ _return ];
		}

		return descrip;
	};

	$timeout(function () {
		//$scope.nuevaOrden();
	});
});