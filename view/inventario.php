<div class="contenedor">
	<div class="row">
		<div class="col-sm-12">

			<!-- MENU TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : inventarioMenu==1}" ng-click="resetValores(); inventarioMenu=1">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> INVENTARIO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu==2}" ng-click="resetValores(); inventarioMenu=2">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-share-alt"></span> TIPO PRODUCTO
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu==3}" ng-click="resetValores(); inventarioMenu=3">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-scale"></span> MEDIDAS
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : inventarioMenu==4}" ng-click="resetValores(); inventarioMenu=4">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-shopping-cart"></span> INGRESO
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
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==1}" ng-show="inventarioMenu==1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">INVENTARIO DE PRODUCTOS</h3>
						</div>
						<div class="panel-body">
							<!-- TABLA -->
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-center">No.</th>
										<th class="col-sm-3 text-center">Producto</th>
										<th class="col-sm-2 text-center">Tipo Producto</th>
										<th class="col-sm-1 text-center">Medida</th>
										<th class="col-sm-1 text-center">Perecedero</th>
										<th class="col-sm-2 text-center">Disponibilidad</th>
										<th class="text-center">Reajustar Cantidad</th>
										<th class="text-center">Editar</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="inv in lstInventario" ng-class="{'danger': !inv.disponibilidad, 'warning':  inv.disponibilidad <= (inv.cantidadMinima + 5) }">
										<td class="text-right">
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
							<!-- PAGINADOR -->
							<nav>
								<ul class="pagination" ng-show="lstPaginacion.length > 1">
									<li ng-class="{disabled: filter.pagina == 1 }">
										<a href="" ng-click="cargarPaginacion( 1 );" aria-label="Previous">
											<span aria-hidden="true">&laquo;</span>
										</a>
									</li>
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

				<!-- TIPOS DE PRODUCTO -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==2}" ng-show="inventarioMenu==2">
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
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==3}" ng-show="inventarioMenu==3">
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
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : inventarioMenu==4}" ng-show="inventarioMenu==4">
					<div class="col-md-offset-1 col-md-10 col-sm-12">
						<div class="panel panel-warning">
							<div class="panel-heading">
								<h4 class="panel-title">INGRESAR DE PRODUCTOS</h4>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form">
									<div class="form-group">
										<div class="col-sm-5 col-md-5">
											<label class="control-label">Producto</label>

											<input type="text" class="form-control" ng-model="nombreProducto" placeholder="Ingrese producto" ng-change="buscarProducto( nombreProducto )" ng-keydown="seleccionKeyProducto( $event.keyCode );">
											<ul class="list-group" ng-show="lstProductos.length">
												
											    <li class="list-group-item" ng-class="{'active': $parent.idxProducto == ixProducto}" ng-repeat="(ixProducto, producto) in lstProductos" ng-click="seleccionarProducto( producto )" ng-mouseenter="$parent.idxProducto = ixProducto">
											    	{{ producto.producto }}
											    </li>
											</ul>
										</div>
										<div class="col-sm-3 col-md-3">
											<label class="control-label">Cantidad</label>
											<input type="number" min="0" class="form-control" placeholder="Ingrese cantidad" >
										</div>
										<div class="col-sm-4">
											<br>
											<button type="button" class="btn btn-sm btn-warning">
												<span class="glyphicon glyphicon-plus"></span>
												Agregar
											</button>
											<button type="button" class="btn btn-sm btn-default">
												Cancelar
											</button>
										</div>
									</div>
									<!-- LISTA DE PRODUCTOS -->
									<div class="form-group">
										<legend class="text-center">
											<small>PRODUCTOS AGREGADOS</small>
										</legend>
										<table class="table table-striped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Producto</th>
													<th>Cantidad</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="form-group">
										<button type="button" class="btn btn-success">
											<span class="glyphicon glyphicon-saved"></span> GUARDAR
										</button>
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