<div class="container">
	<div class="row">
		<div class="col-sm-12 text-center">
			<div class="btn-group">
				<button class="btn btn-default" ng-click="menu=1">
					<span class="glyphicon glyphicon-user"></span> Nuevo Cliente
				</button>
				<button class="btn btn-default" ng-click="menu=2"> 
					<span class="glyphicon glyphicon-list"></span> Adm. Cliente
				</button>
			</div>
		</div>
		<div class="col-sm-8 col-sm-offset-2" ng-show="menu==1">
			<!-- agregar nuevo colaborador -->
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Nuevo Cliente</h3>
				</div>
				<div class="panel-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2">Nit</label>
							<div class="col-sm-3 has-success">
								<input type="number"  ng-model="cliente.nit" class="form-control"  maxlength="15" autofocus>
							</div> 
						</div>
						<div class="form-group">
							<label class="col-sm-2">Nombre</label>
							<div class="col-sm-8 has-success">
								<input type="text" class="form-control" ng-model="cliente.nombre" maxlength="65">
							</div>
						</div>
						<div class="form-group has-success">
							<label class="col-sm-2">Cui(DPI)</label>
							<div class="col-sm-3">
								<input type="number" class="form-control"  maxlength="13" ng-model="cliente.cui">
							</div>
							<label class="col-sm-2">Correo</label>
							<div class="col-sm-3">
								<input type="text" class="form-control"  maxlength="65" ng-model="cliente.correo">
							</div>
						</div>
						<div class="form-group has-success">
							<label class="col-sm-2">Telefono</label>
							<div class="col-sm-3">
								<input type="number" class="form-control"  maxlength="8" ng-model="cliente.telefono">
							</div>
							<label class="col-sm-2">Tipo Cliente</label>
							<div class="col-sm-3">
								<select class="form-control" ng-model="cliente.idTipoCliente">
									<option ng-repeat="tc in lstTipoCliente" value="{{tc.idTipoCliente}}">{{tc.tipoCliente}}</option>
								</select>
							</div>
							
						</div>
						<div class="form-group has-success">
							<label class="col-sm-2">Dirección</label>
							<div class="col-sm-8">
								<input type="text" class="form-control"  maxlength="95" ng-model="cliente.direccion">
							</div>
						</div>
						<div class="col-sm-12 text-center">
							<button class="btn btn-success" ng-click="guardarCliente()">
								<span class="glyphicon glyphicon-saved"></span> Guardar
							</button>
							<button class="btn btn-default" ng-click="cancelarCliente()"> 
								<span class="glyphicon glyphicon-log-out"></span> Cancelar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>