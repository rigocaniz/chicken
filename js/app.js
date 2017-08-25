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
    
    $scope.loading     = false;
    $scope.loadingText = "Cargando...";

    $scope.hideLoading = function(){
        $scope.loading     = false;
        $scope.loadingText = "Cargando...";
    };

    $scope.showLoading = function( mensaje )
    {
        $scope.loading     = true;
        $scope.loadingText = mensaje ? mensaje : "Cargando...";
    };

    $scope.hideLoading();

    // LISTEN INFO NODE
    socket.on('mensaje', function(data) {  
        console.log(data);
    });

    socket.on('info', function(data) {  
        console.log( data );
        $rootScope.$broadcast( 'infoNode', data );
    });

    $scope.difLocalServer = 0;
    $scope.fechaActual = null;
    // DIFERENCIA DE TIEMPO RESPECTO AL SERVIDOR
    $http.post('consultas.php', { opcion : 'timeNow' })
    .success(function (data) {
        $scope.fechaActual    = moment( data.fechaActual );
        $scope.difLocalServer = moment( new Date() ).diff( moment( data.timeNow ) );
    });

    // MUESTRA TIEMPO TRANSCURRIDO
    $scope.tiempoTranscurrido = function ( tiempo ) {
        var de   = moment( new Date() ).add( $scope.difLocalServer );
        var para = moment( tiempo );

        return de.to( para );
    };

    // TIEMPO EN MINUTOS
    $scope.difMinutos = function ( tiempo ) {
        var de   = moment( new Date() ).add( $scope.difLocalServer );
        var para = moment( tiempo );

        return de.diff( para, 'minutes' );
    };

    $scope.formatoFecha = function function_name( fecha, formato ) {
        var _for = formato || "D [de] MMMM [de] YYYY";
        var txt = moment( fecha ).format( _for )

        return txt;
    };

    // CAPTURA TECLA PARA ATAJOS RAPIDOS
    $scope.pressKey = function ( key, altDerecho ) {
        if ( key != 17 && key != 18 ) {
            $rootScope.$broadcast('keyPress', key, altDerecho );
        }
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

    $scope.dialBuscarCliente = $modal({scope: $scope,template:'dial.buscarCliente.html', show: false, backdrop: 'static', keyboard: false});

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
        'nit'           : null,
        'nombre'        : '',
        'cui'           : null,
        'correo'        : '',
        'telefono'      : null,
        'direccion'     : '',
        'idTipoCliente' : 1,
    };  

    $scope.resetValores = function( accion ){
        $scope.accion = 'insert';
        if( accion == 'cliente' )
        {
            $scope.cliente  = {
                'nit'           : null,
                'nombre'        : '',
                'cui'           : null,
                'correo'        : '',
                'telefono'      : null,
                'direccion'     : '',
                'idTipoCliente' : 1,
            }; 
        }
        if( accion == 'lstClientes' )
        {
            $scope.lstClientes = [];
        }

    };

    $scope.accion = 'insert';
    $scope.guardarCliente = function(){
        var cliente = $scope.cliente;

        if( !(cliente.nombre && cliente.nombre.length >= 3) )
        {
            alertify.notify('El nombre debe tener más de 2 caracteres', 'warning', 4);
        }
        else{
            $http.post('consultas.php',{
                opcion  : "consultaCliente",
                accion  : $scope.accion,
                cliente : $scope.cliente
            }).success(function(data){
                console.log(data);
                alertify.set('notifier','position', 'top-right');
                alertify.notify( data.mensaje,data.respuesta, data.tiempo );
                if ( data.respuesta == "success" )
                    $scope.resetValores( 'cliente' );

            }).error(function (error, status){
                $scope.data.error = { message: error, status: status};
                console.log( $scope.data.error.status ); 
            }); 
            
        }
    };

    $scope.lstClientes = [];
    $scope.txtCliente = '';
    $scope.buscarCliente = function( valor, evento ){
        if ( !(valor.length >= 1) )  {
            alertify.notify('Ingrese algún dato para buscar al cliente', 'warning', 5);
            $scope.lstClientes = [];
        }
        else{
            $http.post('consultas.php',{
                opcion : "consultarCliente",
                valor  : valor
            }).success(function(data){
                console.log( data );
                $scope.lstClientes = data;
                if( $scope.lstClientes.length == 1 )
                {
                    $scope.cliente          = $scope.lstClientes[ 0 ]
                    $scope.cliente.telefono = parseInt( $scope.lstClientes[0].telefono );
                    $scope.resetValores( 'lstClientes' );
                    $scope.txtCliente = '';
                    $scope.accion     = 'update';
                    $scope.dialBuscarCliente.hide();
                    $timeout(function(){
                        $( '#nit' ).focus();
                    }, 125)
                }
                else if( $scope.lstClientes.length > 1 ){
                    $scope.dialBuscarCliente.show();
                    if( $scope.lstClientes.length >= 5 ){
                        $timeout(function(){
                            $('#buscarCliente').focus();
                        }, 150);
                    }
                }
                else{
                    alertify.set('notifier','position', 'top-right');
                    alertify.notify('Ningún cliente encontrado', 'warning', 3);
                    $scope.resetValores( 'cliente' );
                    $timeout(function(){
                        $( '#nit' ).focus();
                    }, 125)
                }
            })
        }
    };

    $scope.seleccionarCliente = function(cliente)
    {
        $scope.cliente = angular.copy( cliente );
        $scope.cliente.telefono = parseInt( cliente.telefono );
        $scope.txtCliente = '';
        $scope.resetValores( 'lstClientes' );
        $scope.accion = 'update';
        $scope.dialBuscarCliente.hide();
        $timeout(function(){
            $( '#nit' ).focus();
        }, 125)
    };

    $scope.editarCliente = function(cliente){
        $scope.cliente          = angular.copy(cliente);
        $scope.cliente.telefono = parseInt(cliente.telefono);
    };

});


app.directive('capitalize', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
            var capitalize = function(inputValue) {
                if (inputValue == undefined) inputValue = '';
                var capitalized = inputValue.toUpperCase();
                if (capitalized !== inputValue) {
                    modelCtrl.$setViewValue(capitalized);
                    modelCtrl.$render();
                }
                return capitalized;
            }
            modelCtrl.$parsers.push(capitalize);
            capitalize(scope[attrs.ngModel]); // capitalize initial value
        }
    };
});