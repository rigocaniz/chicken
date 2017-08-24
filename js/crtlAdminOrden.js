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
	$scope.tipoVista = 'menu';



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


	$scope.seleccionMenu = {
		si     : false,
		count  : 0,
		menu   : '',
		imagen : null
	};

	// SELECCIONA O DESELECCIONA TODOS
	$scope.selItemMenu = function ( seleccionado, ixDetalle ) {
		if ( !( $scope.ixMenuActual >= 0 ) )
			return false;

		console.log( seleccionado, ixDetalle );

		$scope.seleccionMenu.menu   = $scope.lstMenus[ $scope.ixMenuActual ].menu;
		$scope.seleccionMenu.imagen = $scope.lstMenus[ $scope.ixMenuActual ].imagen;
		$scope.seleccionMenu.si     = false;

		// SI ES INDIVIDUAL
		if ( ixDetalle != undefined ) {
			if ( seleccionado )
				$scope.seleccionMenu.count++;

			else
				$scope.seleccionMenu.count--;

			$scope.lstMenus[ $scope.ixMenuActual ].detalle[ ixDetalle ].selected = seleccionado;

			if ( $scope.seleccionMenu.count )
				$scope.seleccionMenu.si = true;
		}
		// SI ES PARA TODOS
		else if ( $scope.lstMenus[ $scope.ixMenuActual ] ) {
			$scope.seleccionMenu.count  = 0;

			for (var i = 0; i < $scope.lstMenus[ $scope.ixMenuActual ].detalle.length; i++) {
				$scope.seleccionMenu.count++;		
				$scope.lstMenus[ $scope.ixMenuActual ].detalle[ i ].selected = seleccionado;
			}

			if ( seleccionado )
				$scope.seleccionMenu.si = true;
		}
	};


	// AUX -> ATAJO INICIO
	$scope._keyInicio = function ( key ) {
		console.log( key );

		// ATAJO PRIMEROS 9 ELEMENTOS
		if ( key >= 49 && key <= 57 ) { // {1-9}
			var elemento = key - 49;

			if ( $scope.lstMenus.length && elemento < $scope.lstMenus.length )
				$scope.ixMenuActual = elemento;
		}
		else if ( key == 40 ) { // {DOWN}
			if ( $scope.lstMenus.length && $scope.ixMenuActual == -1 )
				$scope.ixMenuActual = 0;

			else if ( ( $scope.ixMenuActual + 1 ) < $scope.lstMenus.length )
				$scope.ixMenuActual++;
		}
		else if ( key == 38 ) { // {UP}
			if ( $scope.ixMenuActual != -1 && $scope.ixMenuActual > 0 )
				$scope.ixMenuActual--;
		}

		else if ( key == 84 ) // {T}  => SELECCIONAR TODOS
			$scope.selItemMenu( true );
		
		else if ( key == 78 ) // {N}  => DESELECCIONAR TODOS
			$scope.selItemMenu( false );
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho ) {

		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// SI NO EXISTE NINGUN DIALOGO ABIERTO
		if ( !$scope.modalOpen() ) {
			// MODO PANTALLA
			if ( altDerecho && key == 77 ) $scope.tipoVista = 'menu';
			else if ( altDerecho && key == 68 ) $scope.tipoVista = 'dividido';
			else if ( altDerecho && key == 84 ) $scope.tipoVista = 'ticket';

			else{
				$scope._keyInicio( key );
			}
		}

		// CUANDO ESTE ABIERTO ALGUN CUADRO DE DIALOGO
		else{
			// CUANDO EL DIALOGO DE NUEVA ORDEN ESTE ABIERTA
			if ( $scope.modalOpen( 'dial_orden_cliente' ) ) {
				
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