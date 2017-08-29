app.controller('clienteCtrl', function( $scope, $http, $modal, $timeout ){
	
   	$scope.menuCliente = 'ingresar';

   	$scope.dialBuscarCliente = $modal({scope: $scope,template:'dial.buscarCliente.html', show: false, backdrop: 'static'});

	$scope.txtCliente  = '';
	$scope.lstClientes = [];
	$scope.buscarCliente = function( valor, accion ){
		if( valor.length == 0 && accion == 'principal'  ) {
			$scope.txtCliente = '';
			$scope.lstClientes = [];
	        $scope.dialBuscarCliente.show();
			$timeout(function(){
				$('#buscador').focus();
			},150);
		}
		else if( valor.length >= 1 ){

			$http.post('consultas.php',{
	            opcion : "consultarCliente",
	            valor  : valor,
	        }).success(function(data){
	        	console.log( data );
	            if( data.length == 0 )
	            {
	            	$scope.lstClientes = [];
	            	alertify.notify( 'No se encontraron resultados', 'info', 3 );
	            }
	        	else if( data.length == 1 ){
	        		$scope.$parent.cliente = data[ 0 ];
	        		$scope.dialBuscarCliente.hide();
	        		$scope.txtCliente = '';
	        		$scope.menuCliente = 'ingresar';
	        		$( '#ticket' ).focus();
	        	}
	            else if( data.length > 1 ){
					$scope.lstClientes   = data;
					if( accion == 'principal' )
						$scope.dialBuscarCliente.show();
					
					$timeout(function(){
						$('#buscador').focus();
					});
	            }
	        })
		}
		else
			alertify.notify( 'Ingrese un dato para consultar', 'info', 3 );
	};


   	// CONSULTA CLIENTE => INSERT // UPDATE
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
                    $scope.$parent.resetValores( 'cliente' );
                    $scope.txtCliente = '';
                    $( '#ticket' ).focus();
                }
            }).error(function (error, status){
                $scope.data.error = { message: error, status: status};
                console.log( $scope.data.error.status ); 
            });
        }
    };


	$scope.seleccionarCliente = function(cliente)
    {
        $scope.$parent.cliente = angular.copy( cliente );
        $scope.resetValores( 'lstClientes' );
        $scope.txtCliente  = '';
        $scope.menuCliente = 'ingresar';
        $scope.accion      = 'update';
        $scope.dialBuscarCliente.hide();
        $timeout(function(){
            $( '#nit' ).focus();
        }, 125)
    };

});