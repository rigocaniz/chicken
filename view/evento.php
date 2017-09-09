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
	</div>
	<div class="row contenedor-tickets">
        <div class="col-xs-12" ng-hide="lstEvento.length">
            <div class="alert alert-info" role="alert">No existen ordenes</div>
        </div>

        <!-- *********** INFORMACION DE EVENTOS ********* -->
        <div class="col-xs-12 info-orden-ticket" ng-repeat="evento in lstEvento" style="margin-bottom:13px">
            <!-- *********** INFORMACION DE ORDEN ********* -->
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h4>
                        <b>Evento # </b> {{evento.idEvento}} » 
                        <span>{{evento.evento}}</span>
                        <button type="button" class="btn btn-xs btn-danger" title="Cancelar" data-toggle="tooltip" data-placement="top" tooltip>
                            <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="showDialOrden( 'update', evento )" title="Modificar" data-toggle="tooltip" data-placement="top" tooltip>
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </h4>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <h4><b>Fecha Evento:</b> {{formatoFecha( evento.fechaEvento, 'dddd D [de ] MMM [de] YYYY' )}}</h4>
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
                <div class="col-xs-6">
                    <span class="etiqueta">Observación: </span>
                    <span class="valor">{{evento.observacion}}</span>
                </div>
                <div class="col-xs-2">
                    <span class="etiqueta">Usuario: </span>
                    <span class="valor">{{evento.usuario}}</span>
                </div>
                <div class="col-xs-4">
                    <span class="etiqueta">Registro: </span>
                    <span class="valor">{{formatoFecha( evento.fechaRegistro, 'D[/]MM[/]YYYY [-] HH:mm' )}}</span>
                </div>
            </div>
            <!--
            <legend class="legend2" style="cursor:pointer" ng-click="evento.showMenus=!evento.showMenus">Menús Evento</legend>
            <div class="row" ng-show="evento.showMenus">
                <div class="col-xs-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Menú</th>
                                <th>Cantidad</th>
                                <th>P./Unidad</th>
                                <th>Subtotal</th>
                                <th width="350px">Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in evento.lstMenu">
                                <td>{{item.tipo}}</td>
                                <td>{{item.menu}}</td>
                                <td>{{item.cantidad}}</td>
                                <td>{{item.precioUnitario | number:2}}</td>
                                <td>
                                    <b>{{(item.cantidad*item.precioUnitario) | number:2}}</b>
                                </td>
                                <td>{{item.comentario}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
           </div>-->
        </div>
	</div>
</div>

<!-- MODAL EVENTO -->
<script type="text/ng-template" id="dl.evento.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dl_evento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" ng-class="{'panel-success':accionEvento=='insert', 'panel-primary':accionEvento=='update'}">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-calendar"></span>
                    <span ng-show="accionEvento=='update'">Modificar Evento » # {{evento.idEvento}}</span>
                    <span ng-show="accionEvento=='insert'">Nuevo Evento</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- TABS -->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs tabs-title" role="tablist">
                                <li role="presentation" ng-class="{'active' : idTab==1}" ng-click="idTab=1">
                                    <a href="" role="tab" data-toggle="tab">
                                        <span class="glyphicon glyphicon-calendar"></span> Datos del Evento
                                    </a>
                                </li>
                                <li role="presentation" ng-class="{'active' : idTab==2}" ng-click="idTab=2">
                                    <a href="" role="tab" data-toggle="tab">
                                        <span class="glyphicon glyphicon-cutlery"></span> Menús
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- CONTENT-TABS -->
                        <div class="col-sm-12">
                            <div class="tab-content">
                                <!--  INFORMACION EVENTO -->
                                <div role="tabpanel" class="tab-pane" ng-class="{'active' : idTab==1}" ng-show="idTab==1">
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
                                            <input type="text" class="form-control" ng-model="$parent.busquedaCliente" id="busquedaCliente">
                                            <div class="lstResultado" ng-show="lstResultado.length">
                                                <ul>
                                                    <li ng-repeat="item in lstResultado" ng-click="seleccionarCliente( item )" ng-class="{'active':item.active}" ng-bind-html="item.cliente">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                	<div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Evento</label>
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control" ng-model="evento.evento" placeholder="Descripción de evento" id="evento">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Fecha Evento</label>
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control" ng-model="evento.fechaEvento" data-date-format="dd/MM/yyyy" data-autoclose="1" bs-datepicker>
                                        </div>
                                        <label class="col-xs-1">De</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" ng-model="evento.horaInicio" bs-timepicker data-time-format="HH:mm" data-time-type="string">
                                        </div>
                                        <label class="col-xs-1">Para</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" ng-model="evento.horaFinal" bs-timepicker data-time-format="HH:mm" data-time-type="string">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Anticipo</label>
                                        <div class="col-xs-3">
                                            <input type="number" class="form-control" ng-model="evento.anticipo" placeholder="Q. ">
                                        </div>
                                        <label class="col-xs-3">Cantidad de Personas</label>
                                        <div class="col-xs-3">
                                            <input type="number" class="form-control" ng-model="evento.numeroPersonas">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <label class="col-xs-2">Observación</label>
                                        <div class="col-xs-10">
                                            <textarea rows="5" class="form-control" ng-model="evento.observacion"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:5px">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" class="btn btn-success" ng-click="guardarEvento()">
                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                <b>Guardar Información de Evento</b>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- $$$$$$ MENUS ORDENADOS $$$$$$ -->
                                <div role="tabpanel" class="tab-pane" ng-class="{'active' : idTab==2}" ng-show="idTab==2">
                                   <div class="row" style="margin-top:5px">
                                        <!-- BOTON PARA AGREGAR MENU -->
                                        <div class="col-xs-12 text-right" style="margin-top:5px" ng-show="accionMenu==''">
                                            <button type="button" class="btn btn-sm btn-primary" ng-click="$parent.accionMenu='insert'">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <b>Agregar Menú</b>
                                            </button>
                                        </div>

                                        <!-- FORMULARIO PARA MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='insert' || accionMenu=='update'">
                                            <fieldset class="fieldset">
                                                <legend class="legend info">
                                                    <span>{{accionMenu=='insert'?'Agregar':'Editar' }}</span> Menú
                                                </legend>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="btn-group" style="margin-left:18px">
                                                            <button type="button" class="btn btn-default">
                                                                <span ng-show="tipo=='menu'">Menú</span>
                                                                <span ng-show="tipo=='combo'">Combo</span>
                                                                <span ng-show="tipo=='otroMenu'">Personalizado</span>
                                                            </button>
                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="caret"></span>
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="" ng-click="$parent.tipo='menu'">Menú</a></li>
                                                                <li><a href="" ng-click="$parent.tipo='combo'">Combo</a></li>
                                                                <li><a href="" ng-click="$parent.tipo='otroMenu'">Personalizado</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-8">
                                                        <input type="text" class="form-control" ng-model="menu.otroMenu" ng-show="tipo=='otroMenu'" id="inputMenu">
                                                        <select class="form-control" ng-model="menu.idMenu" ng-show="tipo!='otroMenu'" id="selectMenu">
                                                            <option value="{{item.idMenu}}" ng-repeat="item in lstMenu">{{item.menu}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>Cantidad</b>
                                                    <input type="number" ng-model="menu.cantidad" class="form-control">
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>P./Unidad</b>
                                                    <input type="number" ng-model="menu.precioUnitario" class="form-control">
                                                </div>
                                                <div class="col-xs-4">
                                                    <b>Subtotal</b>
                                                    <input type="text" value="{{ menu.cantidad * menu.precioUnitario | number:2}}" class="form-control" disabled>
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
                                                            <button type="button" class="btn btn-xs btn-danger" ng-click="eliminarMenu( $index )" title="Eliminar" data-toggle="tooltip" data-placement="top" tooltip>
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                        </td>
                                                        <td>{{item.tipo}}</td>
                                                        <td>{{item.menu}}</td>
                                                        <td>{{item.cantidad}}</td>
                                                        <td>{{item.precioUnitario | number:2}}</td>
                                                        <td>
                                                            <b>{{(item.cantidad*item.precioUnitario) | number:2}}</b>
                                                        </td>
                                                        <td>{{item.comentario}}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-info" ng-click="modificarMenu( $index )" title="Modificar" data-toggle="tooltip" data-placement="top" tooltip>
                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- MENUS ORDENADOS -->
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
