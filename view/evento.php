<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 4 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
        <div class="col-xs-6" style="margin-top:5px">
            <div class="btn-orden">
                <button class="bt-info" ng-class="{'active':idEstadoEvento==1}" ng-click="idEstadoEvento=1">
                    <span class="glyphicon glyphicon-time"></span>
                    <span class="hidden-xs">Programado</span>
                </button>
                <button class="bt-success" ng-class="{'active':idEstadoEvento==5}" ng-click="idEstadoEvento=5">
                    <span class="glyphicon glyphicon-flag"></span>
                    <span class="hidden-xs">Finalizados</span>
                </button>
                <button class="bt-danger" ng-class="{'active':idEstadoEvento==10}" ng-click="idEstadoEvento=10">
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="hidden-xs">Cancelados</span>
                </button>
            </div>
        </div>
        <div class="col-sm-5" style="margin-bottom:5px">
            <button type="button" class="btn btn-success" ng-click="showDialOrden( 'insert' )">
                <span class="glyphicon glyphicon-plus"></span>
                Nuevo Evento
                <span class="glyphicon glyphicon-calendar"></span>
            </button>
        </div>
        <div class="col-xs-12">
            <div class="col-sm-5">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="filtroFecha=''">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                    </span>
                    <input type="text" class="form-control" ng-model="filtroFecha" data-date-format="dd/MM/yyyy" data-autoclose="1" bs-datepicker placeholder="Filtrar por Fecha">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" ng-click="consultarEvento()">
                            <span class="glyphicon glyphicon-search"></span>
                            Consultar
                            <span class="glyphicon glyphicon-calendar"></span>
                        </button>
                    </span>
                </div>
            </div>
        </div>
	</div>
	<div class="row contenedor-tickets" style="padding-left:19px">
        <div class="col-xs-12" ng-hide="lstEvento.length">
            <div class="alert alert-info" role="alert">No existen ordenes</div>
        </div>

        <!-- *********** INFORMACION DE EVENTOS ********* -->
        <div class="col-xs-12 info-orden-ticket" ng-repeat="evento in lstEvento" style="margin-bottom:9px;padding-bottom:7px">
            <!-- *********** INFORMACION DE ORDEN ********* -->
            <div class="row">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-sm btn-primary" ng-click="showDialOrden( 'update', evento )">
                        <span class="glyphicon glyphicon-pencil"></span>
                        <b>Ver / Modificar</b>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" ng-click="showDialOrden( 'update', evento, 10 )" ng-disabled="evento.idEstadoEvento!=1">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>Cancelar</b>
                    </button>
                    <button type="button" class="btn btn-sm btn-success" ng-click="showDialFactura( evento, 5 )" ng-disabled="evento.idEstadoEvento!=1 && evento.idEstadoEvento!=5">
                        <span class="glyphicon glyphicon-flag"></span>
                        <b>Finalizar</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h4>
                        <b>Evento # </b> {{evento.idEvento}} » 
                        <span>{{evento.evento}}</span>
                    </h4>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <h4><b>Fecha Evento:</b> {{formatoFecha( evento.fechaEvento, 'ddd D [de ] MMM [de] YYYY' )}}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <span class="etiqueta">Cliente: </span>
                    <span class="valor">{{evento.nombre}}</span>
                </div>
                <div class="col-sm-3">
                    <span class="etiqueta"># Personas: </span>
                    <span class="valor">{{evento.numeroPersonas}}</span>
                </div>
                <div class="col-sm-2">
                    <span class="etiqueta">De: </span>
                    <span class="valor">{{formatoFecha( evento.horaInicio, 'HH:mm' )}}</span>
                </div>
                <div class="col-sm-2">
                    <span class="etiqueta">Para: </span>
                    <span class="valor">{{formatoFecha( evento.horaFinal, 'HH:mm' )}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <span class="etiqueta">Estado: </span>
                    <span class="valor">{{evento.estadoEvento}}</span>
                </div>
                <div class="col-xs-3">
                    <span class="etiqueta">Usuario: </span>
                    <span class="valor">{{evento.usuario}}</span>
                </div>
                <div class="col-xs-3">
                    <span class="etiqueta">Registro: </span>
                    <span class="valor" data-title="{{formatoFecha( evento.fechaRegistro, 'HH:mm')}}" data-placement="top" bs-tooltip>
                        {{formatoFecha( evento.fechaRegistro, 'D[/]MM[/]YYYY' )}}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <span class="etiqueta">Observación: </span>
                    <span class="valor">{{evento.observacion}}</span>
                </div>
            </div>
        </div>
	</div>
</div>

<!-- MODAL EVENTO -->
<script type="text/ng-template" id="dl.evento.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dl_evento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" 
                ng-class="{'panel-success':accionEvento=='insert', 'panel-primary':accionEvento=='update' && evento.newIdEstadoEvento!=10, 'panel-danger':evento.newIdEstadoEvento==10}">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-calendar"></span>
                    <span ng-show="evento.newIdEstadoEvento==5 && accionEvento=='update'">Finalizar Evento » # {{evento.idEvento}}</span>
                    <span ng-show="evento.newIdEstadoEvento!=5 && accionEvento=='update'">Modificar Evento » # {{evento.idEvento}}</span>
                    <span ng-show="evento.newIdEstadoEvento!=5 && accionEvento=='insert'">Nuevo Evento</span>
                </div>
                <div class="modal-body">
                    <!-- CANCELAR EVENTO -->
                    <div class="row" ng-show="evento.newIdEstadoEvento==10">
                        <div class="col-xs-12 text-center">
                            <h4>¿Esta seguro de CANCELAR el Evento?</h4>
                        </div>
                        <div class="col-xs-12 text-center">
                            <textarea rows="3" class="form-control" ng-model="evento.comentario" id="comentario" placeholder="Comentario de cancelación"></textarea>
                        </div>
                        <div class="col-xs-12 text-center">
                            <button type="button" class="btn btn-danger" ng-click="guardarEvento()">
                                <span class="glyphicon glyphicon-remove"></span>
                                Cancelar Evento
                            </button>
                        </div>
                    </div>

                    <!-- CANCELAR EVENTO -->
                    <div class="row" ng-show="evento.newIdEstadoEvento==5">
                        <div class="col-xs-12 text-center">
                            <h4>¿Esta seguro de FINALIZAR el Evento?</h4>
                        </div>
                        <div class="col-xs-12">
                            <h3 class="text-primary">{{evento.evento}} » {{formatoFecha( evento.fechaEvento, 'ddd D [de ] MMM [de] YYYY' )}}</h3>
                            <h4><span ng-bind-html="evento.nombreCliente"></span></h4>
                            <hr>
                        </div>
                        <div class="col-xs-12 text-center">
                            <button type="button" class="btn btn-success" ng-click="guardarEvento()">
                                <span class="glyphicon glyphicon-flag"></span>
                                Finalizar Evento
                            </button>
                        </div>
                    </div>

                    <!-- DATOS DEL EVENTO -->
                    <div class="row" ng-show="!(evento.newIdEstadoEvento>0)">
                        <!-- TABS -->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs tabs-title" role="tablist">
                                <li role="presentation" ng-class="{'active' : $parent.idTab==1}" ng-click="$parent.idTab=1">
                                    <a href="" role="tab" data-toggle="tab">
                                        <span class="glyphicon glyphicon-calendar"></span> Datos del Evento
                                    </a>
                                </li>
                                <li role="presentation" ng-class="{'active' : $parent.idTab==2}" ng-click="$parent.idTab=2">
                                    <a href="" role="tab" data-toggle="tab">
                                        <span class="glyphicon glyphicon-cutlery"></span> Menús / Servicios
                                    </a>
                                </li>
                                <li role="presentation" ng-class="{'active' : $parent.idTab==3}" ng-click="$parent.idTab=3">
                                    <a href="" role="tab" data-toggle="tab">
                                        <span class="glyphicon glyphicon-shopping-cart"></span> Movimiento
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- CONTENT-TABS -->
                        <div class="col-sm-12">
                            <div class="tab-content">
                                <!--  INFORMACION EVENTO -->
                                <div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.idTab==1}" ng-show="$parent.idTab==1">
                                    <div class="row" style="margin-top:5px" ng-show="evento.idCliente>0">
                                        <div class="col-sm-12">
                                            <div class="alert alert-info alert-dismissible fade in" role="alert">
                                                <button type="button" class="close" ng-click="evento.idCliente=0;evento.nombreCliente=''" ng-hide="evento.idEvento>0 && accionEvento=='insert'">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <span ng-bind-html="evento.nombreCliente"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px" ng-hide="evento.idCliente>0">
                                        <label class="col-xs-2">Cliente</label>
                                        <div class="col-xs-7">
                                            <input type="text" autocomplete="off" class="form-control" ng-model="$parent.busquedaCliente" id="busquedaCliente">
                                            <div class="lstResultado" ng-show="lstResultado.length">
                                                <ul>
                                                    <li ng-repeat="item in lstResultado" ng-click="seleccionarCliente( item )" ng-class="{'active':item.active}" ng-bind-html="item.cliente">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xs-3" ng-show="!lstResultado.length && $parent.busquedaCliente.length>3">
                                            <button type="button" class="btn btn-primary" ng-click="newClient()">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Cliente
                                                <span class="glyphicon glyphicon-user"></span>
                                            </button>
                                        </div>
                                    </div>
                                	<div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Evento</label>
                                        <div class="col-xs-7">
                                            <input type="text" autocomplete="off" class="form-control" ng-model="evento.evento" placeholder="Descripción de evento" id="evento" focus-enter>
                                        </div>
                                        <div class="col-xs-3" ng-show="accionEvento=='update'">
                                            <span class="label" ng-class="{'label-primary':evento.idEstadoEvento==1, 'label-success':evento.idEstadoEvento==5, 'label-danger':evento.idEstadoEvento==10}">
                                                {{evento.estadoEvento}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Fecha Evento</label>
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control" ng-model="evento.fechaEvento" data-date-format="dd/MM/yyyy" data-autoclose="1" bs-datepicker placeholder="Fecha del Evento" focus-enter>
                                        </div>
                                        <label class="col-xs-1">De</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" ng-model="evento.horaInicio" bs-timepicker data-time-format="HH:mm" data-time-type="string" placeholder="Hora Inicio" focus-enter>
                                        </div>
                                        <label class="col-xs-1">Para</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" ng-model="evento.horaFinal" bs-timepicker data-time-format="HH:mm" data-time-type="string" placeholder="Hora Final" focus-enter>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2"># Personas</label>
                                        <div class="col-xs-2">
                                            <input type="number" class="form-control" ng-model="evento.numeroPersonas" focus-enter>
                                        </div>
                                        <label class="col-xs-2">Salón</label>
                                        <div class="col-xs-4">
                                            <select ng-model="evento.idSalon" class="form-control">
                                                <option value="{{item.idSalon}}" ng-repeat="item in lstSalon">{{item.salon}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Observación</label>
                                        <div class="col-xs-10">
                                            <textarea rows="3" class="form-control" ng-model="evento.observacion"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px" ng-hide="$parent.accionEvento=='insert' && evento.idEvento>0">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" class="btn btn-success" ng-click="guardarEvento()" ng-disabled="$parent.idEstadoEvento!=1">
                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                <b>Guardar Información de Evento</b>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- $$$$$$ MENUS ORDENADOS $$$$$$ -->
                                <div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.idTab==2}" ng-show="$parent.idTab==2 && evento.idEvento>0">
                                   <div class="row" style="margin-top:5px">
                                        <!-- BOTON PARA AGREGAR MENU -->
                                        <div class="col-xs-12 text-right" style="margin-top:5px" ng-show="accionMenu==''">
                                            <button type="button" class="btn btn-sm btn-primary" ng-click="menuAccion( 'insert' )" ng-disabled="$parent.idEstadoEvento!=1">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <b>Agregar Menú / Servicio</b>
                                            </button>
                                        </div>

                                        <!-- ELIMINAR MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='delete'">
                                            <fieldset class="fieldset">
                                                <legend class="legend danger">Eliminar Menú / Servicio</legend>
                                                <div class="col-xs-12" style="margin-top:5px">
                                                    <h4>Eliminar <kbd>{{menu.cantidad}} {{menu.menu}}</kbd></h4>
                                                </div>
                                                <div class="col-xs-12 text-center" style="margin-top:5px">
                                                    <button type="button" class="btn btn-sm btn-default" ng-click="$parent.accionMenu=''">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        Cancelar
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" ng-click="guardarMenu()">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        <b>Eliminar</b>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <!-- FORMULARIO PARA MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='insert' || accionMenu=='update'">
                                            <fieldset class="fieldset">
                                                <legend class="legend info">
                                                    <span>{{accionMenu=='insert'?'Agregar':'Editar' }}</span> Menú / Servicio
                                                </legend>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="btn-group" style="margin-left:18px">
                                                            <button type="button" class="btn btn-default" ng-disabled="$parent.accionMenu=='update'">
                                                                <span ng-show="tipo=='menu'">Menú</span>
                                                                <span ng-show="tipo=='combo'">Combo</span>
                                                                <span ng-show="tipo=='otroMenu'">Menú Person.</span>
                                                                <span ng-show="tipo=='otroServicio'">Servicio</span>
                                                            </button>
                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-disabled="$parent.accionMenu=='update'">
                                                                <span class="caret"></span>
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="" ng-click="$parent.tipo='menu'">Menú</a>
                                                                </li>
                                                                <li>
                                                                    <a href="" ng-click="$parent.tipo='combo'">Combo</a>
                                                                </li>
                                                                <li>
                                                                    <a href="" ng-click="$parent.tipo='otroMenu'">Personalizado</a>
                                                                </li>
                                                                <li>
                                                                    <a href="" ng-click="$parent.tipo='otroServicio'">Servicio</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-8">
                                                        <input type="text" class="form-control" ng-model="menu.otroMenu" ng-if="tipo=='otroMenu' || tipo=='otroServicio'" id="inputMenu">
                                                        <select class="form-control" ng-model="menu.idMenu" ng-if="tipo!='otroMenu' && tipo!='otroServicio'" id="selectMenu">
                                                            <option value="{{item.idMenu}}" ng-repeat="item in lstMenu">{{item.menu}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>Cantidad</b>
                                                    <input type="number" ng-model="menu.cantidad" class="form-control" focus-enter>
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>P./Unidad</b>
                                                    <input type="number" ng-model="menu.precioUnitario" class="form-control" focus-enter>
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>Subtotal</b>
                                                    <input type="text" value="{{ menu.cantidad * menu.precioUnitario | number:2}}" class="form-control" disabled focus-enter>
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>Hora Despacho</b>
                                                    <input type="text" class="form-control" ng-model="menu.horaDespacho" bs-timepicker data-time-format="HH:mm" data-time-type="string" placeholder="Hora Despacho" focus-enter>
                                                </div>
                                                <div class="col-xs-12">
                                                    <b>Comentario</b>
                                                    <textarea rows="3" class="form-control" ng-model="menu.comentario"></textarea>
                                                </div>
                                                <div class="col-xs-12 text-right" style="margin-top:5px">
                                                    <button type="button" class="btn btn-sm btn-default" ng-click="$parent.accionMenu=''">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        Cancelar
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success" ng-click="guardarMenu()">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                        <b>Guardar</b>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <!-- LISTA DE MENUS -->
                                        <div class="col-sm-12" ng-show="accionMenu==''">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Tipo</th>
                                                        <th>H. Desp.</th>
                                                        <th>Menú</th>
                                                        <th>Cantidad</th>
                                                        <th>P./Unidad</th>
                                                        <th>Subtotal</th>
                                                        <th width="350px">Comentario</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="item in lstMenuEvento">
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-danger" ng-click="menuAccion( 'delete', item )" ng-disabled="$parent.idEstadoEvento!=1" data-title="Eliminar" data-placement="top" bs-tooltip>
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                        </td>
                                                        <td>{{item.tipo}}</td>
                                                        <td>{{item.horaDespacho}}</td>
                                                        <td>{{item.menu}}</td>
                                                        <td>{{item.cantidad}}</td>
                                                        <td>{{item.precioUnitario | number:2}}</td>
                                                        <td>
                                                            <b>{{(item.cantidad*item.precioUnitario) | number:2}}</b>
                                                        </td>
                                                        <td>{{item.comentario}}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-info" ng-click="menuAccion( 'update', item )" ng-disabled="$parent.idEstadoEvento!=1" data-title="Modificar" data-placement="top" bs-tooltip>
                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-right">
                                                <h4>
                                                    <b>Total Evento:</b> <kbd>Q. {{montoTotalEvento | number:2}}</kbd>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- MENUS ORDENADOS -->

                                <!-- $$$$$$ MOVIMIENTOS $$$$$$ -->
                                <div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.idTab==3}" ng-show="$parent.idTab==3 && evento.idEvento>0">
                                   <div class="row" style="margin-top:5px">
                                        <!-- BOTON PARA AGREGAR MENU -->
                                        <div class="col-xs-12 text-right" style="margin-top:5px" ng-show="accionMenu==''">
                                            <button type="button" class="btn btn-sm btn-primary" ng-click="menuAccion( 'insertMove' )" ng-disabled="$parent.idEstadoEvento!=1">
                                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                                <b>Agregar Anticipo</b>
                                            </button>
                                        </div>

                                        <!-- ELIMINAR MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='deleteMove'">
                                            <fieldset class="fieldset">
                                                <legend class="legend danger">Eliminar Anticipo</legend>
                                                <div class="col-xs-12" style="margin-top:5px">
                                                    <h4>Eliminar Anticipo</h4>
                                                </div>
                                                <div class="col-xs-12 text-center" style="margin-top:5px">
                                                    <button type="button" class="btn btn-sm btn-default" ng-click="$parent.accionMenu=''">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        Cancelar
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" ng-click="guardarMovimiento()">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        <b>Eliminar</b>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <!-- FORMULARIO PARA MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='insertMove'">
                                            <fieldset class="fieldset">
                                                <legend class="legend info">
                                                    Agregar Anticipo
                                                </legend>
                                                <div class="row">
                                                    <div class="col-xs-5">
                                                        <label>Forma de Pago:</label>
                                                        <select class="form-control" ng-model="movimiento.idFormaPago">
                                                            <option value="{{item.idFormaPago}}" ng-repeat="item in lstFormaPago">{{item.formaPago}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <label>Monto:</label>
                                                        <input type="text" class="form-control" ng-model="movimiento.monto">
                                                    </div>
                                                    <div class="col-xs-8">
                                                        <label>Descripción:</label>
                                                        <input type="text" class="form-control" ng-model="movimiento.motivo">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 text-right" style="margin-top:5px">
                                                    <button type="button" class="btn btn-sm btn-default" ng-click="$parent.accionMenu=''">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                        Cancelar
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-success" ng-click="guardarMovimiento()">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                        <b>Guardar</b>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </div>

                                        <!-- LISTA DE MENUS -->
                                        <div class="col-sm-12" ng-show="accionMenu==''">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Forma Pago</th>
                                                        <th>Monto</th>
                                                        <th>Descripción</th>
                                                        <th>Fecha Registro</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="item in evento.lstMovimiento">
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-danger" ng-click="menuAccion( 'deleteMove', item )" ng-disabled="$parent.idEstadoEvento!=1" data-title="Eliminar" data-placement="top" bs-tooltip>
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                        </td>
                                                        <td>{{item.formaPago}}</td>
                                                        <td>{{item.monto}}</td>
                                                        <td>{{item.motivo}}</td>
                                                        <td>{{formatoFecha( item.fechaRegistro, 'HH[:]mm [-] D[/]MM[/]YYYY' )}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- LISTA DE MENUS -->
                                    </div>
                                </div>
                                <!-- MOVIMIENTOS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- MODAL FACTURAR / FINALIZAR EVENTO -->
<script type="text/ng-template" id="facturar.evento.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dl_evento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-calendar"></span>
                    <span>FINALIZAR Y FACTURAR EVENTO » # {{evento.idEvento}}</span>
                </div>
                <div class="modal-body">
                    <!-- FINALIZAR EVENTO -->
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h3 class="text-primary">{{evento.evento}} » {{formatoFecha( evento.fechaEvento, 'ddd D [de ] MMM [de] YYYY' )}}</h3>
                            <h4>Cliente: <span ng-bind-html="evento.nombreCliente"></span></h4>
                            <hr>
                        </div>
                        <!-- DETALLE DE ORDENES :::EVENTO::: -->
                        <div class="col-xs-7">
                            <fieldset class="fieldset">
                                <legend class="legend warning">DETALLE</legend>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Menú / Servicio</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in evento.lstMenuEvento">
                                            <td>{{item.cantidad}}</td>
                                            <td>{{item.menu}} ({{item.tipo}})</td>
                                            <td>
                                                <b data-title="{{item.precioUnitario}} c/u" data-placement="top" bs-tooltip>Q. {{item.subTotal}}</b>
                                            </td>
                                        </tr>
                                        <tr class="text-success">
                                            <td colspan="2">
                                                <h4 class="text-right"><b>Total Evento</b></h4>
                                            </td>
                                            <td>
                                                <h4><b>Q. {{evento.totalEvento | number:2}}</b></h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>

                            <!-- DETALLE DE ORDENES :::EVENTO::: -->
                            <fieldset class="fieldset">
                                <legend class="legend default">ANTICIPO</legend>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Monto</th>
                                            <th>Descripción</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in evento.lstMovimiento">
                                            <td>{{item.monto | number:2}} ({{item.formaPago}})</td>
                                            <td>{{item.motivo}}</td>
                                            <td>{{item.fechaRegistro}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                        <!-- PAGO :::EVENTO::: -->
                        <div class="col-xs-5">
                            <fieldset class="fieldset">
                                <legend class="legend success">PAGO DE EVENTO</legend>
                                <h3 style="margin-top:0" class="text-right">
                                    <b class="label label-info">Q. {{ evento.totalEvento - evento.totalAnticipo | number:2 }}</b>
                                </h3>
                                <!-- DETALLE DE PAGO -->
                                <div class="row" ng-hide="evento.idFactura>0">
                                    <div class="form-group">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button">
                                                    <b>Q.</b>
                                                </button>
                                            </span>
                                            <input type="number" class="form-control" ng-model="evento.montoEfectivo" ng-change="montoCambioEvento()" placeholder="Efectivo" focus-enter>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button">
                                                    <span class="glyphicon glyphicon-credit-card"></span>
                                                </button>
                                            </span>
                                            <input type="number" class="form-control" ng-model="evento.montoTarjeta" ng-change="montoCambioEvento()" placeholder="Monto Tarjeta" focus-enter>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <b>CAMBIO</b>
                                            </span>
                                            <input type="text" class="form-control" ng-model="evento.cambio" placeholder="Q." disabled style="background:#fff">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <b>Descripción Factura</b>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-default" ng-click="evento.siDetalle=true">
                                                <span class="glyphicon" ng-class="{'glyphicon-check': evento.siDetalle, 'glyphicon-unchecked': !evento.siDetalle}"></span> Detalle
                                            </button>
                                            <button type="button" class="btn btn-default" ng-click="evento.siDetalle=false">
                                                <span class="glyphicon" ng-class="{'glyphicon-check': !evento.siDetalle, 'glyphicon-unchecked': evento.siDetalle}"></span> Personalizado
                                            </button>
                                        </div>
                                        <div class="col-sm-12" ng-show="!evento.siDetalle" style="margin-top:5px">
                                            <textarea ng-model="evento.descripcion" placeholder="Ingrese Descripción de la Factura" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <a href="print.php?id={{evento.idFactura}}&isEvent=1" target="_blank" class="btn btn-primary" ng-show="evento.idFactura>0">
                                    <span class="glyphicon glyphicon-print"></span>
                                    Reimprimir Facturar Evento
                                </a>
                                <button type="button" class="btn btn-success" ng-click="facturarEvento()" ng-hide="evento.idFactura>0">
                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                    Facturar Evento
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" ng-click="$hide();showDialOrden( 'update', evento, 5 )" ng-show="evento.idFactura>0 && evento.idEstadoEvento==1">
                        <span class="glyphicon glyphicon-flag"></span>
                        Finalizar Evento
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


