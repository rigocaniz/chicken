<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-12" style="margin-bottom:4px">
			<button type="button" class="btn btn-success" ng-click="nuevaOrden()">
				<span class="glyphicon glyphicon-plus"></span>
				<u>N</u>ueva Orden
			</button>
			<button type="button" class="btn btn-info">
				<span class="glyphicon glyphicon-search"></span>
				Buscar Orden
			</button>
		</div>
		<div class="col-sm-12">
			<div class="btn-orden" ng-init="tab=1">
				<button class="bt-info" ng-class="{'active':tab==1}" ng-click="tab=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs">Pendientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':tab==2}" ng-click="tab=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs">En Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':tab==3}" ng-click="tab=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs">Finalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':tab==4}" ng-click="tab=4">
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
								<th># Orden</th>
								<th>Menú ({{tab}})</th>
								<th>Lapso</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>321</td>
								<td>Pollo Frito</td>
								<td>Hace 5 minutos</td>
							</tr>
							<tr>
								<td>123</td>
								<td>Papas fritas</td>
								<td>Hace 4 minutos</td>
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
								<input type="text" class="form-control input-lg input-focus" ng-model="$parent.noTicket" id="noTicket" ng-keypress="$event.keyCode==13 && agregarOrden()">
								<span class="input-group-btn">
									<button class="btn btn-lg btn-danger" type="button" ng-click="$parent.noTicket=''">
										<span class="glyphicon glyphicon-remove"></span>
									</button>
								</span>
							</div>
                		</div>
                		<div class="col-xs-12 text-center" style="margin-top:4px">
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'0'">0</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'1'">1</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'2'">2</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'3'">3</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'4'">4</button>
                		</div>
                		<div class="col-xs-12 text-center" style="margin-top:4px">
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'5'">5</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'6'">6</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'7'">7</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'8'">8</button>
                			<button class="btn btn-lg btn-default" style="margin-bottom:4px" ng-click="$parent.noTicket=$parent.noTicket+'9'">9</button>
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
                    	Orden - Ticket # {{ordenActual.noTicket}}
                    </h4>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-7">
                			<button class="btn btn-primary" ng-click="mostrarMenus()">
                				<span class="glyphicon glyphicon-plus"></span>
                				<b><u>M</u>enú</b>
                			</button>
                		</div>
                   </div>
                   <div class="row">
                		<div class="col-xs-12">
                			<table class="table table-condensed table-hover">
                				<thead>
                					<tr>
                						<th>Subtotal</th>
                						<th>Orden</th>
                						<th>Cantidad</th>
                						<th width="35px"></th>
                					</tr>
                				</thead>
                				<tbody>
                					<tr ng-repeat="item in ordenActual.lstAgregar">
                						<td>{{(item.precio*item.cantidad) | number:2}}</td>
                						<td>{{item.menu}} » {{item.tipoServicio}}</td>
                						<td>
                							<button type="button" class="btn btn-xs btn-default" ng-click="ordenCantidad( $index, 0, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-minus"></span>
                							</button>
                							<b>{{item.cantidad}}</b>
                							<button type="button" class="btn btn-xs btn-primary" ng-click="ordenCantidad( $index, 1, item.cantidad, item.precio )">
                								<span class="glyphicon glyphicon-plus"></span>
                							</button>
                						</td>
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
                			<div class="col-xs-5">
	                			<h4>
	                				<b>TOTAL: </b> <span class="badge">Q. {{$parent.ordenActual.totalAgregar | number:2}}</span>
	                			</h4>
                			</div>
                			<div class="col-xs-7 text-right">
	                			<button type="button" class="btn btn-success">
	                				<span class="glyphicon glyphicon-ok"></span>
	                				<b>Confirmar Orden</b>
	                			</button>
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

<!-- LISTADO DE MENUS -->
<script type="text/ng-template" id="dial.orden-menu.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog" id="dial_orden_menu">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                    Seleccione Menú - Ticket # {{ordenActual.noTicket}}
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-12" style="margin-bottom:4px">
                			<button type="button" class="btn" ng-class="{'btn-default':idTipoMenu!=item.idTipoMenu, 'btn-info':idTipoMenu==item.idTipoMenu}" 
                				ng-repeat="item in lstTipoMenu" ng-click="$parent.$parent.idTipoMenu=item.idTipoMenu" style="margin-right:5px">
                				<span class="glyphicon glyphicon-ok" ng-show="idTipoMenu==item.idTipoMenu"></span>
	                			<span>{{item.tipoMenu}}</span>
                			</button>
                		</div>
                   </div>
                	<div class="row">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstMenu">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<img ng-src="img-menu/{{item.imagen}}">
	                			<span>{{item.menu}}</span>
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
                			<img ng-src="img-menu/{{menuActual.imagen}}">
                			<h4>{{menuActual.menu}}</h4>
                			<b>Q. {{menuActual.precio | number:2}}</b>
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
            				<button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=1,'btn-info':idTipoServicio==1}" 
            					ng-click="$parent.idTipoServicio=1" style="margin-right:4px;margin-top:5px">
            					<u>R</u>estaurante
            					<span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==1"></span>
            				</button>
            				<button type="button" class="btn" ng-class="{'btn-default':idTipoServicio!=2,'btn-info':idTipoServicio==2}" 
            					ng-click="$parent.idTipoServicio=2" style="margin-right:4px;margin-top:5px">
            					Para <u>L</u>levar
            					<span class="glyphicon glyphicon-ok" ng-show="idTipoServicio==2"></span>
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
                        <b><u>R</u>egresar a Menú</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>

