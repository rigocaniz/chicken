app.controller('crtlAdminOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstMenu        = [];
	$scope.minutosAlerta  = 20;
	$scope.idEstadoOrden  = 1;
	$scope.idEstadoOrdenTk = 1;
	$scope.tipoVista      = 'menu';
	$scope.ixMenuActual   = -1;
	$scope.ixTicketActual = -1;

	$scope.ixMenuFocus   = -1;
	$scope.ixTicketFocus = -1;

	$scope.lstDestinoMenu = [];
	$scope.lstMenus       = [];
	$scope.lstTickets     = [];
	$scope.lstMenusMaster = [];
	$scope.codigoPersonal = '';
	$scope.clave 		  = '';
	$scope.keyPanel 	  = '';

	$scope.seleccionMenu  = {
		si     : false,
		menu   : '',
		imagen : null,
		count  : {
			total 		: 0,
			llevar      : 0,
			restaurante : 0,
			domicilio   : 0
		},
	};

	$scope.seleccionTicket  = {
		si     : false,
		menu   : '',
		imagen : null,
		count  : {
			total 		: 0,
			llevar      : 0,
			restaurante : 0,
			domicilio   : 0
		},
	};

	$scope.idDestinoMenu = ''; // 1:cocina, 2:barra
	$scope.agruparPor  = '';
	$scope.user = {};

	$scope.dConsultaPersonal = $modal({scope: $scope,template:'consulta.personal.html', show: false, backdrop:false, keyboard: true });

	// CONSULTA INFORMACION DE PERSONA
	$http.post('consultas.php', { opcion : 'iniOrdenAdmin' })
	.success(function (data) { 
		console.log( data );

		if ( data.usuario != undefined ) {
			$scope.user           = data.usuario;
			$scope.lstDestinoMenu = data.usuario.lstDestinos;
			$timeout(function () {
				if ( $scope.lstDestinoMenu.length )
					$scope.idDestinoMenu = $scope.lstDestinoMenu[ 0 ].idDestinoMenu;
			});
		}
	});

	// DIALOGO DE PERSONAL SELECCIONADO
	$scope.dialogoConsultaPersonal = function ( agruparPor ) {
		$scope.codigoPersonal = '';
		$scope.clave 		  = '';
		$scope.dConsultaPersonal.show();
		$timeout(function () {
			document.getElementById('codigoPersonal').focus();
		},50);
	};

	// CONSULTA INFORMACION DE PERSONAL
	$scope.consultarPersonal = function () {
		// CONSULTA DESTINOS
		$http.post('consultas.php', { 
			opcion         : 'login',
			codigoPersonal : $scope.codigoPersonal,
			clave          : $scope.clave
		})
		.success(function (data) { 
			console.log( data );

			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, 4 );

			if ( data.respuesta == 'success' ) {
				$scope.dConsultaPersonal.hide();

				if ( data.usuario != undefined ) {
					$scope.user           = data.usuario;
					$scope.lstDestinoMenu = data.usuario.lstDestinos;
					$scope.idDestinoMenu = '';
					$timeout(function () {
						if ( $scope.lstDestinoMenu.length )
							$scope.idDestinoMenu = $scope.lstDestinoMenu[ 0 ].idDestinoMenu;
					});
				}
			}
			else
				document.getElementById('codigoPersonal').focus();
		});
	};


	// CONSULTA INFORMACION DE ORDENES POR MENU
	$scope.consultaOrden = function () {
		if ( $scope.$parent.loading ) return false;

		if ( $scope.idEstadoOrden > 0 && $scope.idDestinoMenu > 0 ) {

			var datos = { 
				opcion               : 'lstOrdenPorMenu',
				idEstadoDetalleOrden : $scope.idEstadoOrden,
				idDestinoMenu        : $scope.idDestinoMenu
			};

			$scope.$parent.loading = true; // cargando...
			$http.post('consultas.php', datos)
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data.lstMenu ) ) 
				{
					$scope.ixMenuActual = -1;
					$scope.lstMenus     = data.lstMenu;
				}
				
			});
		}
	};

	// CONSULTA INFORMACION DE ORDENES POR TICKET
	$scope.consultaOrdenTicket = function ( ignoreWait ) {
		if ( $scope.$parent.loading && !ignoreWait ) return false;

		if ( $scope.idEstadoOrdenTk > 0 && $scope.idDestinoMenu > 0 ) {
			var idEstadoOrden = $scope.idEstadoOrdenTk == 1 ? 'valid' : 4;

			var datos = { 
				opcion               : 'lstOrdenPorTicket',
				idEstadoOrden        : idEstadoOrden,
				idEstadoDetalleOrden : 'valid',
				idDestinoMenu        : $scope.idDestinoMenu
			};

			$scope.$parent.loading = true; // cargando...
			$http.post('consultas.php', datos)
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data.lstTicket ) ) 
				{
					$scope.ixTicketActual = -1;
					$scope.lstTickets = data.lstTicket;
				}
			});
		}
	};



	// CAMBIA ESTADO ACTUAL A PROCESO SIGUIENTE, SELECCION
	$scope.continuarProcesoMenu = function () {
		if ( !$scope.seleccionMenu.si || $scope.$parent.loading ) return false;

		var lst = [], idEstadoDestino = 0;

		if ( $scope.idEstadoOrden >= 1 && $scope.idEstadoOrden <= 3 ) 
			idEstadoDestino = $scope.idEstadoOrden + 1;

		for (var i = 0; i < $scope.lstMenus[ $scope.ixMenuActual ].detalle.length; i++) {
			var item = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ i ];

			// SI ESTA SELECCIONADO
			if ( item.selected )
				lst.push({
					numeroTicket        : item.numeroTicket,
					idMenu              : item.idMenu,
					cantidad            : item.cantidad,
					idDetalleOrdenMenu  : item.idDetalleOrdenMenu,
					idDetalleOrdenCombo : item.idDetalleOrdenCombo
				});
		}

		// REALIZA CONSULTA HACIA EL SERVIDOR
		if ( idEstadoDestino > 0 && lst.length ) 
		{

			var datos = { 
				opcion        : 'cambioEstadoDetalleOrden',
				idEstadoOrden : idEstadoDestino,
				lstOrdenes    : lst
			};

			$scope.$parent.loading = true; // cargando...

			$http.post('consultas.php', datos)
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, 4 );

				// SI LA RESPUESTA ES SUCCESS
				if ( data.respuesta == 'success' ) 
					$scope.reset();
			});
		}
	};

	$scope.reset = function () {
		$scope.seleccionMenu  = {
			si     : false,
			menu   : '',
			imagen : null,
			count  : {
				total 		: 0,
				llevar      : 0,
				restaurante : 0,
				domicilio   : 0
			},
		};

		$scope.seleccionTicket  = {
			si     : false,
			menu   : '',
			imagen : null,
			count  : {
				total 		: 0,
				llevar      : 0,
				restaurante : 0,
				domicilio   : 0
			},
		};

		$scope.ixMenuFocus   = -1;
		$scope.ixTicketFocus = -1;
	};

	// SI CAMBIA EL DESTINO DE MENU
	$scope.$watch('idDestinoMenu', function (_new) {
		$scope.consultaOrden();
		$scope.consultaOrdenTicket( true );
	});

	// SI CAMBIA EL ESTADO DE ORDEN
	$scope.$watch('idEstadoOrden', function (_new) {
		$scope.consultaOrden();
	});

	$scope.$watch('tipoVista', function (_new) {
		if ( _new == 'menu' ) {
			$scope.keyPanel = 'left';
		}
		else if ( _new == 'ticket' ) {
			$scope.keyPanel = 'right';
		}
		else if ( _new == 'dividido' && $scope.keyPanel != 'left' && $scope.keyPanel != 'right' ) {
			$scope.keyPanel = 'left';
		}
		
		console.log( $scope.keyPanel );
	});


	// CAMBIO DE FOCUS DE PANEL 
	$scope.panel = {
		'array'     : 'lstMenus',
		'index'     : 'ixMenuActual',
		'seleccion' : 'seleccionMenu',
		'focus'     : 'ixMenuFocus'
	};

	$scope.$watch('keyPanel', function (_new) {
		if ( _new == 'left' ) {
			$scope.panel.array     = 'lstMenus';
			$scope.panel.index     = 'ixMenuActual';
			$scope.panel.seleccion = 'seleccionMenu';
			$scope.panel.focus     = 'ixMenuFocus';
		}
		else if ( _new == 'right' ) {
			$scope.panel.array     = 'lstTickets';
			$scope.panel.index     = 'ixTicketActual';
			$scope.panel.seleccion = 'seleccionTicket';
			$scope.panel.focus     = 'ixTicketFocus';
		}
	});

	// ------------- SCROLL ELEMENT -----------------
	$scope.$watch('ixTicketActual', function (_new) {
		$scope.ixTicketFocus = -1;
		$scope.scroll( 'ixt_', _new );
	});

	$scope.$watch('ixMenuActual', function (_new) {
		$scope.ixMenuFocus = -1;
		$scope.scroll( 'ixm_', _new );
	});

	// ------------- SCROLL ELEMENT -----------------
	$scope.$watch('ixTicketFocus', function (_new) {
		$scope.scroll( 'ixt_item_', _new );
	});

	$scope.$watch('ixMenuFocus', function (_new) {
		$scope.scroll( 'ixm_item_', _new );
	});

	// AUTO-SCROLL
	$scope.scroll = function ( pref, index ) {
		// SI EL INDEX ES MAYOR O IGUAL A CEREO
		if ( index >= 0 ) {
			var position = 0, id = index;
			
			$timeout(function () {

				if ( pref === 'ixm_item_' || pref === 'ixt_item_' )
					id = $scope[ $scope.panel.array ][ $scope[ $scope.panel.index ] ].detalle[ index ].idDetalleOrdenMenu;

				// SI ES MAYOR AL PRIMERO (0)
				if ( index > 0 )
					position = $( '#' + pref + id ).position().top - 19;

				if ( pref === 'ixm_item_' )
					$('.body_lst_menu').animate( { scrollTop : position }, 100 );

				else if ( pref === 'ixt_item_' )
					$('.body_lst_ticket').animate( { scrollTop : position }, 100 );

				else
					$(".contenido-lst-orden").animate( { scrollTop : position }, 100 );
			});
		}
	};



	// CLASIFICA ORDEN EN MENUS DE <==== lstMenusMaster
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
					tiempoAlerta : $scope.lstMenusMaster[ ip ].tiempoAlerta,
					numMenus     : 0,
					primerTiempo : $scope.lstMenusMaster[ ip ].fechaRegistro,
					detalle      : []
				});
			}
			
			// AGREGA DETALL AL MENU
			$scope.lstMenus[ ixMenu ].detalle.push({
				perteneceCombo      : $scope.lstMenusMaster[ ip ].perteneceCombo,
				idMenu              : $scope.lstMenusMaster[ ip ].idMenu,
				idDetalleOrdenMenu  : $scope.lstMenusMaster[ ip ].idDetalleOrdenMenu,
				cantidad            : $scope.lstMenusMaster[ ip ].cantidad,
				fechaRegistro       : $scope.lstMenusMaster[ ip ].fechaRegistro,
				tipoServicio        : $scope.lstMenusMaster[ ip ].tipoServicio,
				idTipoServicio      : $scope.lstMenusMaster[ ip ].idTipoServicio,
				idCombo             : $scope.lstMenusMaster[ ip ].idCombo,
				idDetalleOrdenCombo : $scope.lstMenusMaster[ ip ].idDetalleOrdenCombo,
				imagenCombo         : $scope.lstMenusMaster[ ip ].imagenCombo
			});

			$scope.lstMenus[ ixMenu ].numMenus += parseInt( $scope.lstMenusMaster[ ip ].cantidad );
		}
	};

	// CLASIFICA ORDEN EN TICKETS DE <==== lstMenusMaster
	$scope.lstPorTicket = function () {
		console.log( $scope.lstMenusMaster );

		return false;

		$scope.lstTickets = [];
		for (var ip = 0; ip < $scope.lstMenusMaster.length; ip++) {
			var ixMenu = -1;

			for (var im = 0; im < $scope.lstTickets.length; im++) {
				if ( $scope.lstTickets[ im ].idMenu == $scope.lstMenusMaster[ ip ].idMenu ) {
					ixMenu = im;
					break;
				}
			}

			// SI NO EXISTE SE CREA DATOS DE MENU
			if ( ixMenu == -1 ) {
				ixMenu = $scope.lstTickets.length;
				$scope.lstTickets.push({
					idMenu       : $scope.lstMenusMaster[ ip ].idMenu,
					codigoMenu   : $scope.lstMenusMaster[ ip ].codigoMenu,
					menu         : $scope.lstMenusMaster[ ip ].menu,
					imagen       : $scope.lstMenusMaster[ ip ].imagen,
					numMenus     : 0,
					primerTiempo : $scope.lstMenusMaster[ ip ].fechaRegistro,
					detalle      : []
				});
			}
			
			// AGREGA DETALL AL MENU
			$scope.lstTickets[ ixMenu ].detalle.push({
				perteneceCombo      : $scope.lstMenusMaster[ ip ].perteneceCombo,
				idMenu              : $scope.lstMenusMaster[ ip ].idMenu,
				idDetalleOrdenMenu  : $scope.lstMenusMaster[ ip ].idDetalleOrdenMenu,
				cantidad            : $scope.lstMenusMaster[ ip ].cantidad,
				fechaRegistro       : $scope.lstMenusMaster[ ip ].fechaRegistro,
				tipoServicio        : $scope.lstMenusMaster[ ip ].tipoServicio,
				idTipoServicio      : $scope.lstMenusMaster[ ip ].idTipoServicio,
				idCombo             : $scope.lstMenusMaster[ ip ].idCombo,
				idDetalleOrdenCombo : $scope.lstMenusMaster[ ip ].idDetalleOrdenCombo,
				imagenCombo         : $scope.lstMenusMaster[ ip ].imagenCombo
			});

			$scope.lstTickets[ ixMenu ].numMenus += parseInt( $scope.lstMenusMaster[ ip ].cantidad );
		}

		console.log( $scope.lstTickets );
	};


	/* ************************** CAMBIO DE ESTADO ********************** */
	// SI CAMBIA ESTADO DE ORDEN
	$scope.cambioEstadoOrden = function ( idEstadoOrden, tipo ) {
		if ( $scope.$parent.loading ) return false;

		if ( $scope.permitirAccion() ) 
		{
			if ( tipo === 'ticket' )
			{
				$scope.idEstadoOrdenTk = idEstadoOrden;
				$scope.consultaOrdenTicket();
			}
			else
				$scope.idEstadoOrden = idEstadoOrden;
		}
	};

	/* ************************** AUXILIARES ********************** */
	// SI SE PERMITE ACCION
	$scope.permitirAccion = function ( noMostrarAlerta ) {
		var respuesta = true;
		if ( $scope.seleccionMenu.si || $scope.seleccionTicket.si ) {
			if ( !noMostrarAlerta ) {
				alertify.set('notifier','position', 'top-right');
				alertify.notify( "Existen elementos seleccionados", 'info', 2 );
			}

			respuesta = false;
		}

		return respuesta;
	};

	// CAMBIO DE TIPO DE VISTA
	$scope.cambiarVista = function ( tipoVista ) {
		if ( $scope.permitirAccion() )
			$scope.tipoVista = tipoVista;
	};


	/* ************************** SELECCION DE MENUS ********************** */
	// SELECCIONA O DESELECCIONA TODOS
	$scope.selItemMenu = function ( seleccionado, ixDetalle ) {
		if ( !( $scope.ixMenuActual >= 0 ) )
			return false;

		$scope.seleccionMenu.menu   = $scope.lstMenus[ $scope.ixMenuActual ].menu;
		$scope.seleccionMenu.imagen = $scope.lstMenus[ $scope.ixMenuActual ].imagen;
		$scope.seleccionMenu.si     = false;

		// SI ES INDIVIDUAL
		if ( ixDetalle != undefined ) {
			var valor          = -1;
			var idTipoServicio = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ ixDetalle ].idTipoServicio;

			// REALIZA FOCUS AL ELEMENTO SELECCIONADO
			$scope[ $scope.panel.focus ] = ixDetalle;

			if ( seleccionado )
				valor = 1;

			if ( idTipoServicio == 1 )
				$scope.seleccionMenu.count.llevar += valor;

			else if ( idTipoServicio == 2 )
				$scope.seleccionMenu.count.restaurante += valor;

			else if ( idTipoServicio == 3 )
				$scope.seleccionMenu.count.domicilio += valor;
				
			$scope.seleccionMenu.count.total += valor;

			// SELECCION O DESELECCION
			$scope.lstMenus[ $scope.ixMenuActual ].detalle[ ixDetalle ].selected = seleccionado;

			// SI EL TOTAL ES MAYOR A CERO
			if ( $scope.seleccionMenu.count.total )
				$scope.seleccionMenu.si = true;
		}
		// SI ES PARA TODOS
		else if ( $scope.lstMenus[ $scope.ixMenuActual ] ) {
			$scope.seleccionMenu.count.llevar      = 0;
			$scope.seleccionMenu.count.restaurante = 0;
			$scope.seleccionMenu.count.domicilio   = 0;
			$scope.seleccionMenu.count.total  	   = 0;

			for (var i = 0; i < $scope.lstMenus[ $scope.ixMenuActual ].detalle.length; i++) {
				if ( seleccionado ) {
					var idTipoServicio = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ i ].idTipoServicio;

					if ( idTipoServicio == 1 )
						$scope.seleccionMenu.count.llevar++;

					else if ( idTipoServicio == 2 )
						$scope.seleccionMenu.count.restaurante++;

					else if ( idTipoServicio == 3 )
						$scope.seleccionMenu.count.domicilio++;

					$scope.seleccionMenu.count.total++;
				}

				$scope.lstMenus[ $scope.ixMenuActual ].detalle[ i ].selected = seleccionado;
			}

			if ( seleccionado )
				$scope.seleccionMenu.si = true;
		}
	};

	// SELECCIONA O DESELECCIONA TECLA -+
	$scope.selItemKey = function ( seleccionar ) {
		if ( !( $scope.ixMenuActual >= 0 ) )
			return false;

		// INFORMACION DE MENU
		$scope.seleccionMenu.menu   = $scope.lstMenus[ $scope.ixMenuActual ].menu;
		$scope.seleccionMenu.imagen = $scope.lstMenus[ $scope.ixMenuActual ].imagen;

		// SI EXISTE AL MENOS UN ELEMENTO
		if ( $scope.lstMenus[ $scope.ixMenuActual ].detalle.length ) {
			var index = -1;

			// BUSCAR EL PROXIMO PRIMER ELEMENTO SIN SELECCIONAR
			if ( seleccionar ) {
				for (var ix = 0; ix < $scope.lstMenus[ $scope.ixMenuActual ].detalle.length; ix++)
				{
					var itemSel = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ ix ].selected;

					if ( !itemSel ) {
						index = ix;
						break;
					}
				}

			}
			// BUSCAR EL ULTIMO PRIMER ELEMENTO SELECCIONADO
			else {
				for (var ix = ( $scope.lstMenus[ $scope.ixMenuActual ].detalle.length - 1 ); ix >= 0; ix--)
				{
					var itemSel = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ ix ].selected;

					if ( itemSel ) {
						index = ix;
						break;
					}
				}
			}

			// SI SE ENCONTRO ELEMENTO
			if ( index >= 0 ) {

				// ENFOCA EL ELEMENTO ACTUAL
				$scope[ $scope.panel.focus ] = index;

				// CAMBIA VALOR DE SELECTED
				$scope.lstMenus[ $scope.ixMenuActual ].detalle[ index ].selected = seleccionar;

				// CONSULTA EL TIPO DE SERVICIO
				var idTipoServicio = $scope.lstMenus[ $scope.ixMenuActual ].detalle[ index ].idTipoServicio;
				var valor = seleccionar ? 1 : -1;

				// SUMA O RESTA A TOTAL
				$scope.seleccionMenu.count.total += valor;

				// SUMA O RESTA POR TIPO DE SERVICIO
				if ( idTipoServicio == 1 )
					$scope.seleccionMenu.count.llevar += valor;

				else if ( idTipoServicio == 2 )
					$scope.seleccionMenu.count.restaurante += valor;

				else if ( idTipoServicio == 3 )
					$scope.seleccionMenu.count.domicilio += valor;

				// SI EXISTE MENU SELECCIONADO
				if ( $scope.seleccionMenu.count.total > 0 )
					$scope.seleccionMenu.si = true;

				else
					$scope.seleccionMenu.si = false;

			}
		}
	};


	/* ************************** SELECCION DE TICKETS ********************** */
	// SELECCIONA O DESELECCIONA TODOS
	$scope.selItemTicket = function ( seleccionado, ixDetalle ) {
		if ( !( $scope.ixTicketActual >= 0 ) )
			return false;

		$scope.seleccionTicket.menu   = $scope.lstTickets[ $scope.ixTicketActual ].menu;
		$scope.seleccionTicket.imagen = $scope.lstTickets[ $scope.ixTicketActual ].imagen;
		$scope.seleccionTicket.si     = false;

		// SI ES INDIVIDUAL
		if ( ixDetalle != undefined ) {

			// SOLO SI LA ORDEN ESTA LISTA => PASA A SERVIR
			if ( $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ixDetalle ].idEstadoDetalleOrden == 3 )
			{
				var valor          = -1;
				var idTipoServicio = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ixDetalle ].idTipoServicio;

				// REALIZA FOCUS AL ELEMENTO SELECCIONADO
				$scope[ $scope.panel.focus ] = ixDetalle;

				if ( seleccionado )
					valor = 1;

				if ( idTipoServicio == 1 )
					$scope.seleccionTicket.count.llevar += valor;

				else if ( idTipoServicio == 2 )
					$scope.seleccionTicket.count.restaurante += valor;

				else if ( idTipoServicio == 3 )
					$scope.seleccionTicket.count.domicilio += valor;
					
				$scope.seleccionTicket.count.total += valor;

				// SELECCION O DESELECCION
				$scope.lstTickets[ $scope.ixTicketActual ].detalle[ ixDetalle ].selected = seleccionado;

			}

			// SI EL TOTAL ES MAYOR A CERO
			if ( $scope.seleccionTicket.count.total )
				$scope.seleccionTicket.si = true;
		}
		// SI ES PARA TODOS
		else if ( $scope.lstTickets[ $scope.ixTicketActual ] ) {
			$scope.seleccionTicket.count.llevar      = 0;
			$scope.seleccionTicket.count.restaurante = 0;
			$scope.seleccionTicket.count.domicilio   = 0;
			$scope.seleccionTicket.count.total  	 = 0;

			for (var i = 0; i < $scope.lstTickets[ $scope.ixTicketActual ].detalle.length; i++)
			{
				// SOLO SI ES ESTADO 3 (LISTO)
				if ( $scope.lstTickets[ $scope.ixTicketActual ].detalle[ i ].idEstadoDetalleOrden == 3 ) 
				{
					if ( seleccionado ) {
						var idTipoServicio = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ i ].idTipoServicio;

						if ( idTipoServicio == 1 )
							$scope.seleccionTicket.count.llevar++;

						else if ( idTipoServicio == 2 )
							$scope.seleccionTicket.count.restaurante++;

						else if ( idTipoServicio == 3 )
							$scope.seleccionTicket.count.domicilio++;

						$scope.seleccionTicket.count.total++;
					}

					$scope.lstTickets[ $scope.ixTicketActual ].detalle[ i ].selected = seleccionado;
				}
			}

			// SI EXISTE AL MENOS UN ELEMENTO SELECCIONADO
			if ( $scope.seleccionTicket.count.total > 0 )
				$scope.seleccionTicket.si = true;
		}
	};

	// SELECCIONA O DESELECCIONA TECLA -+
	$scope.selItemKeyTicket = function ( seleccionar ) {
		if ( !( $scope.ixTicketActual >= 0 ) )
			return false;

		// INFORMACION DE MENU
		$scope.seleccionTicket.menu   = $scope.lstTickets[ $scope.ixTicketActual ].menu;
		$scope.seleccionTicket.imagen = $scope.lstTickets[ $scope.ixTicketActual ].imagen;

		// SI EXISTE AL MENOS UN ELEMENTO
		if ( $scope.lstTickets[ $scope.ixTicketActual ].detalle.length ) {
			var index = -1;

			// BUSCAR EL PROXIMO PRIMER ELEMENTO SIN SELECCIONAR
			if ( seleccionar ) {
				for (var ix = 0; ix < $scope.lstTickets[ $scope.ixTicketActual ].detalle.length; ix++)
				{
					var itemSel = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ix ];

					if ( !itemSel.selected && itemSel.idEstadoDetalleOrden == 3 ) {
						index = ix;
						break;
					}
				}

			}
			// BUSCAR EL ULTIMO PRIMER ELEMENTO SELECCIONADO
			else {
				for (var ix = ( $scope.lstTickets[ $scope.ixTicketActual ].detalle.length - 1 ); ix >= 0; ix--)
				{
					var itemSel = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ix ].selected;

					if ( itemSel ) {
						index = ix;
						break;
					}
				}
			}

			// SI SE ENCONTRO ELEMENTO Y ESTA EN ESTADO LISTA => PARA SERVIR
			if ( index >= 0 && $scope.lstTickets[ $scope.ixTicketActual ].detalle[ index ].idEstadoDetalleOrden == 3 ) {

				// ENFOCA EL ELEMENTO ACTUAL
				$scope[ $scope.panel.focus ] = index;

				// CAMBIA VALOR DE SELECTED
				$scope.lstTickets[ $scope.ixTicketActual ].detalle[ index ].selected = seleccionar;

				// CONSULTA EL TIPO DE SERVICIO
				var idTipoServicio = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ index ].idTipoServicio;
				var valor = seleccionar ? 1 : -1;

				// SUMA O RESTA A TOTAL
				$scope.seleccionTicket.count.total += valor;

				// SUMA O RESTA POR TIPO DE SERVICIO
				if ( idTipoServicio == 1 )
					$scope.seleccionTicket.count.llevar += valor;

				else if ( idTipoServicio == 2 )
					$scope.seleccionTicket.count.restaurante += valor;

				else if ( idTipoServicio == 3 )
					$scope.seleccionTicket.count.domicilio += valor;

				// SI EXISTE MENU SELECCIONADO
				if ( $scope.seleccionTicket.count.total > 0 )
					$scope.seleccionTicket.si = true;

				else
					$scope.seleccionTicket.si = false;

			}
		}
	};



	$scope.focusElement = function ( next ) {
		if ( $scope[ $scope.panel.array ][ $scope[ $scope.panel.index ] ] && $scope[ $scope.panel.array ][ $scope[ $scope.panel.index ] ].detalle.length ) 
		{
			var count = $scope[ $scope.panel.array ][ $scope[ $scope.panel.index ] ].detalle.length - 1;

			if ( next && $scope[ $scope.panel.focus ] < count ) {
				$scope[ $scope.panel.focus ]++;
			}
			else if ( !next && $scope[ $scope.panel.focus ] > 0 ) {
				$scope[ $scope.panel.focus ]--;
			}
		}
	};



	/* ************************** ATAJOS CON TECLADO ********************** */

	// AUX -> ATAJO INICIO
	$scope._keyInicio = function ( key, altDerecho ) {
		console.log( key, altDerecho );

		/************ PANELES ************ 
		*********************************/
		// ENFOCA TAB IZQUIERDO
		if ( altDerecho && key == 37 && $scope.tipoVista == 'dividido' && $scope.permitirAccion( true ) )
			$scope.keyPanel = 'left';

		// ENFOCA TAB DERECHO
		else if ( altDerecho && key == 39 && $scope.tipoVista == 'dividido' && $scope.permitirAccion( true ) )
			$scope.keyPanel = 'right';

		
		/************ CAMBIO DE USUARIO RAPIDO ************ 
		*********************************/
		else if ( altDerecho && key == 85 && $scope.permitirAccion( true ) ) // {ALT+U}
			$scope.dialogoConsultaPersonal( 'mesero' );


		/************ SELECCION DE MENUS ************ 
		*********************************/
		// SELECCIONA EL ELEMENTO ANTERIOR
		else if ( !altDerecho && key == 39 && $scope.permitirAccion( true ) ) { // {LEFT}
			// si no se ha seleccionado escoge el primero
			if ( $scope[ $scope.panel.array ].length && $scope[ $scope.panel.index ] == -1 )
				$scope[ $scope.panel.index ] = 0;

			// suma en uno el index
			else if ( ( $scope[ $scope.panel.index ] + 1 ) < $scope[ $scope.panel.array ].length )
				$scope[ $scope.panel.index ]++;
		}
		// SELECCIONA EL ELEMENTO SIGUIENTE
		else if ( !altDerecho && key == 37 && $scope.permitirAccion( true ) ) { // {RIGHT}
			if ( $scope[ $scope.panel.index ] != -1 && $scope[ $scope.panel.index ] > 0 )
				$scope[ $scope.panel.index ]--;
			
			else if ( $scope[ $scope.panel.index ] == -1 )
				$scope[ $scope.panel.index ] = $scope[ $scope.panel.array ].length - 1;
		}
		// DESELECCIONA ELEMENTO ACTUAL
		else if ( !altDerecho && key == 8 && $scope.permitirAccion( true ) && $scope[ $scope.panel.index ] != -1 ) { // {BACKSPACE}
			$scope[ $scope.panel.index ] = -1;
		}
		// PARA SELECCION DE ORDENES
		else if ( !altDerecho && key == 84 ) // {T}  => SELECCIONAR TODOS
		{
			if ( $scope.keyPanel == 'left' )
				$scope.selItemMenu( true );

			else
				$scope.selItemTicket( true );
		}
		
		else if ( !altDerecho && key == 78 ) // {N}  => DESELECCIONAR TODOS
		{
			if ( $scope.keyPanel == 'left' )
				$scope.selItemMenu( false );

			else
				$scope.selItemTicket( false );
		}

		// SELECCIONA EL SIGUIENTE NO SELECCIONADO
		else if ( !altDerecho && ( key == 187 || key == 107 ) ) // {+}
		{
			if ( $scope.keyPanel == 'left' )
				$scope.selItemKey( true );

			else
				$scope.selItemKeyTicket( true );
		}

		// DESELECCIONA EL ULTIMO SELECCIONADO
		else if ( !altDerecho && ( key == 189 || key == 109 ) ) // {-}
		{
			if ( $scope.keyPanel == 'left' )
				$scope.selItemKey( false );

			else
				$scope.selItemKeyTicket( false );
		}

		// SELECCIONA EL ELMENTO ENFOCADO
		else if ( !altDerecho && key == 32 && $scope[ $scope.panel.focus ] >= 0 ) // {TAB}
		{
			var item = $scope[ $scope.panel.array ][ $scope[ $scope.panel.index ] ].detalle[ $scope[ $scope.panel.focus ] ];

			if ( $scope.keyPanel == 'left' )
				$scope.selItemMenu( !item.selected, $scope[ $scope.panel.focus ] );

			else if ( $scope.keyPanel == 'right' )
				$scope.selItemTicket( !item.selected, $scope[ $scope.panel.focus ] );
		}



		// FOCUS ELEMENTO SIGUIENTE
		else if ( !altDerecho && key == 40 && $scope[ $scope.panel.index ] >= 0 ) // {DOWN}
			$scope.focusElement( true );

		// FOCUS ELEMENTO ANTERIOR
		else if ( !altDerecho && key == 38 && $scope[ $scope.panel.index ] >= 0 ) // {UP}
			$scope.focusElement( false );


		/************ CONSULTA POR ESTADO ************ 
		**********************************************/
		else if ( altDerecho && key == 13 ) // {CONTINUA CON EL PROCESO}
			$scope.continuarProcesoMenu();
		

		/************ CONSULTA POR ESTADO ************ 
		**********************************************/
		// CAMBIO DE ESTADO
		else if ( !altDerecho && key == 80 ) // {P}
			$scope.cambioEstadoOrden( 1 );

		else if ( !altDerecho && key == 67 ) // {C}
			$scope.cambioEstadoOrden( 2 );

		else if ( !altDerecho && key == 76 ) // {L}
			$scope.cambioEstadoOrden( 3 );

		else if ( !altDerecho && key == 83 ) // {S}
			$scope.cambioEstadoOrden( 4 );


		/************ CONSULTA POR ESTADO ************ 
		**********************************************/
		// CAMBIO DE ESTADO
		else if ( !altDerecho && key == 75 ) // {K}
			$scope.cambioEstadoOrden( 1, 'ticket' );

		else if ( !altDerecho && key == 70 ) // {F}
			$scope.cambioEstadoOrden( 4, 'ticket' );

	};

	// FOCUS NEXT ELEMENT
	$scope.nextElement = function () {
		document.getElementById('clave').focus();
	};


	// *** RETORNA INDEX DE ARREGLO
	$scope.indexArray = function ( arr, cmp, _value ) {
		var index = -1, arreglo = [];

		if ( Array.isArray( arr ) )
			arreglo = arr;

		else
			arreglo = $scope[ arr ];

		for (var i = 0; i < arreglo.length; i++) {
			if ( arreglo[ i ][ cmp ] == _value ) {
				index = i;
				break;
			}
		}	

		return index;
	};


	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho, evento ) {

		// PREVENIR: SPACE, UP, DOWN
		if ( key == 32 || key == 38 || key == 40 )
			evento.preventDefault();

		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// SI NO EXISTE NINGUN DIALOGO ABIERTO
		if ( !$scope.modalOpen() ) {
			// MODO PANTALLA
			if ( altDerecho && key == 77 ) $scope.cambiarVista( 'menu' );
			else if ( altDerecho && key == 68 ) $scope.cambiarVista( 'dividido' );
			else if ( altDerecho && key == 84 ) $scope.cambiarVista( 'ticket' );

			else{
				$scope._keyInicio( key, altDerecho );
			}
		}

		// CUANDO ESTE ABIERTO ALGUN CUADRO DE DIALOGO
		else{
			// CUANDO EL DIALOGO DE NUEVA ORDEN ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_orden_cliente' ) ) {
				
			}
		}
	});


	/* ************************** INFORMACION DE NODEJS ********************** */
	// INFORMACION DE NODEJS
	$scope.$on('infoNode', function( event, datos ) {
		console.log( 'DTS::', datos );

		switch( datos.accion )
		{
			// NUEVA ORDEN
			case 'ordenNueva':
			case 'ordenAgregar':
				var lstDetalle = [], ixTicket = -1;
				for (var id = 0; id < datos.data.lstMenuAgregado.length; id++) {

					// SI ES DEL MISMO DESTINO
					if ( $scope.idDestinoMenu == datos.data.lstMenuAgregado[ id ].idDestinoMenu ) 
					{
						var item = datos.data.lstMenuAgregado[ id ];
						var detalleActual = {
							esCombo              : item.perteneceCombo,
							descripcion          : ( item.perteneceCombo ? item.menu + ' (' + item.combo + ')' : item.menu ),
							fechaRegistro        : item.fechaRegistro,
							idCombo              : null,
							idDetalleOrdenCombo  : item.idDetalleOrdenCombo,
							idDetalleOrdenMenu   : item.idDetalleOrdenMenu,
							idEstadoDetalleOrden : item.idEstadoDetalleOrden,
							idMenu               : item.idMenu,
							idTipoServicio       : item.idTipoServicio,
							imagen               : item.imagen,
							tipoServicio         : item.tipoServicio,
							numeroTicket         : item.numeroTicket,
							perteneceCombo       : item.perteneceCombo,
							imagenCombo          : null,
							cantidad             : 1
						};

						// AGREGA DETALLE
						lstDetalle.push( detalleActual );

						// OBTIENE INDEX SI EXISTE EL MENU
						var ixMenu = $scope.indexArray( 'lstMenus', 'idMenu', item.idMenu );

						// SI NO ESTA EL MENU SE AGREGA
						if ( ixMenu == -1 ) 
						{
							ixMenu = $scope.lstMenus.length;
							$scope.lstMenus.push({
								codigoMenu   : item.codigoMenu,
								idMenu       : item.idMenu,
								imagen       : item.imagen,
								menu         : item.menu,
								numMenus     : 0,
								primerTiempo : item.fechaRegistro,
								tiempoAlerta : item.tiempoAlerta,
								detalle      : []
							});
						}

						$scope.lstMenus[ ixMenu ].numMenus++;
						$scope.lstMenus[ ixMenu ].detalle.push( detalleActual );

						// BUSCA EN LISTA DE TICKET INDEX, Y ORDEN ES AGREGAR
						if ( ixTicket == -1 && datos.accion == 'ordenAgregar' )
							ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', item.idOrdenCliente );
					}
				}

				// SI EXISTE AL MENOS UN DETALLE PARA EL TICKET
				if ( lstDetalle.length )
				{
					if ( datos.accion == 'ordenNueva' ) {
						$scope.lstTickets.push({
							idOrdenCliente   : datos.data.ordenCliente.idOrdenCliente,
							numeroTicket     : datos.data.ordenCliente.numeroTicket,
							responsableOrden : datos.data.ordenCliente.usuarioResponsable,
							total : {
								pendientes : lstDetalle.length,
								cocinando  : 0,
								listos     : 0,
								servidos   : 0,
								total      : lstDetalle.length
							},
							detalle : lstDetalle
						});
					}

					// SI EXISTE EL TICKET
					if ( ixTicket >= 0 )
					{
						$scope.lstTickets[ ixTicket ].detalle          = $scope.lstTickets[ ixTicket ].detalle.concat( lstDetalle );
						$scope.lstTickets[ ixTicket ].total.total      += lstDetalle.length;
						$scope.lstTickets[ ixTicket ].total.pendientes += lstDetalle.length;
					}
				}
			break;

			// CANCELACION DE ORDEN PRINCIPAL
			case 'ordenPrincipalCancelada':

				// RETORNA INDEX DE TICKET SI EXISTE
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.idOrdenCliente );

				// SI SE ENCONTRO EL TICKET CANCELADO
				if ( ixTicket >= 0 ) 
				{
					// SI ES EL ACTUAL LO ELIMINA
					if ( ixTicket == $scope.ixTicketActual )
						$scope.ixTicketActual = -1;

					$scope.lstTickets.splice( ixTicket, 1 );
				}


				// RECORRE DETALLE CANCELADO
				for (var id = 0; id < datos.lstDetalle.length; id++) 
				{
					// SI SE ENCUENTRA EL MENU
					var ic = $scope.indexArray( 'lstMenus', 'idMenu', datos.lstDetalle[ id ].idMenu );
					
					if ( ic >= 0 ) 
					{
						var im2 = $scope.indexArray( $scope.lstMenus[ ic ].detalle, 'idDetalleOrdenMenu', datos.lstDetalle[ id ].idDetalleOrdenMenu );

						// SI SE ENCUENTRA DETALLE
						if ( im2 >= 0 )
						{
							$scope.lstMenus[ ic ].detalle.splice( im2, 1 );
							$scope.lstMenus[ ic ].numMenus--;

							// SI NO EXISTE DETALLE SE ELIMINA EL MENU
							if ( $scope.lstMenus[ ic ].detalle.length == 0 ) {
								$scope.lstMenus.splice( ic, 1 );
							}
						}
					}
				}
			break;

			case 'cancelarOrdenParcial':
				// ELIMINAR DE LISTA DE MENUS
				for (var im = 0; im < $scope.lstMenus.length; im++) {
					for (var idn = 0; idn < datos.data.lstDetalle.length; idn++) {

						var id = $scope.indexArray( $scope.lstMenus[ im ].detalle, 'idDetalleOrdenMenu', datos.data.lstDetalle[ idn ].idDetalleOrdenMenu );
						// SI SE ENCONTRO EL DETALLE
						if ( id >= 0 )
						{
							$scope.lstMenus[ im ].detalle.splice( id, 1 );
							$scope.lstMenus[ im ].numMenus--;

							// SI EL MENU SE QUEDA CON CERO ELEMENTOS
							if ( $scope.lstMenus[ im ].detalle.length == 0 )
								$scope.lstMenus.splice( im, 1 );
						}
					}
				}

				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.data.idOrdenCliente );

				if ( ixTicket >= 0 )
				{
					for (var id = 0; id < datos.data.lstDetalle.length; id++) {
						var ixDetalle = $scope.indexArray( $scope.lstTickets[ ixTicket ].detalle, 'idDetalleOrdenMenu', datos.data.lstDetalle[ id ].idDetalleOrdenMenu );

						// SI EXISTE EL DETALLE SE ELIMINA
						if ( ixDetalle >= 0 )
						{
							$scope.lstTickets[ ixTicket ].detalle.splice( ixDetalle, 1 );
							$scope.lstTickets[ ixTicket ].total.pendientes--;
							$scope.lstTickets[ ixTicket ].total.total--;

							// SI EL DETALLE QUEDA A CERO
							if ( $scope.lstTickets[ ixTicket ].detalle.length == 0 )
								$scope.lstTickets.splice( ixTicket, 1 );
						}
					}
				}
			break;

			// SI ES CAMBIO DE ESTADO DE ORDEN
			case 'cambioEstadoDetalleOrden':
				var estadoAnterior = datos.data.idEstadoOrden - 1;

				for (var i = 0; i < datos.data.lstOrdenes.length; i++)
				{
					var item = datos.data.lstOrdenes[ i ];

					// CONSULTA LAS ORDENES PENDIENTES DEL ESTADO ANTERIOR
					if ( $scope.idEstadoOrden == estadoAnterior )
					{
						var im = $scope.indexArray( 'lstMenus', 'idMenu', item.idMenu );

						// SI SE ENCUENTRA EL MENU
						if ( im >= 0 )
						{
							var id = $scope.indexArray( $scope.lstMenus[ im ].detalle, 'idDetalleOrdenMenu', item.idDetalleOrdenMenu );
							if ( id >= 0 )
							{
								$scope.lstMenus[ im ].numMenus--;
								$scope.lstMenus[ im ].detalle.splice( id, 1 );
							}

							if ( $scope.lstMenus[ im ].detalle.length == 0 )
							{
								$scope.ixMenuActual = -1;
								$scope.ixMenuFocus = -1;
								$scope.lstMenus.splice( im, 1 );
							}
						}
					} // FIN CAMBIO EN ESTADO ORDEN


					var it = $scope.indexArray( 'lstTickets', 'numeroTicket', item.numeroTicket );
					// SI EXISTE EL NUMERO DE TICKET
					if ( it >= 0 )
					{
						var index = $scope.indexArray( $scope.lstTickets[ it ].detalle, 'idDetalleOrdenMenu', item.idDetalleOrdenMenu );

						if ( index >= 0 )
						{
							// CAMBIA DE ESTADO A DETALLE
							$scope.lstTickets[ it ].detalle[ index ].idEstadoDetalleOrden = datos.data.idEstadoOrden;

							// CAMBIO DE ESTADO
							if ( estadoAnterior == 1 ) {
								$scope.lstTickets[ it ].total.pendientes--;
								$scope.lstTickets[ it ].total.cocinando++;
							}
							else if ( estadoAnterior == 2 ) {
								$scope.lstTickets[ it ].total.cocinando--;
								$scope.lstTickets[ it ].total.listos++;
							}
							else if ( estadoAnterior == 3 ) {
								$scope.lstTickets[ it ].total.listos--;
								$scope.lstTickets[ it ].total.servidos++;
							}
						}

						// SI TODOS LOS MENUS ESTAN SERVIDOS, SE QUITA DE LOS TICKETS PENDIENTES
						if ( $scope.lstTickets[ it ].total.servidos == $scope.lstTickets[ it ].total.total && $scope.idEstadoOrdenTk == 1 )
						{
							$scope.lstTickets.splice( it, 1 );
							$scope.ixTicketActual = -1;
						}

						// SI ES EL ELEMENTO ACTUAL CAMBIA IX FOCUS
						if ( it == $scope.ixTicketActual )
							$scope.ixMenuFocus = -1;
					}				
				}
			break;

			// CAMBIO DE TIPO DE SERVICIO A LA ORDEN
			case 'cambioTipoServicio':
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.data.idOrdenCliente );
				
				// SI ENCONTRO LA ORDEN
				if ( ixTicket >= 0 )
				{
					var lstDet = datos.data.detalleOrdenCliente.lst;

					for (var ip = 0; ip < lstDet.length; ip++) 
					{
						for (var ic = 0; ic < lstDet[ ip ].lstDetalle.length; ic++) 
						{
							var lst = $scope.lstTickets[ ixTicket ].detalle;
							for (var ix = 0; ix < lst.length; ix++) 
							{
								if( lst[ ix ].idDetalleOrdenMenu == lstDet[ ip ].lstDetalle[ ic ].idDetalleOrdenMenu ) 
								{
									$scope.lstTickets[ ixTicket ].detalle[ ix ].idTipoServicio = lstDet[ ip ].idTipoServicio;
									break;
								}
							}
						}
					}
				}

				var lstDet = datos.data.detalleOrdenCliente.lst;
				for (var ip = 0; ip < lstDet.length; ip++)
				{
					for (var ixd = 0; ixd < lstDet[ ip ].lstDetalle.length; ixd++) {

						for (var im = 0; im < $scope.lstMenus.length; im++) 
						{
							for (var imd = 0; imd < $scope.lstMenus[ im ].detalle.length; imd++) 
							{
								if ( $scope.lstMenus[ im ].detalle[ imd ].idDetalleOrdenMenu == lstDet[ ip ].lstDetalle[ ixd ].idDetalleOrdenMenu )
								{
									$scope.lstMenus[ im ].detalle[ imd ].idTipoServicio = lstDet[ ip ].idTipoServicio;
									break;
								}
							}
						}

					}
				}
			break;
		}

		$scope.$apply();
	});


	/* ************************** CONSULTA SI EXISTE MODAL ABIERTO ********************** */
	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};

	// DEFINE EL TAMAÑO DEL CONTENEDOR MENU O TICKET
	$(function(){
		$(".contenido-lst-orden").attr( 'style', "height:" + ( $(window).height() - 100 ) + "px" );
		$(window).resize(function (){
			$(".contenido-lst-orden").attr( 'style', "height:" + ( $(this).height() - 100 ) + "px" );
		}) 
	});
});