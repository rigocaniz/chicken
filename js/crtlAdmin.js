app.controller('crtlAdmin', function( $scope , $http, $modal ){

	$scope.inventarioMenu = 2;

	$scope.filtro = {
		filter : { filter: 'idMedida', value : 8 },
		order  : { by: 'idMedia', order: 'ASC' },
		limit  : 25,
		page   : 1
	};

	$scope.lstTipoServicio = [];
	$scope.lstDestinoMenu  = [];

	($scope.inicio = function(){
        $http.post('consultas.php',{
            opcion : 'catDestinoMenu'
        }).success(function(data){
            console.log( 'destinos', data );
            $scope.lstDestinoMenu = data;
        })
		
	})();


    ($scope.catTipoServicio = function(){
        $http.post('consultas.php',{
            opcion : 'catTiposServicio'
        }).success(function(data){
            console.log( 'tiposServicio', data );
            $scope.lstTipoServicio = data;
        })
    })();


    $scope.returnTipoServicio = function( idTipoServicio ){
        for (var i = 0; i < $scope.lstTipoServicio.length; i++) {
            if( $scope.lstTipoServicio[ i ].idTipoServicio == idTipoServicio ){
                return $scope.lstTipoServicio[ i ].tipoServicio;
                break;
            }
        }
    };


	$scope.dialIngreso    = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdminMenu  = $modal({scope: $scope,template:'dial.adminMenu.html', show: false, backdrop: 'static'});
	$scope.dialAdminCombo = $modal({scope: $scope,template:'dial.adminCombo.html', show: false, backdrop: 'static'});

	$scope.listaMenu = function(){
		$http.post('consultas.php',{
			opcion : 'lstMenu',
			filtro : $scope.filtro
		}).success(function(data){
			console.log( 'lista: ', data );
			$scope.lstMenus = data;
		})
	};

	$scope.lstTipoMenu = [];
	$scope.catTipoMenu = function(){
		$http.post('consultas.php',{
			opcion:'catTipoMenu'
		}).success(function(data){
			$scope.lstTipoMenu = data;
		})
	};

	$scope.listaCombo = function(){
		$http.post('consultas.php',{
			opcion:'lstCombo'
		}).success(function(data){
			console.log('lstCombos: ',data);
			$scope.lstCombos = data;
		})
	};


	$scope.filter = {
		pagina: 1,
		limite: 25,
		orden: 'ASC'
	};

	($scope.inicio = function()
	{
		$scope.listaMenu();
		$scope.catTipoMenu();
		$scope.listaCombo();
	})();

	$scope.menu = {
		'idEstadoMenu'  : 1,
		'menu'          : '',
		'idDestinoMenu' : 1,
		'idTipoMenu'    : 1,
		'descripcion'   : '',
		'imagen'        : '',
		'subirImagen'   : true,
		'lstPrecios'    : []
	};


	$scope.accion = 'insert';
	$scope.agregarMenuCombo = function( tipo ){
		//console.log( 'tipo: ', tipo );
		$scope.accion = 'insert';
		if( $scope.tipo == 'menu' ){
			$scope.menu = {
				'idEstadoMenu'  : 1,
				'menu'          : '',
				'idDestinoMenu' : null,
				'idTipoMenu' : 1,
				'descripcion'   : '',
				'imagen'        : '',
				'subirImagen'   : true,
				'lstPrecios'    : []
			};

			$scope.dialAdminMenu.show();
		}

		else if( tipo == 'combo' ){
			$scope.combo = {
				'idEstadoMenu' : 1,
				'combo'        : '',
				'descripcion'  : '',
				'subirImagen'  : true,
				'imagen'       : '',
			}
			$scope.dialAdminCombo.show();
		}

	};
	
	// AGREGAR PRECIOS SEGÚN TIPO SERVICIO
	$scope.precios = {
		idTipoServicio : null,
		precio         : 0
	};

	$scope.agregaPrecio = function( tipo ){
		var precio = $scope.precios;
		console.log( $scope.menu );

		if( tipo == 'menu' )
		{

			if( !(precio.idTipoServicio && precio.idTipoServicio > 0 ) )
			{
				alertify.notify( 'Seleccione el tipo de servicio', 'warning', 5 );
			}
			else if( !(precio.precio && precio.precio > 0 ) ){
				alertify.notify( 'El precio debe ser mayor a 0', 'warning', 5 );
			}
			else{
				$scope.menu.lstPrecios.push({
					idTipoServicio : precio.idTipoServicio,
					precio         : parseFloat( precio.precio ),
					editar         : false
				});
				alertify.notify( 'Agregado', 'success', 4 );

				$scope.precios = {
					idTipoServicio : null,
					precio         : 0
				};
			}


		}
		
	};

	$scope.removerPrecio = function( index ){
		$scope.menu.lstPrecios.splice( index, 1);
	};


	// REGISTRAR MENU
	$scope.consultaMenu = function(){
		var menu = $scope.menu;

		console.log($scope.menu);
		if( $scope.accion == 'update' && !(menu.idMenu && menu.idMenu > 0) ){
			alertify.notify( 'No. de menú no válido', 'info', 5 );
		}
		else if( !( menu.idEstadoMenu && menu.idEstadoMenu > 0 )  ){
			alertify.notify( 'Seleccione el estado del Menú', 'info', 5 );	
		}
		else if( !( menu.menu && menu.menu.length > 3 ) ){
			alertify.notify( 'El nombre del menú debe ser mayor a 3 caracteres', 'info', 5 );		
		}
		else if( !( menu.idDestinoMenu && menu.idDestinoMenu > 0 ) ){
			alertify.notify( 'Seleccione el destino del Menú', 'info', 5 );	
		}
		else if( !( menu.idTipoMenu && menu.idTipoMenu > 0 ) ){
			alertify.notify( 'Seleccione el tipo de Menú', 'info', 5 );	
		}
		else if( !( menu.descripcion && menu.descripcion.length > 15 ) ){
			alertify.notify( 'La descripción del menú debe ser mayor a 15 caracteres', 'info', 5 );		
		}
		/*
		else if( !menu.lstPrecios.length  ){
			alertify.notify( 'No ha ingresado los precios del Menu', 'info', 5 );		
		}
		*/
		else{
			$http.post('consultas.php',{
				opcion:"consultaMenu",
				accion:$scope.accion,
				datos: $scope.menu
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.dialAdminMenu.hide();
					$scope.reset;
					//$scope.menu = {};
				}
			})

		}
	};


		$scope.registraCombo = function(){
			if ( $scope.formCombo.$invalid == true ) {
				alertify.set('notifier','position', 'top-right');
	 			alertify.notify('Ingrese los datos solicitados', 'warning', 3);
			}else{
				$http.post('consultas.php',{
					opcion:"consultaCombo",
					accion:$scope.accion,
					datos: $scope.combo
				}).success(function(data){
					alertify.set('notifier','position', 'top-right');
	 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
					if ( data.respuesta == 'success' ) {
						$scope.combo = {};
					}
				})
			}
		}



});