app.controller('crtlOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstTipoServicio = [];
	$scope.lstTipoMenu     = [];
	$scope.lstMenu = [
		{ idMenu : 1, menu : 'Pollo Frito', img : 'pollo.png' },
		{ idMenu : 2, menu : 'Papas Fritas', img : 'papas.png' },
		{ idMenu : 3, menu : 'Pizza', img : 'pizza.png' },
		{ idMenu : 4, menu : 'Hamburguesa', img : 'hamburguesa.png' },
	];

	$scope.ordenActual = {
		noTicket     : '',
		totalAgregar : 0,
		lstAgregar   : [],
		lstPedidos   : []
	};

	$scope.menuActual     = {
		lstPrecio : []
	};
	$scope.noTicket       = '';
	$scope.idTipoServicio = '';
	$scope.idTipoMenu     = '';
	$scope.accionOrden    = 'nuevo';

	// DIALOGOS
	$scope.dialOrden        = $modal({scope: $scope,template:'dial.orden.nueva.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenCliente = $modal({scope: $scope,template:'dial.orden.cliente.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenMenu    = $modal({scope: $scope,template:'dial.orden-menu.html', show: false, backdrop:false, keyboard: false });
	$scope.dialMenuCantidad = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false, backdrop:false, keyboard: false });

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
			console.log( data );
			if ( data.length ) {
				$scope.lstTipoMenu = data;
				$scope.idTipoMenu  = data[ 0 ].idTipoMenu;
			}
		});
	})();

	// #1 => MUESTRA DIALOGO INGRESO DE TICKET
	$scope.nuevaOrden = function () {
		$scope.noTicket = '';
		$scope.dialOrden.show();
		$timeout(function () {
			document.getElementById('noTicket').focus();
		},100);
	};

	// #2 => CREA UNA NUEVA ORDEN
	$scope.agregarOrden = function () {
		if ( parseInt( $scope.noTicket ) > 0 ) {
			$scope.accionOrden = 'nuevo';
			$scope.dialOrden.hide();
			$scope.dialOrdenCliente.show();
			$scope.ordenActual = {
				noTicket     : parseInt( $scope.noTicket ),
				totalAgregar : 0,
				lstAgregar   : [],
				lstPedidos   : []
			};
		} else {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Número de Ticket NO Válido', 'danger', 4);
		}
	};

	// MOSTRAR DIALOGO DE MENUS
	$scope.mostrarMenus = function () {
		$scope.dialOrdenMenu.show();
		$scope.dialOrdenCliente.hide()
	};

	$scope.consultaMenus = function () {
		if ( $scope.idTipoMenu > 0 ) {
			$scope.lstMenu = [];
			$http.post('consultas.php', { opcion : 'lstMenu', idTipoMenu : $scope.idTipoMenu })
			.success(function (data) {
				console.log( data );
				if ( data.length ) {
					$scope.lstMenu = data;
				}
			});
		}
	};

	$scope.$watch('idTipoMenu', function (_new) {
		$scope.consultaMenus();
	});

	// #3 => SELECCIONA MENU DE lst
	$scope.seleccionarMenu = function ( menu ) {
		$scope.menuActual           = angular.copy( menu );
		$scope.menuActual.cantidad  = 1;
		$scope.menuActual.precio    = 0;
		$scope.menuActual.lstPrecio = [];

		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', { opcion : 'cargarMenuPrecio', idMenu : menu.idMenu })
		.success(function (data) {
			if ( data.length )
				$scope.menuActual.lstPrecio = data;
		});

		//$scope.menuActual.idTipoServicio = '1';
		//$scope.menuActual.tipoServicio   = '';
		$scope.dialOrdenMenu.hide();
		$scope.dialMenuCantidad.show();
	};


	$scope.watchPrecio = function () {
		$scope.menuActual.precio = 0;

		if ( $scope.menuActual.lstPrecio.length && $scope.idTipoServicio > 0 ) {
			for ( var i = 0; i < $scope.menuActual.lstPrecio.length; i++ )
				if ( $scope.menuActual.lstPrecio[ i ].idTipoServicio == $scope.idTipoServicio )
					$scope.menuActual.precio = $scope.menuActual.lstPrecio[ i ].precio;
		}
	};

	$scope.$watch('idTipoServicio', function ( _new, _old ) {
		$scope.watchPrecio();
	});

	$scope.$watch('menuActual.lstPrecio', function ( _new, _old ) {
		$scope.watchPrecio();
	});

	// #4 => AGREGAR MENU Y CANTIDAD A ORDEN A AGREGAR
	$scope.agregarAPedido = function () {
		if ( !( $scope.menuActual.cantidad > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('La cantidad debe ser mayor a cero', 'danger', 4);
		}
		else if ( !( $scope.idTipoServicio > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Debe seleccionar un Tipo de Servicio', 'danger', 4);
		}
		else{
			var tipoServicio = $scope.descripcion( 'lstTipoServicio', 'idTipoServicio', $scope.idTipoServicio, 'tipoServicio' );

			$scope.ordenActual.totalAgregar += parseFloat( $scope.menuActual.precio );
			console.log( $scope.ordenActual.totalAgregar );

			$scope.ordenActual.lstAgregar.unshift({
				idMenu         : $scope.menuActual.idMenu,
				menu           : $scope.menuActual.menu,
				cantidad       : $scope.menuActual.cantidad,
				precio         : $scope.menuActual.precio,
				tipoServicio   : tipoServicio,
				idTipoServicio : $scope.idTipoServicio
			});
			$scope.dialOrdenCliente.show();
			$scope.dialMenuCantidad.hide();
		}
	};

	// RETORNA LA DESCRIPCION DE UN ELEMENTO DE ACUEROD AL id DEL ARREGLO
	$scope.descripcion = function ( arr, id, _value, _return ) {
		var descrip = '';
		for (var i = 0; i < $scope[ arr ].length; i++) {
			if ( $scope[ arr ][ i ][ id ] == _value )
				descrip = $scope[ arr ][ i ][ _return ];
		}

		return descrip;
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key ) {

		// SI NO EXISTE NINGUN DIALOGO ABIERTO
		if ( !$scope.modalOpen() ) {
			if ( key == 78 ) // {N}
				$scope.nuevaOrden();
		}

		// CUANDO ESTE ABIERTO ALGUN CUADRO DE DIALOGO
		else{

			// CUANDO EL DIALOGO DE NUEVA ORDEN ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_orden_cliente' ) ) {
				if ( key == 77 ) // {M}
					$scope.mostrarMenus();
			}

			// CUANDO EL DIALOGO DE CANTIDAD DE ORDEN SELECCIONADA ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_menu_cantidad' ) ) {
				if ( key == 65 ) // {A}
					$scope.agregarAPedido();

				else if ( key == 189 && $scope.menuActual.cantidad > 1 ) // {-}
					$scope.menuActual.cantidad--;

				else if ( key == 187 ) // {+}
					$scope.menuActual.cantidad++;

				// SELECCION DE TIPO DE SERVICIO
				else if ( key == 82 ) // {R}
					$scope.idTipoServicio = 1;

				else if ( key == 76 ) // {L}
					$scope.idTipoServicio = 2;

				else if ( key == 68 ) // {D}
					$scope.idTipoServicio = 3;

				else if ( key == 27 ) { // {esc}
					$scope.dialMenuCantidad.hide();
					$scope.dialOrdenMenu.show()
				}
			}
		}
		console.log( key );
	});

	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
});