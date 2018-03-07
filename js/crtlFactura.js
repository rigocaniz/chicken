app.controller('facturaCtrl', function( $scope, $http, $modal, $timeout, $routeParams ){
	
	$scope.accionCliente     = 'ninguna';
	$scope.accion            = 'insert';
	$scope.buscarTicket      = null;
	$scope.idTab = '';

	$scope.facturacion = {
		datosCliente : {
			nit          : '',
			nombre       : '',
			direccion    : '',
		},
		agrupado: true,
		idEstadoFactura : 1,
		idOrdenCliente  : null,
		numeroTicket    : null,
		noFactura       : null,
		total           : 0,
		lstFormasPago   : [],
		lstOrden        : [],
	};

	$scope.$watch('buscarTicket', function( _new, _old ){
		if( _old != _new ) {
			$scope.numeroTicket = null;
			$scope.facturacion.lstOrden = [];
		}
	});

	($scope.catFormasPago = function(){
		$http.post('consultas.php',{
		    opcion : "catFormasPago"
		}).success(function(data){
		    $scope.lstFormasPago = data;
		    $scope.facturacion.lstFormasPago = data;
		})
	})();

	$scope.test = function () {
		console.log("TEST");
	};

	$scope.lstFacturasDia = [];
	$scope.cargarlstFacturasDia = function(){
		$http.post('consultas.php',{
		    opcion : "lstFacturas"
		}).success(function(data){
			console.log( data );
		    $scope.lstFacturasDia = data || [];
		    if( $scope.lstFacturasDia.length )
		    	$scope.dialReimpresion.show();
		    else
		    	alertify.notify('No hay facturas registradas el día de hoy', 'info', 4);
		})
	};


	$scope.dialAccionCliente = $modal({scope: $scope,template:'dial.accionCliente.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrdenBusqueda = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: true });
	$scope.dialPrintFactura  = $modal({scope: $scope,template:'dial.printFactura.html', show: false, backdrop:false, keyboard: true });
	$scope.dialReimpresion   = $modal({scope: $scope,template:'dial.reimpresion.html', show: false, backdrop:false, keyboard: true });
	$scope.dialCaja          = $modal({scope: $scope,template:'dial.caja.html', show: false, backdrop:false, keyboard: true });
	

	$scope.seleccionarDeBusqueda = function ( orden ) {
		console.log( orden );
		$scope.miIndex = -1;
		$scope.dialOrdenBusqueda.hide();
		$scope.facturacion.numeroTicket   = angular.copy( parseInt( orden.numeroTicket ) );
		$scope.facturacion.idOrdenCliente = angular.copy( parseInt( orden.idOrdenCliente ) );

		$scope.consultaDetalleOrden( orden );

		$timeout(function () {
			//document.getElementById('searchPrincipal').focus();
			$scope.modalInfo( orden, true );
		});
	};

	$scope.impresionFactura = {
		type      : 'd',
		idFactura : null
	};

	$scope.consultaFacturaCliente = function() {
		$scope.facturacion.total = $scope.retornarTotalOrden();
		
		var efectivo = ( $scope.facturacion.lstFormasPago[ 0 ].monto || 0 ),
			tarjeta  = ( $scope.facturacion.lstFormasPago[ 1 ].monto || 0 );

		var vuelto = ( ( efectivo + tarjeta ) - $scope.facturacion.total );
		
		if( !($scope.facturacion.datosCliente.idCliente && $scope.facturacion.datosCliente.idCliente > 0) )
			alertify.notify('Seleccione un cliente', 'warning', 4);
		
		else if( !($scope.facturacion.idOrdenCliente && $scope.facturacion.idOrdenCliente > 0) )
			alertify.notify('Número de orden de Cliente no válido', 'warning', 4);
		
		else if( !($scope.facturacion.lstOrden && $scope.facturacion.lstOrden.length > 0) )
			alertify.notify('La lista de ordenes está vacia', 'warning', 4);
		
		else if( !($scope.facturacion.total && $scope.facturacion.total > 0) )
			alertify.notify('El total del cobro debe ser mayor a 0', 'warning', 4);

		else if( !( vuelto >= 0 ) )
			alertify.notify('Verifique que la forma de pago este correcta', 'warning', 4);

		else {

			var factura = angular.copy( $scope.facturacion );

			// MONTO REAL EN EFECTIVO
			factura.lstFormasPago[ 0 ].monto -= vuelto;

			$scope.$parent.showLoading( 'Guardando...' );
			
			$http.post('consultas.php',{
			    opcion : "consultaFacturaCliente",
			    accion : $scope.accion,
			    data   : factura
			}).success(function(data){
			    console.log(data);		    
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				if( data.respuesta == 'success' ) {
					$scope.resetValores( 'facturacion' );
					$scope.impresionFactura.idFactura = data.data;
					$scope.dialPrintFactura.show();
					
					$timeout(function () {
						$("#btn_print_factura").focus();
					});
				}
				$scope.$parent.hideLoading();
			});
			
		}

	};
	
	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% DIALOGO PARA MAS INFORMACION DE ORDEN %%%%%%%%%%%%%%%%%%%%%% */
	$scope.consultarDetalleOrden = function(){
		$scope.buscarOrdenTicket( $scope.facturacion.idOrdenCliente );
	};

	$scope.infoOrden = {};
	$scope.deBusqueda = false;
	$scope.modalInfo = function ( orden, deBusqueda ) {
		if ( $scope.$parent.loading )
			return false;

		// SI NO ES DE BUSQUEDA
		if ( deBusqueda == undefined )
			$scope.deBusqueda = false;

		// SI ES DE BUSQUEDA
		else {
			$scope.idEstadoOrden = 0;
			$scope.deBusqueda    = true;
		}

		console.log( 'orden>>> ', orden.idOrdenCliente );

		$scope.$parent.loading = true;
		$scope.infoOrden = orden;
		$http.post('consultas.php', { 
			opcion         : 'lstDetalleOrden',
			idOrdenCliente : orden.idOrdenCliente,
			todo           : $scope.todoDetalle,
			agrupado       : $scope.facturacion.agrupado
		})
		.success(function (data) {
			console.log( data );
			$scope.$parent.loading    = false;
			if ( data.length ) {
				$scope.facturacion.lstOrden = data;
			}
			else{
				alertify.set('notifier','position', 'top-right');
                alertify.notify( 'No se encontraron ordenes con el No. de Ticket', 'info', 7 );
			}

		});
	};


	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho ) {
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// SI EL DIALOGO DE AGREGAR CLIENTE ESTA ABIERTO
		if ( $scope.modalOpen( 'dial_accionCliente' ) )
		{
			if ( key == 117 )
				$scope.consultaCliente();
		}

		// SI ES PANTALLA PRINCIPAL Y PRESIONAR LA TECLA {F6}
		else if ( !$scope.modalOpen() && key == 117 )
			$scope.facturarOrden();

		// CTRL + ESPACION (NUEVA ORDEN)
		if ( altDerecho && key == 32 && $scope.lstFacturas.length )
			$scope.agregarFactura({});

		
		// CTRL + DERECHA (SIGUIENRE ORDEN)
		if ( altDerecho && key == 39 && $scope.lstFacturas.length )
		{
			var indexFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
			if ( indexFactura !== -1 && $scope.lstFacturas[ indexFactura + 1 ] !== undefined )
				$scope.idTab = $scope.lstFacturas[ indexFactura + 1 ].idTab;
		}

		// ESC (DESELECCIONA DETALLE)
		if ( key == 27 && $scope.lstFacturas.length )
		{
			var indexFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
			if ( indexFactura >= 0 )
			{
				$scope.lstFacturas[ indexFactura ].ixDetalle = -1;
				$scope.lstFacturas[ indexFactura ].ixDesc    = -1;
			}
		}

		// CTRL + P (ENFOCA DETALLE DE PAGO)
		if ( altDerecho && key == 80 && $scope.lstFacturas.length )
			$scope.focusHtml( 'efectivo_' + $scope.idTab );

		// CTRL + C (ENFOCA BUSQUEDA CLIENTE)
		if ( altDerecho && key == 67 && $scope.lstFacturas.length )
			$scope.focusHtml( 'searchPrincipal_' + $scope.idTab );

		
		// CTRL + SUPR (ELIMINAR ORDEN)
		if ( altDerecho && key == 46 && $scope.lstFacturas.length )
			$scope.quitarFactura( $scope.idTab );


		// CTRL + IZQUIERDA (ORDEN ANTERIOR)
		if ( altDerecho && key == 37 && $scope.lstFacturas.length )
		{
			var indexFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
			if ( indexFactura > 0 )
				$scope.idTab = $scope.lstFacturas[ indexFactura - 1 ].idTab;
		}


		// TECLA C
		if ( altDerecho && key == 67 && $scope.accionCliente == '' )
			$scope.buscarCliente( 'CF', 'cf' )

		// TECLA A
		else if( altDerecho && key == 69 ){
			if( !$scope.modalOpen() ) {
				if( $scope.facturacion.datosCliente.idCliente == 1 )
					alertify.notify('Acción no válida para el tipo de cliente', 'info', 4);
				else
					$scope.editarCliente( $scope.facturacion.datosCliente, 'mostrar' );
			}
			else
				alertify.notify('Acción no válida', 'info', 3);

			$timeout(function(){
				$('#nit').focus();
			},175);
			
		}
		// TECLA E  (EDITAR)
		else if( altDerecho && key == 65 ){
			$scope.accionCliente = 'agregar';
			if( !$scope.modalOpen() )
				$scope.dialAccionCliente.show();

			$timeout(function(){
				$('#nit').focus();
			},175);
		}
		else if( altDerecho && key == 70 ){
			if( !$scope.modalOpen() )
				$scope.consultaFacturaCliente();
			else
				alertify.notify('Acción no válida', 'info', 3);
		}
	});

    $scope.resetValores = function( accion ){
        $scope.accion = 'insert';
        if( accion == 'cliente' ) {
            $scope.cliente  = {
                'nit'           : null,
                'nombre'        : '',
                'cui'           : null,
                'correo'        : '',
                'telefono'      : null,
                'direccion'     : '',
                'idTipoCliente' : 1,
            };
        }
        else if( accion == 'lstClientes' )
            $scope.lstClientes = [];

        else if( 'facturacion' ){
			$scope.facturacion = {
				datosCliente : {
					nit          : '',
					nombre       : '',
					direccion    : '',
				},
				agrupado: true,
				idEstadoFactura : 1,
				idOrdenCliente  : null,
				numeroTicket    : null,
				noFactura       : null,
				total           : 0,
				lstFormasPago   : [],
				lstOrden        : [],
			};
        }
    };

    $scope.resetValores( 'cliente' );
    $scope.resetValores( 'facturacion' );

	$scope.lstDetalleTicket = [];
	$scope.$watch('accionCliente', function(){
		if( $scope.accionCliente == 'agregar' ){
			$scope.resetValores( 'cliente' );
			$scope.accion = 'insert';
			$timeout(function () {
				$("#nit").focus();
			});
		}
	});


	$scope.dividirOrden = function() {
		var agregados = 0, ixIndividual = -1;
		if( $scope.facturacion.lstOrdenRestantes.length ){

			for (var ix = 0; ix < $scope.facturacion.lstOrdenRestantes.length; ix++) {
				var ordenRestante = angular.copy( $scope.facturacion.lstOrdenRestantes[ ix ] );
				if( ordenRestante.cobrarTodo && ordenRestante.cantidad ){
					if( ixIndividual == -1 ){
						$scope.facturacion.lstOrdenesInd.push({'lstOrden' : []});
						ixIndividual = $scope.facturacion.lstOrdenesInd.length - 1;
					}

					agregados++
					$scope.facturacion.lstOrdenesInd[ ixIndividual ].lstOrden.push(ordenRestante);		// AGREGAR ORDEN

					$scope.facturacion.lstOrdenRestantes[ ix ].cantidadRestante -= ordenRestante.cantidad;
					$scope.facturacion.lstOrdenRestantes[ ix ].cantidad         = ( ordenRestante.cantidadRestante - ordenRestante.cantidad );

					if( $scope.facturacion.lstOrdenRestantes[ ix ].cantidadRestante == 0 )
						$scope.facturacion.lstOrdenRestantes.splice( ix, 1 );
				}

				if( !$scope.facturacion.lstOrdenRestantes.length )
					$scope.facturacion.ixSeleccionado = 0;
			}

			if( !agregados )
				alertify.notify( "No selecciono el detalle para la orden Individual", 'warning', 8 );

			else
				alertify.notify( "Orden Creada", 'success', 3 );
		}
		else
			alertify.notify( "No se encontrarón ordenes pendientes", 'info', 5 );
	};


	$scope.lstFacturas  = [];
	$scope.lstDetalleOrden = [];
	$scope.consultaDetalleOrden = function ( orden, deBusqueda ) {
		if ( $scope.$parent.loading )
			return false;

		// SI NO ES DE BUSQUEDA
		if ( deBusqueda == undefined )
			$scope.deBusqueda = false;

		// SI ES DE BUSQUEDA
		else {
			$scope.idEstadoOrden = 0;
			$scope.deBusqueda    = true;
		}

		$scope.ordenesCliente  = [];
		$scope.idTab = '';
		//$scope.lstDetalleOrden = [];

		$scope.$parent.loading = true;
		$scope.infoOrden = orden;
		$http.post('consultas.php', { opcion : 'detalleOrdenFactura', idOrdenCliente : orden.idOrdenCliente })
		.success(function (data) {
			$scope.$parent.loading = false;
			console.log( data );

			if ( Array.isArray( data ) )
			{
				$scope.agregarFactura({
					lstDetalle : data,
				});

				// SELECCIONA LA PRIMERA ORDEN
				if ( data.length )
					$scope.idTab = 1;
			}
		});
	};

	$scope.agregarFactura = function ( factura ) {
		factura.idTab     = 1;
		factura.tab       = "Orden Principal";
		factura.principal = true;

		if ( $scope.lstFacturas.length )
		{
			$scope.lstFacturas[ 0 ].numeroOrden++;
			factura.idTab     = $scope.lstFacturas[ 0 ].numeroOrden;
			factura.tab       = "Orden #" + factura.idTab;
			factura.principal = false;
		}
		else
			factura.numeroOrden = 1;

		$scope.lstFacturas.push({
			numeroOrden : factura.numeroOrden,
			idTab 		: factura.idTab,
			facturado   : false,
			idFactura   : null,
			tab         : factura.tab,
			principal   : factura.principal,
			lstDetalle  : ( factura.lstDetalle || [] ),
			cliente 	   : {
				idCliente     : ( factura.idCliente || '' ),
				nit           : ( factura.nit || '' ),
				nombre        : ( factura.nombre || '' ),
				direccion     : ( factura.direccion || '' ),
				idTipoCliente : ( factura.idTipoCliente || '' )
			},
			detallePago : {
				totalPago      : '',
				totalDescuento : '',
				total          : '',
				cambio         : '',
				efectivo       : '',
				tarjeta        : '',
				tipo           : 'd',
				descripcion    : ''
			}
		});

		return factura.idTab;
	};

	// SI SE ENFOCA UN DETALLE DE LA ORDEN
	$scope.focusDetalle = function( ixDetalle )
	{
		var indexFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
		// SI SE ENCUENTRA LA FACTURA
		if ( indexFactura >= 0 )
		{
			// ASIGNAR INDEX A FACTURA
			$scope.lstFacturas[ indexFactura ].ixDetalle = ixDetalle;

			// COPIA CANTIDAD A ASIGNAR
			var cantidad = angular.copy( $scope.lstFacturas[ indexFactura ].lstDetalle[ ixDetalle ].cantidad );
			$scope.lstFacturas[ indexFactura ].lstDetalle[ ixDetalle ].cantidadTrasladar = cantidad;

			// ENFOCA LA ORDEN ACTUAL
			$scope.focusHtml( 'fact_orden_' + ixDetalle );
		}
	};

	// REALIZA CALCULO DE FACTURA
	$scope.calculoFactura = function ( tipoCalculo ) {
		var ixFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
		var resultado = null;

		if ( ixFactura == -1 ) return false;
		
		switch( tipoCalculo ){
			// CALCULA EL TOTAL DE FACTURA
			case 'totalFactura':
				var totalDescuento = 0,
					total 		   = 0;

				for (var ixF = 0; ixF < $scope.lstFacturas[ ixFactura ].lstDetalle.length; ixF++)
				{
					var detalle = angular.copy( $scope.lstFacturas[ ixFactura ].lstDetalle[ ixF ] );

					// SI LA CANTIDAD ES MAYOR A CERO
					if ( detalle.cantidad > 0 )
					{
						if ( !( detalle.descuento > 0 ) )
							detalle.descuento = 0;

						total          += ( ( detalle.cantidad * detalle.precio ) - detalle.descuento );
						totalDescuento += detalle.descuento;
					}
				}

				$scope.lstFacturas[ ixFactura ].detallePago.total          = angular.copy( total );
				$scope.lstFacturas[ ixFactura ].detallePago.totalDescuento = angular.copy( totalDescuento );
			break;

			case 'totalPago':
				var detallePago = angular.copy( $scope.lstFacturas[ ixFactura ].detallePago );

				detallePago.tarjeta  = $scope.convert( detallePago.tarjeta, 'float', 0 );
				detallePago.efectivo = $scope.convert( detallePago.efectivo, 'float', 0 );
				detallePago.total    = $scope.convert( detallePago.total, 'float', 0 );

				var totalPago = detallePago.tarjeta + detallePago.efectivo;
				var cambio = 0;
				
				if ( totalPago > detallePago.total )
					cambio = ( totalPago - detallePago.total );

				$scope.lstFacturas[ ixFactura ].detallePago.cambio    = cambio;
				$scope.lstFacturas[ ixFactura ].detallePago.totalPago = totalPago;
			break;
		}

		return resultado;
	};

	$scope.quitarFactura = function ( idTab )
	{
		var ixFactura  = $scope.indexArray( 'lstFacturas', 'idTab', idTab );

		if ( $scope.lstFacturas[ ixFactura ].principal || $scope.lstFacturas[ ixFactura ].facturado )
			return false;

		for (var ix = 0; ix < $scope.lstFacturas[ ixFactura ].lstDetalle.length; ix++)
		{
			var cantidad = angular.copy( $scope.lstFacturas[ ixFactura ].lstDetalle[ ix ].cantidad );
			$scope.lstFacturas[ ixFactura ].lstDetalle[ ix ].cantidadTrasladar = cantidad;

			$scope.asignarDetalle( idTab, 1, $scope.lstFacturas[ ixFactura ].lstDetalle[ ix ], ix );

			// SI EXISTEN ELEMENTOS
			if ( $scope.lstFacturas[ ixFactura ].lstDetalle.length )
				ix--;
		}

		// SI ESTABA SELECCIONADO ELIMINA DE LAS OTRAS ORDENES
		for (var ix = 0; ix < $scope.lstFacturas.length; ix++)
			if (  $scope.lstFacturas[ ix ].idTabDestino == idTab )
				$scope.lstFacturas[ ix ].idTabDestino = '';

		$scope.lstFacturas.splice( ixFactura, 1 );
		$scope.idTab = '1';

		// ACTUALIZA TOTAL DE FACTURA
		$scope.calculoFactura( 'totalFactura' );
	};

	$scope.asignarDetalle = function ( idTabActual, idTabDestino, orden, ixDetalle ) {
		console.log( idTabActual, idTabDestino, orden, ixDetalle );
		// SI LA FACTURA NO ESTA DEFINIDA LA CREA ORDEN ACTUAL

		var ixFacturaA = $scope.indexArray( 'lstFacturas', 'idTab', idTabActual );
		var factActual = angular.copy( $scope.lstFacturas[ ixFacturaA ] );

		if( factActual.facturado ){
			console.log( factActual );
			alertify.notify( 'El detalle de la orden ya está facturado en la <b>ORDEN ACTUAL</b>', 'warning', 6);
			return;
		}
		// ORDEN DESTINO
		else if( !(idTabDestino == undefined) && ( idTabDestino > 0 ) ){
			var ixFacturaD = $scope.indexArray( 'lstFacturas', 'idTab', idTabDestino );
			var factDestino = angular.copy( $scope.lstFacturas[ ixFacturaD ] );

			if( factDestino.facturado ){
				alertify.notify( 'El # de ORDEN ya se encuentra facturado, no es posible asignar el detalle de la orden', 'warning', 8);
				return;
			}
		}

		if ( !( idTabDestino > 0 ) )
			idTabDestino = $scope.agregarFactura({});

		orden = angular.copy( orden );
		
		var ixFacturaActual  = $scope.indexArray( 'lstFacturas', 'idTab', idTabActual );
		var ixFacturaDestino = $scope.indexArray( 'lstFacturas', 'idTab', idTabDestino );

		$scope.lstFacturas[ ixFacturaActual ].idTabDestino = idTabDestino.toString();

		if ( orden.cantidadTrasladar > 0 && ixFacturaDestino >= 0 )
		{
			var ixDetalleDestino  = -1,
				cantidadTrasladar = 0;

			// OBTIENE EL INDEX DEL ( MENU-TIPO_SERVICIO )
			for (var ixD = 0; ixD < $scope.lstFacturas[ ixFacturaDestino ].lstDetalle.length; ixD++) {
				var actual = $scope.lstFacturas[ ixFacturaDestino ].lstDetalle[ ixD ];
				if ( actual.idTipoServicio == orden.idTipoServicio && actual.idMenu == orden.idMenu && actual.idCombo == orden.idCombo )
				{
					ixDetalleDestino = ixD;
					break;
				}
			}

			cantidadTrasladar = angular.copy( orden.cantidadTrasladar );

			// SI SE TRASLADA TODO
			if ( cantidadTrasladar == orden.pendiente )
			{
				orden.cantidadTrasladar = "";

				// SI NO EXISTE DETALLE EN ORDEN DESTINO
				if ( ixDetalleDestino == -1 )
					$scope.lstFacturas[ ixFacturaDestino ].lstDetalle.push( orden );

				$scope.lstFacturas[ ixFacturaActual ].lstDetalle.splice( ixDetalle, 1 );
			}
			// SI ES MENOR A ORDENES PENDIENTES
			else
			{
				// SI NO EXISTE DETALLE EN ORDEN DESTINO
				if ( ixDetalleDestino == -1 )
				{
					orden.cantidad          = cantidadTrasladar;
					orden.pendiente         = cantidadTrasladar;
					orden.cantidadTrasladar = "";
					$scope.lstFacturas[ ixFacturaDestino ].lstDetalle.push( orden );
				}
				
				// ACTUALIZA DETALLE ACTUAL
				$scope.lstFacturas[ ixFacturaActual ].lstDetalle[ ixDetalle ].cantidadTrasladar = ''
				$scope.lstFacturas[ ixFacturaActual ].lstDetalle[ ixDetalle ].cantidad          -= cantidadTrasladar;
				$scope.lstFacturas[ ixFacturaActual ].lstDetalle[ ixDetalle ].pendiente         -= cantidadTrasladar;
			}

			// SI EL INDEX DE DETALLE DESTINO EXISTE, ACTUALIZA
			if ( ixDetalleDestino >= 0 )
			{
				$scope.lstFacturas[ ixFacturaDestino ].lstDetalle[ ixDetalleDestino ].cantidadTrasladar = '';
				$scope.lstFacturas[ ixFacturaDestino ].lstDetalle[ ixDetalleDestino ].cantidad          += cantidadTrasladar;
				$scope.lstFacturas[ ixFacturaDestino ].lstDetalle[ ixDetalleDestino ].pendiente         += cantidadTrasladar;
			}
		}

		// REALIZA FOCUS A ELEMENTO
		$scope.focusHtml( 'fact_orden_' + ixDetalle );

		// ACTUALIZA TOTAL DE FACTURA
		$scope.calculoFactura( 'totalFactura' );
	};


	// FACTURAR ORDEN ACTUAL
	$scope.facturarOrden = function () {
		var ixFactura = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );

		if ( ixFactura == -1 )
			return false;

		var msgError = '',
			factura = angular.copy( $scope.lstFacturas[ ixFactura ] );

		// VALIDA CLIENTE
		if ( factura.facturado )
			msgError = "Orden ya facturada";

		// VALIDA CLIENTE
		else if ( !( factura.cliente.idCliente && factura.cliente.idCliente > 0 ) )
			msgError = "Cliente no definido para esta factura";

		// VALIDA FORMA DE PAGO
		else if ( factura.detallePago.totalPago < factura.detallePago.total )
			msgError = "El Pago TOTAL No puede ser menor a: Q. " + factura.detallePago.total;

		// MONTO DE TARJETA
		else if ( factura.detallePago.tarjeta > factura.detallePago.total )
			msgError = "El monto de pago con TARJETA no debe ser mayor al TOTAL";

		// SI EL TOTAL ES CERO
		else if ( !( factura.detallePago.total > 0 ) )
			msgError = "El monto total de la orden debe ser mayor a cero";

		if ( msgError === '' ) {

			for (var ix = 0; ix < factura.lstDetalle.length; ix++) {
				factura.lstDetalle[ ix ].descuento = $scope.convert( factura.lstDetalle[ ix ].descuento, 'float', 0 );

				if ( factura.lstDetalle[ ix ].conDescuento && factura.lstDetalle[ ix ].descuento == 0 )
					msgError = "Revise MONTO de Descuento de Detalle Orden: <b>#" + ( ix + 1 ) + "</b>";

				else if ( factura.lstDetalle[ ix ].conDescuento && !( factura.lstDetalle[ ix ].justificacion.length > 5 ) )
					msgError = "Revise JUSTIFICACION de Descuento de Detalle Orden: <b>#" + ( ix + 1 ) + "</b>";
				
				if ( msgError !== '' )
					break;
			}
		}

		
		// SI OCURRIO ALGUN ERROR
		if ( msgError != null && ( msgError.length ) )
		{
			alertify.notify( msgError, 'warning', 5);
			return false;
		}

		$scope.facturacion.total = $scope.retornarTotalOrden();		
		
		// MONTO REAL EN EFECTIVO
		$scope.$parent.showLoading( 'Guardando...' );
		
		$http.post('consultas.php',{
		    opcion : "consultaFacturaCliente",
		    accion : $scope.accion,
		    data   : factura
		}).success(function(data){
		    console.log(data);		    
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );
			if( data.respuesta == 'success' )
			{
				$scope.resetValores( 'facturacion' );
				$scope.lstFacturas[ ixFactura ].idFactura = data.data;
				$scope.impresionFactura.idFactura = data.data;
				//$scope.dialPrintFactura.show();
				$timeout(function () {
					$("#btn_print_factura").focus();
				});

				$scope.lstFacturas[ ixFactura ].facturado = true;
			}
			$scope.$parent.hideLoading();
		});
	};

	// SI CAMBIA EL ID
	$scope.$watch('idTab', function (_new) {
		if ( _new > 0 )
		{
			$timeout(function () {
				document.getElementById('searchPrincipal_'+_new) && document.getElementById('searchPrincipal_'+_new).focus();
				$scope.calculoFactura( 'totalFactura' );
			});
		}
	});


	// efectivo
   	$scope.lstTicketBusqueda = [];
	$scope.buscarOrdenTicket = function ( idOrdenCliente ) {

		if ( $scope.$parent.loading )
			return false;

		if ( $scope.buscarTicket > 0 || ( idOrdenCliente && idOrdenCliente > 0 ) ) {
			$scope.lstTicketBusqueda = [];
			$scope.$parent.loading = true; // cargando...
			
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { 
				opcion         : 'busquedaTicket',
				ticket         : $scope.buscarTicket,
				idOrdenCliente : idOrdenCliente
			})
			.success(function (data) {
				$scope.$parent.loading = false;

				if ( Array.isArray( data ) && data.length ) {
					if( data.length == 1 ){
						$scope.lstTicketBusqueda = data;
						$scope.seleccionarDeBusqueda( $scope.lstTicketBusqueda[ 0 ] );
						$scope.consultaDetalleOrden(  $scope.lstTicketBusqueda[ 0 ] );
					}
					else if( data.length > 1 ){
						$scope.lstTicketBusqueda = data;
						$scope.dialOrdenBusqueda.show();
					}
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( "No se encontró Ordenes con este <b>No. de TICKET</b>", 'info' );
				}
			});
		}
		else
			alertify.notify( "El No. de Ticket debe ser mayor a 0", 'info', 3 );
	};

	$scope.retornarTotalOrden = function() {
		var total = 0;
		if( $scope.facturacion.tipoGrupo == 'agrupado' && $scope.facturacion.lstOrden && $scope.facturacion.lstOrden.length )
			total = $scope.obtenerTotalModel( 'lstOrden' );
		
		else if( $scope.facturacion.tipoGrupo == 'individual' )
		{
			if( $scope.facturacion.ixSeleccionado == 'pendientes' )
				total = $scope.obtenerTotalModel( 'lstOrdenRestantes' );

			else
				total = $scope.obtenerTotalModel( 'lstOrdenesInd', $scope.facturacion.ixSeleccionado );
		}
		return total;
	};


	$scope.obtenerTotalModel = function( modelo, ixSeleccionado ){
		var total = 0
		for (var i = 0; i < $scope.facturacion[ modelo ].length; i++) {
			var orden = $scope.facturacion[ modelo ] [ i ];
			total += ( ( orden.cantidad * orden.precio ) - orden.descuento );
		}
		return total;
	};


	$scope.consultaCliente = function(){
        var cliente = $scope.cliente;

        if( !(cliente.nombre && cliente.nombre.length >= 3) )
            alertify.notify('El nombre debe tener más de 2 caracteres', 'warning', 4);
		
		else if( cliente.cui != undefined && cliente.cui.length >= 1 && !(cliente.cui.length == 13) )
			alertify.notify('El No. de CUI debe tener 13 dígitos', 'warning', 3);	
        
        else {
            $http.post('consultas.php',{
                opcion  : "consultaCliente",
                accion  : $scope.accion,
                cliente : $scope.cliente
            }).success(function(data){
                console.log(data);
                alertify.set('notifier','position', 'top-right');
                alertify.notify( data.mensaje,data.respuesta, data.tiempo );
                if ( data.respuesta == "success" )
                {
                	//$scope.facturacion.datosCliente = angular.copy( $scope.cliente );
                	if( $scope.accion == 'insert' )
                		$scope.cliente.idCliente = data.data;

                	var cliente = angular.copy( $scope.cliente );

                	//ASIGNA INFORMACION DE CLIENTE
	        		var ixFacturaActual  = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
					$scope.lstFacturas[ ixFacturaActual ].cliente.idCliente     = cliente.idCliente;
					$scope.lstFacturas[ ixFacturaActual ].cliente.nit           = cliente.nit;
					$scope.lstFacturas[ ixFacturaActual ].cliente.nombre        = cliente.nombre;
					$scope.lstFacturas[ ixFacturaActual ].cliente.direccion     = cliente.direccion;
					$scope.lstFacturas[ ixFacturaActual ].cliente.idTipoCliente = cliente.idTipoCliente;
	        		//ASIGNA INFORMACION DE CLIENTE

                    $scope.resetValores( 'cliente' );
                    $scope.dialAccionCliente.hide();
                    $scope.txtCliente = '';
                    $scope.focusHtml( 'efectivo_' + $scope.idTab );
                }
            }).error(function (error, status){
                $scope.data.error = { message: error, status: status};
                console.log( $scope.data.error.status ); 
            });
        }
    };

    $scope.seleccionarCliente = function( cliente ) {
    	$scope.facturacion.datosCliente = angular.copy( cliente );
    	$scope.dialAccionCliente.hide();
    	$scope.txtCliente = '';
    	$timeout(function(){
        	$( '#ticket' ).focus();
        }, 150);
    };

    $scope.editarCliente = function( cliente, accion ){
    	$scope.accionCliente = 'actualizar';
    	$scope.accion        = 'update';
    	$scope.cliente = angular.copy( cliente );
    	console.log( cliente );
    	if( accion == 'mostrar' )
    		$scope.dialAccionCliente.show();
    }

	// BUSCARCLIENTE
	$scope.lstClientes = [];
	$scope.txtCliente  = '';
	$scope.buscarCliente = function( valor, accion ){
		if( valor.length == 0 && 'principal'  ) {
			$scope.accionCliente                      = 'ninguna';
			$scope.facturacion.datosCliente.nombre    = '';
			$scope.facturacion.datosCliente.direccion = '';
			$scope.txtCliente                         = '';
			$scope.lstClientes                        = [];
			valor                                     = 'cf';
			accion                                    = 'cf';
			$timeout(function(){
				$('#buscador').focus();
			});
	        $scope.dialAccionCliente.show();
		}
		
		if( valor.length >= 1 ){
			
			if( accion == 'principal' )
				$scope.accionCliente = 'ninguna';

			$http.post('consultas.php',{
	            opcion : "consultarCliente",
	            valor  : valor,
	        }).success(function(data){
	        	console.log( data );
	            if( accion == 'principal' && data.lstResultados.length == 0 ) {
	            	$scope.accionCliente = 'ninguna';
	            	//$scope.facturacion.datosCliente.nombre    = '';
					//$scope.facturacion.datosCliente.direccion = '';
	            	$scope.txtCliente = '';
	            	$scope.dialAccionCliente.show();

	            	$scope.accionCliente = 'agregar';
	            	$timeout(function(){
	            		$( '#nit' ).focus();
	            	});
	            }
	            if( accion == 'busqueda' && data.lstResultados.length == 0 ) {
	            	alertify.notify( 'No se encontraron resultados', 'info', 3 );
	            	$scope.lstClientes = [];
	            }
	        	else if( ( accion == 'principal' || accion == 'cf' ) && data.lstResultados.length == 1 && data.encontrado ){
	        		//$scope.facturacion.datosCliente = data.lstResultados[ 0 ];
	        		
	        		//ASIGNA INFORMACION DE CLIENTE
	        		var ixFacturaActual  = $scope.indexArray( 'lstFacturas', 'idTab', $scope.idTab );
					$scope.lstFacturas[ ixFacturaActual ].cliente.idCliente     = data.lstResultados[ 0 ].idCliente;
					$scope.lstFacturas[ ixFacturaActual ].cliente.nit           = data.lstResultados[ 0 ].nit;
					$scope.lstFacturas[ ixFacturaActual ].cliente.nombre        = data.lstResultados[ 0 ].nombre;
					$scope.lstFacturas[ ixFacturaActual ].cliente.direccion     = data.lstResultados[ 0 ].direccion;
					$scope.lstFacturas[ ixFacturaActual ].cliente.idTipoCliente = data.lstResultados[ 0 ].idTipoCliente;
	        		//ASIGNA INFORMACION DE CLIENTE

	        		$scope.txtCliente = '';
                    
                    if( accion == 'cf' )
	            		$scope.dialAccionCliente.hide();

	            	console.log(data.lstResultados[ 0 ].nit);
	            	if ( data.lstResultados[ 0 ].nit == 'CF' )
	            		$scope.focusHtml( 'nombreCliente_' + $scope.idTab, true );
	            	
	            	else
	            		$scope.focusHtml( 'efectivo_' + $scope.idTab );
	        	}
	        	else if( (accion == 'principal' || accion == 'busqueda') && data.lstResultados.length == 1 && !data.encontrado ){
	        		$scope.dialAccionCliente.show();
	        		$scope.accionCliente = 'agregar';
	        		$timeout(function(){
	        			$scope.cliente = data.lstResultados[ 0 ];
	        		});
	        	}
	            else if( data.lstResultados.length >= 1 ){
					$scope.lstClientes   = data.lstResultados;
					$scope.accionCliente = 'ninguna';
					$scope.dialAccionCliente.show();
	            }
	        })
		}
		else
			alertify.notify( 'Ingrese un dato para consultar', 'info', 3 );
	};

	// SI EXISTE ORDEN DE CLIENTE
	if ( $routeParams.idOrdenCliente && $routeParams.idOrdenCliente > 0 ) {
		$scope.$parent.loading = false;
		$scope.buscarOrdenTicket( $routeParams.idOrdenCliente );
		$timeout(function(){
			$('#searchPrincipal').focus();
		});
	}
	else{
		$timeout(function(){
			$('#ticket').focus();
		});
	}

	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
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

	$scope.focusHtml = function (element, select) {
		$timeout(function() {
			document.getElementById( element ) && document.getElementById( element ).focus();

			// SI DEBE SELECCIONAR EL TEXTO
			if ( select )
				document.getElementById( element ) && document.getElementById( element ).select();
		});
	};

	$scope.convert = function ( valor, tipo, _default ) {
		if ( tipo == 'float' )
		{
			if ( valor == null || valor == undefined || valor == '' )
				valor = _default;

			else
				valor = parseFloat( valor );
		}

		return valor;
	};
});