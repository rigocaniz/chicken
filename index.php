<!DOCTYPE html>
<html lang="es-GT" ng-app="restaurante">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Restaurante Churchil</title>
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link href="css/stylish-portfolio.css" rel="stylesheet">
    <link rel="stylesheet" href="css/alertify.css">
    <link rel="stylesheet" href="css/themes/semantic.css">
</head>
<body>
	<!-- Navegacion-->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><span class="glyphicon glyphicon-list"></span></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><span class="glyphicon glyphicon-list"></span></a>
            <li class="sidebar-brand">
                <a href="#/" onclick=$("#menu-close").click();>Inicio</a>
            </li>
            <li>
                <a href="#/orden" onclick=$("#menu-close").click();>Ordenes</a>
            </li>
            <li>
                <a href="#/adminOrden" onclick=$("#menu-close").click();>AdminOrdenes</a>
            </li>
            <li>
                <a href="#/factura" onclick=$("#menu-close").click();>Facturación</a>
            </li>
            <li>
                <a href="#/cliente" onclick=$("#menu-close").click();>Clientes</a>
            </li>
            <li>
                <a href="#/caja" onclick=$("#menu-close").click();>Caja</a>
            </li>
             <li>
                <a href="#/inventario" onclick=$("#menu-close").click();>Inventario</a>
            </li>
             <li>
                <a href="#/promocion" onclick=$("#menu-close").click();>Promociones</a>
            </li>
             <li>
                <a href="#/admin" onclick=$("#menu-close").click();>Administración</a>
            </li>
             <li>
                <a href="#/mantenimiento" onclick=$("#menu-close").click();>Mantenimiento</a>
            </li>
             <li>
                <a href="#/tendencia" onclick=$("#menu-close").click();>Tendencias</a>
            </li>
            <li>
                <a href="#/reporte" onclick=$("#menu-close").click();>Reportes</a>
            </li>
             <li>
                <a href="logOut.php">Salir</a>
            </li>
        </ul>
    </nav>
	<div ng-view></div>

	<!-- jQuery -->
    <script src="js/libs/jquery-3.2.1.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/angular.min.js"></script>
	<script src="js/libs/angular-route.min.js"></script>
    <script src="js/libs/ngstrap.js"></script>
    <script src="js/libs/dirPagination.js"></script>
    <script src="js/app.js"></script>
    <script src="js/crtlCliente.js"></script>
    <script src="js/crtlInventario.js"></script>
    <script src="js/crtlNuevoEdita.js"></script>
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
    
    </script>
</body>
</html>