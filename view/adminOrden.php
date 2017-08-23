<div class="col-xs-12" style="margin-top:5px">
	<div class="row">
		<div class="col-sm-8 col-xs-12">
			<div class="btn-orden">
				<button class="bt-info" ng-class="{'active':idEstadoOrden==1}" ng-click="idEstadoOrden=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs">Pendientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':idEstadoOrden==2}" ng-click="idEstadoOrden=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs">En Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':idEstadoOrden==3}" ng-click="idEstadoOrden=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs">Finalizados</span>
				</button>
				<button class="bt-danger" ng-class="{'active':idEstadoOrden==10}" ng-click="idEstadoOrden=10">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs">Cancelados</span>
				</button>
			</div>
		</div>
		<div class="col-sm-3 col-xs-12">
			<div class="btn-group" role="group">
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='ticket', 'btn-default':tipoVista!='ticket'}" 
					ng-click="tipoVista='ticket'">
					<span class="glyphicon glyphicon-user"></span>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='ambos', 'btn-default':tipoVista!='ambos'}" 
					ng-click="tipoVista='ambos'">
					<b>Ambos</b>
				</button>
				<button type="button" class="btn" ng-class="{'btn-primary':tipoVista=='menu', 'btn-default':tipoVista!='menu'}" 
					ng-click="tipoVista='menu'">
					<span class="glyphicon glyphicon-list-alt"></span>
				</button>
			</div>
		</div>
	</div>
	<div class="row" style="margin-top:7px">
		<!-- VISTA POR TICKET -->
		<div ng-class="{'col-sm-6':tipoVista=='ambos','col-sm-12':tipoVista=='ticket'}" ng-hide="tipoVista=='menu'">
			<div class="panel panel-default" ng-repeat="menu in lstMenus track by $index">
				<div class="panel-heading col-xs-12">
					<h3 class="panel-title">
						<div class="col-xs-6">
							<button type="button" class="btn" ng-click="$parent.ixMenuActual=$index" 
								ng-class="{'btn-default':!(difMinutos( menu.primerTiempo )>minutosAlerta),
									'btn-danger':difMinutos( menu.primerTiempo )>minutosAlerta}">
								<span class="glyphicon glyphicon-chevron-down"></span>
								{{menu.menu}} <span class="badge">{{menu.numMenus}}</span>
							</button>
						</div>
						<div class="col-xs-6 text-right">
							<button type="button" class="btn btn-default">
								<span class="glyphicon glyphicon-check"></span>
							</button>
							<button type="button" class="btn btn-default">
								<span class="glyphicon glyphicon-unchecked"></span>
							</button>
						</div>
					</h3>
				</div>
				<div class="panel-body" ng-show="ixMenuActual==$index">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th></th>
										<th>Tipo Servicio</th>
										<th>Lapso</th>
										<th>Combo</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="item in menu.detalle track by $index" 
										ng-class="{'success':item.selected, 'tr_alert':difMinutos( item.fechaRegistro )>minutosAlerta}"
										ng-click="item.selected=!item.selected">
										<td><img ng-src="{{menu.imagen}}" style="height:15px"></td>
										<td>
											<span class="label-border" ng-class="{'btn-success':item.idTipoServicio==2, 'btn-warning':item.idTipoServicio==3, 'btn-primary':item.idTipoServicio==1}">
	                                            <span ng-show="item.idTipoServicio==2">R</span>
	                                            <span ng-show="item.idTipoServicio==3">D</span>
	                                            <span ng-show="item.idTipoServicio==1">L</span>
	                                        </span>
											{{item.tipoServicio}}
										</td>
										<td>
											<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
										</td>
										<td>
											<span class="label label-warning" ng-show="item.perteneceCombo">
												<span class="glyphicon glyphicon-gift"></span>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- VISTA POR MENU -->
		<div ng-class="{'col-sm-6':tipoVista=='ambos','col-sm-12':tipoVista=='menu'}" ng-hide="tipoVista=='ticket'">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Menú</th>
							<th>Tipo Servicio</th>
							<th>Lapso</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in lstOrdenes" ng-click="item.selected=!item.selected;accionItems()" ng-class="{'success':item.selected}">
							<td><img ng-src="{{item.imagen}}" style="height:35px"></td>
							<td>
								<span class="glyphicon glyphicon-gift" ng-show="item.perteneceCombo"></span>
								{{item.menu}}
							</td>
							<td>{{item.tipoServicio}}</td>
							<td>
								<span>{{tiempoTranscurrido( item.fechaRegistro )}}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="acciones" ng-show="seleccion.si">
		<button type="button" class="btn btn-success">
			<span class="glyphicon glyphicon-play"></span>
			<b>Preparar</b>
			<span class="badge">{{seleccion.count}}</span>
		</button>
	</div>
</div>


