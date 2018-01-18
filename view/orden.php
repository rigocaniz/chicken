<?php
    include '../class/sesion.class.php';
    
    if( !$sesion->getAccesoModulo( 1 ) ):
        include 'errores/403.php';
        exit();
    endif;
?>

<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
        <div class="col-sm-4 col-xs-5" style="margin-bottom:9px">
            <button type="button" class="btn btn-success" ng-click="nuevaOrden()">
                <span class="glyphicon glyphicon-plus"></span>
                <u>N</u>ueva Orden
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
                <div class="col-sm-6 col-xs-12 text-right">
                	<a href="#/factura/{{infoOrden.idOrdenCliente}}" class="btn btn-sm btn-primary">
                        <span class="glyphicon glyphicon-shopping-cart"></span>
                        <b>Facturar</b> (F10)
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" ng-click="dialOrdenCancelar.show();comentario=''" ng-show="infoOrden.idEstadoOrden==1">
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
                                        <button type="button" class="btn btn-sm btn-default" ng-click="editarDetalle( item )" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </td>
                                    <td>
                                        <img ng-src="{{item.imagen}}" style="height:40px">
                                    </td>
                                    <td>{{item.cantidad}}</td>
                                    <td>
                                        <button type="button" class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
                                            <span ng-show="item.idTipoServicio==2" title="Restaurante" data-toggle="tooltip" data-placement="top" tooltip>R</span>
                                            <span ng-show="item.idTipoServicio==3" title="A Domicilio" data-toggle="tooltip" data-placement="top" tooltip>D</span>
                                            <span ng-show="item.idTipoServicio==1" title="Para Llevar" data-toggle="tooltip" data-placement="top" tooltip>L</span>
                                        </button>
                                        <span class="glyphicon glyphicon-gift" ng-show="item.esCombo"></span>
                                        <span>{{item.descripcion}}</span>
                                        <span class="estado-menu {{est.css}}" ng-repeat="est in item.estados" title="{{est.title}}" data-toggle="tooltip" data-placement="top" tooltip>{{est.abr}} <span class="badge">{{est.total}}</span></span>
                                    </td>
                                    <td>{{item.subTotal | number:2}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" ng-click="cancelarDetalle( item.lstDetalle )" title="Cancelar" data-toggle="tooltip" data-placement="top" tooltip>
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
								<input type="number" class="form-control input-lg input-focus" ng-model="$parent.noTicket" id="noTicket">
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
                	<button type="button" class="btn btn-primary" ng-click="agregarOrden( 'domicilio' )" ng-disabled="noTicket>0">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>A <u>D</u>omicilio</b>
                    </button>
                	<button type="button" class="btn btn-success" ng-click="agregarOrden()">
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
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <h4>
                    	<span ng-show="ordenActual.idOrdenCliente>0">Orden # <span class="badge-orden">{{ordenActual.idOrdenCliente}}</span></span> - 
                    	<span ng-show="ordenActual.noTicket>0">Ticket: <span class="badge-ticket">{{ordenActual.noTicket}}</span></span>
                        <span class="label label-default" style="float:right" ng-show="accionOrden=='nuevo'">Nuevo</span>
                        <span class="label label-info" style="float:right" ng-show="accionOrden=='modificar'">Agregar</span>
                    </h4>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-12" style="margin-bottom:5px">
                			<button type="button" class="btn btn-primary" ng-click="openCantidad()">
                				<span class="glyphicon glyphicon-plus"></span>
                				<b><u>A</u>gregar Menú</b>
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
                								<span class="label label-info" ng-show="item.observacion.length>2">{{item.observacion}}</span>
                							</span>
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
        			<button type="button" class="btn btn-success" ng-click="guardarOrden()">
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
                                    ng-click="$parent.idTipoServicio=3" style="margin-right:4px;margin-top:5px">
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
                        		<input type="text" class="form-control input-lg" ng-model="$parent.codigoRapido" id="codigoRapido"
                                    placeholder="Código Rápido" numbers-only autocomplete="off">
								<span class="input-group-btn">
		                            <button type="button" class="btn btn-lg btn-primary" ng-click="consultaMenuPorCodigo()">
		                                <span class="glyphicon glyphicon-search"></span>
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
                	<button type="button" class="btn btn-success" ng-click="agregarOrdenLista()">
                        <span class="glyphicon glyphicon-ok"></span>
                        <b>Agregar (F6)</b>
                    </button>
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
                		<div class="col-xs-12" style="margin-bottom:4px">
                			<button type="button" class="btn" ng-class="{'btn-default':idTipoMenu!=item.idTipoMenu, 'btn-info':idTipoMenu==item.idTipoMenu}" 
                				ng-repeat="item in lstTipoMenu track by $index" ng-click="$parent.$parent.idTipoMenu=item.idTipoMenu" style="margin-right:5px">
                				<span class="glyphicon glyphicon-ok" ng-show="idTipoMenu==item.idTipoMenu"></span>
	                			<span>{{item.tipoMenu}}</span>
                			</button>
                		</div>
                		<hr>
                   </div>
                	<div class="row" ng-show="tipoMenu=='menu'">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstMenu track by $index">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<span class="codigo">COD: <b>{{item.codigoMenu}}</b></span>
	                			<img ng-src="{{item.imagen}}">
	                			<span class="menu">{{item.menu}}</span>
                			</button>
                		</div>
                   </div>
                   <div class="row" ng-show="tipoMenu=='combo'">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstCombo track by $index">
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
                            <textarea ng-model="$parent.comentario" rows="3" class="form-control"></textarea>
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
                                            <input type="number" class="form-control" ng-model="tipo.cantidad" id="input_ts_{{$index}}" focus-enter>
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
