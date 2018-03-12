app.controller('ctrlTendencia', function( $scope, $http, $timeout, $modal, $location, $filter ){
	$scope.tipoMenu  = 'menu';
	$scope.menuTen   = "fechaMenu";
	$scope.paraFecha = new Date();
	$scope.deFecha   = new Date( $scope.paraFecha.getFullYear(), $scope.paraFecha.getMonth(), 1 );
	$scope.lstMenu   = [];
	$scope.lstCombo  = [];
	$scope.todos 	 = '1';
	$scope.busqueda  = '';
	$scope.menu = {
		idMenu      : '',
		idCombo     : '',
		descripcion : '',
		codigo      : '',
	};

	$http.post('consultas.php', { opcion:'lstMenu', idTipoMenu:0 })
	.success(function (data) { $scope.lstMenu = data; });
	$http.post('consultas.php', { opcion:'lstCombo' })
	.success(function (data) { $scope.lstCombo = data; });


	$scope.sel = function ( idMenu, idCombo, descripcion, codigo ) {
		$scope.menu = {
			idMenu      : angular.copy( idMenu ),
			idCombo     : angular.copy( idCombo ),
			descripcion : angular.copy( descripcion ),
			codigo      : angular.copy( codigo ),
		};
	};

	$scope.topFechaMenu = function () {
		if ( $scope.$parent.loading )return;

		var deFecha = $filter('date')( $scope.deFecha, 'yyyy-MM-dd' );
		var paraFecha = $filter('date')( $scope.paraFecha, 'yyyy-MM-dd' );

		if ( deFecha.length != 10 || paraFecha.length != 10 )
			return;

		if ( $scope.todos == 0 && !( $scope.menu.idMenu > 0 || $scope.menu.idCombo > 0 ) )
			return;

		$scope.$parent.loading = true;
		$http.post('consultas.php', { 
			opcion    : 'topFechaMenu',
			tipoMenu  : $scope.tipoMenu,
			deFecha   : deFecha,
			paraFecha : paraFecha,
			idMenu    : $scope.menu.idMenu,
			idCombo   : $scope.menu.idCombo,
		})
		.success(function (data) {
			$scope.$parent.loading = false;
			$scope.renderChar( data.lstResultado );

			if ( data.respuesta != 'success' )
				alertify.notify( ( data.mensaje || data ), ( data.respuesta || 'danger' ), 4 );
		});
	};

	// RENDER DE DATOS
	$scope.renderChar = function(lst) {
		while( window.charFechaMenu.series[ 0 ] != undefined )
			window.charFechaMenu.series[ 0 ].remove()

		for (var ix = 0; ix < lst.length; ix++)
			window.charFechaMenu.addSeries( lst[ ix ] );
	};

	window.charFechaMenu = new Highcharts.chart('container', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: [
	            '',
	        ],
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Cantidad'
	        }
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0,
		        dataLabels: {
		            enabled: true
		        }
	        }
	    },
	    series: [],
	    credits:false
	});
});