<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 3 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="contenedor">
    <div class="row">
        <div class="col-sm-12">            
            <div class="pull-right">
                <a href="#/" >
                    <img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
                </a>
            </div>
            <a href="#/">
                <span class="glyphicon glyphicon-home" style="font-size: 20px; padding: 10px"></span>
            </a>
            <div class="text-right" style="margin-top: -25px; margin-right: 90px">
                <button type="button" class="btn btn-sm btn-primary" ng-click="dialCaja.show()">
                    <span class="glyphicon glyphicon-inbox"></span> ACCIONES DE CAJA
                </button>
                <button type="button" class="btn btn-sm btn-info" ng-click="cargarlstFacturasDia()">
                    <span class="glyphicon glyphicon-list"></span>
                    REIMPRESIÓN
                </button>
            </div>
            <legend>
                <span class="glyphicon glyphicon-list-alt"></span> <b>FACTURAR</b>
            </legend>
			<form class="form-horizontal" role="form" autocomplete="off" novalidate>
                <div class="form-group">
                    <label class="col-sm-2 control-label">TICKET</label>
                    <div class="col-sm-4 col-md-3 col-lg-2">
                        <div ng-show="!facturacion.numeroTicket">
                             <input type="text" id="ticket" class="form-control" ng-keypress="$event.keyCode == 13 && buscarOrdenTicket()" ng-model="buscarTicket" ng-disabled="facturacion.idOrdenCliente>0">
                        </div>
                        <div ng-show="facturacion.numeroTicket">
                            <input type="text" class="form-control" ng-keypress="$event.keyCode == 13 && buscarOrdenTicket()" ng-model="facturacion.numeroTicket" disabled>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" class="btn btn-danger" ng-click="buscarOrdenTicket()" ng-show="!facturacion.numeroTicket">
                            <span class="glyphicon glyphicon-search"></span> Buscar
                        </button>
                        <button type="button" class="btn btn-warning" ng-click="facturacion.numeroTicket=null;" ng-show="facturacion.numeroTicket">
                            <span class="glyphicon glyphicon-refresh"></span> Cambiar
                        </button>
                    </div>
                    <div class="col-sm-3 text-right">
                        <h4>
                            <b># Orden: {{ infoOrden.idOrdenCliente }}</b>
                        </h4>
                    </div>
                </div>
                <!-- TABS -->
                <div style="margin-top: 15px">
                    <ul class="nav nav-tabs tabs-title" role="tablist">
                        <li role="presentation" ng-class="{'active' : $parent.idTab==factura.idTab}" 
                            ng-repeat="factura in lstFacturas">
                            <a href="" role="tab" ng-click="$parent.idTab=factura.idTab" style="padding-right:32px">
                                <span class="glyphicon glyphicon-shopping-cart"></span> {{factura.tab}} <span class="label label-primary">{{factura.lstDetalle.length}}</span>
                            </a>
                            <button type="button" ng-class="{'remove-tab': !factura.facturado}" ng-dblclick="quitarFactura( factura.idTab );" 
                                ng-hide="factura.principal || factura.facturado" data-title="Doble clic para eliminar" data-placement="left" bs-tooltip>
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </li>
                        <li role="presentation" style="padding-top:5px">
                            <button type="button" class="btn btn-default" ng-click="agregarFactura({})" data-title="Ctrl+ESPACIO" data-placement="top" bs-tooltip>
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- CONTENT-TABS -->
                <div>
                    <div class="tab-content" ng-repeat="factura in lstFacturas" ng-show="$parent.idTab==factura.idTab">
                        <!-- BUSQUEDA Y NIT -->
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="col-sm-3 control-label">
                                    BUSCAR
                                    <p class="small text-info">Ctrl+C</p>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" id="searchPrincipal_{{factura.idTab}}" class="form-control" ng-model="txtCliente"  placeholder="NIT / DPI / NOMBRE" ng-keypress="$event.keyCode==13&&$event.preventDefault();$event.keyCode == 13 && buscarCliente( txtCliente, 'principal' )" ng-disabled="factura.facturado">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-warning" ng-click="buscarCliente( txtCliente, 'principal' );" readonly>
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
        					<label class="col-sm-1 control-label">NIT</label>
        					<div class="col-sm-3">
        						<input type="text" class="form-control" ng-model="factura.cliente.nit" ng-disabled="factura.cliente.idCliente>0 || factura.facturado" id="cliente_nit" style="font-weight:bold">
        					</div>
        					<div class="col-sm-2 col-md-1">
        						<button type="button" class="btn btn-info" ng-click="editarCliente( factura.cliente, 'mostrar' );" ng-show="factura.cliente.idCliente && factura.cliente.idCliente != 1" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
        							<span class="glyphicon glyphicon-pencil"></span> <u>E</u>ditar
        						</button>
        					</div>
        				</div>
                        <!-- DATOS DE PAGO Y DETALLE DE ORDEN -->
        				<div class="form-group">
        					<label class="col-sm-1 control-label">NOMBRE</label>
        					<div class="col-sm-4">
        						<input type="text" class="form-control" ng-model="factura.cliente.nombre" id="nombreCliente_{{factura.idTab}}" ng-disabled="factura.facturado" style="font-weight:bold">
        					</div>
        					<label class="col-sm-2 col-lg-1 control-label">DIRECCION</label>
        					<div class="col-sm-5 col-lg-6">
        						<input type="text" class="form-control" ng-model="factura.cliente.direccion" ng-disabled="factura.cliente.idCliente!=1 || factura.facturado" focus-enter>
        					</div>
        				</div>

                        <!-- DATOS DE PAGO Y DETALLE DE ORDEN -->
                        <div class="row">
                            <!-- DETALLE ORDEN -->
                            <div class="col-xs-8 col-sm-7">
                                <fieldset class="fieldset">
                                    <legend class="legend info">DETALLE ORDEN</legend>
                                    <table class="table table-hover table-condensed table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="width:100px">Cantidad</th>
                                                <th>Orden</th>
                                                <th>Precio</th>
                                                <th style="width:148px">Descuento</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="orden in factura.lstDetalle">
                                                <td>{{$index+1}}</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" focus-enter
                                                            ng-model="orden.cantidad"
                                                            ng-keydown="$event.keyCode!=13&&$event.keyCode!=9&&$event.keyCode!=116&&$event.preventDefault()"
                                                            ng-focus="focusDetalle( $index )" ng-disabled="factura.facturado">

                                                        <!-- REASIGNAR -->
                                                        <div class="reasignar" ng-show="factura.ixDetalle==$index">
                                                            <button type="button" ng-click="factura.ixDetalle=-1" class="cerrar">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                            <div>
                                                                <strong>Cantidad <kbd>{{orden.pendiente}}</kbd></strong>
                                                                <input type="number" class="form-control" id="fact_orden_{{$index}}" ng-model="orden.cantidadTrasladar" ng-min="1" min="1" ng-max="orden.pendiente" max="{{orden.pendiente}}" focus-enter ng-disabled="factura.facturado">
                                                            </div>
                                                            <div>
                                                                <strong>Orden</strong>
                                                                <select ng-model="factura.idTabDestino" class="form-control" focus-enter ng-disabled="factura.facturado">
                                                                    <option value="" style="font-weight:bold">Nueva Orden</option>
                                                                    <option value="{{tab.idTab}}" ng-hide="tab.idTab==$parent.idTab" ng-repeat="tab in lstFacturas">{{tab.tab}}</option>
                                                                </select>
                                                            </div> 
                                                            <div style="float:left;margin-top:5px;width:100%">
                                                                <button type="button" class="btn btn-block btn-warning" ng-click="asignarDetalle( factura.idTab, factura.idTabDestino, orden, $index )" ng-disabled="!orden.cantidadTrasladar || factura.facturado">
                                                                    <span class="glyphicon glyphicon-ok"></span>
                                                                    <b>Asignar</b>
                                                                </button>
                                                            </div>
                                                        </div><!-- REASIGNAR -->
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="label-border btn-sm" ng-class="{'btn-success':orden.idTipoServicio==2, 'btn-warning':orden.idTipoServicio==3, 'btn-primary':orden.idTipoServicio==1}" title="{{ orden.tipoServicio }}" data-toggle="tooltip" data-placement="top" tooltip>
                                                        <span ng-show="orden.idTipoServicio==2">R</span>
                                                        <span ng-show="orden.idTipoServicio==3">D</span>
                                                        <span ng-show="orden.idTipoServicio==1">L</span>
                                                        <span ng-show="orden.idTipoServicio==''" class="text-primary">
                                                            <span class="glyphicon glyphicon-star"></span>
                                                        </span>
                                                    </div>
                                                    <span class="glyphicon glyphicon-gift" ng-show="orden.idCombo>0"></span>
                                                    <span>{{ orden.descripcion }}</span>
                                                </td>
                                                <td class="text-right">{{ orden.precio | number: 2 }}</td>
                                                <td ng-class="{'danger':orden.conDescuento&&orden.justificacion.length<6}">
                                                    <div style="position:relative;">
                                                        <!-- REASIGNAR -->
                                                        <div class="reasignar" ng-show="factura.ixDesc==$index">
                                                            <button type="button" ng-click="factura.ixDesc=-1" class="cerrar">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                            <div>
                                                                <strong>Justificación</strong>
                                                                <textarea ng-model="orden.justificacion" rows="4" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- REASIGNAR -->
                                                        <button ng-click="orden.conDescuento=true" class="btn btn-sm btn-info" ng-show="!orden.conDescuento" ng-disabled="factura.facturado">
                                                            <b>SI</b>
                                                        </button>
                                                        <div class="input-group input-sm" ng-show="orden.conDescuento">
                                                            <input type="number" class="form-control" ng-model="orden.descuento" 
                                                                ng-change="calculoFactura( 'totalFactura' )"
                                                                placeholder="Q." ng-focus="factura.ixDesc=$index" 
                                                                ng-min="1" min="1" max="{{ orden.precio*orden.cantidad }}" ng-max="{{orden.precio*orden.cantidad}}" required>
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn btn-default" 
                                                                    ng-click="orden.justificacion='';orden.descuento='';orden.conDescuento=0;factura.ixDesc=-1;calculoFactura( 'totalFactura' )">
                                                                    <span class="glyphicon glyphicon-remove"></span>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <strong data-title="{{ orden.precio*orden.cantidad | number: 2 }}" data-placement="top" tooltip>
                                                        Q. {{ (orden.precio*orden.cantidad)-orden.descuento | number: 2 }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr class="success">
                                                <td colspan="5" class="text-right">
                                                    <strong>TOTAL</strong>
                                                </td>
                                                <td class="text-right">
                                                    <strong>Q. {{ factura.detallePago.total | number: 2 }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>

                            <!-- DETALLE PAGO -->
                            <div class="col-xs-4 col-sm-5">
                                <fieldset class="fieldset" style="padding: 5px 25px;">
                                    <legend class="legend info">DETALLE PAGO (Ctrl+P)</legend>
                                    <div class="form-group group-factura">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon">
                                                <b>Total</b>
                                            </span>
                                            <input type="text" class="form-control monto" value="{{factura.detallePago.total | number:2}}" placeholder="Q." disabled style="background:#fff">
                                        </div>
                                    </div>
                                    <div class="form-group group-factura">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-btn">
                                                <span class="btn btn-success">
                                                    <b>Q.</b>
                                                </span>
                                            </span>
                                            <input type="number" class="form-control monto" id="efectivo_{{factura.idTab}}" ng-model="factura.detallePago.efectivo" placeholder="Efectivo" focus-enter ng-change="calculoFactura( 'totalPago' )" ng-disabled="factura.facturado">
                                        </div>
                                    </div>
                                    <div class="form-group group-factura">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-btn">
                                                <span class="btn btn-primary">
                                                    <span class="glyphicon glyphicon-credit-card"></span>
                                                </span>
                                            </span>
                                            <input type="number" class="form-control monto" ng-model="factura.detallePago.tarjeta" placeholder="Tarjeta Débito / Crédito" focus-enter ng-change="calculoFactura( 'totalPago' )" ng-disabled="factura.facturado">
                                        </div>
                                    </div>
                                    <div class="form-group group-factura">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon">
                                                <b>Cambio</b>
                                            </span>
                                            <input type="text" class="form-control monto" value="{{ factura.detallePago.cambio | number:2 }}" placeholder="Q." disabled style="background:#fff">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <b>Descripción Factura</b>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-default" ng-click="factura.detallePago.tipo='d'" ng-disabled="factura.facturado">
                                                <span class="glyphicon" ng-class="{'glyphicon-check': factura.detallePago.tipo=='d', 'glyphicon-unchecked': factura.detallePago.tipo!='d'}"></span> Detalle
                                            </button>
                                            <button type="button" class="btn btn-default" ng-click="factura.detallePago.tipo='p'" ng-disabled="factura.facturado">
                                                <span class="glyphicon" ng-class="{'glyphicon-check': factura.detallePago.tipo=='p', 'glyphicon-unchecked': factura.detallePago.tipo!='p'}"></span> Personalizado
                                            </button>
                                        </div>
                                        <div class="col-sm-12" ng-show="factura.detallePago.tipo=='p'" style="margin-top:5px">
                                            <textarea ng-model="factura.detallePago.descripcion" placeholder="Ingrese Descripción de la Factura" rows="3" class="form-control" factura.facturado></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group text-center" ng-show="factura.facturado">
                                        <a class="btn btn-info btn-lg" id="btn_print_factura" target="_blank" ng-href="print.php?id={{ factura.idFactura }}&type={{ factura.tipo }}" title="IMPRIMIR FACTURA">
                                            <span class="glyphicon glyphicon-print"></span> IMPRIMIR
                                        </a>
                                    </div>
                                    <div class="form-group" ng-hide="factura.facturado">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-success" ng-click="facturarOrden()" ng-disabled="factura.facturado">
                                                <span class="glyphicon glyphicon-saved"></span> <b>FACTURAR (F6)</b>
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><!-- DATOS DE PAGO Y DETALLE DE ORDEN -->
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>


<!-- FACTURAS REGISTRADAS -->
<script type="text/ng-template" id="dial.reimpresion.html">
    <div class="modal" tabindex="-1" role="dialog" id="dial_orden_busqueda">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                    <span class="glyphicon glyphicon-search"></span>
                    REIMPRESIÓN DE FACTURAS
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center"># Orden</th>
                                            <th class="text-center">NIT</th>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center">Direccion</th>
                                            <th class="text-center">Fecha Facturacion</th>
                                            <th class="text-center">General</th>
                                            <th class="text-center">Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in lstFacturasDia track by $index">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-center">{{ item.nit }}</td>
                                            <td>{{ item.nombre }}</td>
                                            <td>{{ item.direccion }}</td>
                                            <td class="text-center">{{ item.fechaRegistro }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-sm" id="btn_print_factura" target="_blank" ng-href="print.php?id={{ item.idFactura }}&type=g" title="IMPRIMIR FACTURA">
                                                    <span class="glyphicon glyphicon-print"></span>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-info btn-sm" id="btn_print_factura" target="_blank" ng-href="print.php?id={{ item.idFactura }}&type=d" title="IMPRIMIR FACTURA">
                                                    <span class="glyphicon glyphicon-print"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- *********** BUSQUEDA DE ORDEN ********* -->
<script type="text/ng-template" id="dial.orden-busqueda.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_busqueda">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                	<span class="glyphicon glyphicon-search"></span>
                    Buscar Orden
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th># Orden</th>
                                            <th>Ticket</th>
                                            <th>Responsable</th>
                                            <th>Estado Orden</th>
                                            <th>Lapso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in lstTicketBusqueda track by $index" ng-click="seleccionarDeBusqueda( item )">
                                            <td>{{ item.idOrdenCliente }}</td>
                                            <td>{{ item.numeroTicket }}</td>
                                            <td>{{ item.usuarioResponsable }}</td>
                                            <td>{{ item.estadoOrden }}</td>
                                            <td>{{ tiempoTranscurrido( item.fechaRegistro ) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                		</div>
                	</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- DIALOGO BUSCAR / LISTAR CLIENTES -->
<script type="text/ng-template" id="dial.accionCliente.html">
    <div class="modal" tabindex="-1" role="dialog" id="dial_accionCliente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading text-center">
                    <button type="button" class="close" ng-click="resetValores( 'lstClientes' ); $hide();">&times;</button>
                    <span class="glyphicon glyphicon-user"></span> CLIENTE
                </div>
                <div class="modal-body">
                    <div class="row">
                    	<div class="col-sm-12 text-right" ng-show="$parent.accionCliente=='ninguna'">
	                    	<button type="button" class="btn btn-info" ng-click="buscarCliente( 'CF', 'cf' ); $parent.accionCliente='ninguna'">
	                    		<span class="glyphicon glyphicon-user"></span> <u><strong>C</strong></u>ONSUMIDOR FINAL
	                    	</button>
	                    	<button type="button" class="btn btn-success" ng-click="$parent.accionCliente='agregar'">
	                    		<span class="glyphicon glyphicon-plus"></span> <u><strong>A</strong></u>GREGAR CLIENTE
	                    	</button>
                    	</div>
                    	<div class="col-sm-12" ng-show="$parent.accionCliente!='agregar' && $parent.accionCliente!='actualizar'">
                    		<hr>
	                        <!-- BUSCAR CLIENTE -->
                            <div class="row">
                                <label class="col-xs-12 col-sm-2 col-md-3 control-label">BUSCAR CLIENTE</label>
                                <div class="col-xs-9 col-sm-6 col-md-5">
                                    <input type="text" class="form-control" id="buscador" ng-model="$parent.txtCliente" ng-change="$parent.lstClientes=[]" ng-keypress="$event.keyCode== 13 && buscarCliente( $parent.txtCliente, 'busqueda' )" placeholder="NIT / DPI / NOMBRE">
                                </div>
                                <div class="col-xs-3 col-sm-4 col-md-3">
                                    <button class="btn btn-sm btn-primary" ng-click="buscarCliente( $parent.txtCliente, 'busqueda' )">
                                        <span class="glyphicon glyphicon-search"></span> Buscar
                                    </button>
                                </div>
                            </div>
                            <br>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center col-sm-4">CLIENTE</th>
                                        <th class="text-center col-sm-2">NIT</th>
                                        <th class="text-center col-sm-2 ">DPI</th>
                                        <th class="text-center col-sm-4">DIRECCION</th>
                                        <th class="text-center col-sm-1">Editar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="cliente in lstClientes" ng-class="{'border-info': cliente.idTipoCliente == 1, 'border-warning': cliente.idTipoCliente == 2}" ng-hide="cliente.idCliente == 1">
                                        <td>{{ cliente.nombre }}</td>
                                        <td class="text-center">{{ cliente.nit }}</td>
                                        <td class="text-center">{{ cliente.cui }}</td>
                                        <td>{{ cliente.direccion }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" ng-click="editarCliente( cliente )">
                                            	<span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" ng-click="seleccionarCliente( cliente )">
                                                Seleccionar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    	</div>
                    	<div class="col-sm-12" ng-show="$parent.accionCliente=='agregar' || $parent.accionCliente=='actualizar'">
                        	<form class="form-horizontal" autocomplete="off" novalidate>
                                <div class="text-right">
                                    <label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}" style="font-size: 14px;">
                                        {{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} CLIENTE
                                    </label>
                                </div>
                                <br>
                                <div class="pull-right">
                                    <label>Tipo</label>
                                    <div class="btn-group" role="group" aria-label="">
                                        <div class="btn-group" role="group" aria-label="">
                                            <button type="button" class="btn btn-default" ng-repeat="tc in lstTipoCliente" ng-click="$parent.cliente.idTipoCliente = tc.idTipoCliente" ng-class="{'btn-warning': tc.idTipoCliente == $parent.cliente.idTipoCliente}">
                                                <span class="glyphicon" ng-class="{'glyphicon-check': tc.idTipoCliente == $parent.cliente.idTipoCliente, 'glyphicon-unchecked': tc.idTipoCliente != $parent.cliente.idTipoCliente}"></span>
                                                {{ tc.tipoCliente }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">NIT</label>
                                    <div class="col-sm-3">
                                        <input type="text" ng-model="$parent.cliente.nit" class="form-control" id="nit" ng-pattern="/^[0-9-\s]+?$/" maxlength="12" focus-enter>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Nombre</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65" focus-enter>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Dirección</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control" maxlength="95" ng-model="$parent.cliente.direccion" focus-enter>
									</div>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cui (DPI)</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="13" ng-model="$parent.cliente.cui" focus-enter>
                                    </div>
                                    <label class="col-sm-2 control-label">Correo</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="email" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo" focus-enter>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Telefono</label>
                                    <div class="col-sm-4 col-md-3">
                                        <input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="8" ng-model="$parent.cliente.telefono" focus-enter>
                                    </div>
                                </div>
								<div class="col-sm-12 text-center">
									<button type="button" class="btn btn-success" ng-click="consultaCliente()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} Cliente (F6)
									</button>
									<button type="button" class="btn btn-default" ng-click="$parent.accionCliente='ninguna'; $parent.resetValores( 'cliente' )"> 
										<span class="glyphicon glyphicon-log-out"></span> Cancelar
									</button>
								</div>
							</form>
                    	</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="resetValores( 'lstClientes' ); $hide();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>


<script type="text/ng-template" id="dial.printFactura.html">
    <div class="modal" tabindex="-1" role="dialog" id="dial_printFactura">
        <div class="modal-dialog">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading text-center">
                    <button type="button" class="close" ng-click="$hide();">&times;</button>
                    <span class="glyphicon glyphicon-list-alt"></span> IMPRIMIR FACTURA
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" autocomplete="off" novalidate>
                        <div class="form-group text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-default" ng-click="impresionFactura.type='d'">
                                    <span class="glyphicon" ng-class="{'glyphicon-check': impresionFactura.type=='d', 'glyphicon-unchecked': impresionFactura.type!='d'}"></span> A Detalle
                                </button>
                                <button type="button" class="btn btn-default" ng-click="impresionFactura.type='g'">
                                    <span class="glyphicon" ng-class="{'glyphicon-check': impresionFactura.type=='g', 'glyphicon-unchecked': impresionFactura.type!='g'}"></span> General
                                </button>
                            </div>
                            <br><br>
                            <a class="btn btn-info btn-lg" id="btn_print_factura" target="_blank" ng-href="print.php?id={{ impresionFactura.idFactura }}&type={{ impresionFactura.type }}" title="IMPRIMIR FACTURA">
                                <span class="glyphicon glyphicon-print"></span> IMPRIMIR
                            </a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>


<!-- MODA CAJA APERTURA / CIERRE DE CAJA -->
<script type="text/ng-template" id="dial.caja.html">
    <div class="modal" tabindex="-1" role="dialog" id="dial_printFactura">
        <div class="modal-dialog modal-xl">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading text-center">
                    <button type="button" class="close" ng-click="$hide();">&times;</button>
                    <span class="glyphicon glyphicon-inbox"></span> APERTURA / CIERRE DE CAJA
                </div>
                <div class="modal-body" ng-controller="ctrlCaja">
                    <div class="text-right">
                        <p>
                            <button type="button" class="btn btn-warning" ng-click="accionCaja='cierreCaja'" ng-show="caja.idCaja">
                                <span class="glyphicon glyphicon-flag"></span> CERRAR CAJA
                            </button>
                            <button type="button" class="btn btn-success" ng-click="accionCaja='aperturarCaja'" ng-show="!caja.idCaja">
                                <span class="glyphicon glyphicon-flag"></span> APERTURAR CAJA
                            </button>
                            <button type="button" class="btn btn-danger" ng-click="accionCaja=''" ng-show="accionCaja!=''" title="CANCELAR ACCIÓN" data-toggle="tooltip" data-placement="top" tooltip>
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </p>
                    </div>
                    <div class="alert alert-danger" role="alert" ng-show="caja.cajaAtrasada">
                        <span class="glyphicon glyphicon-info-sign"></span> USTED NO HA REALIZADO EL CIERRE DE SU CAJA DE FECHA/HORA: <strong style="font-size: 18px">{{ caja.fechaHoraApertura }}</strong>
                    </div>
                    <fieldset class="fieldset">
                        <legend class="legend info">APERTURA / CIERRE</legend>
                        <form class="form-horizontal" autocomplete="off" novalidate>
                            <div class="text-right">
                                <label class="label" ng-class="{'label-danger': caja.idEstadoCaja == 2, 'label-success': caja.idEstadoCaja==1}" style="font-size: 17px;">
                                    ESTADO {{ caja.estadoCaja | uppercase }}
                                </label>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label">CAJERO</label>
                                <div class="col-sm-6 col-md-5 col-lg-4">
                                    <input type="text" class="form-control" ng-model="caja.cajero" placeholder="Cajero" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-2 control-label">CODIGO OPERADOR</label>
                                <div class="col-sm-3 col-md-3 col-lg-2">
                                    <input type="text" class="form-control" ng-model="caja.codigoUsuario" placeholder="Cajero" disabled>
                                </div>
                                <label class="col-sm-3 col-md-2 control-label">USUARIO</label>
                                <div class="col-sm-3 col-md-3 col-lg-2">
                                    <input type="text" class="form-control" ng-model="caja.usuario" placeholder="Cajero" disabled>
                                </div>
                            </div>
                            <!--
                            {{caja|json}}
                        -->
                            <!-- DENOMINACIONES -->
                            <legend class="text-center" ng-show="accionCaja">
                                <i class="fa fa-money" aria-hidden="true"></i> DENOMINACIONES
                            </legend>
                            <div class="form-group" ng-show="accionCaja">
                                <div class="col-sm-6" ng-repeat="denominacion in caja.lstDenominaciones">
                                    <div class="form-group">
                                        <label class="col-sm-4">{{ denominacion.descripcion }} de {{ denominacion.denominacion }}</label>
                                        <div class="col-sm-4">
                                            <input type="number" min="0"  class="form-control" ng-model="denominacion.cantidad" placeholder="Cantidad" ng-pattern="/^[0-9]+?$/" step="0">
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <kbd class="numEfectivo">
                                                {{ ( denominacion.cantidad ? (denominacion.cantidad * denominacion.denominacion ) : '0' ) | number:2 }}
                                            </kbd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right" ng-show="accionCaja=='cierreCaja'">
                                <button type="button" class="btn btn-sm btn-success" ng-click="caja.agregarFaltante=!caja.agregarFaltante">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Agregar Faltante
                                </button>
                                <br><br>
                            </div>
                            <div class="form-group" ng-show="accionCaja=='cierreCaja' && caja.agregarFaltante">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-4">EFECTIVO FALTANTE</label>
                                        <div class="col-sm-4">
                                            <input type="number" min="0"  class="form-control" ng-model="caja.efectivoFaltante" placeholder="Total Efectivo" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="1">
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <kbd class="numEfectivo">
                                                {{ ( caja.efectivoFaltante ? caja.efectivoFaltante : '0' ) | number:2 }}
                                            </kbd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-7"></div>
                            <div class="col-sm-8 col-md-6 col-lg-5">
                                <h4><b>
                                <table class="table table-hover">
                                    <tr ng-show="accionCaja=='cierreCaja'">
                                        <td class="col-sm-7 text-right"><b>EFECTIVO INICIAL:</b></td>
                                        <td class="col-sm-5 text-right">Q. {{ caja.efectivoInicial | number: 2 }}</td>
                                    </tr>
                                    <tr ng-show="caja.agregarFaltante && accionCaja=='cierreCaja' && caja.efectivoFaltante > 0">
                                        <td class="col-sm-7 text-right"><b>EFECTIVO FALTANTE:</b></td>
                                        <td class="col-sm-5 text-right">Q. {{ caja.efectivoFaltante | number: 2 }}</td>
                                    </tr>
                                    <tr ng-show="accionCaja">
                                        <td class="col-sm-7 text-right"><b>EFECTIVO {{ accionCaja == 'cierreCaja' ? 'FINAL' : 'INICIAL' }}:</b></td>
                                        <td class="col-sm-5 text-right">Q. {{ retornarTotal() | number: 2 }}</td>
                                    </tr>
                                </table>
                                </b></h4>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-success btn-lg" ng-show="accionCaja=='aperturarCaja'" ng-click="consultaCaja()">
                                    <span class="glyphicon glyphicon-folder-open"></span> Aperturar Caja
                                </button>
                                <button type="button" class="btn btn-warning btn-lg" ng-show="accionCaja=='cierreCaja'" ng-click="consultaCaja()">
                                    <span class="glyphicon glyphicon-folder-close"></span> Cerrar Caja
                                </button>
                            </div>
                        </form>
                    </fieldset>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>
