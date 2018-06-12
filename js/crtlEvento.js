app.controller('crtlEvento', function( $scope, $http, $timeout, $modal, $sce ){
	$scope.idEstadoEvento  = 1;
	$scope.idTab           = 1;
	$scope.accionEvento    = "insert";
	$scope.accionMenu      = '';
	$scope.tipo            = 'menu';
	$scope.lstEvento       = [];
	$scope.lstMenu         = [];
	$scope.lstResultado    = [];
	$scope.lstMenuEvento   = [];
	$scope.lstSalon        = [];
	$scope.lstFormaPago    = [];
	$scope.accionMenu      = '';
	$scope.busquedaCliente = '';
	$scope.evento          = {};
	$scope.filtroFecha     = '';
	$scope.menu            = {
		id       	   : 0,
		cantidad       : 0,
		precioUnitario : 0,
		otroMenu 	   : '',
		idMenu 		   : null,
		comentario     : ''
	};

	($scope.setEvento = function () {
		$scope.evento = {
			idEvento              : null,
			idEstadoEvento        : null,
			evento                : '',
			idCliente             : '',
			nombreCliente         : '',
			fechaEvento           : null,
			horaInicio            : null,
			horaFinal             : null,
			anticipo              : '',
			numeroPersonas        : 0,
			observacion           : '',
			descuento             : '',
			descripcionDescuento  : '',
			costoExtra            : '',
			descripcionCostoExtra : '',
			estadoEvento 		  : '',
			lstMovimiento         : [],
			idSalon               : $scope.lstSalon.length && $scope.lstSalon[ 0 ].idSalon
		};
	})();

	($scope.init = function () {
		$http.post('consultas.php', { opcion : 'iniEvento' })
		.success(function (data) {
			console.log( data );
			if ( data.lstSalon && data.lstFormaPago )
			{
				$scope.lstSalon          = data.lstSalon;
				$scope.lstFormaPago      = data.lstFormaPago;
			}
		});
	})();

	// DIALOGOS
	if( document.getElementById("dl.evento.html") )
		$scope.dialEvento = $modal({scope: $scope,template:'dl.evento.html', show: false, backdrop:false, keyboard: false });
	if( document.getElementById("facturar.evento.html") )
		$scope.dialFactEvento = $modal({scope: $scope,template:'facturar.evento.html', show: false, backdrop:false, keyboard: false });

	// MUESTRA DIALOGO DE EVENTO
	$scope.showDialOrden = function ( _accion, evento, _status ) {
		$scope.accionEvento = _accion;
		$scope.accionMenu   = '';

		if ( _accion == 'insert' ) {
			$scope.setEvento();

			$timeout(function () {
				if ( document.getElementById( 'busquedaCliente' ) )
					document.getElementById( 'busquedaCliente' ).focus();
			});
		}
		else if ( _accion == 'update' )
		{
			var _idEstadoEvento = _status || '', totalAnticipo = 0;

			for (var ix = 0; ix < evento.lstMovimiento.length; ix++)
				totalAnticipo += parseFloat( evento.lstMovimiento[ ix ].monto );

			$scope.evento = {
				idEvento              : evento.idEvento,
				evento                : evento.evento,
				idCliente             : evento.idCliente,
				nombreCliente         : $sce.trustAsHtml( '<b>' + evento.nombre + '</b> (' + evento.nit + ') - ' + evento.direccion ),
				fechaEvento           : moment( evento.fechaEvento ),
				horaInicio            : ( evento.horaInicio.length==5 ? evento.horaInicio : $scope.$parent.formatoFecha( evento.horaInicio, 'HH:mm' ) ),
				horaFinal             : ( evento.horaFinal.length==5 ? evento.horaFinal : $scope.$parent.formatoFecha( evento.horaFinal, 'HH:mm' ) ),
				anticipo              : evento.anticipo,
				numeroPersonas        : evento.numeroPersonas,
				observacion           : evento.observacion,
				idEstadoEvento        : evento.idEstadoEvento,
				idSalon        		  : evento.idSalon,
				newIdEstadoEvento     : _idEstadoEvento,
				estadoEvento          : evento.estadoEvento,
				lstMovimiento         : evento.lstMovimiento,
				totalAnticipo 		  : totalAnticipo
			};

			// SI NO ES CAMBIO DE ESTADO
			$scope.lstMenuEvento = evento.lstMenu;
			
			$scope.totalEvento(); // CALCULA TOTAL EVENTO
		}

		$scope.dialEvento.show();

		$timeout(function () {
			if ( _status == 10 )
				document.getElementById('comentario') && document.getElementById('comentario').focus();
		});
	};

	// MUESTRA DIALOGO FACTURA EVENTO
	$scope.showDialFactura = function ( evento, _status ) {
		$scope.accionEvento = 'update';
		var totalAnticipo = 0;

		for (var ix = 0; ix < evento.lstMovimiento.length; ix++)
			totalAnticipo += parseFloat( evento.lstMovimiento[ ix ].monto );

		$scope.evento = {
			idEvento          : evento.idEvento,
			idFactura         : evento.idFactura,
			evento            : evento.evento,
			idCliente         : evento.idCliente,
			nombreCliente     : $sce.trustAsHtml( '<b>' + evento.nombre + '</b> (' + evento.nit + ') - ' + evento.direccion ),
			nombre            : evento.nombre,
			nit               : evento.nit,
			direccion         : evento.direccion,
			fechaEvento       : moment( evento.fechaEvento ),
			horaInicio        : $scope.$parent.formatoFecha( evento.horaInicio, 'HH:mm' ),
			horaFinal         : $scope.$parent.formatoFecha( evento.horaFinal, 'HH:mm' ),
			anticipo          : evento.anticipo,
			numeroPersonas    : evento.numeroPersonas,
			observacion       : evento.observacion,
			idEstadoEvento    : evento.idEstadoEvento,
			idSalon        	  : evento.idSalon,
			newIdEstadoEvento : _status,
			estadoEvento      : evento.estadoEvento,
			lstMovimiento     : evento.lstMovimiento,
			totalAnticipo 	  : totalAnticipo,
			lstMenuEvento 	  : evento.lstMenu,
			totalEvento 	  : 0,
			montoEfectivo     : '',
			montoTarjeta      : '',
			cambio            : '',
			siDetalle 		  : true,
			descripcion 	  : ''
		};

		$scope.evento.totalEvento = $scope.totalEvento( evento.lstMenu );

		// SI NO ES CAMBIO DE ESTADO
		$scope.dialFactEvento.show();
	};

	// CONSULTAR CLIENTES
	$scope.consultarCliente = function () {
		if ( $scope.busquedaCliente.length > 3 )
		{
			$scope.lstResultado = [];
			$http.post('consultas.php', { opcion : 'consultarCliente', valor: $scope.busquedaCliente } )
			.success(function (data) {
				if ( Array.isArray( data.lstResultados ) && data.lstResultados.length )
				{
					for (var ic = 0; ic < data.lstResultados.length; ic++)
					{
						if ( data.lstResultados[ ic ].idCliente > 0 )
							$scope.lstResultado.push({
								idCliente : data.lstResultados[ ic ].idCliente,
								cliente   : $sce.trustAsHtml( '<b>' + data.lstResultados[ ic ].nombre + '</b> (' + data.lstResultados[ ic ].nit + ') » ' + data.lstResultados[ ic ].direccion ),
								active    : false
							});
					}

					if ( $scope.lstResultado && $scope.lstResultado.length )
						$scope.lstResultado[ 0 ].active = true;
				}
			});
		}
		else
			$scope.lstResultado = [];
	};

	// SELECCIONA CLIENTE
	$scope.seleccionarCliente = function ( item ) {
		$scope.busquedaCliente      = '';
		$scope.evento.idCliente     = item.idCliente;
		$scope.evento.nombreCliente = item.cliente;
		$scope.lstResultado         = [];
		document.getElementById('evento').focus();
	};

	// GUARDA EL EVENTO
	$scope.guardarEvento = function () {
		if ( !( $scope.evento.idCliente > 0 ) )
			alertify.notify( "Debe seleccionar un cliente", "danger", 2 );

		else if ( $scope.evento.evento.length < 4 )
			alertify.notify( "La descripción del Evento es muy corto", "danger", 3 );

		else if ( $scope.evento.fechaEvento == undefined || $scope.evento.fechaEvento == null )
			alertify.notify( "La fecha del evento no es válido", "danger", 2 );

		else if ( $scope.evento.horaInicio == undefined || $scope.evento.horaInicio == null )
			alertify.notify( "La hora de Inicio no es válida", "danger", 2 );

		else if ( $scope.evento.horaFinal == undefined || $scope.evento.horaFinal == null )
			alertify.notify( "La hora Final no es válida", "danger", 2 );

		else if ( $scope.evento.newIdEstadoEvento == 10 && !( $scope.evento.comentario && $scope.evento.comentario.length > 3 ) )
			alertify.notify( "Comentario demasiado corto", "danger", 5 );
		
		else
		{
			$scope.evento.fechaEventoTxt = moment( $scope.evento.fechaEvento ).format( "YYYY[-]MM[-]DD" );

			// CAMBIA ESTADO ACTUAL
			if ( $scope.evento.newIdEstadoEvento > 0 )
				$scope.evento.idEstadoEvento = $scope.evento.newIdEstadoEvento;

			$http.post('consultas.php', { 
				opcion : 'guardarEvento',
				accion : $scope.accionEvento,
				evento : $scope.evento
			})
			.success(function (data) {
				alertify.notify( data.mensaje, data.respuesta, 3 );

				if ( data.respuesta == 'success' ) {
					if ( $scope.accionEvento == 'insert' )
					{
						$scope.idTab           = 2;
						$scope.accionMenu      = 'insert';
						$scope.evento.idEvento = data.idEvento;
						$scope.menu.cantidad   = angular.copy( $scope.evento.numeroPersonas );
					}
					
					// CONSULTA EVENTOS
					$scope.consultaEvento();

					if ( $scope.evento.newIdEstadoEvento > 0 )
						$scope.dialEvento.hide();
				}
			});
		}
	};

	// CONSULTA MENUS O COMBOS
	$scope.consultarMenu = function () {
		var datos            = null;
		$scope.lstMenu       = [];

		if ( !( $scope.menu.id > 0 ) )
		{
			$scope.menu.otroMenu = "";
			$scope.menu.idMenu   = '';
		}
		
		if ( $scope.tipo == 'menu' ) 
			datos = { opcion : 'lstMenu', idTipoMenu : 0, idEstadoMenu : 1 };

		if ( $scope.tipo == 'combo' ) 
			datos = { opcion : 'lstCombo', idEstadoMenu : 1 };
	
		// SI SE CONSULTA INFORMACION
		if ( datos != null )
		{
			$http.post('consultas.php', datos )
			.success(function (data) {

				if ( $scope.tipo == 'menu' ) 
					$scope.lstMenu = $scope.arrayLst( data, 'idMenu', 'menu' );

				else if ( $scope.tipo == 'combo' )
					$scope.lstMenu = $scope.arrayLst( data, 'idCombo', 'combo' );

				$timeout(function () {

					// SI ESTA DEFINIDO EL MENU ACTUAL
					if ( $scope.menu.idMenuCurrent != undefined && $scope.menu.idMenuCurrent > 0 )
						$scope.menu.idMenu = $scope.menu.idMenuCurrent.toString();
					
					// SELECCIONA LA PRIMERA OPCION
					else if ( $scope.lstMenu.length )
						$scope.menu.idMenu = $scope.lstMenu[ 0 ].idMenu;
				});
			});
		}
	};

	// ACCION DEL MENU
	$scope.menuAccion = function ( _accion, _menu ) {
		$scope.accionMenu = _accion;

		if ( _accion == 'insert' )
		{
			$scope.menu.id             = 0;
			$scope.menu.precioUnitario = 0;
			$scope.menu.cantidad 	   = angular.copy( $scope.evento.numeroPersonas );
			$scope.menu.otroMenu       = '';
			$scope.menu.comentario     = '';
		}
		else if ( _accion == 'update' || _accion == 'delete' )
		{
			var otroMenu = ( _menu.idTipo == 'otroMenu' || _menu.idTipo == 'otroServicio' ) ?  _menu.menu : '';
			$scope.menu = {
				id       	   : _menu.id,
				cantidad       : parseInt( _menu.cantidad ),
				precioUnitario : parseFloat( _menu.precioUnitario ),
				otroMenu 	   : otroMenu,
				idMenu 		   : ( _menu.idTipo != $scope.tipo ? '' : _menu.idMenu ),
				horaDespacho   : _menu.horaDespacho,
				idMenuCurrent  : _menu.idMenu,
				menu  		   : _menu.menu,
				comentario     : _menu.comentario
			};

			if ( _menu.idTipo != $scope.tipo )
				$scope.tipo = _menu.idTipo;
		}

		else if ( _accion == 'insertMove' ) {
			$scope.movimiento = {
				idFormaPago : '1',
				monto       : '',
				motivo      : ''
			};
		}

		else if ( _accion == 'deleteMove' ) {
			$scope.movimiento = _menu;
		}
	};

	// GUARDAR MENU
	$scope.guardarMenu = function () {
		if ( !( $scope.menu.cantidad > 0 ) )
			alertify.notify( "La cantidad debe ser mayor a cero", "danger", 3 );

		else if ( !( $scope.menu.precioUnitario > 0 ) )
			alertify.notify( "El precio debe ser mayor a cero", "danger", 3 );

		else if ( ( $scope.tipo == 'otroMenu' || $scope.tipo == 'otroServicio' ) && !( $scope.menu.otroMenu.length > 3 ) )
			alertify.notify( "Error, menú no se encuentra especificado", "danger", 3 );

		else
		{
			var datos = {
				opcion : 'guardarMenuEvento',
				menu   : {
					accion         : $scope.accionMenu,
					id             : $scope.menu.id,
					idMenu         : $scope.menu.idMenu,
					cantidad       : $scope.menu.cantidad,
					horaDespacho   : $scope.menu.horaDespacho,
					precioUnitario : $scope.menu.precioUnitario,
					comentario     : $scope.menu.comentario,
					otroMenu       : $scope.menu.otroMenu,
					tipo           : $scope.tipo,
					idEvento       : $scope.evento.idEvento
				}
			};

			$http.post('consultas.php', datos )
			.success(function (data) {
				alertify.notify( data.mensaje, data.respuesta, 3 );

				if ( data.respuesta == 'success' ) {
					$scope.lstMenuEvento       = data.lstMenuEvento;
					$scope.accionMenu          = "";
					$scope.menu.id             = 0;
					$scope.menu.precioUnitario = 0;
					$scope.menu.comentario     = '';
					$scope.menu.otroMenu       = '';

					$scope.totalEvento(); // CALCULA TOTAL EVENTO
					// CONSULTA EVENTOS
					$scope.consultaEvento();
				}
			});
		}
	};

	// FACTURAR EVENTO
	$scope.facturarEvento = function () {
		var totalMonto  = ( $scope.evento.montoEfectivo + $scope.evento.montoTarjeta ),
			montoEvento = ( $scope.evento.totalEvento - $scope.evento.totalAnticipo );

		if ( !( $scope.evento.idCliente > 0 ) )
			alertify.notify( "Cliente no esta definido", "danger", 2 );

		else if ( !( $scope.evento.idEvento > 0 ) )
			alertify.notify( "El id del evento no esta definido", "danger", 3 );

		else if ( montoEvento > totalMonto )
			alertify.notify( "El monto ingresado no puede cubrir el total del evento", "danger", 4 );

		else
		{
			var winFactEvent = window.open( '', '_blank' );
			winFactEvent.document.body.innerHTML = "<h4>Espere, guardando...</h4>";
			window.focus();

			var datos = {
				opcion : 'facturarEvento',
				evento : {
					idEvento      : $scope.evento.idEvento,
					totalEvento   : $scope.evento.totalEvento,
					totalAnticipo : $scope.evento.totalAnticipo,
					montoEfectivo : $scope.evento.montoEfectivo,
					montoTarjeta  : $scope.evento.montoTarjeta,
					cambio  	  : $scope.evento.cambio,
					idCliente     : $scope.evento.idCliente,
					siDetalle     : $scope.evento.siDetalle,
					descripcion   : $scope.evento.descripcion,
					nombre        : $scope.evento.nombre,
					nit           : $scope.evento.nit,
					direccion     : $scope.evento.direccion,
				}
			};

			$http.post('consultas.php', datos)
			.success(function (data) {
				
				console.log( data );
				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

				if ( data.respuesta == 'success' )
				{
					winFactEvent.location = "print.php?id=" + data.idFactura + "&isEvent=1";
		  			winFactEvent.focus();
					$scope.consultaEvento();
					$scope.dialFactEvento.hide();
				}
				else
					winFactEvent.close();
			});
		}
	};

	$scope.montoTotalEvento = 0;
	$scope.totalEvento = function ( lstMenus ) {
		var total = 0;
		if ( Array.isArray( lstMenus ) )
		{
			for (var i = 0; i < lstMenus.length; i++) {
				var menu = lstMenus[ i ];
				total += ( parseInt( menu.cantidad ) * parseFloat( menu.precioUnitario ) );
			}
		}
		else if ( $scope.lstMenuEvento )
		{
			$scope.montoTotalEvento = 0;
			for (var i = 0; i < $scope.lstMenuEvento.length; i++) {
				var menu = $scope.lstMenuEvento[ i ];
				$scope.montoTotalEvento += ( parseInt( menu.cantidad ) * parseFloat( menu.precioUnitario ) );
			}
		}


		return total;
	};

	$scope.montoCambioEvento = function () {
		var cambio 	    = 0,
			totalMonto  = ( $scope.evento.montoEfectivo + $scope.evento.montoTarjeta ),
			montoEvento = ( $scope.evento.totalEvento - $scope.evento.totalAnticipo );

		if ( totalMonto > montoEvento )
			cambio = ( totalMonto - montoEvento );

		$scope.evento.cambio = cambio;
	};

	// GUARDA MOVIMIENTO
	$scope.guardarMovimiento = function () {
		console.log( $scope.movimiento, $scope.accionMenu );

		if ( !( $scope.movimiento.idFormaPago > 0 ) )
			alertify.notify( "Debe seleccionar una forma de pago", "danger", 3 );

		else if ( !( $scope.movimiento.monto > 0 ) )
			alertify.notify( "El monto debe ser mayor a cero", "danger", 3 );

		else if ( !( $scope.movimiento.motivo.length > 0 ) )
			alertify.notify( "Debe especificar la descripción", "danger", 3 );

		else
		{
			var datos = {
				opcion     : 'guardarMovimiento',
				movimiento : {
					accion       : $scope.accionMenu,
					idEvento     : $scope.evento.idEvento,
					idFormaPago  : $scope.movimiento.idFormaPago,
					monto        : $scope.movimiento.monto,
					motivo       : $scope.movimiento.motivo,
					idMovimiento : $scope.movimiento.idMovimiento,
				}
			};

			$http.post('consultas.php', datos )
			.success(function (data) {
				console.log( data );
				alertify.notify( data.mensaje, data.respuesta, 3 );

				if ( data.respuesta == 'success' ) {
					$scope.evento.lstMovimiento = data.lstMovimiento;
					$scope.accionMenu           = "";
					$scope.movimiento.monto     = "";
					$scope.movimiento.motivo    = "";
				}
			});
		}
	};

	$scope.newClient = function () {
		$scope.dialEvento.hide();
		$timeout(function () {
			window.location.href = "#/cliente";
		});
	};

	$scope.$watch('busquedaCliente', function (_new) {
		$scope.consultarCliente();
	});

	// SI CAMBIA EL TIPO SE REALIZA LA CONSULTA DE MENU
	$scope.$watch('tipo', function (_new) {
		if ( _new.length ) {
			$scope.consultarMenu();

			$timeout(function () {
				if ( ( _new == 'otroMenu' || _new == 'otroServicio' ) && document.getElementById('inputMenu') != undefined )
					document.getElementById('inputMenu').focus();

				else if ( document.getElementById('selectMenu') != undefined )
					document.getElementById('selectMenu').focus();
			});
		}
	});

	// SI CAMBIA EL ESTADO DEL EVENTO
	$scope.$watch('idEstadoEvento', function (_new) {
		if ( _new > 0  )
		{
			$scope.consultaEvento();
			$scope.filtroFecha = '';
		}
	});

	$scope.$watch('filtroFecha', function (_new) {
		var fecha = moment( $scope.filtroFecha ).format( "YYYY[-]MM[-]DD" );
		if ( fecha.length == 10 )
		{
			$scope.idEstadoEvento = '';
			$scope.consultaEvento( fecha );
		}
		else
			$scope.idEstadoEvento = 1;
			
		console.log( fecha );
	});

	// CONSULTA EVENTOS
	$scope.consultaEvento = function ( fecha ) {
		if ( $scope.$parent.loading ) return false;

		$scope.$parent.loading = true;
		$scope.lstEvento       = [];
		$http.post('consultas.php', { 
			opcion         : 'consultaEvento',
			idEstadoEvento : $scope.idEstadoEvento,
			fecha 		   : ( fecha || '' )
		})
		.success(function (data) {
			$scope.$parent.loading = false;

			if ( Array.isArray( data ) )
				$scope.lstEvento = data;
		});
	};
	
	// PRESS KEY
	$scope.$on('keyPress', function( event, key, altDerecho, evento ) {
		// SI EL DIALOGO DE EVENTO SE ESTA MOSTRANDO
		if ( $scope.modalOpen( 'dl_evento' ) ) 
		{
			// PREVENIR: SPACE, UP, DOWN
			if ( ( key == 40 || key == 38 ) && $scope.lstResultado.length )
				evento.preventDefault();

			if ( key == 40 && $scope.lstResultado.length ) // DOWN
			{
				var ix = $scope.indexActual();

				if ( ix == -1 )
					$scope.lstResultado[ 0 ].active = true;

				else if ( ( ix + 1 ) < $scope.lstResultado.length ) {
					$scope.lstResultado[ ix ].active = false;
					$scope.lstResultado[ ix + 1 ].active = true;
				}
			}
			else if ( key == 38 && $scope.lstResultado.length ) // UP
			{
				var ix = $scope.indexActual();

				if ( ix == -1 )
					$scope.lstResultado[ $scope.lstResultado.length - 1 ].active = true;

				else if ( ix > 0  ) {
					$scope.lstResultado[ ix ].active = false;
					$scope.lstResultado[ ix - 1 ].active = true;
				}
			}
			else if ( key == 13 ) // UP
			{
				var ix = $scope.indexActual();

				if ( ix >= 0 )
					$scope.seleccionarCliente( $scope.lstResultado[ ix ] );
			}
		}
	});

	// INDEX ACTUAL
	$scope.indexActual = function () {
		var index = -1;

		for (var iac = 0; iac < $scope.lstResultado.length; iac++)
		{
			if ( $scope.lstResultado[ iac ].active && index == -1 )
				index = iac;
			else
				$scope.lstResultado[ iac ].active = false;
		}

		return index;
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


	/* ************************** CONSULTA SI EXISTE MODAL ABIERTO ********************** */
	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
});