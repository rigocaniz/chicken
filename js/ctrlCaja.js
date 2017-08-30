app.controller('ctrlCaja', function( $scope , $http, $modal, $timeout ){

	$scope.menuCaja   = 'verCaja';
	$scope.accionCaja = '';
	$scope.accion     = 'insert';

	$scope.caja = {
		cajero           : '',
		usuario          : '',
		operador         : '',
		idCaja           : null,
		fechaApertura    : angular.copy( $scope.$parent.fechaActual ),
		efectivoInicial  : null,
		efectivoFinal    : null,
		efectivoSobrante : null,
		efectivoFaltante : null
	};

	// TECLA PARA ATAJOS RAPIDOS
	$scope.$on('keyPress', function( event, key, altDerecho )
	{
		console.log( event, key, altDerecho );
		// SI SE ESTA MOSTRANDO LA VENTANA DE CARGANDO
		if ( $scope.$parent.loading )
			return false;

		// TECLA A
		if ( altDerecho && key == 65 ){
			if( $scope.accion == 'insert' )
			{
				
			}
		}
	});


	($scope.inicioCaja = function(){
		$http.post('consultas.php',{
			opcion : 'inicioCaja'
		})
		.success(function(data){
			console.log(data);
			$scope.caja.cajero           = data.cajero;
			$scope.caja.operador         = data.operador;

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

			if( !($scope.caja.idCaja > 0) )
				$scope.accion = 'insert';
			else
				$scope.accion = 'cierre';

			$scope.accionCaja = 'aperturarCaja';
		});
	})();


	$scope.consultaCaja = function()
	{
		console.log( $scope.caja );
		var caja = $scope.caja;
		/*
		$http.post('consultas.php',{
			opcion : 'consultaCaja',
			accion : $scope.accion,
			data   : $scope.caja
		})
		.success(function(data){
			console.log(data);
			alertify.set('notifier','position', 'top-right');
			alertify.notify( data.mensaje, data.respuesta, data.tiempo );
			if( data.respuesta == 'success' )
				$scope.inicioCaja();
		});
		*/

	};

});