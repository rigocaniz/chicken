<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<!-- TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation" ng-class="{'active' : $parent.clienteMenu==1}" ng-click="$parent.clienteMenu=1;$parent.cliente={}">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> NUEVO CLIENTE
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : $parent.clienteMenu==2}" ng-click="$parent.clienteMenu=2;txtCliente=''">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-list"></span> ADMIN CLIENTE
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!--  NUEVO CLIENTE -->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.clienteMenu==1}" ng-show="$parent.clienteMenu==1">
					<div class="panel panel-primary">
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-2">Nit</label>
									<div class="col-sm-3 has-success">
										<input type="text"  ng-model="$parent.cliente.nit" class="form-control"  maxlength="15" autofocus>
									</div> 
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nombre</label>
									<div class="col-sm-8 has-success">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65">
									</div>
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Cui(DPI)</label>
									<div class="col-sm-3">
										<input type="number" class="form-control" max="9999999999999" ng-model="$parent.cliente.cui">
									</div>
									<label class="col-sm-2">Correo</label>
									<div class="col-sm-3">
										<input type="text" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo">
									</div>
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Telefono</label>
									<div class="col-sm-3">
										<input type="number" class="form-control"  max="99999999" ng-model="$parent.cliente.telefono">
									</div>
									<label class="col-sm-2">Tipo Cliente</label>
									<div class="col-sm-3">
										<select class="form-control" ng-model="$parent.cliente.idTipoCliente">
											<option ng-repeat="tc in lstTipoCliente" value="{{tc.idTipoCliente}}">{{tc.tipoCliente}}</option>
										</select>
									</div>	
								</div>
								<div class="form-group has-success">
									<label class="col-sm-2">Dirección</label>
									<div class="col-sm-8">
										<input type="text" class="form-control"  maxlength="95" ng-model="$parent.cliente.direccion">
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button class="btn btn-success" ng-click="$parent.guardarCliente()">
										<span class="glyphicon glyphicon-saved"></span> Guardar
									</button>
									<button class="btn btn-default" ng-click="$parent.cancelarCliente()"> 
										<span class="glyphicon glyphicon-log-out"></span> Cancelar
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- ADMIN DE CLIENTES-->
				<div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.clienteMenu==2}" ng-show="$parent.clienteMenu==2">
					<div class="col-sm-offset-1 col-sm-10">
						<div class="panel panel-primary">
							<div class="panel-body">
								<div class="row">
									<label class="col-sm-1 col-md-2">Cliente</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" ng-model="parent.txtCliente" placeholder="NIT / DPI / NOMBRE">
									</div>
									<div class="col-sm-4 col-md-3">
										<button class="btn btn-sm btn-primary" ng-click="$parent.buscarCliente($parent.txtCliente,1)">
											<span class="glyphicon glyphicon-search"></span> Buscar
										</button>
									</div>
								</div>
							</div>
						</div>
						<!-- listado busqueda por nombre -->
						<div class="panel panel-info" ng-show="$parent.masEncontrados==1">
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
										<tr ng-repeat="en in $parent.lstClienteEncontrado">
											<td>{{en.nombre}}</td>
											<td>{{en.nit}}</td>
											<td>{{en.cui}}</td>
											<td>{{en.direccion}}</td>
											<td>
												<button type="button" class="btn btn-info btn-sm" ng-click="$parent.editarCliente(en)">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
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
	</div>
</div>