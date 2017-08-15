<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">
			<div class="pull-right">
	            <img class="img-responsive" src="img/logo_churchil.png" style="height: 56px;">
	        </div>

			<!-- MENU TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : inventarioMenu=='inventario'}" ng-click="resetValores(); inventarioMenu='inventario'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> INVENTARIO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu=='tipoProducto'}" ng-click="resetValores(); inventarioMenu='tipoProducto'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-share-alt"></span> TIPO PRODUCTO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu=='medidas'}" ng-click="resetValores(); inventarioMenu='medidas'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-scale"></span> MEDIDAS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu=='compras'}" ng-click="resetValores(); inventarioMenu='compras'">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-shopping-cart"></span> INGRESAR COMPRAS
					</a>
				</li>
			</ul>

			<!-- INGRESO NUEVO PRODUCTO -->
			<div class="tab-content">
				<div class="text-right">
					<p>
						<button type="button" class="btn btn-success btn-sm" ng-click="editarAccion( null, 'insert' )">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Producto
						</button>
					</p>
				</div>

				<!--  PRODUCTOS DEL INVENTARIO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu=='inventario'}" ng-show="inventarioMenu=='inventario'">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">INVENTARIO DE PRODUCTOS</h3>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-default" ng-click="groupBy='sinFiltro'">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='sinFiltro', 'glyphicon-unchecked': groupBy!='sinFiltro'}"></span> Sin Filtro
								  	</button>
								  	<button type="button" class="btn btn-default" ng-click="groupBy='tipoProducto'">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='tipoProducto', 'glyphicon-unchecked': groupBy!='tipoProducto'}"></span> Tipo de Producto
								  	</button>
								  	<button type="button" class="btn btn-default" ng-click="groupBy='medida'">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='medida', 'glyphicon-unchecked': groupBy!='medida'}"></span> Medidas
								  	</button>
								</div>
							</div>

							<button class="btn btn-sm btn-warning" ng-click="realizarCierre=!realizarCierre">
								<span class="glyphicon glyphicon-pencil"></span> Realizar Cierre
							</button>

							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<div class="panel panel-default" ng-repeat="(ixInventario, inventario) in lstInventario">
									<div class="panel-heading" role="tab">
										<div class="pull-right">
											<span class="badge"> TOTAL {{ inventario.totalProductos }} </span>	
										</div>
										<h4 class="panel-title">
											<span ng-show="groupBy=='sinFiltro'">{{ inventario.listado }}</span>
											<span ng-show="groupBy=='tipoProducto'">{{ inventario.tipoProducto }}</span>
											<span ng-show="groupBy=='medida'">{{ inventario.medida }}</span>
										</h4>
									</div>
									<div class="panel-body">
										<div class="text-right">
											<div class="btn-group btn-group-xs" role="group">
												<button type="button" class="btn btn-sm btn-success">
													Stock Alto
													<span class="badge">
														{{ inventario.totalStockAlto }}
													</span>
												</button>										
												<button type="button" class="btn btn-sm btn-warning">
													Alerta Stock
													<span class="badge">
														{{ inventario.totalAlertas }}
													</span>
												</button>
												<button type="button" class="btn btn-sm btn-danger">
													Stock Vacio
													<span class="badge">{{ inventario.totalStockVacio }}</span>
												</button>
											</div>
										</div>
										<!-- TABLA -->
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-center">No.</th>
													<th class="col-sm-3 text-center">Producto</th>
													<th class="col-sm-2 text-center">Tipo Producto</th>
													<th class="col-sm-1 text-center">Medida</th>
													<th class="col-sm-1 text-center">Perecedero</th>
													<th class="col-sm-2 text-center">Disponible</th>
													<th class="text-center">Reajustar Cantidad</th>
													<th class="text-center">Editar</th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="inv in inventario.lstProductos" ng-class="{'danger border-danger': !inv.disponibilidad, 'warning border-warning':  inv.disponibilidad <= (inv.cantidadMinima + 5) }">
													<td class="text-right">
														{{ $index + 1 }}
													</td>
													<td>
														{{ inv.producto }}
													</td>
													<td class="text-center">
														{{ inv.tipoProducto }}
													</td>
													<td class="text-center">
														{{ inv.medida }}
													</td>
													<td class="text-center">
														{{ inv.esPerecedero }}
													</td>
													<td class="text-center">
														<input type="number" min="0" class="form-control" placeholder="Cantidad" ng-model="inv.disponibilidad" ng-disabled="!realizarCierre" required>
													</td>
													<td class="text-center">
														<button type="button" ng-click="ingresarReajuste( inv.idProducto, inv.producto, inv.disponibilidad )" class="btn btn-primary btn-sm">
															<span class="glyphicon glyphicon-plus"></span>
														</button>
													</td>
													<td class="text-center">
														<button type="button" class="btn btn-info btn-sm" ng-click="editarAccion( inv.idProducto, 'update', producto )">
															<span class="glyphicon glyphicon-pencil"></span>
														</button>
														<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMninima">Pronto a agotarse</span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- TIPOS DE PRODUCTO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu=='tipoProducto'}" ng-show="inventarioMenu=='tipoProducto'">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">TIPOS DE PRODUCTO</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-sm-1 col-md-2">Tipo</label>
									<div class="col-sm-6">
										<input type="text" id="tipoProducto" ng-keyup="$event.keyCode == 13 && consultaTipoProducto()" class="form-control" ng-model="tp.tipoProducto" maxlength="45">
									</div>
									<div class="col-sm-5 col-md-4">
										<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaTipoProducto()">
											<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 4 )">
											Cancelar
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<h4 class="panel-title">
									<span class="glyphicon glyphicon-sort-by-attributes"></span> TIPOS REGISTRADOS
								</h4>
								<br>
								<div class="col-sm-4 col-md-6">
								</div>
								<div class="col-sm-8 col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" ng-model="buscarTipoProducto" placeholder="Buscar tipo">
										<span class="input-group-addon" id="basic-addon1">
											<span class="glyphicon glyphicon-search"></span> BUSCAR
										</span>
									</div>
								</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center col-sm-1">No.</th>
											<th class="text-center col-sm-8">Tipo de producto</th>
											<th class="text-center col-sm-2">Editar</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="tp in lstResultTipos = (lstTipoProducto | filter: buscarTipoProducto)">
											<td class="text-center">{{ $index + 1 }}</td>
											<td>{{ tp.tipoProducto }}</td>
											<td class="text-center">
												<button class="btn btn-info btn-sm" ng-click="editarTipoProducto( tp )">
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="alert alert-info text-right" role="alert" ng-show="!lstResultTipos.length">
									<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- MEDIDA DE PRODUCTO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu=='medidas'}" ng-show="inventarioMenu=='medidas'">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">MEDIDA DE PRODUCTO</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<label class="col-sm-1 col-md-2">Medida</label>
									<div class="col-sm-6">
										<input type="text" id="medida" ng-keyup="$event.keyCode == 13 && consultaMedida()" class="form-control" ng-model="medidaProd.medida" maxlength="45">
									</div>
									<div class="col-sm-5 col-md-4">
										<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaMedida()">
											<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
										</button>
										<button type="button" class="btn btn-sm btn-default" ng-click="resetValores( 6 )">
											Cancelar
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<h4 class="panel-title">
									<span class="glyphicon glyphicon-sort-by-attributes"></span> MEDIDAS REGISTRADOS
								</h4>
								<br>
								<div class="col-sm-4 col-md-6">
								</div>
								<div class="col-sm-8 col-md-6">
									<div class="input-group">
										<input type="text" class="form-control" ng-model="buscarMedida" placeholder="Buscar medida">
										<span class="input-group-addon" id="basic-addon1">
											<span class="glyphicon glyphicon-search"></span> BUSCAR
										</span>
									</div>
								</div>
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center col-sm-1">No.</th>
											<th class="text-center col-sm-8">Tipo de producto</th>
											<th class="text-center col-sm-2">Editar</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="medida in lstResultMedida = (lstMedidas | filter: buscarMedida)">
											<td class="text-center">{{ $index + 1 }}</td>
											<td>{{ medida.medida }}</td>
											<td class="text-center">
												<button class="btn btn-info btn-sm" ng-click="editarMedida( medida )">
													<span class="glyphicon glyphicon-pencil"></span>
												</button>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="alert alert-info text-right" role="alert" ng-show="!lstResultMedida.length">
									<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- INGRESO DE PRODUCTOS A INVENTARIO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu=='compras'}" ng-show="inventarioMenu=='compras'">
					<div class="col-sm-12">
						<div class="panel panel-warning">
							<div class="panel-heading">
								<h4 class="panel-title">INGRESAR PRODUCTOS</h4>
							</div>
							<div class="panel-body">
								<div class="text-right">
									<h3>
										TOTAL: Q.{{ subTotalQuetzales() | number: 2 }} 
									</h3>
								</div>
								<!-- COMPRAS -->
								<form class="form-horizontal" novalidate autocomplete="off">
									<div class="form-group">
										<div class="col-sm-3">
											<label class="control-label">NO. FACTURA</label>
											<input type="number" class="form-control" id="numeroFactura" ng-model="compras.noFactura">
										</div>	
										<div class="col-sm-3">
											<label class="control-label">FECHA DE COMPRA</label>
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-calendar"></i>
												</span>
												<input type="text" class="form-control" ng-model="compras.fechaIngreso" data-date-format="dd/MM/yyyy" data-date-type="number"  data-max-date="today" data-autoclose="1" bs-datepicker>
											</div>
										</div>
										<div class="col-sm-5">
											<label class="control-label">PROVEEDOR</label>
											<input type="text" class="form-control" ng-model="compras.proveedor">
										</div>
									</div>
									<!-- SELECCIONAR PRODUCTOS -->
									<div class="form-group">
										<label class="label label-default">Agregar compras</label>
										<table class="table table-condensed">
											<thead>
												<tr>
													<th class="col-sm-5 text-center">PRODUCTO</th>
													<th class="col-sm-3 text-center">CANTIDAD</th>
													<th class="col-sm-3 text-center">PRECIO UNITARIO</th>
													<th class="text-center">AGREGAR</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div ng-show="!prod.seleccionado">
															<input type="text" id="producto" class="form-control" ng-model="prod.producto" maxlength="35" placeholder="Ingrese producto" ng-change="buscarProducto( prod.producto )" ng-keydown="seleccionKeyProducto( $event.keyCode );">
															<ul class="list-group ul-list" ng-show="lstProductos.length">

															    <li class="list-group-item" ng-class="{'active': $parent.idxProducto == ixProducto}" ng-repeat="(ixProducto, producto) in lstProductos" ng-click="seleccionarProducto( producto )" ng-mouseenter="$parent.idxProducto = ixProducto">
															    	{{ producto.producto }}
															    </li>
															</ul>
														</div>
														<div ng-show="prod.seleccionado">
															<input type="text" class="form-control" ng-model="prod.producto" placeholder="Ingrese producto" disabled>
														</div>
													</td>
													<td class="text-center">
														<input type="number" min="0.01" ng-model="prod.cantidad" id="cantidad" class="form-control" ng-keydown="$event.keyCode == 27 && cancelarIngreso( 1 );" placeholder="Cantidad" >
													</td>
													<td class="text-left">
														<input type="number" class="form-control" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" ng-keydown="( $event.keyCode == 13 && agregarIngresoProducto() ) || ( $event.keyCode == 27 && cancelarIngreso( 1 ) )" step="0.01" ng-model="prod.precioUnitario" placeholder="Precio Unitario">
													</td>
													<td class="text-center">
														<button type="button" class="btn btn-sm btn-warning" ng-click="agregarIngresoProducto();">
															<span class="glyphicon glyphicon-plus"></span> AGREGAR
														</button>
													</td>
												</tr>
												<tr>
													<td colspan="5" class="text-right">
														<button type="button" class="btn btn-sm btn-danger" ng-click="cancelarIngreso( 1 )">
															<span class="glyphicon glyphicon-remove"></span> CANCELAR
														</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<legend class="text-center">
										LISTADO DE PRODUCTOS
									</legend>
									<div class="form-group">
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-center">No.</th>
													<th class="col-sm-4 text-center">Producto</th>
													<th class="col-sm-2 text-center">Cantidad</th>
													<th class="col-sm-2 text-center">Precio Unitario</th>
													<th class="text-center">Total</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<!-- QUETZALES -->
												<tr ng-repeat="(ixProd, prod) in compras.lstProductos" ng-show="compras.lstProductos.length > 0">
													<td>
														{{ prod.idProducto }}
													</td>
													<td>
														{{ prod.producto }}
													</td>
													<td class="text-center">
														<input type="number" class="form-control" ng-model="prod.cantidad" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" min="0.01" placeholder="cantidad" ng-disabled="!prod.editar">
													</td>
													<td class="text-right">
														<input type="number" class="form-control" min="0.01" ng-model="prod.precioUnitario" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" placeholder="Cantidad" step="0.01" ng-disabled="!prod.editar">
													</td>
													<td class="text-right">
														{{ prod.cantidad * prod.precioUnitario | number: 2 }}
													</td>
													<td class="text-center">
														<!-- OPCIONES -->
														<div class="menu-opciones">
															<button type="button" class="btn btn-xs btn-info" ng-click="prod.editar=!prod.editar" ng-show="prod.editar">
																<span class="glyphicon glyphicon-ok"></span>
															</button>
															<button type="button" class="btn btn-xs btn-success" ng-click="prod.editar=!prod.editar" ng-show="!prod.editar">
																<span class="glyphicon glyphicon-pencil"></span>
															</button>
															<button type="button" class="btn btn-xs btn-danger" ng-click="quitarProdIngreso( ixProd )">
																<span class="glyphicon glyphicon-remove"></span>
															</button>
														</div>
													</td>
												</tr>
												<tr id="tb-title" ng-show="compras.lstProductos.length">
													<td colspan="5" class="text-right">
														<strong> TOTAL {{ subTotalQuetzales() | number: 2 }}</strong>
													</td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="form-group" style="margin-top: 15px">
										<div class="col-sm-12 text-center">
											<button type="button" class="btn btn-success btn-lg noBorder" ng-click="guardarLstProductoIngreso()">
												<span class="glyphicon glyphicon-saved"></span> Guardar
											</button>
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
</div>

	
<!-- DIALOGO INGRESO EXISTENCIA -->
<script type="text/ng-template" id="dial.ingreso.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content panel-warning">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-repeat"></span>
					INGRESAR REAJUSTE DEL PRODUCTO
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="pull-right">
								<b>PRODUCTO: </b>
								<label class="label label-warning">
									{{ itemProducto.nombreProducto | uppercase }}
								</label>
							</div>
							<hr>
							<form class="form-horizontal" role="form" name="$parent.formReajuste">
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Disponible</label>
										<input type="text" class="form-control" ng-model="itemProducto.disponibilidad" readonly>
									</div>
									<div class="col-sm-4">
									</div>
									<div class="col-sm-4">
										<label class="control-label">ID PRODUCTO</label>
										<input type="text" class="form-control" ng-model="itemProducto.idProducto" readonly>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Ingresar Cantidad</label>
										<input type="number" min="0" class="form-control" placeholder="Cantidad" ng-model="itemProducto.cantidad">
									</div>
									<div class="col-sm-2">
									</div>
									<div class="col-sm-5">
										<label class="control-label">Nueva Disponibilidad</label>
										<input type="text" class="form-control" placeholder="Cantidad" value="{{ retornarTotal() }}" readonly>											  	
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-2">
										<label class="control-label">DISPONIBILIDAD</label>
									</div>
									<div class="col-sm-10 text-center">
										<button type="button" class="btn btn-default" ng-class="{'btn-success': itemProducto.esIncremento}" ng-click="itemProducto.esIncremento=true">
											<span class="glyphicon" ng-class="{'glyphicon-check': itemProducto.esIncremento, 'glyphicon-unchecked': !itemProducto.esIncremento}"></span> AGREGAR
										</button>

										<button type="button" class="btn btn-default" ng-class="{'btn-danger': !itemProducto.esIncremento}" ng-click="itemProducto.esIncremento=false">
											<span class="glyphicon" ng-class="{'glyphicon-unchecked': itemProducto.esIncremento, 'glyphicon-check': !itemProducto.esIncremento}"></span> DISMINUIR
										</button>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label">INGRESE OBSERVACIÓN</label>
										<textarea rows="3" class="form-control" ng-model="itemProducto.observacion" placeholder="Ingrese la observación del reajuste"></textarea>
										<label class="label label-info">
											Caracteres <span class="badge">{{ itemProducto.observacion.length }}</span>
										</label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" ng-click="consultaReajusteInventario()">
						<span class="glyphicon glyphicon-saved"></span> Guardar
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


<!-- MODAL AGREGAR / EDITAR PRODUCTO -->
<script type="text/ng-template" id="dialAdmin.producto.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" ng-class="{'panel-warning': accion == 'insert', 'panel-info': accion == 'update'}">
				<div class="modal-header panel-heading">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<h3 class="panel-title">
						<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
						{{ accion == 'insert' ? 'INGRESAR NUEVO' : 'ACTUALIZAR' }} PRODUCTO
					</h3>
				</div>
				<div class="modal-body">
					<fieldset class="fieldset">
						<legend class="legend">DATOS</legend>
						<!-- FORMULARIO PRODUCTO -->
						<form class="form-horizontal" role="form" name="$parent.formProducto">
							<div class="form-group">
								<div class="col-sm-7">
									<label class="control-label">Nombre Producto</label>
									<input type="text" class="form-control" ng-model="producto.producto" maxlength="45" required>
								</div>
								<div class="col-sm-3" ng-show="accion!='insert'">
									<label class="control-label">No. Producto</label>
									<input type="text" class="form-control" ng-model="producto.idProducto" readonly>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-5">
									<label class="control-label">Tipo Producto</label>
									<select class="form-control" ng-model="producto.idTipoProducto" required>
										<option ng-repeat="tp in lstTipoProducto" value="{{ tp.idTipoProducto }}">
											{{ tp.tipoProducto }}
										</option>
									</select>
								</div>
								<div class="col-sm-4">
									<label class="control-label">Tipo Medida</label>
									<select class="form-control" ng-model="producto.idMedida" required>
										<option ng-repeat="m in lstMedidas" value="{{m.idMedida}}">{{m.medida}}</option>
									</select>
								</div>
								<div class="col-sm-1">
									<label class="control-label">Pedecedero</label>
									<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.perecedero, 'btn-warning':!producto.perecedero}" ng-click="producto.perecedero=!producto.perecedero">
										<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.perecedero, 'glyphicon-check' : producto.perecedero}"></span>
										{{ producto.perecedero ? 'SI' : 'NO' }}
									</button>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Cantidad minima</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.cantidadMinima" required>
								</div>
								<label class="control-label col-sm-2">Cantidad maxima</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.cantidadMaxima">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Disponibilidad</label>
								<div class="col-sm-3">
									<input type="number" min="0" class="form-control" ng-model="producto.disponibilidad" ng-disabled="accion!='insert'">
								</div>
								<label class="control-label col-sm-2">Producto Importante</label>
								<div class="col-sm-1">
									<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.importante, 'btn-warning':!producto.importante}" ng-click="producto.importante=!producto.importante">
										<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.importante, 'glyphicon-check' : producto.importante}"></span>
										{{ producto.importante ? 'SI' : 'NO' }}
									</button>
								</div>
							</div>
						</form>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaProducto()">
						<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }}
					</button>
					<button type="button" class="btn btn-default" ng-click="resetValores( 1 ); $hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>