app.controller('facturaCtrl', function( $scope, $http, $modal, $timeout ){
	
	$scope.dialAccionCliente = $modal({scope: $scope,template:'dial.accionCliente.html', show: false, backdrop:false, keyboard: false });
	$scope.accionCliente     = 'ninguna';

	$scope.menuFactura = 'facturar';

	$scope.nuevoCliente = function(valor,evento){
		if ( evento == 1 ) {
			$scope.dialCliente.show();
		}else{
			$scope.$parent.buscarCliente(valor,1);
			$scope.dialCliente.show();
		}
	}
	$scope.lstDetalleTicket = [];
	$scope.facturacion = {
		datosCliente : {
			nit       : '',
			nombre    : '',
			direccion : '',
		},
		ticket       : null,
		lstPago      : []
	};
	
	$scope.$watch('accionCliente', function(){
		if( $scope.accionCliente == 'agregar' ){
			$scope.$parent.resetValores( 'cliente' );
			$scope.accion = 'insert';
		}

	});

	$scope.detalleTicket = function(idOrden){
		if (! (idOrden > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
            alertify.notify('Ingrese algún dato para buscar', 'warning', 3);
		}else{
			 $http.post('consultas.php',{
                opcion:"lstDetalleOrdenCliente",
                idOrdenCliente: idOrden
            }).success(function(data){
                console.log(data);

                /*if (encontrados == 1 ) {
                    $scope.lstDetalleTicket = data;
                }else{
                    alertify.set('notifier','position', 'top-right');
                    alertify.notify('Ningún dato localizado', 'warning', 3);
                }*/
            })
		}
	};

	$scope.consultaCliente = function(){
        var cliente = $scope.cliente;

        console.log( cliente );
        if( !(cliente.nombre && cliente.nombre.length >= 3) ) {
            alertify.notify('El nombre debe tener más de 2 caracteres', 'warning', 4);
        }
		else if( cliente.cui == undefined )
			alertify.notify('El No. de CUI es inválido', 'warning', 3);	
		else if( cliente.cui.length >= 1 && !(cliente.cui.length == 13) )
			alertify.notify('El No. de CUI debe tener 13 dígitos', 'warning', 3);	
        else
        {
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
                	$scope.facturacion.datosCliente = angular.copy( $scope.$parent.cliente );
                	if( $scope.accion == 'insert' )
                		$scope.facturacion.datosCliente.idCliente = data.data;

                    $scope.$parent.resetValores( 'cliente' );
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
    	$scope.$parent.cliente = angular.copy( cliente );
    	if( accion == 'mostrar' )
    		$scope.dialAccionCliente.show();
    }

	// BUSCARCLIENTE
	$scope.lstClientes = [];
	$scope.txtCliente  = '';
	$scope.buscarCliente = function( valor, accion ){
		console.log( 'accion:::', accion )
		if( valor.length == 0 && 'principal'  )
		{
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