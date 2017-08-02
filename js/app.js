var app = angular.module('restaurante',['ngRoute','mgcrea.ngStrap','angularUtils.directives.dirPagination'],
	["$provide", function($provide) {//validar que el datepicker sea español
    var PLURAL_CATEGORY = {ZERO: "zero", ONE: "one", TWO: "two", FEW: "few", MANY: "many", OTHER: "other"};
    $provide.value("$locale", {
          "DATETIME_FORMATS": {
             "AMPMS": [
             "AM",
             "PM"
             ],
             "DAY": [
             "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
             ],
             "MONTH": ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre",
               "octubre","noviembre","diciembre"
             ],
             "SHORTDAY": [
               "Dom","Lun","Mar","Mie","Jue","Vie","Sab"
             ],
             "SHORTMONTH": [
               "Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sept","Oct","Nov","Dic"
             ],
             "fullDate"  : "EEEE d MMMM y",
             "longDate"  : "d MMMM y",
             "medium"    : "d MMM y HH:mm:ss",
             "mediumDate": "d MMM y",
             "mediumTime": "HH:mm:ss",
             "short"     : "dd/MM/yy HH:mm",
             "shortDate" : "dd/MM/yyyy",
             "shortTime" : "HH:mm:ss",
          },

        "NUMBER_FORMATS": {
        "CURRENCY_SYM": ",",
        "DECIMAL_SEP": ".",
        "GROUP_SEP": ",",
        "PATTERNS": [
         {
           "gSize": 3,
           "lgSize": 3,
           "macFrac": 0,
           "maxFrac": 3,
           "minFrac": 0,
           "minInt": 1,
           "negPre": "-",
           "negSuf": "",
           "posPre": "",
           "posSuf": ""
         },
         {
           "gSize": 3,
           "lgSize": 3,
           "macFrac": 0,
           "maxFrac": 2,
           "minFrac": 2,
           "minInt": 1,
           "negPre": "(",
           "negSuf": "\u00a0\u00a4)",
           "posPre": "",
           "posSuf": "\u00a0\u00a4"
         }
        ]
        },
        "id": "es-es",
        "pluralCat": function (n) { 
         if (n >= 0 && n <= 2 && n != 2) {   return PLURAL_CATEGORY.ONE;  }  return PLURAL_CATEGORY.OTHER;
        }
    });
}]);
/****rutas para menu opciones*****/
app.config(function($routeProvider) {
	$routeProvider
	.when('/',{
		templateUrl:'view/inicio.php',
		controller:'inicioCtrl'
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
	.otherwise({
        redirectTo:'/'
    });
});
/****controladores****/
app.controller('restauranteCtrl', function($scope){//controlador principal
});

app.controller('inicioCtrl', function($scope, $rootScope){//controlador principal
  // CAPTURA TECLA PARA ATAJOS RAPIDOS
  $scope.pressKey = function ( key ) {
    $rootScope.$broadcast('keyPress', key );
  };
});

