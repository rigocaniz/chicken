<div class="container">
	<div class="row">
		<!-- formulario de registro nuevo producto -->
		<div class="col-sm-12">
			<br>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Registrar nuevo producto</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" name="formProducto">
						<div class="form-group">
							<label class="col-sm-2">Nombre Producto</label>
							<div class="col-sm-4 has-success">
								<input type="text" class="form-control" ng-model="producto.producto" maxlength="45" required>
							</div>
						</div>
						<div class="form-group has-success">
							<label class="col-sm-2">Tipo Producto</label>
							<div class="col-sm-2">
								<select class="form-control" ng-model="producto.idTipoProducto" required>
									<option ng-repeat="tp in lstTipoProducto" value="{{idTipoProducto}}">{{tp.tipoProducto}}</option>
								</select>
							</div>
							<label class="col-sm-2">Tipo Medida</label>
							<div class="col-sm-2">
								<select class="form-control" ng-model="producto.idMedida" required>
									<option ng-repeat="m in lstMedidas" value="{{m.idMedida}}">{{m.medida}}</option>
								</select>
							</div>
							<label class="col-sm-1">Pedecedero</label>
							<div class="col-sm-1">
								<button type="button" class="btn btn-sm" ng-class="{'btn-success': producto.perecedero, 'btn-warning':!producto.perecedero}" ng-click="producto.perecedero=!producto.perecedero">
									<span class="glyphicon" ng-class="{'glyphicon-unchecked' : !producto.perecedero, 'glyphicon-check' : producto.perecedero}"></span>
									{{ producto.perecedero ? 'SI' : 'NO' }}
								</button>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2">Cantidad minima</label>
							<div class="col-sm-2 has-success">
								<input type="number" class="form-control" ng-model="producto.cantidadMinima" required>
							</div>
							<label class="col-sm-2">Cantidad maxima</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="producto.cantidadMaxima">
							</div>
						</div>
						<div class="form-group has-success">
							<label class="col-sm-2">Disponibilidad</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" ng-model="producto.disponibilidad" required>
							</div>
							<label class="col-sm-2">Producto Importante</label>
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
	</div>
</div>