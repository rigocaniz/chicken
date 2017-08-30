<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
				<a href="#/" >
	            	<img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	            </a>
	        </div>

			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : menuFactura=='facturar'}" ng-click="resetValores(); menuFactura='facturar'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> FACTURAR
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : menuFactura=='facturar'}" ng-show="menuFactura=='facturar'">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">
									<span class="glyphicon glyphicon-list-alt"></span> Facturar
								</h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" autocomplete="off" novalidate>
									<div class="form-group">
										<label class="col-sm-2 control-label">BUSCAR CLIENTE</label>
										<div class="col-sm-4">
											<input type="text" id="searchPrincipal" class="form-control" ng-model="txtCliente"  placeholder="NIT / DPI / NOMBRE" ng-keypress="$event.keyCode == 13 && buscarCliente( txtCliente, 'principal' )">
										</div>
										<div class="col-sm-4">
											<button type="button" class="btn btn-info" ng-click="buscarCliente( facturacion.datosCliente.nit, 'principal' );">
												<span class="glyphicon glyphicon-search"></span>
											</button>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">NIT</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" ng-model="facturacion.datosCliente.nit" ng-disabled="facturacion.datosCliente.idCliente!=1">
										</div>
										<div class="col-sm-2 col-md-1">
											<button type="button" class="btn btn-warning" ng-click="editarCliente( facturacion.datosCliente, 'mostrar' );" ng-show="facturacion.datosCliente.idCliente && facturacion.datosCliente.idCliente != 1" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</div>
										<label class="col-sm-1 control-label" ng-show="facturacion.datosCliente.cui.length >=10">CUI</label>
										<div class="col-sm-3" ng-show="facturacion.datosCliente.cui.length >=10">
											<kbd>{{ facturacion.datosCliente.cui }}</kbd>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">NOMBRE</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" ng-model="facturacion.datosCliente.nombre" ng-disabled="facturacion.datosCliente.idCliente!=1">
										</div>
										<label class="col-sm-2 control-label">DIRECCION</label>
										<div class="col-sm-5">
											<input type="text" class="form-control" ng-model="facturacion.datosCliente.direccion" ng-disabled="facturacion.datosCliente.idCliente!=1">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">TICKET</label>
										<div class="col-sm-4">
											<input type="text" id="ticket" class="form-control" ng-model="facturacion.ticket">
										</div>
										<button type="button" class="btn btn-primary" ng-click="buscarOrdenTicket()">
											Buscar
										</button>
									</div>
								</form>
								{{ facturacion | json }}
								<!--
								<table class="table table-hover">
									<thead>
										<tr>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
										</tr>
									</tbody>
								</table>
								<button type="button" class="btn btn-info">Descripción Personalizada</button>
								<table class="table table-hover">
									<thead>
										<tr>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
										</tr>
									</tbody>
								</table>
								<div class="text-center">
									<button type="button" class="btn btn-success">Facturar</button>
									<button type="button" class="btn btn-info">Cancelar</button>
								</div>
								-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


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
                                            <td>
                                            	<button type="button" class="btn btn-info" ng-click="detalleTicket( item.idOrdenCliente )">
                                            		<span class="glyphicon glyphicon-chevron-righ"></span> Seleccionar
                                            	</button>
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


<!-- DIALOGO BUSCAR / LISTAR CLIENTES -->
<script type="text/ng-template" id="dial.accionCliente.html">
    <div class="modal" tabindex="-1" role="dialog">
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
								<div class="form-group">
									<label class="col-sm-2 control-label">NIT</label>
									<div class="col-sm-3">
										<input type="text" ng-model="$parent.cliente.nit" class="form-control" id="nit" ng-pattern="/^[0-9-\s]+?$/" maxlength="15">
									</div>
									<div class="col-sm-6">
										<div class="text-right">
											<label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}" style="font-size: 14px;">
												{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} CLIENTE
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Nombre</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Cui (DPI)</label>
									<div class="col-sm-4 col-md-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="13" ng-model="$parent.cliente.cui">
									</div>
									<label class="col-sm-2 control-label">Correo</label>
									<div class="col-sm-4 col-md-3">
										<input type="email" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Telefono</label>
									<div class="col-sm-4 col-md-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="8" ng-model="$parent.cliente.telefono">
									</div>
									<label class="col-sm-2 control-label">Tipo Cliente</label>
									<div class="col-sm-4">
										<div class="btn-group" role="group" aria-label="">
										  	<button type="button" class="btn btn-default" ng-repeat="tc in lstTipoCliente" ng-click="$parent.cliente.idTipoCliente = tc.idTipoCliente" ng-class="{'btn-warning': tc.idTipoCliente == $parent.cliente.idTipoCliente}">
										  		<span class="glyphicon" ng-class="{'glyphicon-check': tc.idTipoCliente == $parent.cliente.idTipoCliente, 'glyphicon-unchecked': tc.idTipoCliente != $parent.cliente.idTipoCliente}"></span>
										  		{{ tc.tipoCliente }}
										  	</button>
										</div>
									</div>	
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Dirección</label>
									<div class="col-sm-9 col-md-8">
										<input type="text" class="form-control" maxlength="95" ng-model="$parent.cliente.direccion" ng-keypress="$event.keyCode==13 && consultaCliente()">
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button type="button" class="btn btn-success" ng-click="consultaCliente()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} cliente
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