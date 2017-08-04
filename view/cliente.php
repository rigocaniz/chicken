<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<!-- TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : clienteMenu==1}" ng-click="clienteMenu=1">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> NUEVO CLIENTE
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : clienteMenu==2}" ng-click="clienteMenu=2">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> ADMIN CLIENTE
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!--  NUEVO CLIENTE -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : clienteMenu==1}" ng-show="clienteMenu==1">
					<div class="panel panel-primary">
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
				<!-- ADMIN DE CLIENTES-->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : clienteMenu==2}" ng-show="clienteMenu==2">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-body">
								<div class="row">
									<label class="col-sm-1 col-md-2">Cliente</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" ng-model="txtCliente" placeholder="NIT / DPI / NOMBRE">
									</div>
									<div class="col-sm-4 col-md-3">
										<button class="btn btn-sm btn-primary" ng-click="buscarCliente(txtCliente)">
											<span class="glyphicon glyphicon-search"></span> Buscar
										</button>
									</div>
								</div>
							</div>
						</div>
						<!-- listado busqueda por nombre -->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">CLIENTES ENCONTRADOS</h3>
							</div>
							<div class="panel-body">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>CLIENTE</th>
											<th>NIT</th>
											<th>DPI</th>
											<th>DIRECCION</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>