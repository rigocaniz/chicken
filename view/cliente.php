<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<!-- TABS -->
			<ul class="nav nav-tabs tabs-title" role="tablist">
				<li role="presentation">
					<a href="#/">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li role="presentation" ng-class="{'active' : $parent.clienteMenu==1}" ng-click="$parent.clienteMenu=1;$parent.cliente={}">
					<a href="" role="tab" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span> NUEVO CLIENTE
					</a>
				</li>
			</ul>
			<!-- CONTENIDO TABS -->
			<div class="tab-content">
				<!-- BUSCAR CLIENTE -->
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="row">
							<label class="col-xs-12 col-sm-3 col-md-2">BUSCAR CLIENTE</label>
							<div class="col-xs-9 col-sm-6 col-md-5">
								<input type="text" class="form-control" ng-model="$parent.txtCliente" ng-keypress="$event.keyCode == 13 && $parent.buscarCliente( $parent.txtCliente, 1 )" placeholder="NIT / DPI / NOMBRE" capitalize>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-2">
								<button class="btn btn-sm btn-primary" ng-click="$parent.buscarCliente( $parent.txtCliente, 1 )">
									<span class="glyphicon glyphicon-search"></span> Buscar
								</button>
							</div>
						</div>
					</div>
				</div>

				<div role="tabpanel" class="tab-pane" ng-class="{'active' : $parent.clienteMenu==1}" ng-show="$parent.clienteMenu==1">
					<div class="panel panel-primary">
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="form-group">
									<div class="pull-right">
										<label class="label" ng-class="{'label-success': accion == 'insert', 'label-info': accion == 'update'}" style="font-size: 15px;">
											{{ accion == 'insert' ? 'AGREGAR' : 'ACTUALIZAR' }} CLIENTE
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nit</label>
									<div class="col-sm-3">
										<input type="text" ng-model="$parent.cliente.nit" class="form-control" id="nit" maxlength="15" autofocus>
									</div> 
								</div>
								<div class="form-group">
									<label class="col-sm-2">Nombre</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" ng-model="$parent.cliente.nombre" maxlength="65" capitalize>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Cui (DPI)</label>
									<div class="col-sm-3">
										<input type="text" ng-pattern="/^[0-9]+?$/" ng-trim="false" class="form-control" maxlength="13" ng-model="$parent.cliente.cui" capitalize>
									</div>
									<label class="col-sm-2">Correo</label>
									<div class="col-sm-3">
										<input type="email" class="form-control"  maxlength="65" ng-model="$parent.cliente.correo" capitalize>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2">Telefono</label>
									<div class="col-sm-3">
										<input type="number" class="form-control"  max="99999999" ng-model="$parent.cliente.telefono" capitalize>
									</div>
									<label class="col-sm-2">Tipo Cliente</label>
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
									<label class="col-sm-2">Dirección</label>
									<div class="col-sm-8">
										<input type="text" class="form-control"  maxlength="95" ng-model="$parent.cliente.direccion" capitalize>
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button class="btn btn-success" ng-click="$parent.guardarCliente()">
										<span class="glyphicon glyphicon-saved"></span> {{ accion == 'insert' ? 'Guardar' : 'Actualizar' }} cliente
									</button>
									<button class="btn btn-default" ng-click="$parent.cancelarCliente()"> 
										<span class="glyphicon glyphicon-log-out"></span> Cancelar
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