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
					<span class="hidden-xs">Finalizados</span>
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

<!-- Administrar Orden -->
<script type="text/ng-template" id="dial.orden.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content panel-primary">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                    Seleccione Menú
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-md-3 col-sm-4 col-xs-6 text-center" ng-repeat="item in lstMenu">
                			<button type="button" class="menu-btn" ng-click="seleccionarMenu( item )">
	                			<img ng-src="img/{{item.img}}">
	                			<span>{{item.menu}}</span>
                			</button>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-log-out"></span>
                        <b>Salir</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 

<!-- Menú Seleccionado-Cantidad -->
<script type="text/ng-template" id="dial.menu-cantidad.html">
    <div class="modal bs-example-modal-lg" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content panel-info">
                <div class="modal-header panel-heading">
                	<button type="button" class="close" ng-click="$hide()">&times;</button>
                    <b>Ingrese Cantidad Menú</b>
                </div>
                <div class="modal-body">
                	<div class="row">
                		<div class="col-xs-6 text-center">
                			<img ng-src="img/{{menuActual.img}}">
                			<h4>{{menuActual.menu}}</h4>
                		</div>
                		<div class="col-xs-6">
                			<div class="col-xs-12">
	                			<label>Cantidad</label>
	                			<input type="number" class="form-control input-lg" ng-model="menuActual.cantidad" min="1" style="font-weight:bold;">
                			</div>
                			<div class="col-xs-12" style="margin-top:5px">
                				<button type="button" class="btn btn-sm btn-default" ng-click="menuActual.cantidad=menuActual.cantidad-1" ng-disabled="!(menuActual.cantidad>1)">
                					<span class="glyphicon glyphicon-minus"></span>
                				</button>
                				<button type="button" class="btn btn-sm btn-primary" ng-click="menuActual.cantidad=menuActual.cantidad+1">
                					<span class="glyphicon glyphicon-plus"></span>
                				</button>
                			</div>
                		</div>
                   </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-success" ng-click="agregarAPedido()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                        <b>Agregar a Orden</b>
                    </button>
                    <button type="button" class="btn btn-default" ng-click="$hide()">
                        <span class="glyphicon glyphicon-remove"></span>
                        <b>Cancelar</b>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script> 