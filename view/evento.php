<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
        <div class="col-sm-4 col-xs-5" style="margin-bottom:9px">
            <button type="button" class="btn btn-success" ng-click="nuevaOrden()">
                <span class="glyphicon glyphicon-plus"></span>
                <u>N</u>uevo Evento
                <span class="glyphicon glyphicon-calendar"></span>
            </button>
        </div>
		<div class="col-sm-offset-2 col-sm-5 col-xs-6">
            <div class="input-group">
                <input type="number" class="form-control" ng-model="buscarTicket" id="buscarTicket" ng-class="{'input-focus':buscarTicket>0}"
                    placeholder="Ingrese # Ticket + ENTER" style="font-size:19px;padding: 1px 14px;font-weight:normal">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" ng-click="auxKeyTicket( 'supr', 0, 'buscarTicket' )">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </span>
            </div>
		</div>
		<div class="col-xs-12" style="margin-top:5px">
			<div class="btn-orden">
				<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="idEstadoOrden=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs"><u>P</u>endientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="idEstadoOrden=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs"><u>E</u>n Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="idEstadoOrden=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs"><u>F</u>inalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="idEstadoOrden=10">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs"><u>C</u>ancelados</span>
				</button>
			</div>
		</div>
	</div>
	<div class="row contenedor-tickets">
        <!-- *********** LISTA DE TICKETS ********* -->
        <div class="col-xs-12 col-sm-2 hidden-xs">
            <div class="list-group">
                <button type="button" class="list-group-item" ng-repeat="item in lstOrdenCliente track by $index" ng-class="{'active':$index==miIndex}"
                    ng-click="seleccionarTicket( item.idOrdenCliente )" ng-hide="deBusqueda">
                    <span class="tkt-active"></span>
                    <span class="glyphicon glyphicon-bookmark"></span>
                    {{item.numeroTicket}}
                    <span class="badge">{{item.numMenu}}</span>
                </button>
            </div>
        </div>
        
        <div class="col-xs-12" ng-hide="lstOrdenCliente.length || deBusqueda">
            <div class="alert alert-info" role="alert">No existen ordenes</div>
        </div>

        <!-- *********** INFORMACION DE ORDEN ********* -->
        <div class="col-xs-12 col-sm-10 info-orden-ticket" ng-show="lstOrdenCliente.length || deBusqueda">
            <!-- BOTONES PARA AVANZAR -->
            <div class="row visible-xs" ng-show="!deBusqueda">
                <div class="col-xs-12 text-center">
                    <button type="button" class="btn btn-sm btn-default" ng-click="miIndex=0">
                        <span class="glyphicon glyphicon-fast-backward"></span>
                    </button>
                    <button type="button" class="btn btn-sm btn-default" ng-click="downUpOrdenes( true )">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Anterior</b>
                    </button>
                    <span class="badge" style="font-size:17px">{{ ( miIndex + 1 ) + " de " + lstOrdenCliente.length }}</span>
                    <button type="button" class="btn btn-sm btn-default" ng-click="downUpOrdenes( false )">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <b>Siguiente</b>
                    </button>
                    <button type="button" class="btn btn-sm btn-default" ng-click="miIndex = ( lstOrdenCliente.length - 1 )">
                        <span class="glyphicon glyphicon-fast-forward"></span>
                    </button>
                </div>
            </div>
            <!-- *********** INFORMACION DE ORDEN ********* -->
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h4>
                        <b>Orden # </b> {{infoOrden.idOrdenCliente}} » 
                        <span class="badge-ticket">Ticket # {{infoOrden.numeroTicket}}</span>
                    </h4>
                </div>
                <div class="col-sm-6 col-xs-12 text-right" ng-show="infoOrden.idEstadoOrden==1">
                    <button type="button" class="btn btn-sm btn-danger" ng-click="dialOrdenCancelar.show()">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>Cancelar Orden</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <span class="etiqueta">Estado: </span>
                    <span class="valor">{{infoOrden.estadoOrden}}</span>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <span class="etiqueta">Lapso: </span>
                    <span class="valor">{{tiempoTranscurrido( infoOrden.fechaRegistro )}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <span class="etiqueta"># Menús: </span>
                    <span class="valor">{{infoOrden.numMenu}}</span>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <span class="etiqueta">Responsable: </span>
                    <span class="valor">{{infoOrden.usuarioResponsable}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center" ng-show="infoOrden.idEstadoOrden==1 || infoOrden.idEstadoOrden==2">
                    <button type="button" class="btn btn-info" ng-click="consultaOrden( infoOrden )">
                        <span class="glyphicon glyphicon-plus"></span>
                        <b><u>A</u>gregar Orden</b>
                    </button>
                </div>
            </div>
            <legend class="legend2">Menús Ordenados</legend>
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Cantidad</th>
                                    <th>Orden</th>
                                    <th>Subtotal</th>
                                    <th width="35px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in infoOrden.lstOrden track by $index">
                                    <td><img ng-src="{{item.imagen}}" style="height:40px"></td>
                                    <td>{{item.cantidad}}</td>
                                    <td>
                                        <button type="button" class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}"
                                            ng-click="item.showTipoServicio=!item.showTipoServicio">
                                            <span ng-show="item.idTipoServicio==2">R</span>
                                            <span ng-show="item.idTipoServicio==3">D</span>
                                            <span ng-show="item.idTipoServicio==1">L</span>
                                            <span class="lst_servicio" ng-show="item.showTipoServicio">
                                                <ul>
                                                    <li class="list-group-item" ng-hide="item.idTipoServicio==2" ng-click="cambiarServicio( infoOrden.idOrdenCliente, item.lstDetalle, 2 )">Restaurante</li>
                                                    <li class="list-group-item" ng-hide="item.idTipoServicio==3" ng-click="cambiarServicio( infoOrden.idOrdenCliente, item.lstDetalle, 3 )">A Domicilio</li>
                                                    <li class="list-group-item" ng-hide="item.idTipoServicio==1" ng-click="cambiarServicio( infoOrden.idOrdenCliente, item.lstDetalle, 1 )">Para Llevar</li>
                                                </ul>
                                            </span>
                                        </button>
                                        <span class="glyphicon glyphicon-gift" ng-show="item.esCombo"></span>
                                        <span>{{item.descripcion}}</span>
                                    </td>
                                    <td>{{item.subTotal | number:2}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" ng-click="cancelarOrdenParcial( infoOrden.idOrdenCliente, item.lstDetalle )">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td><td></td><td>TOTAL</td>
                                    <td><b>Q. {{infoOrden.total | number:2}}</b></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
           </div>
        </div>
	</div>
</div>

<!-- MODAL EVENTO -->
<script type="text/ng-template" id="dl.evento.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dl_evento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-calendar"></span>
                    Nuevo Evento
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
                                            <input type="text" class="form-control" ng-model="$parent.busquedaCliente">
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
                                            <input type="text" class="form-control" ng-model="evento.horaInicio" bs-timepicker data-time-format="hh:mm" data-time-type="string">
                                        </div>
                                        <label class="col-xs-1">Para</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" ng-model="evento.horaFinal" bs-timepicker data-time-format="hh:mm" data-time-type="string">
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
                                            <button type="button" class="btn btn-sm btn-primary" ng-click="accionMenu='insert'">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <b>Agregar Menú</b>
                                            </button>
                                        </div>

                                        <!-- FORMULARIO PARA MENU -->
                                        <div class="col-sm-12" ng-show="accionMenu=='insert' || accionMenu=='update'">
                                            <fieldset class="fieldset">
                                                <legend class="legend info">
                                                    <span>{{ accionMenu=='insert'?'Agregar':'Editar' }}</span> Menú
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
                                                    <textarea rows="2" class="form-control" ng-model="menu.comentario"></textarea>
                                                </div>
                                                <div class="col-xs-12 text-right" style="margin-top:5px">
                                                    <button type="button" class="btn btn-sm btn-default" ng-click="accionMenu=''">
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
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-danger" ng-click="eliminarMenu()" title="Eliminar" data-toggle="tooltip" data-placement="top" tooltip>
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </button>
                                                        </td>
                                                        <td>Menú</td>
                                                        <td>Combo Familiar</td>
                                                        <td>30</td>
                                                        <td>20</td>
                                                        <td>
                                                            <b>Q. 600.00</b>
                                                        </td>
                                                        <td>No le gusta que le den pocas papas, el quiere con muchas papas por el tipo de evento no importa el costo</td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-info" ng-click="modificarMenu()" title="Modificar" data-toggle="tooltip" data-placement="top" tooltip>
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
