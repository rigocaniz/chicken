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
									<span class="glyphicon glyphicon-list-alt"></span> FACTURAR
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
											<button type="button" class="btn btn-warning" ng-click="buscarCliente( facturacion.datosCliente.nit, 'principal' );" readonly>
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
											<button type="button" class="btn btn-info" ng-click="editarCliente( facturacion.datosCliente, 'mostrar' );" ng-show="facturacion.datosCliente.idCliente && facturacion.datosCliente.idCliente != 1" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
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
										<label class="col-sm-2 col-lg-1 control-label">DIRECCION</label>
										<div class="col-sm-5 col-lg-6">
											<input type="text" class="form-control" ng-model="facturacion.datosCliente.direccion" ng-disabled="facturacion.datosCliente.idCliente!=1">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-1 control-label">TICKET</label>
										<div class="col-sm-4 col-md-3 col-lg-2">
                                            <div ng-show="!facturacion.numeroTicket">
											     <input type="text" id="ticket" class="form-control" ng-keypress="$event.keyCode == 13 && buscarOrdenTicket()" ng-model="buscarTicket" ng-disabled="!facturacion.datosCliente.idCliente">
                                            </div>
                                            <div ng-show="facturacion.numeroTicket">
                                                <input type="text" class="form-control" ng-keypress="$event.keyCode == 13 && buscarOrdenTicket()" ng-model="facturacion.numeroTicket" disabled>
                                            </div>
										</div>
										<button type="button" class="btn btn-info" ng-click="buscarOrdenTicket()" ng-show="!facturacion.numeroTicket">
                                            <span class="glyphicon glyphicon-search"></span> Buscar
										</button>
                                        <button type="button" class="btn btn-warning" ng-click="facturacion.numeroTicket=null;" ng-show="facturacion.numeroTicket">
                                            <span class="glyphicon glyphicon-refresh"></span> Cambiar
                                        </button>
									</div>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-5 col-md-3 col-lg-4">
                                            <fieldset class="fieldset">
                                                <legend class="legend info">FORMAS DE PAGO</legend>
                                                <div class="form-group input-sm">
                                                    <label class="col-md-12 control-label">TOTAL A COBRAR</label>
                                                    <div class="col-md-12">
                                                        <div class="total">
                                                            Q. {{ retornarTotal() | number: 2 }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group input-sm" ng-repeat="formaPago in facturacion.lstFormasPago">
                                                    <label class="col-md-12 control-label">{{ formaPago.formaPago | uppercase }}</label>
                                                    <div class="col-md-12 monto">
                                                        <input type="number" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" class="form-control" ng-model="formaPago.monto">
                                                    </div>
                                                </div>
                                                <div class="form-group input-sm">
                                                    <label class="col-md-12 control-label">VUELTO</label>
                                                    <div class="col-md-12">
                                                        <div class="total">
                                                        {{ totalVuelto() | number: 2 }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <br><br><br>
                                                <hr>
                                                <div class="form-group">
                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-info" ng-click="consultaFacturaCliente()">
                                                            <span class="glyphicon glyphicon-saved"></span> FACTURAR
                                                        </button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-xs-8 col-sm-7 col-md-9 col-lg-8">
                                            <fieldset class="fieldset" ng-show="facturacion.lstOrden.length">
                                                <legend class="legend info">DETALLE ORDEN</legend>
                                                <div class="table-responsive">
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-xs btn-default" ng-click="facturacion.agrupado=!facturacion.agrupado; consultarDetalleOrden();">
                                                            <span class="glyphicon" ng-class="{'glyphicon-th-list': facturacion.agrupado, 'glyphicon-list-alt': !facturacion.agrupado}"></span> <strong>{{ facturacion.agrupado ? 'VER A DETALLE' : 'AGRUPAR ORDEN' }}</strong>
                                                        </button>
                                                    </div>
                                                    <br>
                                                    <table class="table table-hover table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <small>cobrar</small>
                                                                </th>
                                                                <th class="text-center col-sm-4 col-md-6 col-lg-5">
                                                                    Orden
                                                                </th>
                                                                <th class="text-center col-sm-2 col-md-2 col-lg-2">
                                                                    Cant.
                                                                </th>
                                                                <th class="text-right col-sm-2 col-md-1 col-lg-1">
                                                                    Precio
                                                                </th>
                                                                <th class="text-right col-sm-2 col-md-1 col-lg-2">
                                                                    Descuento
                                                                </th>
                                                                <th class="text-right col-sm-3 col-md-3 col-lg-2">
                                                                    Subtotal
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="item in facturacion.lstOrden" ng-class="{'warning': !item.cobrarTodo, 'border-warning': item.descuento > 0 , 'border-danger': item.descuento == item.precioMenu}" ng-dblclick="item.cobrarTodo=!item.cobrarTodo">
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-default" ng-click="item.cobrarTodo=!item.cobrarTodo">
                                                                        <span class="glyphicon " ng-class="{'glyphicon-check': item.cobrarTodo, 'glyphicon-unchecked': !item.cobrarTodo}"></span>
                                                                    </button>
                                                                </td>
                                                                <td>
                                                                    <div class="label-border btn-sm" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}" title="{{ item.tipoServicio }}" data-toggle="tooltip" data-placement="top" tooltip>
                                                                        <span ng-show="item.idTipoServicio==2">R</span>
                                                                        <span ng-show="item.idTipoServicio==3">D</span>
                                                                        <span ng-show="item.idTipoServicio==1">L</span>
                                                                    </div>
                                                                    <span class="glyphicon glyphicon-gift" ng-show="item.esCombo"></span>
                                                                    <span>{{ item.descripcion }}</span>
                                                                    <br>
                                                                    <textarea class="form-control" rows="2" placeholder="Ingrese  justificación del descuento" ng-model="item.comentario" ng-show="item.descuento > 0 && item.cobrarTodo"></textarea>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div ng-show="!item.precioHabilitado">
                                                                        <input type="number" class="form-control" ng-model="item.cantidad" max="{{ item.maximo }}" min="1" ng-pattern="/^[0-9]+?$/" ng-disabled="!item.cobrarTodo">
                                                                    </div>
                                                                    <div ng-show="item.precioHabilitado">
                                                                        {{ item.cantidad }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ item.precioMenu | number: 2 }}
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" class="form-control" ng-model="item.descuento" max="{{ item.precioMenu }}" min="0" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" ng-disabled="!item.cobrarTodo">
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ item.cantidad * ( item.precioMenu - item.descuento ) | number:2 }}
                                                                </td>
                                                            </tr>
                                                            <tr class="success">
                                                                <td colspan="5" class="text-right">
                                                                    <strong>TOTAL</strong>
                                                                </td>
                                                                <td class="text-right" style="">
                                                                    <strong>Q. {{ retornarTotal() | number: 2 }}</strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                                <br><br>
                                <!--
                                {{ facturacion | json }}
								{{ facturacion | json }}
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