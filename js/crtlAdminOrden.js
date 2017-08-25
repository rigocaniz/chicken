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

			$scope.lstMenus[ ixMenu ].numMenus += parseInt( $scope.lstMenusMaster[ ip ].cantidad );
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
		menu   : '',
		imagen : null,
		count  : {
			total 		: 0,
			llevar      : 0,
			restaurante : 0,
			domicilio   : 0
		},
	};

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

	$scope.cambiarVista = function ( tipoVista ) {
		if ( $scope.permitirAccion() )
			$scope.tipoVista = tipoVista;
	};

	// AUX -> ATAJO INICIO
	$scope._keyInicio = function ( key, altDerecho ) {
		console.log( key, altDerecho );

		// ATAJO PRIMEROS 9 ELEMENTOS
		if ( altDerecho && key >= 49 && key <= 57 && $scope.permitirAccion() ) { // {1-9}
			var elemento = key - 49;

			if ( $scope.lstMenus.length && elemento < $scope.lstMenus.length )
				$scope.ixMenuActual = elemento;
		}
		else if ( key == 40 && $scope.permitirAccion( true ) ) { // {DOWN}
			if ( $scope.lstMenus.length && $scope.ixMenuActual == -1 )
				$scope.ixMenuActual = 0;

			else if ( ( $scope.ixMenuActual + 1 ) < $scope.lstMenus.length )
				$scope.ixMenuActual++;
		}
		else if ( key == 38 && $scope.permitirAccion( true ) ) { // {UP}
			if ( $scope.ixMenuActual != -1 && $scope.ixMenuActual > 0 )
				$scope.ixMenuActual--;
		}

		// PARA SELECCION DE ORDENES
		else if ( key == 84 ) // {T}  => SELECCIONAR TODOS
			$scope.selItemMenu( true );
		
		else if ( key == 78 ) // {N}  => DESELECCIONAR TODOS
			$scope.selItemMenu( false );

		else if ( key == 187 ) // {+}
			$scope.selItemKey( true );

		else if ( key == 189 ) // {-}
			$scope.selItemKey( false );
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho ) {

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

	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
});