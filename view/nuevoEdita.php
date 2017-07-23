<div class="container">
	<div class="row">
		<!-- formulario de registro nuevo producto -->
		<div class="col-sm-12" ng-show="evento=='producto'">
			<br>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Registrar nuevo producto</h3>
				</div>
				<div class="panel-body">
					<legend class="text-right">
						<label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}">
							<span class="glyphicon" ng-class="{'glyphicon-plus': accion == 'insert', 'glyphicon glyphicon-pencil': accion == 'update'}"></span>
							{{ accion == 'insert' ? 'INSERTAR' : 'ACTUALIZAR' }}
						</label>
					</legend>
					<form class="form-horizontal" role="form" name="formProducto">
						<div class="form-group">
							<label class="control-label col-sm-2">Nombre Producto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" ng-model="producto.producto" maxlength="45" required>
							</div>
							<label class="control-label col-sm-2" ng-show="accion!='insert'">No. Producto</label>
							<div class="col-sm-2" ng-show="accion!='insert'">
								<input type="text" class="form-control" ng-model="producto.idProducto" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Tipo Producto</label>
							<div class="col-sm-2">
								<select class="form-control" ng-model="producto.idTipoProducto" required>
									<option ng-repeat="tp in lstTipoProducto" value="{{ tp.idTipoProducto }}">
										{{ tp.tipoProducto }}
									</option>
								</select>
							</div>
							<label class="control-label col-sm-2">Tipo Medida</label>
							<div class="col-sm-2">
								<select class="form-control" ng-model="producto.idMedida" required>
									<option ng-repeat="m in lstMedidas" value="{{m.idMedida}}">{{m.medida}}</option>
								</select>
							</div>
							<label class="control-label col-sm-2">Pedecedero</label>
							<div class="col-sm-1">
								<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.perecedero, 'btn-warning':!producto.perecedero}" ng-click="producto.perecedero=!producto.perecedero">
									<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.perecedero, 'glyphicon-check' : producto.perecedero}"></span>
									{{ producto.perecedero ? 'SI' : 'NO' }}
								</button>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Cantidad minima</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="producto.cantidadMinima" required>
							</div>
							<label class="control-label col-sm-2">Cantidad maxima</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="producto.cantidadMaxima">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Disponibilidad</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="producto.disponibilidad" ng-disabled="accion!='insert'">
							</div>
							<label class="control-label col-sm-2">Producto Importante</label>
							<div class="col-sm-1">
								<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.importante, 'btn-warning':!producto.importante}" ng-click="producto.importante=!producto.importante">
									<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.importante, 'glyphicon-check' : producto.importante}"></span>
									{{ producto.importante ? 'SI' : 'NO' }}
								</button>
							</div>
						</div>
						<div class="text-center">
							<button type="button" class="btn btn-success" ng-click="registraNuevoProducto()">Guardar</button>
							<button type="button" class="btn btn-danger" ng-click="cancelaNuevoProducto()">Cancelar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- formulario nuevo menu -->
		<div class="col-sm-10 col-sm-offset-1" ng-show="evento=='menu'">
			<br>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Registrar nuevo menu</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" name="formMenu">
						<div class="form-group">
							<label class="control-label col-sm-2">Nombre Menu</label>
							<div class="col-sm-4 ">
								<input type="text" class="form-control" ng-model="menu.menu" maxlength="45" required>
							</div>
							<label class="control-label col-sm-2">Destino Menu</label>
							<div class="col-sm-2">
								<select class="form-control" ng-model="menu.idDestinoMenu" required>
									<option ng-repeat="dm in lstDestinoMenu" value="{{dm.idDestinoMenu}}">{{dm.destinoMenu}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2" ng-show="id>0">Estado Menu</label>
							<div class="col-sm-2" ng-show="id>0">
								<select class="form-control" ng-model="menu.idEstadoMenu" >
									<option ng-repeat="e in lstEstadoMenu" value="{{e.idEstadoMenu}}">{{e.estadoMenu}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Descripcion</label>
							<div class="col-sm-8">
								<textarea rows="2" class="form-control" ng-model='menu.descripcion' required></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Tipo Servicio</label>
							<div class="col-sm-3">
								<select class="form-control" ng-model="tipoServicio">
									<option  ng-repeat="ts in lstTipoServicio" value="{{ts.idTipoServicio}}">{{ts.tipoServicio}}</option>
								</select>
							</div>
							<label class="col-sm-1">Precio</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="precioMenu">
							</div>
							<button type="button" class="btn btn-primary" ng-click="agregaPrecio(tipoServicio,precioMenu)">Agregar</button>
							<div class="col-sm-12">
								<br>
								<div class="col-sm-2"></div>
								<div class="col-sm-7">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>Tipo Servicio</th>
												<th>Q. Precio</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr ng-repeat="lp in menu.lstPrecios">
												<td>{{lp.tipoServicio}}</td>
												<td>{{lp.precio}}</td>
												<td>
													<button type="button" class="btn btn-danger btn-xs" ng-click="removerPrecio($index)">X</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="text-center">
							<button type="button" class="btn btn-success" ng-click="registraMenu()">Guardar</button>
							<button type="button" class="btn btn-danger" ng-click="menu={}">Cancelar</button>
						</div>
					</form>
				</div>
				<b>Imagen del menu</b>
				<div class="panel-body">
					<div class="form-group">
						<div class="col-sm-8">
							<input type="file" class="form-control" id="imagenMenu">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- formulario combo -->
		<div class="col-sm-10 col-sm-offset-1" ng-show="evento=='combo'">
			<br>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Registrar nuevo combo</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" name="formCombo">
						<div class="form-group">
							<label class="control-label col-sm-2">Nombre Combo</label>
							<div class="col-sm-4 ">
								<input type="text" class="form-control" ng-model="combo.combo" maxlength="45" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Imagen</label>
							<div class="col-sm-4">
								<input type="file" class="form-control" id="comboImagen">
							</div>
							<label class="col-sm-2" ng-show="id>0">Estado Menu</label>
							<div class="col-sm-2" ng-show="id>0">
								<select class="form-control" ng-model="combo.idEstadoMenu" >
									<option ng-repeat="e in lstEstadoMenu" value="{{e.idEstadoMenu}}">{{e.estadoMenu}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Descripcion</label>
							<div class="col-sm-6">
								<textarea rows="3" class="form-control" ng-model='combo.descripcion'></textarea>
							</div>
						</div>
						<div class="text-center">
							<button type="button" class="btn btn-success" ng-click="registraCombo()">Guardar</button>
							<button type="button" class="btn btn-danger" ng-click="combo={}">Cancelar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>