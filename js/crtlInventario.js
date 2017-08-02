app.controller('inventarioCtrl', function( $scope , $http, $modal ){

	// LISTA EL INVENTARIO GENERAL DE PRODUCTOS
	$scope.lstInventario  = [];
	$scope.inventarioMenu = 1;

	$scope.$watch('inventarioMenu', function( _old, _new){
		console.log( _old, _new );
		if( $scope.inventarioMenu == 1 )
			$scope.inventario();
	});


	$scope.dialIngreso     = $modal({scope: $scope,template:'dial.ingreso.html', show: false, backdrop: 'static'});
	$scope.dialAdministrar = $modal({scope: $scope,template:'dialAdmin.producto.html', show: false, backdrop: 'static'});

	$scope.dialAdministrarAbrir = function(){
		$scope.dialAdministrar.show();
	};

	$scope.dialAdministrarCerrar = function(){
		$scope.dialAdministrar.hide();
	};


	$scope.catTipoProducto = function(){
		$http.post('consultas.php',{
			opcion:'catTipoProducto'
		}).success(function(data){
			$scope.lstTipoProducto = data;
		})
	};


	$scope.catMedidas = function(){
		$http.post('consultas.php',{
			opcion:'catMedidas'
		}).success(function(data){
			$scope.lstMedidas = data;
		})
	};

	$scope.lstPaginacion = [];

	$scope.generarPaginacion = function( totalPaginas ){
		$scope.lstPaginacion = [];
		for (var i = 1; i <= totalPaginas; i++) {
			$scope.lstPaginacion.push({
				noPagina : i
			});
		}
	};


	$scope.cargarPaginacion = function( pagina ){
		$scope.filter.pagina = pagina;
		$scope.inventario();
	};

	$scope.filter = {
		pagina: 1,
		limite: 25,
		orden: 'ASC'
	};


	$scope.resetValues = function( accion ){

		if( accion == 1 ){
			$scope.producto = {
				'producto'       : '',
				'idTipoProducto' : null,
				'idMedida'       : null,
				'perecedero'     : true,
				'cantidadMinima' : null,
				'cantidadMaxima' : null,
				'disponibilidad' : '',
				'importante'     : '',
			};			
		}
		else if( accion == 2 ){
			$scope.itemProducto = {
				idProducto     : null,
				nombreProducto : '',
				cantidad       : 0,
				disponibilidad : null,
				observacion    : '',
				esIncremento   : true
			};
		}

	};

	($scope.cargarInicio = function(){
		$scope.catMedidas();
		$scope.catTipoProducto();
		$scope.resetValues( 1 );
	})();

	/*$scope.filtro        = {
		filter : [
			{ filter: 'idMedida', value : 8 },
			{ filter: 'idTipoProducto', value : 2 },
		],
		order : [
			{ by: 'idMedia', order: 'asc' },
			{ by: 'idTipoProducto', order: 'desc' }
		],
		limit : 15,
		page : 1,
	};*/


	$scope.filtro = {
		filter : [
			{ filter: 'idProducto', value : 8 }
		],
		order : [
			{ by: 'idTipoProducto', order: 'DESC' }
		],
		limit : 25,
		page : 1,
	};


	$scope.editarAccion = function( id, accion ){

		$scope.id     = id;
		$scope.accion = accion;

		if ( $scope.id > 0 ){

			$http.post('consultas.php',{
				opcion     : 'cargarProducto',
				idProducto : $scope.id
			}).success(function(data){
				console.log(data);
				$scope.producto = data;
			})

			$scope.accion = 'update';
		}

		$scope.dialAdministrarAbrir();
	};


	// INSERTAR / ACTUALIZAR PRODUCTO
	$scope.consultaProducto = function(){
		var producto = $scope.producto, accion = $scope.accion;

		if( accion == 'update' && !(producto.idProducto > 0) ){
			alertify.notify( 'No. de producto no definido', 'danger', 5 );
		}
		else if( !(producto.producto && producto.producto.length >= 3) ){
			alertify.notify( 'El nombre del producto debe ser mayor a 3 caracteres', 'danger', 5 );
		}
		else if( !(producto.idTipoProducto && producto.idTipoProducto > 0) ){
			alertify.notify( 'Seleccione el tipo de producto', 'danger', 5 );	
		}
		else if( !(producto.idMedida && producto.idMedida > 0) ){
			alertify.notify( 'Seleccione la medida', 'danger', 5 );	
		}
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) ){
			alertify.notify( 'La cantidad mínima debe ser mayor a 0', 'danger', 5 );	
		}
		else if( !(producto.cantidadMinima && producto.cantidadMinima > 0) ){
			alertify.notify( 'La cantidad máxima debe ser mayor a 0', 'danger', 5 );	
		}
		else if( !(producto.disponibilidad && producto.disponibilidad > 0) ){
			alertify.notify( 'La disponibilidad debe ser mayor a 0', 'danger', 5 );	
		}
		else{

			$http.post('consultas.php',{
				opcion : "consultaProducto",
				accion : $scope.accion,
				datos  : $scope.producto
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if ( data.respuesta == "success" ) {
					$scope.resetValues( 1 );
					$scope.inventario();
					$scope.dialAdministrarCerrar();
				}
			})
		}
	};


	$scope.inventario = function(){
		$http.post('consultas.php',{
			opcion : 'lstProductos',
			filter : $scope.filter
		}).success(function(data){
			console.log( "::::", data );
			$scope.lstInventario = data.lstProductos;
			$scope.generarPaginacion( data.totalPaginas );
		})
	};


	// REGISTRAR TIPO PRODUCTO
	$scope.registraTipoProdcuto = function( tipoProducto ){
		if ( tipoProducto== undefined || !tipoProducto.length>3 ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese tipo de producto', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaTipoProducto",
				accion:'insert',
				datos: {tipoProducto:tipoProducto}
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.tipoProducto = '';
					$scope.catTipoProducto();
				}
			})
		}
	}

	//registrar medida de producto
	$scope.registraMedidaProducto = function(medidaProducto){
		if ( medidaProducto== undefined || !medidaProducto.length>3 ) {
			alertify.set('notifier','position', 'top-right');
 			alertify.notify('Ingrese la medida para productos', 'warning', 3);
		}else{
			$http.post('consultas.php',{
				opcion:"consultaMedida",
				accion:'insert',
				datos: {medida:medidaProducto}
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.medidaProducto = '';
					$scope.catMedidas();
				}
			})
		}
	}


	$scope.itemProducto = {
		idProducto     : null,
		nombreProducto : '',
		cantidad       : 0,
		disponibilidad : null,
		observacion    : '',
		esIncremento   : true
	};


	// OBTENER VALORES REAJUSTE
	$scope.ingresarReajuste = function( idProducto, nombreProducto, disponibilidad ){
		$scope.itemProducto.idProducto     = idProducto;
		$scope.itemProducto.nombreProducto = nombreProducto;
		$scope.itemProducto.disponibilidad = disponibilidad;
		$scope.dialIngreso.show();
	}


	$scope.retornarTotal = function()
	{
		var itemProducto = $scope.itemProducto, total = 0;

		if( itemProducto.cantidad ){
			if( itemProducto.esIncremento )
				total = parseFloat( itemProducto.disponibilidad ) + parseFloat( itemProducto.cantidad );
			else
				total = parseFloat( itemProducto.disponibilidad ) - parseFloat( itemProducto.cantidad );

		}else
			total = itemProducto.disponibilidad;

		return total;
	}


	$scope.guardaIngreso = function( idProducto, cantidad ){
		var itemProducto = $scope.itemProducto;

		if( !(itemProducto.cantidad && itemProducto.cantidad > 0) ){
 			alertify.notify('La cantidad debe ser mayor a 0', 'warning', 4);
		}
		else if( !(itemProducto.observacion && itemProducto.observacion.length > 20) ){
			alertify.notify('La observación debe tener más de 20 caracteres', 'warning', 4);
		}
		else{
			$http.post('consultas.php',{
				opcion : "consultaReajusteInventario",
				accion : 'insert',
				datos  : $scope.itemProducto
			}).success(function(data){
				console.log( data );
				//alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				if ( data.respuesta == 'success' ) {
					$scope.inventario();
					$scope.resetValues( 2 );
					$scope.dialIngreso.hide();
				}
			})
		}
	};

});