app.controller('ctrlCaja', function( $scope , $http, $modal, $timeout, $filter ){

	$scope.menuCaja   = 'verCaja';
	$scope.accionCaja = '';
	$scope.accion     = 'insert';

	$scope.caja = {
		cajero            : '',
		cajaAtrasada      : false,
		usuario           : '',
		codigoUsuario     : null,
		idCaja            : null,
		fechaApertura     : angular.copy( $scope.$parent.fechaActual ),
		fechaHoraApertura : null,
		efectivoInicial   : null,
		efectivoFinal     : null,
		efectivoSobrante  : null,
		efectivoFaltante  : null
	};

	$scope.$watch('accionCaja', function(){
		if( $scope.accionCaja == 'cierreCaja' )
		{
			$timeout(function(){
				$('#efectivoFinal').focus();
			}, 150);
		}
	});


	$scope.dialHistorialCaja = $modal({scope: $scope,template:'dial.historialCaja.html', show: false, backdrop: 'static', keyboard: false});

	$scope.abrirDialogoHistorial = function(){
		$scope.dialHistorialCaja.show();
	};

	$scope.fechaCaja     = null;
	$scope.historialCaja = {};
	$scope.cargarHistorialCaja = function( fechaCaja )
	{
		if( fechaCaja )
		{
			var fechaCaja = $filter('date')( fechaCaja,"yyyy-MM-dd");
			$http.post('consultas.php', {
				opcion    : 'historialCaja',
				fechaCaja : fechaCaja
			})
			.success(function(data){
				console.log( data );
				$scope.historialCaja = data || {};
			});	
		}
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho )
	{
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// TECLA F6
		if ( key == 117 ){
			if( $scope.accion == 'insert' ) {
				if( $scope.accionCaja == 'aperturarCaja' )
					$scope.consultaCaja();
				else
					alertify.notify( 'Clic en <b>APERTURAR CAJA</b>', 'info', 3 );
			}
			else if( $scope.accion == 'cierre' ){
				if( $scope.accionCaja == 'cierreCaja' )
					$scope.consultaCaja();
				else
					alertify.notify( 'Clic en <b>CERRAR CAJA</b>', 'info', 3 );
			}
			else if( $scope.accion != 'cierre' && $scope.accion != 'insert' )
				alertify.notify( 'Acción no válida', 'info', 3 );
		}
	});


	($scope.inicioCaja = function(){
		$http.post('consultas.php',{
			opcion : 'inicioCaja'
		})
		.success(function(data){
			console.log( "iniciocaja::: ", data );
			$scope.caja = data;
			$scope.caja.fechaApertura = moment( data.fechaApertura );

			if( !($scope.caja.idCaja > 0) ){
				$scope.accion = 'insert';
				$timeout(function(){
					$('#efectivoInicio').focus();
				}, 150);
			}
			else
				$scope.accion = 'cierre';
		});
	})();

	
	$scope.retornarTotal = function(){
		var total = 0;
		if( $scope.caja.lstDenominaciones ){
			for (var i = 0; i < $scope.caja.lstDenominaciones.length; i++) {
				if( $scope.caja.lstDenominaciones[i].cantidad && $scope.caja.lstDenominaciones[i].cantidad > 0 )
					total += $scope.caja.lstDenominaciones[i].cantidad * $scope.caja.lstDenominaciones[i].denominacion;
			}
			
		}

		return total;
	};

	$scope.consultaCaja = function() {
		var caja = $scope.caja;

		if( $scope.accion == 'cierre' && !( caja.idCaja && caja.idCaja > 0 ) )
			alertify.notify( 'El Cierre de caja no es válido', 'danger', 3 );
		
		else if( $scope.accion == 'insert' && !( $scope.retornarTotal() && $scope.retornarTotal() > 0 ) )
			alertify.notify( 'El <b>EFECTIVO INICIAL</b> debe ser mayor a 0', 'warning', 4 );

		else if( $scope.accion == 'cierre' && !( $scope.retornarTotal() && $scope.retornarTotal() > 0 ) )
			alertify.notify( 'El <b>EFECTIVO FINAL</b> debe ser mayor a 0', 'warning', 4 );

		else if( $scope.accion == 'cierre' && ( ( ( caja.totalIngresos + caja.efectivoInicial ) - caja.totalEgresos )  ) - $scope.retornarTotal() > 0 )
		{
			var faltante = ( ( ( caja.totalIngresos + caja.efectivoInicial ) - caja.totalEgresos ) - $scope.retornarTotal() );
			$scope.caja.agregarFaltante  = true;
			$scope.caja.efectivoFaltante = faltante;
			alertify.notify( 'Existe un faltante de ' + faltante, 'warning', 4 );
		}
		
		else
		{
			$scope.$parent.showLoading( 'Guardando...' );
			$http.post('consultas.php',{
				opcion : 'consultaCaja',
				accion : $scope.accion,
				data   : $scope.caja,
				total  : $scope.retornarTotal()
			})
			.success(function(data){
				alertify.set('notifier','position', 'top-right');
				alertify.notify( data.mensaje, data.respuesta, data.tiempo );
				if( data.respuesta == 'success' ){
					$scope.accionCaja = '';
					$scope.inicioCaja();
				}
				$scope.$parent.hideLoading();
			}).error(function(data){
				$scope.$parent.hideLoading();
			});
		}
	};

	$scope.movimiento = {
		idTipoMovimiento : '3',
		motivo           : '',
		monto            : '',
	};

	$scope.guardarMovimiento = function() {
		if( !( $scope.movimiento.idTipoMovimiento > 0 ) )
			alertify.notify( 'Tipo Movimiento no definido', 'danger', 3 );
		
		else if( !( $scope.movimiento.monto > 0 ) )
			alertify.notify( 'Monto debe ser mayor a cero', 'warning', 4 );

		else if( !( $scope.movimiento.motivo.length > 3 ) )
			alertify.notify( 'Motivo muy corto', 'warning', 4 );

		else{
			$http.post('consultas.php',{
				opcion     : 'guardarMovimientoCaja',
				movimiento : {
					accion           : 'insert',
					idTipoMovimiento : $scope.movimiento.idTipoMovimiento,
					motivo           : $scope.movimiento.motivo,
					monto            : $scope.movimiento.monto,
				}
			})
			.success(function(data){
				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 5 );

				if( data.respuesta == 'success' )
				{
					$scope.getMovimientos();
					$scope.movimiento = {
						idTipoMovimiento : '3',
						motivo           : '',
						monto            : '',
					};
				}
			});
		}
	};

	$scope.lstMovimientos = [];
	$scope.totalIngresos  = 0;
	$scope.totalEgresos   = 0;
	($scope.getMovimientos = function() {
		$scope.lstMovimientos = [];
		$scope.totalIngresos  = 0;
		$scope.totalEgresos   = 0;
		$http.post('consultas.php',{
			opcion : 'lstMovimientos'
		})
		.success(function(data){
			$scope.lstMovimientos = data.lstMovimientos;
			$scope.totalIngresos  = data.ingresos;
			$scope.totalEgresos   = data.egresos;
		});
	})();
});