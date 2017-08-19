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
        controller:'mantenimientoCtrl'
    })
    .when('/factura',{
        templateUrl:'view/factura.php',
        controller:'facturaCtrl'
    })	.otherwise({
        redirectTo:'/'
    });

});

app.directive('tooltip', function(){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            $(element).hover(function(){
                $(element).tooltip('show');
            }, function(){
                $(element).tooltip('hide');
            });
        }
    };
});

/* CONEXION A SERVIDOR DE NOTIFICACIONES */
var socket = io.connect('http://192.168.0.140:8080', { 'forceNew': true });
//var socket = io.connect('http://127.0.0.1:8080', { 'forceNew': true });


/****CONTROLADORES****/

// CONTROLADOR PRINCIPAL
app.controller('inicioCtrl', function($scope, $rootScope, $timeout, $http, $modal ){
    // LISTEN INFO NODE
    socket.on('mensaje', function(data) {  
        console.log(data);
    });

    socket.on('info', function(data) {  
        console.log( data );
        $rootScope.$broadcast( 'infoNode', data );
    });

    $scope.difLocalServer = 0;

    // DIFERENCIA DE TIEMPO RESPECTO AL SERVIDOR
    $http.post('consultas.php', { opcion : 'timeNow' })
    .success(function (data) {
        $scope.difLocalServer = moment( new Date() ).diff( moment( data.timeNow ) );
    });

    // MUESTRA TIEMPO TRANSCURRIDO
    $scope.tiempoTranscurrido = function ( tiempo ) {
        var de   = moment( new Date() ).add( $scope.difLocalServer );
        var para = moment( tiempo );

        return de.to( para );
    };

    $scope.formatoFecha = function function_name( fecha, formato ) {
        var _for = formato || "D [de] MMMM [de] YYYY";
        var txt = moment( fecha ).format( _for )

        return txt;
    };

    // CAPTURA TECLA PARA ATAJOS RAPIDOS
    $scope.pressKey = function ( key ) {
        $rootScope.$broadcast('keyPress', key );
    };

    $scope.imagen = {
        id     : null,
        tipo   : '',
        accion : ''
    };

    $scope.resetImagen = function(){
        $scope.imagen = {
            id     : null,
            tipo   : '',
            accion : ''
        };
        $( '#subirImagen' ).modal( 'hide' );
        $(".close.fileinput-remove").click();
    };

    // ASIGNAR VALOR OBJ IMAGEN
    $scope.asignarValorImagen = function( id, tipo ){
        $scope.imagen.id     = id;
        $scope.imagen.tipo   = tipo;
        $( '#subirImagen' ).modal( 'show' );
    };

    // IMAGEN
    $("#imagen").fileinput({
        language              : 'es',
        uploadUrl             : "upload.php",
        showRemove            : true,
        showUpload            : true,
        autoReplace           : true,
        maxFileCount          : 1,
        cache                 : false,
        maxFileSize           : 1024,
        uploadAsync           : false,
        allowedFileExtensions : ['jpeg','jpg','png'],
        uploadExtraData : function() {
            return { 
                id   : $scope.imagen.id,
                tipo : $scope.imagen.tipo
            };
        }

    })
    .on('filebatchpreupload', function( event, data, previewId, index ) {
        console.log( "1", data.response );
    })
    .on('fileuploaded', function( event, data, previewId, index ) {
        console.log( "2", data.response );
        if( data.response.respuesta ){
            $rootScope.$broadcast('cargarLista', data.response.accion );
            $scope.resetImagen();
        }
    })
    .on('filebatchuploadsuccess', function( event, data, previewId, index ) {
        console.log( "3", data.response );
        if( data.response.respuesta ){
            $rootScope.$broadcast('cargarLista', data.response.accion );
            $scope.resetImagen();
        }

    })
    .on('filebatchuploaderror', function( event, data, msg ) {
        console.log( "4", data );
    })
    .on('fileuploaderror', function( event, data, msg ) {
        console.log( "5", data );
    });

  
    $(function () {
        $('#cargando').removeAttr("style"); 
    });

    $scope.loading = false;

    /*CLIENTES*/
    $scope.clienteMenu = 1;
    $scope.catTipoCliente = function(){
        $http.post('consultas.php',{
            opcion:'catTiposCliente'
        }).success(function(data){
            $scope.lstTipoCliente = data;
        })
    };  

    $scope.catTipoCliente();

    $scope.cliente  = {
        'nit'       : null,
        'nombre'    : '',
        'cui'       : null,
        'correo'    : '',
        'telefono'  : null,
        'direccion' : '',
        'idTipoCliente' : null,
    };  

    $scope.cancelarCliente = function(){
        $scope.cliente = {};
    }

    $scope.guardarCliente = function(){
        if ($scope.cliente.nombre =='' || !( $scope.cliente.idTipoCliente > 0 ) ) {
            alertify.set('notifier','position', 'top-right');
            alertify.notify('Ingrese nombre y tipo de cliente para guardar', 'warning', 3);
        }else{
            //validar accion a realizar
            var accion = 'insert';
            if ( $scope.cliente.idCliente > 0 ) {
                accion = 'update';
            }
            
            $http.post('consultas.php',{
                opcion:"consultaCliente",
                accion:accion,
                cliente: $scope.cliente
            }).success(function(data){
                console.log(data);
                alertify.set('notifier','position', 'top-right');
                alertify.notify(data.mensaje,data.respuesta, data.tiempo);
                if ( data.respuesta == "success" ) {
                    $scope.cancelarCliente();
                }
            }).error(function (error, status){
                $scope.data.error = { message: error, status: status};
                console.log($scope.data.error.status); 
            }); 
        }
    };

    $scope.buscarCliente = function(valor,evento){
        if (valor == "" || valor == undefined )  {
            alertify.set('notifier','position', 'top-right');
            alertify.notify('Ingrese algún dato para buscar', 'warning', 3);
        }else{
            $http.post('consultas.php',{
                opcion:"consultarCliente",
                valor: valor
            }).success(function(data){
                console.log(data);
                var encontrados = data.length;
                if (encontrados == 1 ) {
                    if ( evento == 1) {//consulta desde clientes
                        $scope.clienteMenu = 1;
                        $scope.cliente = data[0]
                        $scope.cliente.cui      = parseInt(data[0].cui);
                        $scope.cliente.telefono = parseInt(data[0].telefono);
                    }else{//consulta desde facturacioón
                        $scope.facturaCliente = data[0];
                    }
                }else if( encontrados > 1 ){
                    $scope.masEncontrados = 1;
                    $scope.lstClienteEncontrado = data;
                }else{
                    alertify.set('notifier','position', 'top-right');
                    alertify.notify('Ningún cliente localizado', 'warning', 3);
                }
            })
        }
    };

    $scope.editarCliente = function(cliente){
        $scope.cliente          = angular.copy(cliente);
        $scope.cliente.cui      = parseInt(cliente.cui);
        $scope.cliente.telefono = parseInt(cliente.telefono);
        $scope.masEncontrados   = 0;
        $scope.clienteMenu      = 1;
    };

});

