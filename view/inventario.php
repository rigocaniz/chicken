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
						<button type="button" class="btn btn-success" ng-click="editarAccion( null, 'insert' )">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Producto
						</button>
						<button type="button" class="btn btn-info noBorde" ng-click="cargarLstFacturaCompra()" ng-show="inventarioMenu=='compras'">
							<span class="glyphicon glyphicon-th-list"></span> VER INGRESOS
						</button>

						<button type="button" class="btn btn-info noBorde" ng-click="verCierreDiario()" ng-show="inventarioMenu=='inventario'">
							<span class="glyphicon glyphicon-th-list"></span> VER CIERRE DIARIO
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
							<div class="pull-right">
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-default" ng-click="groupBy='sinFiltro'">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='sinFiltro', 'glyphicon-unchecked': groupBy!='sinFiltro'}"></span> Sin Filtro
								  	</button>
								  	<button type="button" class="btn btn-default" ng-click="groupBy='tipoProducto'" ng-disabled="realizarReajuste">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='tipoProducto', 'glyphicon-unchecked': groupBy!='tipoProducto'}"></span> Tipo de Producto
								  	</button>
								  	<button type="button" class="btn btn-default" ng-click="groupBy='medida'" ng-disabled="realizarReajuste">
								  		<span class="glyphicon" ng-class="{'glyphicon-check': groupBy=='medida', 'glyphicon-unchecked': groupBy!='medida'}"></span> Medidas
								  	</button>
								</div>
							</div>
							<div class="text-left">
								<button class="btn btn-danger" ng-click="realizarCierre()" ng-disabled="realizarReajuste">
									<span class="glyphicon glyphicon-list-alt"></span> REALIZAR CIERRE
								</button>
								<button class="btn btn-primary" ng-click="realizarReajusteMasivo()" ng-show="!realizarReajuste">
									<span class="glyphicon glyphicon-edit"></span> REAJUSTE MASIVO
								</button>

								<button type="button" class="btn btn-danger" ng-click="cancelarReajuste()" ng-show="realizarReajuste">
									<span class="glyphicon glyphicon-remove"></span> CANCELAR REAJUSTE
								</button>
							</div>
							<br>
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<div class="panel panel-default" ng-repeat="(ixInventario, inventario) in lstInventario">
									<div class="panel-heading" role="tab">
										<div class="pull-right">
											<span class="badge"> TOTAL {{ inventario.totalProductos }} </span>	
										</div>
										<h4 class="panel-title">
											<button type="button" class="btn btn-default btn-sm" ng-click="inventario.mostrar=!inventario.mostrar">
												<span class="glyphicon" ng-class="{'glyphicon-chevron-down': !inventario.mostrar, 'glyphicon-chevron-right' : inventario.mostrar}"></span>
											</button>
											<span ng-show="groupBy=='sinFiltro'">{{ inventario.listado }}</span>
											<span ng-show="groupBy=='tipoProducto'">{{ inventario.tipoProducto }}</span>
											<span ng-show="groupBy=='medida'">{{ inventario.medida }}</span>
										</h4>
									</div>
									<div class="panel-body" ng-show="inventario.mostrar">
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
										<br>
										<div class="row">
										  	<div class="col-sm-5 col-md-6 col-lg-7"></div>
										  	<div class="col-sm-7 col-md-6 col-lg-5">
										    	<div class="input-group">
										      		<span class="input-group-addon" id="basic-addon1">
										        		<span class="glyphicon glyphicon-search"></span>
										      		</span>
										      		<input type="text" class="form-control" ng-model="filtroProducto" maxlength="75" placeholder="Buscar producto">
										    	</div>
										  	</div>
										</div>
										<!-- TABLA -->
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-center">No.</th>
													<th class="col-sm-3 text-center">Producto</th>
													<th class="col-sm-2 text-center">Tipo Producto</th>
													<th class="col-sm-1 text-center">Perecedero</th>
													<th class="text-center">Mínimo</th>
													<th class="text-center">Máximo</th>
													<th class="col-sm-2 text-center">Disponible</th>
													<th class="col-sm-2 text-center" ng-show="realizarReajuste">Cantidad</th>
													<th class="col-sm-1 text-center" ng-show="realizarReajuste"></th>
													<th class="col-sm-2 text-center" ng-show="realizarReajuste">Nueva Disponibilidad</th>
													<th class="col-sm-1 text-center">Medida</th>
													<th class="text-center" ng-show="!realizarReajuste">Reajustar Cantidad</th>
													<th class="text-center" ng-show="!realizarReajuste">Editar</th>
												</tr>
											</thead>
											<tbody>
												<tr ng-repeat="inv in inventario.lstProductos | filter: filtroProducto" ng-class="{'danger border-danger': inv.alertaStock == 1 && !realizarReajuste, 'warning border-warning':  inv.alertaStock == 2 && !realizarReajuste, 'border-success':  inv.alertaStock == 3 && !realizarReajuste}">
													<td class="text-right">
														{{ $index + 1 }}
													</td>
													<td>
														{{ inv.producto }}
														<br>
														<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMinima">
															Pronto a agotarse
														</span>
													</td>
													<td class="text-center">
														{{ inv.tipoProducto }}
													</td>
													<td class="text-center">
														{{ inv.esPerecedero }}
													</td>
													<td class="text-center">
														{{ inv.cantidadMinima }}
													</td>
													<td class="text-center">
														{{ inv.cantidadMaxima }}
													</td>
													<td class="text-center">
														{{ inv.disponibilidad }}
													</td>
													<td class="text-center" ng-show="realizarReajuste">
														<input type="number" min="0" class="form-control" placeholder="Cantidad" ng-model="inv.cantidad">
													</td>
													<td class="text-center" ng-show="realizarReajuste">
														<button class="btn btn-xs" ng-class="{'btn-success': inv.esIncremento, 'btn-danger': !inv.esIncremento}" ng-click="inv.esIncremento=!inv.esIncremento" title="Clic para {{ inv.esIncremento ? 'DISMINUIR' : 'INCREMENTAR' }}" data-toggle="tooltip" data-placement="top" tooltip ng-disabled="!inv.cantidad">
															<span class="glyphicon" ng-class="{'glyphicon-plus-sign': inv.esIncremento, 'glyphicon-minus-sign': !inv.esIncremento}"></span>
														</button>
													</td>
													<td class="text-center" ng-class="{'danger': retornarTotalReajuste( inv.disponibilidad, inv.cantidad, inv.esIncremento ) < 0}" ng-show="realizarReajuste">
														<b>{{ retornarTotalReajuste( inv.disponibilidad, inv.cantidad, inv.esIncremento ) }}</b>
													</td>
													<td class="text-center">
														{{ inv.medida }}
													</td>
													<td class="text-center" ng-hide="realizarReajuste">
														<button type="button" ng-click="ingresarReajuste( inv.idProducto, inv.producto, inv.disponibilidad )" class="btn btn-primary btn-sm">
															<span class="glyphicon glyphicon-plus"></span>
														</button>
													</td>
													<td class="text-center" ng-hide="realizarReajuste">
														<button type="button" class="btn btn-info btn-sm" ng-click="editarAccion( inv.idProducto, 'update', producto )">
															<span class="glyphicon glyphicon-pencil"></span>
														</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="text-center" ng-show="realizarReajuste">
								<textarea class="form-control" rows="2" ng-model="reajusteMasivo.observacion" placeholder="Ingresar Observaciones (Opcional)"></textarea>
							</div>
							<hr>
							<div class="text-center" ng-show="realizarReajuste">
								<button type="button" class="btn btn-success" ng-click="guardarReajusteMasivo()" ng-disabled="loading">
									<span class="glyphicon glyphicon-saved"></span> REALIZAR REAJUSTE
								</button>
								<button type="button" class="btn btn-danger" ng-click="cancelarReajuste()">
									<span class="glyphicon glyphicon-remove"></span> CANCELAR REAJUSTE
								</button>
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
										<input type="text" id="tipoProducto" ng-keyup="$event.keyCode == 13 && consultaTipoProducto()" class="form-control" ng-model="tp.tipoProducto" maxlength="45" ng-disabled="loading">
									</div>
									<div class="col-sm-5 col-md-4">
										<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaTipoProducto()" ng-disabled="loading">
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
										<input type="text" id="medida" ng-keyup="$event.keyCode == 13 && consultaMedida()" class="form-control" ng-model="medidaProd.medida" maxlength="45" ng-disabled="loading">
									</div>
									<div class="col-sm-5 col-md-4">
										<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaMedida()" ng-disabled="loading">
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
					<div class="panel panel-danger">
						<div class="panel-heading">
							<h4 class="panel-title">
								<span class="glyphicon glyphicon-shopping-cart"></span>
								INGRESAR PRODUCTOS
							</h4>
						</div>
						<div class="panel-body">
							<div class="text-right">
								<h3 class="total">
									TOTAL: Q.{{ subTotalQuetzales() | number: 2 }} 
								</h3>
							</div>
							<!-- COMPRAS -->
							<form class="form-horizontal" novalidate autocomplete="off">
								<div class="form-group">
									<div class="col-sm-3">
										<label class="control-label">NO. FACTURA</label>
										<input type="text" maxlength="15" class="form-control" id="numeroFactura" ng-model="compras.noFactura">
									</div>	
									<div class="col-sm-4 col-md-3">
										<label class="control-label">FECHA DE COMPRA</label>
										<div class="input-group">
											<span class="input-group-addon">
												<i class="glyphicon glyphicon-calendar"></i>
											</span>
											<input type="text" class="form-control" ng-model="compras.fechaFactura" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker>
										</div>
									</div>
									<div class="col-sm-5">
										<label class="control-label">PROVEEDOR</label>
										<input type="text"  maxlength="45" class="form-control" ng-model="compras.proveedor">
									</div>
								</div>
								<div class="form-group" style="margin-bottom: 0;">
									<div class="col-sm-6 col-md-5">
										<label class="control-label">ESTADO DE LA FACTURA</label>
										<div class="btn-group" role="group" aria-label="">
										
											<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-success': compras.idEstadoFactura==1 && compras.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-danger': compras.idEstadoFactura==2 && compras.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-warning': compras.idEstadoFactura==3 && compras.idEstadoFactura == estadoFactura.idEstadoFactura}" ng-repeat="estadoFactura in lstEstadosFactura" ng-click="compras.idEstadoFactura = estadoFactura.idEstadoFactura">
												<span class="glyphicon" ng-class="{'glyphicon-check': compras.idEstadoFactura == estadoFactura.idEstadoFactura, 'glyphicon-unchecked': compras.idEstadoFactura != estadoFactura.idEstadoFactura}"></span>
												{{ estadoFactura.estadoFactura }}
											</button>
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<label class="control-label">COMENTARIO</label>
										<textarea class="form-control" ng-model="compras.comentario" placeholder="Ingresar comentario (Opcional)"></textarea>
									</div>
								</div>
								<hr>
								<!-- SELECCIONAR PRODUCTOS -->
								<div class="form-group" style="margin-bottom: 0;">
									<table class="table table-condensed">
										<thead>
											<tr>
												<th class="col-sm-5 text-center">PRODUCTO</th>
												<th class="col-sm-3 text-center">CANTIDAD ({{prod.medida}})</th>
												<th class="col-sm-3 text-center">TOTAL</th>
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
														    	{{ producto.producto }} <small>(<b>{{ producto.medida }})</b></small>
														    </li>
														</ul>
													</div>
													<div ng-show="prod.seleccionado">
														<input type="text" class="form-control" ng-model="prod.producto" placeholder="Ingrese producto" disabled>
													</div>
												</td>
												<td class="text-center">
													<input type="number" min="0.01" ng-model="prod.cantidad" id="cantidad" class="form-control" ng-keydown="$event.keyCode == 27 && cancelarIngreso( 1 );" placeholder="Cantidad">
												</td>
												<td class="text-left">
													<input type="number" class="form-control" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" ng-keydown="( $event.keyCode == 13 && agregarIngresoProducto() ) || ( $event.keyCode == 27 && cancelarIngreso( 1 ) )" step="0.01" ng-model="prod.costo" placeholder="Total">
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
								<div class="form-group">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-center">No.</th>
												<th class="col-sm-4 text-center">Producto</th>
												<th class="col-sm-3 text-center">Cantidad</th>
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
													<input type="number" class="form-control" min="0.01" ng-model="prod.costo" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" placeholder="Cantidad" step="0.01" ng-disabled="!prod.editar">
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
												<td colspan="4" class="text-right">
													<strong> TOTAL {{ subTotalQuetzales() | number: 2 }}</strong>
												</td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="form-group" style="margin-top: 15px">
									<div class="col-sm-12 text-center">
										<button type="button" class="btn btn-success btn-lg noBorder" ng-click="consultaFactura( 'insert' )" ng-disabled="loading">
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


<!-- DIALOGO LST FACTURAS COMPRAS -->
<script type="text/ng-template" id="dial.editarFacturaCompra.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-danger">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-edit"></span>
					EDITAR FACTURA / COMPRA
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="col-sm-3">
								<label class="control-label">NO. FACTURA</label>
								<input type="text" maxlength="15" class="form-control" id="numeroFactura" ng-model="facturaCompra.noFactura">
							</div>	
							<div class="col-sm-4 col-md-3">
								<label class="control-label">FECHA DE COMPRA</label>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
									</span>
									<input type="text" class="form-control" ng-model="facturaCompra.fechaFactura" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker>
								</div>
							</div>
							<div class="col-sm-5">
								<label class="control-label">PROVEEDOR</label>
								<input type="text" maxlength="45" class="form-control" ng-model="facturaCompra.proveedor">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6 col-md-5">
								<label class="control-label">ESTADO DE LA FACTURA</label>
								<div class="btn-group" role="group" aria-label="">
									<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-success': facturaCompra.idEstadoFactura==1 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-danger': facturaCompra.idEstadoFactura==2 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-warning': facturaCompra.idEstadoFactura==3 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura}" ng-repeat="estadoFactura in lstEstadosFactura" ng-click="facturaCompra.idEstadoFactura = estadoFactura.idEstadoFactura">
										<span class="glyphicon" ng-class="{'glyphicon-check': facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'glyphicon-unchecked': facturaCompra.idEstadoFactura != estadoFactura.idEstadoFactura}"></span>

										{{ estadoFactura.estadoFactura }}
									</button>
								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<label class="control-label">COMENTARIO</label>
								<textarea class="form-control" ng-model="facturaCompra.comentario" placeholder="Ingresar comentario (Opcional)"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6 col-md-5">
								<small>
									<kbd>Usuario: {{ facturaCompra.usuario }}</kbd>
								</small>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" ng-click="consultaFactura( 'update' )">
						<span class="glyphicon glyphicon-saved"></span> Guardar
					</button>

					<button type="button" class="btn btn-default" ng-click="facturaCompra={};$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- DIALOGO LST INGRESO PRODUDCTOS -->
<script type="text/ng-template" id="dial.verDetalleFacturaCompra.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-primary">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-list"></span>
					DETALLE FACTURA/INGRESO COMPRAS
				</div>
				<div class="modal-body">
					<form class="form-horizontal" novalidate autocomplete="off">
						<div class="form-group">
							<div class="col-sm-6 col-md-5">
								<label class="control-label">INGRESADO POR:</label>
								<div>
									<kbd>USUARIO: {{ facturaCompra.usuario | uppercase }}</kbd>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-3">
								<label class="control-label">NO. FACTURA</label>
								<input type="text" maxlength="15" class="form-control" id="numeroFactura" ng-model="facturaCompra.noFactura" disabled>
							</div>	
							<div class="col-sm-4 col-md-3">
								<label class="control-label">FECHA DE COMPRA</label>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
									</span>
									<input type="text" class="form-control" ng-model="facturaCompra.fechaFactura" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker disabled>
								</div>
							</div>
							<div class="col-sm-5">
								<label class="control-label">PROVEEDOR</label>
								<input type="text" maxlength="45" class="form-control" ng-model="facturaCompra.proveedor" disabled>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6 col-md-5">
								<label class="control-label">ESTADO DE LA FACTURA</label>
								<div class="btn-group" role="group" aria-label="">
									<button type="button" class="btn btn-default btn-sm" ng-class="{'btn-success': facturaCompra.idEstadoFactura==1 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-danger': facturaCompra.idEstadoFactura==2 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'btn-warning': facturaCompra.idEstadoFactura==3 && facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura}" ng-repeat="estadoFactura in lstEstadosFactura" ng-click="facturaCompra.idEstadoFactura = estadoFactura.idEstadoFactura" disabled>
										<span class="glyphicon" ng-class="{'glyphicon-check': facturaCompra.idEstadoFactura == estadoFactura.idEstadoFactura, 'glyphicon-unchecked': facturaCompra.idEstadoFactura != estadoFactura.idEstadoFactura}"></span>

										{{ estadoFactura.estadoFactura }}
									</button>
								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<label class="control-label">COMENTARIO</label>
								<textarea class="form-control" ng-model="facturaCompra.comentario" placeholder="Ingresar comentario (Opcional)" disabled></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-10 col-sm-offset-1">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-center">No.</th>
												<th class="col-sm-4 text-center">Producto</th>
												<th class="col-sm-3 text-center">Cantidad</th>
												<th class="col-sm-2 text-center">Medida</th>
												<th class="text-center">Total</th>
											</tr>
										</thead>
										<tbody>
											<!-- QUETZALES -->
											<tr ng-repeat="(ixProd, prod) in facturaCompra.lstProductos">
												<td>
													{{ prod.idProducto }}
												</td>
												<td>
													{{ prod.producto }}
												</td>
												<td class="text-center">
													{{ prod.cantidad }}
												</td>
												<td class="text-center">
													{{ prod.medida }}
												</td>
												<td class="text-right">
													{{ prod.costo }}
												</td>
											</tr>
											<tr id="tb-title" ng-show="compras.lstProductos.length">
												<td colspan="4" class="text-right">
													<strong> TOTAL {{ subTotalQuetzales() | number: 2 }}</strong>
												</td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" ng-click="facturaCompra={};$hide()">
						<span class="glyphicon glyphicon-log-out"></span>
						<b>Salir</b>
					</button>
				</div>				
			</div>
		</div>
	</div>
</script>


<!-- DIALOGO LST FACTURAS COMPRAS -->
<script type="text/ng-template" id="dial.lstFacturaCompra.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-danger">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-shopping-cart"></span>
					FACTURAS / COMPRAS INGRESADAS
				</div>
				<div class="modal-body">
					<div class="text-right">
						<div class="btn-group btn-group-sm" role="group" aria-label="...">
						  	<button type="button" class="btn btn-success">
						  		{{ detalleFacturaCompra.pagado }} <span class="badge">{{ detalleFacturaCompra.totalPagadas }}</span>
						  	</button>
						  	<button type="button" class="btn btn-danger">
						  		{{ detalleFacturaCompra.pendiente }} <span class="badge">{{ detalleFacturaCompra.totalPendientes }}</span>
						  	</button>
						  	<button type="button" class="btn btn-warning">
						  		{{ detalleFacturaCompra.pagoParcial }} <span class="badge">{{ detalleFacturaCompra.totalPagosParcial }}</span>
						  	</button>
						</div>
					</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="col-sm-3 text-center">Factura</th>
								<th class="col-sm-3 text-center">Proveedor</th>
								<th class="col-sm-2 text-center">Fecha Factura</th>
								<th class="col-sm-2 text-center">Estado Factura</th>
								<th class="col-sm-2 text-center">Usuario</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<tr dir-paginate="facturaCompra in detalleFacturaCompra.lstFacturaCompra | filter: filtroCancion | itemsPerPage: 25">
								<td class="text-center">
									{{ $index + 1 }}
								</td>
								<td>
									{{ facturaCompra.noFactura }}
									<br>
									<button type="button" class="btn btn-xs btn-default" ng-click="facturaCompra.mostrar=!facturaCompra.mostrar" ng-show="facturaCompra.comentario.length">
										<span class="glyphicon glyphicon-eye-open"></span>
									</button>
									<div ng-show="facturaCompra.mostrar">
										{{ facturaCompra.comentario }}
									</div>
								</td>
								<td class="text-center">
									{{ facturaCompra.proveedor }}
								</td>
								<td class="text-center">
									{{ facturaCompra.fechaFact }}
								</td>
								<td class="text-center" ng-class="{'danger': facturaCompra.idEstadoFactura == 2, 'warning': facturaCompra.idEstadoFactura == 3}">
									{{ facturaCompra.estadoFactura }}
								</td>
								<td class="text-center">
									{{ facturaCompra.usuario }}
								</td>
								<td class="text-center">
									<div class="menu-contenedor">
										<button type="button" class="btn btn-warning noBorde">
											<span class="glyphicon glyphicon-th"></span>
										</button>
										<div class="menu-horizontal">
											<button type="button" class="btn" ng-click="editarFacturaCompra( facturaCompra, 'editar' )" title="Editar" data-toggle="tooltip" data-placement="top" tooltip>
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
											<button type="button" class="btn" ng-click="editarFacturaCompra( facturaCompra, 'verDetalle' )" title="Ver Detalle" data-toggle="tooltip" data-placement="top" tooltip>
												<span class="glyphicon glyphicon-th-list"></span>
											</button>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
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


<!-- DIALOGO CONSULTAR CIERRE DIARIO -->
<script type="text/ng-template" id="dial.verCierreDiario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-primary">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-list-alt"></span>
					HISTORIAL CIERRE DIARIO DE INVENTARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form" name="$parent.formCierre">
						<div class="form-group">
							<div class="col-sm-4">
								<label class="control-label">SELECCIONE LA FECHA DE CIERRE</label>
								<div class="input-group">
								  	<span class="input-group-addon">
    									<span class="fa fa-calendar"></span>
								  	</span>
    								<input type="text" class="form-control"  ng-model="fechaCierre" ng-change="cargarFechaCierre( fechaCierre )" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker>
								</div>
							</div>	
						</div>
						<hr>
						<div ng-show="fechaCierreP.encontrado">
							<div class="form-group">
								<div class="col-sm-3">
									<label class="control-label">FECHA DE CIERRE</label>
									<div class="input-group">
									  	{{ fechaCierreP.fechaCierre }}
									</div>
								</div>	
								<div class="col-sm-3">
									<label class="control-label">REALIZADO POR:</label>
									<div>
										<kbd>{{ fechaCierreP.usuario | uppercase }}</kbd>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="control-label">FECHA / HORA:</label>
									<div>
										<kbd>{{ fechaCierreP.fechaHora }}</kbd>
									</div>
								</div>
								<div class="col-sm-3">
									<label class="control-label">TIPO DE CIERRE:</label>
									<div>
										<kbd>{{ fechaCierreP.todos ? 'TODOS LOS PRODUCTOS' : 'PRODUCTOS IMPORTANTES' }}</kbd>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label">COMENTARIO:</label>
									<div>
										{{ fechaCierreP.comentario }}
									</div>
								</div>
							</div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="col-sm-1 text-center">No.</th>
										<th class="col-sm-3 text-center">Producto</th>
										<th class="col-sm-2 text-center">Tipo Producto</th>
										<th class="col-sm-1 text-center">Perecedero</th>
										<th class="col-sm-2 text-center">Cantidad</th>
										<th class="col-sm-2 text-center">Medida</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="inv in fechaCierreP.lstProductos" ng-class="{'border-success':inv.importante}">
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
											{{ inv.perecedero ? 'SI' : 'NO' }}
										</td>
										<td class="text-center success">
											{{ inv.cantidadCierre }}
										</td>
										<td class="text-center">
											{{ inv.medida }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div ng-show="!fechaCierreP.encontrado && fechaCierre">
							<div class="alert alert-warning" role="alert">
								<span class="glyphicon glyphicon-info-sign"></span> NO SE ENCONTRARON RESULTADOS
							</div>
						</div>
					</form>
			
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" ng-click="consultaCierreDiario()" ng-disabled="loading">
						<span class="glyphicon glyphicon-saved"></span> REALIZAR CIERRE
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

<!-- DIALOGO CIERRE DIARIO -->
<script type="text/ng-template" id="dial.cierreDiario.html">
	<div class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-danger">
				<div class="modal-header panel-heading text-center">
					<button type="button" class="close" ng-click="$hide()">&times;</button>
					<span class="glyphicon glyphicon-list-alt"></span>
					REALIZAR CIERRE DIARIO DE INVENTARIO
				</div>
				<div class="modal-body">
					<form class="form-horizontal" role="form" name="$parent.formCierre">
						<div class="form-group">
							<div class="col-sm-4">
								<label class="control-label">FECHA DEL CIERRE</label>
								<div class="input-group">
								  	<span class="input-group-addon">
    									<span class="fa fa-calendar"></span>
								  	</span>
    								<input type="text" class="form-control" ng-model="cierreDiario.fechaCierre" data-date-format="dd/MM/yyyy" data-max-date="today" data-autoclose="1" bs-datepicker>
								</div>
							</div>
							<div class="col-sm-4">
								<label class="control-label">SELECCIONAR OPCIÓN</label>
								<div class="btn-group" role="group" aria-label="">
								  	<button type="button" class="btn" ng-click="cierreDiario.cierreTodos=true" ng-class="{'btn-info': cierreDiario.cierreTodos, 'btn-default': !cierreDiario.cierreTodos}" title="Todos los productos" data-toggle="tooltip" data-placement="top" tooltip>
								  		<span class="glyphicon" ng-class="{'glyphicon-check': cierreDiario.cierreTodos, 'glyphicon-unchecked': !cierreDiario.cierreTodos}"></span>
								  		Todos
								  	</button>
								  	<button type="button" class="btn" ng-click="cierreDiario.cierreTodos=false" ng-class="{'btn-info': !cierreDiario.cierreTodos, 'btn-default': cierreDiario.cierreTodos}" title="Solo productos importantes" data-toggle="tooltip" data-placement="top" tooltip>
								  		<span class="glyphicon" ng-class="{'glyphicon-check': !cierreDiario.cierreTodos, 'glyphicon-unchecked': cierreDiario.cierreTodos}"></span>
								  		Importantes
								  	</button>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="control-label">AFECTAR DISPONIBILIDAD*</label>
								<button type="button" class="btn" ng-class="{'btn-success': cierreDiario.actualizarDisp, 'btn-warning': !cierreDiario.actualizarDisp}" ng-click="cierreDiario.actualizarDisp=!cierreDiario.actualizarDisp" title="Afectar Disponibilidad en el INVENTARIO" data-toggle="tooltip" data-placement="top" tooltip>
									<span class="glyphicon" ng-class="{'glyphicon-check': cierreDiario.actualizarDisp, 'glyphicon-unchecked': !cierreDiario.actualizarDisp}"></span> {{ cierreDiario.actualizarDisp ? 'SI' : 'NO' }}
								</button>
							</div>
							<div class="col-sm-2" ng-show="accion=='update'">
								<label class="control-label">NO. DE CIERRE</label>
								<input type="text" class="form-control" ng-model="cierreDiario.idCierreDiario" readonly>
							</div>	
						</div>
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th class="col-sm-3 text-center">Producto</th>
									<th class="col-sm-1 text-center">Perecedero</th>
									<th class="col-sm-2 text-center">Disponible <br>Sistema(*)</th>
									<th class="col-sm-2 text-center">Disponible <br>Fisico</th>
									<th class="col-sm-2 text-center">Medida</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="inv in cierreDiario.lstProductos" ng-show="cierreDiario.cierreTodos || (!cierreDiario.cierreTodos && inv.importante == 1 )" ng-class="{'border-success': inv.importante == 1}">
									<td class="text-right">
										{{ $index + 1 }}
									</td>
									<td>
										{{ inv.producto }}
									</td>
									<td class="text-center">
										{{ inv.esPerecedero }}
									</td>
									<td class="text-center warning">
										{{ inv.disponible }}
									</td>
									<td class="text-center success">
										<input type="number" min="0" class="form-control" placeholder="Cantidad" ng-model="inv.disponibilidad" required>
									</td>
									<td class="text-center">
										{{ inv.medida }}
									</td>
								</tr>
							</tbody>
						</table>
						<div class="form-group">
							<div class="col-sm-12">
								<label class="control-label">INGRESAR COMENTARIO</label>
								<textarea rows="3" class="form-control" ng-model="cierreDiario.comentario" placeholder="Ingresar comentario del cierre"></textarea>
								<div class="pull-right">
									<label class="label label-default">
										Caracteres <span class="badge">{{ cierreDiario.comentario.length }}</span>
									</label>
								</div>
							</div>
						</div>
					</form>
			
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" ng-click="consultaCierreDiario()" ng-disabled="loading">
						<span class="glyphicon glyphicon-saved"></span> REALIZAR CIERRE
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

	
<!-- DIALOGO INGRESO REAJUSTE EXISTENCIA -->
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
										<input type="text" class="form-control" placeholder="Cantidad" value="{{ retornarTotalReajuste( itemProducto.disponibilidad, itemProducto.cantidad, itemProducto.esIncremento ) }}" readonly>											  	
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
					<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaProducto()" ng-disabled="loading">
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