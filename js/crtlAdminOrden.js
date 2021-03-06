app.controller('crtlAdminOrden', function( $scope, $http, $timeout, $modal ){
	$scope.lstMenu        = [];
	$scope.idEstadoOrden  = 1;
	$scope.idEstadoOrdenTk = 1;
	$scope.tipoVista      = 'ticket';
	$scope.ixMenuActual   = -1;
	$scope.ixTicketActual = -1;
	$scope.ixMenuFocus   = -1;
	$scope.ixTicketFocus = -1;

	$scope.lstDestinoMenu = [];
	$scope.lstMenus       = [];
	$scope.lstTickets     = [];
	$scope.codigoPersonal = '';
	$scope.clave 		  = '';
	$scope.keyPanel 	  = '';
	$scope.soundAlerta 	  = new Audio('sonidos/nuevaOrden.mp3');

	$scope.lstGrupos = [
		{ 'numeroGrupo' : '99','grupo' : 'Todos' },
		{ 'numeroGrupo' : '1', 'grupo' : 'Grupo #1' },
		{ 'numeroGrupo' : '2', 'grupo' : 'Grupo #2' },
		{ 'numeroGrupo' : '3', 'grupo' : 'Grupo #3' },
		{ 'numeroGrupo' : '4', 'grupo' : 'Grupo #4' },
		{ 'numeroGrupo' : '5', 'grupo' : 'Grupo #5' },
	];
	$scope.numeroGrupo 	  = '99';

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
		si           : false,
		menu         : '',
		imagen       : null,
		numeroTicket : '',
		count        : {
			total 		: 0,
			llevar      : 0,
			restaurante : 0,
			domicilio   : 0
		},
	};

	$scope.lstEstados = [ 
		{ abr : 'P', title : 'Pendiente', css : 'default' },
		{ abr : 'C', title : 'Cocinando', css : 'info' },
		{ abr : 'L', title : 'Listo', css : 'primary' },
		{ abr : 'S', title : 'Servido', css : 'success' }
	];

	$scope.ticketActual = {};

	$scope.idDestinoMenu = ''; // 1:cocina, 2:barra
	$scope.agruparPor  = '';
	$scope.user = {};

	if( document.getElementById("consulta.personal.html") )
		$scope.dConsultaPersonal = $modal({scope: $scope,template:'consulta.personal.html', show: false, backdrop:false, keyboard: true });
	if( document.getElementById("ayuda.html") )
		$scope.dAyuda = $modal({scope: $scope,template:'ayuda.html', show: false, backdrop:false, keyboard: true });
	
	alertify.set('notifier','position', 'top-right');

	// CONSULTA INFORMACION DE PERSONA
	$http.post('consultas.php', { opcion : 'iniOrdenAdmin' })
	.success(function (data) { 

		if ( data.usuario != undefined ) {
			$scope.lstDestinoMenu = data.lstDestinoMenu;
			$scope.user           = data.usuario;
			$timeout(function () {
				if ( data.lstDestinoMenu.length )
					$scope.idDestinoMenu = ( $scope.user.idDestinoMenu || '1' );
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

		if ( $scope.idEstadoOrden > 0 && $scope.idDestinoMenu > 0 && $scope.numeroGrupo > 0 ) {

			var datos = { 
				opcion               : 'consultaOrdenesCocina',
				idEstadoDetalleOrden : $scope.idEstadoOrden,
				idDestinoMenu        : $scope.idDestinoMenu,
				numeroGrupo 		 : $scope.numeroGrupo
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
				opcion         : 'lstOrdenPorTicket',
				idEstadoOrden  : idEstadoOrden,
				numeroGrupo    : $scope.numeroGrupo
			};

			$scope.$parent.loading = true; // cargando...
			$http.post('consultas.php', datos)
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...


				if ( Array.isArray( data.lstTicket ) ) 
				{
					$scope.lstTickets = data.lstTicket;
				}
			});
		}
	};

	// CONSULTA DETALLE DE ORDEN (***DETALLE***)
	$scope.consultaDetalleOrden = function ( force ) {
		if ( $scope.$parent.loading && force === undefined )
			return false;

		// SI NO ESTA DEFINIDO LA ORDEN
		if ( !( $scope.ticketActual.idOrdenCliente > 0 ) )
			return false;

		$scope.ticketActual.lstOrden = [];

		$scope.$parent.loading = true;
		$http.post('consultas.php', { opcion : 'lstDetalleOrdenCliente', idOrdenCliente : $scope.ticketActual.idOrdenCliente, todo : false })
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
						
						// OBTENER INDEX DEL ESTADO
						ixT = $scope.indexArray( lstEstados, 'idEstado', idEstadoActual );

						if ( ixT == -1 )
						{
							var estado = $scope.lstEstados[ idEstadoActual - 1 ] || {};
							ixT        = lstEstados.length;
							
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

				$scope.ticketActual.lstOrden = data.lst;
				console.log( data );

				$timeout(function () {
					var index = -1;

					for (var ix = 0; ix < data.lst.length; ix++)
						if ( data.lst[ ix ].limite )
						{
							index = ix;
							break;
						}
					
					// SI EXISTE ALGUN MENU QUE SERVIR
					if ( index >= 0 )
						document.getElementById( "detalle_orden_" + index ) && document.getElementById( "detalle_orden_" + index ).focus();
				});
			}
		});
	};


	// SERVIR MENU DE ORDEN
	$scope.servirMenu = function( index ) {
		var orden = angular.copy( $scope.ticketActual.lstOrden[ index ] );

		if ( $scope.$parent.loading || !( orden.seleccionados > 0 ) )
			return false;

		var datos = {
			opcion : 'servirMenuCliente',
			datos  : {
				idOrdenCliente : $scope.ticketActual.idOrdenCliente,
				seleccionados  : orden.seleccionados,
				idCombo        : ( orden.esCombo ? orden.idCombo : null ),
				idMenu         : ( !orden.esCombo ? orden.idMenu : null ),
				idTipoServicio : orden.idTipoServicio,
			}
		};

		// SI NO ESTA DEFINIDO LA ORDEN
		if ( !( $scope.ticketActual.idOrdenCliente > 0 ) )
			return false;

		$scope.$parent.loading = true;
		$http.post('consultas.php', datos )
		.success(function (data) {
			$scope.$parent.loading = false;
			alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

			// SI GUARDO CORRECTAMENTE
			if ( data.respuesta == 'success' )
			{
				/*
				console.log( 'ticketActual::: ', $scope.ticketActual );
				$scope.ticketActual.lstOrden[ index ].limite           = 0;
				$scope.ticketActual.lstOrden[ index ].cantidadRestante = 0;
				*/
			}
		});
	};

	// SERVIR TODAS LAS ORDENES
	$scope.servirTodo = function() {
		var lstOrden = [];

		for (var ixO = 0; ixO < $scope.ticketActual.lstOrden.length; ixO++)
		{
			//console.log( $scope.ticketActual.lstOrden[ ixO ] );
			var orden = $scope.ticketActual.lstOrden[ ixO ];
			lstOrden.push({
				idOrdenCliente : $scope.ticketActual.idOrdenCliente,
				seleccionados  : orden.seleccionados,
				idCombo        : ( orden.esCombo ? orden.idCombo : null ),
				idMenu         : ( !orden.esCombo ? orden.idMenu : null ),
				idTipoServicio : orden.idTipoServicio,
				limite         : orden.limite

			});
		}

		if ( $scope.$parent.loading || !( lstOrden.length > 0 ) )
			return false;

		var datos = {
			opcion   : 'servirTodo',
			lstOrden : lstOrden
		};

		$scope.$parent.loading = true;
		$http.post('consultas.php', datos )
		.success(function (data) {
			$scope.$parent.loading = false;
			alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

			// SI GUARDO CORRECTAMENTE
			if ( data.respuesta == 'success' )
			{
				if( $scope.ticketActual.lstOrden && $scope.ticketActual.lstOrden.length  )
				{
					for (var ixO = 0; ixO < $scope.ticketActual.lstOrden.length; ixO++)
					{
						$scope.ticketActual.lstOrden[ ixO ].limite           = 0;
						$scope.ticketActual.lstOrden[ ixO ].cantidadRestante = 0;
					}
				}
			}
		});
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
			si           : false,
			menu         : '',
			imagen       : null,
			numeroTicket : '',
			count        : {
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

	// SI CAMBIA EL ESTADO DE ORDEN
	$scope.$watch('numeroGrupo', function (_new) {
		$scope.consultaOrden();
	});

	$scope.$watch('tipoVista', function (_new) {
		if ( _new == 'menu' ) {

			// ENFOCAR MENU ACTUAL SI ESTABA SELECCIONADO
			$timeout(function () {
				if ( $scope.ixMenuActual >= 0 )
					document.getElementById( "input_" + $scope.ixMenuActual ) && document.getElementById( "input_" + $scope.ixMenuActual ).focus();
			});

			$scope.keyPanel = 'left';
		}
		else if ( _new == 'ticket' ) {
			$scope.keyPanel = 'right';
		}
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

		$scope.ticketActual = {};
		if ( _new >= 0 )
		{
			$scope.ticketActual = $scope.lstTickets[ _new ];
			$scope.consultaDetalleOrden();
		}
	});

	$scope.$watch('ixMenuActual', function (_new) {
		$scope.ixMenuFocus = -1;
		$scope.scroll( 'ixm_', _new );

		if ( _new >= 0 )
			document.getElementById( "input_" + _new ) && document.getElementById( "input_" + _new ).focus();
	});

	// ------------- SCROLL ELEMENT -----------------
	$scope.$watch('ixTicketFocus', function (_new) {
		$scope.scroll( 'ixt_item_', _new );
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
		if ( $scope.seleccionCocina.si || $scope.seleccionTicket.si ) {
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
	// CANTIDAD A COCINAR
	$scope.seleccionCocina = {};
	$scope.cantidadCocinar = function ( menu, _index ) {
		var siParcial = true; // SI APLICA SELECCION PARCIAL
		var todoRecorrido = false,
			aSeleccionar = angular.copy( menu.seleccionados );

		$scope.seleccionCocina = {
			si 			  : false,
			index 		  : _index,
			idMenu        : angular.copy( menu.idMenu ),
			menu          : angular.copy( menu.menu ),
			imagen        : angular.copy( menu.imagen ),
			seleccionados : angular.copy( menu.seleccionados ),
			lstOrden 	  : [],
			observaciones : ""
		};


		for (var ixO = 0; ixO < menu.lstOrden.length; ixO++) {
			var seleccionados = 0;

			// SI CUBRE LA TOTALIDAD DE LA ORDEN
			if ( aSeleccionar >= menu.lstOrden[ ixO ].total && !todoRecorrido )
				seleccionados = angular.copy( menu.lstOrden[ ixO ].total );

			// SI YA SE RECORRIO TODAS LAS ORDENES, SELECCIONA EL PRIMERO QUE FUE IGNORADO
			else if ( todoRecorrido && aSeleccionar && !( menu.lstOrden[ ixO ].seleccionados > 0 ) )
				seleccionados = aSeleccionar;

			// SI NO CUMPLE ENTONCES SELECCIONADOS ES 0
			else if ( !todoRecorrido )
				menu.lstOrden[ ixO ].seleccionados = 0;

			// SI LOS SELECCIONADOS ES MAYOR A CERO
			if ( seleccionados > 0 )
			{
				$scope.seleccionCocina.si = true;

				// SI EXISTE COMENTARIO SE AGREGA
				if ( menu.lstOrden[ ixO ].observacion.length )
					$scope.seleccionCocina.observaciones += menu.lstOrden[ ixO ].observacion;

				// AGREGA ORDEN A LISTA
				$scope.seleccionCocina.lstOrden.push({
					idOrdenCliente : angular.copy( menu.lstOrden[ ixO ].idOrdenCliente ),
					numeroTicket   : angular.copy( menu.lstOrden[ ixO ].numeroTicket ),
					seleccionados  : seleccionados,
					total          : angular.copy( menu.lstOrden[ ixO ].total ),
					observacion    : angular.copy( menu.lstOrden[ ixO ].observacion ),
				});

				menu.lstOrden[ ixO ].seleccionados = angular.copy( seleccionados );
				aSeleccionar -= seleccionados;
			}

			// SI LLEGO AL FINAL DEL RECORRIDO
			if ( ( ixO + 1 ) === menu.lstOrden.length && !todoRecorrido )
			{
				todoRecorrido = true;

				// SI ES POSIBLE SELECCIONAR ORDENES DE MANERA PARCIAL
				if ( aSeleccionar > 0 && siParcial )
					ixO = -1;
			}
		}
	};

	// GUARDA CAMBIO DE ESTADO DETALLE ORDEN
	$scope.guardarEstadoDetalleOrden = function () {
		if ( $scope.$parent.loading ) return false;

		// REALIZA CONSULTA HACIA EL SERVIDOR
		if ( $scope.seleccionCocina.si ) 
		{
			var datos = { 
				opcion        : 'cambioEstadoCocina',
				idEstadoOrden : ( $scope.idEstadoOrden + 1 ),
				lstOrdenes    : $scope.seleccionCocina,
			};

			$scope.$parent.loading = true; // cargando...

			$http.post('consultas.php', datos)
			.success(function (data) {
				$scope.$parent.loading = false; // cargando...

				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );
			
				$scope.lastId = "";

				if ( data.respuesta == 'success' )
					$scope.descontarOrden( true, $scope.seleccionCocina.idMenu, $scope.seleccionCocina.lstOrden, data.myId );
			});
		}
	};

	$scope.myId = "";
	$scope.descontarOrden = function ( _current, _idMenu, _lstOrden, _myId ) {
		var _ixMenu = -1,
			ordenEncontrada = false;

		/* 
			VALIDADOR DE ACTUALIZACION
			{{{{{ "myId" }}}}} 
		*/
		if ( $scope.myId == _myId )
			return false;

		else
			$scope.myId = _myId;

		// SI ES EL ACTUAL
		if ( _current )
			_ixMenu = $scope.seleccionCocina.index;
		
		// SI ES PARA OTROS CLIENTES
		else
			_ixMenu = $scope.indexArray( 'lstMenus', 'idMenu', _idMenu );

		// SI NO EXISTE EL MENU
		if ( _ixMenu == -1 || $scope.lstMenus[ _ixMenu ] == undefined )
			return false;

		// RECORRE ORDENES SELECCINADAS
		for (var ixOr = 0; ixOr < _lstOrden.length; ixOr++) {
			
			for (var ixO = 0; ixO < $scope.lstMenus[ _ixMenu ].lstOrden.length; ixO++)
			{
				var actual = angular.copy( $scope.lstMenus[ _ixMenu ].lstOrden[ ixO ] );

				// SI LA ORDEN ES LA MISMA
				if ( _lstOrden[ ixOr ].idOrdenCliente == actual.idOrdenCliente && _lstOrden[ ixOr ].seleccionados > 0 )
				{
					ordenEncontrada = true; // SI SE ENCONTRO LA ORDEN
					$scope.lstMenus[ _ixMenu ].lstOrden[ ixO ].seleccionados = 0;
					$scope.lstMenus[ _ixMenu ].lstOrden[ ixO ].total         -= _lstOrden[ ixOr ].seleccionados;
					$scope.lstMenus[ _ixMenu ].total                         -= _lstOrden[ ixOr ].seleccionados;

					// SI EL TOTAL DE LA ORDEN ES CERO
					if ( $scope.lstMenus[ _ixMenu ].lstOrden[ ixO ].total == 0 )
						$scope.lstMenus[ _ixMenu ].lstOrden.splice( ixO, 1 );

					break;
				}
			}
		}

		// SI SE ENCONTRO UNA ORDEN SE LIMPIA SELECCIONADOS
		if ( ordenEncontrada )
		{
			$scope.seleccionCocina.si = false;
			$scope.lstMenus[ _ixMenu ].seleccionados = "";
		}

		// SI EL TOTAL DEL MENU ES CERO
		if ( $scope.lstMenus[ _ixMenu ].total == 0 )
		{
			$scope.lstMenus.splice( _ixMenu, 1 );

			// SI ES EL MISMO MENU
			if ( $scope.seleccionCocina.idMenu == _idMenu )
				$scope.seleccionCocina = {};
		}
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


	/* ************************** SELECCION DE TICKETS ********************** */
	$scope.idEstadoOrdenTkt = 0;

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
		
		/************ CAMBIO DE USUARIO RAPIDO ************ 
		*********************************/
		if ( altDerecho && key == 85 && $scope.permitirAccion( true ) ) // {ALT+U}
			$scope.dialogoConsultaPersonal( 'mesero' );


		/************ SELECCION DE MENUS ************ 
		*********************************/
		// SELECCIONA EL ELEMENTO ANTERIOR
		else if ( !altDerecho && key == 40 && $scope.permitirAccion( true ) ) { // {DOWN}
			// si no se ha seleccionado escoge el primero
			if ( $scope[ $scope.panel.array ].length && $scope[ $scope.panel.index ] == -1 )
				$scope[ $scope.panel.index ] = 0;

			// suma en uno el index
			else if ( ( $scope[ $scope.panel.index ] + 1 ) < $scope[ $scope.panel.array ].length )
				$scope[ $scope.panel.index ]++;
		}
		// SELECCIONA EL ELEMENTO SIGUIENTE
		else if ( !altDerecho && key == 38 && $scope.permitirAccion( true ) ) { // {UP}
			if ( $scope[ $scope.panel.index ] != -1 && $scope[ $scope.panel.index ] > 0 )
				$scope[ $scope.panel.index ]--;
			
			else if ( $scope[ $scope.panel.index ] == -1 )
				$scope[ $scope.panel.index ] = $scope[ $scope.panel.array ].length - 1;
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

		/************ CONSULTA POR ESTADO ************ 
		**********************************************/
		else if ( key == 117 ) // {CONTINUA CON EL PROCESO}
			$scope.guardarEstadoDetalleOrden();

		/************ CONSULTA POR ESTADO ************ 
		**********************************************/
		// CAMBIO DE ESTADO
		else if ( altDerecho && key == 80 ) // {P}
			$scope.cambioEstadoOrden( 1 );

		else if ( altDerecho && key == 67 ) // {C}
			$scope.cambioEstadoOrden( 2 );

		else if ( altDerecho && key == 76 ) // {L}
			$scope.cambioEstadoOrden( 3 );

		else if ( altDerecho && key == 83 ) // {S}
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
			arreglo = angular.copy( arr );

		else
			arreglo = angular.copy( $scope[ arr ] );

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

			// ENFOCA TAB IZQUIERDO
			if ( altDerecho && key == 37 && $scope.permitirAccion( true ) )
				$scope.cambiarVista( 'menu' );

			// ENFOCA TAB DERECHO
			else if ( altDerecho && key == 39 && $scope.permitirAccion( true ) )
				$scope.cambiarVista( 'ticket' );

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
		switch( datos.accion )
		{
			// NUEVA ORDEN
			case 'ordenNueva':
			case 'ordenAgregar':
				var lstMO = angular.copy( datos.data.info.paraCocina );

				// REPRODUCIR ALERTA
				$scope.soundAlerta.play();

				/*
					// AGREGA ORDEN A MENU >>>>>>COCINA<<<<<<<
				*/
				for (var ixM = 0; ixM < lstMO.length; ixM++)
				{
					// SI ES EL MISMO DESTINO Y ESTADO DEL MENU
					if ( $scope.idDestinoMenu == lstMO[ ixM ].idDestinoMenu && $scope.idEstadoOrden == lstMO[ ixM ].idEstadoDetalleOrden )
					{
						var ixMenu = $scope.indexArray( 'lstMenus', 'idMenu', lstMO[ ixM ].idMenu ),
							orden = null;

						// RECORRE LISTA DE ORDENES DE MENU
						for (var ixO = 0; ixO < lstMO[ ixM ].lstOrden.length; ixO++)
						{
							var actual = lstMO[ ixM ].lstOrden[ ixO ];

							if ( actual.numeroGrupo == $scope.numeroGrupo || $scope.numeroGrupo == 99 )
								orden = angular.copy( actual );
						}

						// SI MENU NO EXISTE, Y APLICA AGREGAR ORDEN
						if ( ixMenu == -1 && orden != null )
							$scope.lstMenus.push( angular.copy( lstMO[ ixM ] ) );

						// SI MENU EXISTE, Y APLICA AGREGAR/MODIFICAR ORDEN
						if ( ixMenu >= 0 && orden != null )
						{
							var ixOrden = $scope.indexArray( $scope.lstMenus[ ixMenu ].lstOrden, 'idOrdenCliente', orden.idOrdenCliente );

							// SI ORDEN NO EXISTE EN MENU
							if ( ixOrden == -1 )
							{
								$scope.lstMenus[ ixMenu ].lstOrden.push( angular.copy( orden ) );
								$scope.lstMenus[ ixMenu ].total += orden.total;
							}
							else{
								var diferencia = ( parseInt( orden.total ) - parseInt( $scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ].total ) );
								$scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ] = angular.copy( orden );
								$scope.lstMenus[ ixMenu ].total += diferencia;
							}

							// ACTUALIZA EL MENU SI ES EL MISMO Y ESTA SELECCIONADO
							if ( $scope.seleccionCocina.si && $scope.seleccionCocina.idMenu == $scope.lstMenus[ ixMenu ].idMenu )
								$scope.cantidadCocinar( $scope.lstMenus[ ixMenu ], ixMenu );
						}
					}
				}


				/*
					######## SINCRONIZACION TICKET ########
				*/
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.data.idOrdenCliente ),
					orden = datos.data.info.paraTicket[ 0 ];

				// SI SE ENCONTRO EL TICKET SE ACTUALIZA
				if ( ixTicket >= 0 )
				{
					$scope.lstTickets[ ixTicket ] = orden;

					// SI ES LA ORDEN ACTUAL
					if ( $scope.ticketActual.idOrdenCliente == datos.data.idOrdenCliente )
						$scope.consultaDetalleOrden();

				}

				// SI ES EL MISMO GRUPO SE AGREGA
				else if ( orden.numeroGrupo == $scope.numeroGrupo || $scope.numeroGrupo == 99 )
					$scope.lstTickets.push( orden );
			break;


			// CANCELACION DE ORDEN PRINCIPAL
			case 'ordenPrincipalCancelada':

				// RETORNA INDEX DE TICKET SI EXISTE
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.idOrdenCliente );

				// SI SE ENCONTRO EL TICKET CANCELADO
				if ( ixTicket >= 0 ) 
				{
					$scope.lstTickets.splice( ixTicket, 1 );

					// SI ES EL ACTUAL LO ELIMINA
					if ( ixTicket == $scope.ixTicketActual )
					{
						$scope.ixTicketActual = -1;
						$scope.ticketActual   = {};
					}
				}

				// ELIMINA ORDEN DE MENU
				for (var ixMenu = 0; ixMenu < $scope.lstMenus.length; ixMenu++)
				{
					var ixOrden = $scope.indexArray( $scope.lstMenus[ ixMenu ].lstOrden, 'idOrdenCliente', datos.idOrdenCliente );

					if ( ixOrden >= 0 )
					{
						var actual 	  = angular.copy( $scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ] ),
							eliminado = false;

						$scope.lstMenus[ ixMenu ].total -= actual.total;
						$scope.lstMenus[ ixMenu ].lstOrden.splice( ixOrden, 1 );

						if ( $scope.lstMenus[ ixMenu ].total == 0 )
						{
							$scope.lstMenus.splice( ixMenu, 1 );
							$scope.seleccionCocina = {};
							eliminado = true;
						}

						// ACTUALIZA EL MENU SI ES EL MISMO Y ESTA SELECCIONADO
						if ( $scope.seleccionCocina.si && $scope.seleccionCocina.idMenu == $scope.lstMenus[ ixMenu ].idMenu )
							$scope.cantidadCocinar( $scope.lstMenus[ ixMenu ], ixMenu );

						// ELIMINADO
						if ( eliminado )
							ixMenu--;
					}
				}
			break;


			// CANCELAR ORDEN PARCIAL
			case 'cancelarOrdenParcial':
				/*
					================= CANCELAR ORDEN PARCIAL ================
				*/
				var lstMO = angular.copy( datos.data.info.paraCocina );

				/* >>>>>>COCINA<<<<<<< */
				for (var ixMenu = 0; ixMenu < $scope.lstMenus.length; ixMenu++)
				{
					var ixOrden = $scope.indexArray( $scope.lstMenus[ ixMenu ].lstOrden, 'idOrdenCliente', datos.data.info.idOrdenCliente );

					// SI EN EL MENU EXISTE LA ORDEN
					if ( ixOrden >= 0 )
					{
						var ixM    = $scope.indexArray( lstMO, 'idMenu', $scope.lstMenus[ ixMenu ].idMenu ),
							restar = 0;

						// SI EL MENU AUN EXISTE
						if ( ixM >= 0 )
						{
							restar = ( angular.copy( $scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ].total ) - lstMO[ ixM ].total );
							$scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ] = lstMO[ ixM ].lstOrden[ 0 ];
						}
						
						// SI EL MENU YA NO EXISTE EN LA ORDEN, SE RESTA EL TOTAL
						else
						{
							restar = angular.copy( $scope.lstMenus[ ixMenu ].lstOrden[ ixOrden ].total );
							$scope.lstMenus[ ixMenu ].lstOrden.splice( ixOrden, 1 );
						}

						$scope.lstMenus[ ixMenu ].total -= restar;


						// ACTUALIZA EL MENU SI ES EL MISMO Y ESTA SELECCIONADO
						if ( $scope.seleccionCocina.idMenu == $scope.lstMenus[ ixMenu ].idMenu ){

							if ( $scope.seleccionCocina.si && $scope.lstMenus[ ixMenu ].total == 0 )
								$scope.seleccionCocina = {};

							else
								$scope.cantidadCocinar( $scope.lstMenus[ ixMenu ], ixMenu );
						}

						// SI NO EXISTE MAS ORDENES EN EL MENU
						if ( $scope.lstMenus[ ixMenu ].total == 0 )
						{
							$scope.lstMenus.splice( ixMenu, 1 );
							ixMenu = -1;
						}
					}
				}


				/* >>>>>>TICKET<<<<<<< */
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.data.idOrdenCliente ),
					orden = datos.data.info.paraTicket[ 0 ];

				// SI SE ENCONTRO EL TICKET SE ACTUALIZA
				if ( ixTicket >= 0 )
				{
					if ( datos.data.info.paraTicket.length == 0 ){
						$scope.ixTicketActual = -1;
						$scope.lstTickets.splice( ixTicket, 1 );
						$timeout(function () {
							if ( $scope.lstTickets.length )
								$scope.ixTicketActual = 0;
						});
					}

					// SI ES LA ORDEN ACTUAL
					else if ( $scope.ticketActual.idOrdenCliente == datos.data.idOrdenCliente )
					{
						$scope.lstTickets[ ixTicket ] = orden;
						$scope.consultaDetalleOrden( true );
					}
				}
			break;

			// SI ES CAMBIO DE ESTADO DE ORDEN
			case 'cambioEstadoDetalleOrden':
				var estadoAnterior = datos.data.idEstadoOrden - 1;

				/*
					######## CONSULTA LAS ORDENES PENDIENTES DEL ESTADO ANTERIOR ########
				*/
				if ( $scope.idEstadoOrden == estadoAnterior )
				{
					$scope.descontarOrden( false, datos.data.ordenCocina.idMenu, datos.data.ordenCocina.lstOrden, datos.data.myId );
				}


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
							$scope.lstTickets[ it ].detalle[ index ].selected = false;

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
				/* >>>>>>TICKET<<<<<<< */
				var ixTicket = $scope.indexArray( 'lstTickets', 'idOrdenCliente', datos.data.idOrdenCliente );

				// SI SE ENCONTRO EL TICKET Y ES LA ORDEN ACTUAL
				if ( ixTicket >= 0 && $scope.ticketActual.idOrdenCliente == datos.data.idOrdenCliente )
					$scope.consultaDetalleOrden();

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
		$(".contenido-lst-orden").attr( 'style', "height:" + ( $(window).height() - 125 ) + "px" );
		$(window).resize(function (){
			$(".contenido-lst-orden").attr( 'style', "height:" + ( $(this).height() - 125 ) + "px" );
		}) 
	});
});