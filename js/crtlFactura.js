app.controller('facturaCtrl', function( $scope, $http, $modal ){
	$scope.dialCliente  = $modal({scope: $scope,template:'dial.nuevo.cliente.html', show: false, backdrop:false, keyboard: false });
	
	$scope.nuevoCliente = function(valor,evento){
		if ( evento == 1 ) {
			$scope.dialCliente.show();
		}else{
			$scope.$parent.buscarCliente(valor,1);
			$scope.dialCliente.show();
		}
	}
	$scope.lstDetalleTicket = [];
	$scope.detalleTicket = function(idOrden){
		console.log(idOrden);
		if (! (idOrden > 0 ) ) {
			alertify.set('notifier','position', 'top-right');
            alertify.notify('Ingrese algún dato para buscar', 'warning', 3);
		}else{
			 $http.post('consultas.php',{
                opcion:"lstDetalleOrdenCliente",
                idOrdenCliente: idOrden
            }).success(function(data){
                console.log(data);
                /*if (encontrados == 1 ) {
                    $scope.lstDetalleTicket = data;
                }else{
                    alertify.set('notifier','position', 'top-right');
                    alertify.notify('Ningún dato localizado', 'warning', 3);
                }*/
            })
		}
	}
});