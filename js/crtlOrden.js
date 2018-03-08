app.controller('crtlOrden', function( $scope, $http, $timeout, $modal, $location ){
	$scope.lstTipoServicio = [];
	$scope.lstTipoMenu     = [];
	$scope.lstMenu         = [];
	$scope.noTicket        = 0;
	$scope.buscarTicket    = '';
	$scope.idTipoServicio  = '';
	$scope.idTipoMenu      = '';
	$scope.accionOrden     = 'nuevo';
	$scope.idEstadoOrden   = 1;
	$scope.todoDetalle     = false;
	$scope.observacion     = "";
	$scope.comentario      = "";
	$scope.filtroServicio  = 1;
	$scope.miIndex  	   = -1;
	$scope.myId 		   = "";

	$scope.ordenActual = {
		idOrdenCliente : 0,
		noTicket       : '',
		totalAgregar   : 0,
		lstAgregar     : [],
		lstPedidos     : []
	};

	$scope.menuActual = {
		lstPrecio : []
	};

	$scope.menuPer = {};

	alertify.set('notifier','position', 'top-right');

	// DIALOGOS
	$scope.dlAyuda              = $modal({scope: $scope,template:'ayuda.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrden            = $modal({scope: $scope,template:'dial.orden.nueva.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrdenCliente     = $modal({scope: $scope,template:'dial.orden.cliente.html', show: false, backdrop:false, keyboard: false });
	$scope.dialCantidad  		= $modal({scope: $scope,template:'dial.cantidad.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenMenu        = $modal({scope: $scope,template:'dial.orden-menu.html', show: false, backdrop:false, keyboard: false });
	$scope.dialMenuCantidad     = $modal({scope: $scope,template:'dial.menu-cantidad.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenBusqueda    = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrdenCancelar    = $modal({scope: $scope,template:'dial.orden.cancelar.html', show: false, backdrop:false, keyboard: true });
	$scope.dialEditarDetalle    = $modal({scope: $scope,template:'dial.orden.editar.html', show: false, backdrop:false, keyboard: true });
	$scope.dialUltimasOrdenes   = $modal({scope: $scope,template:'dial.ultimas.ordenes.html', show: false, backdrop:false, keyboard: true });
	$scope.dialCancelarDetalle  = $modal({scope: $scope,template:'dial.orden.cancelar-parcial.html', show: false, backdrop:false, keyboard: true });
	$scope.dialCancelarOtro  = $modal({scope: $scope,template:'dial.orden.cancelar-otro.html', show: false, backdrop:false, keyboard: true });

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

	/* ===== MOSTRAR DIALOGOS ===== */
	$scope.openCantidad = function () {
		$scope.cantidadInicial = 1;
		$scope.menuActual      = {};


		if ( !( $scope.ordenActual.noTicket > 0 ) )
			$scope.idTipoServicio = 3;
		else if ( $scope.ordenActual.noTicket > 0 )
		{
			if ( $scope.idTipoServicio == 3 )
				$scope.idTipoServicio = 1;
		}

		$scope.dialOrdenCliente.hide();
		$scope.dialCantidad.show();
		$timeout(function () {
			if ( document.getElementById("cantidadInicial") )
				document.getElementById("cantidadInicial").focus();
		});
	};


	$scope.lstTicketBusqueda = [];
	$scope.buscarOrdenTicket = function ( _ticketDetalle ) {
		if ( $scope.$parent.loading )
			return false;

		if ( $scope.buscarTicket > 0 || _ticketDetalle > 0 ) {
			$scope.lstTicketBusqueda = [];
			$scope.$parent.loading = true; // cargando...

			// SI NO ES DE DETALLE
			if ( _ticketDetalle == undefined )
				$scope.lstOrdenCliente = [];
			
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { opcion : 'busquedaTicket', ticket : ( _ticketDetalle || $scope.buscarTicket ) })
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data ) && data.length ) {
					$scope.buscarTicket = 0;

					if ( data.length == 1 )
						$scope.seleccionarDeBusqueda( data[ 0 ], ( _ticketDetalle || 0 ) );
					
					// SI EXISTEN MAS RESULTADOS Y ES POR DETALLE
					else if ( data.length > 1 && ( _ticketDetalle || 0 ) > 0 )
						alertify.notify( "No se encontro información", 'info', 3 );

					else{
						$scope.lstTicketBusqueda = data;
						$scope.dialOrdenBusqueda.show();
					}
				}
				else{
					alertify.notify( "No se encontro información", 'info', 2 );
				}
			});
		}
	};


	$scope.seleccionarDeBusqueda = function ( orden, _ticketDetalle ) {
		// SI ES BUSQUEDA POR DETALLE
		if ( _ticketDetalle > 0 )
			$scope.itemDetalle.destinoOrden = orden;

		// SI BUSQUEDA DE INICIO
		else
		{
			$scope.miIndex = -1;
			$scope.dialOrdenBusqueda.hide();
			$timeout(function () {
				$scope.consultaDetalleOrden( orden, true );
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
		$scope.accionOrden = 'modificar';

		$scope.ordenActual = {
			idOrdenCliente : orden.idOrdenCliente,
			noTicket       : orden.numeroTicket,
			totalAgregar   : 0,
			lstAgregar     : [],
			lstPedidos     : []
		};
		$scope.openCantidad();
	};

	// #2 => CREA UNA NUEVA ORDEN
	$scope.agregarOrden = function ( domicilio ) {
		if ( $scope.$parent.loading )
			return false;

		$scope.codigoRapido = "";
		
		if ( !( parseInt( $scope.noTicket ) > 0 ) && domicilio != 'domicilio' )
		{
			alertify.set('notifier','position', 'top-right');
			alertify.notify('Número de Ticket NO Válido', 'danger', 4);
		}
		else
		{
			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion : 'consultaOrdenCliente',
				accion : 'insert',
				datos : {
					numeroTicket       : $scope.noTicket,
					usuarioResponsable : '',
					lstU               : $scope.$parent.lstU
				}
			})
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( data.respuesta == 'success' ) {
					$scope.accionOrden = 'nuevo';
					
					// MUESTRA DIALOGO DE ORDEN GENERADA
					$scope.dialOrden.hide();

					$scope.ordenActual = {
						idOrdenCliente : data.data,
						noTicket     : parseInt( $scope.noTicket ),
						totalAgregar : 0,
						lstAgregar   : [],
						lstPedidos   : []
					};
					$scope.openCantidad();
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				}
			});
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
		$scope.dialCantidad.hide()
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
			$http.post('consultas.php', { 
				opcion       : 'lstMenu',
				idTipoMenu   : $scope.idTipoMenu,
				idEstadoMenu : 1
			})
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
			$http.post('consultas.php', { 
				opcion       : 'lstCombo',
				idEstadoMenu : 1
			})
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

		var datos = { 
			opcion         : 'precioDisponibilidad',
			idMenu         : 0,
			idCombo        : 0,
			idTipoServicio : $scope.idTipoServicio,
			cantidad       : $scope.cantidadInicial
		};

		if ( $scope.tipoMenu == 'menu' ) {
			$scope.menuActual = {
				idMenu : menu.idMenu,
				menu   : menu.menu,
				imagen : menu.imagen,
			};

			datos.idMenu = menu.idMenu;
		}
		else if ( $scope.tipoMenu == 'combo' ) {
			$scope.menuActual = {
				idMenu : menu.idCombo,
				menu   : menu.combo,
				imagen : menu.imagen,
			};

			datos.idCombo = menu.idCombo;
		}

		$scope.menuActual.precio    = 0;
		$scope.menuActual.lstPrecio = [];
		$scope.observacion          = "";
		$scope.lstSinDisponibilidad = [];

		$scope.$parent.loading = true; // cargando...
		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', datos )
		.success(function (data) {
			$scope.$parent.loading = false; // cargando...

			if ( data.precio && data.precio > 0 ) {
				$scope.menuActual.precio 	= data.precio;
				$scope.lstSinDisponibilidad = data.lstSinDisponibilidad;

				$scope.dialOrdenMenu.hide();
				if ( data.lstSinDisponibilidad.length )
				{
					$scope.dialCantidad.show();
				}
				else{
					$scope.agregarOrdenLista();
				}
			}
		});

	};

	$scope.agregarOrdenLista = function () {
		if ( !( $scope.menuActual.precio > 0 ) ) {
			alertify.notify('Menú no disponible para este Tipo de Servicio', 'danger', 3);
		}
		else if ( !( $scope.cantidadInicial > 0 ) ) {
			alertify.notify('La cantidad debe ser mayor a cero', 'danger', 2);
		}
		else if ( !( $scope.idTipoServicio > 0 ) ) {
			alertify.notify('Debe seleccionar un Tipo de Servicio', 'danger', 2);
		}
		else{
			var tipoServicio = $scope.descripcion( 'lstTipoServicio', 'idTipoServicio', $scope.idTipoServicio, 'tipoServicio' );

			var subTotal = parseFloat( $scope.menuActual.precio ) * $scope.cantidadInicial;
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
				$scope.ordenActual.lstAgregar[ index ].cantidad += parseInt( $scope.cantidadInicial );
			}
			else{
				$scope.ordenActual.lstAgregar.unshift({
					idMenu         : $scope.menuActual.idMenu,
					menu           : $scope.menuActual.menu,
					cantidad       : parseInt( $scope.cantidadInicial ),
					precio         : $scope.menuActual.precio,
					tipoServicio   : tipoServicio,
					idTipoServicio : $scope.idTipoServicio,
					tipoMenu       : $scope.tipoMenu,
					observacion    : $scope.observacion
				});
			}

			$scope.dialCantidad.hide();
			$scope.dialOrdenCliente.show();
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
					precio         : $scope.ordenActual.lstAgregar[ i ].precio,
					menu           : $scope.ordenActual.lstAgregar[ i ].menu,
					idTipoServicio : $scope.ordenActual.lstAgregar[ i ].idTipoServicio,
					tipoMenu       : $scope.ordenActual.lstAgregar[ i ].tipoMenu,
					observacion    : $scope.ordenActual.lstAgregar[ i ].observacion
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

				$scope.$parent.loading = false; // cargando...

				if ( data.respuesta == 'success' ) {
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
					$scope.dialOrdenCliente.hide();
					$scope.ordenActual.lstAgregar = [];
					
					if ( !( $scope.ordenActual.noTicket > 0 ) )
					{
						window.location.href = "#/factura/" + $scope.ordenActual.idOrdenCliente;
					}
					else
					{
						if ( data.myId != $scope.myId )
						{
							$scope.myId = data.myId;
							if ( $scope.accionOrden == 'nuevo' )
							{
								$scope.lstOrdenCliente.push( data.data.paraMesero );

								// SI EL PEDIDO ES A DOMICILIO
								$timeout(function () {
									if ( $scope.lstOrdenCliente.length == 1 )
										$scope.miIndex = 0;
								});
							}
							else{
								$scope.consultaDetalleOrden();
							}
						}
					}
				}
				else{
					alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				}
			});
		}
		// SI NO SE A AGREGADO NINGUNA ORDEN
		else{
			alertify.notify('No ha agregado ningún menú', 'danger', 4);
		}
	};

	// CONSULTA MENU POR CODIGO
	$scope.lstSinDisponibilidad  = [];
	$scope.consultaMenuPorCodigo = function () {
		if ( $scope.$parent.loading )
			return false;

		if ( $scope.codigoRapido > 0 ) {
			$scope.$parent.loading = true; // cargando...
			$scope.lstSinDisponibilidad = [];

			$http.post('consultas.php', { 
				opcion         : 'menuPorCodigo',
				codigoRapido   : $scope.codigoRapido,
				cantidad       : $scope.cantidadInicial,
				idTipoServicio : $scope.idTipoServicio
			})
			.success(function (data) {

				$scope.$parent.loading = false; // cargando...
				$scope.codigoRapido    = "";

				if ( data.tipoMenu != null ) {
					$scope.menuActual           = data.menu;
					$scope.tipoMenu             = data.tipoMenu;
					$scope.observacion          = "";

					// SI EXISTE DISPONIBILIDAD Y SE ENCUENTRA EL MENU, SE AGREGA
					if ( !data.menu.lstSinDisponibilidad.length )
						$scope.agregarOrdenLista();

					$scope.lstSinDisponibilidad = data.menu.lstSinDisponibilidad;
				}
				
				else{
					alertify.notify('Código no válido', 'danger', 2);
				}
			});
		}
	};


	// => CANCELAR ORDEN PRINCIPAL
	$scope.cancelarOrdenPrincipal = function ( idOrdenCliente ) {
		if ( $scope.$parent.loading )
			return false;

		if ( !( $scope.comentario.length > 5 ) )
			alertify.notify('Comentario demasiado corto', 'danger', 5);

		else if ( idOrdenCliente > 0 ) {

			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion : 'consultaOrdenCliente',
				accion : 'cancel',
				datos : {
					idOrdenCliente : idOrdenCliente,
					comentario     : $scope.comentario
				}
			})
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == 'success' )
					$scope.dialOrdenCancelar.hide();
			});
		} else {
			alertify.notify('Orden de Cliente no válida', 'danger', 4);
		}
	};

	// => CANCELAR ORDEN PARCIAL
	$scope.cancelarOrdenParcial = function ( idOrdenCliente, lstDetalle ) {
		if ( $scope.$parent.loading )
			return false;

		if ( !( $scope.comentario.length > 3 ) && Array.isArray( lstDetalle ) )
			alertify.notify('Comentario demasiado corto', 'danger', 5);

		else{
			$scope.$parent.loading = true; // cargando...

			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', { 
				opcion         : 'cancelarOrdenParcial',
				idOrdenCliente : idOrdenCliente,
				lstDetalle     : lstDetalle,
				comentario     : $scope.comentario
			})
			.success(function (data) {

				$scope.$parent.loading = false; // cargando...

				if ( data.mensaje != undefined ) {
					alertify.notify( data.mensaje, data.respuesta, 3 );

					if ( data.respuesta == 'success' ){
						$scope.dialCancelarDetalle.hide();
						$scope.dialCancelarOtro.hide();

						// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN
						$scope.consultaDetalleOrden();
					}
				}
			});
		}
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
	$scope.lstEstados = [ 
		{ abr : 'P', title : 'Pendiente', css : 'default' },
		{ abr : 'C', title : 'Cocinando', css : 'info' },
		{ abr : 'L', title : 'Listo', css : 'primary' },
		{ abr : 'S', title : 'Servido', css : 'success' }
	];
	$scope.consultaDetalleOrden = function ( orden, deBusqueda, force ) {
		if ( $scope.$parent.loading && force === undefined )
			return false;

		if ( orden !== undefined )
		{
			// SI NO ES DE BUSQUEDA
			if ( deBusqueda == undefined ) {
				$scope.deBusqueda = false;
			}
			// SI ES DE BUSQUEDA
			else {
				$scope.idEstadoOrden = 0;
				$scope.deBusqueda    = true;
			}

			$scope.infoOrden = orden;
		}

		// SI NO ESTA DEFINIDO LA ORDEN
		if ( !( $scope.infoOrden.idOrdenCliente > 0 ) )
			return false;

		$scope.$parent.loading = true;
		$http.post('consultas.php', { opcion : 'lstDetalleOrdenCliente', idOrdenCliente : $scope.infoOrden.idOrdenCliente, todo : $scope.todoDetalle })
		.success(function (data) {
			$scope.$parent.loading = false;
			if ( data.lst ) {
				
				for (var ix = 0; ix < data.lst.length; ix++)
				{
					var lstEstados = [];
					for (var im = 0; im < data.lst[ ix ].lstMenus.length; im++)
					{
						var menu = data.lst[ ix ].lstMenus[ im ];
						var ixT = -1;
						var idEstadoActual = parseInt( menu.perteneceCombo ? menu.idEstadoDetalleOrdenCombo : menu.idEstadoDetalleOrden );
						
						for (var ie = 0; ie < lstEstados.length; ie++) 
						{
							if ( idEstadoActual == lstEstados[ ie ].idEstado )
							{
								ixT = ie;
								break;
							}
						}

						if ( ixT == -1 )
						{
							var estado = $scope.lstEstados[ idEstadoActual - 1 ] || {};

							ixT = lstEstados.length;
							lstEstados.push({
								idEstado : idEstadoActual,
								abr      : estado.abr,
								title    : estado.title,
								css      : estado.css,
								total    : 0
							});
						}

						lstEstados[ ixT ].total++;
					}

					data.lst[ ix ].estados = lstEstados;
				}

				$scope.infoOrden.lstOrden = data.lst;
				$scope.infoOrden.lstOtros = data.lstOtros;
				$scope.infoOrden.total    = data.total;
			}
		});
	};


	$scope.itemDetalle = {};
	// CANCELAR ORDEN
	$scope.cancelarDetalle = function ( itemDetalle ) {
		$scope.itemDetalle = itemDetalle;
		$scope.comentario  = "";
		$scope.dialCancelarDetalle.show();
	};

	// CANCELAR OTRO MENU
	$scope.cancelarOtroMenu = function ( otroMenu ) {
		$scope.itemDetalle = otroMenu;
		$scope.dialCancelarOtro.show();
	};

	// EDITAR ORDEN
	$scope.accionDetalleOrden = 'tipoServicio';
	$scope.editarDetalle = function ( item ) {

		$scope.itemDetalle = {
			descripcion       : item.descripcion,
			imagen            : item.imagen,
			cantidad          : item.cantidad,
			lstMenus          : angular.copy( item.lstMenus ),
			lstDetalle        : angular.copy( item.lstDetalle ),
			idTipoServicio    : item.idTipoServicio,
			lstTipoServicio   : angular.copy( $scope.lstTipoServicio ),
			busqueda  		  : '',
			destinoOrden 	  : {},
			cantidadReasignar : angular.copy( item.cantidad )
		};

		for (var ixT = 0; ixT < $scope.itemDetalle.lstTipoServicio.length; ixT++) {
			
			$scope.itemDetalle.lstTipoServicio[ ixT ].cantidad = '';

			if ( item.idTipoServicio == $scope.itemDetalle.lstTipoServicio[ ixT ].idTipoServicio )
				$scope.itemDetalle.lstTipoServicio[ ixT ].cantidad = item.cantidad;
		}
		

		$scope.dialEditarDetalle.show();

		$timeout(function () {
			document.getElementById("input_ts_0") && document.getElementById("input_ts_0").focus();
		});
	};

	// GUARDAR MODIFICACION DE DATALLE DE ORDEN
	$scope.editarOrdenParcial = function () {
		if ( $scope.$parent.loading )
			return false;

		var error = false;

		if ( $scope.accionDetalleOrden == 'tipoServicio' )
		{
			var total 	   		= 0,
				_current 		= null,
				lstDetalle 		= [],
				cantidadMenus 	= angular.copy( $scope.itemDetalle.cantidad );

			// RECORRER TIPOS DE SERVICIO
			for (var ix = 0; ix < $scope.itemDetalle.lstTipoServicio.length; ix++)
			{
				_current = $scope.itemDetalle.lstTipoServicio[ ix ];
				total    += ( _current.cantidad || 0 );

				// EXISTE CANTIDAD Y TIPO DE SERVICIO ES DIFERENTE AL ACTUAL
				if( _current && _current.cantidad && _current.idTipoServicio != $scope.itemDetalle.idTipoServicio )
				{
					for (var im = 1; im <= _current.cantidad; im++)
					{
						var index = ( cantidadMenus - im );
						
						if ( $scope.itemDetalle.lstMenus[ index ] === undefined )
							break;

						lstDetalle.push({
							idDetalleOrdenMenu  : $scope.itemDetalle.lstMenus[ index ].idDetalleOrdenMenu,
							idDetalleOrdenCombo : $scope.itemDetalle.lstMenus[ index ].idDetalleOrdenCombo,
							idTipoServicio 		: _current.idTipoServicio
						});
					};

					cantidadMenus -= _current.cantidad;
				}
			}

			// SI EL TOTAL ES DIFERENTE
			if ( total != $scope.itemDetalle.cantidad ) {
				alertify.notify('La suma de los Tipos de Servicios debe ser igual a: ' + $scope.itemDetalle.cantidad, 'danger', 5);
				error = true;
			}

			// SI NO SE HIZO NINGUNA MODIFICACION
			else if ( lstDetalle.length === 0 ) {
				alertify.notify('Ningún cambio realizado ', 'info', 5);
				$scope.dialEditarDetalle.hide();
			}

			else {
				$scope.$parent.loading = true; // cargando...

				var datos = {
					opcion         : 'cambiarServicio',
					idOrdenCliente : $scope.infoOrden.idOrdenCliente,
					lstDetalle     : lstDetalle
				};

				// CONSULTA PRECIOS DEL MENU
				$http.post('consultas.php', datos )
				.success(function (data) {
					$scope.$parent.loading = false; // cargando...

					alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

					if ( data.respuesta == 'success' )
					{
						$scope.dialEditarDetalle.hide();
						// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN
						$scope.consultaDetalleOrden();
					}
				});
			}
		}

		else if ( $scope.accionDetalleOrden == 'reasignar' )
		{
			var lstDetalle = [];

			if ( !( $scope.itemDetalle.cantidadReasignar > 0 ) || $scope.itemDetalle.cantidadReasignar > $scope.itemDetalle.cantidad )
			{
				alertify.notify('Cantidad no válida', 'danger', 3);
				return false;
			}

			for (var im = 1; im <= $scope.itemDetalle.cantidadReasignar; im++)
			{
				var index = ( $scope.itemDetalle.lstMenus.length - im );
				
				if ( $scope.itemDetalle.lstMenus[ index ] === undefined )
					break;

				lstDetalle.push({
					idDetalleOrdenMenu  : $scope.itemDetalle.lstMenus[ index ].idDetalleOrdenMenu,
					idDetalleOrdenCombo : $scope.itemDetalle.lstMenus[ index ].idDetalleOrdenCombo
				});
			};

			var datos = {
				opcion                : 'reasignarDetalleOrden',
				idOrdenCliente        : $scope.infoOrden.idOrdenCliente,
				idOrdenClienteDestino : $scope.itemDetalle.destinoOrden.idOrdenCliente,
				lstDetalle            : lstDetalle
			};

			$scope.$parent.loading = true; // cargando...
			// CONSULTA PRECIOS DEL MENU
			$http.post('consultas.php', datos )
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

				if ( data.respuesta == 'success' )
				{
					$scope.dialEditarDetalle.hide();
					// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN
					$scope.consultaDetalleOrden();
				}
			});
		}
	};



	/*
		================ OTRO MENU 
	*/
	$scope.agregarOtroMenu = function () {

		if ( !( $scope.menuPer.cantidad > 0 ) )
			alertify.notify( 'Revise Cantidad', 'danger', 5);

		else if ( !( $scope.menuPer.precioUnidad > 0 ) )
			alertify.notify( 'Revise Precio por Unidad', 'danger', 5);

		else if ( !( $scope.menuPer.descripcion && $scope.menuPer.descripcion.length > 0 ) )
			alertify.notify( 'Revise Descripción', 'danger', 5);

		else{
			console.log( $scope.menuPer );
			$scope.ordenActual.lstAgregar.unshift({
				idMenu         : null,
				menu           : $scope.menuPer.descripcion,
				cantidad       : $scope.menuPer.cantidad,
				precio         : $scope.menuPer.precioUnidad,
				tipoServicio   : '',
				idTipoServicio : '',
				tipoMenu       : 'personalizado',
				observacion    : null
			});

			$scope.filtroServicio = '';
			$scope.menuPer        = {};
		}
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

	$scope.total = function () {
		var total = 0;

		for (var i = 0; i < $scope.ordenActual.lstAgregar.length; i++) {
			actual          = $scope.ordenActual.lstAgregar[ i ];
			actual.cantidad = parseInt( actual.cantidad );
			actual.precio   = parseFloat( actual.precio );

			if ( actual.cantidad && actual.precio )
				total += ( actual.cantidad * actual.precio );
		}

		$scope.ordenActual.totalAgregar = total;
	};

	// QUITAR ELEMENTO DE ORDEN
	$scope.quitarElemento = function ( index, cantidad, precio ) {
		$scope.ordenActual.totalAgregar -= ( cantidad * precio );
		$scope.ordenActual.lstAgregar.splice( index, 1 );
	};

	$scope.cancelarMenu = function () {
		if ( $scope.menuActual && $scope.menuActual.idMenu > 0 ) 
		{
			$scope.menuActual = {};
			$timeout(function () {
				if ( document.getElementById("codigoRapido") )
					document.getElementById("codigoRapido").focus();
			});
		}
	};

	$scope.changeInt = function ( val, sum, min, max ) {
		var result = 0;
		val = parseInt( val );

		if ( val && val < max && sum )
			result = val + 1;

		else if ( val && val > min && !sum )
			result = val - 1;

		else
			result = ( val || min );

		return result;
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
		// $scope.watchPrecio();

		if ( _new )
			$scope.filtroServicio = _new;
	});

	// SI CAMBIA EL INDEX DE PEDIDO
	$scope.$watch('miIndex', function ( _new ) {
		$scope.infoOrden = {};

		// SI ES UN INDEX VALIDO
		if ( _new >= 0 && $scope.lstOrdenCliente[ _new ] )
			$scope.consultaDetalleOrden( $scope.lstOrdenCliente[ _new ] );
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
	$scope._keyInicioOrden = function ( key, altDerecho ) {
		if ( altDerecho && key == 79 ) // {O}
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
		if ( altDerecho && key == 80 ) $scope.idEstadoOrden = 1; // {P}
		if ( altDerecho && key == 76 ) $scope.idEstadoOrden = 3; // {F}
		if ( altDerecho && key == 70 ) $scope.idEstadoOrden = 4; // {F}
		if ( altDerecho && key == 67 ) $scope.idEstadoOrden = 10; // {C}
		if ( altDerecho && key == 83 ) $scope.idEstadoOrden = 6; // {S}

		// BUSQUEDA POR TICKET
		if ( key >= 48 && key <= 57 && !$("#buscarTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'buscarTicket' );

		if ( key >= 96 && key <= 105 && !$("#buscarTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'buscarTicket' );
		
		if ( key == 46 ) // {SUPR}
			$scope.auxKeyTicket( 'supr', 0, 'buscarTicket' );

		if ( key == 8 && !$("#buscarTicket").is(":focus") ) // {BACK}
			$scope.auxKeyTicket( 'back', 0, 'buscarTicket' );

		if ( key == 13 || key == 117 ) // {F6}
			$scope.buscarOrdenTicket();

		// F10: FACTURAR ORDEN SELECCIONADA
		if ( key == 121 && $scope.infoOrden.idOrdenCliente > 0 ) // {F10}
			$location.path( "/factura/" + $scope.infoOrden.idOrdenCliente );

		if ( altDerecho && key == 65 && $scope.infoOrden.idOrdenCliente > 0 ) // {O}
			$scope.consultaOrden( $scope.infoOrden );

		else if ( altDerecho && key == 88 && $scope.infoOrden.idOrdenCliente > 0 )// {X}
		{
			$scope.dialOrdenCancelar.show();
			$scope.comentario = '';
			$timeout(function () {
				document.getElementById( 'txtCancelar' ) && document.getElementById( 'txtCancelar' ).focus();
			});
		}
	};

	// ATAJOS DIALOGO INGRESO DE TICKET
	$scope._keyDialTicket = function ( key ) {
		if ( key == 27 ) // {ESC} || {S}
			$scope.dialOrden.hide();

		if ( key >= 48 && key <= 57 && !$("#noTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 48 ), 'noTicket' );

		if ( key >= 96 && key <= 105 && !$("#noTicket").is(":focus") ) // {0-9}
			$scope.auxKeyTicket( 'number', ( key - 96 ), 'noTicket' );
		
		if ( key == 46 ) // {SUPR}
			$scope.auxKeyTicket( 'supr', 0, 'noTicket' );

		if ( key == 8 && !$("#noTicket").is(":focus") ) // {BACK}
			$scope.auxKeyTicket( 'back', 0, 'noTicket' );

		// SI ES ADOMICILIO
		if ( key == 68 && !( $scope.noTicket > 0 ) ) // {D}
			$scope.agregarOrden( 'domicilio' );

		if ( key == 13 || key == 117 ) // {ENTER} || {F6}
			$scope.agregarOrden();
	};

	// ATAJOS DIALOGO ORDEN CLIENTE
	$scope._keyDialOrden = function ( key, altDerecho ) {
		// ABRE MODAL DE CANTIDAD Y CONSULTA DE MENU
		if ( altDerecho && key == 65 ) // {A}
			$scope.openCantidad();

		// CONFIRMA LA ORDEN DEL CLIENTE
		if ( key == 117 ) // {F6}
			$scope.guardarOrden();

		if ( key == 27 && $scope.accionOrden == 'modificar' ) // {ESC}
			$scope.dialOrdenCliente.hide();

		if ( key == 37 && $scope.filtroServicio > 1 )
			$scope.filtroServicio--;

		else if ( key == 37 && $scope.filtroServicio == 1 )
			$scope.filtroServicio = "";

		else if ( key == 39 && $scope.filtroServicio < 3 )
			$scope.filtroServicio++;

		else if ( key == 39 && $scope.filtroServicio == '' )
			$scope.filtroServicio = 1;
	};

	// ATAJOS DIALOGO CANTIDAD
	$scope._keyCantidad = function ( key, altDerecho ) {
		if ( ( key == 109 || key == 189 ) && $scope.cantidadInicial > 1 ) // {-}
			$scope.cantidadInicial = $scope.changeInt( $scope.cantidadInicial, false, 1, 500 );

		if ( key == 107 || key == 187 ) { // {+}
			$scope.cantidadInicial = $scope.changeInt( $scope.cantidadInicial, true, 1, 500 );
		}

		// CANCELA EL MENU ACTUAL
		if ( key == 46 )
			$scope.cancelarMenu();

		if ( $("#codigoRapido").is( ":focus" ) && key == 8 ) // {BACK}
			$scope.auxKeyTicket( 'supr', 0, 'codigoRapido' );

		// {ENTER & FOCUS cantidad}
		if ( $("#cantidadInicial").is( ":focus" ) && key == 13 ) 
			document.getElementById("codigoRapido").focus();

		// {ENTER & FOCUS codigo menu}
		if ( $("#codigoRapido").is( ":focus" ) && key == 13 )
			$scope.consultaMenuPorCodigo();

		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 79 ) // {SHIFT} + {O}
			document.getElementById("observacionMenu").focus();

		// SELECCION DE TIPO DE SERVICIO
		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 76 && $scope.ordenActual.noTicket > 0 ) // {L}
			$scope.idTipoServicio = 1;

		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 82 && $scope.ordenActual.noTicket > 0 ) // {R}
			$scope.idTipoServicio = 2;

		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 68 && !( $scope.ordenActual.noTicket > 0 ) ) // {D}
			$scope.idTipoServicio = 3;

		// SELECCION DE MENU
		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 77 ) // {M}
			$scope.mostrarMenus( 'menu' );

		if ( altDerecho && !$("#observacionMenu").is(":focus") && key == 67 ) // {C}
			$scope.mostrarMenus( 'combo' );

		if ( key == 27 ) { // {ESC}
			$scope.dialCantidad.hide();
			$scope.dialOrdenCliente.show();
		} 
	};

	$scope._keyDialMenuCantidad = function ( key, altDerecho ) {
		if ( key == 117 ) // {F6}
			$scope.agregarAPedido();

		else if ( ( key == 109 || key == 189 ) && $scope.menuActual.cantidad > 1 ) // {-}
			$scope.menuActual.cantidad--;

		else if ( key == 107 || key == 187 ) { // {+}
			if ( isNaN( $scope.menuActual.cantidad ) )
				$scope.menuActual.cantidad = 0;

			$scope.menuActual.cantidad++;
		}

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
	$scope.$on('keyPress', function( event, key, altDerecho ) {
		if ( ( altDerecho && key == 67 ) || key == 187 || key == 189 || key == 109 || key == 107 )
			event.preventDefault();

		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// SI NO EXISTE NINGUN DIALOGO ABIERTO
		if ( !$scope.modalOpen() )
			$scope._keyInicioOrden( key, altDerecho );

		// CUANDO ESTE ABIERTO ALGUN CUADRO DE DIALOGO
		else{
			// ******** DIALOGO DE INGRESO DE TICKET ********
			if ( $scope.modalOpen( 'dial_orden_nueva' ) ) {
				$scope._keyDialTicket( key );
			}

			// ******** DIALOGO DE ADMON. ORDEN DEL CLIENTE ********
			if ( $scope.modalOpen( 'dial_orden_cliente' ) ) {
				$scope._keyDialOrden( key, altDerecho );
			}

			// ******** DIALOGO DE CANTIDAD ********
			if ( $scope.modalOpen( 'dial_cantidad' ) ) {
				$scope._keyCantidad( key, altDerecho );
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
				$scope._keyDialMenuCantidad( key, altDerecho );
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
				if ( datos.data.myId != $scope.myId && !$scope.$parent.loading )
				{
					$scope.myId = datos.data.myId;
					// SI ESTA EN ESTADO PENDIENTE SE AGREGA A LA LISTA DE PENDIENTES
					if ( datos.data.info && datos.data.info.paraMesero && ( $scope.idEstadoOrden == 1 || $scope.idEstadoOrden == 2 ) ) {
						$scope.lstOrdenCliente.push( datos.data.info.paraMesero );

						// SELECCIONAR LA ORDEN AGREGADA SI ES LA UNICA
						$timeout(function () {
							if ( $scope.lstOrdenCliente.length == 1 )
								$scope.miIndex = 0;
						});
					}
				}

			break;

			case 'ordenAgregar':
				if ( datos.data.myId != $scope.myId && !$scope.$parent.loading )
				{
					$scope.myId = datos.data.myId;
					// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN, SI ES LA MISMA
					if ( $scope.infoOrden.idOrdenCliente == datos.data.idOrdenCliente )
						$scope.consultaDetalleOrden( undefined, undefined, true );
				}
				
			break;


			case 'cambioTipoServicio':
				// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN, SI ES LA MISMA
				if ( $scope.infoOrden.idOrdenCliente == datos.data.idOrdenCliente )
					$scope.consultaDetalleOrden();

			break;

			// ORDEN PRINCIPAL CANCELADA
			case 'ordenPrincipalCancelada':

				// SI SON ORDENES PENDIENTES
				if ( ( $scope.idEstadoOrden == 1 || $scope.idEstadoOrden == 2 ) ) {

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
				// CONSULTA NUEVAMENTE EL DETALLE DE LA ORDEN, SI ES LA MISMA
				if ( $scope.infoOrden.idOrdenCliente == datos.data.idOrdenCliente )
					$scope.consultaDetalleOrden();

			break;

			// SI SE REALIZO CAMBIO DE ESTADO DE DETALLE DE ORDENM
			case 'cambioEstadoDetalleOrden':
				// RECORRE LAS ORDENES Y VERIFICA SI ES LA ACTUAL
				for (var ixO = 0; ixO < datos.data.ordenCocina.lstOrden.length; ixO++)
				{
					// SE ES LA MISMA ORDEN, CONSULTA NUEVAMENTE EL DETALLE
					if ( $scope.infoOrden.idOrdenCliente == datos.data.ordenCocina.lstOrden[ ixO ].idOrdenCliente )
					{
						$scope.consultaDetalleOrden();
						break;
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