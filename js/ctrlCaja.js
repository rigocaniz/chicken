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
			}
			$scope.accionCaja = 'aperturarCaja';
		});
	})();


	$scope.consultaCaja = function()
	{
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

	};

});