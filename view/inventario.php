	<div class="row">
		<!-- Definir botones de inventario menu -->
		<div class="col-sm-12 text-center">
			<div class="btn-group">
				<button class="btn btn-default" ng-click="inventarioMenu=1;inventario();">
					<span class="glyphicon glyphicon-list"></span> Inventario
				</button>
				<button class="btn btn-default" ng-click="inventarioMenu=2;catTipoProducto();">
					<span class="glyphicon glyphicon-share-alt"></span> Tipo Producto
				</button>
				<button class="btn btn-default" ng-click="inventarioMenu=3;catMedidas();"> 
					<span class="glyphicon glyphicon-star-empty"></span> Medidas
				</button>
			</div>
		</div>

		<!--  tabla de porductos existentes -->
		<div class="col-sm-12" ng-show="inventarioMenu==1">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Inventario de productos</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7">
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/producto">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
						
					</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="col-sm-3 text-center">Producto</th>
								<th class="col-sm-2 text-center">Tipo Producto</th>
								<th class="col-sm-1 text-center">Medida</th>
								<th class="col-sm-1 text-center">Perecedero</th>
								<th class="col-sm-2 text-center">Disponibilidad</th>
								<th class="col-sm-2 text-center"></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="inv in lstInventario" ng-class="{'danger': !inv.disponibilidad, 'warning':  inv.disponibilidad < (inv.cantidadMinima + 5) }">
								<td>
									{{ inv.idProducto }}
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
									{{ inv.disponibilidad }}
								</td>
								<td class="text-center">	
									<a ng-click="ingresar(inv.idProducto,inv.producto)" type="button" class="btn btn-success btn-sm">
										<span class="glyphicon glyphicon-plus"></span>
									</a>

									<button type="button" class="btn btn-info btn-sm" ng-click="editarAccion( inv.idProducto, 'update' )">
										<span class="glyphicon glyphicon-pencil"></span>
									</button>
									<a ng-href="#/nuevoEdita/producto/{{inv.idProducto}}" type="button" class="btn btn-primary btn-sm">
										<span class="glyphicon glyphicon-edit"></span>
									</a>
									<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMninima">Pronto a agotarse</span>
								</td>
							</tr>
						</tbody>
					</table>
					<nav>
						<ul class="pagination">
							<li ng-repeat="(ixPagina, pagina) in lstPaginacion" ng-class="{'active': filter.pagina == pagina.noPagina}">
								<a href="" ng-click="cargarPaginacion( pagina.noPagina );">
									{{ pagina.noPagina }}
								</a>
							</li>
						</ul>
					</nav>

				</div>
			</div>
		</div>

		<!-- tipo de producto -->
		<div class="col-sm-6 col-sm-offset-3" ng-show="inventarioMenu==2">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Tipos de producto</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-sm-1">Tipo</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" ng-model="tipoProducto" maxlength="45">
						</div>
						<div class="col-sm-5">
							<button type="button" class="btn btn-success" ng-click="registraTipoProdcuto(tipoProducto)">Guradar</button>
							<button type="button" class="btn btn-default" ng-click="tipoProducto=''">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<b>Tipos Registrados</b>
					<ul class="list-group">
						<li class="list-group-item" ng-repeat="tp in lstTipoProducto">{{tp.tipoProducto}}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- medida de producto -->
		<div class="col-sm-6 col-sm-offset-3" ng-show="inventarioMenu==3">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Medida de producto</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="col-sm-2">Medida</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="medidaProducto" maxlength="45">
						</div>
						<div class="col-sm-5">
							<button type="button" class="btn btn-success" ng-click="registraMedidaProducto(medidaProducto)">Guradar</button>
							<button type="button" class="btn btn-default" ng-click="medidaProducto='';">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-body">
					<b>Medidas Registradas</b>
					<ul class="list-group">
						<li class="list-group-item" ng-repeat="m in lstMedidas">{{m.medida}}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- ingreso de productos a inventario -->
		<div class="col-sm-12" ng-show="inventarioMenu==4">
			<br>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Ingreso de productos</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-1">Producto</label>
							<div class="col-sm-4">
								<input type="text" class="form-control">
							</div>
							<label class="col-sm-1">Cantidad</label>
							<div class="col-sm-2">
								<input type="number" class="form-control">
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary">Ingresar</button>
								<button type="button" class="btn btn-default">Cancelar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- dialogo ingreso a existencias -->
	<script type="text/ng-template" id="dial.ingreso.html">
		<div class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content panel-primary">
					<div class="modal-header panel-heading">
						<button type="button" class="close" ng-click="$hide()">&times;</button>
						Ingresar existencia de {{nombreProducto}}
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="col-sm-2">Cantidad</label>
							<div class="col-sm-3">
								<input type="number" class="form-control" ng-model="$parent.cantidad">
							</div>
						</div>
						<br>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success" ng-click="guardaIngreso( idProducto, $parent.cantidad)">
							<span class="glyphicon glyphicon-ok"></span> Guardar
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
				<div class="modal-content" ng-class="{'panel-success': accion == 'insert', 'panel-info': accion == 'update'}">
					<div class="modal-header panel-heading">
						<button type="button" class="close" ng-click="$hide()">&times;</button>
						<h3 class="panel-title">
							<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
							{{ accion == 'insert' ? 'INGRESAR NUEVO' : 'ACTUALIZAR' }} PRODUCTO
						</h3>
					</div>
					<div class="modal-body">
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
									<label class="control-label col-sm-3">Disponibilidad</label>
									<div class="col-sm-2">
										<input type="number" min="0" class="form-control" ng-model="producto.disponibilidad" ng-disabled="accion!='insert'">
									</div>
									<label class="control-label col-sm-3">Producto Importante</label>
									<div class="col-sm-1">
										<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.importante, 'btn-warning':!producto.importante}" ng-click="producto.importante=!producto.importante">
											<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.importante, 'glyphicon-check' : producto.importante}"></span>
											{{ producto.importante ? 'SI' : 'NO' }}
										</button>
									</div>
								</div>
							</form>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm" ng-class="{'btn-success': accion == 'insert', 'btn-info': accion == 'update'}" ng-click="consultaProducto()">
							<span class="glyphicon glyphicon-ok"></span> Guardar
						</button>
						<button type="button" class="btn btn-default" ng-click="resetValues( 1 ); $hide()">
							<span class="glyphicon glyphicon-log-out"></span>
							<b>Salir</b>
						</button>
					</div>
				</div>
			</div>
		</div>
	</script>


