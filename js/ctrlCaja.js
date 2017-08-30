app.controller('ctrlCaja', function( $scope , $http, $modal, $timeout ){

	$scope.menuCaja   = 'verCaja';
	$scope.accionCaja = '';
	$scope.accion     = 'insert';

	$scope.caja = {
		cajero           : '',
		usuario          : '',
		codigoUsuario    : null,
		idCaja           : null,
		fechaApertura    : angular.copy( $scope.$parent.fechaActual ),
		efectivoInicial  : null,
		efectivoFinal    : null,
		efectivoSobrante : null,
		efectivoFaltante : null
	};

	$scope.$watch('accionCaja', function(){
		if( $scope.accionCaja == 'cierreCaja' )
		{
			$timeout(function(){
				$('#efectivoFinal').focus();
			}, 150);
		}
	});

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho )
	{
		console.log( event, key, altDerecho );
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// TECLA A
		if ( altDerecho && key == 65 ){
			if( $scope.accion == 'insert' ) {
				if( $scope.accionCaja == 'aperturarCaja' )
					$scope.consultaCaja();
				else
					alertify.notify( 'Clic en <b>APERTURAR CAJA</b>', 'info', 3 );
			}
			else if( $scope.accion == 'cierre' || $scope.accion != 'insert' && $scope.accionCaja == 'aperturarCaja' )
				alertify.notify( 'Acción no válida', 'info', 3 );
		}
		// TECLA C
		else if ( altDerecho && key == 67 ){
			if( $scope.accion == 'cierre' ){
				if( $scope.accionCaja == 'cierreCaja' )
					$scope.consultaCaja();
				else
					alertify.notify( 'Clic en <b>CERRAR CAJA</b>', 'info', 3 );
			}
			else if( $scope.accion == 'insert' )
				alertify.notify( 'Acción no válida', 'info', 3 );
		}
	});


	($scope.inicioCaja = function(){
		$http.post('consultas.php',{
			opcion : 'inicioCaja'
		})
		.success(function(data){
			console.log(data);
			$scope.caja.cajero           = data.cajero;
			$scope.caja.idCaja           = data.dataCaja.idCaja;
			$scope.caja.usuario          = data.dataCaja.usuario;
			$scope.caja.codigoUsuario    = data.dataCaja.codigoUsuario;
			$scope.caja.idEstadoCaja     = data.dataCaja.idEstadoCaja;
			$scope.caja.estadoCaja       = data.dataCaja.estadoCaja;
			$scope.caja.fechaApertura    = moment( data.dataCaja.fechaApertura );
			$scope.caja.efectivoInicial  = data.dataCaja.efectivoInicial;
			$scope.caja.efectivoFinal    = data.dataCaja.efectivoFinal;
			$scope.caja.efectivoSobrante = data.dataCaja.efectivoSobrante;
			$scope.caja.efectivoFaltante = data.dataCaja.efectivoFaltante;
			$scope.caja.idCaja           = data.dataCaja.idCaja;

			if( !($scope.caja.idCaja > 0) ){
				$scope.accion = 'insert';
				$timeout(function(){
					$('#efectivoInicio').focus();
				}, 150);
			}
			else{
				$scope.accion = 'cierre';
			}
		});
	})();


	$scope.consultaCaja = function() {
		console.log( "acceder" );
		var caja = $scope.caja;
		if( $scope.accion == 'cierre' && !( caja.idCaja && caja.idCaja > 0 ) )
			alertify.notify( 'El Cierre de caja no es válido', 'danger', 3 );
		
		else if( $scope.accion == 'insert' && !( caja.efectivoInicial && caja.efectivoInicial > 0 ) )
			alertify.notify( 'El EFECTIVO INICIAL debe ser mayor a 0', 'warning', 4 );

		else if( $scope.accion == 'cierre' && !( caja.efectivoFinal && caja.efectivoFinal > 0 ) )
			alertify.notify( 'El EFECTIVO FINAL debe ser mayor a 0', 'warning', 4 );

		else{
			$http.post('consultas.php',{
				opcion : 'consultaCaja',
				accion : $scope.accion,
				data   : $scope.caja
			})
			.success(function(data){
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				if( data.respuesta == 'success' ){
					$scope.accionCaja = '';
					$scope.inicioCaja();
				}
			});
		}
	};

});