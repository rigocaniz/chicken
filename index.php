<?php
    session_start();
    if( isset( $_SESSION[ 'usuario' ] ) AND isset( $_SESSION[ 'idPerfil' ] ) ):
        include 'class/sesion.class.php';
        $token = "USR." . $sesion->getUsuario() . "." . $sesion->getIdPerfil();
/*
    echo "<pre>";
    print_r( $_SESSION );
    echo "</pre>";
    */
?>

<!DOCTYPE html>
<html lang="es-GT" ng-app="restaurante">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurante Churchil</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.min.css">
    <!--
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link href="css/stylish-portfolio.css" rel="stylesheet">
    <link rel="stylesheet" href="css/alertify.css">
    <link rel="stylesheet" href="css/themes/semantic.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/fileinput.css">
    -->
    <!-- jQuery -->
    <script src="node-app/node_modules/socket.io-client/dist/socket.io.js"></script>
    <script src="js/libs/lib.min.js"></script>
    <!--
    <script src="js/libs/jquery-3.2.1.min.js"></script>
    <script src="js/libs/angular.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/angular-route.min.js"></script>
    <script src="js/libs/ngstrap.js"></script>
    <script src="js/libs/dirPagination.js"></script>
    <script src="js/libs/fileinput.js"></script>
    <script src="js/libs/moment.min.js"></script>
    <script src="js/libs/highcharts.js"></script>
    -->
</head>
<body ng-controller="inicioCtrl" ng-keydown="pressKey( $event.keyCode, $event.ctrlKey, $event );" style="overflow-x: hidden;">
    <div class="network-bad">
        <p><span class="glyphicon glyphicon-alert"></span> Error de conexión...</p>
    </div>
    <div id="divFocus">
        <div class="back">
            <h2>Haga clic aquí</h2>
        </div>
    </div>

    <input type="hidden" id="resuFoni" value="<?= $token; ?>">
    <div class="cargando" id="cargando" ng-show="loading">
        <div class="loading-bro ng-cloak">
            <h1>{{ loadingText }}</h1>
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
                <a href="#/evento" onclick='$("#menu-close").click()'>Evento</a>
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
            <!--
            <li>
                <a href="#/promocion" onclick='$("#menu-close").click()'>Promociones</a>
            </li>
        -->
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

    <div id="ng_view" ng-view></div>

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
    
    <!-- DIALOGO CAMBIO DE USUARIO RAPIDO -->
    <script type="text/ng-template" id="change.user.html">
        <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content panel-primary">
                    <div class="modal-header panel-heading">
                        <button type="button" class="close" ng-click="$hide()">&times;</button>
                        <h4>
                            <span class="glyphicon glyphicon-user"></span>
                            Cambio de Usuario Rápido
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-xs-2 col-xs-offset-2">Usuario:</label>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" ng-model="userInfo._usuario" id="_usuario" focus-enter required>
                            </div>
                        </div>
                        <div class="row" style="margin-top:7px">
                            <label class="col-xs-2 col-xs-offset-2">Clave:</label>
                            <div class="col-xs-4">
                                <input type="password" class="form-control" ng-model="userInfo._clave" ng-keydown="$event.keyCode==13 && cambiarUsuario()">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" ng-click="cambiarUsuario()">
                            <span class="glyphicon glyphicon-log-in"></span>
                            <b>Autenticarse</b>
                            <span class="glyphicon glyphicon-user"></span>
                        </button>
                        <button type="button" class="btn btn-default" ng-click="$hide()">
                            <span class="glyphicon glyphicon-log-out"></span>
                            <b>Salir</b>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </script> 

    <!-- CONTROLADORES -->
    <script src="js/app.js"></script>
    <script src="js/rutas.js"></script>
    <script src="js/directivas.js"></script>
    <script src="js/crtlCliente.js"></script>
    <script src="js/crtlInventario.js"></script>
    <script src="js/crtlAdmin.js"></script>
    <script src="js/crtlAdminOrden.js"></script>
    <script src="js/crtlOrden.js"></script>
    <script src="js/crtlReporte.js"></script>
    <script src="js/crtlMantenimiento.js"></script>
    <script src="js/crtlFactura.js"></script>
    <script src="js/ctrlCaja.js"></script>
    <script src="js/crtlEvento.js"></script>
    <script src="js/crtlTen.js"></script>
    <script src="js/alertify.js"></script>
    <!--
    <script src="js/app.min.js"></script>
    -->

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