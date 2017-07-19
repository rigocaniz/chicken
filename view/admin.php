<div class="container">
	<div class="row">
		<!-- Definir botones de inventario menu -->
		<div class="col-sm-12 text-center">
			<div class="btn-group">
				<button class="btn btn-default" ng-click="btnMenu=1;listaMenu()">
					<span class="glyphicon glyphicon-list"></span> Menus
				</button>
				<button class="btn btn-default" ng-click="btnMenu=2;listaCombo()">
					<span class="glyphicon glyphicon-share-alt"></span> Combos
				</button>
			</div>
		</div>
		<!--  menus -->
		<div class="col-sm-12" ng-show="btnMenu==1">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Menus</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7"></div>
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/menu">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
					<div class="row">
						<br>
					  <div class="col-sm-3" ng-repeat="m in lstMenus |filter:filtro">
					    <div class="thumbnail">
					    	<span class="label label-info">{{m.destinoMenu}}</span>
					      <img src="upload/Logo.png" alt="...">
					      <div class="caption">
					        <p>
					        	<strong>{{m.menu}}</strong> 
					        	<span class="label label-success">{{m.estadoMenu}}</span>
					        </p> 
					        <p>{{m.descripcion}}</p>
					        <p>
					        	<a ng-href="#/nuevoEdita/menu/{{m.idMenu}}" type="button" class="btn btn-primary btn-sm">
									<span class="glyphicon glyphicon-edit"></span>
								</a>
							</p>
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<!-- combos -->
		<div class="col-sm-12" ng-show="btnMenu==2">
			<br>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Menus</h3>
				</div>
				<div class="panel-body">
					<div class="col-sm-3">
						<input type="text" class="form-control" ng-model="filtro" placeholder="buscar">
					</div>
					<div class="col-sm-7"></div>
						<a type="button" class="btn btn-primary" ng-href="#/nuevoEdita/combo">
							<span class="glyphicon glyphicon-plus"></span> Ingresar Nuevo
						</a>
					<div class="row">
						<br>
					  <div class="col-sm-3" ng-repeat="c in lstCombos |filter:filtro">
					    <div class="thumbnail">
					      <img src="upload/Logo.png" alt="...">
					      <div class="caption">
					        <p>
					        	<strong>{{c.combo}}</strong> 
					        	<span class="label label-success">{{c.estadoMenu}}</span>
					        </p> 
					        <p>{{c.descripcion}}</p>
					        <p>
					        	<a ng-href="#/nuevoEdita/combo/{{c.idCombo}}" type="button" class="btn btn-primary btn-sm">
									<span class="glyphicon glyphicon-edit"></span>
								</a>
							</p>
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>