/****rutas para menu opciones*****/
app.config(function($routeProvider) {
	$routeProvider
	.when('/',{
		templateUrl:'view/inicio.php'
	})
	.when('/cliente',{
		templateUrl:'view/cliente.php',
		controller:'clienteCtrl'
	})
	.when('/inventario',{
		templateUrl:'view/inventario.php',
		controller:'inventarioCtrl'
	})
    .when('/nuevoEdita/:evento/:id?',{
        templateUrl:'view/nuevoEdita.php',
        controller:'nuevoEditaCtrl'
    })
    .when('/admin',{
        templateUrl:'view/admin.php',
        controller:'crtlAdmin'
    })
    .when('/orden',{
        templateUrl:'view/orden.php',
        controller:'crtlOrden'
    })
    .when('/evento',{
        templateUrl:'view/evento.php',
        controller:'crtlEvento'
    })
    .when('/adminOrden',{
        templateUrl:'view/adminOrden.php',
        controller:'crtlAdminOrden'
    })
    .when('/reporte',{
        templateUrl:'view/reporte.php',
        controller:'reporteCtrl'
    })
    .when('/mantenimiento',{
        templateUrl:'view/mantenimiento.php',
        controller:'crtlMantenimiento'
    })
    .when('/factura/:idOrdenCliente?',{
        templateUrl:'view/factura.php',
        controller:'facturaCtrl'
    })
    .when('/caja',{
        templateUrl : 'view/caja.php',
        controller  : 'ctrlCaja'
    })

    .when('/tendencia',{
        templateUrl : 'view/tendencia.php',
        controller  : 'ctrlTendencia'
    })
    .otherwise({
        redirectTo:'/'
    });

})
.config(function($tooltipProvider) {
    angular.extend($tooltipProvider.defaults, {
        trigger : 'hover',
        html    : true
    });
})
.config(function($popoverProvider) {
    angular.extend($popoverProvider.defaults, {
        trigger : 'hover',
        html    : true
    });
});

