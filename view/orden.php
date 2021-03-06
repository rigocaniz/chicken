<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 1 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
        <div class="col-sm-5 col-xs-6" style="margin-bottom:9px">
            <button type="button" class="btn btn-success" ng-click="openNuevaOrden()">
                <span class="glyphicon glyphicon-plus"></span>
                Nue<u>v</u>a Orden
            </button>
            <button type="button" class="btn btn-default" ng-click="dialUltimasOrdenes.show()">
                <span class="glyphicon glyphicon-print"></span>
                Últimas Ordenes
            </button>
            <button type="button" class="btn btn-info" ng-click="dlAyuda.show()">
                <span class="glyphicon glyphicon-question-sign"></span>
            </button>
        </div>
		<div class="col-sm-offset-2 col-sm-4 col-xs-5">
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
				<button class="bt-info" ng-class="{'active':(idEstadoOrden==1 || idEstadoOrden==2)}" ng-click="idEstadoOrden=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs"><u>P</u>endientes</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="idEstadoOrden=3">
					<span class="glyphicon glyphicon-ok"></span>
					<span class="hidden-xs"><u>L</u>isto</span>
				</button>
                <button class="bt-primary" ng-class="{'active':idEstadoOrden==4}" ng-click="idEstadoOrden=4">
                    <span class="glyphicon glyphicon-flag"></span>
                    <span class="hidden-xs"><u>F</u>inalizados</span>
                </button>
                <button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="idEstadoOrden=10">
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="hidden-xs"><u>C</u>ancelados</span>
                </button>
                <button class="bt-primary" ng-class="{'active':idEstadoOrden==6}" ng-click="idEstadoOrden=6">
                    <span class="glyphicon glyphicon-shopping-cart"></span>
                    <span class="hidden-xs">Facturado<u>s</u></span>
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
                    <span class="glyphicon" ng-class="{'glyphicon-bookmark':(item.numeroTicket>0), 'glyphicon-home':!(item.numeroTicket>0)}"></span>
                    {{(item.numeroTicket>0?item.numeroTicket:item.idOrdenCliente)}}
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
                <div class="col-sm-6 col-xs-12 text-right">
                	<a href="#/factura/{{infoOrden.idOrdenCliente}}" class="btn btn-sm btn-primary" ng-show="infoOrden.idEstadoOrden>=1 && infoOrden.idEstadoOrden<=4">
                        <span class="glyphicon glyphicon-shopping-cart"></span>
                        <b>Facturar</b> (F4)
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" ng-click="dialOrdenCancelar.show();comentario=''" ng-show="infoOrden.idEstadoOrden==1">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>Cancelar Orden</b>
                    </button>
                </div>
            </div>
            <div class="row" ng-show="infoOrden.nit.length>0">
                <div class="col-sm-4 col-xs-4">
                    <span class="etiqueta">NIT: </span>
                    <span class="valor">{{infoOrden.nit}}</span>
                </div>
                <div class="col-sm-8 col-xs-8">
                    <span class="etiqueta">Cliente: </span>
                    <span class="valor">{{infoOrden.nombre}}</span>
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
                <div class="col-xs-12 text-center" ng-show="infoOrden.idEstadoOrden>=1 && infoOrden.idEstadoOrden<=4">
                    <button type="button" class="btn btn-info" ng-click="consultaOrden( infoOrden )">
                        <span class="glyphicon glyphicon-plus"></span>
                        <b><u>A</u>gregar Menú</b>
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
                                    <th width="35px"></th>
                                    <th></th>
                                    <th>Cantidad</th>
                                    <th>Orden</th>
                                    <th>Subtotal</th>
                                    <th width="35px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in infoOrden.lstOrden track by $index">
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default" ng-click="editarDetalle( item )" data-title="<b>Editar Orden</b>" data-placement="top" ng-disabled="idEstadoOrden!=1 && idEstadoOrden!=2" bs-tooltip>
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </td>
                                    <td>
                                        <img ng-src="{{item.imagen}}" style="height:40px">
                                    </td>
                                    <td>{{item.cantidad}}</td>
                                    <td>
                                        <button type="button" class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
                                            <span ng-show="item.idTipoServicio==2" data-title="<b>Restaurante</b>" data-placement="top" bs-tooltip>R</span>
                                            <span ng-show="item.idTipoServicio==3" data-title="<b>A Domicilio</b>" data-placement="top" bs-tooltip>D</span>
                                            <span ng-show="item.idTipoServicio==1" data-title="<b>Para Llevar</b>" data-placement="top" bs-tooltip>L</span>
                                        </button>
                                        <span class="glyphicon glyphicon-gift" ng-show="item.esCombo"></span>
                                        <span>{{item.descripcion}}</span>
                                        <span class="estado-menu {{est.css}}" ng-repeat="est in item.estados" data-title="{{est.title}}" data-placement="top" bs-tooltip>{{est.abr}} <span class="badge">{{est.total}}</span></span>
                                        <p ng-show="item.observacion.length" class="label label-primary">
                                            <span class="glyphicon glyphicon-star"></span>
                                            {{item.observacion}}
                                        </p>
                                    </td>
                                    <td>{{item.subTotal | number:2}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" ng-click="cancelarDetalle( item.lstDetalle )" data-title="Cancelar" data-placement="top" bs-tooltip ng-disabled="idEstadoOrden!=1 && idEstadoOrden!=2">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr ng-repeat="otro in infoOrden.lstOtros track by $index">
                                    <td></td>
                                    <td>
                                        <img ng-src="img/otroMenu.png" style="height:40px" class="img-thumbnail">
                                    </td>
                                    <td>{{otro.cantidad}}</td>
                                    <td>
                                        <b>{{otro.descripcion}}</b>
                                        <p ng-show="otro.observacion.length" class="label label-primary">
                                            <span class="glyphicon glyphicon-star"></span>
                                            {{otro.observacion}}
                                        </p>
                                    </td>
                                    <td>{{(otro.cantidad*otro.precioUnidad) | number:2}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" ng-click="cancelarOtroMenu( otro )" data-title="Eliminar" data-placement="top" bs-tooltip ng-disabled="idEstadoOrden!=1 && idEstadoOrden!=2">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>TOTAL</b></td>
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


<!-- NUEVA ORDEN -->
<script type="text/ng-template" id="dial.orden.nueva.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_nueva">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<span class="glyphicon glyphicon-plus"></span>
                    Nueva Orden
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-5">
                			<h4># Ticket</h4>
                		</div>
                		<div class="col-xs-7">
							<div class="input-group">
								<input type="number" min="1" class="form-control input-lg input-focus" ng-model="$parent.noTicket" id="noTicket">
								<span class="input-group-btn">
									<button class="btn btn-lg btn-info" type="button" ng-click="auxKeyTicket( 'supr', 0, 'noTicket' )">
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</span>
							</div>
                		</div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 7, 'noTicket' )">
                                <u>7</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 8, 'noTicket' )">
                                <u>8</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 9, 'noTicket' )">
                                <u>9</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 4, 'noTicket' )">
                                <u>4</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 5, 'noTicket' )">
                                <u>5</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 6, 'noTicket' )">
                                <u>6</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 1, 'noTicket' )">
                                <u>1</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 2, 'noTicket' )">
                                <u>2</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 3, 'noTicket' )">
                                <u>3</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="padding:10px 36px" ng-click="auxKeyTicket( 'number', 0, 'noTicket' )">
                                <u>0</u>
                            </button>
                            <button class="btn btn-lg btn-danger" style="margin-bottom:4px" ng-click="auxKeyTicket( 'back', 0, 'noTicket' )">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </button>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-primary" ng-click="nuevaOrden( true )" ng-disabled="noTicket>0">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>A <u>D</u>omicilio</b>
                    </button>
                	<button type="button" class="btn btn-success" ng-click="nuevaOrden()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>Agregar Ticket</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b><u>S</u>alir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 

<!-- ORDEN CLIENTE -->
<script type="text/ng-template" id="dial.orden.cliente.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_cliente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <h4>
                    	<span ng-show="ordenActual.idOrdenCliente>0">Orden # <span class="badge-orden">{{ordenActual.idOrdenCliente}}</span></span> - 
                    	<span ng-show="ordenActual.noTicket>0">Ticket: <span class="badge-ticket">{{ordenActual.noTicket}}</span></span>
                        <span class="label label-primary" style="float:right">Confirmar Orden</span>
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- MENU PERSONALIZADO -->
                    <div class="row" ng-show="menuPer.si">
                        <div class="col-sm-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:125px">Cantidad</th>
                                        <th>Descripción</th>
                                        <th style="width:130px">Precio/Unidad</th>
                                        <th style="width:75px">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="number" min="1" class="form-control" ng-model="$parent.menuPer.cantidad">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" ng-model="$parent.menuPer.descripcion">
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" ng-model="$parent.menuPer.precioUnidad">
                                        </td>
                                        <td><b>{{$parent.menuPer.cantidad*$parent.menuPer.precioUnidad | number:2}}</b></td>
                                        <td>
                                            <button type="button" class="btn btn-primary" ng-click="agregarOtroMenu()">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <b>Agregar</b>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default" ng-click="$parent.menuPer={}">
                                <span class="glyphicon glyphicon-remove"></span>
                                Cancelar Menú Personalizado
                            </button>
                        </div>
                    </div>

                    <!-- MENU DEFINIDO -->
                	<div class="row" ng-hide="menuPer.si">
                        <div class="col-xs-6" style="margin-bottom:5px">
                            <button type="button" class="btn btn-primary" ng-click="openCantidad()">
                                <span class="glyphicon glyphicon-plus"></span>
                                <b><u>A</u>gregar Menú</b>
                            </button>
                        </div>
                		<div class="col-xs-6 text-right" style="margin-bottom:5px">
                            <button type="button" class="btn btn-info" ng-click="menuPer.si=true">
                                <span class="glyphicon glyphicon-plus"></span>
                                <b>Menú Personalizado</b>
                            </button>
                		</div>
                		<div class="col-xs-12">
							<ul class="nav nav-tabs">
								<li ng-class="{'active':$parent.filtroServicio==''}">
									<a href="" ng-click="$parent.filtroServicio=''">
										<span class="badge">{{ordenActual.lstAgregar.length}}</span>
										Todos
									</a>
								</li>
								<li ng-class="{'active':$parent.filtroServicio==1}">
									<a href="" ng-click="$parent.filtroServicio=1">
										<span class="badge">{{ (ordenActual.lstAgregar | filter : { idTipoServicio : 1 } ).length }}</span>
										Para Llevar
									</a>
								</li>
								<li ng-class="{'active':$parent.filtroServicio==2}">
									<a href="" ng-click="$parent.filtroServicio=2">
										<span class="badge">{{ (ordenActual.lstAgregar | filter : { idTipoServicio : 2 } ).length }}</span>
										Restaurante
									</a>
								</li>
								<li ng-class="{'active':$parent.filtroServicio==3, 'inactive':ordenActual.noTicket>0}">
									<a href="" ng-click="$parent.filtroServicio=3">
										<span class="badge">{{ (ordenActual.lstAgregar | filter : { idTipoServicio : 3 } ).length }}</span>
										A Domicilio
									</a>
								</li>
							</ul>
                			<table class="table table-condensed table-hover">
                				<thead>
                					<tr>
                						<th width="110px">Cantidad</th>
                                        <th>Orden</th>
                						<th>Observación</th>
                						<th>Subtotal</th>
                						<th width="35px"></th>
                					</tr>
                				</thead>
                				<tbody>
                					<tr ng-repeat="item in ordenActual.lstAgregar | filter : { idTipoServicio : $parent.filtroServicio } track by $index">
                						<td>
                							<button type="button" class="btn btn-xs btn-default" ng-click="ordenCantidad( ordenActual.lstAgregar.indexOf( item ), 0, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-minus"></span>
                							</button>
                                            <input type="text" class="input-sm" ng-model="item.cantidad" ng-change="total()" style="width:43px;border:solid 1px #ccc;padding: 0 7px;">
                							<button type="button" class="btn btn-xs btn-info" ng-click="ordenCantidad( ordenActual.lstAgregar.indexOf( item ), 1, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-plus"></span>
                							</button>
                						</td>
                						<td>
                							<span class="glyphicon glyphicon-gift" ng-show="item.tipoMenu=='combo'"></span>
                							<span>
                								{{item.menu}} ({{item.tipoMenu}}) 
                                            </span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" ng-model="item.observacion">
                                        </td>
                						<td>{{(item.precio*item.cantidad) | number:2}}</td>
                						<td>
                							<button type="button" class="btn btn-xs btn-danger" ng-click="quitarElemento( ordenActual.lstAgregar.indexOf( item ), item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-remove"></span>
                							</button>
                						</td>
                					</tr>
                				</tbody>
                			</table>
                		</div>
                		<div class="col-xs-12">
                			<h4>
                				<b>TOTAL: </b> <span class="badge" style="font-size:1.1em">Q. {{$parent.ordenActual.totalAgregar | number:2}}</span>
                			</h4>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
        			<button type="button" class="btn btn-success" ng-click="agregarOrden()">
        				<span class="glyphicon glyphicon-ok"></span>
        				<b>Confirmar Orden (F6)</b>
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

<!-- INGRESO DE CANTIDAD Y CODIGO DE MENU -->
<script type="text/ng-template" id="dial.cantidad.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_cantidad">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                    <b>Ingrese Cantidad Menú</b>
                </div>
                <div class="modal-body">
                	<div class="row">
                        <div class="col-sm-12">
                            <div class="col-xs-4">
                                <h4>Tipo de Servicio</h4>
                            </div>
                            <div class="col-xs-8">
                                <button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=1,'btn-info':idTipoServicio==1}" 
                                    ng-click="$parent.idTipoServicio=1" ng-disabled="!(ordenActual.noTicket>0)" style="margin-right:4px;margin-top:5px">
                                    <b>Para <u>L</u>levar</b>
                                    <span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==1"></span>
                                </button>
                                <button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=2,'btn-info':idTipoServicio==2}" 
                                    ng-click="$parent.idTipoServicio=2" ng-disabled="!(ordenActual.noTicket>0)" style="margin-right:4px;margin-top:5px">
                                    <b><u>R</u>estaurante</b>
                                    <span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==2"></span>
                                </button>
                                <button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=3,'btn-info':idTipoServicio==3}" 
                                    ng-click="$parent.idTipoServicio=3" style="margin-right:4px;margin-top:5px" ng-disabled="!ordenActual.domicilio">
                                    <b>A <u>D</u>omicilio</b>
                                    <span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==3"></span>
                                </button>
                            </div>
                        </div>
                		<div class="col-xs-12 col-sm-6 col-sm-offset-3">
                			<h3 class="text-center">Cantidad</h3>
							<div class="input-group">
								<span class="input-group-btn">
		                            <button type="button" class="btn btn-lg btn-default" ng-click="$parent.cantidadInicial = changeInt( $parent.cantidadInicial, false, 1, 500 )">
		                                <span class="glyphicon glyphicon-minus"></span>
		                            </button>
								</span>
                				<input type="text" class="form-control input-lg" ng-model="$parent.cantidadInicial" id="cantidadInicial" numbers-only autocomplete="off">
								<span class="input-group-btn">
		                            <button type="button" class="btn btn-lg btn-default" ng-click="$parent.cantidadInicial = changeInt( $parent.cantidadInicial, true, 1, 500 )">
		                                <span class="glyphicon glyphicon-plus"></span>
		                            </button>
								</span>
							</div>
                		</div>
                    </div>
                    <!-- CODIGO O MODAL ( MENU | COMBO ) -->
            		<div class="row" style="margin-top: 6px" ng-hide="menuActual.idMenu>0">
                        <div class="col-xs-12">
                    		<input type="text" class="form-control input-lg" ng-model="$parent.codigoRapido" id="codigoRapido"
                                ng-change="consultaMenuPorCodigo()" placeholder="Código Rápido | Menú" autocomplete="off">
                            <ul class="list-group ul-list" style="cursor:pointer">
                                <li class="list-group-item" ng-class="{'active': $parent.menuActual.ixMenu == ixMenu}" 
                                    ng-repeat="(ixMenu, coin) in lstCoincidencias"
                                    ng-click="$parent.menuActual.ixMenu = ixMenu; _keyCantidad( 13, false, true )">
                                    <label class="label label-danger">#{{coin.menu.codigo}}</label> » <strong>{{coin.menu.menu}}</strong> 
                                    <kbd>{{coin.tipoMenu}}</kbd>
                                    <span> => Q. {{coin.menu.precio | number:2}}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12">
                            <h4>{{lstCoincidencias.length>0?'--':'Sin resultados...'}}</h4>
                        </div>
                        <div class="col-xs-12 text-right" style="margin-top: 13px">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-lg btn-default" ng-click="mostrarMenus( 'menu' )">
                                        <span class="glyphicon glyphicon-apple"></span>
                                        <b class="hidden-xs"><u>M</u>enú</b>
                                    </button>
                                    <button class="btn btn-lg btn-default" ng-click="mostrarMenus( 'combo' )">
                                        <span class="glyphicon glyphicon-gift"></span>
                                        <b class="hidden-xs"><u>C</u>ombo</b>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
            		<div class="row" style="margin-top: 6px" ng-show="menuActual.idMenu>0">
                		<div class="col-sm-6 col-xs-12 text-center">
                			<span class="quitar" ng-click="cancelarMenu()">
						        <span class="glyphicon glyphicon-remove"></span>
						    </span>
                			<img ng-src="{{menuActual.imagen}}" style="height:100px">
                			<h4>{{menuActual.menu}} <kbd>{{tipoMenu}}</kbd></h4>
                			<h3>Q. {{menuActual.precio | number:2}}</h3>
                		</div>
                		<div class="col-xs-12" ng-show="lstSinDisponibilidad.length">
							<p class="alert alert-danger" ng-repeat="item in lstSinDisponibilidad">
								<kbd>{{item.producto}}</kbd> Requerido: <b>{{item.cantidadRequerida}} {{item.medida}}</b> > Disponible: <b>{{item.disponibilidad}} {{item.medida}}</b>
							</p>
            			</div>
            			<div class="col-xs-12" style="margin-top:5px">
                            <textarea ng-model="$parent.observacion" id="observacionMenu" rows="3" class="form-control" placeholder="Observación del menú"></textarea>
            			</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();dialOrdenCliente.show()">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Regresar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

<!-- LISTADO DE MENUS -->
<script type="text/ng-template" id="dial.orden-menu.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_menu">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    Seleccione Menú - Ticket # {{ordenActual.noTicket}}
                </div>
                <div class="modal-body">
                	<div class="row" ng-show="tipoMenu=='menu'">
                        <div class="col-sm-4 col-md-6 col-lg-7">
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-5">
                            <input type="text" id="menu" ng-model="$parent.nombreMenu" class="form-control" placeholder="Buscar menú">
                        </div>
                        <div class="clearfix"></div>
                		<div class="col-xs-12" style="margin-bottom:4px; margin-top:5px;">
                			<button type="button" class="btn" ng-class="{'btn-default':idTipoMenu!=item.idTipoMenu, 'btn-info':idTipoMenu==item.idTipoMenu}" 
                				ng-repeat="item in lstTipoMenu track by $index" ng-click="$parent.$parent.idTipoMenu=item.idTipoMenu" style="margin-right:5px">
                				<span class="glyphicon glyphicon-ok" ng-show="idTipoMenu==item.idTipoMenu"></span>
	                			<span>{{item.tipoMenu}}</span>
                			</button>
                		</div>
                		<hr>
                    </div>
                	<div class="row" ng-show="tipoMenu=='menu'">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstMenu | filter: nombreMenu track by $index">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<span class="codigo">COD: <b>{{item.codigoMenu}}</b></span>
	                			<img ng-src="{{item.imagen}}">
	                			<span class="menu">{{item.menu}}</span>
                			</button>
                		</div>
                   </div>
                   <div class="row" ng-show="tipoMenu=='combo'">
                        <div class="col-sm-4 col-md-6 col-lg-7">
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-5">
                            <input type="text" id="combo" ng-model="$parent.nombreCombo" class="form-control" placeholder="Buscar combo">
                        </div>
                        <div class="clearfix"></div>
                        <br>
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstCombo | filter: nombreCombo track by $index">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<span class="codigo">COD: <b>{{item.codigoCombo}}</b></span>
	                			<img ng-src="{{item.imagen}}">
	                			<span class="menu">{{item.combo}}</span>
                			</button>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide();dialOrdenCliente.show()">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Regresar a Orden Cliente</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 

<!-- Cantidad - Tipo Servicio - Menú -->
<script type="text/ng-template" id="dial.menu-cantidad.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_menu_cantidad">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                    <b>Ingrese Cantidad Menú</b>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-6 text-center">
                			<img ng-src="{{menuActual.imagen}}" style="height:100px">
                			<h4>{{menuActual.menu}} <kbd>{{tipoMenu}}</kbd></h4>
                			<h3>Q. {{menuActual.precio | number:2}}</h3>
                		</div>
                		<div class="col-xs-6">
                			<div class="col-xs-12">
	                			<label>Cantidad</label>
	                			<input type="number" class="form-control input-lg input-focus" ng-model="menuActual.cantidad" id="cantidad_menu" min="1">
                			</div>
                            <div class="col-xs-12" style="margin-top:5px">
                                <button type="button" class="btn btn-default" ng-click="menuActual.cantidad=( menuActual.cantidad>1 ? menuActual.cantidad-1 : menuActual.cantidad)">
                                    <span class="glyphicon glyphicon-minus"></span>
                                </button>
                                <button type="button" class="btn btn-primary" ng-click="menuActual.cantidad=menuActual.cantidad+1">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>
                            <div class="col-xs-12" style="margin-top:5px">
                                <textarea ng-model="$parent.observacion" rows="3" class="form-control" placeholder="Observación del menú"></textarea>
                            </div>
                        </div>
            			<div class="col-xs-12">
            				<button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=2,'btn-info':idTipoServicio==2}" 
            					ng-click="$parent.idTipoServicio=2" style="margin-right:4px;margin-top:5px">
            					<u>R</u>estaurante
            					<span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==2"></span>
            				</button>
            				<button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=1,'btn-info':idTipoServicio==1}" 
            					ng-click="$parent.idTipoServicio=1" style="margin-right:4px;margin-top:5px">
            					Para <u>L</u>levar
            					<span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==1"></span>
            				</button>
            				<button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=3,'btn-info':idTipoServicio==3}" 
            					ng-click="$parent.idTipoServicio=3" style="margin-right:4px;margin-top:5px">
            					A <u>D</u>omicilio
            					<span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==3"></span>
            				</button>
            			</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="agregarAPedido()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b><u>A</u>gregar a Orden</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide();dialOrdenMenu.show()">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <b>Regresar</b>
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
                                            <td>{{item.idOrdenCliente}}</td>
                                            <td>{{item.numeroTicket}}</td>
                                            <td>{{item.usuarioResponsable}}</td>
                                            <td>{{item.estadoOrden}}</td>
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



<!-- CANCELAR ORDEN -->
<script type="text/ng-template" id="dial.orden.cancelar.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-remove"></span>
                    CANCELAR ORDEN
                </div>
                <div class="modal-body">
                    <h3>¿Está seguro de cancelar la Orden con Ticket # <kbd>{{infoOrden.numeroTicket}}</kbd>?</h3>
                    <div class="row">
                        <div class="col-sm-offset-2 col-sm-8">
                            <label>Ingrese motivo de Cancelación</label>
                            <textarea ng-model="$parent.comentario" id="txtCancelar" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" ng-click="cancelarOrdenPrincipal( infoOrden.idOrdenCliente )">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>CANCELAR ORDEN COMPLETA</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir sin Cancelar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- CANCELAR ORDEN PARCIAL -->
<script type="text/ng-template" id="dial.orden.cancelar-parcial.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-remove"></span>
                    CANCELAR ORDEN
                </div>
                <div class="modal-body">
                    <h3>¿Está seguro de cancelar Detalle de Orden con Ticket # <kbd>{{infoOrden.numeroTicket}}</kbd>?</h3>
                    <div class="row">
                        <div class="col-sm-offset-2 col-sm-8">
                            <label>Ingrese motivo de Cancelación</label>
                            <textarea ng-model="$parent.comentario" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" ng-click="cancelarOrdenParcial( infoOrden.idOrdenCliente, itemDetalle )">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>CANCELAR DETALLE ORDEN</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir sin Cancelar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- CANCELAR OTRO MENU -->
<script type="text/ng-template" id="dial.orden.cancelar-otro.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-remove"></span>
                    CANCELAR MENU PERSONALIZADO
                </div>
                <div class="modal-body">
                    <h3>¿Está seguro de cancelar el menú, Ticket # <kbd>{{infoOrden.numeroTicket}}</kbd>?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" ng-click="cancelarOrdenParcial( infoOrden.idOrdenCliente, itemDetalle )">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>CANCELAR DETALLE ORDEN</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir sin Cancelar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 


<!-- EDITAR DETALLE ORDEN -->
<script type="text/ng-template" id="dial.orden.editar.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-pencil"></span>
                    Modificar Detalle Orden
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 text-right">
                            <img ng-src="{{itemDetalle.imagen}}" height="80px">
                        </div>
                        <div class="col-sm-8">
                            <h2>{{itemDetalle.descripcion}}</h2>
                            <h3>Cantidad: <kbd><b>{{itemDetalle.cantidad}}</b></kbd></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn" ng-class="{'btn-primary':accionDetalleOrden=='tipoServicio', 'btn-default':accionDetalleOrden!='tipoServicio'}" ng-click="$parent.accionDetalleOrden='tipoServicio'">
                                <span class="glyphicon glyphicon-retweet"></span>
                                <b>Cambiar Tipo Servicio</b>
                            </button>
                            <button type="button" class="btn" ng-class="{'btn-primary':accionDetalleOrden=='reasignar', 'btn-default':accionDetalleOrden!='reasignar'}" ng-click="$parent.accionDetalleOrden='reasignar'">
                                <span class="glyphicon glyphicon-share-alt"></span>
                                <b>Re-asignar a Otra Orden</b>
                            </button>
                        </div>
                        <hr class="col-sm-10 col-sm-offset-1">
                    </div>

                    <!-- SI ES CAMBIO DE TIPO DE SERVICIO -->
                    <div class="row"  ng-show="accionDetalleOrden=='tipoServicio'">
                        <div class="col-sm-10 col-sm-offset-1">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo de Servicio</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="tipo in itemDetalle.lstTipoServicio">
                                        <td>{{tipo.tipoServicio}}</td>
                                        <td>
                                            <input type="number" min="1" class="form-control" ng-model="tipo.cantidad" id="input_ts_{{$index}}" focus-enter>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SI ES RE-ASIGNACION -->
                    <div class="row"  ng-show="accionDetalleOrden=='reasignar'">
                        <div class="col-sm-10 col-sm-offset-1" ng-hide="itemDetalle.destinoOrden.idOrdenCliente>0">
                            <div class="input-group">
                                <input type="text" class="form-control input-lg" ng-model="itemDetalle.busqueda" id="it_busqueda" numbers-only autocomplete="off" placeholder="# de Ticket" ng-keydown="$event.keyCode==13 && buscarOrdenTicket( itemDetalle.busqueda )">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-lg btn-info" ng-click="buscarOrdenTicket( itemDetalle.busqueda )">
                                        <span class="glyphicon glyphicon-search"></span>
                                        <b>Buscar</b>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-12" ng-show="itemDetalle.destinoOrden.idOrdenCliente>0">
                            <div class="col-sm-6">
                                <span class="etiqueta"># de Ticket:</span>
                                <span class="valor ng-binding">{{itemDetalle.destinoOrden.numeroTicket}}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="etiqueta">Fecha Orden:</span>
                                <span class="valor ng-binding">{{formatoFecha( itemDetalle.destinoOrden.fechaRegistro, 'ddd DD - HH:mm' )}}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="etiqueta">Usuario Orden:</span>
                                <span class="valor ng-binding">{{itemDetalle.destinoOrden.usuarioPropietario}}</span>
                            </div>
                        </div>
                        <div class="col-sm-10 col-sm-offset-1" ng-show="itemDetalle.destinoOrden.idOrdenCliente>0">
                            <div class="col-sm-5 text-right">
                                <h4 class="text-primary">Cantidad a Reasignar</h4>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-lg" ng-model="itemDetalle.cantidadReasignar" numbers-only autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" ng-click="editarOrdenParcial()">
                        <span class="glyphicon glyphicon-ok"></span>
                        <b>Confirmar</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir sin Modificar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>


<!-- IMPRIMIR ULTIMAS ORDENES -->
<script type="text/ng-template" id="dial.ultimas.ordenes.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-print"></span>
                    Imprimir Últimas Ordenes
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <blockquote>
                                <p>Imprime las Últimas Ordenes Pendientes, opcional se puede imprimir las últimas ordenes facturadas.</p>
                            </blockquote>
                        </div>
                        <div class="col-sm-12 text-center" ng-init="siFacturados=false;facturados=''">
                            <h4>Incluir Facturados</h4>
                            <button type="button" class="btn" ng-class="{'btn-primary':siFacturados,'btn-default':!siFacturados}" ng-click="siFacturados=true">
                                <b>SI</b>
                            </button>
                            <button type="button" class="btn" ng-class="{'btn-primary':!siFacturados,'btn-default':siFacturados}" ng-click="siFacturados=false; facturados=''">
                                <b>NO</b>
                            </button>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3" ng-show="siFacturados">
                            <label>Ingrese los Últimos Facturados</label>
                            <input type="text" class="form-control input-lg" ng-model="facturados">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-info" target="_blank" ng-href="orden.print.php?tipo=pendientes&facturados={{ facturados }}">
                        <span class="glyphicon glyphicon-print"></span>
                        <b>Imprimir Últimas Ordenes</b>
                    </a>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>


<!-- AYUDA -->
<script type="text/ng-template" id="ayuda.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" ng-click="$hide()">&times;</button>
                    <span class="glyphicon glyphicon-question-sign"></span>
                    Atajos de Teclado
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <legend class="legend primary">Pantalla de Inicio</legend>
                            <ul class="list-group">
                                <li class="list-group-item"><kbd>Ctrl + O</kbd> Dialogo para <b>NUEVA</b> Orden</li>
                                <li class="list-group-item"><kbd>Ctrl + P</kbd> Ordenes con estado <b>PENDIENTE</b></li>
                                <li class="list-group-item"><kbd>Ctrl + L</kbd> Ordenes con estado <b>LISTO</b></li>
                                <li class="list-group-item"><kbd>Ctrl + F</kbd> Ordenes con estado <b>FINALIZADO/SERVIDO</b></li>
                                <li class="list-group-item"><kbd>Ctrl + C</kbd> Ordenes con estado <b>CANCELADO</b></li>
                                <li class="list-group-item"><kbd>Ctrl + S</kbd> Ordenes con estado <b>FACTURADO</b></li>
                                <li class="list-group-item"><kbd>↑</kbd> selecciona la orden <b>Siguiente</b></li>
                                <li class="list-group-item"><kbd>↓</kbd> selecciona la orden <b>Anterior</b></li>
                                <li class="list-group-item"><kbd>Ctrl + A</kbd> <b>Agregar Menú</b> a la <b>Orden Actual</b></li>
                                <li class="list-group-item"><kbd>F4</kbd> <b>Facturar</b> orden actual</li>
                                <li class="list-group-item"><kbd>Ctrl + X</kbd> Muestra dialog para <b>CANCELAR</b> Orden Actual</li>
                            </ul>
                        </div>
                        <div class="col-xs-6">
                            <legend class="legend primary">Dialogo: CONFIRMAR ORDEN</legend>
                            <ul class="list-group">
                                <li class="list-group-item"><kbd>Ctrl + A</kbd> Agregar menú de Orden</li>
                                <li class="list-group-item"><kbd>→</kbd> pestaña <b>Siguiente</b> Tipo Servicio</li>
                                <li class="list-group-item"><kbd>←</kbd> pestaña <b>Anterior</b> Tipo Servicio</li>
                                <li class="list-group-item"><kbd>F6</kbd> Confirma los menús/combos seleccionados</li>
                                <li class="list-group-item"><kbd>ESC</kbd> abandonar la ventana actual</li>
                            </ul>
                            <legend class="legend primary">Dialogo: INGRESE CANTIDAD MENÚ</legend>
                            <ul class="list-group">
                                <li class="list-group-item"><kbd>Ctrl + L</kbd> Menú para Llevar</li>
                                <li class="list-group-item"><kbd>Ctrl + R</kbd> Menú para Restaurante</li>
                                <li class="list-group-item"><kbd>Ctrl + D</kbd> Menú para Servicio a Domicilio</li>
                                <li class="list-group-item"><kbd>Ctrl + M</kbd> Muetra lista de <b>Menús</b></li>
                                <li class="list-group-item"><kbd>Ctrl + C</kbd> Muetra lista de <b>Combos</b></li>
                                <li class="list-group-item"><kbd>-</kbd> Restar uno a la <b>Cantidad</b></li>
                                <li class="list-group-item"><kbd>+</kbd> Sumar uno a la <b>Cantidad</b></li>
                                <li class="list-group-item"><kbd>ENTER</kbd> Busca menú/combo por <b>Código Rápido</b></li>
                                <li class="list-group-item"><kbd>ESC</kbd> abandonar la ventana actual</li>
                            </ul>
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