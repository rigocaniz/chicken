app.controller('crtlAdmin', function( $scope , $http, $modal, $timeout ){

	$scope.adminMenu       = 'ajustes';
	$scope.accion          = 'insert';
	$scope.filtroUsuario   = 'activos';

	if( document.getElementById("dial.adminUsuario.html") )
		$scope.dialAdminUsuario = $modal({scope: $scope,template:'dial.adminUsuario.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.cambiarEstado.html") )
		$scope.dialCambiarEstadoUsuario = $modal({scope: $scope,template:'dial.cambiarEstado.html', show: false, backdrop: 'static', keyboard: false});
	if( document.getElementById("dial.modulosPerfil.html") )
		$scope.dialModulosPerfil = $modal({scope: $scope,template:'dial.modulosPerfil.html', show: false, backdrop: 'static', keyboard: false})

	$scope.$watch( 'filtroUsuario', function( _old, _new ){
		if( ( _old != _new ) && $scope.adminMenu == 'usuarios' )
			$scope.cargarLstUsuarios();
	})
	$scope.lstEstadoUsuario = [];
	$scope.cargarLstEstadoUsuario = function(){
		$http.post('consultas.php',{
			opcion : 'lstEstadoUsuario'
		}).success(function(data){
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
		$timeout(function(){
			$('#nombreUsuario').focus();
		},125);
	};

	$scope.editarUsuario = function( usuario ){
		$scope.accion  = 'update';
		$scope.usuario = angular.copy( usuario );
		$scope.usuario.codigo = parseInt( $scope.usuario.codigo );
		$scope.dialAdminUsuario.show();
		$timeout(function(){
			$('#nombreUsuario').focus();
		},125);
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

	$scope.documento = [];
	$scope.getDocumento = function () {
		if ( !( $scope.idDocumento > 0 ) )return false;
		$scope.documento = [];

		$http.post('consultas.php', {
			opcion      : 'getDocumento',
			idDocumento : $scope.idDocumento
		})
		.success(function (data) {
			if ( data.getDocumento.length )
				$scope.documento = data.getDocumento;
		});
	};

	$scope.guardarDocumento = function () {
		var lstCampos   = [],
			lstColumnas = [];

		for (var ixC = 0; ixC < $scope.documento.length; ixC++)
		{
			var lstCambio = [],
				campo     = $scope.documento[ ixC ];

			if ( campo.x > 0 && campo.x != campo.old.x )
				lstCambio.push({ cmp: 'x', val: campo.x });

			if ( campo.y > 0 && campo.y != campo.old.y )
				lstCambio.push({ cmp: 'y', val: campo.y });

			if ( campo.fontSize > 0 && campo.fontSize != campo.old.fontSize )
				lstCambio.push({ cmp: 'fontSize', val: campo.fontSize });

			if ( campo.mostrarTitulo != campo.old.mostrarTitulo )
				lstCambio.push({ cmp: 'mostrarTitulo', val: campo.mostrarTitulo });

			if ( campo.idTipoItem == 2 )
			{
				for (var ixE = 0; ixE < campo.encabezado.length; ixE++) {
					var columna = campo.encabezado[ ixE ];
					if ( columna.width > 0 && columna.width != columna.old.width )
						lstColumnas.push({
							idColumnaLista : columna.idColumnaLista,
							val            : columna.width,
						});
				}
			}

			if ( lstCambio.length )
				lstCampos.push({
					idDocumentoDetalle : campo.idDocumentoDetalle,
					lstCambio          : lstCambio,
				});
		}

		$http.post('consultas.php', {
			opcion      : 'guardarDocumento',
			lstCampos   : lstCampos,
			lstColumnas : lstColumnas,
		})
		.success(function (data) {
			alertify.notify( data.mensaje, data.respuesta, 4 );
		});
	};

	$scope.$watch('idDocumento', function (_new) {
		$scope.getDocumento();
	});

	$scope.catDocumentos = [];
	$scope.idDocumento   = 0;
	($scope.inicio = function(){
		$scope.cargarLstEstadoUsuario();
		$scope.cargarLstPerfiles();
		$scope.cargarLstUsuarios();
		$http.post('consultas.php', { opcion: 'catDocumentos' })
		.success(function (data) {
			$scope.catDocumentos = data.catDocumentos;
			$timeout(function () {
				$scope.idDocumento = $scope.catDocumentos[ 0 ].idDocumento;
			});
		});
	})();
});