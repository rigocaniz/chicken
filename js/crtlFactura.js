app.controller('facturaCtrl', function( $scope, $http, $modal, $timeout, $routeParams ){
	
	$scope.accionCliente     = 'ninguna';
	$scope.menuFactura       = 'facturar';
	$scope.accion            = 'insert';
	$scope.buscarTicket      = null;

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
			console.log( data );
		    $scope.lstFormasPago = data;
		    $scope.facturacion.lstFormasPago = data;
		})
	})();

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

	$scope.retornarTotal = function() {
		var total = 0;

		if( $scope.facturacion.lstOrden && $scope.facturacion.lstOrden.length ){
			for (var i = 0; i < $scope.facturacion.lstOrden.length; i++) {
				var orden = $scope.facturacion.lstOrden[ i ];
				if( orden.cobrarTodo )
					total += ( ( orden.cantidad * orden.precioMenu ) - orden.descuento );
			}
		}

		return total;
	};

	$scope.totalVuelto = function(){
		var vuelto = 0, total = 0;
		if( $scope.retornarTotal() > 0 ){
			for (var i = 0; i < $scope.facturacion.lstFormasPago.length; i++) {
				var pago = $scope.facturacion.lstFormasPago[ i ];
				if( (pago.monto && pago.monto > $scope.retornarTotal() ) && pago.idFormaPago == 1 ){
					total += pago.monto;
				}
			}
		}

		if( total > 0 )
			vuelto = total - ( $scope.retornarTotal() > 0 ? $scope.retornarTotal() : 0 );

		return vuelto;
	};

	$scope.dialAccionCliente = $modal({scope: $scope,template:'dial.accionCliente.html', show: false, backdrop:false, keyboard: true });
	$scope.dialOrdenBusqueda = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: true });
	$scope.dialPrintFactura  = $modal({scope: $scope,template:'dial.printFactura.html', show: false, backdrop:false, keyboard: true });
	$scope.dialReimpresion   = $modal({scope: $scope,template:'dial.reimpresion.html', show: false, backdrop:false, keyboard: true });
	$scope.dialCaja          = $modal({scope: $scope,template:'dial.caja.html', show: false, backdrop:false, keyboard: true });
	
	

	$scope.seleccionarDeBusqueda = function ( orden ) {
		$scope.miIndex = -1;
		$scope.dialOrdenBusqueda.hide();
		$scope.facturacion.numeroTicket   = angular.copy( parseInt( orden.numeroTicket ) );
		$scope.facturacion.idOrdenCliente = angular.copy( parseInt( orden.idOrdenCliente ) );

		$timeout(function () {
			$scope.modalInfo( orden, true );
		});
	};

	$scope.impresionFactura = {
		type      : 'g',
		idFactura : null
	};

	$scope.consultaFacturaCliente = function() {
		$scope.facturacion.total = $scope.retornarTotal();
		var factura = $scope.facturacion;
		
		if( !(factura.datosCliente.idCliente && factura.datosCliente.idCliente > 0) )
			alertify.notify('Seleccione un cliente', 'warning', 4);
		
		else if( !(factura.numeroTicket && factura.numeroTicket > 0) )
			alertify.notify('Ingrese un número de Ticket válido', 'warning', 4);
		
		else if( !(factura.lstOrden && factura.lstOrden.length > 0) )
			alertify.notify('La lista de ordenes está vacia', 'warning', 4);
		
		else if( !(factura.total && factura.total > 0) )
			alertify.notify('El total del cobro debe ser mayor a 0', 'warning', 4);

		else {
			
			$scope.$parent.showLoading( 'Guardando...' );
			
			$http.post('consultas.php',{
			    opcion : "consultaFacturaCliente",
			    accion : $scope.accion,
			    data   : $scope.facturacion
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
				$scope.consultaCliente()
		}

		// SI ES PANTALLA PRINCIPAL Y PRESIONAR LA TECLA {F6}
		if ( !$scope.modalOpen() && key == 117 )
			$scope.consultaFacturaCliente();

		// TECLA C
		if ( altDerecho && key == 67 ) {
			$scope.buscarCliente( 'CF', 'cf' )
		}
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


	$scope.modalOpen = function ( _name ) {
		if ( _name == undefined )
			return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
		else
			return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
	};
	
	$scope.cliente  = {
        'nit'           : null,
        'nombre'        : '',
        'cui'           : null,
        'correo'        : '',
        'telefono'      : null,
        'direccion'     : '',
        'idTipoCliente' : 1,
    };

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
        else if( accion == 'lstClientes' ) {
            $scope.lstClientes = [];
        }
        else if( 'facturacion' ){
        	$scope.facturacion = {
				datosCliente : {
					nit          : '',
					nombre       : '',
					direccion    : '',
				},
				agrupado: true,
				idEstadoFactura : 1,
				numeroTicket    : null,
				noFactura       : null,
				total           : 0,
				lstFormasPago   : [],
				lstOrden        : [],
			};
        }
    };


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

	$scope.lstDetalleOrden = [];
	$scope.detalleTicket = function( idOrden ){
		if (! (idOrden > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
            alertify.notify('Ingrese algún dato para buscar', 'warning', 3);
		}else{
			 $http.post('consultas.php',{
                opcion         : "lstDetalleOrdenCliente",
                idOrdenCliente : idOrden
            }).success(function(data){
                console.log(data);
                $scope.lstDetalleOrden = data;
            })
		}
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
                	$scope.facturacion.datosCliente = angular.copy( $scope.cliente );
                	if( $scope.accion == 'insert' )
                		$scope.facturacion.datosCliente.idCliente = data.data;

                    $scope.resetValores( 'cliente' );
                    $scope.dialAccionCliente.hide();
                    $scope.txtCliente = '';
                    $timeout(function(){
                    	$( '#ticket' ).focus();
                    }, 150);
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
    	if( accion == 'mostrar' )
    		$scope.dialAccionCliente.show();
    }

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
				console.log( "TICKET:::", data );
				$scope.$parent.loading = false; // cargando...
				if ( Array.isArray( data ) && data.length ) {
					$scope.lstTicketBusqueda = data;
					if( $scope.lstTicketBusqueda.length == 1 )
						$scope.seleccionarDeBusqueda( $scope.lstTicketBusqueda[ 0 ] );
					else
						$scope.dialOrdenBusqueda.show();
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( "No se encontro información del TICKET", 'info', 3 );
				}
			});
		}else{
			alertify.notify( "El No. de Ticket debe ser mayor a 0", 'info', 3 );
		}
	};

	// BUSCARCLIENTE
	$scope.lstClientes = [];
	$scope.txtCliente  = '';
	$scope.buscarCliente = function( valor, accion ){
		if( valor.length == 0 && 'principal'  ) {
			$scope.accionCliente = 'ninguna';
			$scope.facturacion.datosCliente.nombre    = '';
			$scope.facturacion.datosCliente.direccion = '';
			$scope.txtCliente = '';
			$scope.lstClientes = [];
			/*$timeout(function(){
				$('#buscador').focus();
			});
	        $scope.dialAccionCliente.show();*/
	        valor = 'cf';
		}
		
		if( valor.length >= 1 ){
			
			if( accion == 'principal' )
				$scope.accionCliente = 'ninguna';

			$http.post('consultas.php',{
	            opcion : "consultarCliente",
	            valor  : valor,
	        }).success(function(data){
	        	console.log( data );
	            if( accion == 'principal' && data.length == 0 ) {
	            	$scope.accionCliente = 'ninguna';
	            	$scope.facturacion.datosCliente.nombre    = '';
					$scope.facturacion.datosCliente.direccion = '';
	            	$scope.txtCliente = '';
	            	$scope.dialAccionCliente.show();

	            	$scope.accionCliente = 'agregar';
	            	$timeout(function(){
	            		$( '#nit' ).focus();
	            	});
	            }
	            if( accion == 'busqueda' && data.length == 0 ) {
	            	alertify.notify( 'No se encontraron resultados', 'info', 3 );
	            	$scope.lstClientes = [];
	            }
	        	else if( ( accion == 'principal' || accion == 'cf' ) && data.length == 1 ){
	        		$scope.facturacion.datosCliente = data[ 0 ];
	        		$scope.txtCliente = '';
                    
                    if( accion == 'cf' )
	            		$scope.dialAccionCliente.hide();

	        		$timeout(function(){
                    	$( '#cliente_nit' ).focus();
                    });
	        	}
	            else if( data.length >= 1 ){
					$scope.lstClientes   = data;
					$scope.accionCliente = 'ninguna';
					$scope.dialAccionCliente.show();
	            }
	        })
		}
		else
			alertify.notify( 'Ingrese un dato para consultar', 'info', 3 );
	};

	// SI EXISTE ORDEN DE CLIENTE
	if ( $routeParams.idOrdenCliente && $routeParams.idOrdenCliente > 0 )
	{
		$scope.buscarOrdenTicket( $routeParams.idOrdenCliente );
		$timeout(function(){
			$('#searchPrincipal').focus();
		});
	}
	else
		$timeout(function(){
			$('#ticket').focus();
		});
});