<div style="margin:5px;">
	<div class="row">
		<div class="col-sm-12" style="margin-bottom:4px">
			<button type="button" class="btn btn-success">
				<span class="glyphicon glyphicon-plus"></span>
				Nueva Orden
			</button>
			<button type="button" class="btn btn-info">
				<span class="glyphicon glyphicon-search"></span>
				Buscar Orden
			</button>
		</div>
		<hr class="col-sm-12" style="margin:9px 40px">
		<div class="col-sm-12">
			<div class="btn-orden" ng-init="tab=1">
				<button class="bt-info" ng-class="{'active':tab==1}" ng-click="tab=1">
					<span class="glyphicon glyphicon-time"></span>
					<span class="hidden-xs">Pendientes</span>
				</button>
				<button class="bt-success" ng-class="{'active':tab==2}" ng-click="tab=2">
					<span class="glyphicon glyphicon-play"></span>
					<span class="hidden-xs">En Progreso</span>
				</button>
				<button class="bt-primary" ng-class="{'active':tab==3}" ng-click="tab=3">
					<span class="glyphicon glyphicon-flag"></span>
					<span class="hidden-xs">Finalizadas</span>
				</button>
				<button class="bt-danger" ng-class="{'active':tab==4}" ng-click="tab=4">
					<span class="glyphicon glyphicon-remove"></span>
					<span class="hidden-xs">Cancelados</span>
				</button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-info" style="margin-top: 4px">
				<div class="panel-body">
					<table class="table table-condensed table-hover">
						<thead>
							<tr>
								<th># Orden</th>
								<th>Menú ({{tab}})</th>
								<th>Lapso</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>321</td>
								<td>Pollo Frito</td>
								<td>Hace 5 minutos</td>
							</tr>
							<tr>
								<td>123</td>
								<td>Papas fritas</td>
								<td>Hace 4 minutos</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>