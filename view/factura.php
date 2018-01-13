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
            <div class="pull-right">
                <button type="button" class="btn btn-sm btn-info" ng-click="cargarlstFacturasDia()">
                    <span class="glyphicon glyphicon-list"></span>
                    REIMPRESIÓN
                </button>
            </div>
            <p>
                <button type="button" class="btn btn-sm btn-primary" ng-click="dialCaja.show()">
                    <span class="glyphicon glyphicon-inbox"></span> ACCIONES DE CAJA
                </button>
            </p>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" ng-show="menuFactura=='facturar'">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-list-alt"></span> FACTURAR
                            </h3>
                        </div>
                        <div class="panel-body">
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
                                        <button type="button" class="btn btn-info" ng-click="buscarOrdenTicket()" ng-show="!facturacion.numeroTicket">
                                            <span class="glyphicon glyphicon-search"></span> Buscar
                                        </button>
                                        <button type="button" class="btn btn-warning" ng-click="facturacion.numeroTicket=null;" ng-show="facturacion.numeroTicket">
                                            <span class="glyphicon glyphicon-refresh"></span> Cambiar
                                        </button>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <h4>
                                            <b># Orden:</b>
                                            {{infoOrden.idOrdenCliente}}
                                        </h4>
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">BUSCAR CLIENTE</label>
									<div class="col-sm-4">
										<input type="text" id="searchPrincipal" class="form-control" ng-model="txtCliente"  placeholder="NIT / DPI / NOMBRE" ng-keypress="$event.keyCode == 13 && buscarCliente( txtCliente, 'principal' )">
									</div>
									<div class="col-sm-4">
										<button type="button" class="btn btn-warning" ng-click="buscarCliente( txtCliente, 'principal' );" readonly>
											<span class="glyphicon glyphicon-search"></span>
										</button>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1 control-label">NIT</label>
									<div class="col-sm-4">
										<input type="text" class="form-control" ng-model="facturacion.datosCliente.nit" ng-disabled="facturacion.datosCliente.idCliente!=1" id="cliente_nit" focus-enter>
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
										<input type="text" class="form-control" ng-model="facturacion.datosCliente.nombre" ng-disabled="facturacion.datosCliente.idCliente!=1" focus-enter>
									</div>
									<label class="col-sm-2 col-lg-1 control-label">DIRECCION</label>
									<div class="col-sm-5 col-lg-6">
										<input type="text" class="form-control" ng-model="facturacion.datosCliente.direccion" ng-disabled="facturacion.datosCliente.idCliente!=1" focus-enter>
									</div>
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
                                                    <input type="number" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" class="form-control" ng-model="formaPago.monto" focus-enter>
                                                </div>
                                            </div>
                                            <div class="form-group input-sm">
                                                <label class="col-md-12 control-label">CAMBIO</label>
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
                                                        <span class="glyphicon glyphicon-saved"></span> <b>FACTURAR (F6)</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-xs-8 col-sm-7 col-md-9 col-lg-8">
                                                {{ facturacion.lstOrden | json }}
                                        <fieldset class="fieldset" ng-show="facturacion.lstOrden.length">
                                            <legend class="legend info">DETALLE ORDEN</legend>
                                            <div class="table-responsive">
                                                <div class="text-right">
                                                    <div class="btn-group" role="group" aria-label="...">
                                                        <button type="button" class="btn btn-sm btn-default" ng-click="facturacion.tipoGrupo='agrupado'">
                                                            <span class="glyphicon" ng-class="{'glyphicon-check': facturacion.tipoGrupo=='agrupado', 'glyphicon-unchecked': facturacion.tipoGrupo!='agrupado'}"></span> <strong>AGRUPADO</strong>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-default" ng-click="facturacion.tipoGrupo='detalle'">
                                                            <span class="glyphicon" ng-class="{'glyphicon-check': facturacion.tipoGrupo=='detalle','glyphicon-unchecked': facturacion.tipoGrupo!='detalle'}"></span> <strong>A DETALLE</strong>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-default" ng-click="facturacion.tipoGrupo='individual'">
                                                            <span class="glyphicon" ng-class="{'glyphicon-check': facturacion.tipoGrupo=='individual', 'glyphicon-unchecked': facturacion.tipoGrupo!='individual'}"></span> <strong>INDIVIDUALES</strong>
                                                        </button>
                                                    </div>
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
                                                        <tr ng-repeat="item in facturacion.lstOrden" ng-class="{'warning': !item.cobrarTodo, 
                                                        'border-warning':(item.descuento>0 || item.idDetalleOrdenMenu!=4), 'border-danger': item.descuento == item.precioMenu}" ng-dblclick="item.cobrarTodo=!item.cobrarTodo">
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
                                                                <span data-placement="top" data-title="{{item.estadoDetalleOrden}}" bs-tooltip>{{ item.descripcion }}</span>
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
                                                                {{ ( item.cantidad * item.precioMenu ) -  item.descuento | number:2 }}
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
						</div>
					</div>
				</div>
			</div>
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
								<div class="form-group">
									<label class="col-sm-2 control-label">NIT</label>
									<div class="col-sm-3">
										<input type="text" ng-model="$parent.cliente.nit" class="form-control" id="nit" ng-pattern="/^[0-9-\s]+?$/" maxlength="15" focus-enter>
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
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65" focus-enter>
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
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} cliente (F6)
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
