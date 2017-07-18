app.controller('nuevoEditaCtrl', function( $scope , $http, $modal, $routeParams , $timeout){
	//VALIDACIONES SEGUN EVENTO ENVIADO
	$scope.evento = $routeParams.evento;
	$scope.accion = 'insert';
	$scope.id     = $routeParams.id;
	if ($scope.id > 0) {
		$scope.accion = 'update';
	}

	if( $scope.evento == 'producto' ){
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

		$scope.catTipoProducto = function(){
			$http.post('consultas.php',{
				opcion:'catTipoProducto'
			}).success(function(data){
				$scope.lstTipoProducto = data;
			})
		}   

		$scope.catMedidas = function(){
			$http.post('consultas.php',{
				opcion:'catMedidas'
			}).success(function(data){
				$scope.lstMedidas = data;
			})
		}

		$scope.catTipoProducto();
		$scope.catMedidas();  

		if ($scope.id >0 ) {
			$http.post('consultas.php',{
				opcion:'cargarProducto',
				idProducto:$scope.id
			}).success(function(data){
				//console.log(data);
				$scope.producto = data;
			})
		}

		//registrar nuevo producto
		$scope.registraNuevoProducto = function(){
			if ( $scope.formProducto.$invalid == true ) {
				alertify.set('notifier','position', 'top-right');
	 			alertify.notify('Ingrese todos los datos requeridos identificados con borde de color verde', 'warning', 3);
			}else{
				$http.post('consultas.php',{
					opcion:"consultaProducto",
					accion: $scope.accion,
					datos: $scope.producto
				}).success(function(data){
					console.log(data);
					//mensaje aca
					alertify.set('notifier','position', 'top-right');
					alertify.notify(data.mensaje,data.respuesta, data.tiempo);
					if ( data.respuesta == "success" ) {
						$scope.cancelaNuevoProducto();
					}
				})
			}
		}
		//cancelar registro de nuevo producto
		$scope.cancelaNuevoProducto = function(){
			$scope.producto = {};
		}
	}
	
	if ($scope.evento=='menu') {
		$scope.menu = {
			'menu'          : '',
			'imagen'        : '',
			'descripcion'   : '',
			'idEstadoMenu'  : 1,
			'idDestinoMenu' : null
		}

		$scope.catDestinoMenu = function(){
			$http.post('consultas.php',{
				opcion:'catDestinoMenu'
			}).success(function(data){
				$scope.lstDestinoMenu = data;
			})
		}

		$scope.catEstadoMenu = function(){
			$http.post('consultas.php',{
				opcion:'catEstadoMenu'
			}).success(function(data){
				$scope.lstEstadoMenu = data;
			})
		}

		$scope.catEstadoMenu();
		$scope.catDestinoMenu();

		if ($scope.id >0 ) {
			$http.post('consultas.php',{
				opcion:'cargarMenu',
				idMenu:$scope.id
			}).success(function(data){
				//console.log(data);
				$scope.menu = data;
			})
		}
		//registrar menu
		$scope.registraMenu = function(){
			console.log($scope.menu);
			if ( $scope.formMenu.$invalid == true ) {
				alertify.set('notifier','position', 'top-right');
	 			alertify.notify('Ingrese los datos solicitados', 'warning', 3);
			}else{
				$http.post('consultas.php',{
					opcion:"consultaMenu",
					accion:$scope.accion,
					datos: $scope.menu
				}).success(function(data){
					alertify.set('notifier','position', 'top-right');
	 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
					if ( data.respuesta == 'success' ) {
						$scope.menu = {};
					}
				})
			}
		}
	}
	
	if ( $scope.evento == 'combo' ) {
		$scope.combo = {
			'combo'        : '',
			'imagen'       : '',
			'descripcion'  : '',
			'idEstadoMenu' : 1
		}

		$scope.catEstadoMenu = function(){
			$http.post('consultas.php',{
				opcion:'catEstadoMenu'
			}).success(function(data){
				$scope.lstEstadoMenu = data;
			})
		}

		$scope.catEstadoMenu();

		if ($scope.id >0 ) {
			$http.post('consultas.php',{
				opcion:'cargarCombo',
				idCombo:$scope.id
			}).success(function(data){
				//console.log(data);
				$scope.combo = data;
			})
		}
		//registrar combo
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
	}

});