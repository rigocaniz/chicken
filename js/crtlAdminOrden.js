app.controller('crtlAdminOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstTipoServicio = [];
	$scope.lstTipoMenu     = [];
	$scope.lstMenu         = [];
	$scope.noTicket        = '';
	$scope.idTipoServicio  = '';
	$scope.idTipoMenu      = '';
	$scope.accionOrden     = 'nuevo';

	$scope.minutosAlerta = 20;


	$scope.idDestinoMenu = 1;
	$scope.idEstadoOrden   = 1;
	$scope.tipoVista = 'ambos';



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


	$scope.lstMenusMaster = [];
	// CONSULTA INFORMACION DE ORDENES
	($scope.consultaOrden = function () {
		$scope.lstMenusMaster = [];
		$http.post('consultas.php', { 
			opcion               : 'lstDetalleDestinos',
			idEstadoDetalleOrden : $scope.idEstadoOrden,
			idDestinoMenu        : $scope.idDestinoMenu
		})
		.success(function (data) {
			console.log( data );
			$scope.lstMenusMaster = data;
			$timeout(function () {
				$scope.lstPorMenu();
			});
		});
	})();

	$scope.lstMenus = [];
	$scope.ixMenuActual = -1;
	$scope.lstPorMenu = function () {
		$scope.lstMenus = [];
		for (var ip = 0; ip < $scope.lstMenusMaster.length; ip++) {
			var ixMenu = -1;

			for (var im = 0; im < $scope.lstMenus.length; im++) {
				if ( $scope.lstMenus[ im ].idMenu == $scope.lstMenusMaster[ ip ].idMenu ) {
					ixMenu = im;
					break;
				}
			}

			// SI NO EXISTE SE CREA DATOS DE MENU
			if ( ixMenu == -1 ) {
				ixMenu = $scope.lstMenus.length;
				$scope.lstMenus.push({
					idMenu       : $scope.lstMenusMaster[ ip ].idMenu,
					codigoMenu   : $scope.lstMenusMaster[ ip ].codigoMenu,
					menu         : $scope.lstMenusMaster[ ip ].menu,
					imagen       : $scope.lstMenusMaster[ ip ].imagen,
					numMenus     : 0,
					primerTiempo : $scope.lstMenusMaster[ ip ].fechaRegistro,
					detalle      : []
				});
			}
			
			$scope.lstMenus[ ixMenu ].detalle.push({
				perteneceCombo      : $scope.lstMenusMaster[ ip ].perteneceCombo,
				idDetalleOrdenMenu  : $scope.lstMenusMaster[ ip ].idDetalleOrdenMenu,
				cantidad            : $scope.lstMenusMaster[ ip ].cantidad,
				fechaRegistro       : $scope.lstMenusMaster[ ip ].fechaRegistro,
				tipoServicio        : $scope.lstMenusMaster[ ip ].tipoServicio,
				idTipoServicio      : $scope.lstMenusMaster[ ip ].idTipoServicio,
				idCombo             : $scope.lstMenusMaster[ ip ].idCombo,
				idDetalleOrdenCombo : $scope.lstMenusMaster[ ip ].idDetalleOrdenCombo,
				imagenCombo         : $scope.lstMenusMaster[ ip ].imagenCombo
			});

			$scope.lstMenus[ ixMenu ].numMenus    += parseInt( $scope.lstMenusMaster[ ip ].cantidad );
			//$scope.lstMenus[ ixMenu ].primerTiempo = $scope.lstMenusMaster[ ip ].fechaRegistro;
		}
		console.log( $scope.lstMenus );
	};




	$scope.lstOrdenes = [];
	// INFORMACION DE NODEJS
	$scope.$on('infoNode', function( event, datos ) {
		console.log( 'DTS::', datos );

		if ( datos.data && Array.isArray( datos.data.lstMenuAgregado ) ) {

			for (var i = 0; i < datos.data.lstMenuAgregado.length; i++) {
				$scope.lstOrdenes.push(
					datos.data.lstMenuAgregado[i]
				);
			}
		}

		$scope.$apply();
	});

	$scope.seleccion = {
		si    : false,
		count : 0
	};
	$scope.accionItems = function () {
		$scope.seleccion.si    = false;
		$scope.seleccion.count = 0;
		
		for (var i = 0; i < $scope.lstOrdenes.length; i++) {
			if ( $scope.lstOrdenes[ i ].selected ) {
				$scope.seleccion.si = true;
				$scope.seleccion.count++;
			}
		}
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


	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% DIALOGO PARA BUSQUEDA DE ORDEN %%%%%%%%%%%%%%%%%%%%%% */
	$scope.modalBuscar = function () {
		$scope.dialOrdenBusqueda.show();
		$timeout(function () {
			document.getElementById('inputSearch').focus();
		},200);
	};

	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% DIALOGO PARA MAS INFORMACION DE ORDEN %%%%%%%%%%%%%%%%%%%%%% */
	$scope.infoOrden = {};
	$scope.modalInfo = function ( orden ) {
		$scope.infoOrden = orden;
		$http.post('consultas.php', { opcion : 'lstDetalleOrdenCliente', idOrdenCliente : orden.idOrdenCliente })
		.success(function (data) {
			console.log( data );
			$scope.infoOrden.lstOrden = data;
		});
		$scope.dialOrdenInformacion.show();
	};




	/* ++++++++++++++++++ AUXILIAR ++++++++++++++++ */
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

	/* ++++++++++++++++++ UTIL ++++++++++++++++ */
	// ***RETORNA LA DESCRIPCION DE UN ELEMENTO DE ACUEROD AL id DEL ARREGLO
	$scope.descripcion = function ( arr, id, _value, _return ) {
		var descrip = '';
		for (var i = 0; i < $scope[ arr ].length; i++) {
			if ( $scope[ arr ][ i ][ id ] == _value )
				descrip = $scope[ arr ][ i ][ _return ];
		}

		return descrip;
	};

	// SI CAMBIA EL TIPO DE SERVICIO
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