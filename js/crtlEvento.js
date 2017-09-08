app.controller('crtlEvento', function( $scope, $http, $timeout, $modal, $sce ){
	$scope.idTab           = 1;
	$scope.accionEvento    = "insert";
	$scope.tipo            = 'menu';
	$scope.lstMenu         = [];
	$scope.lstResultado    = [];
	$scope.accionMenu      = '';
	$scope.busquedaCliente = '';
	$scope.evento          = {};
	$scope.menu         = {
		cantidad       : 0,
		precioUnitario : 0,
		otroMenu 	   : '',
		idMenu 		   : null,
		comentario     : ''
	};

	($scope.setEvento = function () {
		$scope.evento = {
			idEvento       : null,
			evento         : '',
			idCliente      : '',
			nombreCliente  : '',
			fechaEvento    : null,
			horaInicio     : null,
			horaFinal      : null,
			anticipo       : 0,
			numeroPersonas : 0,
			observacion    : ''
		};
	})();

	// DIALOGOS
	$scope.dialOrden = $modal({scope: $scope,template:'dl.evento.html', show: false, backdrop:false, keyboard: false });

	// CONSULTAR CLIENTES
	$scope.consultarCliente = function () {
		if ( $scope.busquedaCliente.length > 3 )
		{
			$scope.lstResultado = [];
			$http.post('consultas.php', { opcion : 'consultarCliente', valor: $scope.busquedaCliente } )
			.success(function (data) {
				if ( Array.isArray( data ) && data.length )
				{
					for (var ic = 0; ic < data.length; ic++)
					{
						$scope.lstResultado.push({
							idCliente : data[ ic ].idCliente,
							cliente   : $sce.trustAsHtml( '<b>' + data[ ic ].nombre + '</b> (' + data[ ic ].nit + ') » ' + data[ ic ].direccion ),
							active    : false
						});
					}

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

		else
		{
			$scope.evento.fechaEventoTxt = moment( $scope.evento.fechaEvento ).format( "YYYY[-]MM[-]DD" );

			$http.post('consultas.php', { 
				opcion : 'guardarEvento',
				accion : $scope.accionEvento,
				evento : $scope.evento
			})
			.success(function (data) {
				alertify.notify( data.mensaje, data.respuesta, 3 );

				if ( data.respuesta == 'success' && $scope.accionEvento == 'insert' ) {
					$scope.evento.idEvento = data.idEvento;
					$scope.idTab           = 2;
				}
			});
		}
	};

	// CONSULTA MENUS O COMBOS
	$scope.consultarMenu = function () {
		var datos            = null;
		$scope.lstMenu       = [];
		$scope.menu.otroMenu = "";
		$scope.menu.idMenu   = '';
		
		if ( $scope.tipo == 'menu' ) 
			datos = { opcion : 'lstMenu', idTipoMenu : 0, idEstadoMenu : 1 };

		if ( $scope.tipo == 'combo' ) 
			datos = { opcion : 'lstCombo', idEstadoMenu : 1 };
	
		// SI SE CONSULTA INFORMACION
		if ( datos != null )
		{
			$http.post('consultas.php', datos )
			.success(function (data) {
				console.log( data );

				if ( $scope.tipo == 'menu' ) 
					$scope.lstMenu = $scope.arrayLst( data, 'idMenu', 'menu' );

				else if ( $scope.tipo == 'combo' )
					$scope.lstMenu = $scope.arrayLst( data, 'idCombo', 'combo' );

				$timeout(function () {

					// SELECCIONA LA PRIMERA OPCION
					if ( $scope.lstMenu.length )
						$scope.menu.idMenu = $scope.lstMenu[ 0 ].idMenu;
				});
			});
		}
	};

	// GUARDAR MENU
	$scope.guardarMenu = function () {
		if ( !( $scope.menu.cantidad > 0 ) )
			alertify.notify( "La cantidad debe ser mayor a cero", "danger", 3 );

		else if ( !( $scope.menu.precioUnitario > 0 ) )
			alertify.notify( "El precio debe ser mayor a cero", "danger", 3 );

		else if ( $scope.tipo == 'otroMenu' && !( $scope.menu.otroMenu.length > 3 ) )
			alertify.notify( "Error, menú no se encuentra especificado", "danger", 3 );

		else
		{
			var datos = {
				idMenu         : $scope.menu.idMenu,
				cantidad       : $scope.menu.cantidad,
				precioUnitario : $scope.menu.precioUnitario,
				comentario     : $scope.menu.comentario,
				otroMenu       : $scope.menu.otroMenu,
				tipo           : $scope.tipo
			};
			console.log( datos );
		}
	};

	$scope.$watch('busquedaCliente', function (_new) {
		$scope.consultarCliente();
	});

	// SI CAMBIA EL TIPO SE REALIZA LA CONSULTA DE MENU
	$scope.$watch('tipo', function (_new) {
		if ( _new.length ) {
			$scope.consultarMenu();

			$timeout(function () {
				if ( _new == 'otroMenu' )
					document.getElementById('inputMenu').focus();
				else
					document.getElementById('selectMenu').focus();
			});
		}
	});
	


	$timeout(function () {
		$scope.dialOrden.show();
	});

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