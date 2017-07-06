<div class="container">
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
				<button class="btn btn-default" ng-click="inventarioMenu=4;"> 
					<span class="glyphicon glyphicon-check"></span> Ingresos
				</button>
			</div>
		</div>
		<!--  tabla de porductos existentes -->
		<div class="col-sm-12" ng-show="inventarioMenu==0 || inventarioMenu==1">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Inventario de productos</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7"></div>
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/insert">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
					
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Tipo Producto</th>
								<th>Medida</th>
								<th>Perecedero</th>
								<th>Disponibilidad</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="inv in lstInventario |filter:filtro">
								<td>{{inv.producto}}</td>
								<td>{{inv.tipoProducto}}</td>
								<td>{{inv.medida}}</td>
								<td>{{inv.esPerecedero}}</td>
								<td>{{inv.disponibilidad}}</td>
								<td>
									<a ng-href="#/nuevoEdita/update/{{inv.idProducto}}" type="button" class="btn btn-primary btn-sm">
										<span class="glyphicon glyphicon-edit"></span>
									</a>
									<span class="label label-warning" ng-show="inv.disponibilidad==inv.cantidadMninima">Pronto a agotarse</span>
								</td>
							</tr>
						</tbody>
					</table>
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
</div>
<!-- dialogo de edición de producto -->
