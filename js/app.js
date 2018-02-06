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


/* CONEXION A SERVIDOR DE NOTIFICACIONES */
//var socket = io.connect('http://192.168.0.140:8080', { 'forceNew': true });
var socket = io.connect('http://127.0.0.1:8080', { 'forceNew': true });
var code   = document.getElementById('resuFoni').value;


/****CONTROLADORES****/

// CONTROLADOR PRINCIPAL
app.controller('inicioCtrl', function($scope, $rootScope, $timeout, $http, $modal, $window ){
    alertify.set('notifier','position', 'top-right');
    
    $scope.lstU        = [];
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
    socket.on('connect', function() {
        console.log("CONECT..!", code);
        socket.emit( "sesion", { code : code });
    });

    socket.on('info', function(data) {  
        console.log( data );
        if ( data.action != undefined && data.action == 'lstU' )
            $scope.lstU = data.lst;

        else
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

        if ( !fecha )
            return fecha;

        if ( fecha.length == 8 )
            var txt = moment( fecha, 'HH:mm:ss' ).format( _for )

        else
            var txt = moment( fecha ).format( _for )

        return txt;
    };

    // CAPTURA TECLA PARA ATAJOS RAPIDOS
    $scope.pressKey = function ( key, altDerecho, event ) {
        if ( $scope.loading )
            return false;

        if ( key == 117 || altDerecho )
            event.preventDefault();

        if( altDerecho && ( key == 13 || key == 92 || key == 91 ) ) {
            $window.location.href = "#/";
        }

        else if ( key != 17 && key != 18 && key != 92 && key != 91 ) {
            $rootScope.$broadcast('keyPress', key, altDerecho, event );
        }

        if ( altDerecho && document.location.hash === "#/" ) {
            switch( key )
            {
                case 79: // O
                    $window.location.href = "#/orden";      break;
                case 65: // A
                    $window.location.href = "#/adminOrden";      break;
                case 70: // F
                    $window.location.href = "#/factura";      break;
                case 69: // E
                    $window.location.href = "#/evento";      break;
                case 67: // C
                    $window.location.href = "#/caja";      break;
                case 73: // I
                    $window.location.href = "#/inventario";      break;
                case 77: // M
                    $window.location.href = "#/mantenimiento";      break;
                case 82: // R
                    $window.location.href = "#/reportes";      break;
            }
        }
        console.log( "K:", key );
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

    
    /*CLIENTES*/
    ($scope.catTipoCliente = function(){
        $http.post('consultas.php',{
            opcion : 'catTiposCliente'
        }).success(function(data){
            $scope.lstTipoCliente = data;
        })
    })();  

    //$scope.catTipoCliente();

    //$scope.clienteMenu = 1;
    //$scope.tipo = null;

});


/*
window.onfocus = function() {
    console.log( "Focus" );
    $("#divFocus").removeClass("noFocusApp");
};

window.onblur = function() {
    console.log( "Blur" );
    $("#divFocus").addClass("noFocusApp");
};
*/
