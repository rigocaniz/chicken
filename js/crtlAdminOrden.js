app.controller('crtlAdminOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstMenu        = [];
	$scope.minutosAlerta  = 20;
	$scope.idEstadoOrden  = 1;
	$scope.idEstadoOrdenTk = 1;
	$scope.tipoVista      = 'dividido';
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




	// CONSULTA INFORMACION DE ORDENES
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
				console.log( 'menu::', data );
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data.lstMenu ) ) 
				{
					$scope.ixMenuActual = -1;
					$scope.lstMenus     = data.lstMenu;
				}
				
				$scope.consultaOrdenTicket();
			});
		}
	};

	$scope.consultaOrdenTicket = function () {
		if ( $scope.$parent.loading ) return false;

		if ( $scope.idEstadoOrden > 0 && $scope.idDestinoMenu > 0 ) {

			var datos = { 
				opcion               : 'lstOrdenPorTicket',
				idEstadoOrden        : 'valid',
				idEstadoDetalleOrden : 'valid',
				idDestinoMenu        : $scope.idDestinoMenu
			};

			$scope.$parent.loading = true; // cargando...
			$http.post('consultas.php', datos)
			.success(function (data) {
				console.log( 'ticket::', data );
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data.lstTicket ) ) 
				{
					$scope.ixTicketActual = -1;
					$scope.lstTickets = data.lstTicket;
				}
			});
		}
	};


	// SI CAMBIA EL DESTINO DE MENU
	$scope.$watch('idDestinoMenu', function (_new) {
		$scope.consultaOrden();
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
		'seleccion' : 'seleccionMenu'
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

	// AUTO-SCROLL
	$scope.scroll = function ( pref, index ) {
		// SI EL INDEX ES MAYOR O IGUAL A CEREO
		if ( index >= 0 ) {
			var position = 0;
			
			// SI ES MAYOR AL PRIMERO (0)
			if ( index > 0 )
				position = $( '#' + pref + index ).offset().top;

			$(window).scrollTop( position );
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
	$scope.cambioEstadoOrden = function ( idEstadoOrden ) {
		if ( $scope.$parent.loading ) return false;

		if ( $scope.permitirAccion() )
			$scope.idEstadoOrden = idEstadoOrden;
	};

	//$timeout(function () { $scope.cambioEstadoOrden( 1 ); });


	/* ************************** AUXILIARES ********************** */
	// SI SE PERMITE ACCION
	$scope.permitirAccion = function ( noMostrarAlerta ) {
		var respuesta = true;
		if ( $scope.seleccionMenu.si ) {
			if ( !noMostrarAlerta ) {
				alertify.set('notifier','position', 'top-right');
				alertify.notify( "Existen menús seleccionados", 'info', 2 );
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
			var valor          = -1;
			var idTipoServicio = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ixDetalle ].idTipoServicio;

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

			// SI EL TOTAL ES MAYOR A CERO
			if ( $scope.seleccionTicket.count.total )
				$scope.seleccionTicket.si = true;
		}
		// SI ES PARA TODOS
		else if ( $scope.lstTickets[ $scope.ixTicketActual ] ) {
			$scope.seleccionTicket.count.llevar      = 0;
			$scope.seleccionTicket.count.restaurante = 0;
			$scope.seleccionTicket.count.domicilio   = 0;
			$scope.seleccionTicket.count.total  	   = 0;

			for (var i = 0; i < $scope.lstTickets[ $scope.ixTicketActual ].detalle.length; i++) {
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

			if ( seleccionado )
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
					var itemSel = $scope.lstTickets[ $scope.ixTicketActual ].detalle[ ix ].selected;

					if ( !itemSel ) {
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

			// SI SE ENCONTRO ELEMENTO
			if ( index >= 0 ) {
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

		// FOCUS ELEMENTO SIGUIENTE
		else if ( !altDerecho && key == 40 && $scope[ $scope.panel.index ] >= 0 && $scope.permitirAccion( true ) ) // {DOWN}
			$scope.focusElement( true );

		// FOCUS ELEMENTO ANTERIOR
		else if ( !altDerecho && key == 38 && $scope[ $scope.panel.index ] >= 0 && $scope.permitirAccion( true ) ) // {UP}
			$scope.focusElement( false );


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
	};

	// FOCUS NEXT ELEMENT
	$scope.nextElement = function () {
		document.getElementById('clave').focus();
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

		$scope.$apply();
	});


	/* ************************** CONSULTA SI EXISTE MODAL ABIERTO ********************** */
	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
});