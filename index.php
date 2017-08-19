<?php
    session_start();
    if( isset( $_SESSION[ 'idNivel' ] ) AND isset( $_SESSION[ 'idPerfil' ] ) ):
?>

<!DOCTYPE html>
<html lang="es-GT" ng-app="restaurante">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurante Churchil</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link href="css/stylish-portfolio.css" rel="stylesheet">
    <link rel="stylesheet" href="css/alertify.css">
    <link rel="stylesheet" href="css/themes/semantic.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/fileinput.css">
</head>
<body ng-controller="inicioCtrl" ng-keyup="pressKey( $event.keyCode );">
    <div class="cargando" id="cargando" ng-show="loading">
        <div class="loading-bro">
            <h1>Cargando...</h1>
            <svg id="load" x="0px" y="0px" viewBox="0 0 150 150">
                <circle id="loading-inner" cx="75" cy="75" r="60"/>
            </svg>
        </div>
    </div>

    <!-- Navegacion-->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-md toggle"><span class="glyphicon glyphicon-list"></span></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle">
                <span class="glyphicon glyphicon-list"></span>
            </a>
            <li class="sidebar-brand">
                <a href="#/" onclick='$("#menu-close").click()'>INICIO</a>
            </li>
            <li>
                <a href="#/orden" onclick='$("#menu-close").click()'>Ordenes</a>
            </li>
            <li>
                <a href="#/adminOrden" onclick='$("#menu-close").click()'>AdminOrdenes</a>
            </li>
            <li>
                <a href="#/factura" onclick='$("#menu-close").click()'>Facturación</a>
            </li>
            <li>
                <a href="#/cliente" onclick='$("#menu-close").click()'>Clientes</a>
            </li>
            <li>
                <a href="#/caja" onclick='$("#menu-close").click()'>Caja</a>
            </li>
            <li>
                <a href="#/inventario" onclick='$("#menu-close").click()'>Inventario</a>
            </li>
            <li>
                <a href="#/promocion" onclick='$("#menu-close").click()'>Promociones</a>
            </li>
            <li>
                <a href="#/admin" onclick='$("#menu-close").click()'>Administración</a>
            </li>
            <li>
                <a href="#/mantenimiento" onclick='$("#menu-close").click()'>Mantenimiento</a>
            </li>
            <li>
                <a href="#/tendencia" onclick='$("#menu-close").click()'>Tendencias</a>
            </li>
            <li>
                <a href="#/reporte" onclick='$("#menu-close").click()'>Reportes</a>
            </li>
            <li>
                <a href="logout.php">Salir</a>
            </li>
        </ul>
    </nav>

    <div ng-view></div>

    <!-- MODAL SUBIR IMAGEN -->
    <div class="modal fade" tabindex="-1" id="subirImagen" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading text-center">
                    <button type="button" class="close" ng-click="resetImagen()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="glyphicon glyphicon-picture"></span> SUBIR IMAGEN
                </div>
                <div class="modal-body">
                    <form name="$parent.subirImagen" enctype="multipart/form-data">
                        <div class="form-group">
                            <span class="badge">CAMBIAR IMAGEN</span>
                            <div class="pull-right">
                                <label class="label label-info" style="font-size: 1.2em">
                                    {{ imagen.tipo | uppercase }} #{{ imagen.id }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>SELECCIONE UNA IMAGEN</label>
                            <input id="imagen" name="imagen" type="file">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="resetImagen()">
                        <span class="glyphicon glyphicon-log-out"></span> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="node-app/node_modules/socket.io-client/dist/socket.io.js"></script>
    <script src="js/libs/jquery-3.2.1.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/angular.min.js"></script>
    <script src="js/libs/angular-route.min.js"></script>
    <script src="js/libs/ngstrap.js"></script>
    <script src="js/libs/dirPagination.js"></script>
    <script src="js/libs/fileinput.js"></script>
    <script src="js/libs/moment.min.js"></script>
    

    <script src="js/app.js"></script>
    <script src="js/crtlCliente.js"></script>
    <script src="js/crtlInventario.js"></script>
    <script src="js/crtlNuevoEdita.js"></script>
    <script src="js/crtlAdmin.js"></script>
    <script src="js/crtlAdminOrden.js"></script>
    <script src="js/crtlOrden.js"></script>
    <script src="js/crtlReporte.js"></script>
    <script src="js/crtlMantenimiento.js"></script>
    <script src="js/crtlFactura.js"></script>
    <script src="js/alertify.js"></script>

    <script>
        // Closes the sidebar menu
        $("#menu-close").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("active");
        });
        // Opens the sidebar menu
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("active");
        });


        $(document).ready(function(){
            $('[data-toggle=tooltip]').hover(function(){
                $(this).tooltip('show');
            }, function(){
                $(this).tooltip('hide');
            });
        });
        
    </script>
</body>
</html>
<?php 
else:
    header( "Location: login.php" );
endif;
?>