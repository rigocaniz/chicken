app.controller('crtlAdmin', function( $scope , $http, $modal, $timeout ){

	$scope.adminMenu       = 'ajustes';
	$scope.accion          = 'insert';
	$scope.filtroUsuario   = 'activos';

	$scope.dialAdminUsuario         = $modal({scope: $scope,template:'dial.adminUsuario.html', show: false, backdrop: 'static', keyboard: false});
	$scope.dialCambiarEstadoUsuario = $modal({scope: $scope,template:'dial.cambiarEstado.html', show: false, backdrop: 'static', keyboard: false});
	$scope.dialModulosPerfil        = $modal({scope: $scope,template:'dial.modulosPerfil.html', show: false, backdrop: 'static', keyboard: false})
	
	
	$scope.$watch( 'filtroUsuario', function( _old, _new ){
		if( ( _old != _new ) && $scope.adminMenu == 'usuarios' )
			$scope.cargarLstUsuarios();
	})
	$scope.lstEstadoUsuario = [];
	$scope.cargarLstEstadoUsuario = function(){
		$http.post('consultas.php',{
			opcion : 'lstEstadoUsuario'
		}).success(function(data){
			console.log( data );
			$scope.lstEstadoUsuario = data;
		});
	};

	$scope.dataPerfil = {};
	$scope.datosPerfil = function( perfil ){
		$scope.dataPerfil = angular.copy( perfil );
		$scope.dataPerfil.lstModulosPerfil = [];
		$scope.consultarModulosPerfil( $scope.dataPerfil.idPerfil );
		$scope.dialModulosPerfil.show();
	};

	$scope.consultarModulosPerfil = function( idPerfil ){
		$http.post('consultas.php',{
			opcion   : 'consultarModulosPerfil',
			idPerfil : idPerfil
		}).success(function(data){
			console.log( data );
			$scope.dataPerfil.lstModulosPerfil = data || [];
		});
	};


	$scope.asignarModulo = function( idPerfil, idModulo, asignado ){
		$scope.$parent.showLoading( 'Guardando...' );
		$http.post('consultas.php',{
			opcion   : 'asignarModulo',
			idPerfil : idPerfil,
			idModulo : idModulo,
			asignado : asignado
		}).success(function(data){
			console.log( data );
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );
			if( data.respuesta == 'success' )
				$scope.consultarModulosPerfil( idPerfil );
			
			$scope.$parent.hideLoading();
		});
	};


	$scope.actualizarEstadoUsuario = function(){
		
		$scope.$parent.showLoading( 'Guardando...' );

		$http.post('consultas.php',{
			opcion : 'actualizarEstadoUsuario',
			data   : $scope.user
		}).success(function(data){
			console.log( data );
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );
			if( data.respuesta == 'success' ) {
				$scope.filtroUsuario = 'activos';
				$scope.user = {};
				$scope.dialCambiarEstadoUsuario.hide();
			}

			$scope.$parent.hideLoading();
		});
	};

	$scope.user = {};
	$scope.cambiarEstadoUsuario = function( usuario ){
		$scope.user = angular.copy( usuario );
		$scope.dialCambiarEstadoUsuario.show();
	};


	$scope.lstPerfiles = [];
	$scope.parametro   = null;
	$scope.cargarLstPerfiles = function(){
		$http.post('consultas.php',{
			opcion : 'lstPerfiles'
		}).success(function(data){
			$scope.lstPerfiles = data;
		});

		$scope.parametro = null;
		$http.post('consultas.php',{
			opcion      : 'getParams',
			idParametro : 'gruposCocina'
		}).success(function(data){
			if ( data != null )
				$scope.parametro = data;
		});
	};

	$scope.cargarLstUsuarios = function(){
		$http.post('consultas.php',{
			opcion : 'cargarLstUsuarios',
			filtro : $scope.filtroUsuario
		}).success(function(data){
			console.log( data );
			$scope.lstUsuarios = data;
		});
	};

	$scope.guardarParametro = function () {
		if ( $scope.parametro.idParametro == undefined )
			alertify.notify( 'Parámetro no válido', 'warning', 4 );

		else if ( $scope.parametro.valor == undefined )
			alertify.notify( 'Valor no válido', 'warning', 4 );

		else{
			$http.post('consultas.php',{
				opcion      : 'setParams',
				idParametro : $scope.parametro.idParametro,
				valor       : $scope.parametro.valor,
			}).success(function(data){
				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 4 );
			});
		}
	};

	($scope.inicio = function(){
		$scope.cargarLstEstadoUsuario();
		$scope.cargarLstPerfiles();
		$scope.cargarLstUsuarios();
	})();


	$scope.accion = 'insert';
	$scope.editarPerfil = function( perfil ){
		$scope.accion = 'update';
		$scope.perfil = angular.copy( perfil );
	};


	$scope.consultaPerfil = function(){
		if ( $scope.$parent.loading )
			return false;

		var perfil = $scope.perfil;

		if( $scope.accion == 'update' && !(perfil.idPerfil && perfil.idPerfil > 0 ) )
			alertify.notify( 'No. de perfil no es válido', 'warning', 3 );

		else if( !(perfil.perfil && perfil.perfil.length > 3 ) )
			alertify.notify( 'La descripcion del perfil debe ser mayor a 3 caracteres', 'warning', 5 );

		else {
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : "consultaPerfil",
				accion : $scope.accion,
				datos  : $scope.perfil
			}).success(function(data){
				alertify.set('notifier','position', 'top-right');
 				alertify.notify(data.mensaje, data.respuesta, data.tiempo);
				$scope.$parent.hideLoading();
				if ( data.respuesta == 'success' ) {
					$scope.cargarLstPerfiles();
					$scope.resetValores( 'perfil' );
				}
			})
		}
	};


	$scope.agregarUsuario = function()
	{
		$scope.accion = 'insert';

		$scope.usuario = {
			usuario         : '',
			idEstadoUsuario : 1,
			idPerfil        : 1,
			codigo          : null,
			clave           : '',
			nombres         : '',
			apellidos       : ''
		};
		$scope.dialAdminUsuario.show();
	};

	$scope.editarUsuario = function( usuario ){
		$scope.accion  = 'update';
		$scope.usuario = angular.copy( usuario );
		$scope.usuario.codigo = parseInt( $scope.usuario.codigo );
		$scope.dialAdminUsuario.show();
	};


	// CONSULTA USUARIO => INSERT // UPDATE
	$scope.consultaUsuario = function()
	{
		var usuario = $scope.usuario;

		if( !(usuario.idPerfil && usuario.idPerfil > 0 ) )
			alertify.notify( 'Seleccione el perfil del Usuario', 'warning', 4 );

		else if( !(usuario.usuario) )
			alertify.notify( 'Ingrese usuario válido, sin espacios en blanco', 'warning', 5 );

		else if( !(usuario.usuario.length >=8 ) )
			alertify.notify( 'El nombre de usuario debe tener más de 7 caracteres', 'warning', 5 );

		else if( !(usuario.codigo ) )
			alertify.notify( 'Ingrese CÓDIGO de usuario válido, sin espacios en blanco', 'warning', 5 );

		else if( !(usuario.codigo > 0 ) )
			alertify.notify( 'El código del usuario debe ser mayor a 0', 'warning', 4 );

		else if( !(usuario.nombres && usuario.nombres.length >=3 ) )
			alertify.notify( 'El nombre de la persona debe tener más de 2 caracteres', 'warning', 5 );

		else if( !(usuario.apellidos && usuario.apellidos.length >=2 ) )
			alertify.notify( 'Los apellidos de la persona debe tener más de 1 caracter', 'warning', 5 );

		else
		{
		
			$scope.$parent.showLoading( 'Guardando...' );

			$http.post('consultas.php',{
				opcion : 'consultaUsuario',
				accion : $scope.accion,
				data   : $scope.usuario
			}).success(function(data){
				console.log( data );
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );

				if( data.respuesta == 'success' )
				{
					$scope.cargarLstUsuarios();
					$scope.resetValores( 'usuario' );
					$scope.dialAdminUsuario.hide();
				}

				$scope.$parent.hideLoading();
			});

		}
	};

	// CONSULTA USUARIO => INSERT // UPDATE
	$scope.resetearClave = function( usuario ){
		$http.post('consultas.php',{
			opcion  : 'resetearClave',
			usuario : usuario
		}).success(function(data){
			console.log( data );
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );
		});
	};

	$scope.resetValores = function( accion ){
		$scope.accion = 'insert';

		if( accion == 'usuario' ) {
			$scope.usuario = {
				usuario         : '',
				idEstadoUsuario : 1,
				idPerfil        : 1,
				codigo          : null,
				clave           : '',
				nombres         : '',
				apellidos       : ''
			};
		}
		else if( accion == 'perfil' )
		{
			$scope.perfil = {
				perfil: ''
			}
		}
	};

});