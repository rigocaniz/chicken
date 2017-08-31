app.controller('facturaCtrl', function( $scope, $http, $modal, $timeout ){
	
	$scope.accionCliente     = 'ninguna';
	$scope.menuFactura       = 'facturar';
	$scope.accion            = 'insert';
	$scope.buscarTicket      = null;

	$scope.dialAccionCliente = $modal({scope: $scope,template:'dial.accionCliente.html', show: false, backdrop:false, keyboard: false });
	$scope.dialOrdenBusqueda = $modal({scope: $scope,template:'dial.orden-busqueda.html', show: false, backdrop:false, keyboard: true });

	$scope.seleccionarDeBusqueda = function ( orden ) {
		console.log( orden );
		$scope.miIndex = -1;
		$scope.dialOrdenBusqueda.hide();
		$timeout(function () {
			$scope.modalInfo( orden, true );
		});
	};
	
	/* %%%%%%%%%%%%%%%%%%%%%%%%%%%% DIALOGO PARA MAS INFORMACION DE ORDEN %%%%%%%%%%%%%%%%%%%%%% */
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
		$http.post('consultas.php', { opcion : 'lstDetalleOrdenCliente', idOrdenCliente : orden.idOrdenCliente, todo : $scope.todoDetalle })
		.success(function (data) {
			$scope.$parent.loading    = false;
			if ( data.lst ) {
				$scope.infoOrden.lstOrden = data.lst;
				$scope.infoOrden.total    = data.total;
			}
		});
	};


	// => CAMBIAR SERVICIO DE ORDEN
	$scope.cambiarServicio = function ( idOrdenCliente, lstDetalle, idTipoServicio ) {
		console.log( idOrdenCliente, lstDetalle, idTipoServicio );
		if ( $scope.$parent.loading )
			return false;

		$scope.$parent.loading = true; // cargando...

		// CONSULTA PRECIOS DEL MENU
		$http.post('consultas.php', { 
			opcion         : 'cambiarServicio',
			idOrdenCliente : idOrdenCliente,
			lstDetalle     : lstDetalle,
			idTipoServicio : idTipoServicio
		})
		.success(function (data) {
			console.log( data );

			$scope.$parent.loading = false; // cargando...

			if ( data.mensaje != undefined ) {
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, 3 );
			}
		});
	};


	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho )
	{
		//console.log( event, key, altDerecho );
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		console.log( !$scope.modalOpen() );
		// TECLA C
		if ( altDerecho && key == 67 ) {
			$scope.buscarCliente( 'CF', 'cf' )
		}
		// TECLA A
		else if( altDerecho && key == 69 ){
			if( !$scope.modalOpen() )
			{
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

		else if( !$scope.modalOpen() ) {
			console.log( "prueba" );
			/*
			// MODO PANTALLA
			if ( altDerecho && key == 65 ){
				$scope.dialAccionCliente.show();
			}
			*/

			/*
			else if ( altDerecho && key == 68 ) $scope.cambiarVista( 'dividido' );
			else if ( altDerecho && key == 84 ) $scope.cambiarVista( 'ticket' );

			else{
				$scope._keyInicio( key, altDerecho );
			}
			*/
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


	$timeout(function(){
		$('#searchPrincipal').focus();
	}, 150);
	
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
        if( accion == 'lstClientes' ) {
            $scope.lstClientes = [];
        }
    };


	$scope.lstDetalleTicket = [];
	$scope.facturacion = {
		datosCliente : {
			nit          : '',
			nombre       : '',
			direccion    : '',
		},
		ticket       : null,
		noFactura    : null,
		lstPago      : []
	};
	
	$scope.$watch('accionCliente', function(){
		if( $scope.accionCliente == 'agregar' ){
			$scope.resetValores( 'cliente' );
			$scope.accion = 'insert';
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

        if( !(cliente.nombre && cliente.nombre.length >= 3) ) {
            alertify.notify('El nombre debe tener más de 2 caracteres', 'warning', 4);
        }
		else if( cliente.cui == undefined )
			alertify.notify('El No. de CUI es inválido', 'warning', 3);	
		else if( cliente.cui.length >= 1 && !(cliente.cui.length == 13) )
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
                    $( '#ticket' ).focus();
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
    	$( '#ticket' ).focus();
    };

    $scope.editarCliente = function( cliente, accion ){
    	$scope.accionCliente = 'actualizar';
    	$scope.accion        = 'update';
    	$scope.cliente = angular.copy( cliente );
    	if( accion == 'mostrar' )
    		$scope.dialAccionCliente.show();
    }

   	$scope.lstTicketBusqueda = [];
	$scope.buscarOrdenTicket = function () {

		if ( $scope.$parent.loading )
			return false;

		if ( $scope.buscarTicket > 0 ) {
			$scope.lstTicketBusqueda = [];
			$scope.$parent.loading = true; // cargando...
			
			// CONSULTA TIPO DE SERVICIOS
			$http.post('consultas.php', { opcion : 'busquedaTicket', ticket : $scope.buscarTicket })
			.success(function (data) {
				console.log( data );
				$scope.$parent.loading = false; // cargando...

				if ( Array.isArray( data ) && data.length ) {
					$scope.buscarTicket = 0;
					$scope.lstTicketBusqueda = data;
					$scope.dialOrdenBusqueda.show();
				}
				else{
					alertify.set('notifier','position', 'top-right');
					alertify.notify( "No se encontro información", 'info', 2 );
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
			$timeout(function(){
				$('#buscador').focus();
			});
	        $scope.dialAccionCliente.show();
		}
		else if( valor.length >= 1 ){
			
			if( accion == 'principal' )
				$scope.accionCliente = 'ninguna';

			$http.post('consultas.php',{
	            opcion : "consultarCliente",
	            valor  : valor,
	        }).success(function(data){
	        	console.log( data );
	            if( accion == 'principal' && data.length == 0 )
	            {
	            	$scope.accionCliente = 'ninguna';
	            	$scope.facturacion.datosCliente.nombre    = '';
					$scope.facturacion.datosCliente.direccion = '';
	            	$scope.txtCliente = '';
	            	$scope.dialAccionCliente.show();
	            	$timeout(function(){
	            		$( '#buscador' ).focus();
	            	});
	            }
	            if( accion == 'busqueda' && data.length == 0 )
	            {
	            	alertify.notify( 'No se encontraron resultados', 'info', 3 );
	            	$scope.lstClientes = [];
	            }
	        	else if( accion == 'principal' && data.length == 1 ){
	        		$scope.facturacion.datosCliente = data[ 0 ];
	        		$scope.txtCliente = '';
	        		$( '#ticket' ).focus();
	        	}
	            else if( accion == 'cf' && data.length == 1 )
	            {
	            	$( '#ticket' ).focus();
	            	$scope.txtCliente = '';
	            	$scope.facturacion.datosCliente = data[ 0 ];
	            	$scope.dialAccionCliente.hide();
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

});