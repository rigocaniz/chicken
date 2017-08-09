app.controller('crtlOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstTipoServicio = [];
	$scope.lstTipoMenu     = [];
	$scope.lstMenu         = [];
	$scope.noTicket        = '';
	$scope.idTipoServicio  = '';
	$scope.idTipoMenu      = '';
	$scope.accionOrden     = 'nuevo';
	$scope.idEstadoOrden   = 1;

	$scope.ordenActual = {
		idOrdenCliente : 0,
		noTicket       : '',
		totalAgregar   : 0,
		lstAgregar     : [],
		lstPedidos     : []
	};

	$scope.menuActual     = {
		lstPrecio : []
	};

	// DIALOGOS
	$scope.dialOrden         = $modal({scope: $scope,template:'dial.orden.nueva.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenCliente  = $modal({scope: $scope,template:'dial.orden.cliente.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenMenu     = $modal({scope: $scope,template:'dial.orden-menu.html', show: false, backdrop:false, keyboard: false });
	$scope.dialMenuCantidad  = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenBusqueda = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: false });

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

	$scope.modalBuscar = function () {
		$scope.dialOrdenBusqueda.show();
		$timeout(function () {
			document.getElementById('inputSearch').focus();
		},200);
	};

	// CONSULTA ORDENES
	$scope.lstOrdenCliente = [];
	$scope.consultaOrdenCliente = function () {
		if ( $scope.$parent.loading )
			return false;

		if ( $scope.idEstadoOrden > 0 ) {
			$scope.lstOrdenCliente = [];
			$scope.$parent.loading = true; // cargando...
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { opcion : 'lstOrdenCliente', idEstadoOrden : $scope.idEstadoOrden })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...
				$scope.lstOrdenCliente = data;
			});
		}
	};

	$scope.$watch('idEstadoOrden', function (_new) {
		$scope.consultaOrdenCliente();
	});


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
		if ( $scope.$parent.loading )
			return false;
		
		if ( parseInt( $scope.noTicket ) > 0 ) {

			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion : 'consultaOrdenCliente',
				accion : 'insert',
				datos : {
					numeroTicket       : $scope.noTicket,
					usuarioResponsable : ''
				}
			})
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( data.respuesta == 'success' ) {
					$scope.accionOrden = 'nuevo';
					
					// MUESTRA DIALOGO DE ORDEN GENERADA
					$scope.dialOrden.hide();
					$scope.dialOrdenCliente.show();

					$scope.ordenActual = {
						idOrdenCliente : data.data,
						noTicket     : parseInt( $scope.noTicket ),
						totalAgregar : 0,
						lstAgregar   : [],
						lstPedidos   : []
					};
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				}
			});
		} else {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Número de Ticket NO Válido', 'danger', 4);
		}
	};

	// MOSTRAR DIALOGO DE MENUS
	$scope.tipoMenu = "menu";
	$scope.mostrarMenus = function ( tipoMenu ) {

		if ( tipoMenu == 'menu' )
			$scope.tipoMenu = "menu";
		
		else
			$scope.tipoMenu = "combo";
		
		$scope.consultaMenus();
		$scope.dialOrdenMenu.show();
		$scope.dialOrdenCliente.hide()
	};

	// CONSULTA MENUS
	$scope.lstCombo = [];
	$scope.consultaMenus = function () {
		if ( $scope.$parent.loading )
			return false;

		// SI ES MENU
		if ( $scope.tipoMenu == 'menu' && $scope.idTipoMenu > 0 ) {
			$scope.$parent.loading = true; // cargando...
			$scope.lstMenu = [];
			$http.post('consultas.php', { opcion : 'lstMenu', idTipoMenu : $scope.idTipoMenu, idEstadoMenu : 1 })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...
				if ( data.length ) {
					$scope.lstMenu = data;
				}
			});
		}
		// SI ES COMBO
		else if ( $scope.tipoMenu == 'combo' ) {
			$scope.$parent.loading = true; // cargando...
			$scope.lstCombo = [];
			$http.post('consultas.php', { opcion : 'lstCombo', idEstadoMenu : 1 })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...
				if ( data.length ) {
					$scope.lstCombo = data;
				}
			});
		}
	};

	// #3 => SELECCIONA MENU DE lst
	$scope.seleccionarMenu = function ( menu ) {
		if ( $scope.$parent.loading )
			return false;

		var datos;

		if ( $scope.tipoMenu == 'menu' ) {
			$scope.menuActual = {
				idMenu : menu.idMenu,
				menu   : menu.menu,
				imagen : menu.imagen,
			};

			datos = { opcion : 'cargarMenuPrecio', idMenu : menu.idMenu };
		}
		else if ( $scope.tipoMenu == 'combo' ) {
			$scope.menuActual = {
				idMenu : menu.idCombo,
				menu   : menu.combo,
				imagen : menu.imagen,
			};
			datos = { opcion : 'cargarComboPrecio', idCombo : menu.idCombo };
		}

		$scope.menuActual.cantidad  = 1;
		$scope.menuActual.precio    = 0;
		$scope.menuActual.lstPrecio = [];

		$scope.$parent.loading = true; // cargando...
		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', datos )
		.success(function (data) {
			$scope.$parent.loading = false; // cargando...
			if ( data.length )
				$scope.menuActual.lstPrecio = data;
		});

		$scope.dialOrdenMenu.hide();
		$scope.dialMenuCantidad.show();
	};

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

			var subTotal = parseFloat( $scope.menuActual.precio ) * $scope.menuActual.cantidad;
			$scope.ordenActual.totalAgregar += subTotal;

			var index = -1;
			for (var i = 0; i < $scope.ordenActual.lstAgregar.length; i++) {
				if ( $scope.ordenActual.lstAgregar[ i ].idMenu == $scope.menuActual.idMenu 
					&& $scope.ordenActual.lstAgregar[ i ].idTipoServicio == $scope.idTipoServicio
					&& $scope.ordenActual.lstAgregar[ i ].tipoMenu == $scope.tipoMenu 
				) {
					index = i;
					break;
				}
			}

			if ( index >= 0 ) {
				$scope.ordenActual.lstAgregar[ index ].cantidad += $scope.menuActual.cantidad;
			}
			else{
				$scope.ordenActual.lstAgregar.unshift({
					idMenu         : $scope.menuActual.idMenu,
					menu           : $scope.menuActual.menu,
					cantidad       : $scope.menuActual.cantidad,
					precio         : $scope.menuActual.precio,
					tipoServicio   : tipoServicio,
					idTipoServicio : $scope.idTipoServicio,
					tipoMenu       : $scope.tipoMenu
				});
			}
			$scope.dialOrdenCliente.show();
			$scope.dialMenuCantidad.hide();
		}
	};

	// #5 => AGREGAR MENU Y CANTIDAD A ORDEN A AGREGAR
	$scope.guardarOrden = function () {
		if ( $scope.$parent.loading )
			return false;
		
		// LISTA A AGREGAR
		if ( $scope.ordenActual.lstAgregar.length ) {
			var lstAgregar = [];

			for (var i = 0; i < $scope.ordenActual.lstAgregar.length; i++) {
				lstAgregar.push({
					idMenu         : $scope.ordenActual.lstAgregar[ i ].idMenu,
					cantidad       : $scope.ordenActual.lstAgregar[ i ].cantidad,
					idTipoServicio : $scope.ordenActual.lstAgregar[ i ].idTipoServicio,
					tipoMenu       : $scope.ordenActual.lstAgregar[ i ].tipoMenu
				});
			}

			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion         : 'guardarDetalleOrden',
				idOrdenCliente : $scope.ordenActual.idOrdenCliente,
				lstAgregar     : lstAgregar
			})
			.success(function (data) {
				console.log( data );

				$scope.$parent.loading = false; // cargando...

				if ( data.respuesta == 'success' ) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
					
					$scope.dialOrdenCliente.hide();
					$scope.ordenActual.lstAgregar = [];
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				}
			});
		}
		// SI NO SE A AGREGADO NINGUNA ORDEN
		else{
			alertify.set('notifier','position', 'top-right');
			alertify.notify('No agregado ningún menú', 'danger', 4);
		}
	};




	// SUMA O RESTA UNO A LA CANTIDAD DE UN MENU
	$scope.ordenCantidad = function ( index, sumar, cantidad, precio ) {
		if ( sumar ) {
			$scope.ordenActual.lstAgregar[ index ].cantidad++;
			$scope.ordenActual.totalAgregar += precio;
		} else if ( cantidad > 1 ) {
			$scope.ordenActual.lstAgregar[ index ].cantidad--;
			$scope.ordenActual.totalAgregar -= precio;
		}
	};

	// QUITAR ELEMENTO DE ORDEN
	$scope.quitarElemento = function ( index, cantidad, precio ) {
		$scope.ordenActual.totalAgregar -= ( cantidad * precio );
		$scope.ordenActual.lstAgregar.splice( index, 1 );
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

	$scope.watchPrecio = function () {
		$scope.menuActual.precio = 0;

		if ( $scope.menuActual.lstPrecio.length && $scope.idTipoServicio > 0 ) {
			for ( var i = 0; i < $scope.menuActual.lstPrecio.length; i++ )
				if ( $scope.menuActual.lstPrecio[ i ].idTipoServicio == $scope.idTipoServicio ) {
					$scope.menuActual.precio = $scope.menuActual.lstPrecio[ i ].precio;
					break;
				}
		}
	};

	// SI CAMBIA EL Tipo de Menú
	$scope.$watch('idTipoMenu', function (_new) {
		$scope.consultaMenus();
	});

	// SI CAMBIA EL Tipo de Servicio
	$scope.$watch('idTipoServicio', function ( _new, _old ) {
		$scope.watchPrecio();
	});

	// SI CAMBIA LA Lista de Precio
	$scope.$watch('menuActual.lstPrecio', function ( _new, _old ) {
		$scope.watchPrecio();
	});

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key ) {

		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

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
					$scope.mostrarMenus('menu');

				if ( key == 67 ) // {C}
					$scope.mostrarMenus('combo');
			}

			// CUANDO EL DIALOGO DE CANTIDAD DE ORDEN SELECCIONADA ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_menu_cantidad' ) ) {
				if ( key == 65 || key == 13 ) // {A}
					$scope.agregarAPedido();

				else if ( key == 189 && $scope.menuActual.cantidad > 1 ) // {-}
					$scope.menuActual.cantidad--;

				else if ( key == 187 ) // {+}
					$scope.menuActual.cantidad++;

				// SELECCION DE TIPO DE SERVICIO
				else if ( key == 76 ) // {L}
					$scope.idTipoServicio = 1;

				else if ( key == 82 ) // {R}
					$scope.idTipoServicio = 2;

				else if ( key == 68 ) // {D}
					$scope.idTipoServicio = 3;

				else if ( key == 27 ) { // {esc}
					$scope.dialMenuCantidad.hide();
					$scope.dialOrdenMenu.show()
				}
			}
		}
	});

	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
});