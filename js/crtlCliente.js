app.controller('clienteCtrl', function( $scope, $http, $modal, $timeout ){
	
    $scope.menuCliente = 'ingresar';
    $scope.accion      = 'insert';
    $scope.txtCliente  = '';
    $scope.lstClientes = [];

    // TECLA PARA ATAJOS RAPIDOS
    $scope.$on('keyPress', function( event, key, altDerecho )
    {
        // SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
        if ( $scope.$parent.loading )
            return false;
    
        if( key == 117 )
        {
            if( $scope.menuCliente == 'ingresar' && !$scope.modalOpen() )
                $scope.consultaCliente();
        }
    });


    $scope.modalOpen = function ( _name ) {
        if ( _name == undefined )
            return $("body>div").hasClass('modal') && $("body>div").hasClass('top');
        else
            return !!( $( '#' + _name ).data() && $( '#' + _name ).data().$scope.$isShown );
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

    $scope.resetValores( 'cliente' 

    if( document.getElementById("dial.buscarCliente.html") )
        $scope.dialBuscarCliente = $modal({scope: $scope,template:'dial.buscarCliente.html', show: false, backdrop: 'static'});

	$scope.buscarCliente = function( valor, accion ){
		console.log( valor, accion );
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
	            if( !data.encontrado ) {
	            	$scope.lstClientes = [];
	            	alertify.notify( 'No se encontró resultados, agregar <b>CLIENTE</b>', 'info', 5 );
                    $scope.dialBuscarCliente.hide();
                    $scope.txtCliente  = '';
                    $scope.menuCliente = 'ingresar';
                    $scope.accion      = 'insert';
                    $scope.cliente     = data.lstResultados[ 0 ];
                    $( '#nit' ).focus();
	            }
	        	else if( data.lstResultados.length == 1 && data.encontrado ){
	        		$scope.accion = 'update';
	        		$scope.cliente = data.lstResultados[ 0 ];
	        		$scope.dialBuscarCliente.hide();
	        		$scope.txtCliente = '';
	        		$scope.menuCliente = 'ingresar';
	        		$( '#ticket' ).focus();
	        	}
	            else if( data.lstResultados.length > 1 ){
                    $scope.lstClientes = data.lstResultados;
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

        if( !(cliente.nombre && cliente.nombre.length >= 3) )
            alertify.notify('El nombre debe tener más de 2 caracteres', 'warning', 4);

        else if( !(cliente.direccion && cliente.direccion.length >= 8) )
            alertify.notify('La dirección debe tener más de 7 caracteres', 'warning', 5);

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
                    $scope.resetValores( 'cliente' );
                    $scope.txtCliente = '';
                    $( '#ticket' ).focus();
                }
            }).error(function (error, status){
                $scope.data.error = { message: error, status: status};
                console.log( $scope.data.error.status ); 
            });
        }
    };

	$scope.seleccionarCliente = function( cliente ){
        $scope.cliente = angular.copy( cliente );
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