<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-12" style="margin-bottom:4px">
			<button type="button" class="btn btn-success" ng-click="nuevaOrden()">
				<span class="glyphicon glyphicon-plus"></span>
				<u>N</u>ueva Orden
			</button>
			<button type="button" class="btn btn-info" ng-click="modalBuscar()">
				<span class="glyphicon glyphicon-search"></span>
				Buscar Orden
			</button>
		</div>
		<div class="col-sm-12">
			<div class="btn-orden">
				<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="idEstadoOrden=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs">Pendientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="idEstadoOrden=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs">En Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="idEstadoOrden=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs">Finalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="idEstadoOrden=10">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs">Cancelados</span>
				</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-info" style="margin-top: 4px">
				<div class="panel-body">
					<table class="table table-condensed table-hover">
						<thead>
							<tr>
								<th>Ticket</th>
								<th>Lapso</th>
								<th>Responsable</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="item in lstOrdenCliente">
								<td>{{item.numeroTicket}}</td>
								<td>
									<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
								</td>
								<td><kbd>{{item.usuarioResponsable}}</kbd></td>
								<td>
									<button type="button" class="btn btn-sm btn-default" ng-click="modalInfo( item )">
										<span class="glyphicon glyphicon-list"></span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
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
									<button class="btn btn-lg btn-danger" type="button" ng-click="auxKeyTicket( 'supr' )">
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</span>
							</div>
                		</div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 7 )">
                                <u>7</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 8 )">
                                <u>8</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 9 )">
                                <u>9</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 4 )">
                                <u>4</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 5 )">
                                <u>5</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 6 )">
                                <u>6</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 1 )">
                                <u>1</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 2 )">
                                <u>2</u>
                            </button>
                            <button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="auxKeyTicket( 'number', 3 )">
                                <u>3</u>
                            </button>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-top:4px">
                            <button class="btn btn-lg btn-default" style="padding:10px 36px" ng-click="auxKeyTicket( 'number', 0 )">
                                <u>0</u>
                            </button>
                            <button class="btn btn-lg btn-danger" style="margin-bottom:4px" ng-click="auxKeyTicket( 'back' )">
                                <span class="glyphicon glyphicon-arrow-left"></span>
                            </button>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="agregarOrden()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>Agregar Ticket</b>
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

<!-- ADMINISTRAR ORDEN -->
<script type="text/ng-template" id="dial.orden.cliente.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_cliente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <h4>
                    	<span ng-show="ordenActual.idOrdenCliente>0">Orden # <span class="badge-orden">{{ordenActual.idOrdenCliente}}</span></span> - 
                    	<span ng-show="ordenActual.noTicket>0">Ticket: <span class="badge-ticket">{{ordenActual.noTicket}}</span></span>
                    </h4>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-12">
                			<button class="btn btn-primary" ng-click="mostrarMenus( 'menu' )">
                				<span class="glyphicon glyphicon-plus"></span>
                				<b><u>M</u>enú</b>
                			</button>
                			<button class="btn btn-primary" ng-click="mostrarMenus( 'combo' )">
                				<span class="glyphicon glyphicon-plus"></span>
                				<b><u>C</u>ombo</b>
                			</button>
                		</div>
                   </div>
                   <div class="row">
                		<div class="col-xs-12">
                			<table class="table table-condensed table-hover">
                				<thead>
                					<tr>
                						<th>Cantidad</th>
                						<th>Orden</th>
                						<th>Subtotal</th>
                						<th width="35px"></th>
                					</tr>
                				</thead>
                				<tbody>
                					<tr ng-repeat="item in ordenActual.lstAgregar">
                						<td>
                							<button type="button" class="btn btn-xs btn-default" ng-click="ordenCantidad( $index, 0, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-minus"></span>
                							</button>
                							<b style="font-size:17px">{{item.cantidad}}</b>
                							<button type="button" class="btn btn-xs btn-info" ng-click="ordenCantidad( $index, 1, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-plus"></span>
                							</button>
                						</td>
                						<td>
                							<span class="glyphicon glyphicon-gift" ng-show="item.tipoMenu=='combo'"></span>
                							<span>{{item.menu}} » {{item.tipoServicio}}</span>
                						</td>
                						<td>{{(item.precio*item.cantidad) | number:2}}</td>
                						<td>
                							<button type="button" class="btn btn-xs btn-danger" ng-click="quitarElemento( $index, item.cantidad, item.precio )">
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
        				<b>Confirmar Orden</b>
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
                				ng-repeat="item in lstTipoMenu" ng-click="$parent.$parent.idTipoMenu=item.idTipoMenu" style="margin-right:5px">
                				<span class="glyphicon glyphicon-ok" ng-show="idTipoMenu==item.idTipoMenu"></span>
	                			<span>{{item.tipoMenu}}</span>
                			</button>
                		</div>
                		<hr>
                   </div>
                	<div class="row" ng-show="tipoMenu=='menu'">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstMenu">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<span class="codigo">{{item.idMenu}}</span>
	                			<img ng-src="{{item.imagen}}">
	                			<span class="menu">{{item.menu}}</span>
                			</button>
                		</div>
                   </div>
                   <div class="row" ng-show="tipoMenu=='combo'">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstCombo">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<span class="codigo">{{item.idCombo}}</span>
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
                			<h4>{{menuActual.menu}}</h4>
                			<h3>Q. {{menuActual.precio | number:2}}</h3>
                		</div>
                		<div class="col-xs-6">
                			<div class="col-xs-12">
	                			<label>Cantidad</label>
	                			<input type="number" class="form-control input-lg input-focus" ng-model="menuActual.cantidad" min="1">
                			</div>
                			<div class="col-xs-12" style="margin-top:5px">
                				<button type="button" class="btn btn-default" ng-click="menuActual.cantidad=( menuActual.cantidad>1 ? menuActual.cantidad-1 : menuActual.cantidad)">
                					<span class="glyphicon glyphicon-minus"></span>
                				</button>
                				<button type="button" class="btn btn-primary" ng-click="menuActual.cantidad=menuActual.cantidad+1">
                					<span class="glyphicon glyphicon-plus"></span>
                				</button>
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
                		<div class="col-xs-7">
                			<input type="text" class="form-control" id="inputSearch">
                		</div>
                		<div class="col-xs-5">
                			<button type="button" class="btn btn-primary">
                				<span class="glyphicon glyphicon-search"></span>
                				<span class="hidden-xs">Buscar</span>
                			</button>
                		</div>
                	</div>
                	<div class="row">
                		
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




<!-- *********** INFORMACION DE ORDEN ********* -->
<script type="text/ng-template" id="dial.orden-informacion.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_informacion">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                	<h4 style="margin:0">
						<b>Orden # </b> {{infoOrden.idOrdenCliente}} » 
		                <span class="badge-ticket">Ticket # {{infoOrden.numeroTicket}}</span>
                    </h4>
                </div>
                <div class="modal-body">
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
	                					<tr ng-repeat="item in infoOrden.lstOrden">
	                						<td><img ng-src="{{item.imagen}}" style="height:40px"></td>
	                						<td>{{item.cantidad}}</td>
	                						<td>
	                							<span ng-show="item.idTipoServicio==2" class="label-border label-success">R</span>
	                							<span ng-show="item.idTipoServicio==3" class="label-border label-default">D</span>
	                							<span ng-show="item.idTipoServicio==1" class="label-border label-primary">L</span>
	                							<span class="glyphicon glyphicon-gift" ng-show="item.esCombo"></span>
	                							<span>{{item.descripcion}}</span>
	                						</td>
	                						<td>{{item.subTotal | number:2}}</td>
	                						<td>
	                							
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
