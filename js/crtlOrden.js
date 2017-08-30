app.controller('crtlOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstTipoServicio = [];
	$scope.lstTipoMenu     = [];
	$scope.lstMenu         = [];
	$scope.noTicket        = 0;
	$scope.buscarTicket    = 0;
	$scope.idTipoServicio  = '';
	$scope.idTipoMenu      = '';
	$scope.accionOrden     = 'nuevo';
	$scope.idEstadoOrden   = 1;
	$scope.todoDetalle 	   = false;

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
	$scope.dialOrden            = $modal({scope: $scope,template:'dial.orden.nueva.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenCliente     = $modal({scope: $scope,template:'dial.orden.cliente.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenMenu        = $modal({scope: $scope,template:'dial.orden-menu.html', show: false, backdrop:false, keyboard: false });
	$scope.dialMenuCantidad     = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenBusqueda    = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrdenCancelar    = $modal({scope: $scope,template:'dial.orden.cancelar.html', show: false, backdrop:false, keyboard: true });
	$scope.dialCancelarDetalle  = $modal({scope: $scope,template:'dial.orden.cancelar-parcial.html', show: false, backdrop:false, keyboard: true });

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


	$scope.lstTicketBusqueda = [];
	$scope.buscarOrdenTicket = function () {
		if ( $scope.$parent.loading )
			return false;

		if ( $scope.buscarTicket > 0 ) {
			$scope.lstTicketBusqueda = [];
			$scope.$parent.loading = true; // cargando...
			
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { opcion : 'busquedaTicket', ticket : $scope.buscarTicket })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data ) && data.length ) {
					$scope.buscarTicket = 0;
					$scope.lstTicketBusqueda = data;
					$scope.dialOrdenBusqueda.show();
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( "No se encontro información", 'info', 2 );
				}
			});
		}
	};


	// CONSULTA ORDENES
	$scope.lstOrdenCliente = [];
	$scope.consultaOrdenCliente = function () {
		if ( $scope.$parent.loading )
			return false;

		$scope.lstOrdenCliente = [];
		if ( $scope.idEstadoOrden > 0 ) {
			$scope.miIndex         = -1;
			$scope.$parent.loading = true; // cargando...
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { opcion : 'lstOrdenCliente', idEstadoOrden : $scope.idEstadoOrden })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data ) )
					$scope.lstOrdenCliente = data;

				$timeout(function () {
					// SI EXISTE AL MENOS UNA ORDEN SELECCIONA LA PRIMERA
					if ( $scope.lstOrdenCliente.length )
						$scope.miIndex = 0;
				});
			});
		}
	};

	// SELECCION DE TICKET PARA MOSTRAR DETALLE DE TICKET
	$scope.seleccionarTicket = function ( idOrdenCliente ) {
		$scope.miIndex = -1;

		// SI ORDEN DE CLIENTE ES VALIDO
		if ( idOrdenCliente && idOrdenCliente > 0 ) {
			for (var i = 0; i < $scope.lstOrdenCliente.length; i++) {
				if ( $scope.lstOrdenCliente[ i ].idOrdenCliente == idOrdenCliente ) {
					$scope.miIndex = i;
					break;
				}
			}
		}
	};

	$scope.seleccionarDeBusqueda = function ( orden ) {
		$scope.miIndex = -1;
		$scope.dialOrdenBusqueda.hide();
		$timeout(function () {
			$scope.modalInfo( orden, true );
		});
	};

	// SI ES SUBIR O BAJAR ORDEN
	$scope.downUpOrdenes = function ( subir ) {
		if ( !$scope.lstOrdenCliente.length ) return false;

		// SI ES SUBIR
		if ( subir && $scope.miIndex > 0 && !$scope.$parent.loading )
			$scope.miIndex -= 1;

		// SI ES BAJAR
		else if ( !subir && ( $scope.miIndex + 1 ) < $scope.lstOrdenCliente.length && !$scope.$parent.loading )
			$scope.miIndex += 1;
	};



	// #1 => MUESTRA DIALOGO INGRESO DE TICKET
	$scope.nuevaOrden = function () {
		$scope.noTicket = 0;
		$scope.dialOrden.show();
	};

	// 2. CONSULTA ORDEN YA CREADA
	$scope.consultaOrden = function ( orden ) {
		$scope.dialOrden.hide();
		$scope.dialOrdenCliente.show();
		$scope.accionOrden = 'modificar';

		$scope.ordenActual = {
			idOrdenCliente : orden.idOrdenCliente,
			noTicket       : orden.numeroTicket,
			totalAgregar   : 0,
			lstAgregar     : [],
			lstPedidos     : []
		};
	};

	// #2 => CREA UNA NUEVA ORDEN
	$scope.agregarOrden = function () {
		if ( $scope.$parent.loading )
			return false;

		$scope.codigoRapido = 0;
		
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
		if ( !( $scope.menuActual.precio > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Menú no disponible para este Tipo de Servicio', 'danger', 3);
		}
		else if ( !( $scope.menuActual.cantidad > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('La cantidad debe ser mayor a cero', 'danger', 2);
		}
		else if ( !( $scope.idTipoServicio > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Debe seleccionar un Tipo de Servicio', 'danger', 2);
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

	// #5 =>=>=>=>=>=> GUARDA DETALLE DE ORDEN DE CLIENTE <=<=<=<=<=<=<=<=<=<=
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
				lstAgregar     : lstAgregar,
				accionOrden    : $scope.accionOrden
			})
			.success(function (data) {
				console.log( data );

				$scope.$parent.loading = false; // cargando...

				if ( data.respuesta == 'success' ) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
					
					$scope.dialOrdenCliente.hide();
					$scope.ordenActual.lstAgregar = [];

					//$scope.lstOrdenCliente.push( data.data.ordenCliente );
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

	// CONSULTA MENU POR CODIGO
	$scope.consultaMenuPorCodigo = function () {
		if ( $scope.$parent.loading )
			return false;

		if ( $scope.codigoRapido > 0 ) {
			$scope.$parent.loading = true; // cargando...

			$http.post('consultas.php', { opcion : 'menuPorCodigo', codigoRapido : $scope.codigoRapido })
			.success(function (data) {
				console.log( data );

				$scope.$parent.loading = false; // cargando...
				$scope.codigoRapido    = 0;

				if ( data.tipoMenu != null ) {
					$scope.menuActual = data.menu;
					$scope.tipoMenu   = data.tipoMenu;
					$scope.dialOrdenCliente.hide();
					$scope.dialMenuCantidad.show();
				}
				
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify('Código no válido', 'danger', 2);
				}
			});
		}
	};


	// => CANCELAR ORDEN PRINCIPAL
	$scope.cancelarOrdenPrincipal = function ( idOrdenCliente ) {
		if ( $scope.$parent.loading )
			return false;

		if ( idOrdenCliente > 0 ) {

			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion : 'consultaOrdenCliente',
				accion : 'cancel',
				datos : { idOrdenCliente : idOrdenCliente }
			})
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == 'success' )
					$scope.dialOrdenCancelar.hide();
			});
		} else {
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Orden de Cliente no válida', 'danger', 4);
		}
	};

	// => CANCELAR ORDEN PARCIAL
	$scope.cancelarOrdenParcial = function ( idOrdenCliente, lstDetalle ) {
		if ( $scope.$parent.loading )
			return false;

		$scope.$parent.loading = true; // cargando...

		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', { 
			opcion         : 'cancelarOrdenParcial',
			idOrdenCliente : idOrdenCliente,
			lstDetalle     : lstDetalle
		})
		.success(function (data) {
			console.log( data );

			$scope.$parent.loading = false; // cargando...

			if ( data.mensaje != undefined ) {
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, 3 );

				if ( data.respuesta == 'success' )
					$scope.dialCancelarDetalle.hide();
			}
		});
	};

	// => CAMBIAR SERVICIO DE ORDEN
	$scope.cambiarServicio = function ( idOrdenCliente, lstDetalle, idTipoServicio ) {
		console.log( idOrdenCliente, lstDetalle, idTipoServicio );
		if ( $scope.$parent.loading )
			return false;

		$scope.$parent.loading = true; // cargando...

		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', { 
			opcion         : 'cambiarServicio',
			idOrdenCliente : idOrdenCliente,
			lstDetalle     : lstDetalle,
			idTipoServicio : idTipoServicio
		})
		.success(function (data) {
			console.log( data );

			$scope.$parent.loading = false; // cargando...

			if ( data.mensaje != undefined ) {
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, 3 );
			}
		});
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
	$scope.deBusqueda = false;
	$scope.modalInfo = function ( orden, deBusqueda ) {
		if ( $scope.$parent.loading )
			return false;

		// SI NO ES DE BUSQUEDA
		if ( deBusqueda == undefined ) {
			$scope.deBusqueda = false;
		}
		// SI ES DE BUSQUEDA
		else {
			$scope.idEstadoOrden = 0;
			$scope.deBusqueda    = true;
		}

		$scope.$parent.loading = true;
		$scope.infoOrden = orden;
		$http.post('consultas.php', { opcion : 'lstDetalleOrdenCliente', idOrdenCliente : orden.idOrdenCliente, todo : $scope.todoDetalle })
		.success(function (data) {
			$scope.$parent.loading    = false;
			if ( data.lst ) {
				$scope.infoOrden.lstOrden = data.lst;
				$scope.infoOrden.total    = data.total;
			}
		});
	};


	$scope.itemDetalle = {};
	// CANCELAR ORDEN
	$scope.cancelarDetalle = function ( itemDetalle ) {
		$scope.itemDetalle = itemDetalle;
		$scope.dialCancelarDetalle.show();
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

	// SI EL ESTADO DE ORDEN CAMBIA
	$scope.$watch('idEstadoOrden', function (_new) {
		if ( _new == 10 )
			$scope.todoDetalle = true;
		
		else
			$scope.todoDetalle = false;

		$scope.consultaOrdenCliente();
	});

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


	// SI CAMBIA EL INDEX DE PEDIDO
	$scope.$watch('miIndex', function ( _new ) {
		$scope.infoOrden = {};

		// SI ES UN INDEX VALIDO
		if ( _new >= 0 && $scope.lstOrdenCliente[ _new ] )
			$scope.modalInfo( $scope.lstOrdenCliente[ _new ] );
	});

	$scope.auxKeyTicket = function ( accion, numero, model1, model2 ) {
		if ( accion == 'number' ) {
			var numeroTxt    = numero.toString();

			if ( model2 != undefined ) {
				var noTicketTxt  = parseInt( $scope[ model1 ][ model2 ] || 0 ).toString();
				$scope[ model1 ][ model2 ] = parseInt( noTicketTxt + numeroTxt );
			}
			else{
				var noTicketTxt  = parseInt( $scope[ model1 ] || 0 ).toString();
				$scope[ model1 ] = parseInt( noTicketTxt + numeroTxt );
			}
		}

		if ( accion == 'supr' ){
			if ( model2 != undefined )
				$scope[ model1 ][ model2 ] = 0;
			else
				$scope[ model1 ] = 0;
		}
		
		if ( accion == 'back' ) {
			if ( model2 != undefined ) {
				var noTicketTxt  = parseInt( $scope[ model1 ][ model2 ] ).toString();
				noTicketTxt      = noTicketTxt.substr( 0, ( noTicketTxt.length - 1 ) );
				$scope[ model1 ][ model2 ] = parseInt( noTicketTxt );	
			}
			else{
				var noTicketTxt  = parseInt( $scope[ model1 ] ).toString();
				noTicketTxt      = noTicketTxt.substr( 0, ( noTicketTxt.length - 1 ) );
				$scope[ model1 ] = parseInt( noTicketTxt );
			}
		}
	};

	// ATAJOS PANTALLA PRINCIPAL
	$scope._keyInicioOrden = function ( key ) {
		if ( key == 78 ) // {N}
			$scope.nuevaOrden();

		if ( key == 38 ) // {UP} // ORDEN SIGUIENTE
			$scope.downUpOrdenes( true );

		if ( key == 40 ) // {DOWN} // ORDEN ANTERIOR
			$scope.downUpOrdenes( false );

		if ( key == 33 && $scope.lstOrdenCliente.length ) // {FIRST} // PRIMERA ORDEN
			$scope.miIndex = 0;

		if ( key == 34 && $scope.lstOrdenCliente.length ) // {LAST} // ULTIMA ORDEN
			$scope.miIndex = ( $scope.lstOrdenCliente.length - 1 );

		// ORDENES POR ESTADO
		if ( key == 80 ) $scope.idEstadoOrden = 1; // {P}
		if ( key == 69 ) $scope.idEstadoOrden = 2; // {E}
		if ( key == 70 ) $scope.idEstadoOrden = 3; // {F}
		if ( key == 67 ) $scope.idEstadoOrden = 10; // {C}

		// BUSQUEDA POR TICKET
		if ( key >= 48 && key <= 57 && !$("#buscarTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'buscarTicket' );

		if ( key >= 96 && key <= 105 && !$("#buscarTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'buscarTicket' );
		
		if ( key == 46 ) // {SUPR}
			$scope.auxKeyTicket( 'supr', 0, 'buscarTicket' );

		if ( key == 8 && !$("#buscarTicket").is(":focus") ) // {BACK}
			$scope.auxKeyTicket( 'back', 0, 'buscarTicket' );

		if ( key == 13 ) // {ENTER}
			$scope.buscarOrdenTicket();

		if ( key == 65 && $scope.infoOrden.idOrdenCliente > 0 ) // {O}
			$scope.consultaOrden( $scope.infoOrden );
	};

	// ATAJOS DIALOGO INGRESO DE TICKET
	$scope._keyDialTicket = function ( key ) {
		if ( key == 27 || key == 83 ) // {ESC} || {S}
			$scope.dialOrden.hide();

		if ( key >= 48 && key <= 57 && !$("#noTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'noTicket' );

		if ( key >= 96 && key <= 105 && !$("#noTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'noTicket' );
		
		if ( key == 46 ) // {SUPR}
			$scope.auxKeyTicket( 'supr', 0, 'noTicket' );

		if ( key == 8 && !$("#noTicket").is(":focus") ) // {BACK}
			$scope.auxKeyTicket( 'back', 0, 'noTicket' );

		if ( key == 13 || key == 65 ) // {ENTER} || {A}
			$scope.agregarOrden();
	};

	// ATAJOS DIALOGO ORDEN CLIENTE
	$scope._keyDialOrden = function ( key ) {
		if ( key == 77 ) // {M}
			$scope.mostrarMenus('menu');

		if ( key == 67 ) // {C}
			$scope.mostrarMenus('combo');

		// TECLA PARA CODIGO RAPIDO
		if ( key >= 48 && key <= 57 ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'codigoRapido' );

		if ( key >= 96 && key <= 105 ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'codigoRapido' );
		
		if ( key == 46 || key == 8 ) // {SUPR} || {BACK}
			$scope.auxKeyTicket( 'supr', 0, 'codigoRapido' );

		if ( key == 13 || key == 65 ) // {ENTER}
			$scope.consultaMenuPorCodigo();

		if ( key == 27 && $scope.accionOrden == 'modificar' ) // {ESC}
			$scope.dialOrdenCliente.hide();
	};

	$scope._keyDialMenuCantidad = function ( key ) {
		if ( key == 65 || key == 13 ) // {A} || {ENTER}
			$scope.agregarAPedido();

		else if ( ( key == 109 || key == 189 ) && $scope.menuActual.cantidad > 1 ) // {-}
			$scope.menuActual.cantidad--;

		else if ( key == 107 || key == 187 ) { // {+}
			if ( isNaN( $scope.menuActual.cantidad ) )
				$scope.menuActual.cantidad = 0;

			$scope.menuActual.cantidad++;
		} 

		// SELECCION DE TIPO DE SERVICIO
		else if ( key == 76 ) // {L}
			$scope.idTipoServicio = 1;

		else if ( key == 82 ) // {R}
			$scope.idTipoServicio = 2;

		else if ( key == 68 ) // {D}
			$scope.idTipoServicio = 3;

		else if ( key == 27 ) { // {ESC}
			$scope.dialMenuCantidad.hide();
			$scope.dialOrdenCliente.show();
		}

		else if ( key >= 48 && key <= 57 && !$("#cantidad_menu").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'menuActual', 'cantidad' );

		else if ( key >= 96 && key <= 105 && !$("#cantidad_menu").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'menuActual', 'cantidad' );
		
		else if ( key == 46 ) // {SUPR}
			$scope.auxKeyTicket( 'supr', 0, 'menuActual', 'cantidad' );

		else if ( key == 8 && !$("#cantidad_menu").is(":focus") ) // {BACK}
			$scope.auxKeyTicket( 'back', 0, 'menuActual', 'cantidad' );
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key ) {

		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// SI NO EXISTE NINGUN DIALOGO ABIERTO
		if ( !$scope.modalOpen() )
			$scope._keyInicioOrden( key );

		// CUANDO ESTE ABIERTO ALGUN CUADRO DE DIALOGO
		else{
			// ******** DIALOGO DE INGRESO DE TICKET ********
			if ( $scope.modalOpen( 'dial_orden_nueva' ) ) {
				$scope._keyDialTicket( key );
			}

			// ******** DIALOGO DE ORDEN DEL CLIENTE ********
			if ( $scope.modalOpen( 'dial_orden_cliente' ) ) {
				$scope._keyDialOrden( key );
			}

			// ******** DIALOGO DE LISTADO DE MENUS ********
			if ( $scope.modalOpen( 'dial_orden_menu' ) ) {
				if ( key == 27 ) { // {ESC}
					$scope.dialOrdenMenu.hide();
					$scope.dialOrdenCliente.show();
				}
			}

			// CUANDO EL DIALOGO DE CANTIDAD DE ORDEN SELECCIONADA ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_menu_cantidad' ) ) {
				$scope._keyDialMenuCantidad( key );
			}
		}
	});

	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};


	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% INFORMACION DE NODEJS %%%%%%%%%%%%%%%%%%%%%%%%%%%%  */
	$scope.$on('infoNode', function( event, datos ) {

		switch ( datos.accion ) {
			// SI SE AGREGO UNA ORDEN NUEVA
			case 'ordenNueva':

				// SI ESTA EN ESTADO PENDIENTE SE AGREGA A LA LISTA DE PENDIENTES
				if ( datos.data && datos.data.ordenCliente && $scope.idEstadoOrden == 1 ) {
					$scope.lstOrdenCliente.push( datos.data.ordenCliente );

					// SELECCIONAR LA ORDEN AGREGADA SI ES LA UNICA
					if ( $scope.lstOrdenCliente.length ==1 )
						$scope.miIndex = 0;
				}
			break;

			// SI SE AGREGA OTROS MENUS A ORDEN EXISTENTE
			case 'ordenAgregar':

				// SI ESTA EN ESTADO PENDIENTE SE AGREGA A LA LISTA DE PENDIENTES
				if ( datos.data && datos.data.ordenCliente && ( $scope.idEstadoOrden == 1 || $scope.idEstadoOrden == 2 ) ) {

					var orden = datos.data.ordenCliente;

					// OBTIENE INDEX DE ORDEN
					var index = $scope.indexArray( 'lstOrdenCliente', 'idOrdenCliente', orden.idOrdenCliente );

					// SI EXISTE LA ORDEN
					if ( index >= 0 ) {
						$scope.lstOrdenCliente[ index ].numeroTicket       = orden.numeroTicket;
						$scope.lstOrdenCliente[ index ].usuarioResponsable = orden.usuarioResponsable;
						$scope.lstOrdenCliente[ index ].numMenu            = orden.numMenu;

						// SI ES LA ORDEN ACTUAL
						if ( index == $scope.miIndex && datos.data.detalleOrdenCliente ) {
							$scope.infoOrden.numeroTicket       = orden.numeroTicket;
							$scope.infoOrden.usuarioResponsable = orden.usuarioResponsable;
							$scope.infoOrden.numMenu            = orden.numMenu;
							$scope.infoOrden.lstOrden           = datos.data.detalleOrdenCliente.lst;
							$scope.infoOrden.total              = datos.data.detalleOrdenCliente.total;
						}
					}
				}
			break;

			// SI SE CAMBIO TIPO DE SERVICIO
			case 'cambioTipoServicio':
			
				// SI ESTA EN ESTADO PENDIENTE SE AGREGA A LA LISTA DE PENDIENTES
				if ( datos.data && datos.data.idOrdenCliente == $scope.infoOrden.idOrdenCliente )
					$scope.infoOrden.lstOrden = datos.data.detalleOrdenCliente.lst;
					$scope.infoOrden.total    = datos.data.detalleOrdenCliente.total;

			break;

			// ORDEN PRINCIPAL CANCELADA
			case 'ordenPrincipalCancelada':

				// SI SON ORDENES PENDIENTES
				if ( $scope.idEstadoOrden == 1 ) {

					// OBTIENE INDEX DE ORDEN
					var index    = $scope.indexArray( 'lstOrdenCliente', 'idOrdenCliente', datos.idOrdenCliente );
					var newIndex = 0;
					
					// SI EL INDEX ES EL ELIMINADO
					if ( $scope.miIndex == index )
						$scope.miIndex = -1;

					// SI ES EL ULTIMO ELEMENTO ACTUAL
					if ( ( $scope.miIndex + 1 ) == $scope.lstOrdenCliente.length ) {
						newIndex       = ( $scope.miIndex - 1 );
						$scope.miIndex = -1;
					}

					// SI ENCUENTRA LA ORDEN CANCELADA EN PENDIENTES
					if ( index >= 0 )
						$scope.lstOrdenCliente.splice( index, 1 );

					$timeout(function () {
						if ( $scope.lstOrdenCliente.length && $scope.miIndex == -1 )
							$scope.miIndex = newIndex;
					});
				}
			break;

			case 'cancelarOrdenParcial':

				console.log( datos.data.lstDetalle, $scope.infoOrden.lstOrden );
				if ( datos.data && $scope.infoOrden.idOrdenCliente == datos.data.idOrdenCliente )
				{

					var lstEliminado = datos.data.lstDetalle;
					var ixParent = -1, ixChildren = -1;

					for (var ixo = 0; ixo < $scope.infoOrden.lstOrden.length; ixo++) 
					{

						for (var id = 0; id < $scope.infoOrden.lstOrden[ ixo ].lstDetalle.length; id++) 
						{
							var item = $scope.infoOrden.lstOrden[ ixo ].lstDetalle[ id ];

							for (var i = 0; i < lstEliminado.length; i++) 
							{
								if ( ( item.idDetalleOrdenCombo > 0 && lstEliminado[ i ].idDetalleOrdenCombo == item.idDetalleOrdenCombo ) 
									|| ( item.idDetalleOrdenMenu > 0 && lstEliminado[ i ].idDetalleOrdenMenu == item.idDetalleOrdenMenu ) ) 
								{
									ixParent   = ixo;
									ixChildren = id;
									break;
								}
							}
						
							if ( ixChildren >= 0 ) break;
						}

						if ( ixParent >= 0 ) break;
					}
					
					// SI EXISTE EL ELEMENTO
					if ( ixParent >= 0 ) {
						var subTotal = $scope.infoOrden.lstOrden[ ixParent ].subTotal;
						var cantidad = $scope.infoOrden.lstOrden[ ixParent ].cantidad;
						$scope.infoOrden.lstOrden.splice( ixParent, 1 );
						$scope.infoOrden.numMenu -= cantidad;
						$scope.infoOrden.total   -= subTotal;
					}
				}

			break;
		}

		$scope.$apply();
	});
	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% INFORMACION DE NODEJS %%%%%%%%%%%%%%%%%%%%%%%%%%%%   */

	/* ++++++++++++++++++ UTIL ++++++++++++++++ */

	// ***RETORNA LA DESCRIPCION DE UN ELEMENTO DE ACUEROD AL id DEL ARREGLO
	$scope.descripcion = function ( arr, id, _value, _return ) {
		var descrip = '';
		for (var i = 0; i < $scope[ arr ].length; i++) {
			if ( $scope[ arr ][ i ][ id ] == _value ) {
				descrip = $scope[ arr ][ i ][ _return ];
				break;
			}
		}

		return descrip;
	};

	// *** RETORNA INDEX DE ARREGLO
	$scope.indexArray = function ( arr, cmp, _value ) {
		var index = -1;
		for (var i = 0; i < $scope[ arr ].length; i++) {
			if ( $scope[ arr ][ i ][ cmp ] == _value ) {
				index = i;
				break;
			}
		}

		return index;
	};
});