<?php
    session_start();
    include '../class/sesion.class.php';
    
    $urlManual = 'docs/manual.pdf';

    if( $sesion->getIdPerfil() == 2 )
        $urlManual = 'docs/manualCajero.pdf';
    
    elseif( $sesion->getIdPerfil() == 3 )
        $urlManual = 'docs/manualCocinero.pdf';
    
    elseif( $sesion->getIdPerfil() == 4 OR $sesion->getIdPerfil() == 5 )
        $urlManual = 'docs/manualMesero.pdf';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="col-md-8 col-sm-7 col-xs-6">
                <h2>
                    <span class="glyphicon glyphicon-th"></span>
                    MENU
                </h2>
                <label class="label label-danger titulo-nombre">
                    <span class="glyphicon glyphicon-user"></span>
                    <?= $sesion->getNombre(); ?>
                </label>
            </div>
            <div class="col-md-4 col-sm-5 col-xs-6">
                <div class="pull-right">
                    <img class="img-responsive" src="img/logo_churchil.png" style="height: 100px;">
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/orden" class="item-menu">
                <img src="img/orden.png" alt="">
                <span><u>O</u>rdenes</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/adminOrden" class="item-menu">
                <img src="img/orden1.png" alt="">
                <span><u>A</u>dmin Orden</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/factura" class="item-menu">
                <img src="img/factura.png" alt="">
                <span><u>F</u>acturacion</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/evento" class="item-menu">
                <img src="img/evento.png">
                <span><u>E</u>vento</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/cliente" class="item-menu">
                <img src="img/cliente.png" alt="">
                <span>Clientes</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/caja" class="item-menu">
                <img src="img/caja.png" alt="">
                <span><u>C</u>aja</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/inventario" class="item-menu">
                <img src="img/inventario.png" alt="">
                <span><u>I</u>nventario</span>
            </a>
        </div>
        <!--
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/promocion" class="item-menu">
                <img src="img/promocion.png" alt="">
                <span>Promociones</span>
            </a>
        </div>
        -->
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/admin" class="item-menu">
                <img src="img/admins.png" alt="">
                <span>Administración</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/mantenimiento" class="item-menu">
                <img src="img/menus.png" alt="">
                <span><u>M</u>enús</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/tendencia" class="item-menu">
                <img src="img/tendencia.png" alt="">
                <span>Tendencias</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="#/reporte" class="item-menu">
                <img src="img/reporte.png" alt="">
                <span><u>R</u>eportes</span>
            </a>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <a href="<?= $urlManual; ?>" target="_blank" class="item-menu">
                <img src="img/book.png" alt="">
                <span>Manual de Usuario</span>
            </a>
        </div>
    </div>
</div>
        